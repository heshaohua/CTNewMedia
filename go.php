<?php
/**
* 跳转授权页面
*/
ini_set('display_errors', 1);
require_once './config.inc.php';

$redirecturl = urldecode($_GET['redirecturl']);
$scope = $_GET['scope'];

if(!empty($_SESSION['openid'])){
	header("location:$redirecturl");
}

if(!isset($_GET['code'])){
	//授权请求
	$redirect_uri = SITE_DOMAIN.'go.php?redirecturl='.urlencode($redirecturl).'#'.time();
	\LaneWeChat\Core\WeChatOAuth::getCode($redirect_uri, $state=1, $scope);
}elseif(isset($_GET['code'])){
	$code = $_GET['code'];
	if(empty($code)){
		SystemTool::systemLog($db,'网页授权','获取code异常，code为空');
		echo '网页授权错误，获取code异常';
		exit;
	}
	
	$tempinfo = \LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);
	//file_put_contents("userinfo.txt", print_r($tempinfo,true));
	if(empty($tempinfo['openid'])){
		$extradata = json_encode($tempinfo);
		SystemTool::systemLog($db,'网页授权','获取access_token异常，没有取到openid',$extradata);
		echo '网页授权错误，code invalid';
		exit;
	}
	$_SESSION['openid'] = $tempinfo['openid'];
	$_SESSION['access_token'] = $tempinfo['access_token'];

	//记录临时的page  access_token
	//self::insertPageAccessToken($db,$tempinfo);

	//插入用户信息
	if($scope=='snsapi_userinfo'){
		if(!Userinfo::checkIfuserInfoinDb($db,$tempinfo['openid'])){
			$userinfo = Userinfo::getUserinfoPage($tempinfo['access_token'],$tempinfo['openid']);
			if(!empty($userinfo)&&!isset($userinfo['errcode'])){
				\LaneWeChat\Core\UserManage::insertPageuserinfo($userinfo);	
			}else{
				SystemTool::systemLog($db,'网页授权','获取用户信息异常');
			}
		}
	}
	header("location:$redirecturl");
}

?>