<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    6
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
namespace con4gis\ImportBundle\Classes\Listener;

use con4gis\CoreBundle\Classes\Helper\StringHelper;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickCommon;
use Contao\Database;
use con4gis\ImportBundle\Classes\Events\ConvertDataEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ConvertDataListener
 * @package con4gis\ImportBundle\Classes\Listener
 */
class ConvertDataListener
{


    /**
     * Instanz von \Contao\Database
     * @var \Contao\Database|null
     */
    protected $database = null;


    /**
     * ConvertDataListener constructor.
     * @param Database|null $database
     */
    public function __construct(Database $database = null)
    {
        if ($database !== null) {
            $this->database = $database;
        } else {
            $this->database = Database::getInstance();
        }
    }


    /**
     * Erstellt aus dem CSV-String ein Array mit den einzelnen Zeilen.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertDataToArray(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $importData = $event->getImportData();

        if ($importData) {
            $data = explode(PHP_EOL, $importData);
            $event->setData($data);
        }
    }


    /**
     * Erstellt bei der Zuorgnung nach Name eine Liste der Felder.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertFields(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $data       = $event->getData();

        if ($settings->getHeaderline()) {
            // Spaltennamen auslesen, wenn Spaltennamen vorhanden.
            $sh         = new StringHelper();
            // Sonderzeichen entfernen, damit die Spaltenüberschriften wieder mit den Feldnamen des MCW übereinstimmen!
            
            $delimiter  = ($settings->getDelimiter() != '') ? $settings->getDelimiter() : ';';
            $enclosure  = ($settings->getEnclosure() != '') ? $settings->getEnclosure() : '"';
            $headline   = $sh->removeSpecialSigns(array_shift($data), 'a-zA-Z0-9' . preg_quote("\+*?[^]$(){}=!<>|:-#" . $delimiter));
            $fieldnames = str_getcsv($headline, $delimiter, $enclosure);
            $event->setFieldnames($fieldnames);
            $event->setData($data); // wegen dem Löschen der Überschriften!
        }
    }


    /**
     * Konvertiert die einzelnen Reihen der zu importiernen Daten in je ein Array.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertRowsToArrays(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $data       = $event->getData();
        //$data = array_map('str_getcsv', $data, array(';')); // funktioniert nur beim ersten Element!

        for ($i=0; $i<count($data); $i++) {
            $delimiter  = ($settings->getDelimiter() != '') ? $settings->getDelimiter() : ';';
            $enclosure  = ($settings->getEnclosure() != '') ? $settings->getEnclosure() : '"';
            $data[$i] = str_getcsv($data[$i], $delimiter, $enclosure);
        }

        $event->setData($data);
    }


    /**
     * Ordnet die Daten den Datenbankfelden anhand der Namen der Importfelder zu.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertRows(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $data       = $event->getData();
        $sourcekind = $settings->getSourcekind();
        $srcFields  = $event->getFieldnames();
        $destFields = $settings->getNamedfields();
        $destFields = ($destFields) ? $destFields : $settings->getFieldnames(); // Felder beim Anlegen der Tabelle!
        $tmpdata    = array();

        if (is_array($data) && count($data)) {
            foreach ($data as $row) {
                if (is_array($row) && count($row) && isset($row[0]) && $row[0] !== null) {
                    $tmprow = array();

                    foreach ($destFields as $field) {
                        if (isset($field['destfields'])) {
                            $dbField      = $field['destfields'];
                            $defaultValue = $field['defaultvalue'];
                            $override     = $field['overridevalue'];

                            if (isset($field['srccolumnname']) && $field['srccolumnname']) {
                                $csvField = $field['srccolumnname'];
                            } else {
                                $csvField = $field['destfields'];
                            }


                            if ($settings->getHeaderline()) {
                                $cloumnNumber = array_search($csvField, $srcFields);
                            } else {
                                $cloumnNumber = $csvField - 1;
                            }

                            if ($defaultValue !== null && $defaultValue !== '' &&
                                (
                                    $override !== '' ||
                                    !isset($row[$cloumnNumber]) ||
                                    $row[$cloumnNumber] === '' ||
                                    $row[$cloumnNumber] === null
                                )
                            ) {
                                $tmprow[$dbField] = $defaultValue;
                            } else {

                                if (isset($row[$cloumnNumber])) {
                                    if ($row[$cloumnNumber] !== 'NULL' &&
                                        $row[$cloumnNumber] !== 'Null' &&
                                        $row[$cloumnNumber] !== 'null' &&
                                        $row[$cloumnNumber] !== null
                                    ) {
                                        $tmprow[$dbField] = $row[$cloumnNumber];
                                    } else {
                                        $tmprow[$dbField] = null;
                                    }
                                }
                            }
                        }
                    }

                    $tmpdata[] = $tmprow;
                }
            }

            if (is_array($tmpdata) && count($tmpdata)) {
                $event->setData($tmpdata);
            }
        }
    }


    /**
     * Erzeugt die Standardfelder (tstamp und uuid) falls sie nicht vorhanden sind.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertInsertFields(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $data = $event->getData();

        if (is_array($data) && count($data)) {
            for ($i = 0; $i < count($data); $i++) {
                if (!isset($data[$i]['tstamp']) || !$data[$i]['tstamp']) {
                    $data[$i]['tstamp'] = time();
                }
/*  Raus! Nach Umstellung auf Bundels wird C4GBrickCommon::getGUID() nicht mehr gefunden!
    @todo bei Bedarf korrigieren und wieder einfügen!
                if ((!isset($data[$i]['uuid']) || !$data[$i]['uuid'])) {
                    $data[$i]['uuid'] = C4GBrickCommon::getGUID();
                }
*/
            }
        }

        $event->setData($data);
    }
}
