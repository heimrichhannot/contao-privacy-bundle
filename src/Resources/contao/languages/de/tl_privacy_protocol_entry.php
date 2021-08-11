<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$lang = &$GLOBALS['TL_LANG']['tl_privacy_protocol_entry'];

/*
 * Fields
 */
$lang['tstamp'][0] = 'Änderungsdatum';

// type & date
$lang['type'][0] = 'Typ';
$lang['type'][1] = 'In diesem Feld wird der Typ gespeichert.';
$lang['dateAdded'][0] = 'Zeitpunkt';
$lang['dateAdded'][1] = 'In diesem Feld wird der Zeitpunkt gespeichert, zu dem die Interaktion stattgefunden hat.';

// user
$lang['personalDataExplanation'] = 'Hinweis: Blau hervorgehobene Felder beinhalten personenbezogene Daten.';
$lang['ip'][0] = 'IP-Adresse';
$lang['ip'][1] = 'In diesem Feld wird die IP-Adresse des Besuchers gespeichert.';
$lang['gender'][0] = 'Geschlecht';
$lang['gender'][1] = 'In diesem Feld wird das Geschlecht des Besuchers gespeichert.';
$lang['academicTitle'][0] = 'Akademischer Titel';
$lang['academicTitle'][1] = 'In diesem Feld wird der akademische Titel des Besuchers gespeichert.';
$lang['firstname'][0] = 'Vorname';
$lang['firstname'][1] = 'In diesem Feld wird der Vorname des Besuchers gespeichert.';
$lang['lastname'][0] = 'Nachname';
$lang['lastname'][1] = 'In diesem Feld wird der Nachname des Besuchers gespeichert.';
$lang['email'][0] = 'E-Mail-Adresse';
$lang['email'][1] = 'In diesem Feld wird die E-Mail-Adresse des Besuchers gespeichert.';
$lang['agreement'][0] = 'Ich stimme der Datenschutzerklärung zu.';
$lang['agreement'][1] = 'In diesem Feld wird die Einwilligung des Besuchers gespeichert.';
$lang['member'][0] = 'Mitglied';
$lang['member'][1] = 'In diesem Feld wird die ID des Mitglieds gespeichert.';
$lang['user'][0] = 'Benutzer';
$lang['user'][1] = 'In diesem Feld wird die ID des Benutzers gespeichert.';

// interaction
$lang['url'][0] = 'URL';
$lang['url'][1] = 'In diesem Feld wird die URL gespeichert, unter der die Interaktion stattgefunden hat.';
$lang['cmsScope'][0] = 'CMS-Modus';
$lang['cmsScope'][1] = 'In diesem Feld wird gespeichert, ob die Interaktion im Frontend oder im Backend stattgefunden hat.';
$lang['bundle'][0] = 'Bundle';
$lang['bundle'][1] = 'In diesem Feld wird gespeichert, in welchem Bundle die Interaktion stattgefunden hat.';
$lang['bundleVersion'][0] = 'Bundle-Version';
$lang['bundleVersion'][1] = 'In diesem Feld wird die Version des Bundles gespeichert.';
$lang['dataContainer'][0] = 'Tabelle';
$lang['dataContainer'][1] = 'In diesem Feld wird die Tabelle gespeichert, in der sich der Nutzer befand.';
$lang['description'][0] = 'Beschreibung';
$lang['description'][1] = 'In diesem Feld wird eine Beschreibung gespeichert.';
$lang['additionalData'][0] = 'Zusätzliche Daten';
$lang['additionalData'][1] = 'In diesem Feld werden kontextabhängige zusätzliche Daten gespeichert.';
$lang['module'][0] = 'Modul';
$lang['module'][1] = 'In diesem Feld wird die ID des Moduls gespeichert.';
$lang['moduleName'][0] = 'Modulname';
$lang['moduleName'][1] = 'In diesem Feld wird der Name des Moduls gespeichert.';
$lang['moduleType'][0] = 'Modultyp';
$lang['moduleType'][1] = 'In diesem Feld wird der Typ des Moduls gespeichert.';
$lang['element'][0] = 'Inhaltselement';
$lang['element'][1] = 'In diesem Feld wird die ID des Inhaltselements gespeichert.';
$lang['elementType'][0] = 'Inhaltselementtyp';
$lang['elementType'][1] = 'In diesem Feld wird der Typ des Inhaltselements gespeichert.';

// code
$lang['codeFile'][0] = 'Datei';
$lang['codeFile'][1] = 'In diesem Feld wird der Pfad zur Datei gespeichert.';
$lang['codeLine'][0] = 'Code-Zeile';
$lang['codeLine'][1] = 'In diesem Feld wird die Code-Zeile gespeichert.';
$lang['codeFunction'][0] = 'Funktion';
$lang['codeFunction'][1] = 'In diesem Feld wird die aufrufende Funktion gespeichert.';
$lang['codeStacktrace'][0] = 'Stacktrace';
$lang['codeStacktrace'][1] = 'In diesem Feld wird der Stacktrace zur aufrufenden Funktion gespeichert.';

/*
 * Reference
 */
$lang['reference'] = [
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_FIRST_OPT_IN => 'Erstes Opt-In',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_SECOND_OPT_IN => 'Zweites Opt-In',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_FIRST_OPT_OUT => 'Erstes Opt-Out',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_SECOND_OPT_OUT => 'Zweites Opt-Out',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_OPT_IN => 'Opt-In',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_OPT_OUT => 'Opt-Out',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_CREATE => 'Datensatz erstellt',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_UPDATE => 'Datensatz bearbeitet',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::TYPE_DELETE => 'Datensatz gelöscht',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BACKEND => 'Backend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_FRONTEND => 'Frontend',
    \HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer::CMS_SCOPE_BOTH => 'Beide',
];

/*
 * Legends
 */
$lang['type_date_legend'] = 'Typ und Zeitpunkt';
$lang['user_legend'] = 'Nutzer';
$lang['interaction_legend'] = 'Interaktion';
$lang['code_legend'] = 'Quelltext';

/*
 * Buttons
 */
$lang['new'] = ['Neuer Protokolleintrag', 'Protokolleintrag erstellen'];
$lang['edit'] = ['Protokolleintrag bearbeiten', 'Protokolleintrag ID %s bearbeiten'];
$lang['copy'] = ['Protokolleintrag duplizieren', 'Protokolleintrag ID %s duplizieren'];
$lang['delete'] = ['Protokolleintrag löschen', 'Protokolleintrag ID %s löschen'];
$lang['show'] = ['Protokolleintrag Details', 'Protokolleintrag-Details ID %s anzeigen'];
