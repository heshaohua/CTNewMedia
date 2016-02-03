<?php
/**
 * 处理datatable ajax请求
 */
require_once 'config.inc.php';

if(isset($_GET['page'])){
	$page = intval($_GET['page']);
	if($page<1) $page = 1;
}else{
	$page = 1;
}

if(isset($_GET['pagesize'])){
	$pagesize = intval($_GET['pagesize']);
}else{
	$pagesize = 10;
}

$aColumns = array('id','status','title','urlalias','listimage','remark', 'addtime','visitcount','categoryid','city','money','leftmoney','clicknum','priceperclick','minprice','maxprice');


/** 
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
{
	$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
}else{
	$sLimit = "LIMIT 0,10";
}
	
	
/**
 * Ordering
 */
$sOrder = "";
if ( isset( $_GET['iSortCol_0'] ) )
{
	$sOrder = "ORDER BY  ";
	for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
	{
		if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
		{
			$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
		}
	}
		
	$sOrder = substr_replace( $sOrder, "", -2 );
	if ( $sOrder == "ORDER BY" )
	{
		$sOrder = "ORDER BY addtime desc";
	}else{
		$sOrder .= ",addtime desc";
	}
}else{
	$sOrder = "ORDER BY addtime desc";
}
	
	
/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
*/
$sWhere = "";
if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
}
	
/* Individual column filtering */
for ( $i=0 ; $i<count($aColumns) ; $i++ )
{
	if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
	{
		if ( $sWhere == "" )
		{
			$sWhere = "WHERE ";
		}
		else
		{
			$sWhere .= " AND ";
		}
		$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
	}
}
	
	
/*
* SQL queries
*  * Get data to display
*/
$sTable = 'articles';
$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS id, ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";

//total num
$totalquery = $db->fetch_first("select count(*) as num from articles where status=1");
$totalnum = intval($totalquery['num']);
$totalpage = ceil($totalnum/$pagesize);
if($page>$totalpage) $page = $totalpage;

//current page data list

$datalist = $db->fetch_all($sQuery);

#var_dump($datalist);die();

$outputdata['sEcho'] = intval($_GET['sEcho']);
$outputdata['iTotalRecords'] = count($datalist);
$outputdata['iTotalDisplayRecords'] = $totalnum;
$outputdata['aaData'] = array();

foreach($datalist as $datatemp){
	$item = array();
	$item[] = $datatemp['id'];
	$item[] = $datatemp['title'];
	$tempcategory = getCategoryName($db,$datatemp['categoryid']);
	$item[] = $tempcategory['name'];
	$item[] = $datatemp['city'];
	$item[] = ($datatemp['status']==0)?'未发布':'已发布';
	
	$item[] = '&yen;'.$datatemp['money'];
	$item[] = '&yen;'.$datatemp['leftmoney'];
	$item[] = '&yen;'.$datatemp['minprice'].' ~ '.'&yen;'.$datatemp['maxprice'];
	$item[] = $datatemp['clicknum'];
	$item[] = $datatemp['addtime'];
	$item[] = '<a target="_blank" href="http://www.zhuangxiuji.com.cn/cms/admin/content.php?id='.$datatemp['id'].'">
                <i></i>
                详细&nbsp;
            </a>
            <a  href="edit.php?id='.$datatemp['id'].'">
                <i></i>
                &nbsp;编辑&nbsp;
            </a>
            <a id="deleteitem'.$datatemp['id'].'"  href="javascript:deletearticle(\''.$datatemp['id'].'\');">
                <i></i>
                &nbsp;删除
            </a>';
	$outputdata['aaData'][] = $item;
}
#var_dump($outputdata['aaData']);die();

echo json_encode($outputdata);


//取分类名
function getCategoryName(&$db,$id){
	$sql = "select * from category where id=".$id;
	$result = $db->fetch_first($sql);
	return $result;
}
