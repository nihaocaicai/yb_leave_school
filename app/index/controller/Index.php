<?php
namespace app\index\controller;
use think\Config;
use think\Controller;
use yiban\YBOpenApi;
use app\index\model\Collegename;
use app\index\model\AppInfo;
use app\index\model\Datalist;
use app\index\model\AppUser;


class Index extends Controller{
    public function index(){
        if(!isset($_COOKIE["user_id"])){
            $CallBack = Config::get('yb.CallBack');
            $this->redirect("$CallBack");//302是临时重定向，301是永久重定向
//            return $this->redirect('模块名/控制器名/方法名');// 控制器redirect方法
        }else{
            // 传递情况前台简介数据
            $res_yb_info = AppInfo::order("id desc")->find();
            $res_yb_info = $res_yb_info->toArray();
            $b= explode("|",$res_yb_info['yb_info']);
            $arr1=$this->common();
            return $this->fetch('index',[
                'yb_username' => $arr1['info']['yb_username'],
                'yb_userhead' => $arr1['info']['yb_userhead'],
                'yb_schoolname'=> $arr1['info']['yb_schoolname'],
                'yb_regtime'=> $arr1['info']['yb_regtime'],
                'yb_exp'=> $arr1['info']['yb_exp'],
                'yb_money'=> $arr1['info']['yb_money'],
                'yb_userid'=> $arr1['info']['yb_userid'],
                'yb_zhuti' => $b[0],
                'yb_fangjia_time' =>$b[1],
                'yb_fanxiao_time' => $b[2],
                'yb_jiezhi_time' => $b[3],
                'yb_write_info' => $b[4],
                'yb_fangjia_info' => $b[5]
            ]);
        }
    }

    public function forms(){
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
//        显示页面
        $currentTime=date('Y');
        $arr1=$this->common();
        return $this->fetch('forms',[
            'yb_username' => $arr1["info"]['yb_username'],
            'yb_userhead' => $arr1["info"]['yb_userhead'],
            'yb_schoolname'=> $arr1["info"]['yb_schoolname'],
            'yb_regtime'=> $arr1["info"]['yb_regtime'],
            'yb_exp'=> $arr1["info"]['yb_exp'],
            'yb_money'=> $arr1["info"]['yb_money'],
            'yb_userid'=> $arr1["info"]['yb_userid'],
            'yb_today'=>$currentTime
        ]);
    }

    public function shows(){
        // 传递用户填写数据数据
        $res_user_info = Datalist::where("yb_userid","eq",$_COOKIE['user_id'])->find();
        if(!$res_user_info){
            $arr1=$this->common();
            return $this->fetch('shows',[
                'yb_username' => $arr1["info"]['yb_username'],
                'yb_userhead' => $arr1["info"]['yb_userhead'],
                'yb_schoolname'=> $arr1["info"]['yb_schoolname'],
                'yb_regtime'=> $arr1["info"]['yb_regtime'],
                'yb_exp'=> $arr1["info"]['yb_exp'],
                'yb_money'=> $arr1["info"]['yb_money'],
                'yb_userid'=> $arr1["info"]['yb_userid'],
                'username' =>'请先在表单填写表格',
                'yb_enteryear' => '',
                'yb_collegename' =>'',
                'yb_classname' => '',
                'yb_if_leave' => '',
                'yb_leave_time'=>'',
                'yb_return_time'=>'',
                'yb_leave_reason'=>'',
                'yb_telephone'=>''
            ]);
        }
        $res_user_info = $res_user_info->toArray();
        $b= explode("|",$res_user_info['yb_other_info']);
        $arr1=$this->common();
        return $this->fetch('shows',[
            'yb_username' => $arr1["info"]['yb_username'],
            'yb_userhead' => $arr1["info"]['yb_userhead'],
            'yb_schoolname'=> $arr1["info"]['yb_schoolname'],
            'yb_regtime'=> $arr1["info"]['yb_regtime'],
            'yb_exp'=> $arr1["info"]['yb_exp'],
            'yb_money'=> $arr1["info"]['yb_money'],
            'yb_userid'=> $arr1["info"]['yb_userid'],
            'username' => $res_user_info['yb_username'],
            'yb_enteryear' => $res_user_info['yb_enteryear'],
            'yb_collegename' => $res_user_info['yb_collegename'],
            'yb_classname' => $res_user_info['yb_classname'],
            'yb_if_leave' => $res_user_info['yb_if_leave'],
            'yb_leave_time'=>$b[0],
            'yb_return_time'=>$b[1],
            'yb_leave_reason'=>$b[2],
            'yb_telephone'=>$b[3]
        ]);
    }

