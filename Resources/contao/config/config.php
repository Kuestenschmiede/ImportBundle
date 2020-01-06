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

$GLOBALS['con4gis']['import']['installed'] = true;
array_insert($GLOBALS['BE_MOD']['con4gis'], array_search('con4gis', array_keys($GLOBALS['BE_MOD'])) + 1, array(
    'c4g_import' => array(
        'tables'        => array('tl_c4g_import'),
        'runimport'     => array('\con4gis\ImportBundle\Classes\Contao\Modules\ModulImport', 'runImport')
    )
));



/**
 * Importsettings
 */
$GLOBALS['con4gis']['importsettings']['addditionalSettings'] = array(
    'filerenamesuffix' => 'old'
);

if(TL_MODE == "BE") {
    $GLOBALS['TL_CSS'][] = '/bundles/con4gisimport/css/con4gis.css';
}
