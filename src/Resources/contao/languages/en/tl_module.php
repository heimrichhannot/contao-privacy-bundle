<?php

$lang = &$GLOBALS['TL_LANG']['tl_module'];

/**
 * Fields
 */
$lang['privacyRestrictToJwt'][0]                       = 'Display error messages, if form is called without JWT token.';
$lang['privacyRestrictToJwt'][1]                       = 'Choose this option if form is not public, therefore should not be used without JWT token.';
$lang['privacyAutoSubmit'][0]                          = 'Instantly send form';
$lang['privacyAutoSubmit'][1]                          = 'Choose this option if the form should not be visible. The form will be submitted instantly (Caution: check that no mandatory field is empty).';
$lang['privacyAddReferenceEntity'][0]                  = 'Add reference entity';
$lang['privacyAddReferenceEntity'][1]                  = 'Choose this option to effect reference entites (e.g. update fields or delete on opt-out).';
$lang['privacyUpdateReferenceEntityFields'][0]         = 'Update fields of reference entity with input';
$lang['privacyUpdateReferenceEntityFields'][1]         = 'Choose this option to update the reference entity with form values.';
$lang['privacyDeleteReferenceEntityAfterOptAction'][0] = 'Delete reference entity after opt';
$lang['privacyDeleteReferenceEntityAfterOptAction'][1] = 'Choose this option to delete the reference entity after opt.';


/**
 * Legends
 */
$lang['notification_legend'] = 'Notifications';
$lang['privacy_legend']      = 'Privacy';

/**
 * Reference
 */
$lang['huhPrivacy']['reference'] = [
];