<?php

$lang = &$GLOBALS['TL_LANG']['MSC']['huhPrivacy'];

/**
 * Fields
 */
$lang['addPrivacyProtocolEntry'][0]                   = 'Eintrag im Datenschutzprotokoll hinzufügen';
$lang['addPrivacyProtocolEntry'][1]                   = 'Wählen Sie diese Option, um nach der Interaktion einen Eintrag im Datenschutzprotokoll hinzuzufügen.';
$lang['privacyProtocolEntryArchive'][0]               = 'Archiv';
$lang['privacyProtocolEntryArchive'][1]               = 'Wählen Sie hier das Archiv aus, in dem der Eintrag im Datenschutzprotokoll gespeichert werden soll.';
$lang['privacyProtocolEntryConfig'][0]                = 'Datenschutzprotokoll-Konfiguration';
$lang['privacyProtocolEntryConfig'][1]                = 'Wenn Einträge imm Datenschutzprotokoll angelegt werden sollen, wählen Sie hier bitte die gewünschte Konfiguration aus.';
$lang['privacyProtocolEntryType'][0]                  = 'Typ';
$lang['privacyProtocolEntryType'][1]                  = 'Wählen Sie hier den Typ des Eintrags im Datenschutzprotokoll gespeichert werden soll.';
$lang['privacyProtocolEntryDescription'][0]           = 'Beschreibung';
$lang['privacyProtocolEntryDescription'][1]           = 'Geben Sie hier eine Beschreibung für den Eintrag im Datenschutzprotokoll ein.';
$lang['privacyProtocolFieldMapping'][0]               = 'Feldabbildung';
$lang['privacyProtocolFieldMapping'][1]               = 'Wählen Sie hier bei Bedarf Felder des Datensatzes aus, die in den Protokolleintrag überführt werden sollen.';
$lang['privacyProtocolNotification'][0]               = 'Benachrichtigung';
$lang['privacyProtocolNotification'][1]               = 'Wählen Sie hier die Benachrichtigung, die verschickt werden soll, aus.';
$lang['privacyProtocolActivationJumpTo'][0]           = 'Weiterleitungsseite (Aktivierung)';
$lang['privacyProtocolActivationJumpTo'][1]           = 'Wählen Sie hier die Seite aus, die ein Modul vom Typ "Protokolleintragseditor" enthält und ein Opt aktiviert.';
$lang['privacyProtocolFieldMapping_entityField'][0]   = 'Feld im Datensatz';
$lang['privacyProtocolFieldMapping_protocolField'][0] = 'Feld im Protokolleintrag';
$lang['afterDelete']                                  = 'Nach dem Löschen';

/**
 * Messages
 */
$lang['optInTokenInvalid']                = 'Der Link ist ungültig. Bitte prüfen Sie, ob Sie den Link korrekt eingegeben haben.';
$lang['optOutSuccessful']                 = 'Die Abmeldung war erfolgreich. Sie werden von nun an keine E-Mails mehr von uns erhalten.';
$lang['optOutFailed']                     = 'Die Abmeldung war nicht erfolgreich. Bitte prüfen Sie, ob Sie den Link korrekt eingegeben haben.';
$lang['optOutFailedNoToken']              = 'Die Abmeldung war nicht erfolgreich. Kein Abmelde-Token gefunden.';
$lang['alreadyOptedOut']                  = 'Sie sind bereits abgemeldet und erhalten daher keine E-Mails von uns.';
$lang['messageNoJwtToken']                = 'Es wurde kein Token gefunden. Haben Sie sich vertippt?';
$lang['messageNoBackendOptInConfigFound'] = 'Es wurde keine Backend-Opt-In-Konfiguration für die gewählte Sprache gefunden. Erstellen Sie diese in den Contao-Einstellungen unter "Benachrichtigungen (Backend-Opt-In)".';

/**
 * Reference
 */
$lang['reference']['male']   = 'Männlich';
$lang['reference']['female'] = 'Weiblich';
