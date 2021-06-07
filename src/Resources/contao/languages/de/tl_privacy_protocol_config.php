<?php

$lang = &$GLOBALS['TL_LANG']['tl_privacy_protocol_config'];

/**
 * Fields
 */
$lang['tstamp'][0] = 'Änderungsdatum';
$lang['title'][0]  = 'Titel';
$lang['title'][1]  = 'Geben Sie hier bitte den Titel ein.';

// config
$lang['dataContainer'][0] = 'Zu protokollierende Tabelle';
$lang['dataContainer'][1] = 'Wählen Sie hier die Datenbanktabelle, von der das Protokoll ausgeht. Wenn Sie bspw. das Löschen von Mitgliedern protokollieren möchten, wäre es tl_member.';
$lang['notification'][0]  = 'Benachrichtigung';
$lang['notification'][1]  = 'Wählen Sie hier eine Benachrichtigung aus, die beim Anlegen eines Protokolleintrags verschickt werden soll.';

/**
 * Legends
 */
$lang['general_legend'] = 'Titel';
$lang['config_legend']  = 'Konfiguration';

/**
 * Buttons
 */
$lang['new']    = ['Neue Protokollkonfiguration', 'Protokollkonfiguration erstellen'];
$lang['edit']   = ['Protokollkonfiguration bearbeiten', 'Protokollkonfiguration ID %s bearbeiten'];
$lang['copy']   = ['Protokollkonfiguration duplizieren', 'Protokollkonfiguration ID %s duplizieren'];
$lang['delete'] = ['Protokollkonfiguration löschen', 'Protokollkonfiguration ID %s löschen'];
$lang['show']   = ['Protokollkonfiguration Details', 'Protokollkonfiguration-Details ID %s anzeigen'];
