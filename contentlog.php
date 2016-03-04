<?php
/**
 * content的统计信息
 */
ini_set('display_errors', 1);
require_once './config.inc.php';


//处理分享统计
if(isset($_POST['action'])&&$_POST['action']=='share'){
	$contentid = intval($_POST['id']);
	$openid = trim($_POST['openid']);
	if(!empty($contentid)&&!empty($openid)){
		if(empty($_SESSION['openid'])){
			echo json_encode(array('result'=>'failed','msg'=>'获取session openid失败'));
			exit;
		}
		if($_SESSION['openid']!=$openid){
			echo json_encode(array('result'=>'failed','msg'=>'openid与session openid不符'));
			exit;
		}
		if(ShareCount::logShare($db,$contentid,$openid)){
			echo json_encode(array('result'=>'success','msg'=>'分享成功'));
			exit;
		}else{
			echo json_encode(array('result'=>'failed','msg'=>'数据库错误'));
			exit;
		}	
	}
	echo json_encode(array('result'=>'failed','msg'=>'参数错误'));
	exit;
}


//处理分享点击统计
if(isset($_POST['action'])&&$_POST['action']=='clickshare'){
	$contentid = intval($_POST['id']);
	$clickOpenid = trim($_POST['openid']);
	$shareOpenid = trim($_POST['shareopenid']);

	if(!empty($contentid)&&!empty($clickOpenid)&&!empty($shareOpenid)&&$clickOpenid!=$shareOpenid){
		$contentinfo = ContentClass::getArticle($db,$contentid);
		if($contentinfo===false){
			echo '0';
			exit;
		}
		
		//点击价格
		$clickprice = rand($contentinfo['minprice'],$contentinfo['maxprice']);
		if($clickprice>$contentinfo['leftmoney']){
			$clickprice = $contentinfo['leftmoney'];
		}

		//位置信息
		if(!empty($_SESSION['location'])){
			$xytude = explode(',', $_SESSION['location']);
			$addressinfo = Addressinfo::getLocationByxy($xytude[0],$xytude[1]);
		}else{
			$addressinfo = Addressinfo::getAddressByIp($_SERVER['REMOTE_ADDR']);
		}

		$clickresult = ClickCount::logClick($db,$contentinfo,$clickOpenid,$shareOpenid,$_SERVER['REMOTE_ADDR'],$clickprice,$addressinfo);
		switch($clickresult){
			case -1:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','重复点击')");
				$msg = array('result'=>false,'msg'=>'重复点击');
				echo json_encode($msg);
				exit;
				break;
			case 0:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','位置不在分钱范围内')");
				$msg = array('result'=>false,'msg'=>'位置不在分钱范围内');
				echo json_encode($msg);
				exit;
				break;
			case -2:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','数据插入错误')");
				$msg = array('result'=>false,'msg'=>'数据插入错误');
				echo json_encode($msg);
				exit;
				break;
			case 1:
				$db->query("insert into clicklog(`contentid`,`openid`,`shareopenid`,`ip`,`msg`) values(".$contentinfo['id'].",'".$clickOpenid."','".$shareOpenid."','".$_SERVER['REMOTE_ADDR']."','点击有效')");
				$msg = array('result'=>true,'msg'=>'本次阅读为你的好友增加￥'.$clickprice);
				echo json_encode($msg);
				exit;
				break;
			default:
				$msg = array('result'=>false,'msg'=>'服务器异常错误');
				echo json_encode($msg);
				exit;
		}	
	}
	echo '0';
	exit;
}