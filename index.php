<?php
/**
* 首页
* 分类，幻灯片，其它链接
*/

ini_set('display_errors', 1);
require_once './config.inc.php';
require_once './include/functions.php';


/**
* 用户信息跟着走 1.检查session(openid); 2.没有的话，获取openid，并保存在session
*/

//授权请求
$redirect_uri = 'http://www.zhuangxiuji.com.cn/cms/index.php';
\LaneWeChat\Core\WeChatOAuth::getCode($redirect_uri, $state=1, $scope='snsapi_userinfo');

$pageidx = 'index';

//meta信息
$title = '微官网--同城新媒';


//分类数据
$allcategory = getAllCategory($db);

//template
include 'template/index.html';