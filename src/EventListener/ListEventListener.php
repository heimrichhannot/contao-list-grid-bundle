<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\EventListener;

use Contao\Controller;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Model\Collection;
use HeimrichHannot\ContaoListGridBundle\ContentElement\ContentListGridPlaceholder;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridContentModel;
use HeimrichHannot\ContaoListGridBundle\Model\ListGridModel;
use HeimrichHannot\ListBundle\Backend\ListConfigElement;
use HeimrichHannot\ListBundle\Event\ListAfterParseItemsEvent;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderEvent;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderItemEvent;
use HeimrichHannot\ListBundle\Event\ListCompileEvent;
use HeimrichHannot\UtilsBundle\Image\ImageUtil;
use HeimrichHannot\UtilsBundle\Util\Utils;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ListEventListener implements EventSubscriberInterface
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
     * @var ContaoFramework
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

    private RequestStack $requestStack;
    private Utils        $utils;

    public function __construct(ContaoFramework $framework, Logger $logger, ImageUtil $imageUtil, RequestStack $requestStack, Utils $utils)
    {
        $this->framework = $framework;
        $this->logger = $logger;
        $this->imageUtil = $imageUtil;
        $this->requestStack = $requestStack;
        $this->utils = $utils;
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

        $this->templatePlaceholders = [];
        foreach ($this->templateItems as $item) {
            if (ContentListGridPlaceholder::NAME == $item->type) {
                $this->templatePlaceholders[] = $item;
            }
        }
        $listConfig->perPage = \count($this->templatePlaceholders);
        reset($this->templatePlaceholders);
    }

    public function onHuhListEventItemBeforeRender(ListBeforeRenderItemEvent $event)
    {
        if ($event->getItem()->getManager()->getListConfig()->listGrid < 1 || !$this->config || empty($this->templatePlaceholders)) {
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
        $listImageConfig = $event->getItem()->getManager()->getListConfigElementRegistry()->findOneBy(
            ['tl_list_config_element.pid=?', 'tl_list_config_element.type=?'],
            [$event->getItem()->getManager()->getListConfig()->id, ListConfigElement::TYPE_IMAGE]
        );
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

    public function onListBeforeRenderEvent(ListBeforeRenderEvent $event): void
    {

        if (!$event->getListConfig()->listGrid
            || !$event->getListConfig()->addAjaxPagination
            || !ListGridModel::findByPk($event->getListConfig()->listGrid)
        ) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        if (!$request->isXmlHttpRequest()
            || !$request->headers->has('Huh-List-Request')
            || 'Ajax-Pagination' != $request->headers->get('Huh-List-Request')
        ) {
            return;
        }

        $templateData = $event->getTemplateData();
        $templateData['dataAttributes'] = ' '.trim(($templateData['dataAttributes'] ?? '').' data-mixed-content="1"');
        $event->setTemplateData($templateData);
    }

    public static function getSubscribedEvents()
    {
        return [
            ListCompileEvent::NAME => 'onHuhListEventListCompile',
            ListBeforeRenderItemEvent::NAME => 'onHuhListEventItemBeforeRender',
            ListAfterParseItemsEvent::NAME => 'onHuhListEventListAfterParseItems',
            ListBeforeRenderEvent::NAME => 'onListBeforeRenderEvent',
        ];
    }
}
