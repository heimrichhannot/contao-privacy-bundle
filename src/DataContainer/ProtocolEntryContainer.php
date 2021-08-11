<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\DataContainer;

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;
use HeimrichHannot\PrivacyBundle\Model\ProtocolArchiveModel;
use HeimrichHannot\PrivacyBundle\Model\ProtocolEntryModel;
use HeimrichHannot\UtilsBundle\Dca\DcaUtil;
use HeimrichHannot\UtilsBundle\Driver\DC_Table_Utils;
use HeimrichHannot\UtilsBundle\Form\FormUtil;

class ProtocolEntryContainer
{
    const TYPE_FIRST_OPT_IN = 'first_opt_in';
    const TYPE_SECOND_OPT_IN = 'second_opt_in';
    const TYPE_FIRST_OPT_OUT = 'first_opt_out';
    const TYPE_SECOND_OPT_OUT = 'second_opt_out';
    const TYPE_OPT_IN = 'opt_in';
    const TYPE_OPT_OUT = 'opt_out';
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';

    const TYPES = [
        self::TYPE_FIRST_OPT_IN,
        self::TYPE_FIRST_OPT_IN,
        self::TYPE_SECOND_OPT_IN,
        self::TYPE_FIRST_OPT_OUT,
        self::TYPE_SECOND_OPT_OUT,
        self::TYPE_OPT_IN,
        self::TYPE_OPT_OUT,
        self::TYPE_CREATE,
        self::TYPE_UPDATE,
        self::TYPE_DELETE,
    ];

    const CMS_SCOPE_BACKEND = 'BE';
    const CMS_SCOPE_FRONTEND = 'FE';

    // tl_settings only
    const CMS_SCOPE_BOTH = 'both';

    const CMS_SCOPES = [
        self::CMS_SCOPE_BACKEND,
        self::CMS_SCOPE_FRONTEND,
    ];

    /**
     * @var DcaUtil
     */
    protected $dcaUtil;

    /**
     * @var FormUtil
     */
    protected $formUtil;

    public function __construct(DcaUtil $dcaUtil, FormUtil $formUtil)
    {
        $this->dcaUtil = $dcaUtil;
        $this->formUtil = $formUtil;
    }

    /**
     * @Callback(table="tl_privacy_protocol_entry", target="fields.dataContainer.options")
     */
    public function getDataContainers()
    {
        return $this->dcaUtil->getDataContainers();
    }

    /**
     * @Callback(table="tl_privacy_protocol_entry", target="list.sorting.child_record")
     */
    public function listChildren($row)
    {
        $title = $row['id'];

        if (null !== ($protocolEntry = \HeimrichHannot\PrivacyBundle\Model\ProtocolEntryModel::findByPk($row['id']))
            && null !== ($protocolArchive = $protocolEntry->getRelated('pid'))) {
            $dca = &$GLOBALS['TL_DCA']['tl_privacy_protocol_entry'];

            $dc = new DC_Table_Utils('tl_privacy_protocol_entry');
            $dc->activeRecord = $protocolEntry;
            $dc->id = $protocolEntry->id;

            $title = preg_replace_callback(
                '@%([^%]+)%@i',
                function ($arrMatches) use ($protocolEntry, $dca, $dc) {
                    return $this->formUtil->prepareSpecialValueForOutput(
                        $arrMatches[1],
                        $protocolEntry->{$arrMatches[1]},
                        $dc
                    );
                },
                $protocolArchive->titlePattern
            );
        }

        return '<div class="tl_content_left">'.$title.' <span style="color:#b3b3b3; padding-left:3px">['.\Date::parse(
                Config::get('datimFormat'),
                trim($row['dateAdded'])
            ).']</span></div>';
    }

    /**
     * @Callback(table="tl_privacy_protocol_entry", target="config.onload")
     */
    public function modifyDca(DataContainer $dc)
    {
        Controller::loadDataContainer('tl_privacy_protocol_entry');
        $dca = &$GLOBALS['TL_DCA']['tl_privacy_protocol_entry'];

        if (TL_MODE == 'BE') {
            // fields
            if (null === ($protocolEntry = ProtocolEntryModel::findByPk($dc->id))) {
                return false;
            }

            if (null === ($protocolArchive = ProtocolArchiveModel::findByPk($protocolEntry->pid))) {
                return false;
            }

            $allowedPersonalFields = StringUtil::deserialize($protocolArchive->personalFields, true);
            $allowedCodeFields = StringUtil::deserialize($protocolArchive->codeFields, true);

            foreach ($dca['fields'] as $field => $fieldData) {
                $isPersonalField = isset($fieldData['eval']['personalField']) && $fieldData['eval']['personalField'];
                $isCodeField = isset($fieldData['eval']['codeField']) && $fieldData['eval']['codeField'];

                if ($isPersonalField) {
                    $class = $dca['fields'][$field]['eval']['tl_class'].' personal-data';

                    $dca['fields'][$field]['eval']['tl_class'] = $class;
                }

                if (!\in_array($field, $allowedPersonalFields) && $isPersonalField) {
                    unset($dca['fields'][$field]);
                }

                if ((!\in_array($field, $allowedCodeFields) || !$protocolArchive->addCodeProtocol) && $isCodeField) {
                    unset($dca['fields'][$field]);
                }
            }
        }
    }

