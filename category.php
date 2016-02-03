<?php
/**
* 分类列表
*/

ini_set('display_errors', 1);
require_once 'config.inc.php';
require_once 'include/functions.php';

$pageidx = 'category';
$title = '分类列表';

//分类数据
$categoryid = intval($_GET['id']);
if(empty($categoryid)){
	echo '内容不存在或已删除';
	exit;
}

$contents = $db->fetch_all("select * from articles where categoryid=".$categoryid);


//template
include 'template/category.html';