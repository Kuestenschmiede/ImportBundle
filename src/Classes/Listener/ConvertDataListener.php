<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2025, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\ImportBundle\Classes\Listener;

use con4gis\CoreBundle\Classes\Helper\StringHelper;
use Contao\CoreBundle\Framework\ContaoFramework;
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
    protected $sh = null;
    /**
     * ConvertDataListener constructor.
     * @param Database|null $database
     */
    public function __construct(ContaoFramework $framework, Database $database = null)
    {
        $framework->initialize();
        if ($database !== null) {
            $this->database = $database;
        } else {
            $this->database = Database::getInstance();
        }
        $this->sh = new StringHelper();
    }

    /**
     * Erstellt aus dem CSV-String ein Array mit den einzelnen Zeilen.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertDataToArray(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $event->setData($event->getImportData());
    }

    /**
     * Erstellt bei der Zuorgnung nach Name eine Liste der Felder.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertFields(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings = $event->getSettings();
        $data = $event->getData();

        if ($settings->getHeaderline()) {
            // Spaltennamen auslesen, wenn Spaltennamen vorhanden.

            // Sonderzeichen entfernen, damit die Spaltenüberschriften wieder mit den Feldnamen des MCW übereinstimmen!

            $delimiter = ($settings->getDelimiter() != '') ? $settings->getDelimiter() : ';';
            $enclosure = ($settings->getEnclosure() != '') ? $settings->getEnclosure() : '"';
            $fieldnames = array_shift($data);
            foreach ($fieldnames as $key => $value) {
                $fieldnames[$key] = $this->sh->removeSpecialSigns($value, 'a-zA-Zß0-9' . preg_quote("\+*?[^]$(){}=!<>|:-#" . $delimiter));
            }

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
    }

    /**
     * Ordnet die Daten den Datenbankfelden anhand der Namen der Importfelder zu.
     * @param ConvertDataEvent         $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onConvertRows(ConvertDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings = $event->getSettings();
        $data = $event->getData();
        $sourcekind = $settings->getSourcekind();
        $srcFields = $event->getFieldnames();
        $destFields = $settings->getNamedfields();
        $destFields = ($destFields) ? $destFields : $settings->getFieldnames(); // Felder beim Anlegen der Tabelle!
        $delimiter = ($settings->getDelimiter() != '') ? $settings->getDelimiter() : ';';

        $tmpdata = [];

        if (is_array($data) && count($data)) {
            foreach ($data as $row) {
                if (is_array($row) && count($row) && isset($row[0]) && $row[0] !== null) {
                    $tmprow = [];

                    foreach ($destFields as $field) {
                        if (isset($field['destfields'])) {
                            $dbField = $field['destfields'];
                            $defaultValue = $field['defaultvalue'];
                            $override = $field['overridevalue'];

                            if (isset($field['srccolumnname']) && $field['srccolumnname']) {
                                $csvField = $field['srccolumnname'];
                            } elseif (isset($field['csvField']) && $field['csvField']) {
                                $csvField = $field['csvField'];
                            } else {
                                $csvField = '';
                            }

                            $csvField = str_replace(['.',' '], '', $csvField);
                            if ($csvField !== '') {
                                // Sonderzeichen entfernen, damit die Spaltenüberschriften wieder mit den Feldnamen des MCW übereinstimmen!
                                $csvField = $this->sh->removeSpecialSigns($csvField, 'a-zA-Zß0-9' . preg_quote("'\+*?[^]$(){}=!<>|:-#" . $delimiter));

                                $cloumnNumber = array_search($csvField, $srcFields);

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
                                            switch ($field['fieldtype']) {
                                                case 'decimal':
                                                    $tmprow[$dbField] = floatval($row[$cloumnNumber]);
                                                    break;
                                                case 'int':
                                                    $tmprow[$dbField] = intval($row[$cloumnNumber]);
                                                    break;
                                                default:
                                                    $tmprow[$dbField] = $row[$cloumnNumber];
                                                    break;
                                            }
                                        } else {
                                            $tmprow[$dbField] = null;
                                        }
                                    }
                                }
                            } else {
                                $tmprow[$dbField] = $defaultValue ?: '';
                            }
                        }
                    }
                    if ($event->getPid()) {
                        $tmprow['pid'] = $event->getPid();
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
            }
        }

        $event->setData($data);
    }
}
