<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\ImportBundle\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ConvertDataEvent
 * @package con4gis\ImportBundle\Classes\Events
 */
class ConvertDataEvent extends Event
{
    /**
     * Name des Events
     */
    const NAME = 'con4gis.import.convert';

    /**
     * Entity mit den Einstellungen für den Import
     * @var null
     */
    protected $settings = null;

    /**
     * Daten für den Import.
     * @var array
     */
    protected $importData = [];

    /**
     * Array mit den Spaltenüberschriften
     * @var array
     */
    protected $fieldnames = [];

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
     * @return array
     */
    public function getImportData(): array
    {
        return $this->importData;
    }

    /**
     * @param array $importData
     */
    public function setImportData(array $importData)
    {
        $this->importData = $importData;
    }

    /**
     * @return array
     */
    public function getFieldnames(): array
    {
        return $this->fieldnames;
    }

    /**
     * @param array $fieldnames
     */
    public function setFieldnames(array $fieldnames)
    {
        $this->fieldnames = $fieldnames;
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
}
