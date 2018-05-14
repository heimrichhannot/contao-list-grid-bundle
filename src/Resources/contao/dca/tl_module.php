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

$dc = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */
$dc['palettes']['__selector__'][] = 'addListGrid';
$dc['subpalettes']['addListGrid'] = 'listGrid';

$dc['palettes'][\HeimrichHannot\ListBundle\Backend\Module::MODULE_LIST] = str_replace('listConfig', 'listConfig,addListGrid', $dc['palettes'][\HeimrichHannot\ListBundle\Backend\Module::MODULE_LIST]);

/**
 * Fields
 */
$arrFields =
    [
        'addListGrid' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_module']['addListGrid'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'clr', 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'listGrid'    => [
            'label'            => &$GLOBALS['TL_LANG']['tl_module']['listGrid'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['huh.listgrid.listener.callbacks', 'getListGridConfigurations'],
            'reference'        => &$GLOBALS['TL_LANG']['tl_module'],
            'eval'             => ['includeBlankOption' => true, 'tl_class' => 'w50', 'mandatory' => true],
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ],
    ];

/**
 * Palettes
 */

$dc['fields'] = array_merge($dc['fields'], $arrFields);