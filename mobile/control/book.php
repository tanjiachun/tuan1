<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class bookControl extends BaseMobileControl {
    public function indexOp(){
        $nurse_id=empty($_POST['nurse_id']) ?  0 : intval($_POST['nurse_id']);
        $nurse_book=DB::result_first("SELECT * FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND nurse_id='$nurse_id' AND book_state=10");
        if(!empty($nurse_book)){
            exit(json_encode(array('code'=>1, 'msg'=>'您已经预约过该家政人员，请取消预约后重试')));
        }
        $agent_id=empty($_POST['agent_id']) ?  0 : intval($_POST['agent_id']);
        $nurse_type=empty($_POST['nurse_type']) ?  0 : intval($_POST['nurse_type']);
        $book_phone=empty($_POST['book_phone']) ? '' : $_POST['book_phone'];
        $book_details=empty($_POST['book_details']) ? '' : $_POST['book_details'];
        $work_duration=empty($_POST['work_duration']) ?  0 : intval($_POST['work_duration']);
        $work_duration_days=empty($_POST['work_duration_days']) ?  0 : intval($_POST['work_duration_days']);
        $work_duration_hours=empty($_POST['work_duration_hours']) ?  0 : intval($_POST['work_duration_hours']);
        $work_duration_mins=empty($_POST['work_duration_mins']) ?  0 : intval($_POST['work_duration_mins']);
        $work_area=empty($_POST['work_area']) ?  0 : intval($_POST['work_area']);
        $work_person=empty($_POST['work_person']) ?  0 : intval($_POST['work_person']);
        $work_machine=empty($_POST['work_machine']) ?  0 : intval($_POST['work_machine']);
        $work_cars=empty($_POST['work_cars']) ?  0 : intval($_POST['work_cars']);
        $car_price=empty($_POST['car_price']) ?  0 : intval($_POST['car_price']);
        $work_students=empty($_POST['work_students']) ?  0 : intval($_POST['work_students']);
        $service_price=empty($_POST['service_price']) ?  0 : intval($_POST['service_price']);
        $nurse_price=empty($_POST['nurse_price']) ?  0 : intval($_POST['nurse_price']);
        $book_address=empty($_POST['book_address']) ? '' : $_POST['book_address'];
        $service_member_phone=empty($_POST['service_member_phone']) ? '' : $_POST['service_member_phone'];
        $service_member_name=empty($_POST['service_member_name']) ? '' : $_POST['service_member_name'];
        $book_message=empty($_POST['book_message']) ? '' : $_POST['book_message'];
        $deposit_amount=empty($_POST['deposit_amount']) ?  0 : intval($_POST['deposit_amount']);
        $member_coin_amount=empty($_POST['member_coin_amount']) ?  0 : intval($_POST['member_coin_amount']);
        $book_amount=empty($_POST['book_amount']) ?  0 : intval($_POST['book_amount']);

        $invoice_state=empty($_POST['invoice_state']) ? 0 : $_POST['invoice_state'];
        $invoice_type=empty($_POST['invoice_type']) ? '' : $_POST['invoice_type'];
        $invoice_title=empty($_POST['invoice_title']) ? '' : $_POST['invoice_title'];
        $invoice_content=empty($_POST['invoice_content']) ? '' : $_POST['invoice_content'];
        $invoice_membername=empty($_POST['invoice_membername']) ? '' : $_POST['invoice_membername'];
        $invoice_provinceid=empty($_POST['invoice_provinceid']) ? 0 : intval($_POST['invoice_provinceid']);
        $invoice_cityid=empty($_POST['invoice_cityid']) ? 0 : intval($_POST['invoice_cityid']);
        $invoice_areaid=empty($_POST['invoice_areaid']) ? 0 : intval($_POST['invoice_areaid']);
        $invoice_address=empty($_POST['invoice_address']) ? '' : $_POST['invoice_address'];
        $unit_name=empty($_POST['unit_name']) ? '' : $_POST['unit_name'];
        $invoice_code=empty($_POST['invoice_code']) ? '' : $_POST['invoice_code'];
        $invoice_unit_membername=empty($_POST['invoice_unit_membername']) ? '' : $_POST['invoice_unit_membername'];
        $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_provinceid'");
        $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_cityid'");
        $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_areaid'");
        $invoice_areainfo = $member_provincename.$member_cityname.$member_areaname;
        $invoice_data=array(
            'member_id'=>$this->member_id,
            'invoice_type'=>$invoice_type,
            'invoice_title'=>$invoice_title,
            'invoice_content'=>$invoice_content,
            'invoice_membername'=>$invoice_membername,
            'invoice_provinceid'=>$invoice_provinceid,
            'invoice_cityid'=>$invoice_cityid,
            'invoice_areaid'=>$invoice_areaid,
            'invoice_areainfo'=>$invoice_areainfo,
            'invoice_address'=>$invoice_address,
            'unit_name'=>$unit_name,
            'invoice_code'=>$invoice_code,
            'invoice_unit_membername'=>$invoice_unit_membername,
            'add_time'=>time()
        );
        $invoice_id=DB::insert('invoice',$invoice_data,1);
        if(empty($nurse_id)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请选择要预约的家政人员')));
        }
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请选择要预约的家政人员')));
        }
        if($nurse['state_cideci'] == 2 || $nurse['state_cideci']==4) {
            exit(json_encode(array('code'=>1, 'msg'=>'该家政人员暂时不能接受预约')));
        }
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(intval($member['member_coin'])<intval($member_coin_amount)){
            exit(json_encode(array('code'=>1, 'msg'=>'团豆豆余额不足')));
        }
        $book_sn = makesn(8);
        $out_sn = date('YmdHis').random(18);
        $data=array(
            'book_sn'=>$book_sn,
            'out_sn'=>$out_sn,
            'member_id' => $this->member_id,
            'member_phone' => $this->member['member_phone'],
            'nurse_id'=>$nurse_id,
            'agent_id'=>$agent_id,
            'nurse_type'=>$nurse_type,
            'book_phone'=>$book_phone,
            'book_details'=>$book_details,
            'work_duration'=>$work_duration,
            'work_duration_days'=>$work_duration_days,
            'work_duration_hours'=>$work_duration_hours,
            'work_duration_mins'=>$work_duration_mins,
            'work_area'=>$work_area,
            'work_person'=>$work_person,
            'work_machine'=>$work_machine,
            'work_cars'=>$work_cars,
            'car_price'=>$car_price,
            'work_students'=>$work_students,
            'service_price'=>$service_price,
            'nurse_price'=>$nurse_price,
            'service_address'=>$book_address,
            'service_member_phone'=>$service_member_phone,
            'service_member_name'=>$service_member_name,
            'deposit_amount'=>$deposit_amount,
            'member_coin_amount'=>$member_coin_amount,
            'book_amount'=>$book_amount,
            'book_message'=>$book_message,
            'invoice_type'=>$invoice_type,
            'invoice_id'=>$invoice_id,
            'book_state'=>10,
            'add_time'=>time()
        );
        $book_id = DB::insert('nurse_book', $data, 1);
        if(!empty($book_id)){
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            $coin_data=array(
                'member_id'=>$this->member_id,
                'book_id'=>$book_id,
                'coin_count'=>-$member_coin_amount,
                'get_type'=>'discount',
                'get_state'=>1,
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_coin', $coin_data);
            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin-$member_coin_amount WHERE member_id='$this->member_id'");
            exit(json_encode(array('code'=>0, 'book_sn'=>$book_sn)));
        }else{
            exit(json_encode(array('code'=>1, 'msg'=>'网路不稳定，请稍候重试')));
        }
    }
//	public function indexOp() {
//		$error_data = (object)array();
//		$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
//		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
//		if(empty($nurse)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要预约的阿姨', 'data'=>$error_data)));
//		}
//		if($nurse['nurse_state'] != 1) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'阿姨暂时不能接受预约', 'data'=>$error_data)));
//		}
//		$book_phone = empty($_POST['book_phone']) ? '' : $_POST['book_phone'];
//		$work_time = empty($_POST['work_time']) ? 0 : strtotime($_POST['work_time']);
//		$work_duration = empty($_POST['work_duration']) ? 0 : intval($_POST['work_duration']);
//		$service_ids = empty($_POST['service_id']) ? array() : explode(',', $_POST['service_id']);
//		$book_message = empty($_POST['book_message']) ? '' : $_POST['book_message'];
//		$book_hope = empty($_POST['book_hope']) ? '' : $_POST['book_hope'];
//		if(empty($book_phone)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'请输入预约人手机号', 'data'=>$error_data)));
//		}
//		if(!preg_match('/^1[0-9]{10}$/', $book_phone)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'预约人手机号格式不正确', 'data'=>$error_data)));
//		}
//		if(empty($work_time)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'请输入开始服务时间', 'data'=>$error_data)));
//		}
//		if(empty($work_duration)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'请输入服务时长', 'data'=>$error_data)));
//		}
//		if(empty($book_message)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'请介绍下老人的基本情况', 'data'=>$error_data)));
//		}
//		if(empty($book_hope)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'请输入希望阿姨做的事', 'data'=>$error_data)));
//		}
//		$grade = DB::fetch_first("SELECT * FROM ".DB::table('nurse_grade')." WHERE grade_id='".$nurse['grade_id']."'");
//		$deposit_amount = empty($grade['deposit_amount']) ? 200 : $grade['deposit_amount'];
//		$query = DB::query("SELECT * FROM ".DB::table('nurse_service')." ORDER BY service_sort ASC");
//		while($value = DB::fetch($query)) {
//			$service_list[$value['service_id']] = $value;
//		}
//		$service_amount = 0;
//		foreach($service_ids as $key => $value) {
//			if(!empty($service_list[$value])) {
//				$service_amount += $service_list[$value]['service_price'];
//				$book_service[] = $service_list[$value];
//			}
//		}
//		$red_limit = $service_amount+$deposit_amount;
//		$time = time();
//		$red_id = empty($_POST['red_id']) ? 0 : intval($_POST['red_id']);
//		$red_amount = 0;
//		$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE red_id='$red_id'");
//		if(!empty($red) && $red['member_id'] == $this->member_id && in_array($red['red_cate_id'], array('0', '1')) && empty($red['red_state']) && $red['red_starttime'] <= $time && $red['red_endtime'] >= $time) {
//			if($red['red_limit'] > 0) {
//				if($red['red_limit'] <= $red_limit) {
//					$red_amount = $red['red_price'];
//					$book_red_id = $red['red_id'];
//				}
//			} else {
//				$red_amount = $red['red_price'];
//				$book_red_id = $red['red_id'];
//			}
//		}
//		$book_sn = makesn(8);
//		$out_sn = date('YmdHis').random(18);
//		$data = array(
//			'book_sn' => $book_sn,
//			'out_sn' => $out_sn,
//			'nurse_id' => $nurse['nurse_id'],
//			'agent_id' => $nurse['agent_id'],
//			'member_id' => $this->member_id,
//			'member_phone' => $this->member['member_phone'],
//			'book_name' => $book_name,
//			'book_phone' => $book_phone,
//			'work_time' => $work_time,
//			'work_duration' => $work_duration,
//			'book_service' => empty($book_service) ? '' : serialize($book_service),
//			'book_message' => $book_message,
//			'book_hope' => $book_hope,
//			'service_amount' => $service_amount,
//			'deposit_amount' => $deposit_amount,
//			'red_amount' => $red_amount,
//			'book_amount' => $service_amount+$deposit_amount-$red_amount,
//			'book_state' => 10,
//			'add_time' => time(),
//		);
//		$book_id = DB::insert('nurse_book', $data, 1);
//		if(!empty($book_id)) {
//			if(!empty($book_red_id)) {
//				DB::update('red', array('use_type'=>1, 'use_id'=>$book_id, 'red_state'=>1),array('red_id'=>$red['red_id']));
//				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used+1 WHERE red_t_id='".$red['red_t_id']."'");
//			}
//			DB::query("UPDATE ".DB::table('nurse')." SET nurse_booknum=nurse_booknum+1 WHERE nurse_id='".$nurse['nurse_id']."'");
//			$date = date('Ymd');
//			$nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='".$nurse['nurse_id']."' AND date='$date'");
//			if(empty($nurse_stat)) {
//				$nurse_stat_array = array(
//					'nurse_id' => $nurse['nurse_id'],
//					'date' => $date,
//					'booknum' => 1,
//				);
//				DB::insert('nurse_stat', $nurse_stat_array);
//			} else {
//				$nurse_stat_array = array(
//					'booknum' => $nurse_stat['booknum']+1,
//				);
//				DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$nurse['nurse_id'], 'date'=>$date));
//			}
//			$data = array(
//				'book_sn' => $book_sn,
//			);
//			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
//		} else {
//			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>$error_data)));
//		}
//	}
	
	public function paymentOp() {
		$error_data = (object)array();
        $this->check_authority();
//        $this->member_id=376;
		$book_sn = empty($_POST['book_sn']) ? '' : $_POST['book_sn'];
//        $book_sn='82017101916414249975053';
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
		if(empty($book) || $book['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单不存在', 'data'=>$error_data)));
		}
		if($book['book_state'] != '10') {
			exit(json_encode(array('code'=>'1', 'msg'=>'预约单还不能支付', 'data'=>$error_data)));
		}
		$payment_code = !in_array($_POST['payment_code'], array('alipay', 'weixin', 'predeposit')) ? 'alipay' : $_POST['payment_code'];
		if($payment_code == 'predeposit') {
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
			if($member['available_predeposit'] < $book['book_amount']) {
				exit(json_encode(array('code'=>'1', 'msg'=>'余额不足，请选择其他支付方式', 'data'=>$error_data)));
			}
			$data = array(
				'pdl_memberid' => $member['member_id'],
				'pdl_memberphone' => $member['member_phone'],
				'pdl_stage' => 'book',
				'pdl_type' => 0,
				'pdl_price' => $book['book_amount'],
				'pdl_predeposit' => $member['available_predeposit']-$book['book_amount'],
				'pdl_desc' => '预约阿姨，预约单号: '.$book['book_sn'],
				'pdl_addtime' => time(),
			);
			DB::insert('pd_log', $data);
			DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit-".$book['book_amount']." WHERE member_id='".$member['member_id']."'");
			$book_data = array();
			$book_data['payment_name'] = '余额支付';
			$book_data['payment_code'] = 'predeposit';				
			$book_data['book_state'] = 20;
			$book_data['payment_time'] = time();
			DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
			DB::query("UPDATE ".DB::table('nurse')." SET work_state=1 WHERE nurse_id='".$book['nurse_id']."'");
			//红包
			$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='reward' ORDER BY red_t_amount DESC");
			while($value = DB::fetch($query)) {
				if($value['red_t_total'] > $value['red_t_giveout'] && $value['red_t_amount'] <= $book['book_amount']) {
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
			//阿姨收益统计
			$profit_data = array(
				'nurse_id' => $book['nurse_id'],
				'agent_id' => $book['agent_id'],
				'profit_stage' => 'order',
				'profit_type' => 1,
				'profit_amount' => $book['book_amount'],
				'profit_desc' => $book['book_name'].'预约阿姨，预约单号：'.$book['book_sn'],
				'is_freeze' => 1,
				'book_id' => $book['book_id'],
				'book_sn' => $book['book_sn'],
				'add_time' => time(),
			);
			DB::insert('nurse_profit', $profit_data);
			DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$book['book_amount'].", pool_amount=pool_amount+".$book['book_amount']." WHERE nurse_id='".$book['nurse_id']."'");
			//养老金收益
			$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
			if($this->setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($book['book_amount']*$this->setting['first_oldage_rate']);
				$oldage_data = array(
					'member_id' => $member['member_id'],
					'oldage_stage' => 'consume',					
					'oldage_type' => 1,
					'oldage_price' => $oldage_price,
					'oldage_balance' => $member['oldage_amount']+$oldage_price,
					'oldage_desc' => '消费了'.$book['book_amount'].'元，获得'.$oldage_price.'元养老金',
					'oldage_addtime' => time(),
				);
				DB::insert('oldage', $oldage_data);
				DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
			}		
			//阿姨销售统计
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_salenum=nurse_salenum+1 WHERE nurse_id='".$book['nurse_id']."'");
			$date = date('Ymd');
			$nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='".$book['nurse_id']."' AND date='$date'");
			if(empty($nurse_stat)) {
				$nurse_stat_array = array(
					'nurse_id' => $book['nurse_id'],
					'date' => $date,
					'salenum' => 1,
				);
				DB::insert('nurse_stat', $nurse_stat_array);
			} else {
				$nurse_stat_array = array(
					'salenum' => $nurse_stat['salenum']+1,
				);
				DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$book['nurse_id'], 'date'=>$date));
			}
			//短信通知
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
			$data = array(
				'member_id' => $nurse['member_id'],
				'message_title' => '您有新的工作了，雇主手机号：'.$book['book_phone'].'，请您尽快打电话联系', 
				'message_content' => '您有新的预订单，单号：'.$book['book_sn'],
				'from_type' => 1,
				'from_id' => $book['book_id'],
				'message_time' => time(),
			);
			DB::insert('message', $data);
			require_once(MALL_ROOT.'/static/sms/sms.php');
			$smsmessage = new PublishBatchSMSMessage($nurse['member_phone'], 'SMS_71005119', array());
			$smsstate = $smsmessage->run();
		} elseif($payment_code == 'alipay') {
			require_once MALL_ROOT.'/api/alipay/alipay.php';
			$args= array(
				'subject' => '我要预约阿姨',
				'body' => '我要预约阿姨',
				'notify_url' => SiteUrl.'/api/alipay/app_book.php',
				'out_trade_no' => $book['out_sn'],
				'total_fee' => $book['book_amount'],
			);
			$url = app_payurl($args);
			$data = array(
				'signurl' => $url,
			);
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
		} elseif($payment_code == 'weixin') {
			require_once MALL_ROOT.'/api/weixin/weixin.php';
			$unifiedOrder = new UnifiedOrder_pub();
			$unifiedOrder->setParameter("body", '我要预约阿姨');
			$unifiedOrder->setParameter("out_trade_no", $book['out_sn']);
			$unifiedOrder->setParameter("total_fee", $book['book_amount']*100);
			$unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/app_book.php');
			$unifiedOrder->setParameter("trade_type", 'APP');				
			$prepay_id = $unifiedOrder->getPrepayId();
			$commonUtil = new Common_util_pub();
			$param = array(
				'appid' => APPID,
				'partnerid' => MCHID,
				'prepayid' => $prepay_id,
				'noncestr' => $commonUtil->createNoncestr(),
				'timestamp' => time(),
				'package' => 'Sign=WXPay',
			);
			$param['sign'] = $commonUtil->getSign($param);
			$result = array(
				'appId' => APPID,
				'partnerId' => MCHID,
				'prepayId' => $param['prepayid'],
				'nonceStr' => $param['noncestr'],
				'timeStamp' => (string)$param['timestamp'],
				'package' => 'Sign=WXPay',
				'sign' => $param['sign'],
			);
//			$data = array(
//				'signurl' => $result,
//			);
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$result)));
		}
	}
	
	public function nurseOp() {
		$error_data = (object)array();
		$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
		$nurse = DB::fetch_first("SELECT nurse_id, agent_id, member_phone, nurse_name, nurse_image, nurse_type, nurse_price, nurse_days, nurse_age, nurse_education, birth_cityname, nurse_cityname, nurse_qa, nurse_qa_image, nurse_content, nurse_tags, nurse_desc, grade_id, nurse_score, nurse_viewnum, nurse_favoritenum, nurse_booknum, nurse_salenum, nurse_commentnum, work_state, nurse_state, nurse_time FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		if(empty($nurse) || $nurse['nurse_state'] != 1) {
			exit(json_encode(array('code'=>'1', 'msg'=>'阿姨已下架', 'data'=>$error_data)));	
		}
		$nurse['nurse_qa'] = empty($nurse['nurse_qa']) ? array() : unserialize($nurse['nurse_qa']);
		$nurse['nurse_qa_image'] = empty($nurse['nurse_qa_image']) ? array() : unserialize($nurse['nurse_qa_image']);
		$nurse['nurse_tags'] = empty($nurse['nurse_tags']) ? array() : unserialize($nurse['nurse_tags']);
		$nurse['nurse_time'] = date('Y-m-d H:i', $nurse['nurse_time']);
		$grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE grade_id='".$nurse['grade_id']."'");
		$nurse['grade_name'] = $grade['grade_name'];
		$nurse['grade_icon'] = $grade['grade_icon'];
		$nurse['deposit_amount'] = $grade['deposit_amount'];
		$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$nurse['agent_id']."'");
		$nurse['agent_name'] = $agent['agent_name'];
		$nurse['agent_qq'] = $agent['agent_qq'];
		$nurse['agent_phone'] = $agent['agent_phone'];
		$query = DB::query("SELECT * FROM ".DB::table('nurse_service')." ORDER BY service_sort ASC");
		while($value = DB::fetch($query)) {
			$service_list[] = $value;
		}
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND red_state=0 AND red_cate_id in ('0', '1') AND red_starttime<=$time AND red_endtime>=$time");
		while($value = DB::fetch($query)) {
			$red_list[] = $value;
		}
		$data = array(
			'nurse' => $nurse,
			'service_list' => empty($service_list) ? array() : $service_list,
			'red_list' => empty($red_list) ? array() : $red_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>