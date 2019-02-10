<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');
// 定义配置文件目录
define('CONF_PATH', __DIR__ . '/../conf/');
//定义类的文件路径
define('EXTEND_PATH',  __DIR__ .'/../extend/');
//// 开启调试模式
//define('APP_DEBUG', true);
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
