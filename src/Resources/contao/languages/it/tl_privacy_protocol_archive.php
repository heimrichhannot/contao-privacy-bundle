<?php

$lang = &$GLOBALS['TL_LANG']['tl_privacy_protocol_archive'];

/**
 * Fields
 */
$lang['tstamp'][0] = 'Data della modifica';
$lang['title'][0]  = 'Titolo';
$lang['title'][1]  = 'Inserire un titolo.';

// config
$lang['personalFieldsExplanation']               =
    'ATTENZIONE: selezionare i soli campi per i quali si è ottenuta l\'autorizzazione esplicita dell\'utente. Se avete dubbi, assicuratevi di consultare un avvocato! <strong> Noi, come sviluppatori, non ci assumiamo alcuna responsabilità! </ strong>';
$lang['personalFields'][0]                       = 'Dati personali da raccogliere';
$lang['personalFields'][1]                       = 'Seleziona qui i campi che devono essere registrati.';
$lang['titlePattern'][0]                         = 'Pattern dell\'intestazione';
$lang['titlePattern'][1]                         = 'Inserire un pattern per il titolo delle voci del modulo nella forma "%campo1% %campo2%".';
$lang['skipIpAnonymization'][0]                  = 'Non anonimizzare l\'indirizzo IP';
$lang['skipIpAnonymization'][1]                  = 'Selezionare quest\'opzione se non volete anonimizzare l\'indirizzo IP.';
$lang['addCodeProtocol'][0]                      = 'Registra i dati relativi all\'origine';
$lang['addCodeProtocol'][1]                      = 'Seleziona questa opzione per acquisire i dati relativi all\'origine.';
$lang['codeFields'][0]                           = 'Dati relativi al codice sorgente da raccogliere';
$lang['codeFields'][1]                           = 'Seleziona qui i campi che dovranno essere registrati.';
$lang['addReferenceEntity'][0]                   = 'Aggiungi supporto entità di riferimento';
$lang['addReferenceEntity'][1]                   = 'Selezionare questa opzione se si desidera anche aggiornare un\'entità di riferimento durante le modifiche dello stato Opt.';
$lang['addEntryTypeToReferenceFieldOnChange'][0] = 'Imposta il tipo di log nel campo dell\'entità di riferimento al cambio di stato Opt-In';
$lang['addEntryTypeToReferenceFieldOnChange'][1] = 'Selezionare un campo.';
$lang['referenceEntryTypeField'][0]              = 'Campo di riferimento';
$lang['referenceEntryTypeField'][1]              = 'Selezionare un campo.';
$lang['referenceFieldTable'][0]                  = 'Tabella del campo di firefimento';
$lang['referenceFieldTable'][1]                  = 'Selezionare la tabella che contiene il campo di riferimento.';
$lang['referenceFieldProtocolForeignKey'][0]     = 'Campo della chiave esterna del log';
$lang['referenceFieldProtocolForeignKey'][1]     = 'Selezionare il campo in "tl_privacy_protocol" che dev\'essere utilizzato per l\'identificazione dell\'oggetto di riferimento.';
$lang['referenceFieldForeignKey'][0]             = 'Campo della chiave esterna';
$lang['referenceFieldForeignKey'][1]             = 'Selezionare il campo della tabella prescelta che dev\'essere utilizzato per l\'identificazione dell\'oggetto di riferimento.';
$lang['createInstanceOnChange'][0]               = 'Crea istanza per l\'impostazione del campo di riferimento (se non già disponibile)';
$lang['createInstanceOnChange'][1]               = 'Selezionare questa opzione se l\'istanza del modello non esiste e deve essere creata.';
$lang['referenceTimestampField'][0]              = 'Campo di riferimento per l\'impostazione di una marca temporale';
$lang['referenceTimestampField'][1]              = 'Selezionare qui il campo dell\'entità di riferimento in cui dev\'essere scritto quando si modifica lo stato di Opt-In.';

/**
 * Reference
 */
$lang['reference'] = [];

/**
 * Legends
 */
$lang['general_legend'] = 'Titolo';
$lang['config_legend']  = 'Configurazione';

/**
 * Buttons
 */
$lang['new']    = ['Nuovo archivio di log', 'Crea archivio di log'];
$lang['edit']   = ['Modifica archivio di log', 'Modifica l\'archivio di log con ID %s'];
$lang['copy']   = ['Duplica archivio di log', 'Duplica l\'archivio di log con ID %s'];
$lang['delete'] = ['Cancella archivio di log', 'Cancella l\'archivio di log con ID %s'];
$lang['toggle'] = ['Pubblica archivio log', 'Pubblica / nascondi l\'archivio di log con ID %s'];
$lang['show']   = ['Dettagli archivio di log', 'Mostra i dettagli dell\'archivio di log con ID %s'];
