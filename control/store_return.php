<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_returnControl extends BaseStoreControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_return";
		$wheresql = " WHERE store_id='$this->store_id'";
		$state = !in_array($_GET['state'],array('pending', 'show', 'unshow')) ? '' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'pending') {
			$wheresql .= " AND return_state=3";
		} elseif($state == 'show') {
			$wheresql .= " AND return_state=1";
		} elseif($state == 'unshow') {
			$wheresql .= " AND return_state=2";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= "&search_name=$search_name";
			$wheresql .= " AND return_sn like '%$search_name%'";	
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order_return').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('order_return').$wheresql." ORDER BY return_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$return_ids[] = $value['return_id'];
			$order_ids[] = $value['order_id'];
			$value['return_image'] = empty($value['return_image']) ? '' : unserialize($value['return_image']);
			$return_list[] = $value;
		}
		if(!empty($return_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id in ('".implode("','", $return_ids)."')");
			while($value = DB::fetch($query)) {
				$return_goods[$value['return_id']] = $value;
			}
		}
		if(!empty($order_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order_address')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_address[$value['order_id']] = $value;
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
		include(template('store_return'));
	}
	
	public function physicalOp() {
		$return_id = empty($_GET['return_id']) ? 0 : intval($_GET['return_id']);
		$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$return_id'");
		if(empty($order_return) || $order_return['store_id'] != $this->store_id) {
			$this->showmessage('退货单不存在', 'index.php?act=return', 'error');
		}
		$order_return['return_image'] = empty($order_return['return_image']) ? '' : unserialize($order_return['return_image']);
		$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='$return_id'");
		if(!empty($order_return['express_code']) && !empty($order_return['shipping_code'])) {
			$url = 'http://www.kuaidi100.com/query?type='.$order_return['express_code'].'&postid='.$order_return['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
			$content = geturlfile($url);
			$delivery = json_decode($content, true);
			$delivery_data = array_reverse($delivery['data']);
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('return_physical'));
	}
	
	public function seller_physicalOp() {
		$return_id = empty($_GET['return_id']) ? 0 : intval($_GET['return_id']);
		$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$return_id'");
		if(empty($order_return) || $order_return['store_id'] != $this->store_id) {
			$this->showmessage('退货单不存在', 'index.php?act=return', 'error');
		}
		$order_return['return_image'] = empty($order_return['return_image']) ? '' : unserialize($order_return['return_image']);
		$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='$return_id'");
		if(!empty($order_return['seller_express_code']) && !empty($order_return['seller_shipping_code'])) {
			$url = 'http://www.kuaidi100.com/query?type='.$order_return['seller_express_code'].'&postid='.$order_return['seller_shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
			$content = geturlfile($url);
			$delivery = json_decode($content, true);
			$delivery_data = array_reverse($delivery['data']);
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('return_seller_physical'));
	}
	
	public function agreeOp() {
		if(submitcheck()) {
			$agree_id = empty($_POST['agree_id']) ? 0 : $_POST['agree_id'];
			$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$agree_id'");
			if(empty($order_return) || $order_return['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'退货单不存在')));
			}			
			if($order_return['return_state'] != '3') {
				exit(json_encode(array('msg'=>'退货单已经处理过了')));	
			}
			DB::update('order_return', array('seller_time'=>time(), 'return_state'=>1), array('return_id'=>$agree_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function refuseOp() {
		if(submitcheck()) {
			$refuse_id = empty($_POST['refuse_id']) ? 0 : $_POST['refuse_id'];
			$seller_message = empty($_POST['seller_message']) ? '' : $_POST['seller_message'];
			$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$refuse_id'");
			if(empty($order_return) || $order_return['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'退货单不存在')));
			}			
			if($order_return['return_state'] != '3') {
				exit(json_encode(array('msg'=>'退货单已经处理过了')));	
			}
			DB::update('order_return', array('seller_time'=>time(), 'seller_message'=>$seller_message, 'return_state'=>2), array('return_id'=>$refuse_id));
			$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='$refuse_id'");
			$order_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_goods')." WHERE rec_id='".$order_return_goods['rec_id']."'");
			$goods_returnnum = $order_goods['goods_returnnum']-$order_return_goods['goods_returnnum'];
			$goods_return_state = $goods_returnnum>0 ? 1 : 0;
			DB::update('order_goods', array('goods_returnnum'=>$goods_returnnum, 'goods_return_state'=>$goods_return_state), array('rec_id'=>$order_return_goods['rec_id']));
			$return_state = 0;
			$query = DB::query("SELECT * FROM ".DB::table('order_return')." WHERE order_id='".$order_return['order_id']."'");
			while($value = DB::fetch($query)) {
				if($value['return_state'] == 1 && $value['physical_state'] != 4) {
					$return_state = 1;
				} elseif($value['return_state'] == 3) {
					$return_state = 1;
				}
			}
			DB::update('order', array('return_state'=>$return_state), array('order_id'=>$order_return['order_id']));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function receiveOp() {
		if(submitcheck()) {
			$receive_id = empty($_POST['receive_id']) ? 0 : $_POST['receive_id'];
			$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$receive_id'");
			if(empty($order_return) || $order_return['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'退货单不存在')));
			}			
			if($order_return['return_state'] != '1' || $order_return['physical_state'] != '1') {
				exit(json_encode(array('msg'=>'退货单不能收货')));	
			}
			DB::update('order_return', array('physical_state'=>2), array('return_id'=>$receive_id));
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
			$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$deliver_id'");
			if(empty($order_return) || $order_return['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'退货单不存在')));
			}			
			if($order_return['return_state'] != '1' || $order_return['physical_state'] != '2') {
				exit(json_encode(array('msg'=>'退货单不能发货')));	
			}
			if($order_return['return_type'] != 'exchange') {
				exit(json_encode(array('msg'=>'退货单不能发货')));	
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
				'physical_state' => 3,
				'seller_express_name'=>$express_name,
				'seller_express_code'=>$express_code,
				'seller_shipping_code'=>$shipping_code,
				'seller_shipping_time'=>time()
			);
			DB::update('order_return', $data, array('return_id'=>$deliver_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function finishOp() {
		if(submitcheck()) {
			$finish_id = empty($_POST['finish_id']) ? 0 : $_POST['finish_id'];
			$return_amount = empty($_POST['return_amount']) ? '' : floatval($_POST['return_amount']);
			$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$finish_id'");
			$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='$finish_id'");
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='".$order_return['order_id']."'"); 
			if(empty($order_return) || $order_return['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'退货单不存在')));
			}			
			if($order_return['return_state'] != '1' || $order_return['physical_state'] != '2') {
				exit(json_encode(array('msg'=>'退货单不能完成')));	
			}
			if($order_return['return_type'] != 'return') {
				exit(json_encode(array('msg'=>'退货单不能完成')));	
			}
			if($return_amount <= 0) {
				exit(json_encode(array('msg'=>'请输入退款金额')));
			}
			$leave_amount = $order['order_amount']-$order['return_amount'];
			if($return_amount > $leave_amount) {
				exit(json_encode(array('msg'=>'退款金额不能大于订单金额')));
			}
			$refund_sn = date('Ymd').random(8, 1);
			if($order['payment_code'] == 'alipay') {
				require_once MALL_ROOT.'/api/alipay/alipay.php';
				$param = array();
				$param['batch_no'] = $refund_sn;
				$param['refund_date'] = date('Y-m-d H:i:s');
				$param['transaction_id'] = $order['transaction_id'];
				$param['refund_amount'] = $return_amount;
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
				$refundOrder->setParameter("refund_fee", $return_amount*100);
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
					'pdl_price' => $return_amount,
					'pdl_predeposit' => $member['available_predeposit']+$return_amount,
					'pdl_desc' => '订单退货，订单单号: '.$order['order_sn'],
					'pdl_addtime' => time(),
				);
				DB::insert('pd_log', $data);
				DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+".$return_amount." WHERE member_id='".$member['member_id']."'");
			} else {
				exit(json_encode(array('msg' => '退款失败')));
			}
			//退货单	
			DB::update('order_return', array('return_amount'=>$return_amount, 'physical_state'=>4), array('return_id'=>$finish_id));
			$return_state = 0;
			$query = DB::query("SELECT * FROM ".DB::table('order_return')." WHERE order_id='".$order_return['order_id']."'");
			while($value = DB::fetch($query)) {
				if($value['return_state'] == 1 && $value['physical_state'] != 4) {
					$return_state = 1;
				} elseif($value['return_state'] == 3) {
					$return_state = 1;
				}
			}
			DB::query("UPDATE ".DB::table('order')." SET return_amount=return_amount+$return_amount, return_state=$return_state WHERE order_id='".$order_return['order_id']."'");
			//商家收益统计
			$profit_data = array(
				'store_id' => $order['store_id'],
				'profit_stage' => 'order',
				'profit_type' => 0,
				'profit_amount' => $return_amount,
				'profit_desc' => '订单退货，订单单号：'.$order['order_sn'],			
				'is_freeze' => 0,
				'order_id' => $order['order_id'],
				'order_sn' => $order['order_sn'],
				'add_time' => time(),
			);
			DB::insert('store_profit', $profit_data);
			DB::query("UPDATE ".DB::table('store')." SET plat_amount=plat_amount-".$return_amount.", pool_amount=pool_amount-".$return_amount." WHERE store_id='".$order['store_id']."'");
			//养老金收益
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
			$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
			if($this->setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($return_amount*$this->setting['first_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'consume',					
					'oldage_type' => 0,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']-$oldage_price,
					'oldage_desc' => '订单退货，扣除'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount-$oldage_price WHERE member_id='".$member['member_id']."'");
			}
			//商品销售统计
			$date = date('Ymd', $order['add_time']);
			DB::query("UPDATE ".DB::table('goods')." SET goods_salenum=goods_salenum-".$order_return_goods['goods_returnnum']." WHERE goods_id='".$order_return_goods['goods_id']."'");
			DB::query("UPDATE ".DB::table('goods_spec')." SET spec_salenum=spec_salenum-".$order_return_goods['goods_returnnum']." WHERE spec_id='".$order_return_goods['spec_id']."'");
			$goods_stat = DB::fetch_first("SELECT * FROM ".DB::table('goods_stat')." WHERE goods_id='".$order_return_goods['goods_id']."' AND date='$date'");
			if(!empty($goods_stat)) {
				$goods_stat_array = array(
					'salenum' => $goods_stat['salenum']-$order_return_goods['goods_returnnum'],
				);
				DB::update('goods_stat', $goods_stat_array, array('goods_id'=>$goods['goods_id'], 'date'=>$date));
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
}

?>