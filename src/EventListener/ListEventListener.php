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


use HeimrichHannot\ListBundle\Event\ListBeforeParseItemsEvent;

class ListEventListener
{

    public function onHuhListEventListBeforeParseItems(ListBeforeParseItemsEvent $event)
    {
        return;
    }
}