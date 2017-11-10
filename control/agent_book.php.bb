<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_bookControl extends BaseAgentControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=agent_book";
		$wheresql = " WHERE agent_id='$this->agent_id'";
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
		include(template('agent_book'));
//        exit(json_encode(array('done'=>'true','count'=>$count)));
	}
	
	public function refundOp() {
		if(submitcheck()) {
			$refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
			$refund_amount = empty($_POST['refund_amount']) ? 0 : floatval($_POST['refund_amount']);
			$refund_state = !in_array($_POST['refund_state'], array('1', '2')) ? '' : $_POST['refund_state'];
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$refund_id'");
			if(empty($book) || $book['agent_id'] != $this->agent_id) {
				exit(json_encode(array('msg'=>'预约单不存在')));
			}	
			if($book['book_state'] != '20') {
				exit(json_encode(array('msg'=>'预约单不能退款')));	
			}
			if($book['refund_state'] != 1) {
				exit(json_encode(array('msg'=>'预约单已经退款处理了')));	
			}
			if($refund_amount <= 0) {
				exit(json_encode(array('msg'=>'请输入退款金额')));
			}
			if($refund_amount > $book['book_amount']) {
				exit(json_encode(array('msg'=>'退款金额不能大于订单金额')));
			}
			if(empty($refund_state)) {
				exit(json_encode(array('msg'=>'请选择处理方式')));
			}
			if($refund_state == 2) {
				$book_data = array();
				$book_data['refund_state'] = 2;
				$book_data['refund_time'] = time();
				DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
				exit(json_encode(array('done'=>'true')));	
			}
			$refund_sn = date('Ymd').random(8, 1);
			if($book['payment_code'] == 'alipay') {
				require_once MALL_ROOT.'/api/alipay/alipay.php';
				$param = array();
				$param['batch_no'] = $refund_sn;
				$param['refund_date'] = date('Y-m-d H:i:s');
				$param['transaction_id'] = $book['transaction_id'];
				$param['refund_amount'] = $refund_amount;
				$requesturl = get_refundurl($param);
				$result = geturlfile($requesturl);
				$result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
				$is_success = $result->is_success;
				if($is_success == 'F') {
					exit(json_encode(array('msg' => '退款失败')));	
				}
			} elseif($book['payment_code'] == 'weixin') {
				require_once MALL_ROOT.'/api/weixin/weixin.php';
				$refundOrder = new Refund_pub();
				$refundOrder->setParameter("transaction_id", $book['transaction_id']);
				$refundOrder->setParameter("out_refund_no", $refund_sn);
				$refundOrder->setParameter("total_fee", $book['book_amount']*100);
				$refundOrder->setParameter("refund_fee", $refund_amount*100);
				$refundOrder->setParameter("op_user_id", MCHID);
				$result = $refundOrder->getResult();
				if($result['return_code'] == 'FAIL' || $result['result_code'] == 'FAIL') {
					exit(json_encode(array('msg' => '退款失败')));
				}
			} elseif($book['payment_code'] == 'predeposit') {
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
				$data = array(
					'pdl_memberid' => $member['member_id'],
					'pdl_memberphone' => $member['member_phone'],
					'pdl_stage' => 'book',
					'pdl_type' => 1,
					'pdl_price' => $refund_amount,
					'pdl_predeposit' => $member['available_predeposit']+$refund_amount,
					'pdl_desc' => '预约退款，预约单号: '.$book['book_sn'],
					'pdl_addtime' => time(),
				);
				DB::insert('pd_log', $data);
				DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+".$refund_amount." WHERE member_id='".$member['member_id']."'");
			} else {
				exit(json_encode(array('msg' => '退款失败')));
			}
			$book_data = array();
			$book_data['refund_sn'] = $refund_sn;
			$book_data['refund_amount'] = $refund_amount;				
			$book_data['book_state'] = 0;
			$book_data['refund_time'] = time();
			DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
			DB::query("UPDATE ".DB::table('nurse')." SET work_state=0 WHERE nurse_id='".$book['nurse_id']."'");
			//红包
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=1 AND use_id='$refund_id' AND red_state=1");
			if(!empty($red)) {
				DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
			}
			//家政人员收益统计
			$profit_data = array(
				'nurse_id' => $book['nurse_id'],
				'agent_id' => $book['agent_id'],
				'profit_stage' => 'order',
				'profit_type' => 0,
				'profit_amount' => $refund_amount,
				'profit_desc' => '预约退款，预约单号：'.$book['book_sn'],
				'is_freeze' => 0,
				'book_id' => $book['book_id'],
				'book_sn' => $book['book_sn'],
				'add_time' => time(),
			);
			DB::insert('nurse_profit', $profit_data);
			DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount-".$refund_amount.", pool_amount=pool_amount-".$refund_amount." WHERE nurse_id='".$book['nurse_id']."'");
			//养老金收益
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
			$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
			if($this->setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($refund_amount*$this->setting['first_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'consume',					
					'oldage_type' => 0,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']-$oldage_price,
					'oldage_desc' => '预约退款，扣除'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount-$oldage_price WHERE member_id='".$member['member_id']."'");
			}		
			//家政人员销售统计
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_salenum=nurse_salenum-1 WHERE nurse_id='".$book['nurse_id']."'");
			$date = date('Ymd', $book['add_time']);
			$nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='".$book['nurse_id']."' AND date='$date'");
			if(!empty($nurse_stat)) {
				$nurse_stat_array = array(
					'salenum' => $nurse_stat['salenum']-1,
				);
				DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$book['nurse_id'], 'date'=>$date));
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}	
	}
}

?>