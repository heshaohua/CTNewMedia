<?php
/**
 * 编辑文章内容
 */
ini_set('display_errors', 1);
require_once 'config.inc.php';

//check isAmdin
isAdmin();

$id = intval($_GET['id']);	


if(empty($id)){
	$msg = 'ID can not be empty,Please Choose an article you want to edit';
}else{
	$sql = "select * from articles a,cmscontent b where a.id=b.articleid and a.id=".$id;
	$content = $db->fetch_first($sql);
	if(empty($content)){
		$msg = "There is No article with this ID";
	}else{
		$tempareadata = json_decode($content['city'],true);
		$data['province'] = $tempareadata['province'];
		$data['tempcity'] = $tempareadata['city'];
		$data['district'] = is_array($tempareadata['district'])?implode(',',$tempareadata['district']):'';
		if(!empty($data['district'])){
			$areadata = $data['tempcity'].'('.$data['district'].')';
		}else if($data['tempcity']!='all'){
			$areadata = $data['tempcity'];
		}else{
			$areadata = $data['province'];
		}
	}
}

$allcategory = getCategory($db);

//template
include 'template/content.tpl.php';


//所有分类
function getCategory(&$db){
	$sql = "select * from category";
	$result = $db->fetch_all($sql);
	return $result;
}