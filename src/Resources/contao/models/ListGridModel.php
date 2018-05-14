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

class ListGridModel extends \Model
{
	protected static $strTable = 'tl_listgrid';

	public static function findMultipleByTypes(array $arrTypes=array(), $arrOptions = array())
	{
		$t = static::$strTable;

		$arrColumns[] = "$t.type IN('" . implode("','", $arrTypes) . "')";

		return static::findBy($arrColumns, null, $arrOptions);
	}

}