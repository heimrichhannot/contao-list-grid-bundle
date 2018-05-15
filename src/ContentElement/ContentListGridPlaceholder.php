<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\StringUtil;
use Contao\System;
use Patchwork\Utf8;

class ContentListGridPlaceholder extends ContentElement
{
    const NAME = 'huh_list_grid_element';

    public function generate()
    {
        $trans = System::getContainer()->get('translator');
        if (TL_MODE == 'BE') {
            $objTemplate = new BackendTemplate('be_listgrid_placeholder');
            $objTemplate->setData($this->objModel->row());
            $objTemplate->wildcard = '### '.Utf8::strtoupper($trans->trans('huh.listgrid.cte.placeholder')).' ###';

            if ($this->size = !'') {
                $arrImageSizes = System::getContainer()->get('contao.image.image_sizes')->getAllOptions();

                $arrSize = StringUtil::deserialize($this->objModel->size);

                if (isset($arrSize[2]) && isset($arrImageSizes['image_sizes'][$arrSize[2]])) {
                    $objTemplate->imgSize = $arrImageSizes['image_sizes'][$arrSize[2]];
                }
            }

            $objTemplate->href = 'contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
    }
}
