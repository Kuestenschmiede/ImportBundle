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
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */
/**
 * Set Tablename
 */
$strName = 'tl_c4g_import';


/**
 * Set Elementname
 */
$strElement = 'Import';


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['title']              = array('Title', 'Please enter the title.');
$GLOBALS['TL_LANG'][$strName]['description']        = array('Description', 'Please enter a descriptionm.');
$GLOBALS['TL_LANG'][$strName]['srckind']            = array('Kind of field assignment', 'Please choose the kind of field assignment.');
$GLOBALS['TL_LANG'][$strName]['sourcekind']         = array('Kind of Import', 'Please choose the kind of import.');
$GLOBALS['TL_LANG'][$strName]['srctable']           = array('Table', 'Please choose the source table. When changing the file, the data set MUST be saved so the changes in the fields are visible!');
$GLOBALS['TL_LANG'][$strName]['orderedfields']      = array('Fields', 'Please choose the fields for the import and arrange them in the same order as in the import file. The column names in the created table are then identical to those in the import file.');
$GLOBALS['TL_LANG'][$strName]['namedfields']        = array('Fields', 'Please choose the fields for the import and enter the column headers as they are present in the import file.');
$GLOBALS['TL_LANG'][$strName]['destfields']         = array('Target Field', 'PLease choose the target field of the database.');
$GLOBALS['TL_LANG'][$strName]['srccolumnname']      = array('Source column or -number', 'Please enter the header or number of the source column in the import file.');
$GLOBALS['TL_LANG'][$strName]['srcfile']            = array('Source file', 'Please choose the source file.');
$GLOBALS['TL_LANG'][$strName]['renamefile']         = array('Rename Source File', 'Please choose whether the source file should be renamed after the import.');
$GLOBALS['TL_LANG'][$strName]['truncatetable']      = array('Delete Table', 'Please choose whether the target table should be deleted before the import.');
$GLOBALS['TL_LANG'][$strName]['defaultvalue']       = array('Default value', 'If given, the default value will be imported instead of the data in the import file.');
$GLOBALS['TL_LANG'][$strName]['headerline']         = array('Column headers are present', 'PLease choose whether the first line in the import file has the column headers.');
$GLOBALS['TL_LANG'][$strName]['overridevalue']      = array('Override Import Value', 'Please choose whether the default value should always override the value in the import file or only be included if the import file has no corresponding value.');
$GLOBALS['TL_LANG'][$strName]['delimiter']          = array('Field Separator', 'Please choose the field separator. Default: ;');
$GLOBALS['TL_LANG'][$strName]['enclosure']          = array('Text marker character', 'Please choose the text marker character. Default: "');
$GLOBALS['TL_LANG'][$strName]['fieldnames']         = array('Fields', 'Please choose the fields for the import and arrange them in the same order as in the import file. The column names in the created table are then identical to those in the import file.');
$GLOBALS['TL_LANG'][$strName]['srctablename']       = array('Target Table', 'Please enter the name of the table');
$GLOBALS['TL_LANG'][$strName]['fieldtype']          = array('Field Type', 'Please enter the field type.');
$GLOBALS['TL_LANG'][$strName]['fieldlength']        = array('Field Length', 'Please enter the length of the fields.');
$GLOBALS['TL_LANG'][$strName]['usequeue']           = array('Process via Queue', 'Task processing via queue.');
$GLOBALS['TL_LANG'][$strName]['useinterval']        = array('Interval Execution', 'Please choose whether the task should be repeated in a set interval.');
$GLOBALS['TL_LANG'][$strName]['intervalkind']       = array('Interval', 'Please choose the interval in which to repeat the task.');
$GLOBALS['TL_LANG'][$strName]['intervalcount']      = array('Maximum Number of Executions', 'Please choose whether the task should finish after a set number of executions. Leave empty for infinite executions.');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['title_legend']       = 'Title';
$GLOBALS['TL_LANG'][$strName]['srctable_legend']    = 'Table';
$GLOBALS['TL_LANG'][$strName]['srcfile_legend']     = 'Source file';
$GLOBALS['TL_LANG'][$strName]['expert_legend']      = 'Expert Settings';
$GLOBALS['TL_LANG'][$strName]['usequeue_legend']    = 'Queue Settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG'][$strName]['srckind_ref']        = array('byorder' => 'Arrange fields by order in the file (Column names must NOT be in the file!)', 'byname' => 'Arrange fields by column names (Column names MUST be in the first line of the file!)');
$GLOBALS['TL_LANG'][$strName]['sourcekind_ref']     = array('import' => 'Import only', 'create' => 'Create tables and import');
$GLOBALS['TL_LANG'][$strName]['intervalkind_ref']   = array('hourly' => 'hourly', 'daily' => 'daily', 'weekly' => 'weekly', 'monthly' => 'monthly', 'yearly' => 'yearly');


/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['runimport']          = array('Begin Import', 'Begin Import');


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strName]['new']        = array('New ' . $strElement, 'New ' . $strElement . '');
$GLOBALS['TL_LANG'][$strName]['edit']       = array('Edit ' . $strElement, 'Edit ' . $strElement . ' with ID %s');
$GLOBALS['TL_LANG'][$strName]['copy']       = array('Copy ' . $strElement, 'Copy ' . $strElement . ' with ID %s');
$GLOBALS['TL_LANG'][$strName]['delete']     = array('Delete ' . $strElement, 'Delete ' . $strElement . ' with ID %s');
$GLOBALS['TL_LANG'][$strName]['show']       = array('Show ' . $strElement, 'Show details of the ' . $strElement . 'with ID %s');
