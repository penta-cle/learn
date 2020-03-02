<?php

/**
 * Created by PhpStorm.
 * User: Pentacle
 * Date: 2019/7/12
 * Time: 14:57
 */

namespace app\admin\Controller;

use app\admin\model\Learn;
use app\admin\model\Learning;
use app\admin\model\Level;
use app\admin\model\Major;
use app\admin\model\Xueli;
use app\admin\model\Xuezhi;
use think\Controller;
use think\Session;

class Fenleis extends Controller
{
    //判断登陆
    protected function _initialize()
    {
        if ((Session::has('loged_name', 'admin')) == NULL)
            return $this->error('请先登录', 'Login/index');
        else;
    }

    //查询总分类信息
    public function select()
    {
        $this->assign([
            "major" => Major::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all(),
            "learning" => Learning::all(),
            "level" => Level::all()
        ]);
        return $this->fetch("list");
    }

    //查询专业分类
    public function select_major()
    {
        $major = Major::field("major")->select;
        $this->assign([
            "major" => $major,
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all(),
            "learning" => Learning::all(),
            "level" => Level::all()
        ]);
        return $this->fetch("list");
    }

    //查询学历分类
    public function select_xueli()
    {
        $xueli = Major::field("xueli")->select;
        $this->assign([
            "major" => Major::all(),
            "xueli" => $xueli,
            "xuezhi" => Xuezhi::all(),
            "learning" => Learning::all(),
            "level" => Level::all()
        ]);
        return $this->fetch("list");
    }

    //查询学制分类
    public function select_xuezhi()
    {
        $xuezhi = Xuezhi::field("xuezhi")->select;
        $this->assign([
            "major" => Major::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => $xuezhi,
            "learning" => Learning::all(),
            "level" => Level::all()
        ]);
        return $this->fetch("list");
    }

    //查询学习形式分类
    public function select_learning()
    {
        $learning = Major::field("learning")->select;
        $this->assign([
            "major" => Major::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all(),
            "learning" => $learning,
            "level" => Level::all()
        ]);
        return $this->fetch("list");
    }

    //查询层次分类
    public function select_level()
    {
        $level = Major::field("level")->select;
        $this->assign([
            "major" => Major::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all(),
            "learning" => Learning::all(),
            "level" => $level
        ]);
        return $this->fetch("list");
    }

    //添加专业分类
    public function add_major()
    {
        if (!input("?major"))
            return $this->error("系统发生错误！");
        $major = input("major");
        if ($major == "" || $major == NULL)
            return $this->error("请输入完整内容！");
        $Major = new Major();
        $Major->major = $major;
        $Major->save();
        if ($Major)
            return $this->redirect("Fenleis/select");
        else
            return $this->error("添加失败，请检查信息！");
    }

    //添加学历分类
    public function add_xueli()
    {
        if (!input("?xueli"))
            return $this->error("系统发生错误！");
        $xueli = input("xueli");
        if ($xueli == "" || $xueli == NULL)
            return $this->error("请输入完整内容！");
        $Xueli = new Xueli();
        $Xueli->xueli = $xueli;
        $Xueli->save();
        if ($Xueli)
            return $this->redirect("Fenleis/select");
        else
            return $this->error("添加失败，请检查信息！");
    }

    //添加学制分类
    public function add_xuezhi()
    {
        if (!input("?xuezhi"))
            return $this->error("系统发生错误！");
        $xuezhi = input("xuezhi");
        if ($xuezhi == "" || $xuezhi == NULL)
            return $this->error("请输入完整内容！");
        $Xuezhi = new Xuezhi();
        $Xuezhi->xuezhi = $xuezhi;
        $Xuezhi->save();
        if ($Xuezhi)
            return $this->redirect("Fenleis/select");
        else
            return $this->error("添加失败，请检查信息！");
    }

    //添加学习形式分类
    public function add_learning()
    {
        if (!input("?learning"))
            return $this->error("系统发生错误！");
        $learning = input("learning");
        if ($learning == "" || $learning == NULL)
            return $this->error("请输入完整内容！");
        $Learning = new Learning();
        $Learning->learning = $learning;
        $Learning->save();
        if ($Learning)
            return $this->redirect("Fenleis/select");
        else
            return $this->error("添加失败，请检查信息！");
    }

    //添加学习层次分类
    public function add_level()
    {
        if (!input("?level"))
            return $this->error("系统发生错误！");
        $level = input("level");
        if ($level == "" || $level == NULL)
            return $this->error("请输入完整内容！");
        $Level = new Level();
        $Level->level = $level;
        $Level->save();
        if ($Level)
            return $this->redirect("Fenleis/select");
        else
            return $this->error("添加失败，请检查信息！");
    }

    //删除专业方法
    public function del_major(){
        if (!input("?id"))
            return $this->error("系统发生错误！");
        $id = input("id");
        if ($id == "")
            return $this->error("系统发生错误！");
        $res = Major::destroy($id);
        if ($res)
            return $this->success("删除成功");
        else if ($res == NULL)
            return $this->error("删除失败！");
    }
    //删除学历方法
    public function del_xueli(){
        if (!input("?id"))
            return $this->error("系统发生错误！");
        $id = input("id");
        if ($id == "")
            return $this->error("系统发生错误！");
        $res = Xueli::destroy($id);
        if ($res)
            return $this->success("删除成功");
        else if ($res == NULL)
            return $this->error("删除失败！");
    }
    //删除学制方法
    public function del_xuezhi(){
        if (!input("?id"))
            return $this->error("系统发生错误！");
        $id = input("id");
        if ($id == "")
            return $this->error("系统发生错误！");
        $res = Xuezhi::destroy($id);
        if ($res)
            return $this->success("删除成功");
        else if ($res == NULL)
            return $this->error("删除失败！");
    }
    //删除学习形式方法
    public function del_learning(){
        if (!input("?id"))
            return $this->error("系统发生错误！");
        $id = input("id");
        if ($id == "")
            return $this->error("系统发生错误！");
        $res = Learning::destroy($id);
        if ($res)
            return $this->success("删除成功");
        else if ($res == NULL)
            return $this->error("删除失败！");
    }
    //删除学习层次方法
    public function del_level(){
        if (!input("?id"))
            return $this->error("系统发生错误！");
        $id = input("id");
        if ($id == "")
            return $this->error("系统发生错误！");
        $res = Level::destroy($id);
        if ($res)
            return $this->success("删除成功");
        else if ($res == NULL)
            return $this->error("删除失败！");
    }
}
