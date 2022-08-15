<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\StringUtil;
use Contao\System;

class ContentListGridPlaceholder extends ContentElement
{
    const NAME = 'huh_list_grid_element';

    public function generate()
    {
        if (TL_MODE == 'BE') {
            $container = System::getContainer();
            $trans = $container->get('translator');
            $template = $container->get('contao.framework')->createInstance(BackendTemplate::class, ['be_listgrid_placeholder']);
            $template->setData($this->objModel->row());
            $template->wildcard = '### '.strtoupper($trans->trans('huh.listgrid.cte.placeholder')).' ###';

            if ($this->size = !'') {
                $arrImageSizes = System::getContainer()->get('contao.image.image_sizes')->getAllOptions();

                $arrSize = StringUtil::deserialize($this->objModel->size);

                if (isset($arrSize[2]) && isset($arrImageSizes['image_sizes'][$arrSize[2]])) {
                    $template->imgSize = $arrImageSizes['image_sizes'][$arrSize[2]];
                }
            }

            $template->href = 'contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
    }
}
