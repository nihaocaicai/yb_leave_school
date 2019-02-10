<?php
namespace app\user\controller;
use think\Controller;
use app\user\model\Datalist;
use app\user\model\Collegename;

class Index extends Controller{
    public function index(){
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
        return $this->fetch('index',[
            'yb_today'=>$currentTime
        ]);
    }

    public function shows(){
        $yb_enter_year=isset($_POST['select_nianji']) ? $_POST['select_nianji']: '';
        $yb_collegename=isset($_POST['select_college']) ? $_POST['select_college']: '';
        $yb_class=isset($_POST['select_class']) ? $_POST['select_class']: '';
        $yb_class_year=isset($_POST['select_class_year']) ? $_POST['select_class_year']: '';
        $yb_if_leave=isset($_POST['if_leave']) ? $_POST['if_leave']: '';
        if($yb_if_leave=="是"){
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
        $res = Datalist::where($map)->field(array('yb_username','yb_if_leave','yb_classname'))
            ->select();
        $b = array();
        foreach ($res as $val){
            $b[]=$val->toArray();
        }
        $this->assign('datas',$b);
        return $this->fetch('shows',[
            'nianji'=>$yb_enter_year,
            'collegename'=>$yb_collegename,
            'classname'=>$yb_class."+".$yb_class_year."班"
        ]);
    }
}