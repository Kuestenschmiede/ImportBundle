<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2025, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\ImportBundle\Classes\Contao\Callbacks;

use con4gis\CoreBundle\Classes\Helper\DcaHelper;
use con4gis\CoreBundle\Classes\Helper\StringHelper;
use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\ImportBundle\Classes\Events\ImportRunEvent;
use con4gis\QueueBundle\Classes\Queue\QueueManager;
use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\StringUtil;
use Contao\Controller;
use Contao\System;
use Contao\Image;
use Contao\Input;
use Contao\FilesModel;

/**
 * Class TlC4gImport
 * @package con4gis\ImportBundle\Classes\Contao\Callbacks
 */
class TlC4gImport
{
    /**
     * button_callback: Prüft, ob ein Import ausgeführt werden kann.
     */
    public function cbGenerateButton(DataContainerOperation $operation)
    {
        if (!$this->testImport($operation->getRecord())) {
            $operation->disable();
        }
    }

    /**
     * Prüft, ob die Mindestangaben für einen Import vorliegen.
     * @param $row
     * @return bool
     */
    protected function testImport($row)
    {
        if (!isset($row['srcfile']) || $row['srcfile'] == '') {
            return false;
        }

        if ($row['sourcekind'] == 'import' && (!isset($row['srctable']) || !isset($row['namedfields']))) {
            return false;
        }

        if ($row['sourcekind'] == 'create' && (!isset($row['srctablename']) || !isset($row['fieldnames']))) {
            return false;
        }

        if (($row['sourcekind'] !== 'import_maps') && ($row['srctable'] == '' && $row['srctablename'] == '')) {
            return false;
        }

        $file = FilesModel::findByUuid($row['srcfile']);
        $rootDir = System::getContainer()->getParameter('kernel.project_dir');

        if (!$file || !is_file($rootDir . '/' . $file->path)) {
            return false;
        }

        if ($row['sourcekind'] == 'import') {
            $fields = StringUtil::deserialize($row['namedfields'], true);

            if (!count($fields)) {
                return false;
            }
        }

        if ($row['sourcekind'] == 'create') {
            $fields = StringUtil::deserialize($row['fieldnames'], true);

            if (!count($fields)) {
                return false;
            }
        }

        return true;
    }

    /**
     * save_callback: Speichert den Import in der Queue.
     * @param $value
     * @param $dc
     * @return mixed
     */
    public function cbAddToQueue($value, $dc)
    {
        if ($value) {
            $event = new ImportRunEvent();
            $qm = new QueueManager();
            $interval = '';
            $intervalcount = '';

            if (Input::post('useinterval')) {
                $interval = Input::post('intervalkind');
                $intervalcount = Input::post('intervalcount');
            }

            $metaData = [
                'srcmodule' => 'import',
                'srctable' => 'tl_c4g_import',
                'srcid' => $dc->id,
                'intervalkind' => $interval,
                'intervalcount' => $intervalcount,
            ];

            if ($intervalcount) {
                $metaData['intervaltorun'] = $intervalcount;
            }

            $event->setImportId($dc->id);
            $qm->addToQueue($event, 1024, $metaData);
        }

        return $value;
    }

    /**
     * load_callback: Lädt die Namen der Spalten, falls diese in der Importdatei angegeben sind.
     * @param $value
     * @param $dc
     * @return mixed
     */
    public function cbLoadFieldNames($value, $dc)
    {
        if (!$value) {
            $sh = new StringHelper();
            $row = $dc->activeRecord;
            $fields = $this->getFields($row->srcfile, $row->delimiter, $row->enclosure, $row->headerline);
            $i = 1;

            foreach ($fields as $field) {
                if ($row->headerline) {
                    $temp['destfields'] = $field;
                } else {
                    $temp['destfields'] = $i;
                }

                $temp['fieldtype'] = 'varchar';
                $temp['fieldwidth'] = 255;
                $tmpValues[] = $sh->removeSpecialSigns($temp, '-_');
                $i++;
            }

            if (is_array($tmpValues) && count($tmpValues)) {
                $value = serialize($tmpValues);
            }
        }

        return $value;
    }

    public function cbConvertTableName($value, $dc)
    {
        if ($dc->activeRecord->saveTlTables !== '1') {
            if (C4GUtils::startsWith($value, 'tl_') === true) {
                return '';
            }
        }

        return $value;
    }

    /**
     * options_callback: Lädt die Felder einer CSV-Datei bzw. ein Array mit den Spaltennummern.
     * @param $dc
     * @return array
     */
    public function getFieldsFromFile($dc)
    {
        $row = $dc->activeRecord;

        return $this->getFields($row->srcfile, $row->delimiter, $row->enclosure, $row->headerline);
    }

    /**
     * Lädt die Felder einer CSV-Datei bzw. ein Array mit den Spaltennummern.
     * @param $srcfile
     * @param $delimiter
     * @param $enclosure
     * @param $headerline
     * @return array
     */
    protected function getFields($srcfile, $delimiter, $enclosure, $headerline)
    {
        $file = FilesModel::findByUuid($srcfile);
        $data = [];

        $rootDir = System::getContainer()->getParameter('kernel.project_dir');
        if ($file && is_file($rootDir . '/' . $file->path)) {
            $rootDir = System::getContainer()->getParameter('kernel.project_dir');
            $content = file_get_contents($rootDir . '/' . $file->path);

            if ($content) {
                $lines = explode(PHP_EOL, $content);

                if (is_array($lines) && count($lines)) {
                    $line = array_shift($lines);

                    if ($line && $delimiter) {
                        $fields = str_getcsv($line, $delimiter, $enclosure);
                        $i = 1;
                        if (count($fields) === 1) {
                            // check if fields are separable with the other possible delimiter
                            $tmpFields = str_getcsv($line, $delimiter === ';' ? ',': ';', $enclosure);
                            if (count($tmpFields) > 1) {
                                $fields = $tmpFields;
                            }
                        }

                        foreach ($fields as $field) {
                            if ($headerline) {
                                $data[] = $field;
                            } else {
                                $data[] = $i;
                            }

                            $i++;
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Converts specialchars from the fieldnames, so no &#40;-like stuff will be saved into the database.
     * @param $value
     * @param $dc
     * @return string
     */
    public function cbConvertFieldNames($value, $dc)
    {
        $arrFields = StringUtil::deserialize($value);
        foreach ($arrFields as $key => $field) {
            // catch specialchars in the fieldname
            $field['destfields'] = html_entity_decode($field['destfields']);
            // replace forbidden characters
            $field['destfields'] = str_replace('(', '_', $field['destfields']);
            $field['destfields'] = str_replace(')', '_', $field['destfields']);
            $field['destfields'] = str_replace(' ', '_', $field['destfields']);
            $arrFields[$key] = $field;
        }

        return serialize($arrFields);
    }

    public function getAddressGeoFields($dc)
    {
        $sourcekind = $dc->activeRecord->sourcekind;
        // case import
        switch ($sourcekind) {
            case 'import':
                $dh = new DcaHelper();

                return $dh->cbGetFields($dc);

                break;
            case 'create':
                $fields = StringUtil::deserialize($dc->activeRecord->fieldnames);
                $arrFields = [];
                foreach ($fields as $key => $field) {
                    $arrFields[$key] = $field['destfields'];
                }

                return $arrFields;

                break;
            case 'import_maps':
                return $this->getFieldsFromFile($dc);
        }
    }
}
