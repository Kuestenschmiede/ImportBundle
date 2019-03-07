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

$GLOBALS['BE_MOD']['con4gis_core'] = array_merge($GLOBALS['BE_MOD']['con4gis_core'], array(
    'import' => array(
        'tables'        => array('tl_c4g_import'),
        'runimport'     => array('\con4gis\ImportBundle\Classes\Contao\Modules\ModulImport', 'runImport')
    )
));

//$GLOBALS['BE_MOD']['con4gis'] =
//    \con4gis\CoreBundle\Resources\contao\classes\C4GUtils::sortBackendModules($GLOBALS['BE_MOD']['con4gis']);

/**
 * Importsettings
 */
$GLOBALS['con4gis']['importsettings']['addditionalSettings'] = array(
    'filerenamesuffix' => 'old'
);