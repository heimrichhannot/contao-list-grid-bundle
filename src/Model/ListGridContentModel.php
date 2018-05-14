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

namespace HeimrichHannot\ContaoListGridBundle\Model;


use Contao\ContentModel;
use Contao\Model\Collection;

class ListGridContentModel extends ContentModel
{

	/**
	 * Find all published content elements by their parent ID and parent table and content types with limit and offset parameter
	 *
	 * @param integer $pid         	The article ID
	 * @param array   $types 		Content types as array
	 * @param integer $limit 		Limit items
	 * @param integer $offset        Offset for limiting items
	 * @param array   $options     	An optional options array
	 *
	 * @return Collection|ContentModel|null A collection of models or null if there are no content elements
	 */
	public static function findPublishedByPidAndTypes($pid, $types = [], $limit=0, $offset=0, array $options = [])
	{
		$t = static::$strTable;

		$arrColumns = array("$t.pid=? AND $t.ptable=?");

		if(is_array($types) && !empty($types))
		{
			$arrColumns[] = "$t.type IN('" . implode("','", $types) . "')";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.invisible=''";
		}

		if (!isset($options['order']))
		{
			$options['order'] = "$t.sorting";
		}

		$options['limit']  = $limit;
		$options['offset'] = $offset;

		return static::findBy($arrColumns, [$pid, 'tl_listgrid'], $options);
	}
}