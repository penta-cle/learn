<?php

/**
 * Created by PhpStorm.
 * User: Pentacle
 * Date: 2019/8/16
 * Time: 14:48
 */

namespace app\api\model;

use think\Model;
use traits\model\SoftDelete;

class Learn extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
    public static function getall()
    {
        $arr = Learn::select();
        return $arr;
    }
    public static function getId($id)
    {

        $Article = Learn::find($id);
        if ($Article == NULL)
            return [];
        return $Article;
    }
    //判断毕业状态
    public function getGraduateAttr($value)
    {
        $status = [0 => "未毕业", 1 => "已毕业"];
        return $status[$value];
    }
    public static function SearchArticle($key)
    {
        $key = "%" . $key . "%";
        $articles = Learn::where("uname", 'like', $key)
            ->paginate(10);
        return $articles;
    }
}
