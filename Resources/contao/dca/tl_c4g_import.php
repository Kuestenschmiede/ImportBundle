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

/**
 * Set Tablename
 */
$strName = 'tl_c4g_import';


/**
 * Table tl_c4g_import
 */
$GLOBALS['TL_DCA'][$strName] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true/*,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)*/
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
            'panelLayout'             => 'sort,filter;search,limit',
			'flag'                    => 1
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
            'runimport' => array
            (
                'label'               => &$GLOBALS['TL_LANG'][$strName]['runimport'],
                'href'                => 'key=runimport',
                'icon'                => 'web/bundles/con4gisimport/import.png',
                'button_callback'     => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbGenerateButton'),
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['importConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            )
		)
	),

	// Select
	'select' => array
	(
		'buttons_callback' => array()
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('sourcekind', 'useinterval'),
		'default'                     => '{title_legend},title,description;{srcfile_legend},srcfile,headerline,renamefile,truncatetable;{sourcekind_legend},sourcekind;{expert_legend:hide},delimiter,enclosure;{usequeue_legend},usequeue,useinterval;'
	),

	// Subpalettes
	'subpalettes' => array
	(
	    'sourcekind_import'           => '{srctable_legend},srctable,namedfields',
        'sourcekind_create'           => '{srctable_legend},srctablename,fieldnames',
        'useinterval'                 => 'intervalkind,intervalcount'
	),

	// Fields
	'fields' => array
	(
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['title'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'alnum', 'nospace'=>false, 'spaceToUnderscore'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['description'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'clr', 'rte'=>'tinyMCE'),
            'sql'                     => "mediumtext NULL"
        ),
        'srcfile' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['srcfile'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'tl_class'=>'clr wizard'),
            'sql'                     => "blob NULL"
        ),
        'renamefile' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['renamefile'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'headerline' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['headerline'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12', 'submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'truncatetable' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['truncatetable'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'sourcekind' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['sourcekind'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('import', 'create'),
            'reference'               => $GLOBALS['TL_LANG'][$strName]['sourcekind_ref'],
            'eval'                    => array('tl_class'=>'clr', 'submitOnChange'=>true, 'includeBlankOption'=>true, 'chosen'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'srctable' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['srctable'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'cbGetTables'),
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr', 'submitOnChange'=>true, 'includeBlankOption'=>true, 'chosen'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'namedfields' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['namedfields'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'sql'                     => "blob NULL",
            'eval'                    => array
            (
                'tl_class' => 'clr',
                'columnFields' => array
                (
                    'destfields' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['destfields'],
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'select',
                        'options_callback'        => array('\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'cbGetFields'),
                        'eval'                    => array('mandatory'=>true,'chosen'=>true, 'includeBlankOption'=>true,'style'=>'width:200px'),
                    ),
                    'srccolumnname' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['srccolumnname'],
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'select',
                        'options_callback'        => array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'getFieldsFromFile'),
                        'eval'                    => array('mandatory'=>true,'chosen'=>true, 'includeBlankOption'=>true,'style'=>'width:200px')
                    ),
                    'defaultvalue' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['defaultvalue'],
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('style'=>'width:200px')
                    ),
                    'overridevalue' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['overridevalue'],
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'checkbox'
                    )
                )
            )
        ),
        'srctablename' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['srctablename'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'fieldnames' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['fieldnames'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'sql'                     => "blob NULL",
            'load_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbLoadFieldNames')),
            'save_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbConvertFieldNames')),
            'eval'                    => array
            (
                'tl_class' => 'clr',
                'columnFields' => array
                (
                    'destfields' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['destfields'],
                        'default'                 => '',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true,'style'=>'width:200px'),
                    ),
                    'fieldtype' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['fieldtype'],
                        'default'                 => 'varchar',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('style'=>'width:200px')
                    ),
                    'fieldlength' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['fieldlength'],
                        'default'                 => '255',
                        'exclude'                 => true,
                        'inputType'               => 'text',
                        'eval'                    => array('style'=>'width:200px')
                    )
                )
            )
        ),
        'additionaldata' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['additionaldata'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'clr'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'delimiter' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['delimiter'],
            'exclude'                 => true,
            'default'                 => ';',
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>1, 'tl_class'=>'w50', 'nospace'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'enclosure' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['enclosure'],
            'exclude'                 => true,
            'default'                 => '"',
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>1, 'tl_class'=>'w50', 'nospace'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'usequeue' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['usequeue'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'save_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbAddToQueue')),
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'useinterval' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['useinterval'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50', 'submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'intervalkind' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['intervalkind'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('hourly', 'daily', 'weekly', 'monthly', 'yearly'),
            'reference'               => $GLOBALS['TL_LANG'][$strName]['intervalkind_ref'],
            'eval'                    => array('tl_class'=>'w50', 'includeBlankOption'=>true, 'chosen'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'intervalcount' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['intervalcount'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50', 'rgxp'=>'natural'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
	)
);
