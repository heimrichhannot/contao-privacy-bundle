<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$protocolManager = System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class);

$GLOBALS['TL_DCA']['tl_privacy_protocol_config'] = [
    'config' => [
        'dataContainer' => 'Table',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list' => [
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'sorting' => [
            'mode' => 1,
            'fields' => ['title'],
            'headerFields' => ['title'],
            'panelLayout' => 'filter;search,limit',
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_config']['edit'],
                'href' => 'table=tl_privacy_protocol_entry',
                'icon' => 'edit.gif',
            ],
            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_config']['editheader'],
                'href' => 'act=edit',
                'icon' => 'header.gif',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_config']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_config']['copy'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null)
                    .'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_config']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],
    'palettes' => [
        'default' => '{general_legend},title;{config_legend},dataContainer,archive,entryType,description,notification,fieldMapping;',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_config']['tstamp'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'dateAdded' => [
            'label' => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
            'sorting' => true,
            'flag' => 6,
            'eval' => ['rgxp' => 'datim', 'doNotCopy' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'dataContainer' => [
            'inputType' => 'select',
            'label' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_config']['dataContainer'],
            'eval' => [
                'chosen' => true,
                'submitOnChange' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w50',
                'mandatory' => true,
            ],
            'exclude' => true,
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'archive' => $protocolManager->getArchiveFieldDca(),
        'entryType' => $protocolManager->getTypeFieldDca(),
        'description' => $protocolManager->getDescriptionFieldDca(),
        'notification' => [
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => ['NotificationCenter\tl_module', 'getNotificationChoices'],
            'eval' => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'hasOne', 'load' => 'lazy', 'table' => 'tl_nc_notification'],
        ],
        'fieldMapping' => [
            'sql' => 'blob NULL',
        ],
    ],
];
