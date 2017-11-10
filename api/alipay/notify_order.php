<?php
error_reporting(0);
define('InMall', true);
define('MALL_ROOT', substr(dirname(__FILE__), 0, -10));

require_once MALL_ROOT.'/config.ini.php';
require_once MALL_ROOT.'/system/function/common.php';
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
	$order_amount = $notifydata['price'];
	$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE out_sn='$out_sn'");
	if($order['order_state'] == 10) {
		if(floatval($order_amount) == floatval($order['order_amount'])) {
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
			$order_data = array();				
			$order_data['transaction_id'] = $transaction_id;
			$order_data['payment_name'] = '支付宝';
			$order_data['payment_code'] = 'alipay';
			$order_data['order_state'] = 20;
			$order_data['payment_time'] = time();
			DB::update('order', $order_data, array('order_id'=>$order['order_id']));
			$log_data = array();
			$log_data['order_id'] = $order['order_id'];
			$log_data['order_state'] = 20;
			$log_data['order_intro'] = '订单付款';
			$log_data['state_info'] = '支付方式：支付宝';
			$log_data['log_time'] = time();
			Db::insert('order_log', $log_data);
			//优惠券
			$time = time();
			$query = DB::query("SELECT * FROM ".DB::table('coupon_template')." WHERE store_id='".$order['store_id']."' AND coupon_rule_type='buy' AND coupon_rule_starttime<=$time AND coupon_rule_endtime>=$time ORDER BY coupon_rule_amount DESC");
			while($value = DB::fetch($query)) {
				if($value['coupon_t_total'] > $value['coupon_t_giveout'] && $value['coupon_rule_amount'] <= $order['order_amount']) {
					$coupon_template = $value;
					$coupon_t_id = $value['coupon_t_id'];
					break;
				}
			}
			if(!empty($coupon_t_id)) {
				if($coupon_template['coupon_t_period_type'] == 'duration') {
					$coupon_template['coupon_t_starttime'] = strtotime(date('Y-m-d'));
					$coupon_template['coupon_t_endtime'] = $coupon_template['coupon_t_starttime']+3600*24*($coupon_template['coupon_t_days']+1)-1;
				}
				$coupon_data = array(
					'coupon_sn' => makesn(3),
					'member_id' => $member['member_id'],
					'store_id' => $coupon_template['store_id'],
					'coupon_t_id' => $coupon_template['coupon_t_id'],
					'coupon_title' => $coupon_template['coupon_t_title'],
					'coupon_desc' => $coupon_template['coupon_t_desc'],
					'coupon_starttime' => $coupon_template['coupon_t_starttime'],
					'coupon_endtime' => $coupon_template['coupon_t_endtime'],
					'coupon_price_type' => $coupon_template['coupon_t_price_type'],
					'coupon_price' => $coupon_template['coupon_t_price'],
					'coupon_limit' => $coupon_template['coupon_t_limit'],
					'coupon_goods_id' => $coupon_template['coupon_t_goods_id'],
					'coupon_state' => 0,
					'coupon_addtime' => time(),
				);
				$coupon_id = DB::insert('coupon', $coupon_data, 1);
				if(!empty($coupon_id)) {
					DB::update('coupon_template', array('coupon_t_giveout'=>$coupon_template['coupon_t_giveout']+1), array('coupon_t_id'=>$coupon_template['coupon_t_id']));
				}
			}
			//红包
			$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='reward' ORDER BY red_t_amount DESC");
			while($value = DB::fetch($query)) {
				if($value['red_t_total'] > $value['red_t_giveout'] && $value['red_t_amount'] <= $order['order_amount']) {
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
			//商家收益统计
			$profit_data = array(
				'store_id' => $order['store_id'],
				'profit_stage' => 'order',
				'profit_type' => 1,
				'profit_amount' => $order['order_amount'],
				'profit_desc' => $order['member_phone'].'购买商品，订单单号：'.$order['order_sn'],
				'is_freeze' => 1,
				'order_id' => $order['order_id'],
				'order_sn' => $order['order_sn'],
				'add_time' => time(),
			);
			DB::insert('store_profit', $profit_data);
			DB::query("UPDATE ".DB::table('store')." SET plat_amount=plat_amount+".$order['order_amount'].", pool_amount=pool_amount+".$order['order_amount']." WHERE store_id='".$order['store_id']."'");
			//养老金收益
			$setting['first_oldage_rate'] = floatval($setting['first_oldage_rate']);
			if($setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($order['order_amount']*$setting['first_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'consume',					
					'oldage_type' => 1,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']+$oldage_price,
					'oldage_desc' => '消费了'.$order['order_amount'].'元，获得'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
			}
			//商品销售统计
			$date = date('Ymd');
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='".$order['order_id']."'");
			while($value = DB::fetch($query)) {
				$order_goods[] = $value;
			}
			foreach($order_goods as $key => $value) {
				$goods_stat = DB::fetch_first("SELECT * FROM ".DB::table('goods_stat')." WHERE goods_id='".$value['goods_id']."' AND date='$date'");
				if(empty($goods_stat)) {
					$goods_stat_array = array(
						'goods_id' => $value['goods_id'],
						'date' => $date,
						'salenum' => $value['goods_num'],
					);
					DB::insert('goods_stat', $goods_stat_array);
				} else {
					$goods_stat_array = array(
						'salenum' => $goods_stat['salenum']+$value['goods_num'],
					);
					DB::update('goods_stat', $goods_stat_array, array('goods_id'=>$goods['goods_id'], 'date'=>$date));
				}
			}
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