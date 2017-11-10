<?php
/**
 * 平台后台入口文件
 */
define('InMall', true);
define('ProjectName', 'admin');
define('MALL_ROOT', str_replace('\\','/',dirname(__FILE__)));
//注释掉了这行，2017-11-9 Jason
//define('SiteUrl', htmlspecialchars(strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')))));
require(MALL_ROOT.'/config.ini.php');
require(MALL_ROOT.'/system/function/common.php');
require(MALL_ROOT.'/system/core/runtime.php');