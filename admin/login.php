<?php
/**
 * login page
 * 默认页面
 * 提交处理结果（消息）
 */
ini_set('display_errors', 1);
require_once 'config.inc.php';
if(!empty($_SESSION['admin'])){
	header("location:index.php");
}


if(!isset($_POST['login'])){
	if(isset($_GET['msg']))
		$msg = $_GET['msg'];
	include 'template/login.html';
}else{
	if(empty($_POST['username'])||empty($_POST['password'])){
		$msg = '用户名或密码错误';
	}

	if(!in_array($_POST['username'],array('admin'))||md5($_POST['password'])!=md5('2014admin')){
		$msg = '用户名或密码错误';
	}
	if(!empty($msg)){
		header("location:login.php?msg=".$msg);
	}else{
		$_SESSION['admin'] = 'admin';
		header("location:index.php");
	}
}