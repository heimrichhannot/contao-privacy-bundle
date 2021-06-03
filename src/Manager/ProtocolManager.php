<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\Manager;

use Contao\BackendUser;
use Contao\ContentElement;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Environment;
use Contao\FrontendUser;
use Contao\Model;
use Contao\Module;
use Contao\ModuleModel;
use Contao\System;
use HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer;
use HeimrichHannot\PrivacyBundle\Model\ProtocolArchiveModel;
use HeimrichHannot\PrivacyBundle\Model\ProtocolEntryModel;
use HeimrichHannot\PrivacyBundle\Util\ProtocolUtil;
use HeimrichHannot\UtilsBundle\Dca\DcaUtil;
use HeimrichHannot\UtilsBundle\String\StringUtil;

class ProtocolManager
{
    protected DcaUtil      $dcaUtil;
    protected StringUtil   $stringUtil;
    protected ProtocolUtil $protocolUtil;

    public function __construct(
        DcaUtil $dcaUtil,
        StringUtil $stringUtil,
        ProtocolUtil $protocolUtil
    ) {
        $this->dcaUtil = $dcaUtil;
        $this->stringUtil = $stringUtil;
        $this->protocolUtil = $protocolUtil;
    }

    public function addEntryFromContentElement($type, $archive, array $data, ContentElement $element, $packageName = '')
    {
        $data['element'] = $element->id;
        $data['elementType'] = $element->type;

        $this->addEntry($type, $archive, $data, $packageName);
    }

    /**
     * Adds a new protocol entry from the scope of a module.
     *
     * @param string             $type
     * @param int                $archive
     * @param Module|ModuleModel $module
     * @param string             $packageName
     */
    public function addEntryFromModule($type, $archive, array $data, $module, $packageName = '')
    {
        $data['module'] = $module->id;
        $data['moduleType'] = $module->type;
        $data['moduleName'] = $module->name;

        $this->addEntry($type, $archive, $data, $packageName);
    }

    public function addEntry($type, $archive, array $data, $packageName = '', $skipFields = ['id', 'tstamp', 'dateAdded', 'pid', 'type'])
    {
        if (null === ($protocolArchive = ProtocolArchiveModel::findByPk($archive))) {
            return false;
        }

        $allowedPersonalFields = \Contao\StringUtil::deserialize($protocolArchive->personalFields, true);
        $allowedCodeFields = \Contao\StringUtil::deserialize($protocolArchive->codeFields, true);

        Controller::loadDataContainer('tl_privacy_protocol_entry');

        $dca = &$GLOBALS['TL_DCA']['tl_privacy_protocol_entry'];

        $protocolEntry = new ProtocolEntryModel();
        $protocolEntry->tstamp = $protocolEntry->dateAdded = time();
        $protocolEntry->pid = $archive;
        $protocolEntry->type = $type;
        $stackTrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 4);
        $relevantStackEntry = [];

        // compute stacktrace entry containing the relevant function call
        if (!empty($stackTrace)) {
            $classMethods = get_class_methods('HeimrichHannot\PrivacyBundle\Manager\ProtocolManager');

            foreach ($stackTrace as $index => $entry) {
                if (!$this->stringUtil->endsWith($entry['file'], 'ProtocolManager.php') || !\in_array($entry['function'], $classMethods)) {
                    $relevantStackEntry = $entry;

                    if (isset($stackTrace[$index + 1]['function'])) {
                        $relevantStackEntry['function'] = $stackTrace[$index + 1]['function'];
                    }

                    break;
                }
            }
        }

