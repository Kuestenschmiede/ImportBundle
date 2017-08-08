<?php
/**
 * con4gis
 * @version   2.0.0
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2016 - 2017.
 * @link      https://www.kuestenschmiede.de
 */
namespace con4gis\ImportBundle\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ImportRunEvent
 * @package con4gis\ImportBundle\Classes\Events
 */
class ImportRunEvent extends Event
{


    /**
     * Name des Events
     */
    const NAME = 'con4gis.import.run';


    /**
     * Id der Importkonfiguration
     * @var int
     */
    protected $importId = 0;


    /**
     * Entity mit den Einstellungen für den Import
     * @var null
     */
    protected $settings = null;


    /**
     * Pfad zur Importdatei.
     * @var string
     */
    protected $importFile = '';


    /**
     * Daten für den Import.
     * @var string
     */
    protected $importData = '';


    /**
     * Daten für das Speichern in der Datenbank
     * @var array
     */
    protected $data = array();


    /**
     * @return int
     */
    public function getImportId(): int
    {
        return $this->importId;
    }


    /**
     * @param int $importId
     */
    public function setImportId(int $importId)
    {
        $this->importId = $importId;
    }


    /**
     * @return null
     */
    public function getSettings()
    {
        return $this->settings;
    }


    /**
     * @param null $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }


    /**
     * @return string
     */
    public function getImportFile(): string
    {
        return $this->importFile;
    }


    /**
     * @param string $importFile
     */
    public function setImportFile(string $importFile)
    {
        $this->importFile = $importFile;
    }


    /**
     * @return string
     */
    public function getImportData(): string
    {
        return $this->importData;
    }


    /**
     * @param string $importData
     */
    public function setImportData(string $importData)
    {
        $this->importData = $importData;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
}
