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

require_once MALL_ROOT.'/api/weixin/weixin.php';
$notify = new Notify_pub();
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
$notify->saveData($xml);

if($notify->checkSign() == TRUE && $notify->data['result_code'] == 'SUCCESS' && $notify->data['return_code'] == 'SUCCESS') {
	$pdr_out_sn = $notify->data['out_trade_no'];
	$pdr_transaction_id = $notify->data['transaction_id'];
	$pdr_amount = $notify->data['total_fee'];
	$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_out_sn='$pdr_out_sn'");
	if($recharge['pdr_payment_state'] == 0) {
		$diff_amount = $pdr_amount-$recharge['pdr_amount']*100;
		if(!empty($recharge) && ($diff_amount >= -1 && $diff_amount<=1)) {
			$data = array();
			$data['pdr_transaction_id'] = $pdr_transaction_id;
			$data['pdr_payment_state'] = 1;
			$data['pdr_payment_time'] = time();			
			DB::update('pd_recharge', $data, array('pdr_id'=>$recharge['pdr_id']));
			$pdr_amount = $recharge['pdr_amount']+$recharge['pdr_discount'];
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$recharge['pdr_memberid']."'");
			$data_log = array();
			$data_log['pdl_memberid'] = $member['member_id'];
			$data_log['pdl_memberphone'] = $member['member_phone'];
			$data_log['pdl_stage'] = 'recharge';
			$data_log['pdl_type'] = 1;
			$data_log['pdl_price'] = $pdr_amount;
			$data_log['pdl_predeposit'] = $member['available_predeposit']+$pdr_amount;
			$data_log['pdl_desc'] = '钱包充值。支付方式：微信支付，充值单号: '.$pdr_transaction_id;
			$data_log['pdl_addtime'] = time();
			DB::insert('pd_log', $data_log);
			DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+$pdr_amount, card_predeposit=card_predeposit+$pdr_amount WHERE member_id='".$member['member_id']."'");
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