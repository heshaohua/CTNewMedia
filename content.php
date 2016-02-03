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

//处理位置信息
// if(isset($_POST['action'])&&$_POST['action']=='location'){
// 	$latitude = $_POST['latitude'];
// 	$longitude = $_POST['longitude'];
// 	$_SESSION['location'] = 
// 	$addressinfo = getLocationByxy($latitude,$longitude);
	
// }

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
		//点击价格
		$clickprice = rand($contentinfo['minprice'],$contentinfo['maxprice']);
		if($clickprice>$contentinfo['leftmoney']){
			$clickprice = $contentinfo['leftmoney'];
		}

		$clickresult = logClick($db,$contentinfo,$clickOpenid,$shareOpenid,$_SERVER['REMOTE_ADDR'],$clickprice);
		switch($clickresult){
			case -1:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','重复点击')");
				echo '0';
				exit;
				break;
			case 0:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','位置不在分钱范围内')");
				echo '0';
				exit;
				break;
			case -2:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','数据插入错误')");
				echo '0';
				exit;
				break;
			case 1:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','点击有效')");
				echo '0';
				exit;
				break;
			default:
				echo '0';
				exit;
		}
			
	}
	echo '0';
	exit;
}

//用户openid
if(empty($_SESSION['openid'])){
	checkOpenid($db,'snsapi_userinfo');
}

$pageidx = 'content';

$contentid = intval($_GET['id']);
$content = getArticle($db,$contentid);
if(isset($_GET['shareopenid'])&&!empty($_GET['shareopenid'])){
	$shareopenid = $_GET['shareopenid'];
}

if(empty($content)){
	echo '内容不存在或者已删除';
	exit;
}

$title = '同城新媒广告';

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
function logClick($db,$contentinfo,$clickopenid,$shareopenid,$ip,$money){
	//是否已经点击过
	if(checkisClicked($db,$contentinfo['id'],$clickopenid,$shareopenid)){
		$result = -1;
		return $result;
	}
	//位置信息检查
	if(!checkLocationisValid($db,$clickopenid,$contentinfo['city'],$ip)){
		$result = 0;
		return $result;
	}

	
	$timenow = time();
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
	$error = $db->error();
	
	if(!empty($error)){
		$result = -2;
		return $result;
	}

	//刚刚插入的一条数据id
	$clickcountId = $db->insert_id();

	//操作剩余金额等数据
	$sql = "update articles leftmoney = leftmoney - ".$money.",clicknum=clicknum+1 where id=".$contentinfo['id'];
	$db->query($sql);
	$error = $db->error();
	if(!empty($error)){
		$db->query("delete from clickcount where id=".$clickcountId);
		$result = -2;
		return $result;
	}

	$result = 1;
	return $result;
}

//位置信息是否符合要求
// 区域级别(全国)(省[成都市，德阳市,....])(市级[区县1，区县2])
function checkLocationisValid($db,$openid,$contentarea,$ip){
	$userinfo = getUserinfobyDb($db,$openid);
	$setarea = explode('|', $contentarea);
	if(!empty($userinfo['city'])){
		if(in_array($userinfo['city'],$setarea)){
			return true;
		}else{
			return false;
		}
	}

	//通过ip来定位位置信息
	$ipinfo = getIpInfo($ip);
	if(in_array($ipinfo['city'],$setarea)){
		return true;
	}else{
		return false;
	}
}

//判断是否已经点击过
function checkisClicked($db,$contentid,$openid,$shareopenid){
	$result = $db->fetch_first("select * from clickcount where contentid=".$contentid." and openid='".$openid."' and shareopenid='".$shareopenid."'");
	if(empty($result['id'])||$result['isvalid']==0)
		return false;
	else
		return true;
}

