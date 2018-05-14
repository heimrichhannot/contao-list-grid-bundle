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

namespace HeimrichHannot\ListGrid\Backend;


class DcaManager extends \Backend
{

	public static function getConfigItemTypesByPid($intPid)
	{
		$groups = array();

		$objConfig = \HeimrichHannot\ListGrid\ListGridModel::findByPK($intPid);

		if($objConfig === null)
		{
			return $groups;
		}

		$arrAdded = array();

		foreach ($GLOBALS['TL_CTE'] as $k=>$v)
		{
			foreach (array_keys($v) as $kk)
			{
				$groups[$k][] = $kk;
				$arrAdded[] = $kk;
			}
		}
		
		return $groups;
	}

	/**
	 * Store initial values when creating a product
	 *
	 * @param   string $strTable
	 * @param   int    $insertID
	 * @param   array  $arrSet
	 */
	public function updateNewRecord($strTable, $insertID, $arrSet)
	{
		if ($arrSet['type'] != '') {
			return;
		}

		$arrTypes = static::getConfigItemTypesByPid($arrSet['pid']);

		if(empty($arrTypes)) return;

		\Database::getInstance()->prepare("UPDATE $strTable SET type=? WHERE id=?")->execute($arrTypes[0], $insertID);
	}

}