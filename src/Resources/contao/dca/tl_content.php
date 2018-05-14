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

$dc = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Dynamically add parent table
 */
if (Input::get('do') == 'listgrid')
{
    $dc['config']['ptable']                   = 'tl_listgrid';
    $dc['config']['oncreate_callback'][]      = ['HeimrichHannot\ListGrid\Backend\DcaManager', 'updateNewRecord'];
    $dc['fields']['type']['options_callback'] = ['tl_content_listgrid', 'getDependendTypes'];

    $dc['palettes']['listgrid_news'] = '{title_legend},type;{template_legend},news_template, size';

} else
{
    // drop newsconfig content elements from tl_content type dropdown
    unset($GLOBALS['TL_CTE']['newsconfig']);
}

/**
 * Fields
 */

/**
 * @todo rename to itemTemplate
 */
$arrFields = [
    'news_template' => [
        'label'            => &$GLOBALS['TL_LANG']['tl_list_config']['itemTemplate'],
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => ['huh.list.choice.template.item', 'getCachedChoices'],
        'eval'             => ['tl_class' => 'w50 clr', 'includeBlankOption' => true],
        'sql'              => "varchar(128) NOT NULL default ''",
    ],
];

$dc['fields'] = array_merge($dc['fields'], $arrFields);


class tl_content_listgrid extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function getDependendTypes($dc)
    {
        $arrTypes = [];

        if (\Input::get('act') != 'edit')
        {
            return $arrTypes;
        }

        // Return if there is no active record (override all)
        if (!$dc->activeRecord)
        {
            return $arrTypes;
        }

        return HeimrichHannot\ListGrid\Backend\DcaManager::getConfigItemTypesByPid($dc->activeRecord->pid);
    }


}
