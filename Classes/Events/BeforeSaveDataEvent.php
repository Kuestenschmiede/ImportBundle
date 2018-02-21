<?php
/**
 * con4gis
 * @version   php 7
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */
namespace con4gis\ImportBundle\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

class BeforeSaveDataEvent extends Event
{
    /**
     * Name of the event
     */
    const NAME = 'con4gis.import.beforesave';

    /**
     * Data to save
     * @var array
     */
    protected $data = array();

    /**
     * Entity mit den Einstellungen für den Import
     * @var null
     */
    protected $settings = null;

    /**
     * Zusätzliches Feld für Daten, die für Import-Erweiterungen gebraucht werden.
     * @var array
     */
    protected $additionalData = array();

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