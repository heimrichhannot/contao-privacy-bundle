<?php

$lang = &$GLOBALS['TL_LANG']['tl_settings'];

/**
 * Fields
 */
$lang['privacyProtocolCallbacks'][0]    = 'Protokolleinträge durch DCA-Callbacks erzeugen';
$lang['privacyProtocolCallbacks'][1]    = 'Fügen Sie hier neue Einträge hinzu, um automatisch beim Erzeugen, Bearbeiten oder Löschen von Datensätzen neue Einträge im Datenschutzprotokoll hinzuzufügen.';
$lang['privacyProtocolTable'][0]        = 'Tabelle';
$lang['privacyProtocolCallback'][0]     = 'Callback';
$lang['privacyProtocolArchive'][0]      = 'Protokollarchiv';
$lang['privacyProtocolFieldMapping'][0] = 'Abbildung von DCA-Feldern in Protokollfelder';
$lang['privacyProtocolFieldMapping'][1] = 'Wählen Sie hier bei Bedarf Felder des Datensatzes aus, die in den Protokolleintrag überführt werden sollen.';
$lang['privacyProtocolEntityField'][0]  = 'DCA-Feld';
$lang['privacyProtocolField'][0]        = 'Protokollfeld';
$lang['privacyOptInNotifications'][0]   = 'Benachrichtigungen (Backend-Opt-In)';
$lang['privacyOptInNotifications'][1]   = 'Wählen Sie hier die Benachrichtigungen aus, die bei der Nutzung des Backend-Opt-In-Formulars verschickt werden sollen. ';
$lang['privacyOptInLanguage'][0]        = 'Sprache';
$lang['privacyOptInNotification'][0]    = 'Benachrichtigung';
$lang['privacyOptInJumpTo'][0]          = 'Weiterleitungsseite';
$lang['cmsScope'][0]                    = 'CMS-Modus';
$lang['cmsScope'][1]                    = 'In diesem Feld wird gespeichert, ob die Interaktion im Frontend oder im Backend stattgefunden hat.';

/**
 * Legends
 */
$lang['huh_privacy_legend'] = 'Datenschutz';

$lang['reference']['huhPrivacy'] = [
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BACKEND  => 'Backend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_FRONTEND => 'Frontend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BOTH     => 'Beide'
];
