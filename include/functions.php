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


//检查用户openid，不存在则获取
function checkOpenid(){
	if(empty($_SESSION['openid'])){
		//授权请求
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		file_put_contents('url.txt',$url."\n",FILE_APPEND);
		$redirect_uri = $url;
		\LaneWeChat\Core\WeChatOAuth::getCode($redirect_uri, $state=1, $scope='snsapi_base');
		$code = $_GET['code'];
		$tempinfo = \LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);
		//var_dump($tempinfo);exit;
		$_SESSION['openid'] = $tempinfo['openid'];
		$_SESSION['access_token'] = $tempinfo['access_token'];
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
