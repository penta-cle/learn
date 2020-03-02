<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/16
 * Time: 10:42
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

class Learns extends Controller
{
    //查询学生信息
    public function select_student()
    {
        $list = Learn::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "list" => $list,
            "major" => Major::all(),
            "level" => Level::all(),
            "learning" => Learning::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all()
        ]);
        return $this->fetch("list");
    }

    //查询专业信息
    public function select_major()
    {
        $major = Major::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "major" => $major,
            "list" => Learn::all(),
            "level" => Level::all(),
            "learning" => Learning::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all()
        ]);
        return $this->fetch("list");
    }

    //查询学历信息
    public function select_xueli()
    {
        $xueli = Xueli::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "xueli" => $xueli,
            "list" => Learn::all(),
            "major" => Major::all(),
            "level" => Level::all(),
            "learning" => Learning::all(),
            "xuezhi" => Xuezhi::all()
        ]);
        return $this->fetch("list");
    }

    //查询学制信息
    public function select_xuezhi()
    {
        $xuezhi = Xuezhi::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "xuezhi" => $xuezhi,
            "xueli" => Xueli::all(),
            "list" => Learn::all(),
            "major" => Major::all(),
            "level" => Level::all(),
            "learning" => Learning::all()
        ]);
        return $this->fetch("list");
    }

    //查询学习形式
    public function select_learning()
    {
        $learning = Learning::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "learning" => $learning,
            "list" => Learn::all(),
            "major" => Major::all(),
            "level" => Level::all(),
            "xuezhi" => Xuezhi::all(),
            "xueli" => Xueli::all()
        ]);
        return $this->fetch("list");
    }

    //查询学习层次
    public function select_level()
    {
        $level = Level::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "level" => $level,
            "list" => Learn::all(),
            "major" => Major::all(),
            "learning" => Learning::all(),
            "xuezhi" => Xuezhi::all(),
            "xueli" => Xueli::all(),
        ]);
        return $this->fetch("list");
    }

    //添加学生信息
    public function add_student()
    {
        if (!input("pic") || !input("?uname") || !input("?sex") || !input("?birth") || !input("?r_time") ||
            !input("?sname") || !input("?major") || !input("?xueli") || !input("?xuezhi") || !input("?learning") ||
            !input("?level") || !input("?snumber") || !input("?graduate") || !input("?xname"))
            return result(201);
        $pic = input("pic");
        $uname = input("uname");
        $sex = input("sex");
        $birth = input("birth");
        $r_time = input("r_time");
        $b_time = input("b_time");
        $sname = input("sname");
        $major = input("major");
        $xueli = input("xueli");
        $xuezhi = input("xuezhi");
        $learning = input("learning");
        $level = input("level");
        $snumber = input("snumber");
        $graduate = input("graduate");
        $xname = input("xname");
        if ($pic == "" || $uname == "" || $sex == "" || $birth == "" || $r_time == "" || $sname == "" || $major == "" || $xueli == "" || $xuezhi == "" || $learning == "" || $level == "" || $snumber == "" || $graduate == "" || $xname == "" || $pic == NULL || $uname == NULL || $sex == NULL || $birth == NULL || $r_time == NULL || $sname == NULL || $major == NULL || $xueli == NULL || $xuezhi == NULL || $learning == NULL || $level == NULL || $snumber == NULL || $graduate == NULL || $xname == NULL)
            return result(201);
        $Learn = new Learn();
        $Learn->save(input());
        if ($Learn)
            return $this->success("添加成功！");
//            return result(0, "添加成功！");
        else
//            return result(201);
            return $this->error("添加失败！");
    }

    //edit跳转
    public function edit()
    {
        if (!input("?id"))
            return $this->error("系统发生错误！");
        $id = input("id");
        if ($id == "" || $id == NULL)
            return $this->error("系统发生错误！");
        $res = Learn::getId($id);
        // var_dump($res);
        $art = $res->getData();
//        $major1 = Learn::where("id",$id)->field("major")->find();

        $this->assign([
            "list" => $art,
            "major" => Major::all(),
            "level" => Level::all(),
            "learning" => Learning::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all()
        ]);
        return $this->fetch("edit");
    }

    //编辑学生信息
    public function edit_student()
    {
        if (!input("pic") || !input("?uname") || !input("?sex") || !input("?birth") || !input("?r_time") ||
            !input("?sname") || !input("?major") || !input("?xueli") || !input("?xuezhi") || !input("?learning") ||
            !input("?level") || !input("?snumber") || !input("?graduate") || !input("?xname"))
            return result(201);
        $id = input("id");
        $pic = input("pic");
        $uname = input("uname");
        $sex = input("sex");
        $birth = input("birth");
        $r_time = input("r_time");
        $b_time = input("b_time");
        $sname = input("sname");
        $major = input("major");
        $xueli = input("xueli");
        $xuezhi = input("xuezhi");
        $learning = input("learning");
        $level = input("level");
        $snumber = input("snumber");
        $graduate = input("graduate");
        $xname = input("xname");
        if ($pic == "" || $uname == "" || $sex == "" || $birth == "" || $r_time == "" || $sname == "" || $major == "" || $xueli == "" || $xuezhi == "" || $learning == "" || $level == "" || $snumber == "" || $graduate == "" || $xname == "" || $pic == NULL || $uname == NULL || $sex == NULL || $birth == NULL || $r_time == NULL || $sname == NULL || $major == NULL || $xueli == NULL || $xuezhi == NULL || $learning == NULL || $level == NULL || $snumber == NULL || $graduate == NULL || $xname == NULL)
            return result(201);
        $Learn = Learn::find($id);
        $Learn->save(input());
        if ($Learn)
            return $this->success("编辑成功！");
//            return result(0, "编辑成功！");
        else
//            return result(201);
            return $this->error("编辑失败！");
    }

    //删除学生信息
    public function del_student()
    {
        if (!input("?id"))
            return $this->error("系统发生错误！");
        $id = input("id");
        if ($id == "")
            return $this->error("系统发生错误！");
        $res = Learn::destroy($id);
        if ($res)
            return $this->success("删除成功");
        else if ($res == NULL)
            return $this->error("删除失败！");
    }

    //批量删除
    public function delAllCategory()
    {
        $id = input("id/a");
        //方法一
        $id = implode(",", $id);
//        $data=Article::where("id in ($id)")->destory();  //warning: 直接删除！！
        //方法二
        $data = Learn::destroy($id); //软删除
        exit(json_encode($data));
    }

    //按是否毕业分类查询下拉
    public function getByGraduate()
    {
        $name = input("name");
        if ($name == "all") {
            $list = Learn::order('id desc')->paginate(10, false, ['query' => request()->param()]);
            $this->assign([
                "list" => $list,
                "major" => Major::all(),
                "level" => Level::all(),
                "learning" => Learning::all(),
                "xueli" => Xueli::all(),
                "xuezhi" => Xuezhi::all()
            ]);
            return $this->fetch("list");
        } else {
            $arr = Learn::where('graduate', $name)->paginate(10, false, ['query' => request()->param()]);
            $this->assign([
                "list" => $arr,
                "major" => Major::all(),
                "level" => Level::all(),
                "learning" => Learning::all(),
                "xueli" => Xueli::all(),
                "xuezhi" => Xuezhi::all()
            ]);
        }
        return $this->fetch("list");
    }

    //按学历分类查询下拉
    public function getByXueli()
    {
        $name = input("name");
        if ($name == "all") {
            $list = Learn::order('id desc')->paginate(10, false, ['query' => request()->param()]);
            $this->assign([
                "list" => $list,
                "major" => Major::all(),
                "level" => Level::all(),
                "learning" => Learning::all(),
                "xueli" => Xueli::all(),
                "xuezhi" => Xuezhi::all()
            ]);
            return $this->fetch("list");
        } else {
            $arr = Learn::where('xueli', $name)->paginate(10, false, ['query' => request()->param()]);
            $this->assign([
                "list" => $arr,
                "major" => Major::all(),
                "level" => Level::all(),
                "learning" => Learning::all(),
                "xueli" => Xueli::all(),
                "xuezhi" => Xuezhi::all()
            ]);
        }
        return $this->fetch("list");
    }

    //信息搜索功能
    public function Search()
    {
        $key = input('key');
        $list = Learn::SearchArticle($key);
        $this->assign([
            "list" => $list,
            "major" => Major::all(),
            "level" => Level::all(),
            "learning" => Learning::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all()
        ]);
        return $this->fetch("list");
    }

    //导入数据
    public function insertExcel()
    {

        if (request()->isPost()) {
            vendor("PHPExcel.PHPExcel"); //方法一
            $objPHPExcel = new \PHPExcel();
            //获取表单上传文件
            $file = request()->file('excel');
            if ($file == "" || $file == NULL)
                return $this->error("请选择文件");
            $info = $file->validate(['ext' => 'xlsx'])->move(ROOT_PATH . 'public');  //上传验证后缀名,以及上传之后移动的地址  E:\wamp\www\bick\public
            if ($info) {
//              echo $info->getFilename();
                $exclePath = $info->getSaveName();  //获取文件名
                $file_name = ROOT_PATH . 'public' . DS . $exclePath;//上传文件的地址
                $objReader = \PHPExcel_IOFactory::createReader("Excel2007");
                $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
                $excel_array = $obj_PHPExcel->getSheet(0)->toArray();   //转换为数组格式
                array_shift($excel_array);  //删除第一个数组(标题);
                $city = [];
                $i = 0;

                foreach ($excel_array as $k => $v) {
//                    $city[$k]['id'] = $v[0];
                    $city[$k]['uname'] = $v[1];
                    $city[$k]['sex'] = $v[2];
                    $city[$k]['verify_code'] = $v[3];
                    $city[$k]['snumber'] = $v[4];
                    $city[$k]['major'] = $v[5];
                    $city[$k]['sname'] = $v[6];
                    $city[$k]['qr_code'] = $v[7];
                    $city[$k]['xueli'] = $v[8];
                    $city[$k]['xuezhi'] = $v[9];
                    $city[$k]['learning'] = $v[10];
                    $city[$k]['graduate'] = $v[11];
                    $city[$k]['r_time'] = $v[12];
                    $city[$k]['b_time'] = $v[13];
                    $city[$k]['xname'] = $v[14];
                    if ($v[1] == NULL || $v[1] == "")
                        return $this->error("请勿重复添加！");
                    else
                        $i++;
                }
//                return result(0,$city);
                Learn::insertAll($city);
            } else {
                echo $file->getError();
            }
        }
        $list = Learn::order('id desc')->paginate(10, false, ['query' => request()->param()]);
        $this->assign([
            "list" => $list,
            "major" => Major::all(),
            "level" => Level::all(),
            "learning" => Learning::all(),
            "xueli" => Xueli::all(),
            "xuezhi" => Xuezhi::all()
        ]);

        return $this->redirect("learns/select_student");
    }

}