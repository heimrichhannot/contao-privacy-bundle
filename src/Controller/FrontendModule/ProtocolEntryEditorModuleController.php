<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\Controller\FrontendModule;

use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\System;
use Contao\Template;
use Firebase\JWT\JWT;
use HeimrichHannot\FormHybrid\FormHelper;
use HeimrichHannot\PrivacyBundle\Form\ProtocolEntryForm;
use HeimrichHannot\PrivacyBundle\HeimrichHannotPrivacyBundle;
use HeimrichHannot\StatusMessages\StatusMessage;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(ProtocolEntryEditorModuleController::TYPE,category="privacy")
 */
class ProtocolEntryEditorModuleController extends AbstractFrontendModuleController
{
    const TYPE = 'protocol_entry_editor';

    /**
     * @var ModelUtil
     */
    protected $modelUtil;

    /**
     * @var \HeimrichHannot\RequestBundle\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var ContaoFramework
     */
    protected $framework;

    public function __construct(
        ModelUtil $modelUtil,
        ContaoFramework $framework,
        \HeimrichHannot\RequestBundle\Component\HttpFoundation\Request $request
    ) {
        $this->modelUtil = $modelUtil;
        $this->request = $request;
        $this->framework = $framework;
    }

    protected function getResponse(Template $template, ModuleModel $module, Request $request): ?Response
    {
        $decoded = $this->getDataFromJwtToken($module);

        if ($module->privacyAutoSubmit) {
            $formId = FormHelper::getFormId($module->formHybridDataContainer, $module->id);

            if ($module->useCustomFormId) {
                $formId = $module->customFormId;
            }

            // TODO replace by request bundle after formhybrid bundle creation
            \HeimrichHannot\Request\Request::setPost('FORM_SUBMIT', $formId);
        }

        if (\is_array($decoded)) {
            $this->setDefaultValuesFromToken($decoded, $module);
        }

        $module->formHybridAddPrivacyProtocolEntry = true;

        $form = new ProtocolEntryForm($module);
        $template->form = $form->generate();

        return $template->getResponse();
    }

    protected function getDataFromJwtToken(ModuleModel $module)
    {
        if (!($token = $this->request->getGet(HeimrichHannotPrivacyBundle::OPT_IN_OUT_TOKEN_PARAM)) || !$this->request->getGet(HeimrichHannotPrivacyBundle::OPT_ACTION_PARAM)) {
            if ($module->privacyRestrictToJwt) {
                StatusMessage::addError($GLOBALS['TL_LANG']['MSC']['huhPrivacy']['messageNoJwtToken'], $module->id);
            }

            return false;
        }

        try {
            $decoded = JWT::decode($token, System::getContainer()->getParameter('contao.encryption_key'), ['HS256']);
            $decoded = (array) $decoded;
            $decoded['data'] = (array) $decoded['data'];
        } catch (\Exception $e) {
            StatusMessage::addError($GLOBALS['TL_LANG']['MSC']['huhPrivacy']['optInTokenInvalid'], $module->id);

            return false;
        }

        return $decoded;
    }

    protected function setDefaultValuesFromToken(array $decoded, ModuleModel $module)
    {
        if (!isset($decoded['data']) || !\is_array($decoded['data'])) {
            return;
        }

        $table = $module->formHybridDataContainer;

        $this->framework->getAdapter(Controller::class)->loadDataContainer($table);
        $this->framework->getAdapter(System::class)->loadLanguageFile($table);

        $dca = &$GLOBALS['TL_DCA'][$table];

        foreach ($decoded['data'] as $field => $value) {
            if ($module->privacyAutoSubmit) {
                \HeimrichHannot\Request\Request::setPost($field, $value);
            } else {
                $dca['fields'][$field]['default'] = $value;
            }
        }
    }
}
