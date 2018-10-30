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

\Contao\Controller::loadDataContainer('tl_list_config_element');

$dc = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Dynamically add parent table
 */
if (Input::get('do') == 'list_grid') {
    $dc['config']['ptable'] = 'tl_listgrid';
    
    $dc['palettes'][\HeimrichHannot\ContaoListGridBundle\ContentElement\ContentListGridPlaceholder::NAME] = '{title_legend},type;
    {template_legend},listGrid_placeholderTemplate;
    {image_legend},size,
    {invisible_legend:hide},invisible,start,stop';
    
} else {
    // drop newsconfig content elements from tl_content type dropdown
    unset($GLOBALS['TL_CTE'][\HeimrichHannot\ContaoListGridBundle\ContentElement\ContentListGridPlaceholder::NAME]);
}

/**
 * Fields
 */
$arrFields = [
    'listGrid_placeholderTemplate' => [
        'label'            => &$GLOBALS['TL_LANG']['tl_list_config']['itemTemplate'],
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => ['huh.list.choice.template.item', 'getCachedChoices'],
        'eval'             => ['tl_class' => 'w50 clr', 'includeBlankOption' => true],
        'sql'              => "varchar(128) NOT NULL default ''",
    ],
];

$dc['fields']['invisible']['eval']['tl_class'] = 'w50 clr';
$dc['fields']['start']['eval']['tl_class'] = 'w50 clr';
$arrFields['listGrid_imageSelectorField']              = $GLOBALS['TL_DCA']['tl_list_config_element']['fields']['imageSelectorField'];
$arrFields['listGrid_imageField']                      = $GLOBALS['TL_DCA']['tl_list_config_element']['fields']['imageField'];
$arrFields['listGrid_placeholderImage']                = $GLOBALS['TL_DCA']['tl_list_config_element']['fields']['placeholderImage'];

$dc['fields'] = array_merge($dc['fields'], $arrFields);
