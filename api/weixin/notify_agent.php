<?php
error_reporting(0);
define('InMall', true);
define('MALL_ROOT', substr(dirname(__FILE__), 0, -10));

require_once MALL_ROOT.'/config.ini.php';
require_once MALL_ROOT.'/system/database/mysql.php';
$class = 'db_mysql';
$db = & DB::object($class);
$db->set_config($config['db']);
$db->connect();

define('APPID', $config['weixin']['app_id']);
define('APPSECRET', $config['weixin']['app_secret']);
define('MCHID', $config['weixin']['mch_id']);
define('API_KEY', $config['weixin']['api_key']);

if(file_exists(MALL_ROOT.'/cache/cache_setting.php')){
    require_once(MALL_ROOT."/cache/cache_setting.php");
} else {
    $query = DB::query("SELECT * FROM ".DB::table('setting'));
    while($value = DB::fetch($query)) {
        $setting[$value['setting_key']] = $value['setting_value'];
    }
}

require_once MALL_ROOT.'/api/weixin/weixin.php';
$notify = new Notify_pub();
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$notify->saveData($xml);
if($notify->checkSign() == TRUE && $notify->data['result_code'] == 'SUCCESS' && $notify->data['return_code'] == 'SUCCESS') {
    $out_sn = $notify->data['out_trade_no'];
    $transaction_id = $notify->data['transaction_id'];
    $book_amount = $notify->data['total_fee'];
    $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE out_sn='$out_sn'");
    if($book['book_state'] == 10) {
        $diff_amount = $book_amount-$book['book_amount']*100;
        if($diff_amount >= -1 && $diff_amount<=1) {
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
            $book_data = array();
            $book_data['transaction_id'] = $transaction_id;
            $book_data['payment_name'] = '微信支付';
            $book_data['payment_code'] = 'weixin';
            $book_data['book_state'] = 30;
            $book_data['payment_time'] = time();
            DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));

            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>';
        } else {
            echo '<xml><return_code><![CDATA[FAIL]]></return_code></xml>';
        }
    } else {
        echo '<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>';
    }
} else {
    echo '<xml><return_code><![CDATA[FAIL]]></return_code></xml>';
}

?>