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
 * Class TlCon4gisImport
 * @package con4gis\ImportBundle\Classes\Contao\Callbacks
 */
class TlCon4gisImport
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

        if (!isset($row['srctable']) || !isset($row['srcfile']) || !isset($row['namedfields'])) {
            return false;
        }

        if ($row['srctable'] == '') {
            return false;
        }

        $file = \FilesModel::findByUuid($row['srcfile']);

        if (!$file || !is_file(TL_ROOT . '/' . $file->path)) {
            return false;
        }

        $fields = deserialize($row['namedfields'], true);

        if (!count($fields)) {
            return false;
        }

        return true;
    }
}
