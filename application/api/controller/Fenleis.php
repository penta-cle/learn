<?php

/**
 * Created by PhpStorm.
 * User: Pentacle
 * Date: 2019/7/12
 * Time: 14:57
 */

namespace app\api\Controller;

use app\api\model\Article;
use app\api\model\Fenlei;
use think\Controller;
use think\Session;

class Fenleis extends Controller
{
    //查询分类表详情信息
    public function select()
    {
        $list = Fenlei::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $atitle = Article::field("atitle")->find();
        $fenlei = Fenlei::find();
        $this->assign([
            "list" => $list,
            "atitle" => $atitle,
            "fname" =>Fenlei::all()

        ]);
        // $this->assign("list", $list);
        return result(0,$list);
    }

}
