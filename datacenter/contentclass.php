<?php
/**
 * content 类
 */
class ContentClass{
	
	//文章内容
	public static function getArticle($db,$id){
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

}
?>