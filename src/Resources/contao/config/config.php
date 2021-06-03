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
    $GLOBALS['BE_MOD']['privacy']['protocols']['export_csv'] = System::getContainer()->get('huh.exporter.action.backendexport')->getBackendModule();
    $GLOBALS['BE_MOD']['privacy']['protocols']['export_xls'] = System::getContainer()->get('huh.exporter.action.backendexport')->getBackendModule();
}

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_privacy_protocol_archive'] = 'HeimrichHannot\PrivacyBundle\Model\ProtocolArchiveModel';
$GLOBALS['TL_MODELS']['tl_privacy_protocol_entry']   = 'HeimrichHannot\PrivacyBundle\Model\ProtocolEntryModel';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags']['privacy_addInsertTags']         = ['HeimrichHannot\PrivacyBundle\EventListener\HookListener', 'addInsertTags'];

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
