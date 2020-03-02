<?php

/**
 * Created by PhpStorm.
 * User: Pentacle
 * Date: 2019/7/12
 * Time: 14:57
 */

namespace app\admin\controller;
use app\admin\model\Learn;
use think\Controller;
use think\Session;
use think\Cache;

class Index extends Controller
{

    //判断登陆
    protected function _initialize()
    {
        if ((Session::has('loged_name', 'admin')) == NULL)
            return $this->error('请先登录', 'Login/index');
        else;
    }

    public function index()
    {
        //获取系统概要信息
        $this->redirect("Login/index");
    }
//
//
//    //清除缓存
//    public function clear()
//    {
//        Cache::clear();
//        return $this->index();
//    }

//上传文件
    public function uploads()
    {
        // 获取表单上传文件
        $imgs = request()->file('imgs');
        $i = 0;$error=[];$path=[];
        $head = input('server.REQUEST_SCHEME') . '://' . input('server.SERVER_NAME');
        foreach($imgs as $file){

            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH .'public'. DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                $path[$i] = $head."/uploads/".$info->getSavename();

            }else{
                // 上传失败获取错误信息
                $error[$i] = $file->getError();
            }
            $i++;
        }
        $data = [
            "path"=> $path,
            "error"=> $error,
        ];
        return json($data);
    }

    //上传文件
    public function upload()
    {
        // 获取表单上传文件
        $imgs = request()->file('imgs');
        $i = 0;$error=0;$path=[];
        $head = input('server.REQUEST_SCHEME') . '://' . input('server.SERVER_NAME');
        foreach($imgs as $file){

            $linshi = $file->move(ROOT_PATH .'public'. DS . 'linshi');
            if($linshi){

                $linshiname =  ROOT_PATH .'public'. DS . 'linshi'.DS;
                $linshiname .=  $linshi->getSavename();
                $image = \think\Image::open($linshiname);
                $image->thumb(750, 750)->save(ROOT_PATH .'public'. DS . 'uploads'.DS.$linshi->getSavename());
                // 移动到框架应用根目录/public/uploads/ 目录下
                //$info = $file->move(ROOT_PATH .'public'. DS . 'uploads');

                // 成功上传后 获取上传信息
                $path[$i] = $head."/uploads/".$linshi->getSavename();

//                $move_url = config(‘excel_path’);
//                $file = request()->file(‘xls_file’);
//                $info = $file->validate([‘size’=>52428800,’ext’=>’xls,xlsx’])->rule(‘uniqid’)->move($move_url);
//                unset($info);
//                unlink($linshiname);

            }else{
                // 上传失败获取错误信息
                $error[$i] = $file->getError();
            }
            $i++;
        }
        $data = [
            "data"=> $path,
            "errno"=> $error,
        ];
        return json($data);
    }

    //导出excel表格 -- 报名表信息
    public function exportSignup(){
        $data = Learn::alias('s')
            //            ->join('attendee a', 's.aid=a.id')
//            ->join('meeting m', 's.mid=m.id')
            ->field("s.*")
            ->order('id')//按id排序
            ->select();
        $field = array(
            'A' => array('id', 'ID'),
            'B' => array('uname', '学生姓名'),
            'C' => array('sex', '性别'),
            'D' => array('verify_code', '验证码'),
            'E' => array('snumber', '学号'),
            'F' => array('major', '专业'),
            'G' => array('sname', '学校名'),
            'H' => array('qr_code', '二维码'),
            'I' => array('xueli', '学历'),
            'J' => array('xuezhi', '学制'),
            'K' => array('learning', '学习形式'),
            'L' => array('graduate', '是否毕业'),
            'M' => array('r_time', '入学时间'),
            'N' => array('b_time', '毕业时间'),
            'O' => array('xname', '校长名')
        );
        $this->phpExcelList($field, $data, '信息明细_' . date("Y-m-d H时i分"));
    }

    //phpexcel
    public function phpExcelList($field, $list, $title='文件')
    {
        vendor('phpExcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel); //设置保存版本格式
        foreach ($list as $key => $value) {
            foreach ($field as $k => $v) {
                if ($key == 0) {
                    $objPHPExcel->getActiveSheet()->setCellValue($k . '1', $v[1]);
                }
                $i = $key + 2; //表格是从2开始的
                $objPHPExcel->getActiveSheet()->setCellValue($k . $i, $value[$v[0]]);
            }
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$title.'.xls');
        header("Content-Transfer-Encoding:binary");
        //        $objWriter->save($title.'.xls');
        $objWriter->save('php://output');
    }
    //导入数据
    public function insertExcel(){
        if(request() -> isPost())
        {
            vendor("PHPExcel.PHPExcel"); //方法一
            $objPHPExcel =new \PHPExcel();
            //获取表单上传文件
            $file = request()->file('excel');
            $info = $file->validate(['ext' => 'xlsx'])->move(ROOT_PATH . 'public');  //上传验证后缀名,以及上传之后移动的地址  E:\wamp\www\bick\public
            if($info)
            {
//              echo $info->getFilename();
                $exclePath = $info->getSaveName();  //获取文件名
                $file_name = ROOT_PATH . 'public' . DS . $exclePath;//上传文件的地址
                $objReader =\PHPExcel_IOFactory::createReader("Excel2007");
                $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
                $excel_array=$obj_PHPExcel->getSheet(0)->toArray();   //转换为数组格式
                array_shift($excel_array);  //删除第一个数组(标题);
                $city = [];
                $i=0;
                foreach($excel_array as $k=>$v) {
                    $city[$k]['id'] = $v[0];
                    $city[$k]['uname'] = $v[1];
                    $city[$k]['sex'] = $v[2];
                    $city[$k]['paper'] = $v[3];
                    $city[$k]['snumber'] = $v[4];
                    $city[$k]['major'] = $v[5];
                    $city[$k]['sname'] = $v[6];
                    $city[$k]['college'] = $v[7];
                    $city[$k]['xueli'] = $v[8];
                    $city[$k]['xuezhi'] = $v[9];
                    $city[$k]['learning'] = $v[10];
                    $city[$k]['graduate'] = $v[11];
                    $city[$k]['r_time'] = $v[12];
                    $city[$k]['b_time'] = $v[13];
                    $city[$k]['xname'] = $v[14];
                    $i++;
                }
                Learn::insertAll($city);
            }else
            {
                echo $file->getError();
            }
        }
        return $this->fetch("learns/list");
    }
}