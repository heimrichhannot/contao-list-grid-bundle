<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\Test;

use Contao\TestCase\ContaoTestCase;
use HeimrichHannot\ContaoListGridBundle\DependencyInjection\HeimrichHannotContaoListGridExtension;
use HeimrichHannot\ContaoListGridBundle\HeimrichHannotContaoListGridBundle;

class HeimrichHannotContaoListGridBundleTest extends ContaoTestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new HeimrichHannotContaoListGridBundle();
        $this->assertInstanceOf(HeimrichHannotContaoListGridBundle::class, $bundle);
    }

    public function testGetTheContainerExtension()
    {
        $bundle = new HeimrichHannotContaoListGridBundle();
        $this->assertInstanceOf(HeimrichHannotContaoListGridExtension::class, $bundle->getContainerExtension());
    }
}
