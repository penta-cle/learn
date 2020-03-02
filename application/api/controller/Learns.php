<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/19
 * Time: 11:11
 */

namespace app\api\Controller;

use app\api\model\Learn;
use app\api\model\Learning;
use app\api\model\Level;
use app\api\model\Major;
use app\api\model\Xueli;
use app\api\model\Xuezhi;
use think\Controller;
use think\Session;
//use Qrcode\Qrcode;
use Endroid\QrCode\QrCode;
use app\common\services\QrcodeServer;

class Learns extends Controller
{

    public function select(){
        return $this->fetch("list");
    }
    public function importExcel() {
        import('phpexcel.PHPExcel', EXTEND_PATH, '.php'); // 引用PHPExcel文件 
        $excel = new \PHPExcel();
     
        $user = Cookie::get('user');
        $file = request()->file('file');  // 获取表单上传文件
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');       // 移动到框架应用根目录/public/uploads/ 目录下
        if($info) {
            $filename = ROOT_PATH . 'public' . DS . 'uploads/' .$info->getSaveName();
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');        //如果写称Excel2017有些情况下会报错
            $obj_PHPExcel = $objReader->load($filename, $encode = 'utf-8');  //加载文件内容,编码utf-8  
            $excel_array = $obj_PHPExcel->getsheet(0)->toArray();            //转换为数组格式
            $arr = reset($excel_array);     //excel表格第一行的值
            unset($excel_array[0]);         //删除第一个数组(标题)
     
            //查询已添加的所有医院名
            $ynameData = Db::table('hospitals')->field('yname')->find();
            if(empty($ynameData)) {
                unlink($filename);
                return $this->rejson(0, '您还没有添加过医院，请先前去添加');
            }
     
            if(count($arr) != 4) {
                unlink($filename);
                return $this->rejson(0, '您的表格格式不正确，只能有4列');
            }
     
            //判断表格中的数据是否可以使用
            foreach($excel_array as $k4 => $v4) {
                //判断表格中的医院是否在后台已有添加过
                $yname = Db::table('hospitals')->field('yid')->where('yname', $v4[0])->find();
                if(empty($yname)) {
                    unlink($filename);
                    $str = '所属医院不存在，请检查：第' .($k4 + 1). '行，第1列';
                    return $this->rejson(0, $str);
                }
     
                if($user['jid'] == 2 && $yname['yid'] != $user['yid']) {
                    unlink($filename);
                    return $this->rejson(0, '只能导入您自己所属医院的数据');
                }
                
                $res = Db::table('this_option')
                        ->field('a.yid')
                        ->alias('a')
                        ->join('hospitals b', 'a.yid = b.yid')
                        ->where(['b.yname' => $v4[0], 'a.xname' => $v4[1]])
                        ->find();
                if(!empty($res)) {
                    unlink($filename);
                    $str = '项目名已存在，请检查：第' .($k4 + 1). '行，第2列';
                    return $this->rejson(0, $str);
                }
     
                //判断表格的格式
                foreach($v4 as $k2 => $v2) {
                    //ctype_space 判断字符串全部为空格
                    if($v2 == ''|| ctype_space($v2)) {
                        unlink($filename);
                        $str = '单元格的值不能为空，请检查：第' .($k4 + 1). '行，第' .($k2 + 1). '列';
                        return $this->rejson(0, $str);
                    }
     
                    //判断项目价格是否为数值型
                    if(!is_numeric($v4[3])) {
                        unlink($filename);
                        $str = '项目价格的格式不正确, 请检查：第' .($k4 + 1). '行，第4列';
                        return $this->rejson(0, $str);
                    }
                }
            }
     
            //数据正确，开始导入数据
            $data = [];
            foreach($excel_array as $k => $v) {
                $yid = Db::table('hospitals')->field('yid')->where('yname', $v[0])->find();
                $data[] = [
                    'yid' => $yid['yid'],
                    'xname' => $v[1],
                    'xdec' => $v[2],
                    'xprice' => $v[3],
                    'status' => '1',
                    'isdelete' => '0'
                ];
            }
            //一次添加多条数据
            $res = Db::table('this_option')->insertAll($data);
     
            unlink($filename); //上传成功删除服务器本地的文件
            return $this->rejson(1, '一键导入成功', $info->getFilename());
        } else {
            // 上传失败获取错误信息
            return $this->rejson(0, $file->getError());
        }
    }

    public function create(){
        // 自定义二维码配置
        $config = [
            'title'         => true,
            'title_content' => '嗨，老范',
            'logo'          => true,
            'logo_url'      => './logo.png',
            'logo_size'     => 80,
        ];

        // 直接输出
        $qr_url = 'http://www.baidu.com?id=' . rand(1000, 9999);

        $qr_code = new QrcodeServer($config);
        $qr_img = $qr_code->createServer($qr_url);
        echo $qr_img;

        // 写入文件
        $qr_url = '这是个测试二维码';
        $file_name = './static/qrcode';  // 定义保存目录

        $config['file_name'] = $file_name;
        $config['generate']  = 'writefile';

        $qr_code = new QrcodeServer($config);
        $rs = $qr_code->createServer($qr_url);
        print_r($rs);

        exit;
    }

    //如下：
    // 二维码
    public function qrcode()
    {
        $savePath = APP_PATH . '/../Public/qrcode/';
        $webPath = '/qrcode/';
        $qrData = 'http://www.cnblogs.com/nickbai/';
        $qrLevel = 'H';
        $qrSize = '8';
        $savePrefix = 'NickBai';

        if($filename = createQRcode($savePath, $qrData, $qrLevel, $qrSize, $savePrefix)){
            $pic = $webPath . $filename;
        }
        echo "<img src='".$pic."'>";
    }

    public function qrcode2() {

        header('Content-Type: image/png');
        vendor("phpqrcode.phpqrcode");//引入工具包
        $qRcode = new \QRcode();
        $content = "wenben";
        $data = 'http://www.abc.cn/info/'.$content;//网址或者是文本内容
        // 纠错级别：L、M、Q、H
        $level = 'L';
        // 点的大小：1到10,用于手机端4就可以了
        $size = 4;
        // 生成的文件名
        $qRcode->png($data, $filename, $level, $size);
    }

    public function tiao()
    {

        // $content 一般为url地址 当然也可以是文字内容
        $content = 'http://www.baidu.com?rand=' . rand(1000, 9999);
        $qrCode = new QrCode($content);
        ob_end_clean();
        // 指定内容类型
        header('Content-Type: ' . $qrCode->getContentType());
        // 输出二维码
        $qrCode->writeFile(ROOT_PATH .'public'. DS . 'qrcode'.'/.png');
        echo $qrCode->writeString();

    }

    //传送学生所有信息
    public function student()
    {
        $arr = Learn::field("id")->find();
        return result(0, $arr);
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
//        $uid = input("uid");

        if ($pic == "" || $uname == "" || $sex == "" || $birth == "" || $r_time == "" || $sname == "" || $major == "" || $xueli == "" || $xuezhi == "" || $learning == "" || $level == "" || $snumber == "" || $graduate == "" || $xname == "" || $pic == NULL || $uname == NULL || $sex == NULL || $birth == NULL || $r_time == NULL || $sname == NULL || $major == NULL || $xueli == NULL || $xuezhi == NULL || $learning == NULL || $level == NULL || $snumber == NULL || $graduate == NULL || $xname == NULL)
            return result(201);
        $Learn = new Learn();
        $Learn->save(input());
        if ($Learn)
            // return $this->success("添加成功！");
            return result(0, $Learn);
        else
            return result(201);
        //return $this->error("添加失败！");
    }
}