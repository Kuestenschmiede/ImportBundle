<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 *
 * con4gis
 * @version   php 7
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2017
 * @link      https://www.kuestenschmiede.de
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
		'enableVersioning'            => true,
		/*'sql' => array
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
		'__selector__'                => array('sourcekind'),
		'default'                     => '{title_legend},title,description;{srcfile_legend},srcfile,headerline,renamefile,truncatetable;{sourcekind_legend},sourcekind;{expert_legend:hide},delimiter,enclosure;'
	),

	// Subpalettes
	'subpalettes' => array
	(
	    'sourcekind_import'           => '{srctable_legend},srctable,namedfields;',
        'sourcekind_create'           => '{srctable_legend},srctablename,fieldnames;'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
		),
		'tstamp' => array
		(
		),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['title'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'alnum', 'nospace'=>true, 'spaceToUnderscore'=>true)
        ),
        'description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['description'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'clr', 'rte'=>'tinyMCE')
        ),
        'srcfile' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['srcfile'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'tl_class'=>'clr wizard')
        ),
        'renamefile' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['renamefile'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12')
        ),
        'headerline' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['headerline'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12')
        ),
        'truncatetable' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['truncatetable'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12')
        ),
        'sourcekind' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['sourcekind'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('import', 'create'),
            'reference'               => $GLOBALS['TL_LANG'][$strName]['sourcekind_ref'],
            'eval'                    => array('tl_class'=>'clr', 'submitOnChange'=>true, 'includeBlankOption'=>true, 'chosen'=>true)
        ),
        'srctable' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['srctable'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('\con4gis\CoreBundle\Classes\Helper\DcaHelper', 'cbGetTables'),
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr', 'submitOnChange'=>true, 'includeBlankOption'=>true, 'chosen'=>true)
        ),
        'namedfields' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['namedfields'],
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
                        'inputType'               => 'text',
                        'eval'                    => array('style'=>'width:200px')
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
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr')
        ),
        'fieldnames' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['fieldnames'],
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'load_callback'           => array(array('\con4gis\ImportBundle\Classes\Contao\Callbacks\TlC4gImport', 'cbLoadFieldNames')),
            'eval'                    => array
            (
                'tl_class' => 'clr',
                'columnFields' => array
                (
                    'destfields' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['destfields'],
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
            'eval'                    => array('tl_class'=>'clr')
        ),
        'delimiter' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['delimiter'],
            'exclude'                 => true,
            'default'                 => ';',
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>1, 'tl_class'=>'w50', 'nospace'=>true)
        ),
        'enclosure' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['enclosure'],
            'exclude'                 => true,
            'default'                 => '"',
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>1, 'tl_class'=>'w50', 'nospace'=>true)
        )
	)
);
