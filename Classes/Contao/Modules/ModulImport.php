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
namespace con4gis\ImportBundle\Classes\Contao\Modules;

use con4gis\ImportBundle\Classes\Events\ImportRunEvent;
use Contao\BackendTemplate;
use Contao\System;

/**
 * Class ModulImport
 * @package con4gis\ImportBundle\Classes\Contao\Modules
 */
class ModulImport
{
    /**
     * Template
     * @var string
     */
    protected $templateName = 'be_mod_import';

    /**
     * @var \Contao\BackendTemplate|null
     */
    protected $template = null;

    /**
     * Instanz von doctrine.orm.default_entity_manager
     * @var null|object
     */
    protected $entityManager = null;

    /**
     * Instanz des Symfony EventDispatchers
     * @var null
     */
    protected $dispatcher = null;

    /**
     * Id der Importkonfiguration
     * @var int
     */
    protected $importId = 0;

    /**
     * ModulImport constructor.
     * @param null $em
     * @param null $dispatcher
     */
    public function __construct($em = null, $dispatcher = null)
    {
        if (($em !== null)) {
            $this->entityManager = $em;
        } else {
            $this->entityManager = \Contao\System::getContainer()->get('doctrine.orm.default_entity_manager');
        }

        if ($dispatcher !== null) {
            $this->dispatcher = $dispatcher;
        } else {
            $this->dispatcher = \Contao\System::getContainer()->get('event_dispatcher');
        }

        System::loadLanguageFile('default');
        $this->template = new BackendTemplate($this->templateName);
        $this->importId = (isset($_GET['id'])) ? $_GET['id'] : 0;
        $this->importId = (isset($_POST['id'])) ? $_POST['id'] : $this->importId;
        // Contao funktioniert nicht: \Contao\Environment::get('id');
    }

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
     * Generate the module
     * @return string
     */
    public function runImport()
    {
        $event = new ImportRunEvent();
        $event->setImportId($this->importId);
        $this->dispatcher->dispatch($event::NAME, $event);
        $this->template->dataCount = $event->getDataCount();

        return $this->template->parse();
    }
}
