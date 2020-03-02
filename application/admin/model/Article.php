<?php

/**
 * Created by PhpStorm.
 * User: Pentacle
 * Date: 2019/7/12
 * Time: 14:48
 */

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Article extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
    public static function getall()
    {
        $arr = Article::select();
        return $arr;
    }
    public static function getId($id)
    {

        $Article = Article::find($id);
        if ($Article == NULL)
            return [];
        return $Article;
    }

    public static function SearchArticle($key)
    {
        $key = "%" . $key . "%";
        $articles = Article::where("atitle", 'like', $key)
            ->whereOr("aname",'like',$key)
            ->paginate(10);
        return $articles;
    }
}
