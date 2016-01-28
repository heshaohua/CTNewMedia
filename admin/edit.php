<?php
/**
 * 编辑文章内容
 */
ini_set('display_errors', 1);
require_once 'config.inc.php';

//check isAmdin
isAdmin();


//保存修改
if(isset($_POST['savepost'])){
	$id = intval($_POST['articleid']);
	$content = addslashes($_POST['content']);
	$title = addslashes($_POST['title']);
	$remark = addslashes(trim($_POST['remark']));
	//$tags = addslashes($_POST['tags']);
	$status = $_POST['status'];
	$categoryid = $_POST['categoryid'];
	$city = $_POST['city'];
	$money = floatval($_POST['money']);
	$leftmoney = floatval($_POST['leftmoney']);
	$priceperclick = floatval($_POST['priceperclick']);
	$clicknum = intval($_POST['clicknum']);
	// if(!$_FILES['slpic']['error']){
		// #/upload/blog/image/{yyyy}{mm}{dd}/{time}{rand:6}
		// $daydir = date('Ymd',time());
		// $filename = time();
		// $filename .= rand(111111,999999);
		// $filename .= '.'.substr($_FILES['slpic']['type'],6);
		// $returnfilename = '/upload/blog/image/'.$daydir.'/'.$filename;

		// $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/blog/image/'.$daydir;
		// if(!is_dir($uploaddir)){
			// mkdir($uploaddir,0777);
		// }

		// if(move_uploaded_file($_FILES['slpic']['tmp_name'],$uploaddir.'/'.$filename)){
			// $listimage = $returnfilename;
		// }
	// }

	if(empty($listimage)){
		if(preg_match('/<img(.*?)src="(.*?)(?=")/',$_POST['content'],$temp)){
			$listimage = $temp[2];
		}
	}
	

	//update 主表
	$sql = "update articles set title='".$title."',remark='".$remark."',status=".$status." ,categoryid='".$categoryid."',city='".$city."',money=".$money.",leftmoney=".$leftmoney.",priceperclick=".$priceperclick.",clicknum=".$clicknum." where id=".$id;
	$db->query($sql);
	if(!empty($listimage)){
		$sql = "update articles set listimage='".$listimage."' where id=".$id;
		$db->query($sql);
	}

	//内容表
	$sql = "update cmscontent set content='".$content."' where articleid=".$id;
	$db->query($sql);

	$msg = "Saved Successfully";
	echo '修改保存成功<script>setTimeout(function(){window.location.href=\'index.php\';},1000)</script>';
}else{
	$id = intval($_GET['id']);	
}

if(empty($id)){
	$msg = 'ID can not be empty,Please Choose an article you want to edit';
}else{
	$sql = "select * from articles a,cmscontent b where a.id=b.articleid and a.id=".$id;
	$data = $db->fetch_first($sql);
	if(empty($data)){
		$msg = "There is No article with this ID";
	}
}

$allcategory = getCategory($db);

//template
include 'template/edit.tpl.php';


//所有分类
function getCategory(&$db){
	$sql = "select * from category";
	$result = $db->fetch_all($sql);
	return $result;
}