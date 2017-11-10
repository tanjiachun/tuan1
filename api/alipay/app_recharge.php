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

require_once MALL_ROOT.'/api/alipay/alipay.php';
$notifydata = app_notifycheck();
if($notifydata['validator']) {
	$pdr_out_sn = $notifydata['order_no'];
	$pdr_transaction_id = $notifydata['trade_no'];
	$pdr_amount = $notifydata['price'];
	$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_out_sn='$pdr_out_sn'");
	if($recharge['pdr_payment_state'] == 0) {
		if(!empty($recharge) && floatval($pdr_amount) == floatval($recharge['pdr_amount'])) {	
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
			$data_log['pdl_desc'] = '钱包充值。支付方式：支付宝，充值单号: '.$pdr_transaction_id;
			$data_log['pdl_addtime'] = time();
			DB::insert('pd_log', $data_log);
			//财务表插入数据
			$finance_data=array(
			    'finance_type'=>'recharge',
                'member_id'=>$member['member_id'],
                'finance_state'=>0,
                'finance_amount'=>$pdr_amount,
                'finance'=>time()
            );
			DB::insert('finance',$finance_data);
			DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+$pdr_amount, card_predeposit=card_predeposit+$pdr_amount WHERE member_id='".$member['member_id']."'");
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