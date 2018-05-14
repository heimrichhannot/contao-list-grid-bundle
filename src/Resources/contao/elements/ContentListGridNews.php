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

namespace HeimrichHannot\ListGrid;

class ContentListGridNews extends \ContentElement
{
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate           = new \BackendTemplate('be_listgrid_news');
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD'][$this->type][0]) . ' ###';
			$objTemplate->setData($this->objModel->row());

			if($this->size =! '')
			{
				$arrImageSizes = \System::getImageSizes();

				$arrSize = deserialize($this->objModel->size);

				if(isset($arrSize[2]) && isset($arrImageSizes['image_sizes'][$arrSize[2]]))
				{
					$objTemplate->imgSize = $arrImageSizes['image_sizes'][$arrSize[2]];
				}
			}

			$objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	protected function compile(){}
}