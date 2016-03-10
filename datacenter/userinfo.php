<?php
/**
 * 用户信息处理， 增，删，查，改
 * 
 */
class Userinfo{
	
	//检查openid是否已经关注
	public static function checkSubscribe($db,$openid){
		$sql = "select subscribe from users where openid='".$openid."'";
		$result = $db->fetch_first($sql);
		if(empty($result)||$result['subscribe']==0){
			return false;
		}
		return true;
	}

	//更新关注状态，影响极大
	public static function updateSubscribe($db,$openid,$subscribe){
		$sql = "update users set subscribe=".$subscribe." where openid='".$openid."'";
		$db->query($sql);
	}

	//检查是否已经插入用户信息
	public static function checkIfuserInfoinDb($db,$openid){
		$sql = "select * from users where openid='".$openid."'";
		$result = $db->fetch_first($sql);
		if(empty($result)||empty($result['nickname'])){
			return false;
		}
		return true;
	}

	//从数据库取用户信息
	public static function getUserinfobyDb($db,$openid){
		$sql = "select * from users where openid='".$openid."'";
		$result = $db->fetch_first($sql);
		if(empty($result))
			return false;
		return $result;
	}

	//拉取用户信息，网页授权
	public static function getUserinfoPage($access_token,$openid){
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		$tempinfo = file_get_contents($url);
		$userinfo = json_decode($tempinfo,true);
		return $userinfo;
	}

}
?>