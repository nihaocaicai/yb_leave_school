<?php
namespace app\index\controller;
use think\Config;
use yiban\YBOpenApi;
use think\Controller;
use app\index\model\AppUser;

class Login extends Controller{
    public function login(){
        $AppID=Config::get('yb.AppID');
        $AppSecret=Config::get('yb.AppSecret');
        $CallBack=Config::get('yb.CallBack');
        //初始化
        $api = YBOpenApi::getInstance()->init($AppID, $AppSecret, $CallBack);
        $au  = $api->getAuthorize();

        //网站接入获取access_token，未授权则跳转至授权页面
        $info = $au->getToken();
        if(!$info['status']) {//授权失败
            echo $info['msg'];
            die;
        }
        $token = $info['token'];//网站接入获取的token
        $api->bind($token);
        $arr1 = $api->request('user/me');
        setcookie('user_id',$arr1['info']['yb_userid'],time()+3600*24,"/");  //设置易班id
//        setcookie('user_name',$arr['yb_username'],time()+3600,"/");  //设置易班用户名

        if(AppUser::where([
            'yb_userid' =>$arr1['info']['yb_userid'],
        ])->count()){
            AppUser::where([
                'yb_userid' => $arr1['info']['yb_userid'],
            ])->update([
                    'yb_username'=>$arr1['info']['yb_username'],
                    'yb_usertoken'=>$token,
                    'yb_userhead'=>$arr1['info']['yb_userhead'],
                    'yb_schoolname'=>$arr1['info']['yb_schoolname'],
                ]
            );
        }else{
            AppUser::create([
                'yb_userid' => $_COOKIE["user_id"],
                'yb_username'=>$arr1['info']['yb_username'],
                'yb_usertoken'=>$token,
                'yb_userhead'=>$arr1['info']['yb_userhead'],
                'yb_schoolname'=>$arr1['info']['yb_schoolname'],
            ]);
        }

        if (isset($token)) {
            $this->redirect('index/index/index');
        }        //判断是否登录成功，返回首页
    }
}