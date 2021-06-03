<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\Module;

use Contao\Controller;
use Contao\Message;
use Contao\StringUtil;
use Contao\System;
use Contao\Validator;
use HeimrichHannot\Haste\Util\Salutations;
use HeimrichHannot\Haste\Util\Url;
use HeimrichHannot\Haste\Util\Widget;
use HeimrichHannot\NotificationCenterPlus\MessageModel;
use HeimrichHannot\Request\Request;

class ModuleBackendOptIn extends \BackendModule
{
    protected $strTemplate = 'be_privacy_opt_in';

    public function generate()
    {
        System::loadLanguageFile('tl_privacy_backend');
        Controller::loadDataContainer('tl_privacy_backend');

        if ('tl_privacy_backend' === Request::getPost('FORM_SUBMIT')) {
            if ($email = Request::getPost('email')) {
                if (!Validator::isEmail($email)) {
                    Message::addError(sprintf($GLOBALS['TL_LANG']['tl_privacy_backend']['invalidEmail'], $email));
                    Controller::redirect(Url::getCurrentUrl());
                } else {
                    $this->sendEmail();
                }
            }
        }

        return parent::generate();
    }

    protected function compile()
    {
        $dca = &$GLOBALS['TL_DCA']['tl_privacy_backend'];

        $this->Template->message = Message::generate();
        $this->Template->href = $this->getReferer(true);
        $this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];

        // add fields
        $fields = [];
        $lang = Request::getPost('language') ?: ($GLOBALS['TL_LANGUAGE'] ?: 'de');

        foreach ($dca['fields'] as $field => $data) {
            switch ($field) {
                case 'language':
                    $widget = Widget::getBackendFormField($field, $data, $lang);

                    break;

                default:
                    $widget = Widget::getBackendFormField($field, $data);

                    break;
            }

            $fields[$field] = $widget;
        }

        $this->Template->fields = $fields;

        // form actions
        $this->Template->formAction = \Environment::get('request');
    }

    protected function sendEmail()
    {
        $dca = &$GLOBALS['TL_DCA']['tl_privacy_backend'];
        $lang = Request::getPost('language') ?: ($GLOBALS['TL_LANGUAGE'] ?: 'de');
        $backendConfig = StringUtil::deserialize(\Config::get('privacyOptInNotifications'), true);
        $notification = null;
        $jumpTo = null;

        foreach ($backendConfig as $config) {
            if ($config['language'] === $lang) {
                $notification = $config['notification'];
                $jumpTo = $config['jumpTo'];
            }
        }

        if (!$notification || !$jumpTo) {
            \Message::addError($GLOBALS['TL_LANG']['MSC']['huhPrivacy']['messageNoBackendOptInConfigFound']);
            Controller::redirect(Url::getCurrentUrl());
        }

        if (null !== ($message = MessageModel::findPublishedById($notification))) {
            $tmpLang = $GLOBALS['TL_LANGUAGE'];

            $GLOBALS['TL_LANGUAGE'] = $lang;

            $tokens = [
                'salutation_submission' => Salutations::createSalutation(
                    $lang,
                    $_POST
                ),
            ];

            $data = [];
            $dataForInsertTag = [];

            foreach (Request::getInstance()->request as $field => $value) {
                $tokens['form_'.$field] = $value;

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
            Controller::redirect(Url::getCurrentUrl());
        }
    }
}