    public function modify(){
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
        // 传递用户填写数据数据
        $res_user_info = Datalist::where("yb_userid","eq",$_COOKIE['user_id'])->find();
        if(!$res_user_info){
            return "请先插入数据";
        }

        $res_user_info = $res_user_info->toArray();
        $c= explode("|",$res_user_info['yb_other_info']);
        //        显示页面
        $currentTime=date('Y');
        $arr1=$this->common();
        return $this->fetch('modify',[
            'yb_username' => $arr1["info"]['yb_username'],
            'yb_userhead' => $arr1["info"]['yb_userhead'],
            'yb_schoolname'=> $arr1["info"]['yb_schoolname'],
            'yb_regtime'=> $arr1["info"]['yb_regtime'],
            'yb_exp'=> $arr1["info"]['yb_exp'],
            'yb_money'=> $arr1["info"]['yb_money'],
            'yb_userid'=> $arr1["info"]['yb_userid'],
            'yb_today'=>$currentTime,
            'username' => $res_user_info['yb_username'],
            'yb_leave_time'=>$c[0],
            'yb_return_time'=>$c[1],
            'yb_leave_reason'=>$c[2],
            'yb_telephone'=>$c[3]
        ]);
    }

    public function insertData(){
        $leave_time = isset($_POST['leave_time']) ? $_POST['leave_time']: '';
        $return_time = isset($_POST['return_time']) ? $_POST['return_time']: '';
        $leave_reason = isset($_POST['leave_reason']) ? $_POST['leave_reason']: '';
        $telephone = isset($_POST['telephone']) ? $_POST['telephone']: '';
        $select_class = isset($_POST['select_class']) ? $_POST['select_class']: '';
        $select_class_year = isset($_POST['select_class_year']) ? $_POST['select_class_year']: '';
        $username = isset($_POST['username']) ? $_POST['username']: '';
        $select_nianji = isset($_POST['select_nianji']) ? $_POST['select_nianji']: '';
        $select_college = isset($_POST['select_college']) ? $_POST['select_college']: '';
        $if_leave = isset($_POST['if_leave']) ? $_POST['if_leave']: '';

        $yb_other_info=$leave_time."|".$return_time."|".$leave_reason."|".$telephone;
        $yb_classname=$select_class."+".$select_class_year."班";
        if(Datalist::get(function ($query){$query->where("yb_userid","eq",$_COOKIE['user_id']);})){
            Datalist::where("yb_userid","eq",$_COOKIE['user_id'])->update([
                    'yb_userid' =>$_COOKIE['user_id'],
                    'yb_username'=>$username,
                    'yb_enteryear'=>$select_nianji,
                    'yb_collegename'=>$select_college,
                    'yb_classname'=>$yb_classname,
                    'yb_if_leave'=>$if_leave,
                    'yb_other_info'=>$yb_other_info
                ]
            );
        }else{
            Datalist::create([
                    'yb_userid' =>$_COOKIE['user_id'],
                    'yb_username'=>$username,
                    'yb_enteryear'=>$select_nianji,
                    'yb_collegename'=>$select_college,
                    'yb_classname'=>$yb_classname,
                    'yb_if_leave'=>$if_leave,
                    'yb_other_info'=>$yb_other_info
                ]
            );
        }
        return $this->shows();
    }

    public function updateData()
    {
        $leave_time = isset($_POST['leave_time']) ? $_POST['leave_time']: '';
        $return_time = isset($_POST['return_time']) ? $_POST['return_time']: '';
        $leave_reason = isset($_POST['leave_reason']) ? $_POST['leave_reason']: '';
        $telephone = isset($_POST['telephone']) ? $_POST['telephone']: '';
        $select_class = isset($_POST['select_class']) ? $_POST['select_class']: '';
        $select_class_year = isset($_POST['select_class_year']) ? $_POST['select_class_year']: '';
        $username = isset($_POST['username']) ? $_POST['username']: '';
        $select_nianji = isset($_POST['select_nianji']) ? $_POST['select_nianji']: '';
        $select_college = isset($_POST['select_college']) ? $_POST['select_college']: '';
        $if_leave = isset($_POST['if_leave']) ? $_POST['if_leave']: '';

        $yb_other_info=$leave_time."|".$return_time."|".$leave_reason."|".$telephone;
        $yb_classname=$select_class."+".$select_class_year."班";

        $res = Datalist::where([
            'yb_userid' =>$_COOKIE['user_id'],
        ])->update([
                'yb_username' => $username,
                'yb_enteryear' => $select_nianji,
                'yb_collegename' => $select_college,
                'yb_classname' => $yb_classname,
                'yb_if_leave' => $if_leave,
                'yb_other_info' => $yb_other_info
            ]
        );
        return $this->shows();
    }

    public function common(){
        $res_user_info = AppUser::where("yb_userid","eq",$_COOKIE["user_id"])->find();
        $res_user_info = $res_user_info->toArray();
        //初始化
        $AppID=Config::get('yb.AppID');
        $AppSecret=Config::get('yb.AppSecret');
        $CallBack=Config::get('yb.CallBack');
        $api = YBOpenApi::getInstance()->init($AppID, $AppSecret, $CallBack);
        $api->bind($res_user_info["yb_usertoken"]);
        $arr1 = $api->request('user/me');
        return $arr1;
    }
}
