<?php

/**
 * con4gis
 * @version   php 7
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

$GLOBALS['con4gis']['import']['installed'] = true;

$GLOBALS['BE_MOD']['con4gis'] = array_merge($GLOBALS['BE_MOD']['con4gis'], array(
    'import' => array(
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