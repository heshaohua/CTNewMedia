<?php
/**
 * delete app
 */

ini_set('display_errors', 1);
require_once 'config.inc.php';

//check isAmdin
isAdmin();

$id = intval($_GET['id']);
if(empty($id)){
	echo 'no this article';
	exit();
}	
$sql = "select id from articles where id=".$id;
$data = $db->fetch_first($sql);
if(empty($data)){
	$msg = "There is No article with this ID";
	echo $msg;
	exit();
}

$sql = "delete from articles where id=".$id;
$db->query($sql);

$sql = "delete from cmscontent where articleid=".$id;
$db->query($sql);

echo 'success';