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
use Contao\DC_Table;
/**
 * Set Tablename
 */
$strName = 'tl_c4g_import';


/**
 * Table tl_c4g_import
 */
$GLOBALS['TL_DCA'][$strName] = array
(
	'config' => array
	(
        'dataContainer'               => DC_Table::class,
		'enableVersioning'            => true
	),
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
            'panelLayout'             => 'sort,filter;search,limit',
			'flag'                    => 1,
            'icon'                    => 'bundles/con4giscore/images/be-icons/con4gis_blue.svg',
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => [
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			],
            'back' => [
                'href'                => 'key=back',
                'class'               => 'header_back',
                'button_callback'     => ['\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'back'],
                'icon'                => 'back.svg',
                'label'               => &$GLOBALS['TL_LANG']['MSC']['backBT'],
            ],
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			),
            'runimport' => array
            (
                'label'               => &$GLOBALS['TL_LANG'][$strName]['runimport'],
                'href'                => 'key=runimport',
                'icon'                => 'bundles/con4gisimport/images/be-icons/import.svg',
                'button_callback'     => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbGenerateButton'),
                'attributes'          => 'onclick="if(!confirm(\'' . key_exists($strName,$GLOBALS['TL_LANG']) && key_exists('importConfirm',$GLOBALS['TL_LANG'][$strName]) ? $GLOBALS['TL_LANG'][$strName]['importConfirm'] : '' . '\'))return false;Backend.getScrollOffset()"'
            )
		)
	),
	'select' => array
	(
		'buttons_callback' => array()
	),
	'edit' => array
	(
		'buttons_callback' => array()
	),
	'palettes' => array
	(
		'__selector__'                => array('sourcekind', 'useinterval', 'importaddresses'),
		'default'                     => '{title_legend},title,description;{srcfile_legend},srcfile,headerline,renamefile,truncatetable;{sourcekind_legend},sourcekind,importaddresses;{expert_legend:hide},delimiter,enclosure;{usequeue_legend},usequeue,useinterval;'
	),
	'subpalettes' => array
	(
	    'sourcekind_import'           => '{srctable_legend},saveTlTables,srctable,namedfields',
        'sourcekind_create'           => '{srctable_legend},saveTlTables,srctablename,fieldnames',
        'sourcekind_import_maps'      => '{srctable_legend},mapStructure,nameField,locstyleField,tooltipField,structureField,popupFields',
        'useinterval'                 => 'intervalkind,intervalcount',
        'importaddresses'             => 'addressfields,geotable,geoxfield,geoyfield'
	),
	'fields' => array
	(
	    'title' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'alnum', 'nospace'=>false, 'spaceToUnderscore'=>true),
        ),
        'description' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'clr', 'rte'=>'tinyMCE'),
        ),
        'srcfile' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'tl_class'=>'clr wizard'),
        ),
        'renamefile' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
        ),
        'headerline' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12', 'submitOnChange'=>true),
        ),
        'truncatetable' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
        ),
        'sourcekind' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('import', 'create', 'import_maps'),
            'reference'               => &$GLOBALS['TL_LANG'][$strName]['sourcekind_ref'],
            'eval'                    => array('tl_class'=>'clr', 'submitOnChange'=>true, 'includeBlankOption'=>true, 'chosen'=>true),
        ),
        'srctable' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'cbGetTables'),
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'clr', 'submitOnChange'=>true, 'includeBlankOption'=>true, 'chosen'=>true),
        ),
        'namedfields' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'eval'                    => array
            (
                'tl_class' => 'clr',
                'columnFields' => array
                (
                    'destfields' => array
                    (
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'select',
                        'options_callback'        => array('\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'cbGetFields'),
                        'eval'                    => array('mandatory'=>true,'chosen'=>true, 'includeBlankOption'=>true,'style'=>'width:200px'),
                    ),
                    'srccolumnname' => array
                    (
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'select',
                        'options_callback'        => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'),
                        'eval'                    => array('mandatory'=>false,'chosen'=>true, 'includeBlankOption'=>true,'style'=>'width:200px')
                    ),
                    'defaultvalue' => array
                    (
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('style'=>'width:200px')
                    ),
                    'overridevalue' => array
                    (
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'checkbox'
                    )
                )
            )
        ),
        'mapStructure' => [
            'default'                 => 0,
            'exclude'                 => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_c4g_maps.name',
            'eval'                    => ['tl_class'=>'clr'],
        ],
        'nameField' => [
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => ['\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'],
            'eval'                    => ['tl_class'=>'clr'],
        ],
        'locstyleField' => [
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => ['\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'],
            'eval'                    => ['tl_class'=>'clr', 'includeBlankOption' => true],
        ],
        'tooltipField' => [
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => ['\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'],
            'eval'                    => ['tl_class'=>'clr', 'includeBlankOption' => true],
        ],
        'structureField' => [
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => ['\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'],
            'eval'                    => ['tl_class'=>'clr', 'includeBlankOption' => true],
        ],
        'popupFields' => [
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => ['\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'],
            'eval'                    => ['mandatory'=>false,'chosen'=>true, 'includeBlankOption'=>true, 'multiple' => true],
        ],
        'importaddresses' => [
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'clr', 'submitOnChange'=>true),
        ],
        'addressfields' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getAddressGeoFields'),
            'eval'                    => array('mandatory'=>false,'chosen'=>true, 'includeBlankOption'=>true, 'multiple' => true),
        ),
        'geoxfield' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getAddressGeoFields'),
            'eval'                    => array('mandatory'=>false,'chosen'=>true, 'includeBlankOption'=>true),
        ),
        'geoyfield' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getAddressGeoFields'),
            'eval'                    => array('mandatory'=>false,'chosen'=>true, 'includeBlankOption'=>true),
        ),
        'srctablename' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'save_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbConvertTableName')),
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr'),
        ),
        'fieldnames' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'load_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbLoadFieldNames')),
            'save_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbConvertFieldNames')),
            'eval'                    => array
            (
                'tl_class' => 'clr',
                'columnFields' => array
                (
                    'destfields' => array
                    (
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true,'style'=>'width:200px', "submitOnChange" => true),
                    ),
                    'csvField' => array
                    (
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'select',
                        'options_callback'        => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'),
                        'eval'                    => array('mandatory'=>false, 'chosen'=>true, 'includeBlankOption'=>true,'style'=>'width:200px', 'submitOnChange' => true),
                    ),
                    'fieldtype' => array
                    (
                        'default'                 => 'varchar',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('style'=>'width:200px', "submitOnChange" => true)
                    ),
                    'fieldlength' => array
                    (
                        'default'                 => '255',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('style'=>'width:200px', "submitOnChange" => true)
                    )
                )
            )
        ),
        'additionaldata' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'clr'),
        ),
        'delimiter' => array
        (
            'exclude'                 => true,
            'default'                 => ';',
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>1, 'tl_class'=>'w50', 'nospace'=>true),
        ),
        'enclosure' => array
        (
            'exclude'                 => true,
            'default'                 => '"',
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>1, 'tl_class'=>'w50', 'nospace'=>true, 'preserveTags'=>true),
        ),
        'saveTlTables' => array
        (
            'exclude'                 => true,
            'default'                 => false,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class' => 'clr', 'submitOnChange' => true),
        ),
        'usequeue' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'save_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbAddToQueue')),
            'eval'                    => array('tl_class'=>'w50'),
        ),
        'useinterval' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50', 'submitOnChange'=>true),
        ),
        'intervalkind' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('hourly', 'daily', 'weekly', 'monthly', 'yearly'),
            'reference'               => &$GLOBALS['TL_LANG'][$strName]['intervalkind_ref'],
            'eval'                    => array('tl_class'=>'w50', 'includeBlankOption'=>true, 'chosen'=>true),
        ),
        'intervalcount' => array
        (
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50', 'rgxp'=>'natural'),
        )
	)
);
