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
use app\api\model\Like;
use app\api\model\User;
use app\api\model\Pinglun;
use think\Controller;
use think\Session;

class Articles extends Controller
{

    //查询文章详细信息
    public function select()
    {
        $list = Article::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "list" => $list,
            "apic" => Article::all(),
            "aname" => Article::all(),
            "atitle" => Article::all(),
            "fenlei" => Fenlei::all(),
            "id" => Article::all(),
        ]);
        // $this->assign("list", $list);
        return result(0, $list);
    }

    //查看轮播图信息
    public function selectPic()
    {
        $list = Article::field("apic")->select();
        return result(0,$list);
    }

    //根据aid uid查询评论详情
    public function getPinglun()
    {
        $aid = input("aid"); //文章id
        if ($aid == "" || $aid == "")
            return result(201);
        $pinglun = Pinglun::where("aid", $aid)->order("create_time", "desc")->field("id,uid,ptext,pname,create_time")->paginate(10);
        return result(0, $pinglun);
    }

    //添加评论接口
    public function add_pinglun()
    {
        $aid = input("aid");     //文章id
        $keywords = input("keywords");
        $uid = input("uid");    //用户id
        $uname = User::where("id", $uid)->field("name")->find();
        if ($aid == "" || $uid == "" || $keywords == "" || $aid == NULL || $uid == NULL || $keywords == NULL)
            return result(201);
        $Pinglun = new Pinglun();
        $Article = Article::where("id", $aid)->field("allpinglun")->find();
        $Pinglun->aid = $aid;
        $Pinglun->uid = $uid;
        $Pinglun->pname = $uname['name'];
        $Pinglun->ptext = $keywords;
        $Pinglun->save();
        $arr = $Article->allpinglun;
        $Article->allpinglun = $arr + 1; //添加评论时文章列表评论总数+1
        $Article->save();
        return result(0, $Pinglun);
    }


    //根据aid uid获取文章点赞状态
    public function getarticledianzan()
    {
        $aid = input("aid");
        $uid = input("uid");
        $dian = Like::where("aid", $aid)->where("uid", $uid)->find();
        if (!$dian)
            return result(0, ["state" => false]); else
            return result(0, $dian);
    }

    //文章端点赞动作
    public function Articledianzan()
    {
        //        $lid = input("id");
        $userid = input("uid"); //用户id
        $artid = input("aid"); //文章id
        $arilike = Like::where("uid", $userid)->where("aid", $artid)->find();
        $all_like = Article::where("id", $artid)->field("likes")->find();
        if ($arilike) {
            $state = $arilike->state;
            $arilike->state = $state == 1 ? 0 : 1;
            $arilike->save();

            if ($arilike->state == 0) {
                $all_like = Article::where("id", $artid)->field("likes")->find();
                $arr = $all_like->likes;
                $all_like->likes = $arr - 1;  //点赞时总数-1
                $all_like->save();
            } else {
                $all_like = Article::where("id", $artid)->field("likes")->find();
                $arr = $all_like->likes;
                $all_like->likes = $arr + 1; //未点赞时总数+1
                $all_like->save();
            }

        } else {
            $arilike = new Like();
            $arilike->uid = $userid;
            $arilike->aid = $artid;
            $arilike->state = 1;
            $arilike->save();

            $all_like = Article::where("id", $artid)->field("likes")->find();
            $arr = $all_like->likes;
            $all_like->likes = $arr - 1;
            $all_like->save();
        }
        return result(0, $arilike);
    }


}