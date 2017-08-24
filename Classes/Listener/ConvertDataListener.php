<?php
/**
 * con4gis
 * @version   php 7
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2017
 * @link      https://www.kuestenschmiede.de
 */
namespace con4gis\ImportBundle\Classes\Listener;

use c4g\projects\C4GBrickCommon;
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
            $headline   = array_shift($data);
            $delimiter  = ($settings->getDelimiter() != '') ? $settings->getDelimiter() : ';';
            $enclosure  = ($settings->getEnclosure() != '') ? $settings->getEnclosure() : '"';
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
        $srcFields  = $event->getFieldnames();
        $destFields = $settings->getNamedfields();
        $tmpdata    = array();

        if (is_array($data) && count($data)) {
            foreach ($data as $row) {
                if (is_array($row) && count($row) && isset($row[0]) && $row[0] !== null) {
                    $tmprow = array();

                    foreach ($destFields as $field) {
                        if (isset($field['destfields']) && isset($field['srccolumnname'])) {
                            $dbField      = $field['destfields'];
                            $csvField     = $field['srccolumnname'];
                            $defaultValue = $field['defaultvalue'];
                            $override     = $field['overridevalue'];

                            if ($settings->getHeaderline()) {
                                $cloumnNumber = array_search($csvField, $srcFields);
                            } else {
                                $cloumnNumber = $csvField - 1;
                            }

                            if ($defaultValue &&
                                (
                                    $override ||
                                    !isset($row[$cloumnNumber]) ||
                                    $row[$cloumnNumber] == ''
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
                                        $tmprow[$dbField] = '';
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

                if ((!isset($data[$i]['uuid']) || !$data[$i]['uuid'])) {
                    $data[$i]['uuid'] = C4GBrickCommon::getGUID();
                }
            }
        }

        $event->setData($data);
    }


    /**
     * Löscht die Felder, die nicht in der Zieltabelle vorkommen.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertDeleteFields(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $table      = $settings->getSrctable();
        $data       = $event->getData();

        for ($i=0; $i<count($data); $i++) {
            $tmpdata    = array();
            $row        = $data[$i];

            foreach ($row as $field => $value) {
                if ($this->database->fieldExists($field, $table)) {
                    $tmpdata[$field] = $value;
                }
            }

            if (is_array($tmpdata) && count($tmpdata)) {
                // original Zeile ersetzen!
                $data[$i] = $tmpdata;
            }
        }

        $event->setData($data);
    }
}
