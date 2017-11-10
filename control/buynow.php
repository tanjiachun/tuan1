<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class buynowControl extends BaseHomeControl {
	public function indexOp() {
		if(empty($this->member_id)) {
			$this->showmessage('您还未登录了', 'index.php?act=login', 'info');
		}
		$spec_id = empty($_GET['spec_id']) ? 0 : intval($_GET['spec_id']);
		$quantity = empty($_GET['quantity']) ? 0 : intval($_GET['quantity']);
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='$spec_id'");
		if(empty($goods) || $goods['goods_show'] != 1 || $goods['goods_state'] != 1) {
			$this->showmessage('商品已下架', 'index.php?act=index&op=goods', 'info');
		}
		if($quantity <= 0) {
			$this->showmessage('请填写购买数量', 'index.php?act=goods&goods_id='.$goods['goods_id'], 'info');
		}
		if($goods['spec_goods_storage'] < 1) {
			$this->showmessage('商品已经售完', 'index.php?act=goods&goods_id='.$goods['goods_id'], 'info');
		}
		$goods['spec_goods_price'] = $this->card['discount_rate'] > 0 ? $goods['spec_goods_price']*$this->card['discount_rate']/10 : $goods['spec_goods_price'];
		$goods['spec_goods_price'] = priceformat($goods['spec_goods_price']);
		if($goods['spec_goods_storage'] < $quantity) {
			$quantity = $goods['spec_goods_storage'];
		}
		if($goods['spec_open'] == 1 && !empty($goods['spec_name']) && !empty($goods['spec_goods_spec'])) {
			$spec_name = empty($goods['spec_name']) ? array() : unserialize($goods['spec_name']);
			if(!empty($spec_name)) {
				$spec_name = array_values($spec_name);
				$spec_goods_spec = empty($goods['spec_goods_spec']) ? array() : unserialize($goods['spec_goods_spec']);
				$i = 0;
				foreach($spec_goods_spec as $key => $value) {
					$goods['spec_info'][] = $spec_name[$i].":".$value;
					$i++;
				}
			}	
		}
		$goods_amount = $goods['spec_goods_price']*$quantity;
		$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='".$goods['store_id']."'");
		$curmodule = 'home';
		$bodyclass = '';
		include(template('buynow'));
	}
	
	public function step2Op() {
		if(empty($this->member_id)) {
			$this->showmessage('您还未登录了', 'index.php?act=login', 'info');
		}
		$query = DB::query("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' ORDER BY address_default DESC, address_id DESC");
		while($value = DB::fetch($query)) {
			if(!empty($value['address_default'])) {
				$address = $value;
			}
			$address_list[] = $value;
		}
		$spec_id = empty($_GET['spec_id']) ? 0 : intval($_GET['spec_id']);
		$quantity = empty($_GET['quantity']) ? 0 : intval($_GET['quantity']);
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='$spec_id'");
		if(empty($goods) || $goods['goods_show'] != 1 || $goods['goods_state'] != 1) {
			$this->showmessage('商品已下架', 'index.php?act=index&op=goods', 'info');
		}
		if($quantity <= 0) {
			$this->showmessage('请填写购买数量', 'index.php?act=goods&goods_id='.$goods['goods_id'], 'info');
		}
		if($goods['spec_goods_storage'] < 1) {
			$this->showmessage('商品已经售完', 'index.php?act=goods&goods_id='.$goods['goods_id'], 'info');
		}
		$goods['spec_goods_price'] = $this->card['discount_rate'] > 0 ? $goods['spec_goods_price']*$this->card['discount_rate']/10 : $goods['spec_goods_price'];
		$goods['spec_goods_price'] = priceformat($goods['spec_goods_price']);
		if($goods['spec_goods_storage'] < $quantity) {
			$quantity = $goods['spec_goods_storage'];
		}
		if($goods['spec_open'] == 1 && !empty($goods['spec_name']) && !empty($goods['spec_goods_spec'])) {
			$spec_name = empty($goods['spec_name']) ? array() : unserialize($goods['spec_name']);
			if(!empty($spec_name)) {
				$spec_name = array_values($spec_name);
				$spec_goods_spec = empty($goods['spec_goods_spec']) ? array() : unserialize($goods['spec_goods_spec']);
				$i = 0;
				foreach($spec_goods_spec as $key => $value) {
					$goods['spec_info'][] = $spec_name[$i].":".$value;
					$i++;
				}
			}	
		}
		$goods_amount = $goods['spec_goods_price']*$quantity;
		$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='".$goods['store_id']."'");
		$extend_name = array(
			'kd' => $store['kd_rename'],
			'es' => $store['es_rename'],
			'py' => $store['py_rename'],
		);
		$transport_goods = array();
		$transport_goods['goods_num'] = $quantity;
		if($goods['goods_postage'] == 'freight') {
			$transport_goods['kd_price'] = $goods['kd_price'];
			$transport_goods['es_price'] = $goods['es_price'];
			$transport_goods['py_price'] = $goods['py_price'];
		} else {
			$transport_goods['transport_id'] = $goods['transport_id'];
		}	
		$transport_list = $this->transport($transport_goods, $address['city_id']);
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('coupon')." WHERE member_id='$this->member_id' AND coupon_state=0 AND store_id='".$goods['store_id']."' AND coupon_starttime<=$time AND coupon_endtime>=$time");
		while($value = DB::fetch($query)) {
			$value['coupon_goods_id'] = empty($value['coupon_goods_id']) ? array() : explode(',', $value['coupon_goods_id']);
			if(empty($value['coupon_goods_id']) || in_array($goods['goods_id'], $value['coupon_goods_id'])) {
				if($value['coupon_price_type'] == 'cash') {
					$value['coupon_discount'] = $value['coupon_price'];
				} elseif($value['coupon_price_type'] == 'discount') {
					$value['coupon_discount'] = $goods_amount*(10-$value['coupon_price'])/10;
					$value['coupon_discount'] = priceformat($value['coupon_discount']);
				}
				if($value['coupon_limit'] > 0) {
					if($value['coupon_limit'] <= $goods_amount) {
						$coupon_list[] = $value;
					}
				} else {
					$coupon_list[] = $value;
				}
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND red_state=0 AND red_cate_id in ('0', '2') AND red_starttime<=$time AND red_endtime>=$time");
		while($value = DB::fetch($query)) {
			if($value['red_limit'] > 0) {
				if($value['red_limit'] <= $goods_amount) {
					$red_list[] = $value;
				}
			} else {
				$red_list[] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$province_list[] = $value;
		}
		$curmodule = 'home';
		$bodyclass = '';
		include(template('buynow_step2'));
	}
	
	public function orderOp() {
		if(empty($this->member_id)) {
			exit(json_encode(array('done'=>'login')));
		}
		$order_invoice = !in_array($_POST['order_invoice'], array('no', 'yes')) ? 'no' : $_POST['order_invoice'];
		$invoice_title = empty($_POST['invoice_title']) ? '' : $_POST['invoice_title'];
		$invoice_content = empty($_POST['invoice_content']) ? '' : $_POST['invoice_content'];
		$invoice_membername = empty($_POST['invoice_membername']) ? '' : $_POST['invoice_membername'];
		$invoice_provinceid = empty($_POST['invoice_provinceid']) ? 0 : intval($_POST['invoice_provinceid']);
		$invoice_cityid = empty($_POST['invoice_cityid']) ? 0 : intval($_POST['invoice_cityid']);
		$invoice_areaid = empty($_POST['invoice_areaid']) ? 0 : intval($_POST['invoice_areaid']);
		$invoice_address = empty($_POST['invoice_address']) ? '' : $_POST['invoice_address'];
		if($order_invoice == 'yes') {
			if(empty($invoice_title)) {
				exit(json_encode(array('id'=>'invoice_title', 'msg'=>'请输入发票抬头')));
			}
			if(empty($invoice_content)) {
				exit(json_encode(array('id'=>'invoice_content', 'msg'=>'请输入发票明细')));
			}
			if(empty($invoice_membername)) {
				exit(json_encode(array('id'=>'invoice_membername', 'msg'=>'请输入收件人')));
			}
			if(empty($invoice_provinceid)) {
				exit(json_encode(array('id'=>'invoice_provinceid', 'msg'=>'请选择邮寄地区')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$invoice_cityid'");
			if(empty($district_count)) {
				if(empty($invoice_cityid)) {
					exit(json_encode(array('id'=>'invoice_provinceid', 'msg'=>'请选择邮寄地区')));
				}	
			} else {
				if(empty($invoice_areaid)) {
					exit(json_encode(array('id'=>'invoice_provinceid', 'msg'=>'请选择邮寄地区')));
				}
			}
			if(empty($invoice_address)) {
				exit(json_encode(array('id'=>'invoice_address', 'msg'=>'请输入邮寄地址')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_provinceid'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_cityid'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_areaid'");
			$invoice_data = array(
				'invoice_title' => $invoice_title,
				'invoice_content' => $invoice_content,
				'invoice_membername' => $invoice_membername,
				'invoice_provinceid' => $invoice_provinceid,
				'invoice_cityid' => $invoice_cityid,
				'invoice_areaid' => $invoice_areaid,
				'invoice_areainfo' => $province_name.$city_name.$area_name,
				'invoice_address' => $invoice_address,
			);
			$invoice_content = serialize($invoice_data);
		} else {
			$invoice_content = '';
		}
		$address = DB::fetch_first("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' AND address_default=1");
		if(empty($address)) {
			exit(json_encode(array('id'=>'system', 'msg'=>'请选择收货地址')));
		}
		$spec_id = empty($_POST['spec_id']) ? 0 : intval($_POST['spec_id']);
		$quantity = empty($_POST['quantity']) ? 0 : intval($_POST['quantity']);
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='$spec_id'");
		if(empty($goods) || $goods['goods_show'] != 1 || $goods['goods_state'] != 1) {
			exit(json_encode(array('id'=>'system', 'msg'=>'商品已下架')));
		}
		if($quantity <= 0) {
			exit(json_encode(array('id'=>'system', 'msg'=>'请填写购买数量')));
		}
		if($goods['spec_goods_storage'] < 1) {
			exit(json_encode(array('id'=>'system', 'msg'=>'商品已经售完')));
		}
		$goods['spec_goods_orig_price'] = $goods['spec_goods_price'];
		$goods['spec_goods_price'] = $this->card['discount_rate'] > 0 ? $goods['spec_goods_price']*$this->card['discount_rate']/10 : $goods['spec_goods_price'];
		$goods['spec_goods_price'] = priceformat($goods['spec_goods_price']);
		if($goods['spec_goods_storage'] < $quantity) {
			$quantity = $goods['spec_goods_storage'];
		}
		if($goods['spec_open'] == 1 && !empty($goods['spec_name']) && !empty($goods['spec_goods_spec'])) {
			$spec_name = empty($goods['spec_name']) ? array() : unserialize($goods['spec_name']);
			if(!empty($spec_name)) {
				$spec_name = array_values($spec_name);
				$spec_goods_spec = empty($goods['spec_goods_spec']) ? array() : unserialize($goods['spec_goods_spec']);
				$i = 0;
				foreach($spec_goods_spec as $key => $value){
					$goods['spec_info'][] = $spec_name[$i].":".$value;
					$i++;
				}
			}	
		}
		$goods_orig_amount = $goods['spec_goods_orig_price']*$quantity;
		$goods_amount = $goods['spec_goods_price']*$quantity;
		$extend_type = empty($_POST['extend_type']) ? '' : $_POST['extend_type'];
		$transport_goods = array();
		$transport_goods['goods_num'] = $quantity;
		if($goods['goods_postage'] == 'freight') {
			$transport_goods['kd_price'] = $goods['kd_price'];
			$transport_goods['es_price'] = $goods['es_price'];
			$transport_goods['py_price'] = $goods['py_price'];
		} else {
			$transport_goods['transport_id'] = $goods['transport_id'];
		}	
		$transport_list = $this->transport($transport_goods, $address['city_id']);		
		if(empty($extend_type) || !in_array($extend_type, array('kd', 'es', 'py', 'free'))) {
			exit(json_encode(array('id'=>'system', 'msg'=>'请选择配送方式')));
		}
		$transport_amount = 0;
		if(!empty($transport_list)) {
			$transport_amount = $transport_list[$extend_type];
			if($transport_amount <= 0) {
				exit(json_encode(array('id'=>'system', 'msg'=>'请选择配送方式')));	
			}
		}
		$time = time();
		$coupon_id = empty($_POST['coupon_id']) ? 0 : intval($_POST['coupon_id']);
		$coupon_amount = 0;
		$coupon = DB::fetch_first("SELECT * FROM ".DB::table('coupon')." WHERE coupon_id='$coupon_id'");
		if(!empty($coupon) && $coupon['member_id'] == $this->member_id && $coupon['store_id'] == $goods['store_id'] && empty($coupon['coupon_state']) && $coupon['coupon_starttime'] <= $time && $coupon['coupon_endtime'] >= $time) {
			$coupon['coupon_goods_id'] = empty($coupon['coupon_goods_id']) ? array() : explode(',', $coupon['coupon_goods_id']);
			if(empty($coupon['coupon_goods_id']) || in_array($goods['goods_id'], $coupon['coupon_goods_id'])) {
				if($coupon['coupon_price_type'] == 'cash') {
					$coupon_discount = $coupon['coupon_price'];
				} elseif($coupon['coupon_price_type'] == 'discount') {
					$coupon_discount = $goods_amount*(10-$coupon['coupon_price'])/10;
					$coupon_discount = priceformat($coupon_discount);
				}
				if($coupon['coupon_limit'] > 0) {
					if($coupon['coupon_limit'] <= $goods_amount) {
						$coupon_amount = $coupon_discount;
						$order_coupon_id = $coupon['coupon_id'];
					}
				} else {
					$coupon_amount = $coupon_discount;
					$order_coupon_id = $coupon['coupon_id'];
				}
			}
		}
		$red_id = empty($_POST['red_id']) ? 0 : intval($_POST['red_id']);
		$red_amount = 0;
		$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE red_id='$red_id'");
		if(!empty($red) && $red['member_id'] == $this->member_id && in_array($red['red_cate_id'], array('0', '2')) && empty($red['red_state']) && $red['red_starttime'] <= $time && $red['red_endtime'] >= $time) {
			if($red['red_limit'] > 0) {
				if($red['red_limit'] <= $goods_amount) {
					$red_amount = $red['red_price'];
					$order_red_id = $red['red_id'];
				}
			} else {
				$red_amount = $red['red_price'];
				$order_red_id = $red['red_id'];
			}
		}
		$order_goods_name = $goods['goods_name'];
		$order_amount = $goods_amount+$transport_amount-$coupon_amount-$red_amount;
		$payment_code = !in_array($_POST['payment_code'], array('alipay', 'weixin', 'predeposit')) ? 'alipay' : $_POST['payment_code'];
		if($payment_code == 'predeposit') {
			if($this->member['available_predeposit'] < $order_amount) {
				exit(json_encode(array('id'=>'system', 'msg'=>'余额不足，请选择其他支付方式')));
			}
		}
		$order_sn = makesn();
		$out_sn = date('YmdHis').random(18);
		$order_data = array();
		$order_data['order_sn'] = $order_sn;
		$order_data['out_sn'] = $out_sn;
		$order_data['store_id'] = $goods['store_id'];
		$order_data['member_id'] = $this->member_id;
		$order_data['member_phone'] = $this->member['member_phone'];
		$order_data['order_goods_name'] = $order_goods_name;
		$order_data['invoice_content'] = $invoice_content;
		$order_data['goods_orig_amount'] = $goods_orig_amount;
		$order_data['goods_amount'] = $goods_amount;
		$order_data['transport_amount'] = $transport_amount;
		$order_data['coupon_amount'] = $coupon_amount;
		$order_data['red_amount'] = $red_amount;
		$order_data['order_amount'] = $order_amount;
		$order_data['order_state'] = 10;
		$order_data['add_time'] = time();
		$order_id = DB::insert('order', $order_data, 1);
		$order_address_data = array();
		$order_address_data['order_id'] = $order_id;
		$order_address_data['true_name'] = $address['true_name'];
		$order_address_data['mobile_phone'] = $address['mobile_phone'];
		$order_address_data['province_id'] = $address['province_id'];
		$order_address_data['city_id'] = $address['city_id'];
		$order_address_data['area_id'] = $address['area_id'];
		$order_address_data['area_info'] = $address['area_info'];
		$order_address_data['address_info'] = $address['address_info'];			
		DB::insert('order_address', $order_address_data);
		$order_goods_data = array();
		$order_goods_data['order_id'] = $order_id;
		$order_goods_data['goods_id'] = $goods['goods_id'];
		$order_goods_data['goods_name'] = $goods['goods_name'];
		$order_goods_data['spec_id'] = $goods['spec_id'];
		$order_goods_data['spec_info'] = implode(' ', $goods['spec_info']);
		$order_goods_data['goods_image'] = $goods['goods_image'];
		$order_goods_data['goods_price'] = $goods['spec_goods_price'];
		$order_goods_data['goods_num'] = $quantity;
		DB::insert('order_goods', $order_goods_data);
		DB::query("UPDATE ".DB::table('goods')." SET goods_storage=goods_storage-$quantity, goods_salenum=goods_salenum+$quantity WHERE goods_id='".$goods['goods_id']."'");
		DB::query("UPDATE ".DB::table('goods_spec')." SET spec_goods_storage=spec_goods_storage-$quantity, spec_salenum=spec_salenum+$quantity WHERE spec_id='".$goods['spec_id']."'");
		$log_data = array();
		$log_data['order_id'] = $order_id;
		$log_data['order_state'] = '10';
		$log_data['order_intro'] = '订单提交';	
		$log_data['log_time'] = time();
		DB::insert('order_log', $log_data);
		if(!empty($order_coupon_id)) {
			DB::update('coupon', array('coupon_orderid'=>$order_id, 'coupon_state'=>1),array('coupon_id'=>$coupon['coupon_id']));
			DB::query("UPDATE ".DB::table('coupon_template')." SET coupon_t_used=coupon_t_used+1 WHERE coupon_t_id='".$coupon['coupon_t_id']."'");
		}
		if(!empty($order_red_id)) {
			DB::update('red', array('use_type'=>2, 'use_id'=>$order_id, 'red_state'=>1),array('red_id'=>$red['red_id']));
			DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used+1 WHERE red_t_id='".$red['red_t_id']."'");
		}
		if($payment_code == 'predeposit') {
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
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
			$order_state = 20;
		} else {
			$order_state = 10;	
		}
		$order_sn = makesn(1);
		$out_sn = date('YmdHis').random(18);
		$order_temp_data = array();
		$order_temp_data['member_id'] = $this->member_id;
		$order_temp_data['order_sn'] = $order_sn;
		$order_temp_data['order_goods_name'] = $order_goods_name;
		$order_temp_data['out_sn'] = $out_sn;
		$order_temp_data['payment_code'] = $payment_code;
		$order_temp_data['order_amount'] = $order_amount;
		$order_temp_data['order_state'] = $order_state;
		$order_temp_data['order_ids'] = $order_id;
		$order_temp_data['add_time'] = time();
		$order_temp_id = DB::insert('order_temp', $order_temp_data, 1);
		if(!empty($order_temp_id)) {
			exit(json_encode(array('done'=>'true', 'order_sn'=>$order_sn)));
		} else {
			exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function addressOp() {
		$query = DB::query("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' ORDER BY address_default DESC, address_id DESC");
		while($value = DB::fetch($query)) {
			$address_list[] = $value;
		}
		include(template('buynow_address'));
	}
	
	public function address_addOp() {
		if(submitcheck()) {
			if(empty($this->member)) {
				exit(json_encode(array('msg'=>'您还未登陆')));
			}
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
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$province_id'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$city_id'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$area_id'");
			$area_info = $province_name.$city_name.$area_name;
			$address_default_id = DB::result_first("SELECT address_id FROM ".DB::table('address')." WHERE member_id='$this->member_id' AND address_default=1");
			$address_data = array(
				'member_id' => $this->member_id,
				'true_name' => $true_name,
				'mobile_phone' => $mobile_phone,
				'province_id' => $province_id,
				'city_id' => $city_id,
				'area_id' => $area_id,
				'area_info' => $area_info,
				'address_info' => $address_info,
				'address_default' => 1,
			);		
			$address_id = DB::insert('address', $address_data, 1);
			if(!empty($address_id) && !empty($address_default_id)) {
				DB::update('address', array('address_default'=>0), array('address_id'=>$address_default_id));
			}
			$spec_id = empty($_GET['spec_id']) ? 0 : intval($_GET['spec_id']);
			$quantity = empty($_GET['quantity']) ? 0 : intval($_GET['quantity']);
			$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='$spec_id'");
			if($goods['spec_goods_storage'] < $quantity) {
				$quantity = $goods['spec_goods_storage'];
			}
			$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='".$goods['store_id']."'");
			$extend_name = array(
				'kd' => $store['kd_rename'],
				'es' => $store['es_rename'],
				'py' => $store['py_rename'],
			);
			$transport_goods = array();
			$transport_goods['goods_num'] = $quantity;
			if($goods['goods_postage'] == 'freight') {
				$transport_goods['kd_price'] = $goods['kd_price'];
				$transport_goods['es_price'] = $goods['es_price'];
				$transport_goods['py_price'] = $goods['py_price'];
			} else {
				$transport_goods['transport_id'] = $goods['transport_id'];
			}
			$transport_list = $this->transport($transport_goods, $address_data['city_id']);
			if(!empty($transport_list)) {
				foreach($transport_list as $key => $value) {
					$transport .= '<li extend_type="'.$key.'" extend_price="'.$value.'">'.$extend_name[$key].'(￥'.$value.')<b></b></li>';
				}
			} else {
				$transport = '<li extend_type="free" extend_price="0.00">免运费(￥0.00)<b></b></li>';
			}
			$address = '<span>寄送至：'.$address_data['area_info'].$address_data['address_info'].'</span><span>收货人：'.$address_data['true_name'].' '.$address_data['mobile_phone'].'</span>';
			exit(json_encode(array('done'=>'true', 'transport'=>$transport, 'address'=>$address)));
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			include(template('buynow_address_add'));
		}
	}
	
	public function address_editOp() {
		if(submitcheck()) {
			if(empty($this->member)) {
				exit(json_encode(array('msg'=>'您还未登陆')));
			}
			$address_id = empty($_POST['address_id']) ? 0 : intval($_POST['address_id']);
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
			DB::update('address', $address_data, array('address_id'=>$address_id, 'member_id'=>$this->member_id));
			$address_default = DB::fetch_first("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' AND address_default=1");
			$spec_id = empty($_GET['spec_id']) ? 0 : intval($_GET['spec_id']);
			$quantity = empty($_GET['quantity']) ? 0 : intval($_GET['quantity']);
			$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='$spec_id'");
			if($goods['spec_goods_storage'] < $quantity) {
				$quantity = $goods['spec_goods_storage'];
			}
			$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='".$goods['store_id']."'");
			$extend_name = array(
				'kd' => $store['kd_rename'],
				'es' => $store['es_rename'],
				'py' => $store['py_rename'],
			);
			$transport_goods = array();
			$transport_goods['goods_num'] = $quantity;
			if($goods['goods_postage'] == 'freight') {
				$transport_goods['kd_price'] = $goods['kd_price'];
				$transport_goods['es_price'] = $goods['es_price'];
				$transport_goods['py_price'] = $goods['py_price'];
			} else {
				$transport_goods['transport_id'] = $goods['transport_id'];
			}
			$transport_list = $this->transport($transport_goods, $address_default['city_id']);
			if(!empty($transport_list)) {
				foreach($transport_list as $key => $value) {
					$transport .= '<li extend_type="'.$key.'" extend_price="'.$value.'">'.$extend_name[$key].'(￥'.$value.')<b></b></li>';
				}
			} else {
				$transport = '<li extend_type="free" extend_price="0.00">免运费(￥0.00)<b></b></li>';
			}
			$address = '<span>寄送至：'.$address_default['area_info'].$address_default['address_info'].'</span><span>收货人：'.$address_default['true_name'].' '.$address_default['mobile_phone'].'</span>';
			exit(json_encode(array('done'=>'true', 'transport'=>$transport, 'address'=>$address)));
		} else {
			$address_id = empty($_GET['address_id']) ? 0 : intval($_GET['address_id']);
			$address = DB::fetch_first("SELECT * FROM ".DB::table('address')." WHERE address_id='$address_id' AND member_id='$this->member_id'");
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$address['province_id']."' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$city_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$address['city_id']."' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$area_list[] = $value;
			}
			include(template('buynow_address_edit'));
		}
	}
	
	public function address_delOp() {
		if(submitcheck()) {
			if(empty($this->member)) {
				exit(json_encode(array('done'=>'login')));
			}
			$address_id = empty($_POST['address_id']) ? 0 : intval($_POST['address_id']);
			DB::delete('address', array('address_id'=>$address_id, 'member_id'=>$this->member_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function address_defaultOp() {
		if(submitcheck()) {
			if(empty($this->member)) {
				exit(json_encode(array('done'=>'login')));
			}
			$address_id = empty($_POST['address_id']) ? 0 : intval($_POST['address_id']);
			DB::update('address', array('address_default'=>0), array('member_id'=>$this->member_id));
			DB::update('address', array('address_default'=>1), array('address_id'=>$address_id, 'member_id'=>$this->member_id));
			$address_default = DB::fetch_first("SELECT * FROM ".DB::table('address')." WHERE address_id='$address_id' AND member_id='$this->member_id'");
			$spec_id = empty($_GET['spec_id']) ? 0 : intval($_GET['spec_id']);
			$quantity = empty($_GET['quantity']) ? 0 : intval($_GET['quantity']);
			$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='$spec_id'");
			if($goods['spec_goods_storage'] < $quantity) {
				$quantity = $goods['spec_goods_storage'];
			}
			$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='".$goods['store_id']."'");
			$extend_name = array(
				'kd' => $store['kd_rename'],
				'es' => $store['es_rename'],
				'py' => $store['py_rename'],
			);
			$transport_goods = array();
			$transport_goods['goods_num'] = $quantity;
			if($goods['goods_postage'] == 'freight') {
				$transport_goods['kd_price'] = $goods['kd_price'];
				$transport_goods['es_price'] = $goods['es_price'];
				$transport_goods['py_price'] = $goods['py_price'];
			} else {
				$transport_goods['transport_id'] = $goods['transport_id'];
			}
			$transport_list = $this->transport($transport_goods, $address_default['city_id']);
			if(!empty($transport_list)) {
				foreach($transport_list as $key => $value) {
					$transport .= '<li extend_type="'.$key.'" extend_price="'.$value.'">'.$extend_name[$key].'(￥'.$value.')<b></b></li>';
				}
			} else {
				$transport = '<li extend_type="free" extend_price="0.00">免运费(￥0.00)<b></b></li>';
			}
			$address = '<span>寄送至：'.$address_default['area_info'].$address_default['address_info'].'</span><span>收货人：'.$address_default['true_name'].' '.$address_default['mobile_phone'].'</span>';
			exit(json_encode(array('done'=>'true', 'transport'=>$transport, 'address'=>$address)));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	private function transport($goods, $area_id) {
		$tmp = array();	
		$result = array();		
		$result['kd'] = 0;
		$result['es'] = 0;
		$result['py'] = 0;
		if(empty($goods['transport_id'])) {
			if(is_numeric($goods['kd_price']) && $goods['kd_price'] > 0) {
				$result['kd'] = $goods['kd_price'];
			}
			if(is_numeric($goods['es_price']) && $goods['es_price'] > 0) {
				$result['es'] = $goods['es_price'];
			}
			if(is_numeric($goods['py_price']) && $goods['py_price'] > 0) {
				$result['py'] = $goods['py_price'];
			}
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('transport_extend')." WHERE transport_id='".$goods['transport_id']."'");
			while($value = DB::fetch($query)) {
				$extend[] = $value;	
			}			
			$unset_kd = true;
			$unset_es = true;
			$unset_py = true;
			$calc = array();
			$calc_default = array();
			if(!empty($extend) && is_array($extend)) {
				foreach($extend as $key => $value) {
					if($value['extend_type'] == 'kd') {
						$unset_kd = false;
					}
					if($value['extend_type'] == 'es') {
						$unset_es = false;
					}
					if($value['extend_type'] == 'py') {
						$unset_py = false;
					}
					if(strpos($value['area_id'], ",".intval($area_id).",") !== false) {
						if($goods['goods_num'] <= $value['extend_snum']) {
							$calc[$value['extend_type']] = $value['extend_sprice'];
						} else {
							$calc[$value['extend_type']] = sprintf('%.2f', ($value['extend_sprice'] + ceil(($goods['goods_num']-$value['extend_snum'])/$value['extend_xnum'])*$value['extend_xprice']));
						}
					}
					if($value['is_default'] == 1) {
						if($goods['goods_num'] <= $value['extend_snum']) {
							$calc_default[$value['extend_type']] = $value['extend_sprice'];
						} else {
							$calc_default[$value['extend_type']] = sprintf('%.2f', ($value['extend_sprice'] + ceil(($goods['goods_num']-$value['extend_snum'])/$value['extend_xnum'])*$value['extend_xprice']));
						}
					}
				}
			}
			foreach(array('kd', 'es', 'py') as $extend_type) {
				if(!isset($calc[$extend_type]) && isset($calc_default[$extend_type])) {
					$calc[$extend_type] = $calc_default[$extend_type];
				}
			}
			$result['kd'] = $calc['kd'];
			$result['es'] = $calc['es'];
			$result['py'] = $calc['py'];
		}		
		$result['kd'] = sprintf('%.2f', $result['kd']);
		$result['es'] = sprintf('%.2f', $result['es']);
		$result['py'] = sprintf('%.2f', $result['py']);
		if(is_numeric($result['kd']) && $result['kd'] > 0) {
			if($unset_kd == true) {
				unset($result['kd']);
			}
		} else {
			unset($result['kd']);	
		}
		if(is_numeric($result['es']) && $result['es'] > 0) {
			if($unset_es == true) {
				unset($result['es']);
			}
		} else {
			unset($result['es']);	
		}
		if(is_numeric($result['py']) && $result['py'] > 0) {
			if($unset_py == true) {
				unset($result['py']);
			}
		} else {
			unset($result['py']);	
		}
		return $result;
	}
}

?>