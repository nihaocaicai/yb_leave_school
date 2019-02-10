<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\AdminUser;
use app\admin\model\Datalist;
use app\admin\model\Collegename;
use app\index\model\AppInfo;
use PHPExcel;
use PHPExcel_IOFactory;
class Index extends Controller{
    public function index()
    {
        if(!isset($_COOKIE["yb_admin_username"])){
            return $this->fetch('login');
        }else{
            return $this->fetch('index',[
                'yb_admin_username' => $_COOKIE["yb_admin_username"],
            ]);
        }
    }

//登录
    public function checkLogin(){
        $username = $_POST["username"];
        $password = $_POST["password"];
        //从数据库中读取后台管理员的用户和密码，检测账号密码是否正确
        $res_user_info = AdminUser::where("username","eq",$username)->find();

        //判断用户账号密码是否正确
        if($res_user_info["password"]==$password){
            setcookie('yb_admin_username',$res_user_info["username"],time()+3600*24,"/");
            setcookie('yb_admin_college',$res_user_info["collegename"],time()+3600*24,"/");
            setcookie('if_super_admin',$res_user_info["if_super_admin"],time()+3600*24,"/");//将用户名保存到cookies中
            return $this->redirect('admin/index/index');
            }else{
            return $this->fetch('login');
            }
    }
    public function loginOut(){
        setcookie('yb_admin_username',"",time()-3700*24,"/");  //删除cookies
        setcookie('yb_admin_college',"",time()-3700*24,"/");
        setcookie('if_super_admin',"",time()-3700*24,"/");
        return $this->fetch('login');
    }

    public function changePassword(){
        $username = $_COOKIE["yb_admin_username"];
        $newpass=$_POST['$newpass'];
        //从数据库中读取后台管理员的用户和密码，检测账号密码是否正确
        AdminUser::where("username","eq",$username)->update([
                'password' => $newpass,
            ]
        );
        return $newpass;
    }
// 所有数据展示页面
    public function userData(){
        $currentTime=date('Y');
        return $this->fetch('user_data',[
            'yb_today'=>$currentTime
        ]);
    }
    public function getUserDatas(){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;

        $enteryear = isset($_POST['enteryear']) ? $_POST['enteryear'] : '';
        $collegename = isset($_POST['collegename']) ? $_POST['collegename'] : '';
        $classname = isset($_POST['classname']) ? $_POST['classname'] : '';
        $student_name = isset($_POST['student_name']) ? $_POST['student_name'] : '';
        $if_leave = isset($_POST['if_leave']) ? $_POST['if_leave'] : '';

        $map['yb_enteryear']  = array('like',"%".$enteryear."%");
        $map['yb_collegename']  = array('like',"%".$collegename."%");
        $map['yb_classname']  = array('like',"%".$classname."%");
        $map['yb_username']  = array('like',"%".$student_name."%");
        $map['yb_if_leave']  = array('like',"%".$if_leave."%");

        if($_COOKIE['if_super_admin']=="是"){
            $row = Datalist::where($map)->count();
            $res = Datalist::where($map)->limit($offset,$rows)->select();
        }elseif($_COOKIE['yb_admin_college']==null){
            return "请设置账号所属学院";
        }else{
            $row = Datalist::where('yb_collegename', 'eq', $_COOKIE['yb_admin_college'])->where($map)->count();
            $res = Datalist::where('yb_collegename', 'eq', $_COOKIE['yb_admin_college'])->where($map)->limit($offset,$rows)->select();
        }

        $result = array();
        $result["total"] = $row;

        foreach ($res as $val){
            $b[]=$val->toArray();
        }
        $result["rows"]=$b;
        echo json_encode($result);
    }
    public function removeUser(){
        $id=array();
        $id[] = $_POST['array'];
        foreach ($id as $val){
            $res = Datalist::destroy($val);
        }
        if ($res) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => 'Some errors occured.'));

        }
    }
    public function removeAll(){
        if($_COOKIE['if_super_admin']=="是"){
            $res = Datalist::where("1=1")->delete();
        }elseif($_COOKIE['yb_admin_college']==null){
            return "请设置账号所属学院";
        }else {
            $res = Datalist::where('yb_collegename', 'eq', $_COOKIE['yb_admin_college'])->delete();
        }

        if ($res) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => 'Some errors occured.'));
        }
    }
    public function newUser(){
        $yb_other_info=$_POST['leave_time']."|".$_POST['return_time']."|".$_POST['leave_reason']."|".$_POST['telephone'];
        $result=Datalist::create([
                'yb_userid' =>$_POST['yb_userid'],
                'yb_username'=>$_POST['yb_username'],
                'yb_enteryear'=>$_POST['yb_enteryear'],
                'yb_collegename'=>$_POST['yb_collegename'],
                'yb_classname'=>$_POST['yb_classname'],
                'yb_if_leave'=>$_POST['yb_if_leave'],
                'yb_other_info'=>$yb_other_info
            ]
        );
        if ($result){
            echo json_encode(array('success'=>true));
        } else {
            echo json_encode(array('msg'=>'Some errors occured.'));
        }
    }
    public function editUser(){
        $yb_other_info=$_POST['leave_time']."|".$_POST['return_time']."|".$_POST['leave_reason']."|".$_POST['telephone'];
        $result=Datalist::where("id","eq",intval($_REQUEST['id']))->update([
                'yb_userid' =>$_POST['yb_userid'],
                'yb_username'=>$_POST['yb_username'],
                'yb_enteryear'=>$_POST['yb_enteryear'],
                'yb_collegename'=>$_POST['yb_collegename'],
                'yb_classname'=>$_POST['yb_classname'],
                'yb_if_leave'=>$_POST['yb_if_leave'],
                'yb_other_info'=>$yb_other_info
            ]
        );
        if ($result){
            echo json_encode(array('success'=>true));
        } else {
            echo json_encode(array('msg'=>'Some errors occured.'));
        }
    }

