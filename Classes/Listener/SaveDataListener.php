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
     * AreaImport constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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
            $fields = $settings->getFieldnames();

            if (is_array($fields) && count($fields)) {
                $query = "CREATE TABLE IF NOT EXISTS $tableName (";
                $query.= "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
                $query.= "tstamp INT(10) NOT NULL default 0,";

                foreach ($fields as $field) {
                    $query .= ' ' . $field['destfields'] . ' ' . $field['fieldtype'] . '(' . $field['fieldlength'];
                    $query .= ') NOT NULL default ';

                    if ($field['fieldtype'] == 'int' || $field['fieldtype'] == 'integer') {
                        $query .= 0;
                    } else {
                        $query .= '""';
                    }

                    $query .= ',';
                }

                $query = substr($query, 0, strlen($query)-1);
                $query .= ")";

                \Contao\Database::getInstance()->execute($query);
            }
        }
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
        $db         = Database::getInstance();  // Nicht alle Tabellen haben Entities!

        if (is_array($data) && count($data) && $tableName) {
            foreach ($data as $datum) {
                $query = "INSERT INTO $tableName SET ";

                foreach ($datum as $field => $value) {
                    $query .= "`$field` = '$value', ";
                }

                $query = substr($query, 0, strlen($query)-2);
                //$db->prepare("INSERT INTO $tableName SET %s")->set($datum)->execute($query); // Funktioniert nicht!
                $db->execute($query);
            }
        }
    }
}
