<?php
date_default_timezone_set('America/Chicago');
//引入微信框架
require_once 'lanwechat/lanewechat.php';

define('SITE_DOMAIN','http://www.zhuangxiuji.com.cn/cms/');

//数据库
$DB['config']['dbhost'] = 'localhost';
$DB['config']['dbname'] = 'cms';
$DB['config']['dbuser'] = 'root';
$DB['config']['dbpassword'] = 'newnonesearch';
require_once 'include/mysql.class.php';
$db = new mysql;
$db->connect($DB['config']['dbhost'], $DB['config']['dbuser'], $DB['config']['dbpassword'], $DB['config']['dbname']);

//地址解析类
require_once 'datacenter/address.php';
//分享相关类
require_once 'datacenter/sharecount.php';
//统计相关类
require_once 'datacenter/clickcount.php';
//用户类
require_once 'datacenter/userinfo.php';
//文章类
require_once 'datacenter/contentclass.php';

//系统工具类
require_once 'datacenter/systemtool.php';