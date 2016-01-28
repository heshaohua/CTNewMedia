<?php
/**
* content.php   详细页面
*/

ini_set('display_errors', 1);
require_once './config.inc.php';
require_once './include/functions.php';

//处理分享统计
if(isset($_POST['action'])&&$_POST['action']=='share'){
	$contentid = intval($_POST['id']);
	$openid = trim($_POST['openid']);
	if(!empty($contentid)&&!empty($openid)){
		logShare($db,$contentid,$openid);	
	}
	exit;
}

//处理分享点击统计
if(isset($_POST['action'])&&$_POST['action']=='clickshare'){
	$contentid = intval($_POST['id']);
	$clickOpenid = trim($_POST['openid']);
	$shareOpenid = trim($_POST['shareopenid']);
	if(!empty($contentid)&&!empty($clickOpenid)&&!empty($shareOpenid)&&$clickOpenid!=$shareOpenid){
		$contentinfo = getArticle($db,$contentid);
		if($contentinfo===false){
			echo '0';
			exit;
		}
		if(logClick($db,$contentid,$clickOpenid,$shareOpenid,$_SERVER['REMOTE_ADDR'],$contentinfo['priceperclick'])){
			echo '1';
			exit;
		}	
	}
	echo '0';
	exit;
}

//用户openid
if(empty($_SESSION['openid'])){
	checkOpenid();
}

$pageidx = 'content';

$contentid = intval($_GET['id']);
$content = getArticle($db,62);
if(isset($_GET['shareopenid'])&&!empty($_GET['shareopenid'])){
	$shareopenid = $_GET['shareopenid'];
}
if(empty($content)){
	echo '内容不存在或者已删除';
	exit;
}
$title = '同城新媒广告分享';

//jsapi 相关部分
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$signPackage = \LaneWeChat\Core\JsapiTicket::getSignPackage($url);

$shareinfo = getShareInfo($db,$content,$_SESSION['openid']);

//template
include 'template/content.html';



//文章内容
function getArticle($db,$id){
	$id = intval($id);
	if($id==0) return false;
	$result = $db->fetch_first("select * from articles where id=".$id);
	if(!empty($result)&&$result!=false){
		$contentresult = $db->fetch_first("select * from cmscontent where articleid=".$id);
		if(!empty($contentresult)&&$contentresult!=false){
			$result['content'] = $contentresult['content'];
			return $result;
		}
		return false;
	}
	return false;
}

//share info
function getShareInfo($db,$content,$openid){
	$shareinfo['title'] = $content['title'];
	$url = "http://www.zhuangxiuji.com.cn/cms/content.php?id=".$content['id'];
	if(checkSubscribe($db,$openid)){
		$url .= '&shareopenid='.$openid;
	}
	
	$shareinfo['link'] = $url;
	$shareinfo['imgUrl'] = $content['listimage'];
	return $shareinfo;
}

//checkshare
function logShare($db,$contentid,$openid){
	$result = $db->fetch_first("select * from shares where contentid=".$contentid." and shareOpenid='".$openid."'");
	$timenow = time();
	if(!empty($result['id'])){
		$db->query("update shares set updatetime=".$timenow." where id=".$result['id']);
	}else{
		$sql = "insert into shares(`contentid`,`shareOpenid`,`addtime`)
				values(
					'".$contentid."',
					'".$openid."',
					'".$timenow."'
				)";
		$db->query($sql);
	}
}


//check click
function logClick($db,$contentid,$clickopenid,$shareopenid,$ip,$money){
	$result = $db->fetch_first("select * from clickcount where contentid=".$contentid." and shareOpenid='".$shareopenid."' and clickOpenid='".$clickopenid."'");
	$timenow = time();
	if(!empty($result['id'])){
		return false;
	}else{
		$sql = "insert into clickcount(`contentid`,`shareOpenid`,`clickOpenid`,`addtime`,`ip`,`money`) 
				values(
					'".$contentid."',
					'".$shareopenid."',
					'".$clickopenid."',
					'".$timenow."',
					'".$ip."',
					'".$money."'
				)";
		$db->query($sql);
		echo $db->error();
	}
	return true;
}

