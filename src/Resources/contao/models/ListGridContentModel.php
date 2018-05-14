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


class ListGridContentModel extends \ContentModel
{

	/**
	 * Find all published content elements by their parent ID and parent table and content types with limit and offset parameter
	 *
	 * @param integer $intPid         	The article ID
	 * @param string  $strParentTable 	The parent table name
	 * @param array   $arrTypes 		Content types as array
	 * @param integer $intLimit 		Limit items
	 * @param integer $intOffset        Offset for limiting items
	 * @param array   $arrOptions     	An optional options array
	 *
	 * @return \Model\Collection|\ContentModel|null A collection of models or null if there are no content elements
	 */
	public static function findPublishedByPidAndTypes($intPid, $arrTypes = array(), $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		$t = static::$strTable;

		$arrColumns = array("$t.pid=? AND $t.ptable=?");

		if(is_array($arrTypes) && !empty($arrTypes))
		{
			$arrColumns[] = "$t.type IN('" . implode("','", $arrTypes) . "')";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.invisible=''";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, array($intPid, 'tl_listgrid'), $arrOptions);
	}
}