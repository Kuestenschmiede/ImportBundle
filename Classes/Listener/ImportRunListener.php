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

use con4gis\ImportBundle\Classes\Events\BeforeSaveDataEvent;
use Contao\FilesModel;
use Doctrine\ORM\EntityManager;
use con4gis\CoreBundle\Classes\Helper\InputHelper;
use con4gis\ImportBundle\Classes\Events\ConvertDataEvent;
use con4gis\ImportBundle\Classes\Events\ImportRunEvent;
use con4gis\ImportBundle\Classes\Events\SaveDataEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ImportRunListener
 * @package con4gis\ImportBundle\Classes\Listener
 */
class ImportRunListener
{


    /**
     * @var EntityManager|null
     */
    protected $entityManager = null;


    /**
     * ImportRunListener constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }


    /**
     * Lädt die Einstellungen für den Import.
     * @param ImportRunEvent            $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onImportRunGetSettings(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $importId           = $event->getImportId();
        $respositoryName    = '\con4gis\ImportBundle\Entity\TlC4gImport';
        $respository        = $this->entityManager->getRepository($respositoryName);
        $importSettings     = $respository->find($importId);

        if ($importSettings && isset($GLOBALS['con4gis']['importsettings']['addditionalSettings'])) {
            $importSettings->setAdditionaldata($GLOBALS['con4gis']['importsettings']['addditionalSettings']);
        }

        $event->setSettings($importSettings);
    }


    /**
     * GET- und POST-Daten überschreiben die Einstellungen der Importkonfiguration.
     * @param ImportRunEvent            $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onImportRunGetInputData(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $importSettings     = $event->getSettings();
        $getData            = InputHelper::getAllData('get');
        $postData           = InputHelper::getAllData('post');
        $importSettings->setData($getData);
        $importSettings->setData($postData);

        $event->setSettings($importSettings);
    }


    /**
     * Erstellt den Pfad zur Importdatei.
     * @param ImportRunEvent            $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onImportRunGetPath(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $importSettings = $event->getSettings();
        $srcfile        = $importSettings->getSrcfile();
        $modleFiles     = FilesModel::findByUuid($srcfile);
        $path           = $modleFiles->path;
        $path           = TL_ROOT . '/' . $path;
        $event->setImportFile($path);
    }


    /**
     * Lädt die Daten für den Import aus der Datei.
     * @param ImportRunEvent            $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onImportRunLoadData(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $path = $event->getImportFile();

        if (is_file($path)) {
            $content = file_get_contents($path);
            $event->setImportData($content);
        }
    }


    /**
     * Ruft die Umwandlung der Daten auf.
     * @param ImportRunEvent            $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onImportRunConvertData(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings       = $event->getSettings();
        $importData     = $event->getImportData();
        $additionalData = $event->getAdditionalData();
        $convertEvent   = new ConvertDataEvent();
        $convertEvent->setSettings($settings);
        $convertEvent->setImportData($importData);
        $convertEvent->setData($event->getData());
        $convertEvent->setAdditionalData($additionalData);
        $dispatcher->dispatch($convertEvent::NAME, $convertEvent);
        $data           = $convertEvent->getData();
        $event->setData($data);
    }

    /**
     * Führt mögliche Aufrufe vor dem Speichern der Daten in die Datenbank aus.
     * @param ImportRunEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onImportRunBeforeSaveData(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings        = $event->getSettings();
        $data            = $event->getData();
        $beforeSaveEvent = new BeforeSaveDataEvent();
        $beforeSaveEvent->setData($data);
        $beforeSaveEvent->setSettings($settings);
        $dispatcher->dispatch($beforeSaveEvent::NAME, $beforeSaveEvent);
        $data = $beforeSaveEvent->getData();
        $event->setData($data);
    }


    /**
     * Ruft die Umwandlung der Daten auf.
     * @param ImportRunEvent            $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onImportRunSaveData(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $data       = $event->getData();
        $saveEvent  = new SaveDataEvent();
        $saveEvent->setSettings($settings);
        $saveEvent->setData($data);
        $dispatcher->dispatch($saveEvent::NAME, $saveEvent);
    }


    /**
     * Ruft die Umwandlung der Daten auf.
     * @param ImportRunEvent            $event
     * @param                           $eventName
     * @param EventDispatcherInterface  $dispatcher
     */
    public function onImportRunRenameFile(ImportRunEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings           = $event->getSettings();
        $path               = $event->getImportFile();
        $renameFile         = $settings->getRenamefile();
        $filerenamesuffix   = $settings->getAdditionaldata('filerenamesuffix');

        if ($renameFile && is_file($path) && $filerenamesuffix) {
            rename($path, $path . ".$filerenamesuffix");
        }
    }
}
