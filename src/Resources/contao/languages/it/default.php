<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$lang = &$GLOBALS['TL_LANG']['MSC']['huhPrivacy'];

/*
 * Fields
 */
$lang['addPrivacyProtocolEntry'][0] = 'Aggiunta voci al log della privacy';
$lang['addPrivacyProtocolEntry'][1] = 'Selezionare questa opzione per aggiungere una voce al log della privacy dopo l\'interazione.';
$lang['privacyProtocolEntryArchive'][0] = 'Archivio';
$lang['privacyProtocolEntryArchive'][1] = 'Seleziona qui l\'archivio in cui deve essere memorizzata la voce di log della privacy.';
$lang['privacyProtocolEntryType'][0] = 'Tipo';
$lang['privacyProtocolEntryType'][1] = 'Seleziona qui il tipo di voce da salvare nel log della privacy.';
$lang['privacyProtocolEntryDescription'][0] = 'Descrizione';
$lang['privacyProtocolEntryDescription'][1] = 'Inserisci qui una descrizione per la voce di log della privacy.';
$lang['privacyProtocolFieldMapping'][0] = 'Mappatura dei campi';
$lang['privacyProtocolFieldMapping'][1] = 'Se necessario, selezionare i campi del record di dati che devono essere inseriti nella voce di log.';
$lang['privacyProtocolNotification'][0] = 'Notifica';
$lang['privacyProtocolNotification'][1] = 'Seleziona qui la notifica da inviare.';
$lang['privacyProtocolActivationJumpTo'][0] = 'Pagina di inoltro (attivazione)';
$lang['privacyProtocolActivationJumpTo'][1] = 'Selezionare la pagina contenente un modulo del tipo "Editor di voce di log" e che attiva una Opt.';
$lang['privacyProtocolFieldMapping_entityField'][0] = 'Campo nel record';
$lang['privacyProtocolFieldMapping_protocolField'][0] = 'Campo nella voce di log';
$lang['afterDelete'] = 'Dopo la cancellazione';

/*
 * Messages
 */
$lang['optInTokenInvalid'] = 'Il link non è valido. Controllare di averlo inserito correttamente.';
$lang['optOutSuccessful'] = 'La cancellazione è avvenuta con successo. Non riceverai più email da noi.';
$lang['optOutFailed'] = 'La cancellazione non ha avuto successo. Si prega di verificare di aver inserito correttamente il link.';
$lang['optOutFailedNoToken'] = 'La cancellazione non ha avuto successo. Non è stato trovato nessun token di logoff.';
$lang['alreadyOptedOut'] = 'Sei già disconnesso e quindi non riceverai più alcuna e-mail da noi.';
$lang['messageNoJwtToken'] = 'Non è stato trovato alcun token. Hai scritto in modo errato?';
$lang['messageNoBackendOptInConfigFound'] = 'Non è stata trovata alcuna configurazione di opt-in nel back-end per la lingua selezionata. Creala nelle impostazioni di Contao alla voce "Notifiche (back-end opt-in)".';

/*
 * Reference
 */
$lang['reference']['male'] = 'Maschio';
$lang['reference']['female'] = 'Femmina';
$lang['reference']['other'] = 'Altro';
