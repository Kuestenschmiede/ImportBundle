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
 * Class SaveDataEvent
 * @package con4gis\ImportBundle\Classes\Events
 */
class SaveDataEvent extends Event
{


    /**
     * Name des Events
     */
    const NAME = 'con4gis.import.save';


    /**
     * Entity mit den Einstellungen für den Import
     * @var null
     */
    protected $settings = null;


    /**
     * Daten für das Speichern in der Datenbank
     * @var array
     */
    protected $data = array();


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
