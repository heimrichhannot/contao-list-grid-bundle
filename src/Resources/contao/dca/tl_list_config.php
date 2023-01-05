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

$dc = &$GLOBALS['TL_DCA']['tl_list_config'];

$dc['palettes']['default'] = str_replace('{sorting_legend}', '{listGrid_legend},listGrid;{sorting_legend}', $dc['palettes']['default']);

/**
 * Fields
 */
$dc['fields']['listGrid'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'      => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql'       => "int(10) unsigned NOT NULL default '0'",
];

\Contao\System::getContainer()->get('huh.utils.dca')->addOverridableFields(
    ['listGrid'],
    'tl_list_config',
    'tl_list_config',
    [
        'checkboxDcaEvalOverride' => [
            'tl_class' => 'w50 clr',
        ],
    ]
);