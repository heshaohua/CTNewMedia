<?php
/**
 * system tool
 */
class SystemTool{
	/**
	 * 获取所有分类
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	public static function getAllCategory($db){
		$sql = "select * from category";
		$result = $db->fetch_all($sql);
		return $result;
	}

	/**
	 * 将用户的page  access_token插入数据库
	 * @param  [type] $db   [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public static function insertPageAccessToken($db,$data){
		$timenow = time();
		$sql = "insert into pagetoken(`openid`,`access_token`,`refresh_token`,`scope`,`addtime`) values(
				'".$data['openid']."','".$data['access_token']."','".$data['refresh_token']."','".$data['scope']."','".$timenow."'
			)";
		$db->query($sql);
	}


	/**
	 * [checkOpenid description]
	 * @param  [type] $db    [description]
	 * @param  string $scope [description]
	 * @return [type]        [description]
	 */
	public static function checkOpenid($db,$scope = 'snsapi_base',$redirecturl){
		if(empty($redirecturl)||empty($scope)){
			echo '参数异常';
			exit;
		}
		$_SESSION['redirectpage'] = $redirecturl;
		$gourl = SITE_DOMAIN.'go.php?scope='.$scope;
		header("location:$gourl");
		exit;
	}

	/**
	 * 系统log
	 * @param  [type] $db     [description]
	 * @param  [type] $action [description]
	 * @param  [type] $msg    [description]
	 * @return [type]         [description]
	 */
	public static function systemLog($db,$action,$msg,$extradata=''){
		$sql = "insert into systemlog(`action`,`msg`,`extradata`) values('".$action."','".$msg."','".$extradata."')";
		$db->query($sql);
	}
}
?>