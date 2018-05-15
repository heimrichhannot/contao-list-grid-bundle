<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ContaoListGridBundle\Model;

use Contao\Model;

/**
 * Class ListGridModel.
 *
 * @property int    $id
 * @property int    $sorting
 * @property int    $tstamp
 * @property string $title
 */
class ListGridModel extends Model
{
    protected static $strTable = 'tl_listgrid';
}
