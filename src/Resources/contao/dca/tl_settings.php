<?php

$dca = &$GLOBALS['TL_DCA']['tl_settings'];

/**
 * Palettes
 */
$dca['palettes']['default'] .= ';{huh_privacy_legend},privacyProtocolCallbacks,privacyProtocolFieldMapping,privacyOptInNotifications,privacyOptInJumpTo;';

/**
 * Fields
 */
$fields = [
    'privacyProtocolCallbacks'    => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolCallbacks'],
        'inputType' => 'multiColumnEditor',
        'eval'      => [
            'tl_class'          => 'long clr',
            'multiColumnEditor' => [
                'minRowCount' => 0,
                'fields'      => [
                    'table'    => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolTable'],
                        'inputType'        => 'select',
                        'options_callback' => ['HeimrichHannot\Haste\Dca\General', 'getDataContainers'],
                        'eval'             => ['mandatory' => true, 'includeBlankOption' => true, 'style' => 'width: 180px', 'chosen' => true],
                    ],
                    'callback' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolCallback'],
                        'inputType' => 'select',
                        'options'   => ['oncreate_callback', 'onversion_callback', 'ondelete_callback'],
                        'eval'      => ['mandatory' => true, 'includeBlankOption' => true, 'style' => 'width: 140px'],
                    ],
                    'cmsScope' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['cmsScope'],
                        'inputType' => 'select',
                        'options'   => array_merge(
                            [\HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BOTH],
                            \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPES
                        ),
                        'reference' => &$GLOBALS['TL_LANG']['tl_settings']['reference']['huhPrivacy'],
                        'eval'      => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'style' => 'width: 75px'],
                    ],
                    'archive'  => [
                        'label'      => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolArchive'],
                        'inputType'  => 'select',
                        'foreignKey' => 'tl_privacy_protocol_archive.title',
                        'eval'       => ['mandatory' => true, 'includeBlankOption' => true, 'chosen' => true, 'style' => 'width: 200px'],
                    ],
                    'type'     => $GLOBALS['TL_DCA']['tl_privacy_protocol_entry']['fields']['type'],
                ],
            ],
        ],
    ],
    'privacyProtocolFieldMapping' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolFieldMapping'],
        'inputType' => 'multiColumnEditor',
        'eval'      => [
            'tl_class'          => 'long clr',
            'multiColumnEditor' => [
                'minRowCount' => 0,
                'fields'      => [
                    'table'         => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolTable'],
                        'inputType'        => 'select',
                        'options_callback' => ['HeimrichHannot\Haste\Dca\General', 'getDataContainers'],
                        'eval'             => ['mandatory' => true, 'includeBlankOption' => true, 'style' => 'width: 250px', 'chosen' => true],
                    ],
                    'entityField'   => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolEntityField'],
                        'inputType' => 'text',
                        'eval'      => [
                            'mandatory' => true,
                            'style'     => 'width: 250px'
                        ],
                    ],
                    'protocolField' => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolField'],
                        'inputType'        => 'select',
                        'options_callback' => [\HeimrichHannot\PrivacyBundle\Util\ProtocolUtil::class, 'getFieldsAsOptions'],
                        'exclude'          => true,
                        'eval'             => [
                            'includeBlankOption' => true,
                            'chosen'             => true,
                            'tl_class'           => 'w50',
                            'mandatory'          => true,
                            'style'              => 'width: 250px'
                        ],
                    ]
                ],
            ],
        ],
    ],
    'privacyOptInNotifications'       => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyOptInNotifications'],
        'inputType' => 'multiColumnEditor',
        'eval'      => [
            'tl_class' => 'long clr',
            'multiColumnEditor' => [
                'minRowCount' => 0,
                'fields' => [
                    'language' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyOptInLanguage'],
                        'exclude'   => true,
                        'inputType' => 'select',
                        'options'   => System::getLanguages(true),
                        'eval'      => [
                            'rgxp'               => 'locale',
                            'tl_class'           => 'w50',
                            'includeBlankOption' => true,
                            'mandatory'          => true,
                            'skipForJwtToken'    => true,
                            'groupStyle' => 'width: 200px'
                        ]
                    ],
                    'notification'           => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_settings']['privacyOptInNotification'],
                        'exclude'          => true,
                        'search'           => true,
                        'inputType'        => 'select',
                        'options_callback' => ['HeimrichHannot\FormHybrid\Backend\Module', 'getNoficiationMessages'],
                        'eval'             => ['chosen' => true, 'maxlength' => 255, 'tl_class' => 'w50 clr',
                                               'includeBlankOption' => true, 'mandatory' => true, 'groupStyle' => 'width: 400px']
                    ],
                    'jumpTo' => [
                        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['privacyOptInJumpTo'],
                        'exclude'                 => true,
                        'inputType'               => 'pageTree',
                        'foreignKey'              => 'tl_page.title',
                        'eval'                    => ['fieldType'=>'radio', 'tl_class' => 'w50', 'mandatory' => true, 'groupStyle' => 'width: 200px'],
                        'relation'                => ['type'=>'hasOne', 'load'=>'lazy']
                    ],
                ],
            ],
        ],
        'sql'       => "blob NULL",
    ],
];

$fields['privacyProtocolCallbacks']['eval']['multiColumnEditor']['fields']['type']['eval']['style'] = 'width: 200px';
unset($fields['privacyProtocolCallbacks']['eval']['multiColumnEditor']['fields']['type']['label'][1]);
unset($fields['privacyProtocolCallbacks']['eval']['multiColumnEditor']['fields']['cmsScope']['label'][1]);

$dca['fields'] += $fields;
