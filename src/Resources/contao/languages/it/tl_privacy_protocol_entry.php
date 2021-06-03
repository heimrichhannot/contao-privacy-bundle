<?php

$lang = &$GLOBALS['TL_LANG']['tl_privacy_protocol_entry'];

/**
 * Fields
 */
$lang['tstamp'][0] = 'Data della modifica';

// type & date
$lang['type'][0]      = 'Tipo';
$lang['type'][1]      = 'In questo campo va specificato il tipo.';
$lang['dateAdded'][0] = 'Data aggiunta';
$lang['dateAdded'][1] = 'In questo campo va specificata la data in cui l\'interazione ha avuto luogo.';

// user
$lang['personalDataExplanation'] = 'Nota: i campi evidenziati in blu contengono informazioni personali.';
$lang['ip'][0]                   = 'Indirizzo IP';
$lang['ip'][1]                   = 'In questo campo verrà memorizzato l\'indirizzo IP del visitatore.';
$lang['gender'][0]               = 'Sesso';
$lang['gender'][1]               = 'In questo campo verrà memorizzato il sesso del visitatore.';
$lang['academicTitle'][0]        = 'Titolo accademico';
$lang['academicTitle'][1]        = 'In questo campo verrà memorizzato il titolo accademico del visitatore.';
$lang['firstname'][0]            = 'Nome';
$lang['firstname'][1]            = 'In questo campo verrà memorizzato il nome del visitatore.';
$lang['lastname'][0]             = 'Cognome';
$lang['lastname'][1]             = 'In questo campo verrà memorizzato il cognome del visitatore.';
$lang['email'][0]                = 'Indirizzo E-Mail';
$lang['email'][1]                = 'In questo campo verrà memorizzato l\'indirizzo E-Mail del visitatore.';
$lang['agreement'][0]            = 'Accetto l\'informativa sulla privacy.';
$lang['agreement'][1]            = 'In questo campo verrà memorizzato il consenso del visitatore.';
$lang['member'][0]               = 'Membro';
$lang['member'][1]               = 'In questo campo verrà memorizzato l\'ID del membro.';
$lang['user'][0]                 = 'Utente';
$lang['user'][1]                 = 'In questo campo verrà memorizzato l\'ID dell\'utente.';

// interaction
$lang['url'][0]            = 'URL';
$lang['url'][1]            = 'In questo campo viene registra l\'URL attraverso la quale cui è avvenuta l\'interazione.';
$lang['cmsScope'][0]       = 'Modalità CMS';
$lang['cmsScope'][1]       = 'In questo campo viene registrato se l\'interazione è avvenuta tramite il frontend o il backend.';
$lang['bundle'][0]         = 'Pacchetto';
$lang['bundle'][1]         = 'Questo campo registra il pacchetto in cui è avvenuta l\'interazione.';
$lang['bundleVersion'][0]  = 'Versione pacchetto';
$lang['bundleVersion'][1]  = 'In questo campo viene registrata la versione del pacchetto.';
$lang['dataContainer'][0]          = 'Tabella';
$lang['dataContainer'][1]          = 'In questo campo viene registrata la tabella in cui l\utente si trovava.';
$lang['description'][0]    = 'Descrizione';
$lang['description'][1]    = 'In questo campo viene registrata una descrizione.';
$lang['additionalData'][0] = 'Dati aggiuntivi';
$lang['additionalData'][1] = 'In questo campo vengono registrati dei dati aggiuntivi dipendenti dal contesto.';
$lang['module'][0]         = 'Modulo';
$lang['module'][1]         = 'In questo campo viene registrato l\'ID del modulo.';
$lang['moduleName'][0]     = 'Nome del modulo';
$lang['moduleName'][1]     = 'In questo campo viene registrato il nome del modulo.';
$lang['moduleType'][0]     = 'Tipo del modulo';
$lang['moduleType'][1]     = 'In questo campo viene registrato il tipo del modulo.';
$lang['element'][0]        = 'Elemento di contenuto';
$lang['element'][1]        = 'In questo campo viene registrato l\'ID dell\'elemento di contenuto.';
$lang['elementType'][0]    = 'Tipo dell\'elemento di contenuto';
$lang['elementType'][1]    = 'In questo campo viene registrato il tipo dell\'elemento di contenuto.';

// code
$lang['codeFile'][0]       = 'File';
$lang['codeFile'][1]       = 'In questo campo viene registrato il percorso del file.';
$lang['codeLine'][0]       = 'Linea di codice';
$lang['codeLine'][1]       = 'In questo campo viene registrata la linea di codice.';
$lang['codeFunction'][0]   = 'Funzione';
$lang['codeFunction'][1]   = 'In questo campo viene registrata la funzione chiamante.';
$lang['codeStacktrace'][0] = 'Stack trace';
$lang['codeStacktrace'][1] = 'In questo campo viene memorizzata la traccia dello stack della funzione chiamante.';

/**
 * Reference
 */
$lang['reference'] = [
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_FIRST_OPT_IN   => 'Primo Opt-In',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_SECOND_OPT_IN  => 'Secondo Opt-In',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_FIRST_OPT_OUT  => 'Primo Opt-Out',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_SECOND_OPT_OUT => 'Secondo Opt-Out',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_OPT_OUT        => 'Opt-Out',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_CREATE         => 'Record creato',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_UPDATE         => 'Record modificato',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_DELETE         => 'Recorn eliminato',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BACKEND   => 'Backend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_FRONTEND  => 'Frontend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BOTH      => 'Entrambi'
];

/**
 * Legends
 */
$lang['type_date_legend']   = 'Tipo ed ora';
$lang['user_legend']        = 'Itente';
$lang['interaction_legend'] = 'Interazione';
$lang['code_legend']        = 'Codice sorgente';

/**
 * Buttons
 */
$lang['new']    = ['Nuova voce di log', 'Creazione della voce di log'];
$lang['edit']   = ['Modifica di una voce di log', 'Modifica della voce di log con ID %s'];
$lang['copy']   = ['Duplicazione di una voce di log', 'Duplicazione della voce di log con ID %s'];
$lang['delete'] = ['Cancellazione di una voce di log', 'Cancellazione della voce di log con ID %s'];
$lang['show']   = ['Dettagli della voce di log', 'Mostra i dettagli della voce di log con ID %s'];
