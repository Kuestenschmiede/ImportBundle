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

use Contao\Database;
use Doctrine\ORM\EntityManager;
use con4gis\ImportBundle\Classes\Events\SaveDataEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SaveDataListener
 * @package con4gis\ImportBundle\Classes\Listener
 */
class SaveDataListener
{


    /**
     * Doctrine
     * @var null
     */
    protected $entityManager = null;


    /**
     * @var Database|null
     */
    protected $db = null;


    /**
     * AreaImport constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->db               = Database::getInstance();
    }


    /**
     * Löscht die Tabelle vor dem Einfügen neuer Daten, falls gewünscht.
     * @param SaveDataEvent             $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onSaveDataTruncateTable(SaveDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $tableName  = $settings->getSrctable();

        if ($settings->getTruncatetable() && $tableName) {
            $connection = $this->entityManager->getConnection();
            $platform   = $connection->getDatabasePlatform();
            $connection->executeUpdate($platform->getTruncateTableSQL($tableName, true));
        }
    }


    /**
     * Löscht die Tabelle vor dem Einfügen neuer Daten, falls gewünscht.
     * @param SaveDataEvent             $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onSaveDataCreateTable(SaveDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $tableName  = $settings->getSrctablename();

        if ($settings->getSourcekind() == 'create' && $tableName) {
            if (!$this->db->tableExists($tableName)) {
                $query = "CREATE TABLE IF NOT EXISTS $tableName (";
                $query .= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
                $query .= "tstamp INT(10) NOT NULL default 0,";
            } else {
                $query = "ALTER TABLE $tableName";
            }

            $fields = $settings->getFieldnames();

            if (is_array($fields) && count($fields)) {

                foreach ($fields as $field) {
                    if ($field['destfields'] != 'id' && $field['destfields'] != 'tstamp') {
                        if ($this->db->tableExists($tableName)) {
                            if ($this->db->fieldExists($field['destfields'], $tableName)) {
                                $query .= " CHANGE {$field['destfields']}";
                            } else {
                                $query .= " ADD ";
                            }
                        }
                        $query .= ' ' . $field['destfields'] . ' ' . $field['fieldtype'];
                        $query .= '(' . $field['fieldlength'] . ')';

                        if ($field['fieldtype'] == 'varchar' || $field['fieldtype'] == 'char') {
                            $query .= " COLLATE 'utf8_general_ci'";
                        }

                        $query .= ' NOT NULL default ';

                        if ($field['fieldtype'] == 'int' || $field['fieldtype'] == 'integer') {
                            $query .= "'0'";
                        } elseif ($field['fieldtype'] == 'decimal') {
                            $query .= "'0.00'";
                        } else {
                            $query .= '""';
                        }

                        $query .= ',';
                    }
                }

                $query = substr($query, 0, strlen($query) - 1);

                if (!$this->db->tableExists($tableName)) {
                    $query .= ") ENGINE='MyISAM' COLLATE 'utf8_general_ci';";
                } else {
                    $query .= ";";
                }

                $this->db->execute($query);
            }
        }
    }


    /**
     * Löscht die Felder, die nicht in der Zieltabelle vorkommen.
     * @param SaveDataEvent             $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onConvertDeleteFields(SaveDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $table      = $settings->getSrctable();
        $data       = $event->getData();

        for ($i = 0; $i < count($data); $i++) {
            $tmpdata = array();
            $row     = $data[$i];

            foreach ($row as $field => $value) {
                if ($this->db->fieldExists($field, $table)) {
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

    /**
     * Fügt die neuen Daten ein.
     * @param SaveDataEvent             $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onSaveDataInsert(SaveDataEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $tableName  = $settings->getSrctable();
        $tableName  = ($tableName) ? $tableName : $settings->getSrctablename();
        $data       = $event->getData();

        if (is_array($data) && count($data) && $tableName) {
            foreach ($data as $datum) {
                $query = "INSERT INTO $tableName SET ";
                $where = '';

                if (isset($datum['id'])) {
                    // Wenn Datensatz vorhanden ist, soll ein UPDATE ausgeführt werden!
                    $searchQuery  = "SELECT * FROM $tableName WHERE id = {$datum['id']}";
                    $searchResult = $this->db->execute($searchQuery);

                    if ($searchResult->numRows) {
                        $query = "UPDATE $tableName SET ";
                        $where = " WHERE id = {$datum['id']}";
                    }
                }

                foreach ($datum as $field => $value) {
                    // replace commas with dots to prevent SQL syntax error
                    $value = str_replace(',', '.', $value);
                    $query .= "`$field` = '$value', ";
                }

                $query = substr($query, 0, strlen($query)-2) . $where;
                $this->db->execute($query);
            }
        }
    }
}
