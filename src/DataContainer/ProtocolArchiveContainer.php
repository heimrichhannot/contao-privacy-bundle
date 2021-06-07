<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\DataContainer;

use Contao\Controller;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\RequestToken;
use Contao\StringUtil;
use HeimrichHannot\UtilsBundle\Dca\DcaUtil;

class ProtocolArchiveContainer
{
    /**
     * @var DcaUtil
     */
    protected $dcaUtil;

    public function __construct(DcaUtil $dcaUtil)
    {
        $this->dcaUtil = $dcaUtil;
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="fields.referenceFieldTable.options")
     */
    public function getDataContainers()
    {
        return $this->dcaUtil->getDataContainers();
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="fields.codeFields.options")
     */
    public function getCodeFieldsAsOptions()
    {
        return $this->dcaUtil->getFields(
            'tl_privacy_protocol_entry',
            [
                'evalConditions' => [
                    'codeField' => true,
                ],
            ]
        );
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="fields.personalFields.options")
     * @Callback(table="tl_privacy_protocol_archive", target="fields.referenceFieldProtocolForeignKey.options")
     */
    public function getPersonalFieldsAsOptions(DataContainer $dc, $includeAdditionalFields = true)
    {
        $fields = $this->dcaUtil->getFields(
            'tl_privacy_protocol_entry',
            [
                'evalConditions' => [
                    'personalField' => true,
                ],
            ]
        );

        if ($includeAdditionalFields) {
            $fields += $this->dcaUtil->getFields(
                'tl_privacy_protocol_entry',
                [
                    'evalConditions' => [
                        'additionalField' => true,
                    ],
                ]
            );
        }

        asort($fields);

        return $fields;
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="config.onsubmit")
     */
    public function setDateAdded($dc)
    {
        $this->dcaUtil->setDateAdded($dc);
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="config.oncopy")
     */
    public function setDateAddedOnCopy($insertId, $dc)
    {
        $this->dcaUtil->setDateAddedOnCopy($insertId, $dc);
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="config.onload")
     */
    public function checkPermission()
    {
        $user = \Contao\BackendUser::getInstance();
        $database = \Contao\Database::getInstance();

        if ($user->isAdmin) {
            return;
        }

        // Set root IDs
        if (!\is_array($user->privacy_protocols) || empty($user->privacy_protocols)) {
            $root = [0];
        } else {
            $root = $user->privacy_protocols;
        }

        $GLOBALS['TL_DCA']['tl_privacy_protocol_archive']['list']['sorting']['root'] = $root;

        // Check permissions to add archives
        if (!$user->hasAccess('create', 'privacy_protocolp')) {
            $GLOBALS['TL_DCA']['tl_privacy_protocol_archive']['config']['closed'] = true;
        }

        /** @var \Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
        $objSession = \Contao\Session::getInstance();

        // Check current action
        switch (\Contao\Input::get('act')) {
            case 'create':
            case 'select':
                // Allow
                break;

            case 'edit':
                // Dynamically add the record to the user profile
                if (!\in_array(\Contao\Input::get('id'), $root)) {
                    /** @var \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $sessionBag */
                    $sessionBag = $objSession->getBag('contao_backend');

                    $arrNew = $sessionBag->get('new_records');

                    if (\is_array($arrNew['tl_privacy_protocol_archive']) && \in_array(\Contao\Input::get('id'), $arrNew['tl_privacy_protocol_archive'])) {
                        // Add the permissions on group level
                        if ('custom' != $user->inherit) {
                            $objGroup = $database->execute('SELECT id, privacy_protocols, privacy_protocolp FROM tl_user_group WHERE id IN('.implode(',', array_map('intval', $user->groups)).')');

                            while ($objGroup->next()) {
                                $arrModulep = StringUtil::deserialize($objGroup->privacy_protocolp);

                                if (\is_array($arrModulep) && \in_array('create', $arrModulep)) {
                                    $arrModules = StringUtil::deserialize($objGroup->privacy_protocols, true);
                                    $arrModules[] = \Contao\Input::get('id');

                                    $database->prepare('UPDATE tl_user_group SET privacy_protocols=? WHERE id=?')->execute(serialize($arrModules), $objGroup->id);
                                }
                            }
                        }

                        // Add the permissions on user level
                        if ('group' != $user->inherit) {
                            $user = $database->prepare('SELECT privacy_protocols, privacy_protocolp FROM tl_user WHERE id=?')
                                ->limit(1)
                                ->execute($user->id);

                            $arrModulep = StringUtil::deserialize($user->privacy_protocolp);

                            if (\is_array($arrModulep) && \in_array('create', $arrModulep)) {
                                $arrModules = StringUtil::deserialize($user->privacy_protocols, true);
                                $arrModules[] = \Contao\Input::get('id');

                                $database->prepare('UPDATE tl_user SET privacy_protocols=? WHERE id=?')
                                    ->execute(serialize($arrModules), $user->id);
                            }
                        }

                        // Add the new element to the user object
                        $root[] = \Contao\Input::get('id');
                        $user->privacy_protocols = $root;
                    }
                }
            // no break;

            case 'copy':
            case 'delete':
            case 'show':
                if (!\in_array(\Contao\Input::get('id'), $root) || ('delete' == \Contao\Input::get('act') && !$user->hasAccess('delete', 'privacy_protocolp'))) {
                    throw new \Exception('Not enough permissions to '.\Contao\Input::get('act').' privacy_protocol_archive ID '.\Contao\Input::get('id').'.');
                }

                break;

            case 'editAll':
            case 'deleteAll':
            case 'overrideAll':
                $session = $objSession->all();

                if ('deleteAll' == \Contao\Input::get('act') && !$user->hasAccess('delete', 'privacy_protocolp')) {
                    $session['CURRENT']['IDS'] = [];
                } else {
                    $session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
                }
                $objSession->replace($session);

                break;

            default:
                if (\strlen(\Contao\Input::get('act'))) {
                    throw new \Exception('Not enough permissions to '.\Contao\Input::get('act').' privacy_protocol_archives.');
                }

                break;
        }
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="list.operations.editheader.button")
     */
    public function editHeader($row, $href, $label, $title, $icon, $attributes)
    {
        return \Contao\BackendUser::getInstance()->canEditFieldsOf('tl_privacy_protocol_archive') ? '<a href="'.Controller::addToUrl($href.'&amp;id='.$row['id']).'&rt='.RequestToken::get().'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="list.operations.copy.button")
     */
    public function copyArchive($row, $href, $label, $title, $icon, $attributes)
    {
        return \Contao\BackendUser::getInstance()->hasAccess('create', 'privacy_protocolp') ? '<a href="'.Controller::addToUrl($href.'&amp;id='.$row['id']).'&rt='.RequestToken::get().'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }

    /**
     * @Callback(table="tl_privacy_protocol_archive", target="list.operations.delete.button")
     */
    public function deleteArchive($row, $href, $label, $title, $icon, $attributes)
    {
        return \Contao\BackendUser::getInstance()->hasAccess('delete', 'privacy_protocolp') ? '<a href="'.Controller::addToUrl($href.'&amp;id='.$row['id']).'&rt='.RequestToken::get().'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }
}
