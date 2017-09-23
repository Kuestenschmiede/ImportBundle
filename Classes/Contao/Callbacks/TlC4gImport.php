<?php
/**
 * con4gis
 * @version   php 7
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2017
 * @link      https://www.kuestenschmiede.de
 */
namespace con4gis\ImportBundle\Classes\Contao\Callbacks;

use Contao\Controller;
use Contao\Image;

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
     * Lädt die Namen der Spalten, falls diese in der Importdatei angegeben sind.
     * @param $value
     * @param $dc
     * @return mixed
     */
    public function cbLoadFieldNames($value, $dc)
    {
        if (!$value) {
            $row    = $dc->activeRecord;
            $file   = \FilesModel::findByUuid($row->srcfile);

            if ($file && is_file(TL_ROOT . '/' . $file->path)) {
                $content = file_get_contents(TL_ROOT . '/' . $file->path);

                if ($content) {
                    $lines = explode(PHP_EOL, $content);

                    if (is_array($lines) && count($lines)) {
                        $line      = array_shift($lines);
                        $delimiter = $row->delimiter;
                        $enclosure = $row->enclosure;

                        if ($line && $delimiter && $enclosure) {
                            $fields     = str_getcsv($line, $delimiter, $enclosure);
                            $tmpValues  = array();

                            foreach ($fields as $field) {
                                $temp['destfields'] = $field;
                                $temp['fieldtype']  = 'varchar';
                                $temp['fieldwidth'] = 255;
                                $tmpValues[] = $temp;
                            }

                            if (is_array($tmpValues) && count($tmpValues)) {
                                $value = serialize($tmpValues);
                            }
                        }
                    }
                }
            }
        }

        return $value;
    }
}
