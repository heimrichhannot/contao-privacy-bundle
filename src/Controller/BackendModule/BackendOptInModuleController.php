<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\Controller\BackendModule;

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Environment;
use Contao\Input;
use Contao\Message;
use Contao\RequestToken;
use Contao\StringUtil;
use Contao\System;
use Contao\Validator;
use Contao\Widget;
use HeimrichHannot\NotificationCenterPlus\MessageModel;
use HeimrichHannot\UtilsBundle\Salutation\SalutationUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

/**
 * @Route("/contao/privacy_backend_opt_in",
 *     name=BackendOptInModuleController::class,
 *     defaults={"_scope": "backend"}
 * )
 */
class BackendOptInModuleController extends AbstractController
{
    protected ContaoFramework $framework;

    protected SalutationUtil $salutationUtil;

    private TwigEnvironment $twig;

    public function __construct(TwigEnvironment $twig, ContaoFramework $framework, SalutationUtil $salutationUtil)
    {
        $this->twig = $twig;
        $this->framework = $framework;
        $this->salutationUtil = $salutationUtil;
    }

    public function __invoke(Request $request): Response
    {
        $this->framework->getAdapter(Controller::class)->loadDataContainer('tl_privacy_backend');
        $this->framework->getAdapter(System::class)->loadLanguageFile('tl_privacy_backend');
        $this->framework->getAdapter(System::class)->loadLanguageFile('default');

        if ('tl_privacy_backend' === $request->request->get('FORM_SUBMIT')) {
            if ($email = $request->request->get('email')) {
                if (!Validator::isEmail($email)) {
                    Message::addError(sprintf($GLOBALS['TL_LANG']['tl_privacy_backend']['invalidEmail'], $email));
                    Controller::reload();
                } else {
                    $this->sendEmail($request);
                }
            }
        }

        $dca = &$GLOBALS['TL_DCA']['tl_privacy_backend'];
        $templateData = [
        ];

        $templateData['message'] = Message::generate();
        $templateData['href'] = Controller::getReferer();
        $templateData['title'] = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
        $templateData['button'] = $GLOBALS['TL_LANG']['MSC']['backBT'];
        $templateData['refererId'] = TL_REFERER_ID;
        $templateData['requestToken'] = RequestToken::get();

        // add fields
        $fields = [];
        $lang = $request->request->get('language') ?: ($GLOBALS['TL_LANGUAGE'] ?: 'de');

        foreach ($dca['fields'] as $field => $data) {
            switch ($field) {
                case 'language':

                    $widget = $this->getBackendFormField($field, $data, $lang);
                    break;

                default:
                    $widget = $this->getBackendFormField($field, $data);

                    break;
            }

            $fields[$field] = $widget;
        }

        $templateData['fields'] = array_map(function ($field) {
            return $field->parse();
        }, $fields);

        // form actions
        $templateData['formAction'] = Environment::get('request');

        return new Response($this->twig->render(
            '@HeimrichHannotPrivacy/module/privacy_backend_opt_in.html.twig',
            $templateData
        ));
    }

    protected function getBackendFormField($field, array $dca, $value = null)
    {
        if (!($class = $GLOBALS['BE_FFL'][$dca['inputType']])) {
            return false;
        }

        return new $class(Widget::getAttributesFromDca($dca, $field, $value));
    }

    protected function sendEmail(Request $request)
    {
        $lang = $request->request->get('language');
        if (!Validator::isLanguage($lang)) {
            $lang = $GLOBALS['TL_LANGUAGE'] ?: 'de';
        }

        $dca = &$GLOBALS['TL_DCA']['tl_privacy_backend'];
        $backendConfig = StringUtil::deserialize(Config::get('privacyOptInNotifications'), true);
        $notification = null;
        $jumpTo = null;

        foreach ($backendConfig as $config) {
            if ($config['language'] === $lang) {
                $notification = $config['notification'];
                $jumpTo = $config['jumpTo'];
            }
        }

        if (!$notification || !$jumpTo) {
            Message::addError($GLOBALS['TL_LANG']['MSC']['huhPrivacy']['messageNoBackendOptInConfigFound']);
            Controller::reload();
        }

        if (null !== ($message = MessageModel::findPublishedById($notification))) {
            $tmpLang = $GLOBALS['TL_LANGUAGE'];

            $GLOBALS['TL_LANGUAGE'] = $lang;

            $tokens = [
                'salutation_submission' => $this->salutationUtil->createSalutation(
                    $lang,
                    $_POST
                ),
            ];

            $data = [];
            $dataForInsertTag = [];

            foreach ($request->request->all() as $field => $value) {
                $tokens['form_'.$field] = Input::get($field);

                if (!isset($dca['fields'][$field]['eval']['skipForJwtToken']) || !$dca['fields'][$field]['eval']['skipForJwtToken']) {
                    $data[$field] = $value;
                    $dataForInsertTag[] = "$field:$value";
                }
            }

            $dataString = implode('#', $dataForInsertTag);

            $tokens['opt_in_url'] = Controller::replaceInsertTags(
                "{{privacy_opt_url::$dataString::$jumpTo}}",
                false
            );

            $message->send($tokens, $lang);

            $GLOBALS['TL_LANGUAGE'] = $tmpLang;

            Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['tl_privacy_backend']['emailSentSuccessfully'], $data['email']));
            Controller::reload();
        }
    }
}
