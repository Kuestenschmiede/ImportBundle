<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */

/**
 * Set Tablename
 */
$strName = 'tl_c4g_import';


/**
 * Set Elementname
 */
$strElement = 'Import';


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['title']              = array('Titel', 'Bitte geben Sie den Titel ein.');
$GLOBALS['TL_LANG'][$strName]['description']        = array('Beschreibung', 'Bitte geben Sie eine Beschreibung ein.');
$GLOBALS['TL_LANG'][$strName]['srckind']            = array('Art der Feldzuordnung', 'Bitte wählen Sie die Art der Feldzuordnung aus.');
$GLOBALS['TL_LANG'][$strName]['sourcekind']         = array('Importart', 'Bitte wählen Sie die Art des Imports.');
$GLOBALS['TL_LANG'][$strName]['srctable']           = array('Tabelle', 'Bitte wählen Sie die Quelltabelle aus. Wird die Datei geändert, MUSS der Datensatz gespeichert werden, damit die Änderungen in den anderen Feldern sichtbar werden!');
$GLOBALS['TL_LANG'][$strName]['orderedfields']      = array('Felder', 'Bitte wählen Sie die Felder für den Import aus und bringen sie in die Reihenfolge, in der sie auch in der Importdatei vorkommen. Die Spaltennamen in der erstellten Tabelle entsprechen dann den angegebenen Namen aus der Importdatei.');
$GLOBALS['TL_LANG'][$strName]['namedfields']        = array('Felder', 'Bitte wählen Sie die Felder für den Import aus und tragen Sie die Spaltenüberschriften ein, wie sie auch in der Importdatei vorkommen.');
$GLOBALS['TL_LANG'][$strName]['destfields']         = array('Zielfeld', 'Bitte wählen Sie das Zielfeld der Datenbank aus.');
$GLOBALS['TL_LANG'][$strName]['srccolumnname']      = array('Quellspalte oder -nummer', 'Bitte geben Sie die Überschrift oder die Nummer der Quellspalte der Importdatei ein.');
$GLOBALS['TL_LANG'][$strName]['srcfile']            = array('Quelldatei', 'Bitte wählen Sie die Quelldatei aus.');
$GLOBALS['TL_LANG'][$strName]['renamefile']         = array('Quelldatei umbenennen', 'Bitte wählen Sie, ob die Quelldatei nach dem Import umbenannt werden soll.');
$GLOBALS['TL_LANG'][$strName]['truncatetable']      = array('Tabelle löschen', 'Bitte wählen Sie, ob die Zieltabelle vor dem Import gelöscht werden soll.');
$GLOBALS['TL_LANG'][$strName]['defaultvalue']       = array('Vorgabewert', 'Der Vorgabewert wird statt der Daten aus der Importdatei importiert, falls er angegeben ist.');
$GLOBALS['TL_LANG'][$strName]['headerline']         = array('Spaltenüberschrifen sind vorhanden', 'Bitte wählen Sie, ob in der ersten Zeile der Importdatei die Spaltenüberschriften stehen.');
$GLOBALS['TL_LANG'][$strName]['overridevalue']      = array('Importwert überschreiben', 'Bitte wählen Sie, ob der Vorgabewert den Importwert immer überschreiben soll, oder nur dann eingefügt wird, wenn kein Wert in der Importdatei vorhanden ist.');
$GLOBALS['TL_LANG'][$strName]['delimiter']          = array('Feldtrennzeichen', 'Bitte wählen Sie das Feldtrennzeichen aus. Vorgabewert: ;');
$GLOBALS['TL_LANG'][$strName]['enclosure']          = array('Textmarkierungszeichen', 'Bitte wählen Sie das Textmarkierungszeichen aus. Vorgabewert: "');
$GLOBALS['TL_LANG'][$strName]['saveTlTables']       = array('Contao-Tabellen speichern', 'Tabellen, die mit "tl_" beginnen, dürfen nur ausgewählt werden, wenn dies gesetzt ist. Es besteht die Gefahr, dass die Webseite beschädigt wird.');
$GLOBALS['TL_LANG'][$strName]['fieldnames']         = array('Felder', 'Bitte geben Sie die gewünschten Spaltennamen an und wählen die dazu gehörigen Felder aus der Importdatei aus. Wenn Sie kein Feld aus der Datei auswählen, wird die Spalte leer erzeugt (so können z.B. Koordinaten-Felder für den Adressimport erzeugt werden).');
$GLOBALS['TL_LANG'][$strName]['srctablename']       = array('Zieltabelle', 'Bitte geben Sie den Namen der Tabelle ein.');
$GLOBALS['TL_LANG'][$strName]['fieldtype']          = array('Feldtyp', 'Bitte geben Sie den Typ des Feldes ein.');
$GLOBALS['TL_LANG'][$strName]['fieldlength']        = array('Feldlänge', 'Bitte geben Sie die Länge des Feldes ein.');
$GLOBALS['TL_LANG'][$strName]['usequeue']           = array('Abarbeitung über Warteschlange', 'Auftrag über Warteschlange abarbeiten.');
$GLOBALS['TL_LANG'][$strName]['useinterval']        = array('Intervallausführung', 'Bitte wählen Sie aus, ob der Auftrag in einem bestimmten Intervall wiederholt ausgeführt werden soll.');
$GLOBALS['TL_LANG'][$strName]['intervalkind']       = array('Intervall', 'Bitte legen Sie das Intervall fest, in dem der Auftrag wiederholt werden soll.');
$GLOBALS['TL_LANG'][$strName]['intervalcount']      = array('Maximale Anzahl der Ausführungen', 'Bitte legen Sie fest, ob der Auftrag nach einer bestimmten Anzahl von Ausführungen beendet sein soll. Für unendliche Ausführung bitte leer lassen.');
$GLOBALS['TL_LANG'][$strName]['importaddresses']      = array('Adressen umwandeln (erfordert con4gis.io)', 'Setzen Sie diese Checkbox, wenn Sie Adressfelder beim Importieren in Koordinaten umwandeln lassen möchten (erfordert einen con4gis.io-Schlüssel).');
$GLOBALS['TL_LANG'][$strName]['addressfields']      = array('Adressfelder', 'Wählen Sie das Feld / die Felder aus, in denen die Adresse gespeichert ist.');
$GLOBALS['TL_LANG'][$strName]['geoxfield']      = array('Feld für X-Koordinate', 'Wählen Sie das Datenbankfeld aus, in dem die X-Koordinate gespeichert werden soll.');
$GLOBALS['TL_LANG'][$strName]['geoyfield']      = array('Feld für Y-Koordinate', 'Wählen Sie das Datenbankfeld aus, in dem die Y-Koordinate gespeichert werden soll.');
$GLOBALS['TL_LANG'][$strName]['csvField']      = array('Quellspalte oder -nummer', 'Bitte geben Sie die Überschrift oder die Nummer der Quellspalte der Importdatei ein.');

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['title_legend']       = 'Titel';
$GLOBALS['TL_LANG'][$strName]['sourcekind_legend']  = 'Importart';
$GLOBALS['TL_LANG'][$strName]['srctable_legend']    = 'Tabelle';
$GLOBALS['TL_LANG'][$strName]['srcfile_legend']     = 'Quelldatei';
$GLOBALS['TL_LANG'][$strName]['expert_legend']      = 'Experteneinstellungen';
$GLOBALS['TL_LANG'][$strName]['usequeue_legend']    = 'Einstellungen für die Warteschlange';


