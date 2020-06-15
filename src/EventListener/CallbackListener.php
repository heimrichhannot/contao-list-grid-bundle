<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
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
     * Returns list grid configurations.
     *
     * @return array
     */
    public function getListGridConfigurations()
    {
        $options = [];

        /** @var ListGridModel|Collection|null $configs */
        $configs = $this->framework->getAdapter(ListGridModel::class)->findAll();

        if (!$configs) {
            return $options;
        }

        foreach ($configs as $config) {
            $options[$config->id] = $config->title;
        }

        return $options;
    }
}
