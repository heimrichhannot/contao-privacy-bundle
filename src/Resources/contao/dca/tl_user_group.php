<?php

$dca = &$GLOBALS['TL_DCA']['tl_user_group'];

/**
 * Palettes
 */
$dca['palettes']['default'] = str_replace('fop;', 'fop;{privacy_legend},privacy_protocols,privacy_protocolp;', $dca['palettes']['default']);

/**
 * Fields
 */
$dca['fields']['privacy_protocols'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_user']['privacy_protocols'],
    'exclude'    => true,
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_privacy_protocol_archive.title',
    'eval'       => ['multiple' => true],
    'sql'        => "blob NULL"
];

$dca['fields']['privacy_protocolp'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['privacy_protocolp'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => ['create', 'delete'],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['multiple' => true],
    'sql'       => "blob NULL"
];