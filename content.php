<?php
/**
* content.php   详细页面
*/

ini_set('display_errors', 1);
require_once './config.inc.php';

//用户openid
if(empty($_SESSION['openid'])){
	SystemTool::checkOpenid($db,'snsapi_userinfo');
}

//share openid
if(isset($_GET['shareopenid'])&&!empty($_GET['shareopenid'])){
	$shareopenid = $_GET['shareopenid'];
}

//位置信息,ajax请求处理
if($_POST['action']==='location'){
	$latitude = $_POST['latitude'];
	$longitude = $_POST['longitude'];
	$_SESSION['location'] = $latitude.','.$longitude;
	exit;
}

$contentid = intval($_GET['id']);
$content = ContentClass::getArticle($db,$contentid);
if(empty($content)){
	echo '内容不存在或者已删除';
	exit;
}

$pageidx = 'content';
$title = '同城新媒广告';

//jsapi 相关部分
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$signPackage = \LaneWeChat\Core\JsapiTicket::getSignPackage($url);

//share setting

$shareinfo['title'] = $content['title'];
$url = SITE_DOMAIN."/content.php?id=".$content['id'];
if(Userinfo::checkSubscribe($db,$openid)){
	$url .= '&shareopenid='.$openid;
}	
$shareinfo['link'] = $url;
$shareinfo['imgUrl'] = $content['listimage'];

//template
include 'template/content.html';