        foreach ($dca['fields'] as $field => $fieldData) {
            if (!\in_array($field, $allowedPersonalFields) && isset($fieldData['eval']['personalField']) && $fieldData['eval']['personalField']) {
                continue;
            }

            if ((!\in_array($field, $allowedCodeFields) || !$protocolArchive->addCodeProtocol) && isset($fieldData['eval']['codeField'])
                && $fieldData['eval']['codeField']) {
                continue;
            }

            switch ($field) {
                case 'ip':
                    if (Environment::get('remoteAddr')) {
                        $protocolEntry->ip = System::anonymizeIp(Environment::get('ip'));
                    }

                    break;

                case 'cmsScope':
                    if (TL_MODE == 'FE') {
                        $protocolEntry->cmsScope = ProtocolEntryContainer::CMS_SCOPE_FRONTEND;

                        if (null !== ($member = FrontendUser::getInstance()) && $member->id) {
                            $protocolEntry->authorType = DcaUtil::AUTHOR_TYPE_MEMBER;
                            $protocolEntry->author = $member->id;
                        }
                    } elseif (TL_MODE == 'BE') {
                        $protocolEntry->cmsScope = ProtocolEntryContainer::CMS_SCOPE_BACKEND;

                        if (null !== ($user = BackendUser::getInstance()) && $user->id) {
                            $protocolEntry->authorType = DcaUtil::AUTHOR_TYPE_USER;
                            $protocolEntry->author = $user->id;
                        }
                    }

                    break;

                case 'url':
                    $protocolEntry->url = \Environment::get('url').'/'.\Environment::get('request');

                    break;

                case 'bundle':
                    $protocolEntry->bundle = $packageName;

                    break;

                case 'bundleVersion':
                    if (!$packageName) {
                        continue 2;
                    }

                    $path = TL_ROOT.'/composer/composer.lock';

                    if (!file_exists($path)) {
                        $path = TL_ROOT.'/composer.lock';
                    }

                    if (!file_exists($path)) {
                        continue 2;
                    }

                    $composerLock = file_get_contents($path);

                    if (!$composerLock) {
                        continue 2;
                    }

                    try {
                        $composerLock = json_decode($composerLock, true);

                        foreach ($composerLock['packages'] as $package) {
                            if (isset($package['name']) && $package['name'] === $packageName) {
                                if (isset($package['version'])) {
                                    $protocolEntry->bundleVersion = $package['version'];
                                }

                                break;
                            }
                        }
                    } catch (\Exception $e) {
                        // silently fail
                    }

                    break;

                case 'codeFile':
                    if (!empty($relevantStackEntry)) {
                        $protocolEntry->codeFile = $relevantStackEntry['file'];
                    }

                    break;

                case 'codeLine':
                    if (!empty($relevantStackEntry)) {
                        $protocolEntry->codeLine = $relevantStackEntry['line'];
                    }

                    break;

                case 'codeFunction':
                    if (!empty($relevantStackEntry)) {
                        $protocolEntry->codeFunction = $relevantStackEntry['function'];
                    }

                    break;

                case 'codeStacktrace':
                    $protocolEntry->codeStacktrace = (new \Exception())->getTraceAsString();

                    break;

                case 'dataContainer':
                    // provide backward compability to implementations with table (version 1.x)
                    if (isset($data['table'])) {
                        $protocolEntry->dataContainer = $data['table'];
                    }
            }

            // $data always has the highest priority
            if (isset($data[$field]) && !\in_array($field, $skipFields)) {
                $protocolEntry->{$field} = $data[$field];
            }
        }

        $protocolEntry->save();

