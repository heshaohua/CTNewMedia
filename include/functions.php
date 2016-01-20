<?php
/**
* functions.php
* 公共functions 库
*/


//获取所有分类
function getAllCategory($db){
	$sql = "select * from category";
	$result = $db->fetch_all($sql);
	return $result;
}


