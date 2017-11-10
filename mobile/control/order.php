<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class orderControl extends BaseMobileControl {
	public function indexOp() {
		$order_all = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id'");
		$book_all = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id'");
		$order_pending = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND order_state in ('10', '20', '30')");
		$book_pending = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND book_state in ('10', '40')");
		$order_comment = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND order_state=40 AND comment_state=0");
		$book_comment = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND book_state in ('20', '30') AND comment_state=0");
		$order_return = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND return_state=1");
		$order_count['all'] = $order_all+$book_all;
		$order_count['pending'] = $order_pending+$book_pending;
		$order_count['comment'] = $order_comment+$book_comment;
		$order_count['return'] = $order_return;
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$state = !in_array($_POST['state'],array('pending', 'comment', 'return')) ? '' : $_POST['state'];
		if($state == 'pending') {
			$query = DB::query("(SELECT order_id as id, 'order' as type, add_time FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND order_state in ('10', '20', '30')) UNION (SELECT book_id as id, 'nurse' as type, add_time FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND book_state in ('10', '40')) ORDER BY add_time DESC LIMIT $start, $perpage");
			while($value = DB::fetch($query)) {
				$temp_list[] = $value;
			}
		} elseif($state == 'comment') {
			$query = DB::query("(SELECT order_id as id, 'order' as type, add_time FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND order_state=40 AND comment_state=0) UNION (SELECT book_id as id, 'nurse' as type, add_time FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND book_state in ('20', '30') AND comment_state=0) ORDER BY add_time DESC LIMIT $start, $perpage");
			while($value = DB::fetch($query)) {
				$temp_list[] = $value;
			}
		} elseif($state == 'return') {
			$query = DB::query("SELECT order_id as id, 'order' as type, add_time FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND return_state=1");
			while($value = DB::fetch($query)) {
				$temp_list[] = $value;
			}
		} else {
			$query = DB::query("(SELECT order_id as id, 'order' as type, add_time FROM ".DB::table('order')." WHERE member_id='$this->member_id') UNION (SELECT book_id as id, 'nurse' as type, add_time FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id') ORDER BY add_time DESC LIMIT $start, $perpage");
			while($value = DB::fetch($query)) {
				$temp_list[] = $value;
			}
		}	
		foreach($temp_list as $key => $value) {
			if($value['type'] == 'order') {
				$order_ids[] = $value['id'];
			} elseif($value['type'] == 'nurse') {
				$book_ids[] = $value['id'];
			}
		}
		if(!empty($order_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$store_ids[] = $value['store_id'];
				$value['add_time'] = date('Y-m-d H:i', $value['add_time']);
				$value['payment_time'] = date('Y-m-d H:i', $value['payment_time']);
				$value['shipping_time'] = date('Y-m-d H:i', $value['shipping_time']);
				$value['receive_time'] = date('Y-m-d H:i', $value['receive_time']);
				$value['finish_time'] = date('Y-m-d H:i', $value['finish_time']);
				$value['comment_time'] = date('Y-m-d H:i', $value['comment_time']);
				$order_list[$value['order_id']] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('order_address')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_address[$value['order_id']] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$value['spec_info'] = empty($value['spec_info']) ? array() : explode(' ', $value['spec_info']);
				$order_goods[$value['order_id']][] = $value;
			}
			if(!empty($store_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
				while($value = DB::fetch($query)) {
					$store_list[$value['store_id']] = $value['store_name'];
				}
			}
			foreach($order_list as $key => $value) {
				$order_list[$key]['store_name'] = $store_list[$value['store_id']];
				$order_list[$key]['order_address'] = $order_address[$value['order_id']];
				$order_list[$key]['order_goods'] = $order_goods[$value['order_id']];
			}
		}
		if(!empty($book_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $book_ids)."')");
			while($value = DB::fetch($query)) {
				$nurse_ids[] = $value['nurse_id'];
				$agent_ids[] = $value['agent_id'];
				$value['book_service'] = empty($value['book_service']) ? array() : unserialize($value['book_service']);
				$value['add_time'] = date('Y-m-d H:i', $value['add_time']);
				$value['payment_time'] = date('Y-m-d H:i', $value['payment_time']);
				$value['finish_time'] = date('Y-m-d H:i', $value['finish_time']);
				$value['comment_time'] = date('Y-m-d H:i', $value['comment_time']);
				$book_list[$value['book_id']] = $value;
			}
			if(!empty($nurse_ids)) {
				$query = DB::query("SELECT nurse_id, nurse_name, nurse_image, nurse_education, nurse_type FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
				while($value = DB::fetch($query)) {
					$nurse_list[$value['nurse_id']] = $value;
				}
			}
			if(!empty($agent_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
				while($value = DB::fetch($query)) {
					$agent_list[$value['agent_id']] = $value['agent_name'];
				}
			}
			foreach($book_list as $key => $value) {
				$book_list[$key]['agent_name'] = $agent_list[$value['agent_id']];
				$book_list[$key]['nurse'] = $nurse_list[$value['nurse_id']];
			}
		}
		foreach($temp_list as $key => $value) {
			if($value['type'] == 'order') {
				$temp_order_list[$key]['type'] = 'order';
				$temp_order_list[$key]['data'] = $order_list[$value['id']];
			} elseif($value['type'] == 'nurse') {
				$temp_order_list[$key]['type'] = 'nurse';
				$temp_order_list[$key]['data'] = $book_list[$value['id']];
			}
		}
		$data = array(
			'order_count' => $order_count,
			'order_list' => $temp_order_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function viewOp() {	
		$id = empty($_POST['id']) ? 0 : intval($_POST['id']);
		$type = !in_array($_POST['type'], array('order', 'nurse')) ? '' : $_POST['type'];
		if($type == 'order') {
			$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$id'");
			if(empty($order) || $order['member_id'] != $this->member_id) {
				exit(json_encode(array('code'=>'1', 'msg'=>'订单不存在', 'data'=>array())));
			}
			$order['store_name'] = DB::result_first("SELECT store_name FROM ".DB::table('store')." WHERE store_id='".$order['store_id']."'");
			$order['add_time'] = date('Y-m-d H:i', $order['add_time']);
			$order['payment_time'] = date('Y-m-d H:i', $order['payment_time']);
			$order['shipping_time'] = date('Y-m-d H:i', $order['shipping_time']);
			$order['receive_time'] = date('Y-m-d H:i', $order['receive_time']);
			$order['finish_time'] = date('Y-m-d H:i', $order['finish_time']);
			$order['comment_time'] = date('Y-m-d H:i', $order['comment_time']);
			$order['order_address'] = DB::fetch_first("SELECT * FROM ".DB::table('order_address')." WHERE order_id='$id'");
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$id'");
			while($value = DB::fetch($query)) {
				$value['spec_info'] = empty($value['spec_info']) ? array() : explode(' ', $value['spec_info']);
				$order_goods[] = $value;
			}
			$order['order_goods'] = $order_goods;
			$query = DB::query("SELECT * FROM ".DB::table('order_log')." WHERE order_id='$id' ORDER BY log_time ASC");
			while($value = DB::fetch($query)) {
				$value['log_time'] = date('Y-m-d H:i', $value['log_time']);
				$order_log[] = $value;
			}
			$data = array(
				'order' => $order,
				'order_log' => $order_log,
			);
		} elseif($type == 'nurse') {
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$id'");
			if(empty($book) || $book['member_id'] != $this->member_id) {
				exit(json_encode(array('code'=>'1', 'msg'=>'预订单不存在', 'data'=>array())));
			}
			$book['nurse'] = DB::fetch_first("SELECT nurse_id, nurse_name, nurse_image, nurse_age, nurse_education, nurse_type FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
			$book['agent_name'] = DB::result_first("SELECT agent_name FROM ".DB::table('agent')." WHERE agent_id='".$book['agent_id']."'");
			$book['book_service'] = empty($book['book_service']) ? array() : unserialize($book['book_service']);
			$book['add_time'] = date('Y-m-d H:i', $book['add_time']);
			$book['payment_time'] = date('Y-m-d H:i', $book['payment_time']);
			$book['finish_time'] = date('Y-m-d H:i', $book['finish_time']);
			$book['comment_time'] = date('Y-m-d H:i', $book['comment_time']);
			$data = array(
				'order' => $book,
				'order_log' => array(),
			);
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择类型', 'data'=>array())));
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function cancelOp() {
		$order_id = empty($_POST['order_id']) ? 0 : intval($_POST['order_id']);
		$cancel_message = empty($_POST['cancel_message']) ? '' : $_POST['cancel_message'];
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
		if(empty($order) || $order['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'订单不存在', 'data'=>array())));
		}			
		if($order['order_state'] != '10') {
			exit(json_encode(array('code'=>'1', 'msg'=>'订单不能取消', 'data'=>array())));	
		}
		DB::update('order', array('cancel_message'=>$cancel_message, 'order_state'=>0), array('order_id'=>$order_id));
		$log_data = array();
		$log_data['order_id'] = $order_id;
		$log_data['order_state'] = 0;
		$log_data['order_intro'] = '订单取消';	
		$log_data['log_time'] = time();
		DB::insert('order_log', $log_data);
		$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
		while($value = DB::fetch($query)) {
			DB::query("UPDATE ".DB::table('goods')." SET goods_storage=goods_storage+".$value['goods_num'].", goods_salenum=goods_salenum-".$value['goods_num']." WHERE goods_id='".$value['goods_id']."'");
			DB::query("UPDATE ".DB::table('goods_spec')." SET spec_goods_storage=spec_goods_storage+".$value['goods_num'].", spec_salenum=spec_salenum-".$value['goods_num']." WHERE spec_id='".$value['spec_id']."'");
		}
		$coupon = DB::fetch_first("SELECT * FROM ".DB::table('coupon')." WHERE coupon_orderid='$order_id' AND coupon_state=1");
		if(!empty($coupon)) {
			DB::query("UPDATE ".DB::table('coupon')." SET coupon_orderid=0, coupon_state=0 WHERE coupon_id='".$coupon['coupon_id']."'");
			DB::query("UPDATE ".DB::table('coupon_template')." SET coupon_t_used=coupon_t_used-1 WHERE coupon_t_id='".$coupon['coupon_t_id']."'");
		}
		$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=2 AND use_id='$order_id' AND red_state=1");
		if(!empty($red)) {
			DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
			DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	public function receiveOp() {
		$order_id = empty($_POST['order_id']) ? 0 : intval($_POST['order_id']);
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
		if(empty($order) || $order['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'订单不存在', 'data'=>array())));
		}			
		if($order['order_state'] != '30') {
			exit(json_encode(array('code'=>'1', 'msg'=>'订单不能收货', 'data'=>array())));
		}
		DB::update('order', array('order_state'=>40), array('order_id'=>$receive_id));
		$log_data = array();
		$log_data['order_id'] = $receive_id;
		$log_data['order_state'] = 40;
		$log_data['order_intro'] = '订单收货';	
		$log_data['log_time'] = time();
		DB::insert('order_log', $log_data);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	public function commentOp() {
		$order_id = empty($_POST['order_id']) ? 0 : intval($_POST['order_id']);
		$result = empty($_POST['comment_score']) ? '' : $_POST['comment_score'];
		$comment_score_array = explode('|', $result);
		foreach($comment_score_array as $key => $value) {
			$result = explode(',', $value);
			if(!empty($result[0])) {
				$comment_score[$result[0]] = $result[1];
			}
		}
		$result = empty($_POST['comment_content']) ? '' : $_POST['comment_content'];
		$comment_content_array = explode('|', $result);
		foreach($comment_content_array as $key => $value) {
			$result = explode('@', $value);
			if(!empty($result[0])) {
				$comment_content[$result[0]] = $result[1];
			}
		}
		$star_array = array('1', '2', '3', '4', '5');
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
		if(empty($order) || $order['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'订单不存在', 'data'=>array())));
		}
		if($order['order_state'] != '40') {
			exit(json_encode(array('code'=>'1', 'msg'=>'订单不能评价', 'data'=>array())));
		}
		if(!empty($order['comment_state'])) {
			exit(json_encode(array('code'=>'1', 'msg'=>'您已经评价过了', 'data'=>array())));
		}
		$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='$order_id'");
		while($value = DB::fetch($query)) {
			if($comment_content[$value['rec_id']]) {
				$comment_score[$value['rec_id']] = !in_array($comment_score[$value['rec_id']], $star_array) ? 1 : $comment_score[$value['rec_id']];
				$data = array(
					'goods_id' => $value['goods_id'],
					'member_id' => $order['member_id'],
					'spec_id' => $value['spec_id'],
					'order_id' => $order['order_id'],
					'comment_score' => $comment_score[$value['rec_id']],
					'comment_content' => $comment_content[$value['rec_id']],
					'comment_time' => time(),
				);
				$comment_id = DB::insert('goods_comment', $data , 1);
				if(!empty($comment_id)) {
					DB::query("UPDATE ".DB::table('goods')." SET goods_score=goods_score+".$comment_score[$value['rec_id']].", goods_commentnum=goods_commentnum+1 WHERE goods_id='".$value['goods_id']."'");
				}
			}
		}
		if(empty($comment_id)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请至少写点你的感受', 'data'=>array())));
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
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	function book_cancelOp() {
		$book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
		if(empty($book) || $book['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不存在', 'data'=>array())));
		}	
		if($book['book_state'] != '10') {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不能取消', 'data'=>array())));	
		}
		DB::update('nurse_book', array('book_state'=>0), array('book_id'=>$book_id));
		$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=1 AND use_id='$book_id' AND red_state=1");
		if(!empty($red)) {
			DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
			DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	function book_refundOp() {
		$book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
		$refund_reason = empty($_POST['refund_reason']) ? '' : $_POST['refund_reason'];
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
		if(empty($book) || $book['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不存在', 'data'=>array())));
		}	
		if($book['book_state'] != '20') {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不能退款', 'data'=>array())));
		}
		if(time() >= $book['work_time']+604800) {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不能退款', 'data'=>array())));
		}
		if(empty($refund_reason)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入退款原因', 'data'=>array())));
		}
		$book_data = array();
		$book_data['refund_state'] = 1;				
		$book_data['refund_amount'] = $book['book_amount'];
		$book_data['refund_reason'] = $refund_reason;
		DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	function book_finishOp() {
		$book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
		if(empty($book) || $book['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不存在', 'data'=>array())));
		}	
		if($book['book_state'] != '20') {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不能完成', 'data'=>array())));
		}
		DB::update('nurse_book', array('book_state'=>30, 'finish_time'=>time()), array('book_id'=>$book_id));
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
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	public function book_commentOp() {
		$book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
		$star_array = array('1', '2', '3', '4', '5');
		$comment_level = !in_array($_POST['comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['comment_level'];			
		$comment_honest_star = !in_array($_POST['comment_honest_star'], $star_array) ? 1 : intval($_POST['comment_honest_star']);
		$comment_love_star = !in_array($_POST['comment_love_star'], $star_array) ? 1 : intval($_POST['comment_love_star']);
		$comment_content = empty($_POST['comment_content']) ? '' : $_POST['comment_content'];
		$tag_ids = empty($_POST['tag_ids']) ? array() : explode(',', $_POST['tag_ids']);
		$comment_image = empty($_POST['comment_image']) ? array() : $_POST['comment_image'];
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
		if(empty($book) || $book['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不存在', 'data'=>array())));
		}
		if(!in_array($book['book_state'], array('20','30'))) {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不能评价', 'data'=>array())));	
		}
		if(!empty($book['comment_state'])) {
			exit(json_encode(array('code'=>'1', 'msg'=>'您已经评价过了', 'data'=>array())));
		}
		if(empty($comment_level)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择满意度评分', 'data'=>array())));
		}
		if(empty($comment_content)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请至少写点你的服务感受', 'data'=>array())));
		}
		$query = DB::query("SELECT * FROM ".DB::table('nurse_tag'));
		while($value = DB::fetch($query)) {
			$tag_list[$value['tag_id']] = $value;
		}
		$comment_tags = array();
		$comment_score = $comment_honest_star+$comment_love_star;
		foreach($tag_ids as $key => $value) {
			if(!empty($tag_list[$value])) {
				$comment_tags[] = $tag_list[$value]['tag_name'];
				$comment_score += $tag_list[$value]['tag_score'];
			}
		}
		$data = array(
			'nurse_id' => $book['nurse_id'],
			'member_id' => $book['member_id'],
			'book_id' => $book['book_id'],
			'comment_level' => $comment_level,
			'comment_honest_star' => $comment_honest_star,
			'comment_love_star' => $comment_love_star,
			'comment_tags' => empty($comment_tags) ? '' : serialize($comment_tags),
			'comment_score' => $comment_score,
			'comment_image' => empty($comment_image) ? '' : serialize($comment_image),
			'comment_content' => $comment_content,
			'comment_time' => time(),
		);
		$comment_id = DB::insert('nurse_comment', $data , 1);
		if(!empty($comment_id)) {
			DB::update('nurse_book', array('comment_state'=>1, 'comment_time'=>time()), array('book_id'=>$book['book_id']));
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
			$nurse['nurse_tags'] = empty($nurse['nurse_tags']) ? array() : unserialize($nurse['nurse_tags']);
			$nurse_tags = array_merge($nurse['nurse_tags'], $comment_tags);
			$nurse_score = $nurse['nurse_score']+$comment_score;
			$query = DB::query("SELECT * FROM ".DB::table('nurse_grade')." ORDER BY nurse_score DESC");
			$grade_id = 0;
			while($value = DB::fetch($query)) {
				if($value['nurse_score'] < $nurse_score) {
					$grade_id = $value['grade_id'];
					break;
				}
			}
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_tags='".(empty($nurse_tags) ? '' : serialize($nurse_tags))."', grade_id=$grade_id, nurse_score=nurse_score+$comment_score, nurse_commentnum=nurse_commentnum+1 WHERE nurse_id='".$nurse['nurse_id']."'");
			//养老金收益
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
			$this->setting['second_oldage_rate'] = floatval($this->setting['second_oldage_rate']);
			if($this->setting['second_oldage_rate'] > 0) {
				$oldage_price = priceformat($this->setting['second_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'comment',					
					'oldage_type' => 1,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']+$oldage_price,
					'oldage_desc' => '您评价了阿姨看护'.$nurse['nurse_name'].'，获得'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
			}
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}
	}
	
	public function bookOp() {
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
		if(!empty($nurse)) {
			$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
			if($page < 1) $page = 1;
			$perpage = 10;
			$start = ($page-1)*$perpage;
			$book_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='".$nurse['nurse_id']."'");
			$query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE nurse_id='".$nurse['nurse_id']."' LIMIT $start, $perpage");
			while($value = DB::fetch($query)) {
				$agent_ids[] = $value['agent_id'];
				$value['book_service'] = empty($value['book_service']) ? array() : unserialize($value['book_service']);
				$value['add_time'] = date('Y-m-d H:i', $value['add_time']);
				$value['payment_time'] = date('Y-m-d H:i', $value['payment_time']);
				$value['finish_time'] = date('Y-m-d H:i', $value['finish_time']);
				$value['comment_time'] = date('Y-m-d H:i', $value['comment_time']);
				$book_list[] = $value;
			}
			if(!empty($agent_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
				while($value = DB::fetch($query)) {
					$agent_list[$value['agent_id']] = $value['agent_name'];
				}
			}
			foreach($book_list as $key => $value) {
				$book_list[$key]['agent_name'] = $agent_list[$value['agent_id']];
			}
		}
		$data = array(
			'book_count' => $book_count,
			'book_list' => $book_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>