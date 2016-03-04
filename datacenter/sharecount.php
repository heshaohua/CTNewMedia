<?php
/**
 *  share统计
 *
 */
class ShareCount{

	/**
	 * share内容记录
	 * @param  [type] $db        [description]
	 * @param  [type] $contentid [description]
	 * @param  [type] $openid    [description]
	 * @return [type]            [description]
	 */
	public static function logShare($db,$contentid,$openid){
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
		$errormsg = $db->error();
		if(!empty($errormsg))
			return false;
		else
			return true;
	}
	
	
}
?>