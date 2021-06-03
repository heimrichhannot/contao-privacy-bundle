<?php

$dca = &$GLOBALS['TL_DCA']['tl_module'];

$protocolManager = System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class);

/**
 * Palettes
 */
$dca['palettes'][\HeimrichHannot\PrivacyBundle\Controller\FrontendModule\ProtocolEntryEditorModuleController::TYPE] = '
    {title_legend},name,headline,type;
    {config_legend},formHybridDataContainer,formHybridEditable,formHybridForcePaletteRelation,formHybridAddEditableRequired,formHybridAddDisplayedSubPaletteFields,formHybridResetAfterSubmission,formHybridSuccessMessage,formHybridSkipScrollingToSuccessMessage;
    {privacy_legend},privacyRestrictToJwt,privacyAutoSubmit,formHybridPrivacyProtocolArchive,formHybridPrivacyProtocolEntryType,formHybridPrivacyProtocolDescription,formHybridPrivacyProtocolFieldMapping,privacyAddReferenceEntity,formHybridAddOptIn;
    {notification_legend},formHybridSendSubmissionAsNotification,formHybridSendConfirmationAsNotification;
    {redirect_legend},jumpTo;
    {template_legend},customTpl;
    {protected_legend},protected;{expert_legend},guests,cssID';

/**
 * Subpalettes
 */
$dca['palettes']['__selector__'][]                                                            = 'privacyAddReferenceEntity';
$dca['palettes']['__selector__'][]                                                            = 'privacyDeleteReferenceEntityAfterOptAction';
$dca['palettes']['__selector__'][]                                                            = 'addOptOutDeletePrivacyProtocolEntry';
$dca['subpalettes']['privacyAddReferenceEntity']                                              = 'privacyUpdateReferenceEntityFields,privacyDeleteReferenceEntityAfterOptAction';
$dca['subpalettes']['privacyDeleteReferenceEntityAfterOptAction']                             = 'addOptOutDeletePrivacyProtocolEntry';
$dca['subpalettes']['addOptOutDeletePrivacyProtocolEntry']                                    = 'optOutDeletePrivacyProtocolArchive,optOutDeletePrivacyProtocolEntryType,optOutDeletePrivacyProtocolDescription';

/**
 * Fields
 */
$fields = [
    'privacyRestrictToJwt' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['privacyRestrictToJwt'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => ['tl_class' => 'w50'],
        'sql'                     => "char(1) NOT NULL default ''"
    ],
    'privacyAutoSubmit' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['privacyAutoSubmit'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => ['tl_class' => 'w50'],
        'sql'                     => "char(1) NOT NULL default ''"
    ],
    'privacyAddReferenceEntity'               => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['privacyAddReferenceEntity'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50', 'submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''"
    ],
    'privacyUpdateReferenceEntityFields' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['privacyUpdateReferenceEntityFields'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => ['tl_class' => 'w50'],
        'sql'                     => "char(1) NOT NULL default ''"
    ],
    'privacyDeleteReferenceEntityAfterOptAction' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['privacyDeleteReferenceEntityAfterOptAction'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => ['tl_class' => 'w50', 'submitOnChange' => true],
        'sql'                     => "char(1) NOT NULL default ''"
    ],
    'addOptOutDeletePrivacyProtocolEntry'     => $protocolManager->getSelectorFieldDca(),
    'optOutDeletePrivacyProtocolArchive'      => $protocolManager->getArchiveFieldDca(),
    'optOutDeletePrivacyProtocolEntryType'    => $protocolManager->getTypeFieldDca(),
    'optOutDeletePrivacyProtocolDescription'  => $protocolManager->getDescriptionFieldDca(),
    'optOutDeletePrivacyProtocolFieldMapping' => $protocolManager->getFieldMappingFieldDca('formHybridDataContainer')
];

$fields['addOptOutDeletePrivacyProtocolEntry']['label'][0]     .= ' (' . $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['afterDelete'] . ')';
$fields['optOutDeletePrivacyProtocolArchive']['label'][0]      .= ' (' . $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['afterDelete'] . ')';
$fields['optOutDeletePrivacyProtocolEntryType']['label'][0]    .= ' (' . $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['afterDelete'] . ')';
$fields['optOutDeletePrivacyProtocolDescription']['label'][0]  .= ' (' . $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['afterDelete'] . ')';
$fields['optOutDeletePrivacyProtocolFieldMapping']['label'][0] .= ' (' . $GLOBALS['TL_LANG']['MSC']['huhPrivacy']['afterDelete'] . ')';

$dca['fields'] = array_merge(is_array($dca['fields']) ? $dca['fields'] : [], $fields);