// 数据下载页面
    public function downloadData(){
        if($_COOKIE['if_super_admin']=="是"){
            // 传递学院数据
            $res_collegename = Collegename::order("id")->column("yb_collegename");
            $json = json_encode($res_collegename);
            $this->assign('res_collegename',$json);
            //传递班级数据
            $res_class = Collegename::order("id")->column("yb_classname");
            for($y=0;$y<sizeof($res_class);$y++){
                $b[$y]= explode("|",$res_class[$y]);
            }
            $json1 = json_encode($b);
            $this->assign('res_classname',$json1);
            $currentTime=date('Y');
            return $this->fetch('download_data',[
                'yb_today'=>$currentTime
            ]);
        }else{
            // 传递学院数据
            $res_collegename=array();
            $res_collegename[] = array(
                array($_COOKIE['yb_admin_college'])
            );
            $json = json_encode($res_collegename);
            $this->assign('res_collegename',$json);
            //传递班级数据
            $res_class = Collegename::where("yb_collegename","eq",$_COOKIE['yb_admin_college'])->order("id")->column("yb_classname");
            for($y=0;$y<sizeof($res_class);$y++){
                $b[$y]= explode("|",$res_class[$y]);
            }
            $json1 = json_encode($b);
            $this->assign('res_classname',$json1);
            $currentTime=date('Y');
            return $this->fetch('download_data',[
                'yb_today'=>$currentTime
            ]);
        }
    }

// 数据下载
    public function download(){
        $yb_enter_year=$_POST['select_nianji'];
        $yb_collegename=$_POST['select_college'];
        $yb_class=$_POST['select_class'];
        $yb_class_year=$_POST['select_class_year'];
        if($_POST['if_leave']=="是"){
            $yb_if_leave=$_POST['if_leave'];
        }else{
            $yb_if_leave="";
        }
        if($yb_class_year==null){
            $map['yb_enteryear']  = array('eq',$yb_enter_year);
            $map['yb_if_leave']  = array('like',"%".$yb_if_leave."%");
            $map['yb_collegename']  = array('like',"%".$yb_collegename."%");
            $map['yb_classname']  = array('like',"%".$yb_class."%");
        }else{
            $map['yb_enteryear']  = array('eq',$yb_enter_year);
            $map['yb_if_leave']  = array('like',"%".$yb_if_leave."%");
            $map['yb_collegename']  = array('like',"%".$yb_collegename."%");
            $map['yb_classname']  = array('like',"%".$yb_class."+".$yb_class_year."%");
        }
        $res = Datalist::where($map)->field(array('yb_enteryear','yb_collegename','yb_classname','yb_username','yb_if_leave','yb_other_info'))
            ->select();
        $b = array();
        $b[]= array(
            '年级',
            '学院',
            '班级',
            '姓名',
            '是否离校',
            '其他信息(离校时间|返校时间|原因|联系方式)');
        foreach ($res as $val){
            $b[]=$val->toArray();
        }

        $objPHPExcel=new PHPExcel();//实例化PHPExcel类， 等同于在桌面上新建一个excel
        $objSheet=$objPHPExcel->getActiveSheet();//获得当前活动sheet
//        $objSheet->setCellValue("A1","姓名");
        $objSheet->setTitle("离校返校数据");//给当前活动sheet设置名称
        $objSheet->fromArray($b);
        if($yb_class_year==null){
            $filename=$yb_enter_year."级-".$yb_collegename."-".$yb_class.".xls";
        }else{
            $filename=$yb_enter_year."级-".$yb_collegename."-".$yb_class."+".$yb_class_year."班".".xls";
        }

        header('Content-Type: application/vnd.ms-excel');
        $filename=iconv("UTF-8","GB2312//IGNORE",$filename);
        header('Content-Disposition: attachment;filename="'.$filename.'"');//告诉浏览器将输出文件的名称
        header('Cache-Control: max-age=0');//禁止缓存
        header ('Pragma: public');

        $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');//生成excel文件
        $objWriter->save("php://output");

    }

