<?php
/**
* content.php   详细页面
*/

ini_set('display_errors', 1);
require_once './config.inc.php';

$contentid = intval($_GET['id']);

//share openid
if(isset($_GET['shareopenid'])&&!empty($_GET['shareopenid'])){
	$shareopenid = $_GET['shareopenid'];
}

//用户openid
if(empty($_SESSION['openid'])){
	SystemTool::systemLog($db,'content.php','empty session openid','check openid');
	$redirecturl = SITE_DOMAIN.'content.php?id='.$contentid;
	if(!empty($shareopenid))
		$redirecturl .= '&shareopenid='.$shareopenid;
	$redirecturl .= '#'.time();
	SystemTool::checkOpenid($db,'snsapi_userinfo',$redirecturl);
	exit;
}



$content = ContentClass::getArticle($db,$contentid);
if(empty($content)){
	echo '内容不存在或者已删除';
	exit;
}

if(!empty($shareopenid)){
	$isclicked = ClickCount::checkisClicked($db,$contentid,$_SESSION['openid'],$shareopenid);
}

$tempareadata = json_decode($content['city'],true);
$data['province'] = $tempareadata['province'];
$data['tempcity'] = $tempareadata['city'];
$data['district'] = is_array($tempareadata['district'])?implode(',',$tempareadata['district']):'';
if(!empty($data['district'])){
	$areadata = $data['tempcity'].'('.$data['district'].')';
}else if($data['tempcity']!='all'){
	$areadata = $data['tempcity'];
}else{
	$areadata = $data['province'];
}



$pageidx = 'content';
$title = '同城新媒';

//jsapi 相关部分
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$signPackage = \LaneWeChat\Core\JsapiTicket::getSignPackage($url);

//share setting

$shareinfo['title'] = $content['title'];
$url = SITE_DOMAIN."content.php?id=".$content['id'];
if(\LaneWeChat\Core\UserManage::checkisSubscribe($_SESSION['openid'])){
	$url .= '&shareopenid='.$_SESSION['openid'];
}	
$shareinfo['link'] = $url;
$shareinfo['imgUrl'] = $content['listimage'];

//template
include 'template/content.html';