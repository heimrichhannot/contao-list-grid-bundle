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
use HeimrichHannot\ListBundle\Backend\ListConfigElement;
use HeimrichHannot\ListBundle\Event\ListAfterParseItemsEvent;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderItemEvent;
use HeimrichHannot\ListBundle\Event\ListCompileEvent;
use HeimrichHannot\UtilsBundle\Image\ImageUtil;
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
    /**
     * @var ImageUtil
     */
    private $imageUtil;

    public function __construct(ContaoFrameworkInterface $framework, Logger $logger, ImageUtil $imageUtil)
    {
        $this->framework = $framework;
        $this->logger = $logger;
        $this->imageUtil = $imageUtil;
    }

    public function onHuhListEventListCompile(ListCompileEvent $event)
    {
        if ($event->getListConfig()->listGrid < 1) {
            return;
        }

        $listConfig = $event->getListConfig();
        /** @var ListGridModel $config */
        $config = $this->framework->getAdapter(ListGridModel::class)->findByIdOrAlias($listConfig->listGrid);
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
                $this->templatePlaceholders[] = $item;
            }
        }
        $listConfig->perPage = \count($this->templatePlaceholders);
        $event->setListConfig($listConfig);
        reset($this->templatePlaceholders);
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
        if (false === next($this->templatePlaceholders)) {
            $this->templatePlaceholders = [];
        }

        $event->setTemplateName($placeholder->listGrid_placeholderTemplate);

        $templateData = $event->getTemplateData();
        $listImageConfig = $event->getItem()->getManager()->getListConfigElementRegistry()->findOneBy(['pid=?', 'type=?'], [$event->getItem()->getManager()->getListConfig()->id, ListConfigElement::TYPE_IMAGE]);
        if (!$listImageConfig) {
            return;
        }
        $templateData['listGrid']['addImage'] = false;
        if (isset($templateData['images'][$listImageConfig->imageField])
            && $templateData['images'][$listImageConfig->imageField][$listImageConfig->imageSelectorField]) {
            $imageConfig = [
                $listImageConfig->imageSelectorField => $templateData['images'][$listImageConfig->imageField][$listImageConfig->imageSelectorField],
                $listImageConfig->imageField => $templateData['images'][$listImageConfig->imageField][$listImageConfig->imageField],
                'size' => $placeholder->size,
            ];
            $this->imageUtil->addToTemplateData(
                $listImageConfig->imageField,
                $listImageConfig->imageSelectorField,
                $templateData['images'][$listImageConfig->imageField],
                $imageConfig);

            $templateData['listGrid']['addImage'] = true;
        }

        $event->setTemplateData($templateData);
    }

    public function onHuhListEventListAfterParseItems(ListAfterParseItemsEvent $event)
    {
        if ($event->getListConfig()->listGrid < 1 || !$this->config || !$this->templateItems) {
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
