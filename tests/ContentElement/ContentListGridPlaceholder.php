<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\Test\ContentElement;

use Contao\System;
use Contao\TestCase\ContaoTestCase;
use Symfony\Component\Translation\TranslatorInterface;

class ContentListGridPlaceholder extends ContaoTestCase
{
    public function testGenerateFe()
    {
        if (!defined('TL_MODE')) {
            define('TL_MODE', 'FE');
        }
        $this->addToAssertionCount(1);
    }

    public function skiptestGenerateBe()
    {
        if (!defined('TL_MODE')) {
            define('TL_MODE', 'BE');
        }
        $element = $this->getMockBuilder(self::class)->setMethods(['generate'])->disableOriginalConstructor()->getMock();
        $container = $this->mockContainer();
        $container->set('translator', $this->createMock(TranslatorInterface::class));
        System::setContainer($container);
    }
}