        // set reference field
        if ($protocolArchive->addReferenceEntity) {
            $modelClass = Model::getClassFromTable($protocolArchive->referenceFieldTable);

            if (class_exists($modelClass)) {
                $set = [];
                $justCreated = false;

                $instance = $modelClass::findBy([$protocolArchive->referenceFieldTable.'.'.$protocolArchive->referenceFieldForeignKey.'=?'],
                    [$protocolEntry->{$protocolArchive->referenceFieldProtocolForeignKey}]);

                if (null === $instance && $protocolArchive->createInstanceOnChange) {
                    $justCreated = true;

                    $set['tstamp'] = $set['dateAdded'] = time();

                    $dbFields = Database::getInstance()->getFieldNames($protocolArchive->referenceFieldTable);

                    foreach ($data as $field => $value) {
                        if (!\in_array($field, $dbFields)) {
                            continue;
                        }

                        $set[$field] = $value;
                    }
                }

                if (isset($GLOBALS['TL_HOOKS']['privacy_initReferenceModelOnProtocolChange']) && \is_array($GLOBALS['TL_HOOKS']['privacy_initReferenceModelOnProtocolChange'])) {
                    foreach ($GLOBALS['TL_HOOKS']['privacy_initReferenceModelOnProtocolChange'] as $callback) {
                        if (\is_array($callback)) {
                            System::importStatic($callback[0])->{$callback[1]}($set, $protocolEntry, $data);
                        } elseif (\is_callable($callback)) {
                            $callback($set, $protocolEntry, $data);
                        }
                    }
                }

                if ($protocolArchive->referenceTimestampField) {
                    $set[$protocolArchive->referenceTimestampField] = time();
                }

                if ($protocolArchive->addEntryTypeToReferenceFieldOnChange) {
                    $set[$protocolArchive->referenceEntryTypeField] = $protocolEntry->type;
                }

                // store with Database since the entity might use DC_Multilingual
                if ($justCreated) {
                    Database::getInstance()->prepare(
                        'INSERT INTO '.$protocolArchive->referenceFieldTable.' %s'
                    )->set($set)->execute();
                } else {
                    Database::getInstance()->prepare(
                        'UPDATE '.$protocolArchive->referenceFieldTable.' %s WHERE '.$protocolArchive->referenceFieldTable.'.id=?'
                    )->set($set)->execute($instance->id);
                }
            }
        }

