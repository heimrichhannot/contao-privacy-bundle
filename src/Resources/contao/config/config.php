<?php

/**
 * Backend modules
 */
array_insert(
    $GLOBALS['BE_MOD'],
    1,
    [
        'privacy' => [
            'privacy_opt_in' => [
                'callback' => 'HeimrichHannot\PrivacyBundle\Module\ModuleBackendOptIn',
                'icon'     => 'system/modules/privacy/assets/img/icon_email.png',
            ],
            'protocols'      => [
                'tables' => ['tl_privacy_protocol_archive', 'tl_privacy_protocol_entry'],
                'icon'   => 'system/modules/privacy/assets/img/icon_protocol.png',
            ],
        ],
    ]
);

if (class_exists('\HeimrichHannot\ContaoExporterBundle\HeimrichHannotContaoExporterBundle')) {
    $GLOBALS['BE_MOD']['privacy']['protocols']['export_csv'] = ['huh.exporter.action.backendexport', 'export'];
    $GLOBALS['BE_MOD']['privacy']['protocols']['export_xls'] = ['huh.exporter.action.backendexport', 'export'];
}

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_privacy_protocol_archive'] = 'HeimrichHannot\PrivacyBundle\Model\ProtocolArchiveModel';
$GLOBALS['TL_MODELS']['tl_privacy_protocol_entry']   = 'HeimrichHannot\PrivacyBundle\Model\ProtocolEntryModel';

/**
 * Notifications
 */
$backendOptInType = System::getContainer()->get(\HeimrichHannot\UtilsBundle\Dca\DcaUtil::class)
    ->getNewNotificationTypeArray(true);

$backendOptInType['email_text'][] = 'opt_in_url';
$backendOptInType['email_text'][] = 'salutation_submission';
$backendOptInType['email_html'][] = 'opt_in_url';
$backendOptInType['email_html'][] = 'salutation_submission';

foreach ($backendOptInType as $strField => $arrTokens) {
    $backendOptInType[$strField] = array_unique(array_merge(['form_*'], $arrTokens));
}

System::getContainer()->get(\HeimrichHannot\UtilsBundle\Dca\DcaUtil::class)->activateNotificationType(
    \HeimrichHannot\PrivacyBundle\DataContainer\NotificationContainer::NOTIFICATION_TYPE_PRIVACY,
    \HeimrichHannot\PrivacyBundle\DataContainer\NotificationContainer::NOTIFICATION_TYPE_PRIVACY_OPT_IN_FORM,
    $backendOptInType
);

/**
 * Permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'privacy_protocols';
$GLOBALS['TL_PERMISSIONS'][] = 'privacy_protocolp';
