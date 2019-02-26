<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    6
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
namespace con4gis\ImportBundle\Classes\Contao\Callbacks;

use con4gis\CoreBundle\Classes\Helper\StringHelper;
use con4gis\ImportBundle\Classes\Events\ImportRunEvent;
use con4gis\QueueBundle\Classes\Queue\QueueManager;
use Contao\Controller;
use Contao\Image;
use Contao\Input;

/**
 * Class TlC4gImport
 * @package con4gis\ImportBundle\Classes\Contao\Callbacks
 */
class TlC4gImport
{


    /**
     * button_callback: Prüft, ob ein Import ausgeführt werden kann.
     * @param $arrRow
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     * @param $strTable
     * @param $arrRootIds
     * @param $arrChildRecordIds
     * @param $blnCircularReference
     * @param $strPrevious
     * @param $strNext
     * @return string
     */
    public function cbGenerateButton($arrRow,
                                     $href,
                                     $label,
                                     $title,
                                     $icon,
                                     $attributes,
                                     $strTable,
                                     $arrRootIds,
                                     $arrChildRecordIds,
                                     $blnCircularReference,
                                     $strPrevious,
                                     $strNext
    ) {

        if ($this->testImport($arrRow)) {
            $link   = '<a href="' . Controller::addToUrl($href) . '&id=' . $arrRow['id'];
            $link  .= '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label);
            $link  .= '</a> ';
        } else {
            $link = '<span style="opacity: 0.4;">' . Image::getHtml($icon, $label) . '</span>';
        }
        return $link;
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

        if  ($row['sourcekind'] == 'import' && (!isset($row['srctable']) || !isset($row['namedfields']))) {
            return false;
        }

        if ($row['sourcekind'] == 'create' && (!isset($row['srctablename']) || !isset($row['fieldnames']))) {
            return false;
        }

        if ($row['srctable'] == '' && $row['srctablename'] == '') {
            return false;
        }

        $file = \FilesModel::findByUuid($row['srcfile']);

        if (!$file || !is_file(TL_ROOT . '/' . $file->path)) {
            return false;
        }

        if ($row['sourcekind'] == 'import') {
            $fields = deserialize($row['namedfields'], true);

            if (!count($fields)) {
                return false;
            }
        }

        if ($row['sourcekind'] == 'create') {
            $fields = deserialize($row['fieldnames'], true);

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
            $event          = new ImportRunEvent();
            $qm             = new QueueManager();
            $interval       = '';
            $intervalcount  = '';

            if (Input::post('useinterval')) {
                $interval      = Input::post('intervalkind');
                $intervalcount = Input::post('intervalcount');
            }

            $metaData = array(
                'srcmodule'     => 'import',
                'srctable'      => 'tl_c4g_import',
                'srcid'         => $dc->id,
                'intervalkind'  => $interval,
                'intervalcount' => $intervalcount
            );

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
            $sh     = new StringHelper();
            $row    = $dc->activeRecord;
            $fields = $this->getFields($row->srcfile, $row->delimiter, $row->enclosure, $row->headerline);
            $i      = 1;

            foreach ($fields as $field) {
                if ($row->headerline) {
                    $temp['destfields'] = $field;
                } else {
                    $temp['destfields'] = $i;
                }

                $temp['fieldtype']  = 'varchar';
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


    /**
     * options_callback: Lädt die Felder einer CSV-Datei bzw. ein Array mit den Spaltennummern.
     * @param $dc
     * @return array
     */
    public function getFieldsFromFile($dc)
    {
        $row    = $dc->activeRecord;
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
        $file   = \FilesModel::findByUuid($srcfile);
        $data   = array();

        if ($file && is_file(TL_ROOT . '/' . $file->path)) {
            $content = file_get_contents(TL_ROOT . '/' . $file->path);

            if ($content) {
                $lines = explode(PHP_EOL, $content);

                if (is_array($lines) && count($lines)) {
                    $line      = array_shift($lines);

                    if ($line && $delimiter && $enclosure) {
                        $fields    = str_getcsv($line, $delimiter, $enclosure);
                        $i         = 1;

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
        $arrFields = deserialize($value);
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
}
