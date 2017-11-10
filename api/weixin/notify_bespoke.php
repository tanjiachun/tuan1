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
	$bespoke_amount = $notify->data['total_fee'];
	$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE out_sn='$out_sn'");
	if($bespoke['bespoke_state'] == 10) {
		$diff_amount = $bespoke_amount-$bespoke['bespoke_amount']*100;
		if($diff_amount >= -1 && $diff_amount<=1) {
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$bespoke['member_id']."'");
			$bespoke_data = array();
			$bespoke_data['transaction_id'] = $transaction_id;
			$bespoke_data['payment_name'] = '微信支付';
			$bespoke_data['payment_code'] = 'weixin';
			$bespoke_data['bespoke_state'] = 20;
			$bespoke_data['payment_time'] = time();
			DB::update('pension_bespoke', $bespoke_data, array('bespoke_id'=>$bespoke['bespoke_id']));
			//红包
			$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='reward' ORDER BY red_t_amount DESC");
			while($value = DB::fetch($query)) {
				if($value['red_t_total'] > $value['red_t_giveout'] && $value['red_t_amount'] <= $bespoke['bespoke_amount']) {
					$red_template = $value;
					$red_t_id = $value['red_t_id'];
					break;
				}
			}
			if(!empty($red_t_id)) {
				if($red_template['red_t_period_type'] == 'duration') {
					$red_template['red_t_starttime'] = strtotime(date('Y-m-d'));
					$red_template['red_t_endtime'] = $red_template['red_t_starttime']+3600*24*($red_template['red_t_days']+1)-1;
				}
				$red_data = array(
					'red_sn' => makesn(2),
					'member_id' => $member['member_id'],
					'red_t_id' => $red_template['red_t_id'],
					'red_title' => $red_template['red_t_title'],
					'red_price' => $red_template['red_t_price'],
					'red_starttime' => $red_template['red_t_starttime'],
					'red_endtime' => $red_template['red_t_endtime'],
					'red_limit' => $red_template['red_t_limit'],
					'red_cate_id' => $red_template['red_t_cate_id'],
					'red_state' => 0,
					'red_addtime' => time(),
				);
				$red_id = DB::insert('red', $red_data, 1);
				if(!empty($red_id)) {
					DB::update('red_template', array('red_t_giveout'=>$red_template['red_t_giveout']+1), array('red_t_id'=>$red_template['red_t_id']));
				}
			}
			//机构收益统计
			$profit_data = array(
				'pension_id' => $bespoke['pension_id'],
				'profit_stage' => 'order',
				'profit_type' => 1,
				'profit_amount' => $bespoke['bespoke_amount'],
				'profit_desc' => $bespoke['bespoke_name'].'预定房间，预定单号：'.$bespoke['bespoke_sn'],
				'is_freeze' => 1,
				'bespoke_id' => $bespoke['bespoke_id'],
				'bespoke_sn' => $bespoke['bespoke_sn'],
				'add_time' => time(),
			);
			DB::insert('pension_profit', $profit_data);
			DB::query("UPDATE ".DB::table('pension')." SET plat_amount=plat_amount+".$bespoke['bespoke_amount'].", pool_amount=pool_amount+".$bespoke['bespoke_amount']." WHERE pension_id='".$bespoke['pension_id']."'");
			//养老金收益
			$setting['first_oldage_rate'] = floatval($setting['first_oldage_rate']);
			if($setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($bespoke['bespoke_amount']*$setting['first_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'consume',
					'oldage_type' => 1,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']+$oldage_price,
					'oldage_desc' => '消费了'.$bespoke['bespoke_amount'].'元，获得'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
			}			
			//机构销售统计
			DB::query("UPDATE ".DB::table('pension')." SET pension_salenum=pension_salenum+1 WHERE pension_id='".$bespoke['pension_id']."'");
			$date = date('Ymd');
			$pension_stat = DB::fetch_first("SELECT * FROM ".DB::table('pension_stat')." WHERE pension_id='".$bespoke['pension_id']."' AND date='$date'");
			if(empty($pension_stat)) {
				$pension_stat_array = array(
					'pension_id' => $bespoke['pension_id'],
					'date' => $date,
					'salenum' => 1,
				);
				DB::insert('pension_stat', $pension_stat_array);
			} else {
				$pension_stat_array = array(
					'salenum' => $pension_stat['salenum']+$bespoke['bed_number'],
				);
				DB::update('pension_stat', $pension_stat_array, array('pension_id'=>$bespoke['pension_id'], 'date'=>$date));
			}
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