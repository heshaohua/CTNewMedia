<?php
/**
 * 点击统计
 */

class ClickCount{

	/**
	 * 是否已经点击过了
	 * @param  [type] $db          [description]
	 * @param  [type] $contentid   [description]
	 * @param  [type] $openid      [description]
	 * @param  [type] $shareopenid [description]
	 * @return [type]              [description]
	 */
	public static function  checkisClicked($db,$contentid,$openid,$shareopenid){
		$result = $db->fetch_first("select * from clickcount where contentid=".$contentid." and clickOpenid='".$openid."' and shareOpenid='".$shareopenid."'");
		if(empty($result['id'])||$result['isvalid']==0)
			return false;
		else
			return true;
	}


	/**
	 * 检查有效范围
	 * @param  [type] 
	 * $contentarea = array(
	 *			'country' => 'China'/ 'all',
	 *			'province' => '',
	 *			'city' => 'all'/array(c1,c2,c3)/'',
	 *			'district' => 'all'/array(d1,d2,d3)/'',
	 *	);
	 * @param  [type] $addressinfo [description]
	 * @return [type]              [description]
	 */
	public static function checkLocationisValid($contentarea,$addressinfo){
		$country = $addressinfo['country'];
		$province = $addressinfo['province'];
		$city = $addressinfo['city'];
		$district = $addressinfo['district'];

		$Ccountry = $contentarea['country'];
		$Cprovince = $contentarea['province'];
		$Ccity = $contentarea['city'];
		$Cdistrict = $contentarea['district'];

		//country
		if($Ccountry == 'all') return true;
		if($Ccountry != $country) return false;

		//province
		if($Cprovince == 'all') return true;
		if($province != $Cprovince) return false;

		//city
		if($Ccity == 'all') return true;
		if($city != $Ccity) return false;

		//district
		if($Cdistrict == 'all') return true;
		if(!in_array($district,$Cdistrict)) return false;

		return true;
	}

	/**
	 * [logClick description] 记录点击
	 * @param  [type] $db          [description]
	 * @param  [type] $contentinfo [description]
	 * @param  [type] $clickopenid [description]
	 * @param  [type] $shareopenid [description]
	 * @param  [type] $ip          [description]
	 * @param  [type] $money       [description]
	 * @param  [type] $addressinfo [description]
	 * @return [type]              [description]
	 */
	public static function logClick($db,$contentinfo,$clickopenid,$shareopenid,$ip,$money,$addressinfo){
		//是否已经点击过
		if(self::checkisClicked($db,$contentinfo['id'],$clickopenid,$shareopenid)){
			$result = -1;
			return $result;
		}

		//位置信息检查
		$contentarea = json_decode($contentinfo['city'],true);
		if(!self::checkLocationisValid($contentarea,$addressinfo)){
			$result = 0;
			return $result;
		}

		
		$timenow = time();
		$sql = "insert into clickcount(`contentid`,`shareOpenid`,`clickOpenid`,`addtime`,`ip`,`money`) 
				values(
						'".$contentinfo['id']."',
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
		$sql = "update articles set leftmoney = leftmoney - ".$money.",clicknum=clicknum+1 where id=".$contentinfo['id'];
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
}

?>