    public static function addPersonalPrivacyProtocolFieldsToDca($table)
    {
        if (!Database::getInstance()->tableExists('tl_privacy_protocol_entry')) {
            return;
        }

        Controller::loadDataContainer('tl_privacy_protocol_entry');
        System::loadLanguageFile('tl_privacy_protocol_entry');

        Controller::loadDataContainer($table);
        System::loadLanguageFile($table);

        $dca = &$GLOBALS['TL_DCA'][$table];

        foreach ($GLOBALS['TL_DCA']['tl_privacy_protocol_entry']['fields'] as $field => $data) {
            if (!isset($data['eval']['personalField']) || !$data['eval']['personalField']) {
                continue;
            }

            $dca['fields'][$field] = $data;
        }
    }

    public static function getPersonalFieldsPalette()
    {
        if (!Database::getInstance()->tableExists('tl_privacy_protocol_entry')) {
            return '';
        }

        Controller::loadDataContainer('tl_privacy_protocol_entry');
        System::loadLanguageFile('tl_privacy_protocol_entry');

        $paletteFields = [];

        foreach ($GLOBALS['TL_DCA']['tl_privacy_protocol_entry']['fields'] as $field => $data) {
            if (!isset($data['eval']['personalField']) || !$data['eval']['personalField']) {
                continue;
            }

            $paletteFields[] = $field;
        }

        return implode(',', $paletteFields);
    }

    /**
     * @Callback(table="tl_privacy_protocol_entry", target="config.onsubmit")
     */
    public function setDateAdded($dc)
    {
        $this->dcaUtil->setDateAdded($dc);
    }

    /**
     * @Callback(table="tl_privacy_protocol_entry", target="config.oncopy")
     */
    public function setDateAddedOnCopy($insertId, $dc)
    {
        $this->dcaUtil->setDateAddedOnCopy($insertId, $dc);
    }

    /**
     * @Callback(table="tl_privacy_protocol_entry", target="config.onload")
     */
    public function checkPermission()
    {
        $user = \Contao\BackendUser::getInstance();
        $database = \Contao\Database::getInstance();

        if ($user->isAdmin) {
            return;
        }

        // Set the root IDs
        if (!\is_array($user->privacy_protocols) || empty($user->privacy_protocols)) {
            $root = [0];
        } else {
            $root = $user->privacy_protocols;
        }

        $id = \strlen(\Contao\Input::get('id')) ? \Contao\Input::get('id') : CURRENT_ID;

        // Check current action
        switch (\Contao\Input::get('act')) {
            case 'paste':
                // Allow
                break;

            case 'create':
                if (!\strlen(\Contao\Input::get('pid')) || !\in_array(\Contao\Input::get('pid'), $root)) {
                    throw new \Exception('Not enough permissions to create privacy_protocol_entry items in privacy_protocol_entry archive ID '.\Contao\Input::get('pid').'.');
                }

                break;

            case 'cut':
            case 'copy':
                if (!\in_array(\Contao\Input::get('pid'), $root)) {
                    throw new \Exception('Not enough permissions to '.\Contao\Input::get('act').' privacy_protocol_entry item ID '.$id.' to privacy_protocol_entry archive ID '.\Contao\Input::get('pid').'.');
                }
            // no break STATEMENT HERE

            case 'edit':
            case 'show':
            case 'delete':
            case 'feature':
                $objArchive = $database->prepare('SELECT pid FROM tl_privacy_protocol_entry WHERE id=?')->limit(1)->execute($id);

                if ($objArchive->numRows < 1) {
                    throw new \Exception('Invalid privacy_protocol_entry item ID '.$id.'.');
                }

                if (!\in_array($objArchive->pid, $root)) {
                    throw new \Exception('Not enough permissions to '.\Contao\Input::get('act').' privacy_protocol_entry item ID '.$id.' of privacy_protocol_entry archive ID '.$objArchive->pid.'.');
                }

                break;

            case 'select':
                if (!\in_array($id, $root)) {
                    throw new \Exception('Not enough permissions to access privacy_protocol_entry archive ID '.$id.'.');
                }

                break;

            case 'editAll':
            case 'deleteAll':
            case 'overrideAll':
            case 'cutAll':
            case 'copyAll':
                if (!\in_array($id, $root)) {
                    throw new \Exception('Not enough permissions to access privacy_protocol_entry archive ID '.$id.'.');
                }

                $objArchive = $database->prepare('SELECT id FROM tl_privacy_protocol_entry WHERE pid=?')->execute($id);

                if ($objArchive->numRows < 1) {
                    throw new \Exception('Invalid privacy_protocol_entry archive ID '.$id.'.');
                }

                $session = \Contao\Session::getInstance();

                $sessionData = $session->getData();
                $sessionData['CURRENT']['IDS'] = array_intersect((\is_array($sessionData['CURRENT']['IDS']) ? $sessionData['CURRENT']['IDS'] : []), $objArchive->fetchEach('id'));

                try {
                    $session->setData($sessionData);
                } catch (\Exception $e) {
                }

                break;

            default:
                if (\strlen(\Contao\Input::get('act'))) {
                    throw new \Exception('Invalid command "'.\Contao\Input::get('act').'".');
                } elseif (!\in_array($id, $root)) {
                    throw new \Exception('Not enough permissions to access privacy_protocol_entry archive ID '.$id.'.');
                }

                break;
        }
    }
}
