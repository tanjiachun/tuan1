<?php
/**
 * 基础配置文件
 */
if(!defined("InMall")) {
    exit("Access Invalid!");
}
//调试模式开关
define("IS_DEBUG", false);

global $config;
$config = array();
// ----------------------------  CONFIG DB  ----------------------------- //
$config['db']['dbhost'] = 'localhost';
$config['db']['dbuser'] = 'root';
$config['db']['dbpw'] = '';
$config['db']['dbcharset'] = 'utf8';
$config['db']['pconnect'] = '0';
$config['db']['dbname'] = 'mw';
$config['db']['tablepre'] = 'pre_mall_';

// --------------------------  CONFIG OUTPUT  --------------------------- //
$config['output']['charset'] = 'utf-8';
$config['output']['forceheader'] = 1;
$config['output']['gzip'] = '0';

// --------------------------  CONFIG COOKIE  --------------------------- //
$config['cookie']['cookiepre'] = 'F7CF_';
$config['cookie']['cookiedomain'] = '';
$config['cookie']['cookiepath'] = '/';

// -------------------------  CONFIG SECURITY  -------------------------- //
$config['security']['authkey'] = 'e930a54cc0d21VuL';
$config['security']['urlxssdefend'] = 1;
$config['security']['querysafe']['status'] = 1;
$config['security']['querysafe']['dfunction']['0'] = 'load_file';
$config['security']['querysafe']['dfunction']['1'] = 'hex';
$config['security']['querysafe']['dfunction']['2'] = 'substring';
$config['security']['querysafe']['dfunction']['3'] = 'if';
$config['security']['querysafe']['dfunction']['4'] = 'ord';
$config['security']['querysafe']['dfunction']['5'] = 'char';
$config['security']['querysafe']['daction']['0'] = '@';
$config['security']['querysafe']['daction']['1'] = 'intooutfile';
$config['security']['querysafe']['daction']['2'] = 'intodumpfile';
$config['security']['querysafe']['daction']['3'] = 'unionselect';
$config['security']['querysafe']['daction']['4'] = '(select';
$config['security']['querysafe']['daction']['5'] = 'unionall';
$config['security']['querysafe']['daction']['6'] = 'uniondistinct';
$config['security']['querysafe']['dnote']['0'] = '/*';
$config['security']['querysafe']['dnote']['1'] = '*/';
$config['security']['querysafe']['dnote']['2'] = '#';
$config['security']['querysafe']['dnote']['3'] = '--';
$config['security']['querysafe']['dnote']['4'] = '"';
$config['security']['querysafe']['dlikehex'] = 1;
$config['security']['querysafe']['afullnote'] = '0';

// -------------------------  CONFIG WEIXIN  -------------------------- //
$config['weixin']['app_id'] = 'wxd9ac14ffde0728b5';
$config['weixin']['app_secret'] = '968a5ce6639fa3d94d4066fc70adb8fc';
$config['weixin']['mch_id'] = '1468053902';
$config['weixin']['api_key'] = 'MXmKQvLo5wm4DBhPqkxoFxRxGy2Mgs4N';

// -------------------------  CONFIG 网易云信  -------------------------- //
/*
 * 临时开发调试使用
 * */
define('NIM_APP_KEY', '4ab8daeac32c2f8d119076956c997bba');
define('NIM_APP_SECRET', 'd10854404277');

/**
 * 单个号码每日短信发送量限制
 */
define('SMS_SEND_LITMIT', 100);


$config['sms']['sms_template_list'] = array(
    'register_code' => 'SMS_100430006', // 验证码${code}，您正在注册成为团家政用户，感谢您的支持！  //完成
    'login_code' => 'SMS_100820147', // 验证码${code}，您正在使用验证码方式登录团家政账户，请勿泄露。  //完成
    'account_modify' => 'SMS_100855150', // 验证码${code}，您正在尝试变更账户重要 信息，请勿泄露。
    'account_notice' => 'SMS_100405008', // 您正在尝试异地登录团家政账户，若非本人操作，请立即修改登录密码。
    'promotion_code' => 'SMS_100850152', // 验证码${code}，您正在申请参加团家政网站活动，请勿泄露。
    'passwd_modify' => 'SMS_100880133', // 验证码${code}，您正在尝试修改团家政账户登录密码，请勿泄露。    //完成
    'pay_passwd_modify' => 'SMS_100820148', // 验证码${code}，您正在尝试修改团家政账户支付密码，请勿泄露。    //完成
    'account_verify' => 'SMS_100945139', // 验证码${code}，您正在进行团家政账户实名认证，请勿泄露。             //完成
    'domestic_staff_join' => 'SMS_100430006', // 验证码${code}，您正在注册成为团家政用户，感谢您的支持！ //临时
    'domestic_staff_notice' => 'SMS_100815144', // 您已成功注册成为团家政平台上的家政服务人员，团家政网祝您工作顺利!  //完成 机构
    'find_passwd' => 'SMS_100830161', // 验证码${code}，您正在使用验证码方式找回团家政账户登录密码，请勿泄露。 //完成
    'start_work_code' => 'SMS_100395045', // 您已成功付款，家政员到岗后请及时在团家政订单页确认到岗，或将到岗验证码${code}告知家政员，如有问题请咨询客服。 //完成 todo WeChat
    'personal_order_reservation' => 'SMS_100855153', // 您有一个新的预定，请及时到团家政APP我的订单页面查看预定状态。    //完成    todo WeChat
    'personal_order_payment' => 'SMS_100860138', // 您有一个已经付款订单，请使用团家政APP查看订单详情，并及时与雇主确定工作时间。 //完成 todo WeChat
    'company_order_reservation' => 'SMS_100925120', // 您有一个新的预定，请到团家政网站机构管理中心查看预定详情。    //完成    todo WeChat
    'company_order_payment' => 'SMS_100850155', // 您有一个新的已付款订单，请到团家政网站机构管理中心查看订单详情。     //完成 todo WeChat
    'order_renew_notice' => 'SMS_100905161', // 您本月的家政服务即将结束，将从您的团家政账户自动扣除家政员下月工资，请确保账户余额充足，如有问题请咨询客服。
    'order_reviews_notice' => 'SMS_100820149', // 您有新的待评价订单，请到团家政手机APP我的订单页面发表评价，评价完成即可获得团豆豆奖励。 //完成
    'company_register' => 'SMS_100885172', // 您的团家政账号已经注册成功！//临时
    'overdue_seven'=>'SMS_101205047', //七天后订单到期通知   //完成
    'overdue_three'=>'SMS_101070060', //三天后订单到期通知   //完成
    'overdue_one'=>'SMS_101200069', //一天后订单到期通知     //完成
    'order_overdue'=>'SMS_101110073', //订单过期通知        //完成
    'order_lose'=>'SMS_101140072', //订单失效通知           //完成
);



?>
