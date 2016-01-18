<?php
/**
 * 写新文章
 */
ini_set('display_errors', 1);
require_once '../config.inc.php';
//check isAmdin
isAdmin();



if(isset($_POST['addpost'])){
	$title = addslashes($_POST['title']);
	$remark = addslashes(trim($_POST['remark']));
	$author = $_SESSION['admin'];
	$tags = addslashes($_POST['tags']);
	$content = addslashes($_POST['content']);
	$status = $_POST['status'];
	if(empty($listimage)){
		if(preg_match('/<img(.*?)src="(.*?)(?=")/',$_POST['content'],$temp)){
			$listimage = $temp[2];
		}
	}

	
	if(!empty($listimage))
		$sql = "insert into articles(`title`,`listimage`,`remark`,`tags`,`author`,`status`) values('".$title."','".$listimage."','".$remark."','".$tags."','".$author."',".$status.")";
	else
		$sql = "insert into articles(`title`,`remark`,`tags`,`author`,`status`) values('".$title."','".$remark."','".$tags."','".$author."',".$status.")";
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