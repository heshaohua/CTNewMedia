<?php
/**
* functions.php
* 公共functions 库
*/


//获取所有分类
function getAllCategory($db){
	$sql = "select * from category";
	$result = $db->fetch_all($sql);
	return $result;
}


//网页授权
function checkOpenid($db,$scope = 'snsapi_base'){
	if(empty($_SESSION['openid'])&&!isset($_GET['code'])){
		//授权请求
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		//file_put_contents('url.txt',$url."\n",FILE_APPEND);
		$redirect_uri = $url;
		\LaneWeChat\Core\WeChatOAuth::getCode($redirect_uri, $state=1, $scope);
	}elseif(isset($_GET['code'])){
		$code = $_GET['code'];
		$tempinfo = \LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);
		//file_put_contents("userinfo.txt", print_r($tempinfo,true));
		$_SESSION['openid'] = $tempinfo['openid'];
		$_SESSION['access_token'] = $tempinfo['access_token'];

		//记录临时的page  access_token
		insertPageAccessToken($db,$tempinfo);

		//插入用户信息
		if($scope=='snsapi_userinfo'){
			if(!checkIfuserInfoinDb($db,$tempinfo['openid'])){
				\LaneWeChat\Core\UserManage::addUser($tempinfo['openid']);
			}
		}
	}
}


//检查openid是否已经关注
function checkSubscribe($db,$openid){
	$sql = "select subscribe from users where openid='".$openid."'";
	$result = $db->fetch_first($sql);
	if(empty($result)||$result['subscribe']==0){
		return false;
	}
	return true;
}

//检查是否已经插入用户信息
function checkIfuserInfoinDb($db,$openid){
	$sql = "select * from users where openid='".$openid."'";
	$result = $db->fetch_first($sql);
	if(empty($result)||empty($result['nickname'])){
		return false;
	}
	return true;
}

//将用户的page  access_token插入数据库
function insertPageAccessToken($db,$data){
	$timenow = time();
	$sql = "insert into pagetoken(`openid`,`access_token`,`refresh_token`,`scope`,`addtime`) values(
			'".$data['openid']."','".$data['access_token']."','".$data['refresh_token']."','".$data['scope']."','".$timenow."'
		)";
	$db->query($sql);
}

//查询ip信息
function getIpInfo($ip){
	$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip;
	$tempdata = file_get_contents($url);
	//var remote_ip_info = {"ret":1,"start":-1,"end":-1,"country":"\u4e2d\u56fd","province":"\u56db\u5ddd","city":"\u6210\u90fd","district":"","isp":"","type":"","desc":""};
	$tempdata = substr($tempdata,strpos($tempdata,'{'));
	file_put_contents("ipdata.log", $tempdata);
	$ipinfo = json_decode($tempdata,true);
	return $ipinfo;
}

//根据坐标查询地址
function getLocationByxy($latitude,$longitude){
	$url = "http://apis.map.qq.com/ws/geocoder/v1";
	$location = $latitude.",".$longitude;
	//$coord_type = 1;
	$key = 'NOJBZ-ZQCC4-JEYU6-XSJ6M-X4C7H-SLB7F';
	$addressinfo = file_get_contents($url."?"."location=".$location."&key=".$key);
	return json_decode($addressinfo,true);
}


//从数据库取用户信息
function getUserinfobyDb($db,$openid){
	$sql = "select * from users where openid='".$openid."'";
	$result = $db->fetch_first($sql);
	if(empty($result))
		return false;
	return $result;
}