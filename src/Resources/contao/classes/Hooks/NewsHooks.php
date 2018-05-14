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

namespace HeimrichHannot\ListGrid\Hooks;


class NewsHooks extends \Controller
{
	public function parseAllArticlesHook($arrArticles = array(), $blnAddArchive, $objModule)
	{
		$arrArticles = $this->getListGridList($arrArticles, $blnAddArchive, $objModule);

		return $arrArticles;
	}

	public function parseArticlesHook(&$objTemplate, $arrArticle, $objModule)
	{
		$this->getListGridArticle($objTemplate, $arrArticle, $objModule);
	}

	protected function getListGridList($arrArticles, $blnAddArchive, $objModule)
	{
		if(!$objModule->addListGrid) return $arrArticles;

		$objConfig = \HeimrichHannot\ListGrid\ListGridModel::findByPk($objModule->listGrid);

		$objConfigItems = \HeimrichHannot\ListGrid\ListGridContentModel::findPublishedByPidAndTypes($objConfig->id);

		if($objConfigItems === null) return $arrArticles;

		$arrArticlesList = array();

		$idxNews = 0;

		while($objConfigItems->next())
		{
			// skip listgrid_news, handled by static::getListGridTicket
			if($objConfigItems->type == 'listgrid_news')
			{
				$arrArticlesList[] = $arrArticles[$idxNews];
				$idxNews++;
				continue;
			}

			$arrArticlesList[] = \Controller::getContentElement($objConfigItems->id, $objModule->inColumn);
		}

		return $arrArticlesList;
	}

	protected function getListGridArticle(&$objTemplate, $arrArticle, $objModule)
	{
		if(!$objModule->addListGrid) return;

		$objConfig = \HeimrichHannot\ListGrid\ListGridModel::findByPk($objModule->listGrid);

		if($objConfig === null) return;

		$objConfigItems = \HeimrichHannot\ListGrid\ListGridContentModel::findPublishedByPidAndTypes($objConfig->id, array('listgrid_news'), 1, ($objTemplate->count - 1));

		if($objConfigItems === null) return;

		// overwrite template
		$objTemplate->setName($objConfigItems->news_template);

		
		// overwrite image size
		if($objTemplate->addImage && $objConfigItems->size != '')
		{
			$size = deserialize($objConfigItems->size);
			
			if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
			{
				$arrArticle['singleSRC'] = $objTemplate->singleSRC;
				$arrArticle['size'] = $objConfigItems->size;
				$this->addImageToTemplate($objTemplate, $arrArticle);
			}

		}
	}

}