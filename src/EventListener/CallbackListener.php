<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\ContaoListGridBundle\EventListener;


use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Model\Collection;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridModel;

class CallbackListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Returns list grid configurations
     *
     * @return array
     */
    public function getListGridConfigurations()
    {
        $options = [];

        /** @var ListGridModel|Collection|null $configs */
        $configs = $this->framework->getAdapter(ListGridModel::class)->findAll();

        if (!$configs)
        {
            return $options;
        }

        foreach ($configs as $config)
        {
            $strType = $GLOBALS['TL_LANG']['LISTGRID_TYPES'][$config->type];

            $options[$strType ? $strType : $config->type][$config->id] = $config->title;
        }

        return $options;
    }

    /**
     * Return all news config types as array
     *
     * @return array
     */
    public function getListGridTypes()
    {
        if (!is_array($GLOBALS['LISTGRID_TYPES'])) {
            return array();
        }

        return array_keys($GLOBALS['LISTGRID_TYPES']);
    }
}