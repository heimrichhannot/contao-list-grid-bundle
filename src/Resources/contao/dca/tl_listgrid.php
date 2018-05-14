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
$GLOBALS['TL_DCA']['tl_listgrid'] = [

	// Config
	'config'   => [
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'ctable'           => ['tl_content'],
		'sql'              => [
			'keys' => [
				'id' => 'primary',
            ],
        ],
    ],
	// List
	'list'     => [
		'sorting'           =>
            [
			'mode'        => 1,
			'flag'        => 3,
			'fields'      => ['sorting'],
			'panelLayout' => 'filter;search,limit',
			'fields'      => ['title'],
            ],
		'label'             =>
            [
			'fields' => ['title'],
			'format' => '%s',
            ],
		'global_operations' => [
			'all' => [
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"',
            ],
        ],
		'operations'        => [
			'edit'       =>
                [
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['edit'],
				'href'  => 'table=tl_content',
				'icon'  => 'edit.gif',
                ],
			'editheader' =>
                [
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['editmeta'],
				'href'  => 'act=edit',
				'icon'  => 'header.gif',
                ],
			'copy'       => [
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif',
            ],
			'delete'     => [
				'label'      => &$GLOBALS['TL_LANG']['tl_listgrid']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm']
								. '\'))return false;Backend.getScrollOffset()"',
            ],
			'show'       => [
				'label' => &$GLOBALS['TL_LANG']['tl_listgrid']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif',
            ],
        ],
    ],
	// Palettes
	'palettes' =>
        [
		'__selector__' => ['type'],
		'default'      => '{title_legend},type',
		'news'         => '{title_legend},type,title',
        ],
	// Fields
	'fields'   => [
		'id'      => [
			'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
		'sorting' => [
			'sorting' => true,
			'flag'    => 2,
			'sql'     => "int(10) unsigned NOT NULL default '0'",
        ],
		'tstamp'  =>
            [
			'sql' => "int(10) unsigned NOT NULL default '0'",
            ],
		'type'    =>
            [
			'label'            => &$GLOBALS['TL_LANG']['tl_listgrid']['type'],
			'default'          => 'news',
			'exclude'          => true,
			'filter'           => true,
			'inputType'        => 'select',
			'options_callback' => ['huh.listgrid.listener.callbacks', 'getListGridTypes'],
			'reference'        => &$GLOBALS['TL_LANG']['LISTGRID_TYPES'],
			'eval'             => ['helpwizard' => true, 'chosen' => true, 'submitOnChange' => true],
			'sql'              => "varchar(32) NOT NULL default ''",
            ],
		'title'   => [
			'label'     => &$GLOBALS['TL_LANG']['tl_listgrid']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => ['mandatory' => true, 'maxlength' => 255],
			'sql'       => "varchar(255) NOT NULL default ''",
        ],
    ],
];