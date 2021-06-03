<?php

$lang = &$GLOBALS['TL_LANG']['tl_settings'];

/**
 * Fields
 */
$lang['privacyProtocolCallbacks'][0]    = 'Creazione di voci di log tramite callback DCA';
$lang['privacyProtocolCallbacks'][1]    = 'Aggiungete voci qui, in modo che, automaticamente, creando, modificando o cancellando records verranno registrate voci nel log.';
$lang['privacyProtocolTable'][0]        = 'Tabella';
$lang['privacyProtocolCallback'][0]     = 'Callback';
$lang['privacyProtocolArchive'][0]      = 'Archivio di registro';
$lang['privacyProtocolFieldMapping'][0] = 'Mappatura dei campi DCA nei campi di log';
$lang['privacyProtocolFieldMapping'][1] = 'Se necessario, selezionare i campi del record di dati che devono essere trasferiti alla voce di log.';
$lang['privacyProtocolEntityField'][0]  = 'Campo DCA';
$lang['privacyProtocolField'][0]        = 'Campo log';
$lang['privacyOptInNotifications'][0]   = 'Notifiche (back-end opt-in)';
$lang['privacyOptInNotifications'][1]   = 'Seleziona le notifiche che desideri vengano inviate quando si usa il modulo di Opt-In del Backend';
$lang['privacyOptInLanguage'][0]        = 'Lingua';
$lang['privacyOptInNotification'][0]    = 'Notifica';
$lang['privacyOptInJumpTo'][0]          = 'Pagina di inoltro';
$lang['cmsScope'][0]                    = 'Modalitò CMS';
$lang['cmsScope'][1]                    = 'In questo campo viene registrato se l\'interazione è avvenuta tramite il frontend o il backend.';

/**
 * Legends
 */
$lang['huh_privacy_legend'] = 'Privacy Policy';

$lang['reference']['huhPrivacy'] = [
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BACKEND  => 'Backend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_FRONTEND => 'Frontend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BOTH     => 'Entrambi'
];
