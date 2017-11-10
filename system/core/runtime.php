<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
error_reporting(E_ERROR);
if(PHP_VERSION < '5.3.0') {
	set_magic_quotes_runtime(0);
}
define('MAGIC_QUOTES_GPC', function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());

date_default_timezone_set("Asia/Shanghai");
define("TIMESTAMP", time());

define('APPID', $config['weixin']['app_id']);
define('APPSECRET', $config['weixin']['app_secret']);
define('MCHID', $config['weixin']['mch_id']);
define('API_KEY', $config['weixin']['api_key']);

if(function_exists('ini_get')) {
	$memorylimit = @ini_get('memory_limit');
	if($memorylimit && return_bytes($memorylimit) < 33554432 && function_exists('ini_set')) {
		ini_set('memory_limit', '128m');
	}
}

if($config['security']['urlxssdefend'] && $_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_SERVER['REQUEST_URI'])) {
	xss_check();
}

if(!empty($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false) {
	$config['output']['gzip'] = false;
}
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) =='xmlhttprequest') {
	$inajax	= 1;
} else {
	if(empty($_GET['inajax'])) {
		$inajax	= 0;
	} else {
		$inajax	= 1;
	}
}
$allowgzip = $config['output']['gzip'] && empty($inajax) && function_exists("ob_gzhandler");
$allowgzip ? ob_start('ob_gzhandler') : ob_start();

define('CHARSET', $config['output']['charset']);
if(defined('ProjectName') && ProjectName == 'mobile'){
    header('Content-type: application/json');
}else{
    if($config['output']['forceheader']) {
        @header('Content-Type: text/html; charset='.CHARSET);
    }
}


include_once MALL_ROOT.'/system/libraries/security.php';
$security = new Security();
if(MAGIC_QUOTES_GPC) {
	$_GET = dstripslashes($_GET);
	$_POST = dstripslashes($_POST);
	$_COOKIE = dstripslashes($_COOKIE);
}
$ignore = array('article_content', 'goods_body');
$_GET = !empty($_GET) ? $security->validateForInput($_GET, $ignore) : array();
$_POST = !empty($_POST) ? $security->validateForInput($_POST, $ignore) : array();
$_COOKIE = !empty($_COOKIE) ? $security->validateForInput($_COOKIE, $ignore) : array();

$_GET['act'] = $_GET['act'] ? strtolower($_GET['act']) : ($_POST['act'] ? strtolower($_POST['act']) : "index");
$_GET['op'] = $_GET['op'] ? strtolower($_GET['op']) : ($_POST['op'] ? strtolower($_POST['op']) : "index");

require_once(MALL_ROOT.'/system/database/mysql.php');
$class = 'db_mysql';
$db = & DB::object($class);
$db->set_config($config['db']);
$db->connect();

// 云信封装SDK
include_once MALL_ROOT.'/system/libraries/nim/nim.php';

// 异步短信封装类
include_once MALL_ROOT.'/system/libraries/simple_sms.php';


if(!defined("ProjectName")) {
	require_once(MALL_ROOT.'/include/home.php');
	require_once(MALL_ROOT.'/include/profile.php');
	require_once(MALL_ROOT.'/include/store.php');
	require_once(MALL_ROOT.'/include/agent.php');
	require_once(MALL_ROOT.'/include/pension.php');		
} else {
	require_once(MALL_ROOT.'/include/'.ProjectName.'.php');
}
require_once(MALL_ROOT.'/system/core/router.php');
Router::run();
?>