//  前台信息编辑页面
    public function appInfo(){
        if($_COOKIE['if_super_admin']=="是"){
            // 传递情况前台简介数据
            $res_yb_info = AppInfo::order("id desc")->find();
            $res_yb_info = $res_yb_info->toArray();
            $b= explode("|",$res_yb_info['yb_info']);
            return $this->fetch('app_info',[
                'yb_zhuti' => $b[0],
                'yb_fangjia_time' =>$b[1],
                'yb_fanxiao_time' => $b[2],
                'yb_jiezhi_time' => $b[3],
                'yb_write_info' => $b[4],
                'yb_fangjia_info' => $b[5]
            ]);
        }else{
            return "你不是超级管理员，无法访问该页面";
        }
    }
    public function updateAppInfo(){
        $yb_info=$_POST['title']."|".$_POST['holiday_time']."|".$_POST['return_time']."|".$_POST['deadline']."|".$_POST['holiday_attention']."|".$_POST['write_attention'];
        AppInfo::where([
            'id' =>1,
        ])->update([
                'yb_info'=>$yb_info
            ]
        );
        return $this->appInfo();
    }

// 学院编辑页面
    public function collegeInfo(){
        if($_COOKIE['if_super_admin']=="是"){
            // 传递学院数据
            $b = array();
            $res_yb_info = Collegename::order("id asc")->field(array('id','yb_collegename','yb_classname'))->select();
            foreach ($res_yb_info as $val){
                $b[]=$val->toArray();
            }
            $this->assign('college_info',$b);
            return $this->fetch('college_info');
        }else{
            return "你不是超级管理员，无法访问该页面";
        }
    }
    public function updateCollegeInfo(){
        $collegename=$_POST['collegename'];
        $classname=$_POST['classname'];
        Collegename::where([
            'yb_collegename' =>$collegename,
        ])->update([
            'yb_classname'=>$classname
            ]
        );
        return $this->collegeInfo();
    }
    public function deleteCollegeInfo(){
        $id = $_GET['id'];
        Collegename::destroy($id);
        return $this->collegeInfo();
    }
    public function insertCollegeInfo(){
        $collegename=$_POST['collegename'];
        $classname=$_POST['classname'];
        Collegename::create([
            'yb_collegename' =>$collegename,
            'yb_classname'=>$classname
        ]);
        return $this->collegeInfo();
    }

//  后台用户编辑页面
    public function adminUser(){
        if($_COOKIE['if_super_admin']=="是"){
            // 传递学院数据
            $res_collegename = Collegename::order("id")->column("yb_collegename");
            $this->assign('res_collegename',$res_collegename);
            // 传递后台用户数据
            $b = array();
            $res_yb_info = AdminUser::order("id asc")->field(array('id','username','password','collegename','if_super_admin'))->select();
            foreach ($res_yb_info as $val){
                $b[]=$val->toArray();
            }
            $this->assign('admin_user_info',$b);
            return $this->fetch('admin_user');
        }else{
            return "你不是超级管理员，无法访问该页面";
        }
    }
    public function updateAdminUser(){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $select_collegename=$_POST['select_college'];
        $if_super_admin=$_POST['if_super_admin'];
        AdminUser::where([
            'username' =>$username,
        ])->update([
                'password'=>$password,
                'collegename'=>$select_collegename,
                'if_super_admin'=>$if_super_admin
                ]
        );
        return $this->adminUser();
    }
    public function deleteAdminUser(){
        $id = $_GET['id'];
        AdminUser::destroy($id);
        return $this->adminUser();
    }
    public function insertAdminUser(){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $select_collegename=$_POST['select_college'];
        $if_super_admin=$_POST['if_super_admin'];
        AdminUser::create([
                'username' =>$username,
                'password'=>$password,
                'collegename'=>$select_collegename,
                'if_super_admin'=>$if_super_admin
            ]
        );
        return $this->adminUser();
    }
}