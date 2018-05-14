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


use Contao\Controller;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use HeimrichHannot\ContaoListGridBundle\ContentElement\ContentListGridElement;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridContentModel;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridModel;
use HeimrichHannot\ListBundle\Event\ListAfterParseItemsEvent;
use HeimrichHannot\ListBundle\Event\ListBeforeParseItemsEvent;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderEvent;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderItemEvent;
use HeimrichHannot\ListBundle\Event\ListCompileEvent;
use HeimrichHannot\SubColumnsBootstrapBundle\Backend\Content;
use Symfony\Bridge\Monolog\Logger;

class ListEventListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var ListGridModel|Adapter
     */
    protected $config;
    protected $templateItems;

    public function __construct(ContaoFrameworkInterface $framework, Logger $logger)
    {
        $this->framework = $framework;
        $this->logger = $logger;
    }

    public function onHuhListEventListCompile (ListCompileEvent $event)
    {
        if ($event->getModule()->addListGrid && $event->getModule()->listGrid)
        {
            /** @var ListGridModel $config */
            $config = $this->framework->getAdapter(ListGridModel::class)->findByIdOrAlias($event->getModule()->listGrid);
            if (!$config)
            {
                return;
            }
            $this->config = $config;
        }
        return;
    }

    public function onHuhListEventListAfterParseItems(ListAfterParseItemsEvent $event)
    {
        $templateItems = ListGridContentModel::findPublishedByPidAndTypes($this->config->id);
        if (!$templateItems) {
            return;
        }
        $items = [];
        $pointer = 0;
        $listItems = $event->getParsedItems();
        foreach ($templateItems as $item)
        {
            if ($item->type == ContentListGridElement::NAME) {
                $items[] = $listItems[$pointer];
                $pointer++;
                continue;
            }
            $items[] = $this->framework->getAdapter(Controller::class)->getContentElement($item);
        }
        $event->setParsedItems($items);
    }


    public function onHuhListEventItemBeforeRender(ListBeforeRenderItemEvent $event)
    {

        return;
    }
}