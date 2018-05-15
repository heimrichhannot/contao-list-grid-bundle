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
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['list_grid'] = [
    'tables' => ['tl_listgrid', 'tl_content'],
    'icon'   => 'bundles/heimrichhannotcontaolistgrid/img/listgrid-icon.png',
];

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_listgrid'] = \HeimrichHannot\ContaoListGridBundle\Model\ListGridModel::class;

/**
 * Content Elements
 */
array_insert(
    $GLOBALS['TL_CTE'],
    0,
    ['listgrid' => [
        \HeimrichHannot\ContaoListGridBundle\ContentElement\ContentListGridPlaceholder::NAME =>
            \HeimrichHannot\ContaoListGridBundle\ContentElement\ContentListGridPlaceholder::class]
    ]);

/**
 * Listegrid Configurations
 */
$GLOBALS['LISTGRID_TYPES'] = array
(
	'news' => array
	(
		'types' => array('listgrid_news'),
	),
);