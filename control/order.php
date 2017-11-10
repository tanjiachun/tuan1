<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class orderControl extends BaseProfileControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=order";
		$wheresql = " WHERE member_id='$this->member_id'";
		$state = !in_array($_GET['state'],array('pending', 'payment', 'deliver', 'receive', 'finish', 'cancel')) ? '' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'pending') {
			$wheresql .= " AND order_state=10";
		} elseif($state == 'payment') {
			$wheresql .= " AND order_state=20";
		} elseif($state == 'deliver') {
			$wheresql .= " AND order_state=30";
		} elseif($state == 'receive') {
			$wheresql .= " AND order_state=40";
		} elseif($state == 'finish') {
			$wheresql .= " AND order_state=50";
		} elseif($state == 'cancel') {
			$wheresql .= " AND order_state=0";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= "&search_name=$search_name";
			$wheresql .= " AND order_sn like '%$search_name%'";	
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('order').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$order_ids[] = $value['order_id'];
			$order_list[] = $value;
		}
		if(!empty($order_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order_address')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_address[$value['order_id']] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_goods[$value['order_id']][] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('order'));
	}

	public function viewOp() {	
		$order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
		if(empty($order) || $order['member_id'] != $this->member_id) {
			$this->showmessage('订单不存在', 'index.php?act=order', 'error');
		}
		$order['invoice_content'] = empty($order['invoice_content']) ? array() : unserialize($order['invoice_content']);
		$order_address = DB::fetch_first("SELECT * FROM ".DB::table('order_address')." WHERE order_id='$order_id'");
		$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
		while($value = DB::fetch($query)) {
			$goods = empty($goods) ? $value : $goods;
			$value['spec_info'] = !empty($value['spec_info']) ? explode(' ', $value['spec_info']) : '';
			$order_goods[] = $value;
		}
		if(!empty($order['express_code']) && !empty($order['shipping_code'])) {
			$url = 'http://www.kuaidi100.com/query?type='.$order['express_code'].'&postid='.$order['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
			$content = geturlfile($url);
			$delivery = json_decode($content, true);
			$delivery_data = array_reverse($delivery['data']);
		}
		$query = DB::query("SELECT * FROM ".DB::table('order_log')." WHERE order_id='$order_id' ORDER BY log_time ASC");
		while($value = DB::fetch($query)) {
			$order_log[] = $value;
		}
		$used_coupon = DB::fetch_first("SELECT * FROM ".DB::table('coupon')." WHERE coupon_orderid='$order_id' AND coupon_state=1");
		$used_red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=2 AND use_id='$order_id' AND red_state=1");
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('order_view'));
	}
	
	public function physicalOp() {
		$order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
		$order['invoice_content'] = empty($order['invoice_content']) ? array() : unserialize($order['invoice_content']);
		if(empty($order) || $order['member_id'] != $this->member_id) {
			$this->showmessage('订单不存在', 'index.php?act=order', 'error');
		}
		$order_address = DB::fetch_first("SELECT * FROM ".DB::table('order_address')." WHERE order_id='$order_id'");
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
		$used_coupon = DB::fetch_first("SELECT * FROM ".DB::table('coupon')." WHERE coupon_orderid='$order_id' AND coupon_state=1");
		$used_red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=2 AND use_id='$order_id' AND red_state=1");
		if(!empty($order['express_code']) && !empty($order['shipping_code'])) {
			$url = 'http://www.kuaidi100.com/query?type='.$order['express_code'].'&postid='.$order['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
			$content = geturlfile($url);
			$delivery = json_decode($content, true);
			$delivery_data = array_reverse($delivery['data']);
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('order_physical'));
	}
	
	public function cancelOp() {
		if(submitcheck()) {
			$cancel_id = empty($_POST['cancel_id']) ? 0 : intval($_POST['cancel_id']);
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$cancel_id'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}			
			if($order['order_state'] != '10') {
				exit(json_encode(array('msg'=>'订单不能取消')));	
			}
			DB::update('order', array('order_state'=>0), array('order_id'=>$cancel_id));
			$log_data = array();
			$log_data['order_id'] = $cancel_id;
			$log_data['order_state'] = 0;
			$log_data['order_intro'] = '订单取消';	
			$log_data['log_time'] = time();
			DB::insert('order_log', $log_data);
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$cancel_id'");
			while($value = DB::fetch($query)) {
				DB::query("UPDATE ".DB::table('goods')." SET goods_storage=goods_storage+".$value['goods_num'].", goods_salenum=goods_salenum-".$value['goods_num']." WHERE goods_id='".$value['goods_id']."'");
				DB::query("UPDATE ".DB::table('goods_spec')." SET spec_goods_storage=spec_goods_storage+".$value['goods_num'].", spec_salenum=spec_salenum-".$value['goods_num']." WHERE spec_id='".$value['spec_id']."'");
			}
			$coupon = DB::fetch_first("SELECT * FROM ".DB::table('coupon')." WHERE coupon_orderid='$cancel_id' AND coupon_state=1");
			if(!empty($coupon)) {
				DB::query("UPDATE ".DB::table('coupon')." SET coupon_orderid=0, coupon_state=0 WHERE coupon_id='".$coupon['coupon_id']."'");
				DB::query("UPDATE ".DB::table('coupon_template')." SET coupon_t_used=coupon_t_used-1 WHERE coupon_t_id='".$coupon['coupon_t_id']."'");
			}
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=2 AND use_id='$cancel_id' AND red_state=1");
			if(!empty($red)) {
				DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function paymentOp() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}
			$order_sn = empty($_POST['order_sn']) ? '' : $_POST['order_sn'];
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_sn='$order_sn'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'该订单不存在')));
			}
			if($order['order_state'] != '10') {
				exit(json_encode(array('msg'=>'该订单还不能支付')));
			}
			$payment_code = !in_array($_POST['payment_code'], array('alipay', 'weixin', 'predeposit')) ? 'alipay' : $_POST['payment_code'];
			if($payment_code == 'predeposit') {
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
				$data = array(
					'pdl_memberid' => $member['member_id'],
					'pdl_memberphone' => $member['member_phone'],
					'pdl_stage' => 'order',
					'pdl_type' => 0,
					'pdl_price' => $order['order_amount'],
					'pdl_predeposit' => $member['available_predeposit']-$order['order_amount'],
					'pdl_desc' => '购买商品，订单单号: '.$order['order_sn'],
					'pdl_addtime' => time(),
				);
				DB::insert('pd_log', $data);
				DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit-".$order['order_amount']." WHERE member_id='".$member['member_id']."'");
				$order_data = array();
				$order_data['order_state'] = 20;
				$order_data['payment_name'] = '余额支付';
				$order_data['payment_code'] = 'predeposit';
				$order_data['payment_time'] = time();
				DB::update('order', $order_data, array('order_id'=>$order['order_id']));
				$log_data = array();
				$log_data['order_id'] = $order['order_id'];
				$log_data['order_state'] = 20;
				$log_data['order_intro'] = '订单付款';
				$log_data['state_info'] = '支付方式：余额支付';
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
				$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
				if($this->setting['first_oldage_rate'] > 0)	{
					$oldage_price = priceformat($order['order_amount']*$this->setting['first_oldage_rate']);
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
			} else {
				DB::update('order', array('payment_code'=>$payment_code), array('order_id'=>$order['order_id']));
			}
			exit(json_encode(array('done'=>'true', 'order_sn'=>$order_sn)));
		} else {
			$order_sn = empty($_GET['order_sn']) ? '' : $_GET['order_sn'];
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_sn='$order_sn'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				$this->showmessage('该订单不存在', 'index.php?act=order', 'error');
			}
			if($order['order_state'] != '10') {
				$this->showmessage('该订单还不能支付', 'index.php?act=order', 'info');
			}
			$order_address = DB::fetch_first("SELECT * FROM ".DB::table('order_address')." WHERE order_id='".$order['order_id']."'");
			$curmodule = 'home';
			$bodyclass = '';
			include(template('order_payment'));
		}
	}
	
	public function alipayOp() {
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
					$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
					if($this->setting['first_oldage_rate'] > 0)	{
						$oldage_price = priceformat($order['order_amount']*$this->setting['first_oldage_rate']);
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
					$this->showmessage('订单付款成功', 'index.php?act=order');
				} else {
					$this->showmessage('订单付款失败', 'index.php?act=order', 'error');
				}
			} else {
				$this->showmessage('订单付款成功', 'index.php?act=order');
			}
		} else {
			$this->showmessage('订单付款失败', 'index.php?act=order', 'error');
		}
	}
	
	public function weixinOp() {
		$order_sn = empty($_GET['order_sn']) ? '' : $_GET['order_sn'];
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_sn='$order_sn'");
		if($order['order_state'] == '20') {
			$this->showmessage('订单付款成功', 'index.php?act=order');
		} else {
			$this->showmessage('订单付款失败', 'index.php?act=order', 'error');
		}
	}
	
	public function checkstateOp() {
		$order_sn = empty($_GET['order_sn']) ? '' : $_GET['order_sn'];
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_sn='$order_sn'");
		if($order['order_state'] == 20) {
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('done'=>'false')));
		}
	}
	
	public function refundOp() {
		if(submitcheck()) {
			$refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
			$refund_reason = empty($_POST['refund_reason']) ? '' : $_POST['refund_reason'];
			$refund_message = empty($_POST['refund_message']) ? '' : $_POST['refund_message'];
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$refund_id'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}			
			if($order['order_state'] != '20') {
				exit(json_encode(array('msg'=>'订单不能退款')));	
			}
			if(empty($refund_message)) {
				exit(json_encode(array('msg'=>'请选择退款原因')));
			}
			DB::update('order', array('refund_reason'=>$refund_reason, 'refund_message'=>$refund_message, 'refund_state'=>1), array('order_id'=>$refund_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}	
	}
	
	public function returnOp() {
		if(submitcheck()) {
			$order_id = empty($_POST['order_id']) ? 0 : intval($_POST['order_id']);
			$return_type = empty($_POST['return_type']) ? array() : $_POST['return_type'];
			$return_goodsnum = empty($_POST['return_goodsnum']) ? array() : $_POST['return_goodsnum'];
			$return_content = empty($_POST['return_content']) ? array() : $_POST['return_content'];
			$return_image = empty($_POST['return_image']) ? array() : $_POST['return_image'];
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}
			if($order['order_state'] != '30') {
				exit(json_encode(array('msg'=>'订单不能退换货')));	
			}
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
			while($value = DB::fetch($query)) {
				if(in_array($return_type[$value['rec_id']], array('return', 'exchange'))) {
					$return_goodsnum[$value['rec_id']] = intval($return_goodsnum[$value['rec_id']]);
					$max_stock = $value['goods_num']-$value['goods_returnnum'];
					if($max_stock > 0 && $return_goodsnum[$value['rec_id']] <= $max_stock) {
						$order_goods[] = $value;
					}
				}
			}
			if(empty($order_goods)) {
				exit(json_encode(array('msg'=>'请至少选择一个要退换货的商品')));
			}	
			foreach($order_goods as $key => $value) {
				$return_data = array(
					'return_sn' => makesn(7),
					'order_id' => $order['order_id'],
					'store_id' => $order['store_id'],
					'member_id' => $order['member_id'],
					'member_phone' => $order['member_phone'],
					'return_type' => $return_type[$value['rec_id']],
					'return_content' => $return_content[$value['rec_id']],							
					'return_image' => empty($return_image[$value['rec_id']]) ? '' : serialize($return_image[$value['rec_id']]),
					'return_state' => 3,
					'return_addtime' => time(),
				);
				$return_id = DB::insert('order_return', $return_data, 1);
				if(!empty($return_id)) {
					$return_goods_data = array(
						'return_id' => $return_id,
						'rec_id' => $value['rec_id'],
						'goods_id' => $value['goods_id'],
						'goods_name' => $value['goods_name'],
						'spec_id' => $value['spec_id'],
						'spec_info' => $value['spec_info'],
						'goods_image' => $value['goods_image'],
						'goods_price' => $value['goods_price'],
						'goods_returnnum' => $return_goodsnum[$value['rec_id']],
					);
					DB::insert('order_return_goods', $return_goods_data);
					$goods_return_state = $value['goods_returnnum']+$return_goodsnum[$value['rec_id']] < $value['goods_num'] ? 1 : 2;
					DB::query("UPDATE ".DB::table('order_goods')." SET goods_returnnum=goods_returnnum+".$return_goodsnum[$value['rec_id']].", goods_return_state=$goods_return_state WHERE rec_id='".$value['rec_id']."'");
				}
			}
			DB::update('order', array('return_state'=>1), array('order_id'=>$order['order_id']));
			exit(json_encode(array('done'=>'true')));
		} else {
			$order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
			while($value = DB::fetch($query)) {
				if($value['goods_num'] > $value['goods_returnnum']) {
					$order_goods[] = $value;
				}
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('order_return'));	
		}
	}
	
	public function receiveOp() {
		if(submitcheck()) {
			$receive_id = empty($_POST['receive_id']) ? 0 : intval($_POST['receive_id']);
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$receive_id'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}			
			if($order['order_state'] != '30') {
				exit(json_encode(array('msg'=>'订单不能收货')));	
			}
			DB::update('order', array('order_state'=>40), array('order_id'=>$receive_id));
			$log_data = array();
			$log_data['order_id'] = $receive_id;
			$log_data['order_state'] = 40;
			$log_data['order_intro'] = '订单收货';	
			$log_data['log_time'] = time();
			DB::insert('order_log', $log_data);
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$receive_id'");
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function commentOp() {
		if(submitcheck()) {
			$order_id = empty($_POST['order_id']) ? 0 : intval($_POST['order_id']);
			$comment_level = empty($_POST['comment_level']) ? array() : $_POST['comment_level'];
			$comment_score = empty($_POST['comment_score']) ? array() : $_POST['comment_score'];
			$comment_content = empty($_POST['comment_content']) ? array() : $_POST['comment_content'];
			$comment_image = empty($_POST['comment_image']) ? array() : $_POST['comment_image'];
			$star_array = array('1', '2', '3', '4', '5');
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}
			if($order['order_state'] != '40') {
				exit(json_encode(array('msg'=>'订单不能评价')));	
			}
			if(!empty($order['comment_state'])) {
				exit(json_encode(array('msg'=>'您已经评价过了')));	
			}
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
			while($value = DB::fetch($query)) {
				if(!empty($comment_content[$value['rec_id']]) || !empty($comment_image[$value['rec_id']])) {
					$order_goods[] = $value;	
				}
			}
			if(empty($order_goods)) {
				exit(json_encode(array('msg'=>'请至少写点你的感受')));
			}
			foreach($order_goods as $key => $value) {
				$comment_level[$value['rec_id']] = !in_array($comment_level[$value['rec_id']], array('good', 'middle', 'bad')) ? 'good' : $comment_level[$value['rec_id']];
				$comment_score[$value['rec_id']] = !in_array($comment_score[$value['rec_id']], $star_array) ? 1 : $comment_score[$value['rec_id']];
				$data = array(
					'goods_id' => $value['goods_id'],
					'member_id' => $order['member_id'],
					'spec_id' => $value['spec_id'],
					'order_id' => $order['order_id'],
					'comment_level' => $comment_level[$value['rec_id']],
					'comment_score' => $comment_score[$value['rec_id']],
					'comment_image' => empty($comment_image[$value['rec_id']]) ? '' : serialize($comment_image[$value['rec_id']]),
					'comment_content' => $comment_content[$value['rec_id']],
					'comment_time' => time(),
				);
				$comment_id = DB::insert('goods_comment', $data , 1);
				if(!empty($comment_id)) {
					DB::query("UPDATE ".DB::table('goods')." SET goods_score=goods_score+".$comment_score[$value['rec_id']].", goods_commentnum=goods_commentnum+1 WHERE goods_id='".$value['goods_id']."'");
				}
			}
			DB::update('order', array('comment_state'=>1, 'comment_time'=>time()), array('order_id'=>$order['order_id']));
			//养老金收益
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
			$this->setting['second_oldage_rate'] = floatval($this->setting['second_oldage_rate']);
			if($this->setting['second_oldage_rate'] > 0) {
				$oldage_price = priceformat($this->setting['second_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'comment',					
					'oldage_type' => 1,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']+$oldage_price,
					'oldage_desc' => '您评价了商品订单'.$order['order_sn'].'，获得'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			$order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
			while($value = DB::fetch($query)) {
				$order_goods[] = $value;
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('order_comment'));
		}
	}

	public function bookOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=order&op=book";
		$wheresql = " WHERE member_id='$this->member_id'";
		$state = !in_array($_GET['state'],array('pending', 'payment', 'finish', 'cancel')) ? '' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'pending') {
			$wheresql .= " AND book_state=10";
		} elseif($state == 'payment') {
			$wheresql .= " AND book_state=20";
		} elseif($state == 'finish') {
			$wheresql .= " AND book_state=30";
		} elseif($state == 'cancel') {
			$wheresql .= " AND book_state=0";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= "&search_name=".urlencode($search_name);
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_name like '%".$search_name."%' OR member_phone like '%".$search_name."%'");
			while($value = DB::fetch($query)) {
				$nurse_ids[] = $value['nurse_id'];
			}
			if(!empty($nurse_ids)) {
				$wheresql .= " AND (book_sn like '%".$search_name."%' OR nurse_id in ('".implode("','", $nurse_ids)."'))";
			} else {
				$wheresql .= " AND book_sn like '%".$search_name."%'";
			}	
		}
		$nurse_ids = array();
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$nurse_ids[] = $value['nurse_id'];
			$value['book_message'] = nl2br($value['book_message']);
			$value['book_service'] = empty($value['book_service']) ? array() : unserialize($value['book_service']);
			$value['invoice_content'] = empty($value['invoice_content']) ? array() : unserialize($value['invoice_content']);
			$book_service = array();
			foreach($value['book_service'] as $subkey => $subvalue) {
				$book_service[] = $subvalue['service_name'];
			}
			$value['book_service'] = empty($book_service) ? '' : implode(' ', $book_service);
			$book_list[] = $value;
		}
		if(!empty($nurse_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
			while($value = DB::fetch($query)) {
				$nurse_list[$value['nurse_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('order_book'));
	}
	function book_cancelOp() {
		if(submitcheck()) {
			$cancel_id = empty($_POST['cancel_id']) ? 0 : intval($_POST['cancel_id']);
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$cancel_id'");
			if(empty($book) || $book['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预约单不存在')));
			}	
			if($book['book_state'] != '10') {
				exit(json_encode(array('msg'=>'预约单不能取消')));	
			}
			DB::update('nurse_book', array('book_state'=>0), array('book_id'=>$cancel_id));
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=1 AND use_id='$cancel_id' AND red_state=1");
			if(!empty($red)) {
				DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
			}
			//$nurse_id=DB::fetch_first("SELECT nurse_id FROM".DB::table('nurse_book')."WHERE book_id=$cancel_id");
			DB::query("DELETE FROM ".DB::table('nurse_book')." WHERE book_id='$cancel_id'");
			DB::query("DELETE FROM ".DB::table('message')." WHERE from_id=$cancel_id");
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	function book_refundOp() {
		if(submitcheck()) {
			$refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
			$refund_reason = empty($_POST['refund_reason']) ? '' : $_POST['refund_reason'];
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$refund_id'");
			if(empty($book) || $book['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预约单不存在')));
			}	
			if($book['book_state'] != '20') {
				exit(json_encode(array('msg'=>'预约单不能退款')));	
			}
			if(time() >= $book['work_time']+604800) {
				exit(json_encode(array('msg'=>'预约单不能退款')));	
			}
			if(empty($refund_reason)) {
				exit(json_encode(array('msg'=>'请输入退款原因')));	
			}
			$book_data = array();
			$book_data['refund_state'] = 1;				
			$book_data['refund_amount'] = $book['book_amount'];
			$book_data['refund_reason'] = $refund_reason;
			DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}	
	}
	
	function book_finishOp() {
		if(submitcheck()) {
			$finish_id = empty($_POST['finish_id']) ? 0 : intval($_POST['finish_id']);
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$finish_id'");
			if(empty($book) || $book['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预约单不存在')));
			}	
			if($book['book_state'] != '20') {
				exit(json_encode(array('msg'=>'预约单不能完成')));	
			}
			DB::update('nurse_book', array('book_state'=>30, 'finish_time'=>time()), array('book_id'=>$finish_id));
			DB::query("UPDATE ".DB::table('nurse')." SET work_state=0 WHERE nurse_id='".$book['nurse_id']."'");
			$profit = DB::fetch_first("SELECT * FROM ".DB::table('nurse_profit')." WHERE book_id='".$book['book_id']."'");
			if(!empty($profit)) {
				$profit_data = array(
					'is_freeze' => 0,
					'add_time' => time(),
				);
				DB::update('nurse_profit', $profit_data, array('profit_id'=>$profit['profit_id']));
				DB::query("UPDATE ".DB::table('nurse')." SET pool_amount=pool_amount-".$profit['profit_amount'].", available_amount=available_amount+".$profit['profit_amount']." WHERE nurse_id='".$book['nurse_id']."'");	
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function book_commentOp() {
		if(submitcheck()) {
			$book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
			$star_array = array('1', '2', '3', '4', '5');
			$comment_level = !in_array($_POST['comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['comment_level'];			
			$comment_honest_star = !in_array($_POST['comment_honest_star'], $star_array) ? 1 : intval($_POST['comment_honest_star']);
			$comment_love_star = !in_array($_POST['comment_love_star'], $star_array) ? 1 : intval($_POST['comment_love_star']);
			$comment_content = empty($_POST['comment_content']) ? '' : $_POST['comment_content'];
			$comment_image = empty($_POST['comment_image']) ? array() : $_POST['comment_image'];
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
			if(empty($book) || $book['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预约单不存在')));
			}
			if($book['book_state'] != '30') {
				exit(json_encode(array('msg'=>'预约单不能评价')));	
			}
			if(!empty($book['comment_state'])) {
				exit(json_encode(array('msg'=>'您已经评价过了')));	
			}
			if(empty($comment_level)) {
				exit(json_encode(array('msg'=>'请选择满意度评分')));
			}
			if(empty($comment_content)) {
				exit(json_encode(array('msg'=>'请至少写点你的服务感受')));
			}
			$query = DB::query("SELECT * FROM ".DB::table('nurse_tag'));
			while($value = DB::fetch($query)) {
				$tag_list[$value['tag_id']] = $value;
			}
			$comment_tags = array();
			$comment_score = $comment_honest_star+$comment_love_star;
			if($comment_level=='good'){
			    $comment_score+=10;
            }elseif ($comment_level=='middle'){
			    $comment_score-=2;
            }elseif ($comment_level=='bad'){
                $comment_score-=10;
            }
            $comment_add_score=$comment_score*intval($book['work_duration_days']);
//			foreach($tag_ids as $key => $value) {
//				if(!empty($tag_list[$value])) {
//					$comment_tags[] = $tag_list[$value]['tag_name'];
//					$comment_score += $tag_list[$value]['tag_score'];
//				}
//			}
			$data = array(
				'nurse_id' => $book['nurse_id'],
				'member_id' => $book['member_id'],
				'book_id' => $book['book_id'],
				'comment_level' => $comment_level,
				'comment_honest_star' => $comment_honest_star,
				'comment_love_star' => $comment_love_star,
				'comment_image' => empty($comment_image) ? '' : serialize($comment_image),
				'comment_content' => $comment_content,
				'comment_time' => time(),
			);
			$comment_id = DB::insert('nurse_comment', $data , 1);
			if(!empty($comment_id)) {
				DB::update('nurse_book', array('comment_state'=>1, 'comment_time'=>time()), array('book_id'=>$book['book_id']));
                $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
                $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
				$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
				//家政人员积分收益
			    $nurse_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('nurse_score')." WHERE nurse_id='".$book['nurse_id']."' AND get_type='member_comment' AND get_time=$now_date");
				if($nurse_get_score+$comment_add_score>=1600){
				    $comment_add_score=1600-$nurse_get_score;
                }
			    $nurse_score = $nurse['nurse_score']+$comment_add_score;
				$query = DB::query("SELECT * FROM ".DB::table('nurse_grade')." ORDER BY nurse_score DESC");
				while($value = DB::fetch($query)) {
					if($value['nurse_score'] < $nurse_score) {
						$grade_id = $value['grade_id'];
						break;
					}
				}
				$nurse_score_data=array(
				    'nurse_id'=>$book['nurse_id'],
                    'book_id'=>$book_id,
                    'score_count'=>$comment_add_score,
                    'get_type'=>'member_comment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('nurse_score', $nurse_score_data);
				$complaint_state = 0;
				if($comment_level == 'bad') {
					$complaint_state = 1;
				}
				DB::query("UPDATE ".DB::table('nurse')." SET grade_id=$grade_id, nurse_score=nurse_score+$comment_add_score, nurse_commentnum=nurse_commentnum+1, complaint_state=$complaint_state WHERE nurse_id='".$book['nurse_id']."'");
				//机构积分收益
                if(!empty($book['agent_id'])){
                    $agent_nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='".$book['agent_id']."'");
                    $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$book['agent_id']."'");
                    $agent_add_score=ceil($comment_add_score/intval($agent_nurse_count));
                    if($agent_add_score<1){
                        $agent_add_score=1;
                    }
                    $agent_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('agent_score')." WHERE agent_id='".$book['agent_id']."' AND get_type='member_comment' AND get_time=$now_date");
                    if($agent_get_score+$agent_add_score>=1000){
                        $agent_add_score=1000-$agent_get_score;
                    }
                    $agent_score=$agent['agent_score']+$agent_add_score;
                    $query = DB::query("SELECT * FROM ".DB::table('agent_grade')." ORDER BY agent_score DESC");
                    while($value = DB::fetch($query)) {
                        if($value['agent_score'] < $agent_score) {
                            $agent_grade_id = $value['grade_id'];
                            break;
                        }
                    }
                    $agent_score_data=array(
                        'agent_id'=>$book['agent_id'],
                        'book_id'=>$book_id,
                        'score_count'=>$agent_add_score,
                        'get_type'=>'member_comment',
                        'true_time'=>time(),
                        'get_time'=>$now_date
                    );
                    DB::insert('agent_score', $agent_score_data);
                    DB::query("UPDATE ".DB::table('agent')." SET grade_id=$agent_grade_id, agent_score=agent_score+$agent_add_score WHERE agent_id='".$book['agent_id']."'");
                }
				//雇主积分收益
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
				$member_add_score=0;
                if(empty($comment_image)){
                    $member_add_score+=30;
                }else{
                    $member_add_score+=80;
                }
                $member_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('member_score')." WHERE member_id='".$book['member_id']."' AND get_type='member_comment' AND get_time=$now_date");
                if($member_get_score+$member_add_score>=400){
                    $member_add_score=400-$member_get_score;
                }
                $member_score_data=array(
                    'member_id'=>$book['member_id'],
                    'book_id'=>$book_id,
                    'agent_id'=>$book['agent_id'],
                    'score_count'=>$member_add_score,
                    'get_type'=>'member_comment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_score', $member_score_data);
                $member_score=$member['member_score']+$member_add_score;
                $grade = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member_score." ORDER BY card_predeposit DESC");
                $member_data=array(
                    'member_score'=>intval($member_score),
                    'grade_id'=>intval($grade['card_id'])
                );
                DB::update('member', $member_data, array('member_id'=>$book['member_id']));
                //雇主团豆豆收益
                $member_add_coin=0;
                if(empty($comment_image)){
                    $member_add_coin+=20;
                }else{
                    $member_add_coin+=50;
                }
                $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='member_comment' AND get_time=$now_date");
                if($member_get_coin+$member_add_coin>=200){
                    $member_add_coin=200-$member_get_coin;
                }
                $member_coin_data=array(
                    'member_id'=>$book['member_id'],
                    'book_id'=>$book_id,
                    'coin_count'=>$member_add_coin,
                    'get_type'=>'member_comment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin', $member_coin_data);
                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
                //升级送团豆豆
                if(intval($grade['card_id'])>intval($member['grade_id'])){
                    if(intval($grade['card_id'])>intval($member['large_grade_id'])){
                        DB::query("UPDATE ".DB::table('member')." SET large_grade_id=$grade_id WHERE member_id='".$book['member_id']."'");
                        DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+188 WHERE member_id='".$book['member_id']."'");
                        $member_coin_level_data=array(
                            'member_id'=>$book['member_id'],
                            'book_id'=>$book_id,
                            'coin_count'=>188,
                            'get_type'=>'level_up',
                            'true_time'=>time(),
                            'get_time'=>$now_date
                        );
                        DB::insert('member_coin', $member_coin_level_data);
                    }
                }
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			$book_id = empty($_GET['book_id']) ? 0 : intval($_GET['book_id']);
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
			$query = DB::query("SELECT * FROM ".DB::table('nurse_tag'));
			while($value = DB::fetch($query)) {
				$tag_list[] = $value;
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('order_book_comment'));
		}
	}
	
	public function bespokeOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=order&op=bespoke";
		$wheresql = " WHERE member_id='$this->member_id'";
		$state = !in_array($_GET['state'],array('pending', 'payment', 'finish', 'cancel')) ? '' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'pending') {
			$wheresql .= " AND bespoke_state=10";
		} elseif($state == 'payment') {
			$wheresql .= " AND bespoke_state=20";
		} elseif($state == 'finish') {
			$wheresql .= " AND bespoke_state=30";
		} elseif($state == 'cancel') {
			$wheresql .= " AND bespoke_state=0";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= "&search_name=".urlencode($search_name);
			$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_name like '%".$search_name."%' OR pension_phone like '%".$search_name."%'");
			while($value = DB::fetch($query)) {
				$pension_ids[] = $value['pension_id'];
			}
			if(!empty($pension_ids)) {
				$wheresql .= " AND (bespoke_sn like '%".$search_name."%' OR pension_id in ('".implode("','", $pension_ids)."'))";
			} else {
				$wheresql .= " AND bespoke_sn like '%".$search_name."%'";
			}	
		}
		$pension_ids = array();
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_bespoke').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension_bespoke').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$pension_ids[] = $value['pension_id'];
			$room_ids[] = $value['room_id'];
			$value['bespoke_message'] = empty($value['bespoke_message']) ? array() : unserialize($value['bespoke_message']);
			$bespoke_message = array();
			foreach($value['bespoke_message'] as $subkey => $subvalue) {
				$bespoke_message[] = $subvalue['name'].' '.$subvalue['cardid'];
			}
			$value['bespoke_message'] = empty($bespoke_message) ? '' : implode(',', $bespoke_message);
			$live_time = strtotime(date('Y-m-d', $value['live_time']))-3600*24;
			$value['validity_period'] = strtotime(date('Y-m-d', $live_time).' 18:00');
			$bespoke_list[] = $value;
		}
		if(!empty($pension_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $pension_ids)."')");
			while($value = DB::fetch($query)) {
				$pension_list[$value['pension_id']] = $value;
			}
		}
		if(!empty($room_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('pension_room')." WHERE room_id in ('".implode("','", $room_ids)."')");
			while($value = DB::fetch($query)) {
				$room_list[$value['room_id']] = $value;
			}
		}
		$person_array = array('1'=>'自理', '2'=>'半自理', '3'=>'全自理', '4'=>'特护');
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('order_bespoke'));	
	}
	
	function bespoke_cancelOp() {
		if(submitcheck()) {
			$cancel_id = empty($_POST['cancel_id']) ? 0 : intval($_POST['cancel_id']);
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_id='$cancel_id'");
			if(empty($bespoke) || $bespoke['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预定单不存在')));
			}	
			if($bespoke['bespoke_state'] != '10') {
				exit(json_encode(array('msg'=>'预定单不能取消')));	
			}
			DB::update('pension_bespoke', array('bespoke_state'=>0), array('bespoke_id'=>$cancel_id));
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=3 AND use_id='$cancel_id' AND red_state=1");
			if(!empty($red)) {
				DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	function bespoke_refundOp() {
		if(submitcheck()) {
			$refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
			$refund_reason = empty($_POST['refund_reason']) ? '' : $_POST['refund_reason'];
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_id='$refund_id'");
			$live_time = strtotime(date('Y-m-d', $bespoke['live_time']))-3600*24;
			$validity_period = strtotime(date('Y-m-d', $live_time).' 18:00');
			if(empty($bespoke) || $bespoke['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预定单不存在')));
			}	
			if($bespoke['bespoke_state'] != '20') {
				exit(json_encode(array('msg'=>'预定单不能退款')));	
			}
			if(time() >= $validity_period) {
				exit(json_encode(array('msg'=>'预定单不能退款')));	
			}
			if(empty($refund_reason)) {
				exit(json_encode(array('msg'=>'请输入退款原因')));	
			}
			$bespoke_data = array();
			$bespoke_data['refund_state'] = 1;				
			$bespoke_data['refund_amount'] = $bespoke['bespoke_amount'];
			$bespoke_data['refund_reason'] = $refund_reason;
			DB::update('pension_bespoke', $bespoke_data, array('bespoke_id'=>$bespoke['bespoke_id']));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}	
	}
	
	function bespoke_finishOp() {
		if(submitcheck()) {
			$finish_id = empty($_POST['finish_id']) ? 0 : intval($_POST['finish_id']);
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_id='$finish_id'");
			if(empty($bespoke) || $bespoke['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预定单不存在')));
			}	
			if($bespoke['bespoke_state'] != '20') {
				exit(json_encode(array('msg'=>'预定单不能入驻')));	
			}
			DB::update('pension_bespoke', array('bespoke_state'=>30, 'finish_time'=>time()), array('bespoke_id'=>$finish_id));
			$profit = DB::fetch_first("SELECT * FROM ".DB::table('pension_profit')." WHERE bespoke_id='".$bespoke['bespoke_id']."'");
			if(!empty($profit)) {
				$profit_data = array(
					'is_freeze' => 0,
					'add_time' => time(),
				);
				DB::update('pension_profit', $profit_data, array('profit_id'=>$profit['profit_id']));
				DB::query("UPDATE ".DB::table('pension')." SET pool_amount=pool_amount-".$profit['profit_amount'].", available_amount=available_amount+".$profit['profit_amount']." WHERE pension_id='".$bespoke['pension_id']."'");	
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function bespoke_commentOp() {
		if(submitcheck()) {
			$bespoke_id = empty($_POST['bespoke_id']) ? 0 : intval($_POST['bespoke_id']);
			$star_array = array('1', '2', '3', '4', '5');
			$comment_level = !in_array($_POST['comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['comment_level'];
			$comment_score = !in_array($_POST['comment_score'], $star_array) ? 1 : intval($_POST['comment_score']);
			$comment_content = empty($_POST['comment_content']) ? '' : $_POST['comment_content'];
			$comment_image = empty($_POST['comment_image']) ? array() : $_POST['comment_image'];
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_id='$bespoke_id'");
			if(empty($bespoke) || $bespoke['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预定单不存在')));
			}
			if($bespoke['bespoke_state'] != '30') {
				exit(json_encode(array('msg'=>'预定单不能评价')));	
			}
			if(!empty($bespoke['comment_state'])) {
				exit(json_encode(array('msg'=>'您已经评价过了')));	
			}
			if(empty($comment_level)) {
				exit(json_encode(array('msg'=>'请选择满意度评分')));
			}
			if(empty($comment_content)) {
				exit(json_encode(array('msg'=>'请至少写点你的感受')));
			}
			$data = array(
				'pension_id' => $bespoke['pension_id'],
				'member_id' => $bespoke['member_id'],
				'room_id' => $bespoke['room_id'],
				'bespoke_id' => $bespoke['bespoke_id'],
				'comment_level' => $comment_level,
				'comment_score' => $comment_score,
				'comment_image' => empty($comment_image) ? '' : serialize($comment_image),
				'comment_content' => $comment_content,
				'comment_time' => time(),
			);
			$comment_id = DB::insert('pension_comment', $data, 1);
			if(!empty($comment_id)) {
				DB::update('pension_bespoke', array('comment_state'=>1, 'comment_time'=>time()), array('bespoke_id'=>$bespoke['bespoke_id']));
				DB::query("UPDATE ".DB::table('pension')." SET pension_score=pension_score+".$comment_score.", pension_commentnum=pension_commentnum+1 WHERE pension_id='".$bespoke['pension_id']."'");
				//养老金收益
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$bespoke['member_id']."'");
				$this->setting['second_oldage_rate'] = floatval($this->setting['second_oldage_rate']);
				if($this->setting['second_oldage_rate'] > 0) {
					$oldage_price = priceformat($this->setting['second_oldage_rate']);
					$oldage_data = array(
						'member_id' => $member['member_id'],
						'oldage_stage' => 'comment',					
						'oldage_type' => 1,
						'oldage_price' => $oldage_price,
						'oldage_balance' => $member['oldage_amount']+$oldage_price,
						'oldage_desc' => '您评价了养老机构'.$pension['pension_name'].'，获得'.$oldage_price.'元养老金',
						'oldage_addtime' => time(),
					);
					DB::insert('oldage', $oldage_data);
					DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
				}	
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			$bespoke_id = empty($_GET['bespoke_id']) ? 0 : intval($_GET['bespoke_id']);
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_id='$bespoke_id'");
			$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='".$bespoke['pension_id']."'");
			$room = DB::fetch_first("SELECT * FROM ".DB::table('pension_room')." WHERE room_id='".$bespoke['room_id']."'");
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('order_bespoke_comment'));
		}
	}
}

?>