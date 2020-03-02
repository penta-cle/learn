<?php

/**
 * Created by PhpStorm.
 * User: Pentacle
 * Date: 2019/8/16
 * Time: 14:48
 */

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Learning extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
    public static function getall()
    {
        $arr = Learning::select();
        return $arr;
    }

}
