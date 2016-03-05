<?php
/**
 * system tool
 */
class SystemTool{
	//获取所有分类
	public static function getAllCategory($db){
		$sql = "select * from category";
		$result = $db->fetch_all($sql);
		return $result;
	}

	//将用户的page  access_token插入数据库
	public static function insertPageAccessToken($db,$data){
		$timenow = time();
		$sql = "insert into pagetoken(`openid`,`access_token`,`refresh_token`,`scope`,`addtime`) values(
				'".$data['openid']."','".$data['access_token']."','".$data['refresh_token']."','".$data['scope']."','".$timenow."'
			)";
		$db->query($sql);
	}


	//网页授权
	public static function checkOpenid($db,$scope = 'snsapi_base'){
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
			self::insertPageAccessToken($db,$tempinfo);

			//插入用户信息
			if($scope=='snsapi_userinfo'){
				if(!Userinfo::checkIfuserInfoinDb($db,$tempinfo['openid'])){
					$userinfo = Userinfo::getUserinfoPage($tempinfo['access_token'],$tempinfo['openid']);
					\LaneWeChat\Core\UserManage::insertPageuserinfo($userinfo);
				}
			}
		}
	}
}
?>