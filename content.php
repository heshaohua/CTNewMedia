<?php
/**
* content.php   详细页面
*/

ini_set('display_errors', 1);
require_once './config.inc.php';
require_once './include/functions.php';

$pageidx = 'content';


$content = getArticle($db,62);

$title = $content['title'];

//template
include 'template/content.html';


//文章内容
function getArticle($db,$id){
	$id = intval($id);
	if($id==0) return false;
	$result = $db->fetch_first("select * from articles where id=".$id);
	if(!empty($result)&&$result!=false){
		$contentresult = $db->fetch_first("select * from cmscontent where articleid=".$id);
		if(!empty($contentresult)&&$contentresult!=false){
			$result['content'] = $contentresult['content'];
			return $result;
		}
		return false;
	}
	return false;
}