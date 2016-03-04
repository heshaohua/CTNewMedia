<?php
/**
* 首页
* 分类，幻灯片，其它链接
*/

ini_set('display_errors', 1);
require_once './config.inc.php';

//用户openid
if(empty($_SESSION['openid'])){
	SystemTool::checkOpenid($db,'snsapi_userinfo');
}

//分类数据
$allcategory = SystemTool::getAllCategory($db);

$pageidx = 'index';

//meta信息
$title = '微官网--同城新媒';

//template
include 'template/index.html';