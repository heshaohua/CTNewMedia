<?php
/**
 * 地址转换
 */
class Addressinfo{

	/**
	 * 根据ip获取位置信息
	 * @param  $ip
	 * @return remote_ip_info = {"ret":1,"start":-1,"end":-1,"country":"\u4e2d\u56fd","province":"\u56db\u5ddd","city":"\u6210\u90fd","district":"","isp":"","type":"","desc":""}
	 */
	public static function getAddressByIp($ip){
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip;
		$tempdata = file_get_contents($url);
		$tempdata = substr($tempdata,strpos($tempdata,'{'));
		//file_put_contents("ipdata.log", $tempdata);
		$ipinfo = json_decode($tempdata,true);
		return $ipinfo;
	}

	/**
	 * 坐标转换地址信息
	 * @param  [type] $latitude  [description]
	 * @param  [type] $longitude [description]
	 * @return [type]            [description]
	 */
	public static function getLocationByxy($latitude,$longitude){
		$url = "http://apis.map.qq.com/ws/geocoder/v1";
		$location = $latitude.",".$longitude;
		//$coord_type = 1;
		$key = 'NOJBZ-ZQCC4-JEYU6-XSJ6M-X4C7H-SLB7F';
		$addressinfo = file_get_contents($url."?"."location=".$location."&key=".$key);
		return json_decode($addressinfo,true);
	}

}
?>