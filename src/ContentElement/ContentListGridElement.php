<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package listgrid
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\ContaoListGridBundle\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\StringUtil;
use Contao\System;
use Patchwork\Utf8;

class ContentListGridElement extends ContentElement
{
    const NAME = "huh_list_grid_element";

	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate           = new BackendTemplate('be_listgrid_news');
			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD'][$this->type][0]) . ' ###';
			$objTemplate->setData($this->objModel->row());

			if($this->size =! '')
			{
				$arrImageSizes = System::getContainer()->get('contao.image.image_sizes')->getAllOptions();

				$arrSize = StringUtil::deserialize($this->objModel->size);

				if(isset($arrSize[2]) && isset($arrImageSizes['image_sizes'][$arrSize[2]]))
				{
					$objTemplate->imgSize = $arrImageSizes['image_sizes'][$arrSize[2]];
				}
			}

			$objTemplate->href     = 'contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	protected function compile(){}
}