        return $protocolEntry;
    }

    public function updateReferenceEntity($protocolArchive, $data, $editableFields, $context)
    {
        if (null === ($protocolArchive = ProtocolArchiveModel::findByPk($protocolArchive))) {
            return null;
        }

        $instance = null;

        if ($data) {
            $instance = $this->protocolUtil->findReferenceEntity(
                $protocolArchive->referenceFieldTable,
                $protocolArchive->referenceFieldForeignKey,
                $data->{$protocolArchive->referenceFieldForeignKey}
            );
        }

        if (null === $instance) {
            return null;
        }

        $changedFields = [];
        $set = [];

        foreach ($editableFields as $field) {
            if (\in_array($field, ['id', 'tstamp', 'pid', 'dateAdded'])) {
                continue;
            }

            if ($instance->{$field} != $data->{$field}) {
                $changedFields[$field] = [
                    'old' => $instance->{$field},
                    'new' => $data->{$field},
                ];
            }

            $set[$field] = $data->{$field};
        }

        if (isset($GLOBALS['TL_HOOKS']['privacy_afterUpdateReferenceEntity']) && \is_array($GLOBALS['TL_HOOKS']['privacy_afterUpdateReferenceEntity'])) {
            foreach ($GLOBALS['TL_HOOKS']['privacy_afterUpdateReferenceEntity'] as $callback) {
                \System::importStatic($callback[0])->{$callback[1]}($set, $data, $changedFields, $context);
            }
        }

        Database::getInstance()->prepare(
            'UPDATE '.$protocolArchive->referenceFieldTable.' %s WHERE '.$protocolArchive->referenceFieldTable.'.id=?'
        )->set($set)->execute($instance->id);

        return $instance;
    }

    public function deleteReferenceEntity($protocolArchive, $data)
    {
        if (null === ($protocolArchive = ProtocolArchiveModel::findByPk($protocolArchive))) {
            return false;
        }

        $instance = null;

        if ($data) {
            $instance = $this->protocolUtil->findReferenceEntity(
                $protocolArchive->referenceFieldTable,
                $protocolArchive->referenceFieldForeignKey,
                $data->{$protocolArchive->referenceFieldForeignKey}
            );
        }

        if (null === $instance) {
            return false;
        }

        $data = $instance->row();

        $data['dataContainer'] = $protocolArchive->referenceFieldTable;

        if ('tl_member' == $protocolArchive->referenceFieldTable) {
            $data['member'] = $instance->id;
        }

        // delete entity
        return $instance->delete();
    }

    /**
     * @param string|null $label A custom label
     *
     * @return array
     */
    public function getSelectorFieldDca($label = null)
    {
        if (!$label || !\is_string($label)) {
            $label = $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['addPrivacyProtocolEntry'];
        }

        return [
            'label' => $label,
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 clr', 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ];
    }

    public function getArchiveFieldDca()
    {
        return [
            'label' => $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolEntryArchive'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_privacy_protocol_archive.title',
            'eval' => ['tl_class' => 'w50 clr', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ];
    }

    public function getTypeFieldDca()
    {
        System::loadLanguageFile('tl_privacy_protocol_entry');

        return [
            'label' => $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolEntryType'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options' => ProtocolEntryContainer::TYPES,
            'reference' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_entry']['reference'],
            'eval' => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
            'sql' => "varchar(32) NOT NULL default ''",
        ];
    }

    public function getDescriptionFieldDca()
    {
        return [
            'label' => $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolEntryDescription'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['tl_class' => 'long clr'],
            'sql' => 'text NULL',
        ];
    }

    public function getFieldMappingFieldDca($tableField)
    {
        return [
            'label' => $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolFieldMapping'],
            'inputType' => 'multiColumnEditor',
            'eval' => [
                'tl_class' => 'long clr',
                'multiColumnEditor' => [
                    'minRowCount' => 0,
                    'fields' => [
                        'entityField' => [
                            'label' => &$GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolFieldMapping_entityField'],
                            'inputType' => 'select',
                            'options_callback' => function (DataContainer $dc) use ($tableField) {
                                if (!$dc->activeRecord->{$tableField}) {
                                    return [];
                                }

                                return $this->dcaUtil->getFields($dc->activeRecord->{$tableField});
                            },
                            'exclude' => true,
                            'eval' => [
                                'includeBlankOption' => true,
                                'chosen' => true,
                                'tl_class' => 'w50',
                                'mandatory' => true,
                                'style' => 'width: 400px',
                            ],
                        ],
                        'protocolField' => [
                            'label' => &$GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolFieldMapping_protocolField'],
                            'inputType' => 'select',
                            'options_callback' => [ProtocolUtil::class, 'getFieldsAsOptions'],
                            'exclude' => true,
                            'eval' => [
                                'includeBlankOption' => true,
                                'chosen' => true,
                                'tl_class' => 'w50',
                                'mandatory' => true,
                                'style' => 'width: 400px',
                            ],
                        ],
                    ],
                ],
            ],
            'sql' => 'blob NULL',
        ];
    }

    public function getTextualFieldMappingFieldDca()
    {
        return [
            'label' => $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolFieldMapping'],
            'inputType' => 'multiColumnEditor',
            'eval' => [
                'tl_class' => 'long clr',
                'multiColumnEditor' => [
                    'minRowCount' => 0,
                    'fields' => [
                        'entityField' => [
                            'label' => &$GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolFieldMapping_entityField'],
                            'inputType' => 'text',
                            'eval' => [
                                'includeBlankOption' => true,
                                'tl_class' => 'w50',
                                'mandatory' => true,
                                'style' => 'width: 400px',
                            ],
                        ],
                        'protocolField' => [
                            'label' => &$GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolFieldMapping_protocolField'],
                            'inputType' => 'select',
                            'options_callback' => [ProtocolUtil::class, 'getFieldsAsOptions'],
                            'exclude' => true,
                            'eval' => [
                                'includeBlankOption' => true,
                                'chosen' => true,
                                'tl_class' => 'w50',
                                'mandatory' => true,
                                'style' => 'width: 400px',
                            ],
                        ],
                    ],
                ],
            ],
            'sql' => 'blob NULL',
        ];
    }

    public function getNotificationFieldDca()
    {
        return [
            'label' => $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolNotification'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'select',
            'options_callback' => [\HeimrichHannot\FormHybrid\Backend\Module::class, 'getNoficiationMessages'],
            'eval' => ['chosen' => true, 'maxlength' => 255, 'tl_class' => 'w50 clr', 'includeBlankOption' => true, 'mandatory' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ];
    }

    public function getActivationJumpToFieldDca()
    {
        return [
            'label' => $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['privacyProtocolActivationJumpTo'],
            'exclude' => true,
            'inputType' => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval' => ['fieldType' => 'radio', 'tl_class' => 'w50', 'mandatory' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
        ];
    }
}
