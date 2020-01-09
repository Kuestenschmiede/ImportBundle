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
namespace con4gis\ImportBundle\Classes\Events;

use con4gis\QueueBundle\Classes\Events\QueueEvent;

/**
 * Class ImportRunEvent
 * @package con4gis\ImportBundle\Classes\Events
 */
class ImportRunEvent extends QueueEvent
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
    protected $data = [];

    /**
     * Zusätzliches Feld für Daten, die für Import-Erweiterungen gebraucht werden.
     * @var array
     */
    protected $additionalData = [];

    /**
     * @var int
     */
    protected $dataCount = 0;

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

    /**
     * @return array
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    /**
     * @param array $additionalData
     */
    public function setAdditionalData(array $additionalData)
    {
        $this->additionalData = $additionalData;
    }

    /**
     * @return int
     */
    public function getDataCount(): int
    {
        return $this->dataCount;
    }

    /**
     * @param int $dataCount
     */
    public function setDataCount(int $dataCount): void
    {
        $this->dataCount = $dataCount;
    }
}
