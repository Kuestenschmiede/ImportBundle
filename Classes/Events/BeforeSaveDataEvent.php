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
    protected $data = [];

    /**
     * Entity mit den Einstellungen für den Import
     * @var null
     */
    protected $settings = null;

    /**
     * Zusätzliches Feld für Daten, die für Import-Erweiterungen gebraucht werden.
     * @var array
     */
    protected $additionalData = [];

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
