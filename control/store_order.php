<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_orderControl extends BaseStoreControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_order";
		$wheresql = " WHERE store_id='$this->store_id'";
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
		$this->store['store_express'] = empty($this->store['store_express']) ? array() : unserialize($this->store['store_express']);
		$query = DB::query("SELECT * FROM ".DB::table('express')." ORDER BY express_order ASC");
		while($value = DB::fetch($query)) {
			if(empty($this->store['store_express'])) {
				$express_list[] = $value;
			} else {
				if(in_array($value['express_id'], $this->store['store_express'])) {
					$express_list[] = $value;
				}
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_order'));
	}
	
	public function viewOp() {	
		$order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
		if(empty($order) || $order['store_id'] != $this->store_id) {
			$this->showmessage('订单不存在', 'index.php?act=store_order', 'error');
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
		$this->store['store_express'] = empty($this->store['store_express']) ? array() : unserialize($this->store['store_express']);
		$query = DB::query("SELECT * FROM ".DB::table('express')." ORDER BY express_order ASC");
		while($value = DB::fetch($query)) {
			if(empty($this->store['store_express'])) {
				$express_list[] = $value;
			} else {
				if(in_array($value['express_id'], $this->store['store_express'])) {
					$express_list[] = $value;
				}
			}
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_order_view'));
	}
	
	public function addressOp() {
		if(submitcheck()) {
			$order_id = empty($_POST['order_id']) ? 0 : intval($_POST['order_id']);
			$true_name = empty($_POST['true_name']) ? '' : $_POST['true_name'];
			$mobile_phone = empty($_POST['mobile_phone']) ? '' : $_POST['mobile_phone'];
			$province_id = empty($_POST['province_id']) ? 0 : intval($_POST['province_id']);
			$city_id = empty($_POST['city_id']) ? 0 : intval($_POST['city_id']);
			$area_id = empty($_POST['area_id']) ? 0 : intval($_POST['area_id']);
			$address_info = empty($_POST['address_info']) ? '' : $_POST['address_info'];
			if(empty($true_name)) {
				exit(json_encode(array('msg'=>'请输入联系人')));
			}
			if(empty($mobile_phone)) {
				exit(json_encode(array('msg'=>'请输入电话')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $mobile_phone)) {
				exit(json_encode(array('msg'=>'电话格式不正确')));
			}
			if(empty($province_id) || empty($city_id)) {
				exit(json_encode(array('msg'=>'请选择所在地区')));
			}
			$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$city_id'");
			if(!empty($count) && empty($area_id)) {
				exit(json_encode(array('msg'=>'请选择所在地区')));
			}
			if(empty($address_info)) {
				exit(json_encode(array('msg'=>'请输入详细地址')));
			}
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
			if(empty($order) || $order['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$province_id'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$city_id'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$area_id'");
			$area_info = $province_name.$city_name.$area_name;
			$address_data = array(
				'true_name' => $true_name,
				'mobile_phone' => $mobile_phone,
				'province_id' => $province_id,
				'city_id' => $city_id,
				'area_id' => $area_id,
				'area_info' => $area_info,
				'address_info' => $address_info,
			);		
			DB::update('order_address', $address_data, array('order_id'=>$order_id));
			exit(json_encode(array('done'=>'true', 'true_name'=>$true_name, 'mobile_phone'=>$mobile_phone, 'address'=>$area_info.$address_info)));
		} else {
			$order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
			$order_address = DB::fetch_first("SELECT * FROM ".DB::table('order_address')." WHERE order_id='$order_id'");
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$order_address['province_id']."' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$city_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$order_address['city_id']."' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$area_list[] = $value;
			}
			include(template('store_order_address'));
		}
	}
	
	public function cancelOp() {
		if(submitcheck()) {
			$cancel_id = empty($_POST['cancel_id']) ? 0 : $_POST['cancel_id'];
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$cancel_id'");
			if(empty($order) || $order['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}			
			if($order['order_state'] != '10') {
				exit(json_encode(array('msg'=>'订单不能关闭')));	
			}
			DB::update('order', array('order_state'=>0), array('order_id'=>$cancel_id));
			$log_data = array();
			$log_data['order_id'] = $cancel_id;
			$log_data['order_state'] = '0';
			$log_data['order_intro'] = '订单关闭';	
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
	
	public function refundOp() {
		if(submitcheck()) {
			$refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
			$refund_amount = empty($_POST['refund_amount']) ? 0 : floatval($_POST['refund_amount']);
			$refund_state = !in_array($_POST['refund_state'], array('1', '2')) ? '' : $_POST['refund_state'];
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$refund_id'");
			if(empty($order) || $order['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}
			if($order['order_state'] != '20') {
				exit(json_encode(array('msg'=>'订单不能退款')));
			}
			if($order['refund_state'] != 1) {
				exit(json_encode(array('msg'=>'订单已经退款处理了')));
			}
			if($refund_amount <= 0) {
				exit(json_encode(array('msg'=>'请输入退款金额')));
			}
			if($refund_amount > $order['order_amount']) {
				exit(json_encode(array('msg'=>'退款金额不能大于订单金额')));
			}
			if(empty($refund_state)) {
				exit(json_encode(array('msg'=>'请选择处理方式')));
			}
			if($refund_state == 2) {
				$order_data = array();
				$order_data['refund_state'] = 2;
				$order_data['refund_time'] = time();
				DB::update('order', $order_data, array('order_id'=>$order['order_id']));
				exit(json_encode(array('done'=>'true')));
			}
			$refund_sn = date('Ymd').random(8, 1);
			if($order['payment_code'] == 'alipay') {
				require_once MALL_ROOT.'/api/alipay/alipay.php';
				$param = array();
				$param['batch_no'] = $refund_sn;
				$param['refund_date'] = date('Y-m-d H:i:s');
				$param['transaction_id'] = $order['transaction_id'];
				$param['refund_amount'] = $refund_amount;
				$requesturl = get_refundurl($param);
				$result = geturlfile($requesturl);
				$result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
				$is_success = $result->is_success;
				if($is_success == 'F') {
					exit(json_encode(array('msg' => '退款失败')));	
				}
			} elseif($order['payment_code'] == 'weixin') {
				require_once MALL_ROOT.'/api/weixin/weixin.php';
				$refundOrder = new Refund_pub();
				$refundOrder->setParameter("transaction_id", $order['transaction_id']);
				$refundOrder->setParameter("out_refund_no", $refund_sn);
				$refundOrder->setParameter("total_fee", $order['order_amount']*100);
				$refundOrder->setParameter("refund_fee", $refund_amount*100);
				$refundOrder->setParameter("op_user_id", MCHID);
				$result = $refundOrder->getResult();
				if($result['return_code'] == 'FAIL' || $result['result_code'] == 'FAIL') {
					exit(json_encode(array('msg' => '退款失败')));
				}
			} elseif($order['payment_code'] == 'predeposit') {
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
				$data = array(
					'pdl_memberid' => $member['member_id'],
					'pdl_memberphone' => $member['member_phone'],
					'pdl_stage' => 'order',
					'pdl_type' => 1,
					'pdl_price' => $refund_amount,
					'pdl_predeposit' => $member['available_predeposit']+$refund_amount,
					'pdl_desc' => '订单退款，订单单号: '.$order['order_sn'],
					'pdl_addtime' => time(),
				);
				DB::insert('pd_log', $data);
				DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+".$refund_amount." WHERE member_id='".$member['member_id']."'");
			} else {
				exit(json_encode(array('msg' => '退款失败')));
			}
			$order_data = array();
			$order_data['refund_sn'] = $refund_sn;
			$order_data['refund_amount'] = $refund_amount;				
			$order_data['order_state'] = 0;
			$order_data['refund_time'] = time();
			DB::update('order', $order_data, array('order_id'=>$order['order_id']));
			$log_data = array();
			$log_data['order_id'] = $order['order_id'];
			$log_data['order_state'] = 0;
			$log_data['order_intro'] = '订单退款';
			$log_data['log_time'] = time();
			Db::insert('order_log', $log_data);
			//优惠券和红包
			$coupon = DB::fetch_first("SELECT * FROM ".DB::table('coupon')." WHERE coupon_orderid='$refund_id' AND coupon_state=1");
			if(!empty($coupon)) {
				DB::query("UPDATE ".DB::table('coupon')." SET coupon_orderid=0, coupon_state=0 WHERE coupon_id='".$coupon['coupon_id']."'");
				DB::query("UPDATE ".DB::table('coupon_template')." SET coupon_t_used=coupon_t_used-1 WHERE coupon_t_id='".$coupon['coupon_t_id']."'");
			}
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=2 AND use_id='$refund_id' AND red_state=1");
			if(!empty($red)) {
				DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
			}
			//商家收益统计
			$profit_data = array(
				'store_id' => $order['store_id'],
				'profit_stage' => 'order',
				'profit_type' => 0,
				'profit_amount' => $refund_amount,
				'profit_desc' => '订单退款，订单单号：'.$order['order_sn'],			
				'is_freeze' => 0,
				'order_id' => $order['order_id'],
				'order_sn' => $order['order_sn'],
				'add_time' => time(),
			);
			DB::insert('store_profit', $profit_data);
			DB::query("UPDATE ".DB::table('store')." SET plat_amount=plat_amount-".$refund_amount.", pool_amount=pool_amount-".$refund_amount." WHERE store_id='".$order['store_id']."'");
			//养老金收益
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
			$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
			if($this->setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($refund_amount*$this->setting['first_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'consume',					
					'oldage_type' => 0,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']-$oldage_price,
					'oldage_desc' => '订单退款，扣除'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount-$oldage_price WHERE member_id='".$member['member_id']."'");
			}
			//商品销售统计
			$date = date('Ymd', $order['add_time']);
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='".$order['order_id']."'");
			while($value = DB::fetch($query)) {
				DB::query("UPDATE ".DB::table('goods')." SET goods_storage=goods_storage+".$value['goods_num'].", goods_salenum=goods_salenum-".$value['goods_num']." WHERE goods_id='".$value['goods_id']."'");
				DB::query("UPDATE ".DB::table('goods_spec')." SET spec_goods_storage=spec_goods_storage+".$value['goods_num'].", spec_salenum=spec_salenum-".$value['goods_num']." WHERE spec_id='".$value['spec_id']."'");
				$order_goods[] = $value;
			}
			foreach($order_goods as $key => $value) {
				$goods_stat = DB::fetch_first("SELECT * FROM ".DB::table('goods_stat')." WHERE goods_id='".$value['goods_id']."' AND date='$date'");
				if(!empty($goods_stat)) {
					$goods_stat_array = array(
						'salenum' => $goods_stat['salenum']-$value['goods_num'],
					);
					DB::update('goods_stat', $goods_stat_array, array('goods_id'=>$goods['goods_id'], 'date'=>$date));
				}
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}	
	}
	
	public function deliverOp() {
		if(submitcheck()) {
			$deliver_id = empty($_POST['deliver_id']) ? 0 : $_POST['deliver_id'];
			$express_id = empty($_POST['express_id']) ? 0 : intval($_POST['express_id']);
			$shipping_code = empty($_POST['shipping_code']) ? '' : $_POST['shipping_code'];
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$deliver_id'");
			if(empty($order) || $order['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'订单不存在')));
			}			
			if($order['order_state'] != '20') {
				exit(json_encode(array('msg'=>'订单不能发货')));	
			}
			if(empty($express_id)) {
				exit(json_encode(array('msg'=>'请选择快递公司')));
			}
			if(empty($shipping_code)) {
				exit(json_encode(array('msg'=>'请输入快递编号')));
			}
			$express = DB::fetch_first("SELECT * FROM ".DB::table('express')." WHERE express_id='$express_id'");
			$express_name = $express['express_name'];
			$express_code = $express['express_code'];
			$data = array(
				'express_name'=>$express_name,
				'express_code'=>$express_code,
				'shipping_code'=>$shipping_code,
				'order_state'=>30,
				'shipping_time'=>time()
			);
			DB::update('order', $data, array('order_id'=>$deliver_id));
			$log_data = array();
			$log_data['order_id'] = $deliver_id;
			$log_data['order_state'] = '30';
			$log_data['order_intro'] = '订单发货';
			$log_data['log_time'] = time();
			DB::insert('order_log', $log_data);
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
}

?>