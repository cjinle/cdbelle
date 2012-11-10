<?php
//定义项目名称和路径
define('APP_NAME', 'Members');
define('APP_PATH', './');
define('BUILD_DIR_SECURE', true);
define('DIR_SECURE_CONTENT', 'deney Access!');
define( 'DS' , DIRECTORY_SEPARATOR);
define( 'ROOT' , dirname(__FILE__) . DS);
// 加载框架入口文件
require( "./ThinkPHP/ThinkPHP.php");