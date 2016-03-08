<?php
/**
 * 写新文章
 */
ini_set('display_errors', 1);
require_once 'config.inc.php';
//check isAmdin
isAdmin();



if(isset($_POST['addpost'])){
	$title = addslashes(trim($_POST['title']));
	//$remark = addslashes(trim($_POST['remark']));
	$author = $_SESSION['admin'];
	//$tags = addslashes($_POST['tags']);
	$content = addslashes($_POST['content']);
	$status = $_POST['status'];
	$categoryid = $_POST['categoryid'];
	$minprice = floatval($_POST['minprice']);
	$maxprice = floatval($_POST['maxprice']);

	//有效范围
	$areadata = array();
	$areadata['country'] = urlencode('中国');

	$province = trim($_POST['province']);
	$areadata['province'] = urlencode($province);

	$city = trim($_POST['city']);
	$district = trim($_POST['district']);
	if($city=='全省'){
		$city = 'all';
		$district = '';
	}else{
		if(!empty($district)){
			$districttemp = explode('|', $district);
			foreach($districttemp as $key=>$item){
				$districttemp[$key] = urlencode($item);
			}
		}
	}
	$areadata['city'] = urlencode($city);

	$areadata['district'] = isset($districttemp)?$districttemp:urlencode($district);



	$money = floatval($_POST['money']);
	if(empty($listimage)){
		if(preg_match('/<img(.*?)src="(.*?)(?=")/',$_POST['content'],$temp)){
			$listimage = $temp[2];
		}
	}

	if(!empty($listimage)&&strpos($listimage,'//')===false){
		$listimage = 'http://www.zhuangxiuji.com.cn'.$listimage;
	}

	
	if(!empty($listimage))
		$sql = "insert into articles(`title`,`listimage`,`remark`,`author`,`status`,`categoryid`,`city`,`money`,`leftmoney`,`minprice`,`maxprice`) values('".$title."','".$listimage."','".$remark."','".$author."',".$status.",'".$categoryid."','".urldecode(json_encode($areadata))."',".$money.",".$money.",".$minprice.",".$maxprice.")";
	else
		$sql = "insert into articles(`title`,`remark`,`author`,`status`,`categoryid`,`city`,`money`,`leftmoney`,`minprice`,`maxprice`) values('".$title."','".$remark."','".$author."',".$status.",'".$categoryid."','".urldecode(json_encode($areadata))."',".$money.",".$money.",".$minprice.",".$maxprice.")";
	$db->query($sql);
	$articleId = $db->insert_id();
	if($articleId){
		$urlalias = prepareUrlText($title).'-'.$articleId;
		$sql = "update articles set urlalias='".$urlalias."' where id=".$articleId;
		$db->query($sql);

		//content表
		$sql = "insert into cmscontent(`articleid`,`content`) values(".$articleId.",'".$content."')";
		$db->query($sql);

		echo '成功,3秒之后跳转回写文章的页面<script>setTimeout(function(){window.location.href=window.location.href;},3000)</script>';
		
	}
}else{
	$allcategory = getCategory($db);
	//template
	include 'template/add.tpl.php';
}

//alias算法
function prepareUrlText($str){
	$notAcceptableCharactersRegex = '#[^-a-zA-Z0-9_ ]#';
	$str = preg_replace($notAcceptableCharactersRegex, '', $str);
	$str = trim($str);
	$str = preg_replace('#[-_ ]+#', '-', $str);
	$str = trim($str,'-');
	return strtolower($str);
}

//所有分类
function getCategory(&$db){
	$sql = "select * from category";
	$result = $db->fetch_all($sql);
	return $result;
}