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

$GLOBALS['BE_MOD']['con4gis'] = array_merge($GLOBALS['BE_MOD']['con4gis'], array(
    'c4g_import' => array(
        'brick' => 'import',
        'tables'    => array('tl_c4g_import'),
        'runimport' => array('\con4gis\ImportBundle\Classes\Contao\Modules\ModulImport', 'runImport'),
        'icon'      => 'bundles/con4giscore/images/be-icons/edit.svg'
    )
));



/**
 * Importsettings
 */
$GLOBALS['con4gis']['importsettings']['addditionalSettings'] = array(
    'filerenamesuffix' => 'old'
);
