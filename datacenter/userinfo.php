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

}
?>