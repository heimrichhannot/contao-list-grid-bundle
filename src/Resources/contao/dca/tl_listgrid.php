<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package listgrid
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

/**
 * Table tl_slick_config
 */
$GLOBALS['TL_DCA']['tl_listgrid'] = array(

	// Config
	'config'   => array(
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'ctable'           => array('tl_content'),
		'sql'              => array(
			'keys' => array(
				'id' => 'primary',
			),
		),
	),
	// List
	'list'     => array(
		'sorting'           => array
		(
			'mode'        => 1,
			'flag'        => 3,
			'fields'      => array('sorting'),
			'panelLayout' => 'filter;search,limit',
			'fields'      => array('title'),
		),
		'label'             => array
		(
			'fields' => array('title'),
			'format' => '%s',
		),
		'global_operations' => array(
			'all' => array(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"',
			),
		),
		'operations'        => array(
			'edit'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['edit'],
				'href'  => 'table=tl_content',
				'icon'  => 'edit.gif',
			),
			'editheader' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['editmeta'],
				'href'  => 'act=edit',
				'icon'  => 'header.gif',
			),
			'copy'       => array(
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif',
			),
			'delete'     => array(
				'label'      => &$GLOBALS['TL_LANG']['tl_listgrid']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm']
								. '\'))return false;Backend.getScrollOffset()"',
			),
			'show'       => array(
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif',
			),
		),
	),
	// Palettes
	'palettes' => array
	(
		'__selector__' => array('type'),
		'default'      => '{title_legend},type',
		'news'         => '{title_legend},type,title',
	),
	// Fields
	'fields'   => array(
		'id'      => array(
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		),
		'sorting' => array(
			'sorting' => true,
			'flag'    => 2,
			'sql'     => "int(10) unsigned NOT NULL default '0'",
		),
		'tstamp'  => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'type'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_listgrid']['type'],
			'default'          => 'news',
			'exclude'          => true,
			'filter'           => true,
			'inputType'        => 'select',
			'options_callback' => array('tl_listgrid', 'getListGridConfigurations'),
			'reference'        => &$GLOBALS['TL_LANG']['LISTGRID_TYPES'],
			'eval'             => array('helpwizard' => true, 'chosen' => true, 'submitOnChange' => true),
			'sql'              => "varchar(32) NOT NULL default ''",
		),
		'title'   => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_listgrid']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('mandatory' => true, 'maxlength' => 255),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
	),
);

class tl_listgrid extends Backend
{

	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Return all news config types as array
	 *
	 * @return array
	 */
	public function getListGridConfigurations()
	{
		if (!is_array($GLOBALS['LISTGRID_TYPES'])) {
			return array();
		}

		return array_keys($GLOBALS['LISTGRID_TYPES']);
	}
}

