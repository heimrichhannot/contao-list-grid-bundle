<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\EventListener;

use Contao\Controller;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Model\Collection;
use HeimrichHannot\ContaoListGridBundle\ContentElement\ContentListGridPlaceholder;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridContentModel;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridModel;
use HeimrichHannot\ListBundle\Event\ListAfterParseItemsEvent;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderItemEvent;
use HeimrichHannot\ListBundle\Event\ListCompileEvent;
use Symfony\Bridge\Monolog\Logger;

class ListEventListener
{
    /**
     * @var ListGridModel|Adapter
     */
    protected $config;
    /**
     * @var ListGridContentModel|Collection|null
     */
    protected $templateItems;
    /**
     * @var ListGridContentModel|Collection|null
     */
    protected $templatePlaceholders = [];
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContaoFrameworkInterface $framework, Logger $logger)
    {
        $this->framework = $framework;
        $this->logger = $logger;
    }

    public function onHuhListEventListCompile(ListCompileEvent $event)
    {
        if ($event->getModule()->addListGrid && $event->getModule()->listGrid) {
            /** @var ListGridModel $config */
            $config = $this->framework->getAdapter(ListGridModel::class)->findByIdOrAlias($event->getModule()->listGrid);
            if (!$config) {
                return;
            }
            $this->config = $config;
            /* @var ListGridContentModel|Collection|null templateItems */
            $this->templateItems = $this->framework->getAdapter(ListGridContentModel::class)->findPublishedByPidAndTypes($this->config->id);
            if (!$this->templateItems) {
                return;
            }
            foreach ($this->templateItems as $item) {
                if (ContentListGridPlaceholder::NAME == $item->type) {
                    $this->templatePlaceholders = $item;
                }
            }
            reset($this->templatePlaceholders);
        }
    }

    public function onHuhListEventItemBeforeRender(ListBeforeRenderItemEvent $event)
    {
        if (!$this->config || empty($this->templatePlaceholders)) {
            return;
        }
        if (!$placeholder = current($this->templatePlaceholders)) {
            $this->templatePlaceholders = [];

            return;
        }
        $event->setTemplateName($placeholder->listGrid_placeholderTemplate);
        if (false === next($this->templatePlaceholders)) {
            $this->templatePlaceholders = [];
        }
    }

    public function onHuhListEventListAfterParseItems(ListAfterParseItemsEvent $event)
    {
        if (!$this->config || !$this->templateItems) {
            return;
        }
        $items = [];
        $pointer = 0;
        $listItems = $event->getParsedItems();
        foreach ($this->templateItems as $item) {
            if (ContentListGridPlaceholder::NAME == $item->type) {
                $items[] = $listItems[$pointer];
                ++$pointer;
                continue;
            }
            $items[] = $this->framework->getAdapter(Controller::class)->getContentElement($item);
        }
        $event->setParsedItems($items);
    }
}