/**
 * Reference
 */
$GLOBALS['TL_LANG'][$strName]['srckind_ref']        = array('byorder' => 'Felder anhand der Reihenfolge zuordnen (Es dürfen KEINE Spaltennamen in der Datei stehen!)', 'byname' => 'Felder anhand der Spaltenüberschriften zuordnen (In der ersten Zeile MÜSSEN die Spaltennamen stehen!)');
$GLOBALS['TL_LANG'][$strName]['sourcekind_ref']     = array('import' => 'Nur importieren', 'create' => 'Tabellen anlegen und importieren');
$GLOBALS['TL_LANG'][$strName]['intervalkind_ref']   = array('hourly' => 'stündlich', 'daily' => 'täglich', 'weekly' => 'wöchentlich', 'monthly' => 'monatlich', 'yearly' => 'jährlich');


/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['runimport']          = array('Import durchführen', 'Import durchführen');


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strName]['new']        = array('Neuer ' . $strElement, 'Neuen ' . $strElement . ' anlegen');
$GLOBALS['TL_LANG'][$strName]['edit']       = array($strElement . ' bearbeiten', $strElement . ' mit der ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strName]['copy']       = array($strElement . ' kopieren', $strElement . ' mit der ID %s kopieren');
$GLOBALS['TL_LANG'][$strName]['delete']     = array($strElement . ' löschen', $strElement . ' mit der ID %s löschen');
$GLOBALS['TL_LANG'][$strName]['show']       = array($strElement . ' anzeigen', 'Details des ' . $strElement . 's mit der ID %s anzeigen');
