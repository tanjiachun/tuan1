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

if(file_exists(MALL_ROOT.'/cache/cache_setting.php')){
    require_once(MALL_ROOT."/cache/cache_setting.php");
} else {
    $query = DB::query("SELECT * FROM ".DB::table('setting'));
    while($value = DB::fetch($query)) {
        $setting[$value['setting_key']] = $value['setting_value'];
    }
}

require_once MALL_ROOT.'/api/alipay/alipay.php';
$notifydata = trade_notifycheck();
if($notifydata['validator']) {
    $out_sn = $notifydata['order_no'];
    $transaction_id = $notifydata['trade_no'];
    $book_amount = $notifydata['price'];
    $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE out_sn='$out_sn'");
    if($book['book_state'] == 10) {
        if(floatval($book_amount) == floatval($book['book_amount'])) {
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
            $book_data = array();
            $book_data['transaction_id'] = $transaction_id;
            $book_data['payment_name'] = '支付宝';
            $book_data['payment_code'] = 'alipay';
            $book_data['book_state'] = 30;
            $book_data['payment_time'] = time();
            DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
            //财务表插入数据（收入）
            $finance_data=array(
                'finance_type'=>'bail',
                'agent_id'=>$book['agent_id'],
                'finance_state'=>0,
                'finance_amount'=>$book['deposit_amount'],
                'finance_time'=>time()
            );
            DB::insert('finance',$finance_data);
            echo 'success';
        } else {
            echo 'fail';
        }
    } else {
        echo 'success';
    }
} else {
    echo 'fail';
}
?>