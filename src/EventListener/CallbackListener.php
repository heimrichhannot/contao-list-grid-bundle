<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\Model\Collection;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridModel;

class CallbackListener
{
    private ContaoFramework $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    /**
     * @Callback(table="tl_list_config", target="fields.listGrid.options")
     */
    public function onListGridOptionsCallback(): array
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
