<?php

/**
 * Created by PhpStorm.
 * User: Pentacle
 * Date: 2019/7/12
 * Time: 14:57
 */

namespace app\admin\Controller;

use app\admin\model\Admin;
use think\Controller;
use think\Session;

class Login extends Controller
{

    public function index()
    {
        if(!session::get("name","admin"))
        return $this->fetch("Login");
        else
        return $this->redirect('Learns/select_student');
    }

    public function login()
    {
        $username = input("post.username");
        $password = input("post.password");
        if ($username == "") {
            return $this->error("请输入用户名！");
        } else if ($password == "") {
            return $this->error("请输入密码！");
        }
        $password = md5(md5($password));
        $rescode = Admin::verify($username, $password);
        $uid = Admin::where("username", $username)->field("id")->find();
        $user = Admin::where("username",$username)->find();
        if ($rescode === 404) {
            return $this->error("没有此用户！");
        } else if ($rescode === 500) {
            return $this->error("密码错误，请重新输入！");
        } else if ($rescode === 200) {
            Session::set('id',  $user->id, 'admin'); 
            Session::set('name', $username, 'admin');  //存储用户名
            Session::set('loged_name', $username, 'admin');
            Session('uid', $uid['id']);
            return $this->redirect('Learns/select_student');
        }
    }

    public function logout()
    {
        Session::clear('admin');
        $this->redirect("index");
    }

    public function adminers()
    {
        $arr = Admin::all();
        $this->assign("list", $arr);
        return $this->fetch("list");
    }

    public function lists()
    {
        return $this->fetch("list");
    }

    //修改管理员密码
    public function revisepassword()
    {

        $admin_id = Session::get('uid');
        $admin = new Admin();
        $admin_info = $admin->where('id', $admin_id)->find();

        $data = input('post.');

        if ($data) {
            if ((md5(md5(md5($data['password_origin'])))) == $admin_info['password']) {
                if ($data['password'] == $data['password_again']) {
                    $md5_password = md5(md5(md5($data['password'])));
                    $result = Admin::where('id', $admin_id)->update(['password' => $md5_password]);
                    if ($result) {
                        return $this->success('修改成功啦!');
                    } else {
                        return $this->error('修改失败，请输入非近期密码');
                    }
                } else {
                    return $this->error('密码不一致，请再次输入!');
                }
            } else {
                return $this->error('原密码输入错误!');
            }
        }
        return $this->redirect('lactives/select');

    }

}