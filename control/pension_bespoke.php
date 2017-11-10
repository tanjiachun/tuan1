<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pension_bespokeControl extends BasePensionControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=pension_bespoke";
		$wheresql = " WHERE pension_id='$this->pension_id'";
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
			$wheresql .= " AND (bespoke_name like '%".$search_name."%' AND bespoke_phone like '%".$search_name."%' AND bespoke_sn like '%".$search_name."%')";
		}
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
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('pension_bespoke'));
	}
	
	public function refundOp() {
		if(submitcheck()) {
			$refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
			$refund_amount = empty($_POST['refund_amount']) ? 0 : floatval($_POST['refund_amount']);
			$refund_state = !in_array($_POST['refund_state'], array('1', '2')) ? '' : $_POST['refund_state'];
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_id='$refund_id'");
			if(empty($bespoke) || $bespoke['pension_id'] != $this->pension_id) {
				exit(json_encode(array('msg'=>'预约单不存在')));
			}	
			if($bespoke['bespoke_state'] != '20') {
				exit(json_encode(array('msg'=>'预约单不能退款')));	
			}
			if($bespoke['refund_state'] != 1) {
				exit(json_encode(array('msg'=>'预约单已经退款处理了')));	
			}
			if($refund_amount <= 0) {
				exit(json_encode(array('msg'=>'请输入退款金额')));
			}
			if($refund_amount > $bespoke['bespoke_amount']) {
				exit(json_encode(array('msg'=>'退款金额不能大于订单金额')));
			}
			if(empty($refund_state)) {
				exit(json_encode(array('msg'=>'请选择处理方式')));
			}
			if($refund_state == 2) {
				$bespoke_data = array();
				$bespoke_data['refund_state'] = 2;
				$bespoke_data['refund_time'] = time();
				DB::update('pension_bespoke', $bespoke_data, array('bespoke_id'=>$bespoke['bespoke_id']));
				exit(json_encode(array('done'=>'true')));	
			}
			$refund_sn = date('Ymd').random(8, 1);
			if($bespoke['payment_code'] == 'alipay') {
				require_once MALL_ROOT.'/api/alipay/alipay.php';
				$param = array();
				$param['batch_no'] = $refund_sn;
				$param['refund_date'] = date('Y-m-d H:i:s');
				$param['transaction_id'] = $bespoke['transaction_id'];
				$param['refund_amount'] = $refund_amount;
				$requesturl = get_refundurl($param);
				$result = geturlfile($requesturl);
				$result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
				$is_success = $result->is_success;
				if($is_success == 'F') {
					exit(json_encode(array('msg' => '退款失败')));	
				}
			} elseif($bespoke['payment_code'] == 'weixin') {
				require_once MALL_ROOT.'/api/weixin/weixin.php';
				$refundOrder = new Refund_pub();
				$refundOrder->setParameter("transaction_id", $bespoke['transaction_id']);
				$refundOrder->setParameter("out_refund_no", $refund_sn);
				$refundOrder->setParameter("total_fee", $bespoke['bespoke_amount']*100);
				$refundOrder->setParameter("refund_fee", $refund_amount*100);
				$refundOrder->setParameter("op_user_id", MCHID);
				$result = $refundOrder->getResult();
				if($result['return_code'] == 'FAIL' || $result['result_code'] == 'FAIL') {
					exit(json_encode(array('msg' => '退款失败')));
				}
			} elseif($bespoke['payment_code'] == 'predeposit') {
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$bespoke['member_id']."'");
				$data = array(
					'pdl_memberid' => $member['member_id'],
					'pdl_memberphone' => $member['member_phone'],
					'pdl_stage' => 'bespoke',
					'pdl_type' => 1,
					'pdl_price' => $refund_amount,
					'pdl_predeposit' => $member['available_predeposit']+$refund_amount,
					'pdl_desc' => '预定退款，预定单号: '.$bespoke['bespoke_sn'],
					'pdl_addtime' => time(),
				);
				DB::insert('pd_log', $data);
				DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+".$refund_amount." WHERE member_id='".$member['member_id']."'");
			} else {
				exit(json_encode(array('msg' => '退款失败')));
			}
			$bespoke_data = array();
			$bespoke_data['refund_sn'] = $refund_sn;
			$bespoke_data['refund_amount'] = $refund_amount;				
			$bespoke_data['bespoke_state'] = 0;
			$bespoke_data['refund_time'] = time();
			DB::update('pension_bespoke', $bespoke_data, array('bespoke_id'=>$bespoke['bespoke_id']));
			//红包
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=3 AND use_id='$refund_id' AND red_state=1");
			if(!empty($red)) {
				DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
			}
			//机构收益统计
			$profit_data = array(
				'pension_id' => $bespoke['pension_id'],
				'profit_stage' => 'order',
				'profit_type' => 0,
				'profit_amount' => $refund_amount,
				'profit_desc' => '预定退款，预定单号：'.$bespoke['bespoke_sn'],
				'is_freeze' => 0,
				'bespoke_id' => $bespoke['bespoke_id'],
				'bespoke_sn' => $bespoke['bespoke_sn'],
				'add_time' => time(),
			);
			DB::insert('pension_profit', $profit_data);
			DB::query("UPDATE ".DB::table('pension')." SET plat_amount=plat_amount-".$refund_amount.", pool_amount=pool_amount-".$refund_amount." WHERE pension_id='".$bespoke['pension_id']."'");
			//养老金收益
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$bespoke['member_id']."'");
			$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
			if($this->setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($refund_amount*$this->setting['first_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'consume',
					'oldage_type' => 0,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']-$oldage_price,
					'oldage_desc' => '预定退款，扣除'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount-$oldage_price WHERE member_id='".$member['member_id']."'");
			}
			//机构销售统计
			DB::query("UPDATE ".DB::table('pension')." SET pension_salenum=pension_salenum-1 WHERE pension_id='".$bespoke['pension_id']."'");
			$date = date('Ymd', $bespoke['add_time']);
			$pension_stat = DB::fetch_first("SELECT * FROM ".DB::table('pension_stat')." WHERE pension_id='".$bespoke['pension_id']."' AND date='$date'");
			if(!empty($pension_stat)) {
				$pension_stat_array = array(
					'salenum' => $pension_stat['salenum']-$bespoke['bed_number'],
				);
				DB::update('pension_stat', $pension_stat_array, array('pension_id'=>$bespoke['pension_id'], 'date'=>$date));
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
}

?>