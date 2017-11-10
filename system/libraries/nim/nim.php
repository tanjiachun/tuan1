<?php
/**
 * 网易云信入口文件.
 */
date_default_timezone_set('PRC');
//根目录
defined('IM_ROOT') || define('IM_ROOT', str_replace('\\', '/', dirname(__FILE__)));
//是否处于开发模式(开发模式将记录接口请求参数，错误记录等)
defined('IM_DEBUG') || define('IM_DEBUG', true);
//日志保存路径
defined('IM_LOG_DIR') || define('IM_LOG_DIR', IM_ROOT.'/tmp_log/');
//引入配置文件类
include IM_ROOT.'/nim_config.php';
//相关类
include IM_ROOT.'/lib/Nimbase.php';
include IM_ROOT.'/lib/NimHistory.php';
include IM_ROOT.'/lib/NimLog.php';
include IM_ROOT.'/lib/NimMsg.php';
include IM_ROOT.'/lib/NimRequest.php';
include IM_ROOT.'/lib/NimTeam.php';
include IM_ROOT.'/lib/NimUser.php';
