<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class bookControl extends BaseHomeControl {
	public function indexOp() {
		if(empty($this->member_id)) {
			$this->showmessage('您还未登录了', 'index.php?act=login', 'info');
		}
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		if(empty($nurse)) {
			$this->showmessage('请选择要预约的家政人员', 'index.php?act=index&op=nurse', 'info');
		}
		if($nurse['nurse_state'] != 1) {
			$this->showmessage('该家政人员暂时不能接受预约', 'index.php?act=index&op=nurse', 'info');	
		}
		$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$nurse['agent_id']."'");
		$grade = DB::fetch_first("SELECT * FROM ".DB::table('nurse_grade')." WHERE grade_id='".$nurse['grade_id']."'");
		$query = DB::query("SELECT * FROM ".DB::table('nurse_service')." ORDER BY service_sort ASC");
		while($value = DB::fetch($query)) {
			$service_list[] = $value;
		}
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$province_list[] = $value;
		}
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND red_state=0 AND red_cate_id in ('0', '1') AND red_starttime<=$time AND red_endtime>=$time");
		while($value = DB::fetch($query)) {
			if($value['red_limit'] > 0) {
				if($value['red_limit'] <= $grade['deposit_amount']) {
					$red_list[] = $value;
				}
			} else {
				$red_list[] = $value;
			}
		}
		$curmodule = 'home';
		$bodyclass = '';
		include(template('book'));
	}
	public function messageOp() {
		$book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
		$data = array(
			'member_id' => $this->member_id,
			'message_title' => '请等待阿姨联系您，进一步沟通服务细节', 
			'message_content' => '您有未支付预订单，单号：'.$book['book_sn'],
			'from_type' => 1,
			'from_id' => $book['book_id'],
			'message_time' => time(),
		);
		DB::insert('message', $data);
		dsetcookie('mallmessage', '1', 0);
		exit(json_encode(array('done'=>'true')));
	}
	
	public function orderOp() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}
			$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
			$book_phone = empty($_POST['book_phone']) ? '' : $_POST['book_phone'];
			$work_time = empty($_POST['work_time']) ? 0 : strtotime($_POST['work_time']);
			$work_duration = empty($_POST['work_duration']) ? 0 : intval($_POST['work_duration']);
			$service_ids = empty($_POST['book_service']) ? array() : $_POST['book_service'];					
			$book_message = empty($_POST['book_message']) ? '' : $_POST['book_message'];
			$red_id = empty($_POST['red_id']) ? 0 : intval($_POST['red_id']);
			if(empty($nurse_id)) {
				exit(json_encode(array('id'=>'system', 'msg'=>'请选择要预约的家政人员')));
			}
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
			if(empty($nurse)) {
				exit(json_encode(array('id'=>'system', 'msg'=>'请选择要预约的家政人员')));
			}
			if($nurse['nurse_state'] != 1) {
				exit(json_encode(array('id'=>'system', 'msg'=>'该家政人员暂时不能接受预约')));
			}
			if(empty($book_phone)) {
				exit(json_encode(array('id'=>'book_phone', 'msg'=>'请输入预约人手机号')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $book_phone)) {
				exit(json_encode(array('id'=>'book_phone', 'msg'=>'预约人手机号格式不正确')));
			}
			if(empty($work_time)) {
				exit(json_encode(array('id'=>'work_time', 'msg'=>'请输入开始服务时间')));
			}
			if(empty($work_duration)) {
				exit(json_encode(array('id'=>'work_duration', 'msg'=>'请输入服务时长')));
			}
			if(empty($book_message)) {
				exit(json_encode(array('id'=>'book_message', 'msg'=>'请介绍下老人的基本情况')));
			}
			$grade = DB::fetch_first("SELECT * FROM ".DB::table('nurse_grade')." WHERE grade_id='".$nurse['grade_id']."'");
			$deposit_amount = $grade['deposit_amount'];
			$query = DB::query("SELECT * FROM ".DB::table('nurse_service')." ORDER BY service_sort ASC");
			while($value = DB::fetch($query)) {
				$service_list[$value['service_id']] = $value;
			}
			$service_amount = 0;
			foreach($service_ids as $key => $value) {
				if(!empty($service_list[$value])) {
					$service_amount += $service_list[$value]['service_price'];
					$book_service[] = $service_list[$value];
				}
			}
			$time = time();
			$red_amount = 0;
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE red_id='$red_id'");
			if(!empty($red) && $red['member_id'] == $this->member_id && in_array($red['red_cate_id'], array('0', '1')) && empty($red['red_state']) && $red['red_starttime'] <= $time && $red['red_endtime'] >= $time) {
				if($red['red_limit'] > 0) {
					if($red['red_limit'] <= $deposit_amount) {
						$red_amount = $red['red_price'];
						$book_red_id = $red['red_id'];
					}
				} else {
					$red_amount = $red['red_price'];
					$book_red_id = $red['red_id'];
				}
			}
			$book_sn = makesn(8);
			$out_sn = date('YmdHis').random(18);
			$data = array(
				'book_sn' => $book_sn,
				'out_sn' => $out_sn,
				'nurse_id' => $nurse_id,
				'agent_id' => $nurse['agent_id'],
				'member_id' => $this->member_id,
				'member_phone' => $this->member['member_phone'],
				'book_phone' => $book_phone,
				'work_time' => $work_time,
				'work_duration' => $work_duration,
				'book_service' => empty($book_service) ? '' : serialize($book_service),
				'book_message' => $book_message,
				'service_amount' => $service_amount,
				'deposit_amount' => $deposit_amount,
				'red_amount' => $red_amount,
				'book_amount' => $deposit_amount-$red_amount,
				'book_state' => 10,
				'add_time' => time(),
			);
			$book_id = DB::insert('nurse_book', $data, 1);
			if(!empty($book_id)) {
				if(!empty($book_red_id)) {
					DB::update('red', array('use_type'=>1, 'use_id'=>$book_id, 'red_state'=>1),array('red_id'=>$red['red_id']));
					DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used+1 WHERE red_t_id='".$red['red_t_id']."'");
				}
				DB::query("UPDATE ".DB::table('nurse')." SET nurse_booknum=nurse_booknum+1 WHERE nurse_id='$nurse_id'");
				$date = date('Ymd');
				$nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='$nurse_id' AND date='$date'");
				if(empty($nurse_stat)) {
					$nurse_stat_array = array(
						'nurse_id' => $nurse_id,
						'date' => $date,
						'booknum' => 1,
					);
					DB::insert('nurse_stat', $nurse_stat_array);
				} else {
					$nurse_stat_array = array(
						'booknum' => $nurse_stat['booknum']+1,
					);
					DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$nurse_id, 'date'=>$date));
				}
				exit(json_encode(array('done'=>'true', 'book_sn'=>$book_sn)));
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
		}
	}
	public function paymentOp() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}
			$book_sn = empty($_POST['book_sn']) ? '' : $_POST['book_sn'];
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
			if(empty($book) || $book['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预约单不存在')));
			}
			if($book['book_state'] != '10') {
				exit(json_encode(array('msg'=>'预约单还不能支付')));
			}
			$payment_code = !in_array($_POST['payment_code'], array('alipay', 'weixin', 'predeposit')) ? 'alipay' : $_POST['payment_code'];
			if($payment_code == 'predeposit') {
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
				if($member['available_predeposit'] < $book['book_amount']) {
					exit(json_encode(array('msg'=>'余额不足，请选择其他支付方式')));
				}
				$data = array(
					'pdl_memberid' => $member['member_id'],
					'pdl_memberphone' => $member['member_phone'],
					'pdl_stage' => 'book',
					'pdl_type' => 0,
					'pdl_price' => $book['book_amount'],
					'pdl_predeposit' => $member['available_predeposit']-$book['book_amount'],
					'pdl_desc' => '预约家政人员，预约单号: '.$book['book_sn'],
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
				if($book['book_type']!='father'){
				    //团豆豆消费（如果有） 财务表插入数据
                    if(!empty($book['member_coin_amount'])){
                        $finance_coin_data=array(
                            'finance_type'=>'coin',
                            'book_id'=>$book['book_id'],
                            'member_id'=>$book['member_id'],
                            'agent_id'=>$book['agent_id'],
                            'nurse_id'=>$book['nurse_id'],
                            'finance_state'=>1,
                            'finance_amount'=>intval($book['member_coin_amount']/100),
                            'finance_time'=>time()
                        );
                        DB::insert('finance',$finance_coin_data);
                    }
                    //在岗码生成
                    $book_code=num6();
                    $book_code_array=array(
                        'member_id'=>$this->member_id,
                        'book_id'=>$book['book_id'],
                        'book_sn'=>$book['book_sn'],
                        'code_value'=>$book_code,
                        'code_state'=>0,
                        'add_time'=>time()
                    );
                    $book_code_id=DB::insert('book_code',$book_code_array,1);
                    //站内交易消息插入
                    if(!empty($book_code_id)){
                        $message_data=array(
                            'member_id'=>$this->member_id,
                            'book_id'=>$book['book_id'],
                            'book_sn'=>$book['book_sn'],
                            'message_type'=>'deal',
                            'message_content'=>'编号为'.$book['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_code.'请您妥善保管',
                            'add_time'=>time()
                        );
                        DB::insert('system_message',$message_data);
                    }
                    //到岗验证码 短信通知
                    send_work_code($book['member_phone'], 'start_work_code',$book_code);

                    //通知家政人员
                    $content =  array(
                        'code' =>''
                    );
                    if(empty($book['agent_id'])){
//                        send_text_code($book['book_phone'],'personal_order_payment');
                        $text_send = new Simple_SMS($book['book_phone'], 'personal_order_payment', $content);
                    }else{
//                        send_text_code($book['book_phone'],'company_order_payment');
                        $text_send = new Simple_SMS($book['book_phone'], 'company_order_payment', $content);
                    }
                    $send_result = $text_send->async_send();

                    //家政人员收益统计
                    if(empty($book['service_price'])){
                        $nurse_get_amount=floor($book['deposit_amount']*0.93);
                    }else{
                        $nurse_get_amount=intval($book['deposit_amount'])-ceil(intval($book['service_price'])*0.2);
                    }
                    $profit_data = array(
                        'nurse_id' => $book['nurse_id'],
                        'agent_id' => $book['agent_id'],
                        'profit_stage' => 'order',
                        'profit_type' => 1,
                        'profit_amount' => $nurse_get_amount,
                        'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                        'is_freeze' => 1,
                        'book_id' => $book['book_id'],
                        'book_sn' => $book['book_sn'],
                        'add_time' => time(),
                    );
                    DB::insert('nurse_profit', $profit_data);
                    DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$nurse_get_amount.", pool_amount=pool_amount+".$nurse_get_amount." WHERE nurse_id='".$book['nurse_id']."'");
                    //机构收益
                    if(!empty($book['agent_id'])){
                        $agent_profit_data=array(
                            'nurse_id' => $book['nurse_id'],
                            'agent_id' => $book['agent_id'],
                            'profit_stage' => 'order',
                            'profit_type' => 1,
                            'profit_amount' => $nurse_get_amount,
                            'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                            'is_freeze' => 1,
                            'book_id' => $book['book_id'],
                            'book_sn' => $book['book_sn'],
                            'add_time' => time(),
                        );
                        DB::insert('agent_profit', $agent_profit_data);
                    }
                }
                //合并付款
                if($book['book_type']=='father'){
                    $book_ids=explode(',',$book['book_sub_order']);
                    $pay_time=time();
                    for($i=0;$i<count($book_ids);$i++){
                        $books = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_ids[$i]'");
                        //团豆豆消费（如果有） 财务表插入数据
                        if(!empty($books['member_coin_amount'])){
                            $finance_coin_datas=array(
                                'finance_type'=>'coin',
                                'book_id'=>$books['book_id'],
                                'member_id'=>$books['member_id'],
                                'agent_id'=>$books['agent_id'],
                                'nurse_id'=>$books['nurse_id'],
                                'finance_state'=>1,
                                'finance_amount'=>intval($books['member_coin_amount']/100),
                                'finance_time'=>time()
                            );
                            DB::insert('finance',$finance_coin_datas);
                        }
                        //在岗码生成
                        $book_codes=num6();
                        $book_code_datas=array(
                            'member_id'=>$this->member_id,
                            'book_id'=>$books['book_id'],
                            'book_sn'=>$books['book_sn'],
                            'code_value'=>$book_codes,
                            'code_state'=>0,
                            'add_time'=>time()
                        );
                        $book_code_ids=DB::insert('book_code',$book_code_datas,1);
                        //到岗验证码 短信通知
                        send_work_code($books['member_phone'], 'start_work_code',$book_codes);

                        //通知家政人员
                        $content =  array(
                            'code' =>''
                        );
                        if(empty($books['agent_id'])){
//                            send_text_code($books['book_phone'],'personal_order_payment');
                            $text_send = new Simple_SMS($books['book_phone'], 'personal_order_payment', $content);
                        }else{
//                            send_text_code($books['book_phone'],'company_order_payment');
                            $text_send = new Simple_SMS($books['book_phone'], 'company_order_payment', $content);
                        }
                        $send_result = $text_send->async_send();
                        //站内消息插入
                        if(!empty($book_code_ids)){
                            $message_datas=array(
                                'member_id'=>$this->member_id,
                                'book_id'=>$books['book_id'],
                                'book_sn'=>$books['book_sn'],
                                'message_type'=>'deal',
                                'message_content'=>'编号为'.$books['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_codes.'请您妥善保管',
                                'add_time'=>time()
                            );
                            DB::insert('system_message',$message_datas);
                        }
                        DB::query("UPDATE ".DB::table('nurse_book')." SET book_state=20,payment_time=$pay_time WHERE book_id='".$books['book_id']."'");
                        //家政人员收益统计
                        if(empty($books['service_price'])){
                            $nurse_get_amounts=floor($books['deposit_amount']*0.93);
                        }else{
                            $nurse_get_amounts=intval($books['deposit_amount'])-ceil(intval($books['service_price'])*0.2);
                        }
                        $profit_datas = array(
                            'nurse_id' => $books['nurse_id'],
                            'agent_id' => $books['agent_id'],
                            'profit_stage' => 'order',
                            'profit_type' => 1,
                            'profit_amount' => $nurse_get_amounts,
                            'profit_desc' => '预约家政人员，预约单号：'.$books['book_sn'],
                            'is_freeze' => 1,
                            'book_id' => $books['book_id'],
                            'book_sn' => $books['book_sn'],
                            'add_time' => time(),
                        );
                        DB::insert('nurse_profit', $profit_datas);
                        DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$nurse_get_amounts.", pool_amount=pool_amount+".$nurse_get_amounts." WHERE nurse_id='".$books['nurse_id']."'");
                        //机构收益
                        if(!empty($books['agent_id'])){
                            $agent_profit_datas=array(
                                'nurse_id' => $books['nurse_id'],
                                'agent_id' => $books['agent_id'],
                                'profit_stage' => 'order',
                                'profit_type' => 1,
                                'profit_amount' => $nurse_get_amounts,
                                'profit_desc' => '预约家政人员，预约单号：'.$books['book_sn'],
                                'is_freeze' => 1,
                                'book_id' => $books['book_id'],
                                'book_sn' => $books['book_sn'],
                                'add_time' => time(),
                            );
                            DB::insert('agent_profit', $agent_profit_datas);
                        }
                    }
                }
                //雇主团豆豆收益
                $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
                $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
                $member_add_coin=50;
                $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='payment' AND get_time=$now_date");
                if($member_add_coin+$member_get_coin>=200){
                    $member_add_coin=200-$member_get_coin;
                }
                $member_coin_data=array(
                    'member_id'=>$book['member_id'],
                    'book_id'=>$book['book_id'],
                    'coin_count'=>$member_add_coin,
                    'get_type'=>'payment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin', $member_coin_data);
                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
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
				//家政人员销售统计
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
			} else {
				DB::update('nurse_book', array('payment_code'=>$payment_code), array('book_id'=>$book['book_id']));
			}
			exit(json_encode(array('done'=>'true', 'book_sn'=>$book_sn)));
		} else {
			if(empty($this->member_id)) {
				$this->showmessage('您还未登录了', 'index.php?act=login', 'info');
			}
			$book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
			if(empty($book) || $book['member_id'] != $this->member_id) {
				$this->showmessage('预约单不存在', 'index.php?act=member_book', 'error');
			}
			if($book['book_state'] != '10') {
				$this->showmessage('预约单还不能支付', 'index.php?act=member_book', 'info');
			}
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
			$curmodule = 'home';
			$bodyclass = '';
			include(template('book_payment'));
		}
	}
	public function alipayOp() {
		require_once MALL_ROOT.'/api/alipay/alipay.php';
		$notifydata = trade_notifycheck();
		if($notifydata['validator']) {
			$out_sn = $notifydata['order_no'];
			$transaction_id = $notifydata['trade_no'];
			$book_amount = $notifydata['price'];
			$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE out_sn='$out_sn'");
			if($book['book_state'] == 10) {
				if(floatval($book_amount) == floatval($book['book_amount'])) {
					$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
					$book_data = array();
					$book_data['transaction_id'] = $transaction_id;
					$book_data['payment_name'] = '支付宝';
					$book_data['payment_code'] = 'alipay';				
					$book_data['book_state'] = 20;
					$book_data['payment_time'] = time();
					DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
					DB::query("UPDATE ".DB::table('nurse')." SET work_state=1 WHERE nurse_id='".$book['nurse_id']."'");
                    if($book['book_type']!='father'){
                        //团豆豆消费（如果有） 财务表插入数据
                        if(!empty($book['member_coin_amount'])){
                            $finance_coin_data=array(
                                'finance_type'=>'coin',
                                'book_id'=>$book['book_id'],
                                'member_id'=>$book['member_id'],
                                'agent_id'=>$book['agent_id'],
                                'nurse_id'=>$book['nurse_id'],
                                'finance_state'=>1,
                                'finance_amount'=>intval($book['member_coin_amount']/100),
                                'finance_time'=>time()
                            );
                            DB::insert('finance',$finance_coin_data);
                        }
                        //在岗码生成
                        $book_code=num6();
                        $book_code_array=array(
                            'member_id'=>$this->member_id,
                            'book_id'=>$book['book_id'],
                            'book_sn'=>$book['book_sn'],
                            'code_value'=>$book_code,
                            'code_state'=>0,
                            'add_time'=>time()
                        );
                        $book_code_id=DB::insert('book_code',$book_code_array,1);
                        //到岗验证码 短信通知
                        send_work_code($book['member_phone'], 'start_work_code',$book_code);

                        //通知家政人员
                        $content =  array(
                            'code' =>''
                        );
                        if(empty($book['agent_id'])){
//                        send_text_code($book['book_phone'],'personal_order_payment');
                            $text_send = new Simple_SMS($book['book_phone'], 'personal_order_payment', $content);
                        }else{
//                        send_text_code($book['book_phone'],'company_order_payment');
                            $text_send = new Simple_SMS($book['book_phone'], 'company_order_payment', $content);
                        }
                        $send_result = $text_send->async_send();
                        //站内交易消息插入
                        if(!empty($book_code_id)){
                            $message_data=array(
                                'member_id'=>$this->member_id,
                                'book_id'=>$book['book_id'],
                                'book_sn'=>$book['book_sn'],
                                'message_type'=>'deal',
                                'message_content'=>'编号为'.$book['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_code.'请您妥善保管',
                                'add_time'=>time()
                            );
                            DB::insert('system_message',$message_data);
                        }
                        //家政人员收益统计
                        if(empty($book['service_price'])){
                            $nurse_get_amount=floor($book['deposit_amount']*0.93);
                        }else{
                            $nurse_get_amount=intval($book['deposit_amount'])-ceil(intval($book['service_price'])*0.2);
                        }
                        $profit_data = array(
                            'nurse_id' => $book['nurse_id'],
                            'agent_id' => $book['agent_id'],
                            'profit_stage' => 'order',
                            'profit_type' => 1,
                            'profit_amount' => $nurse_get_amount,
                            'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                            'is_freeze' => 1,
                            'book_id' => $book['book_id'],
                            'book_sn' => $book['book_sn'],
                            'add_time' => time(),
                        );
                        DB::insert('nurse_profit', $profit_data);
                        DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$nurse_get_amount.", pool_amount=pool_amount+".$nurse_get_amount." WHERE nurse_id='".$book['nurse_id']."'");
                        //机构收益
                        if(!empty($book['agent_id'])){
                            $agent_profit_data=array(
                                'nurse_id' => $book['nurse_id'],
                                'agent_id' => $book['agent_id'],
                                'profit_stage' => 'order',
                                'profit_type' => 1,
                                'profit_amount' => $nurse_get_amount,
                                'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                                'is_freeze' => 1,
                                'book_id' => $book['book_id'],
                                'book_sn' => $book['book_sn'],
                                'add_time' => time(),
                            );
                            DB::insert('agent_profit', $agent_profit_data);
                        }
                        //财务表插入数据（收入）
                        $finance_data1=array(
                            'finance_type'=>'book',
                            'book_id'=>$book['book_id'],
                            'member_id'=>$this->member_id,
                            'finance_state'=>0,
                            'finance_amount'=>$book['deposit_amount'],
                            'finance_time'=>time()
                        );
                        DB::insert('finance',$finance_data1);
                        //财务表插入数据（支出）
                        if(!empty($book['member_coin_amount'])){
                            $finance_data2=array(
                                'finance_type'=>'coin',
                                'book_id'=>$book['book_id'],
                                'member_id'=>$this->member_id,
                                'finance_state'=>1,
                                'finance_amount'=>intval($book['member_coin_amount'])/100,
                                'fiance_time'=>time()
                            );
                            DB::insert('finance',$finance_data2);
                        }
                    }
                    //合并付款
                    if($book['book_type']=='father'){
                        $book_ids=explode(',',$book['book_sub_order']);
                        $pay_time=time();
                        for($i=0;$i<count($book_ids);$i++){
                            $books = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_ids[$i]'");
                            //团豆豆消费（如果有） 财务表插入数据
                            if(!empty($books['member_coin_amount'])){
                                $finance_coin_datas=array(
                                    'finance_type'=>'coin',
                                    'book_id'=>$books['book_id'],
                                    'member_id'=>$books['member_id'],
                                    'agent_id'=>$books['agent_id'],
                                    'nurse_id'=>$books['nurse_id'],
                                    'finance_state'=>1,
                                    'finance_amount'=>intval($books['member_coin_amount']/100),
                                    'finance_time'=>time()
                                );
                                DB::insert('finance',$finance_coin_datas);
                            }
                            //在岗码生成
                            $book_codes=num6();
                            $book_code_datas=array(
                                'member_id'=>$this->member_id,
                                'book_id'=>$books['book_id'],
                                'book_sn'=>$books['book_sn'],
                                'code_value'=>$book_codes,
                                'code_state'=>0,
                                'add_time'=>time()
                            );
                            $book_code_ids=DB::insert('book_code',$book_code_datas,1);
                            //到岗验证码 短信通知
                            send_work_code($books['member_phone'], 'start_work_code',$book_codes);

                            $content =  array(
                                'code' =>''
                            );
                            if(empty($books['agent_id'])){
//                            send_text_code($books['book_phone'],'personal_order_payment');
                                $text_send = new Simple_SMS($books['book_phone'], 'personal_order_payment', $content);
                            }else{
//                            send_text_code($books['book_phone'],'company_order_payment');
                                $text_send = new Simple_SMS($books['book_phone'], 'company_order_payment', $content);
                            }
                            $send_result = $text_send->async_send();

                            //站内消息插入
                            if(!empty($book_code_ids)){
                                $message_datas=array(
                                    'member_id'=>$this->member_id,
                                    'book_id'=>$books['book_id'],
                                    'book_sn'=>$books['book_sn'],
                                    'message_type'=>'deal',
                                    'message_content'=>'编号为'.$books['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_codes.'请您妥善保管',
                                    'add_time'=>time()
                                );
                                DB::insert('system_message',$message_datas);
                            }
                            DB::query("UPDATE ".DB::table('nurse_book')." SET book_state=20,payment_time=$pay_time WHERE book_id='".$books['book_id']."'");
                            //财务表插入数据（收入）
                            $finance_data1s=array(
                                'finance_type'=>'book',
                                'book_id'=>$books['book_id'],
                                'member_id'=>$this->member_id,
                                'finance_state'=>0,
                                'finance_amount'=>$books['deposit_amount'],
                                'finance_time'=>time()
                            );
                            DB::insert('finance',$finance_data1s);
                            //财务表插入数据（支出）
                            if(!empty($books['member_coin_amount'])){
                                $finance_data2s=array(
                                    'finance_type'=>'coin',
                                    'book_id'=>$books['book_id'],
                                    'member_id'=>$this->member_id,
                                    'finance_state'=>1,
                                    'finance_amount'=>intval($books['member_coin_amount'])/100,
                                    'fiance_time'=>time()
                                );
                                DB::insert('finance',$finance_data2s);
                            }
                            //家政人员收益统计
                            if(empty($books['service_price'])){
                                $nurse_get_amounts=floor($books['deposit_amount']*0.93);
                            }else{
                                $nurse_get_amounts=intval($books['deposit_amount'])-ceil(intval($books['service_price'])*0.2);
                            }
                            $profit_datas = array(
                                'nurse_id' => $books['nurse_id'],
                                'agent_id' => $books['agent_id'],
                                'profit_stage' => 'order',
                                'profit_type' => 1,
                                'profit_amount' => $nurse_get_amounts,
                                'profit_desc' => '预约家政人员，预约单号：'.$books['book_sn'],
                                'is_freeze' => 1,
                                'book_id' => $books['book_id'],
                                'book_sn' => $books['book_sn'],
                                'add_time' => time(),
                            );
                            DB::insert('nurse_profit', $profit_datas);
                            DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$nurse_get_amounts.", pool_amount=pool_amount+".$nurse_get_amounts." WHERE nurse_id='".$books['nurse_id']."'");
                            //机构收益
                            if(!empty($books['agent_id'])){
                                $agent_profit_datas=array(
                                    'nurse_id' => $books['nurse_id'],
                                    'agent_id' => $books['agent_id'],
                                    'profit_stage' => 'order',
                                    'profit_type' => 1,
                                    'profit_amount' => $nurse_get_amounts,
                                    'profit_desc' => '预约家政人员，预约单号：'.$books['book_sn'],
                                    'is_freeze' => 1,
                                    'book_id' => $books['book_id'],
                                    'book_sn' => $books['book_sn'],
                                    'add_time' => time(),
                                );
                                DB::insert('agent_profit', $agent_profit_datas);
                            }
                        }
                    }
                    //雇主团豆豆收益
                    $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
                    $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
                    $member_add_coin=50;
                    $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='payment' AND get_time=$now_date");
                    if($member_add_coin+$member_get_coin>=200){
                        $member_add_coin=200-$member_get_coin;
                    }
                    $member_coin_data=array(
                        'member_id'=>$book['member_id'],
                        'book_id'=>$book['book_id'],
                        'coin_count'=>$member_add_coin,
                        'get_type'=>'payment',
                        'true_time'=>time(),
                        'get_time'=>$now_date
                    );
                    DB::insert('member_coin', $member_coin_data);
                    DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
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
					//家政人员销售统计
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
					$this->showmessage('预约付款成功', 'index.php?act=index.php?act=member_book');
				} else {
					$this->showmessage('预约付款失败', 'index.php?act=index.php?act=member_book', 'error');
				}
			} else {
				$this->showmessage('预约付款成功', 'index.php?act=index.php?act=member_book');
			}
		} else {
			$this->showmessage('预约付款失败', 'index.php?act=index.php?act=member_book', 'error');
		}
	}
	public function weixinOp(){
        $book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
        if(empty($book) || $book['member_id'] != $this->member_id) {
            $this->showmessage('预约单不存在', 'index.php?act=index.php?act=member_book', 'error');
        }
        if($book['book_state'] == '20') {
            //雇主团豆豆收益
//            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
//            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
//            $member_add_coin=50;
//            $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='payment' AND get_time=$now_date");
//            if($member_add_coin+$member_get_coin>=200){
//                $member_add_coin=200-$member_get_coin;
//            }
//            $member_coin_data=array(
//                'member_id'=>$book['member_id'],
//                'book_id'=>$book['book_id'],
//                'coin_count'=>$member_add_coin,
//                'get_type'=>'payment',
//                'true_time'=>time(),
//                'get_time'=>$now_date
//            );
//            DB::insert('member_coin', $member_coin_data);
//            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
            $this->showmessage('预约付款成功', 'index.php?act=index.php?act=member_book');
        }
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
        require_once MALL_ROOT.'/api/weixin/weixin.php';
        $unifiedOrder = new OrderQuery_pub();
        $unifiedOrder->setParameter("out_trade_no", $book['out_sn']);
        $result = $unifiedOrder->postXml();
        $result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        exit(json_encode(array('data'=>$result,'money'=>intval($book['book_amount']))));


    }
    public function weixin_bookOp(){
        $book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
        $transaction_id = empty($_GET['transaction_id']) ? '' : $_GET['transaction_id'];
        $total_fee = empty($_GET['total_fee']) ? '' : $_GET['total_fee'];
        $diff_amount = $total_fee-$book['book_amount']*100;
        if($book['book_state'] == 10) {
            if($diff_amount >= -1 && $diff_amount<=1) {
                $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
                $book_data = array();
                $book_data['transaction_id'] = $transaction_id;
                $book_data['payment_name'] = '微信';
                $book_data['payment_code'] = 'weixin';
                $book_data['book_state'] = 20;
                $book_data['payment_time'] = time();
                DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
                DB::query("UPDATE ".DB::table('nurse')." SET work_state=1 WHERE nurse_id='".$book['nurse_id']."'");
                //在岗码生成
                $book_code=num6();
                $book_code_array=array(
                    'member_id'=>$this->member_id,
                    'book_id'=>$book['book_id'],
                    'book_sn'=>$book['book_sn'],
                    'code_value'=>$book_code,
                    'code_state'=>0,
                    'add_time'=>time()
                );
                $book_code_id=DB::insert('book_code',$book_code_array);
                //到岗验证码 短信通知
                $content=array(
                    'code'=>$book_code
                );
                $text_send = new Simple_SMS($book['member_phone'], 'start_work_code', $content);
                $send_result = $text_send->async_send();
                //站内交易消息插入
                if(!empty($book_code_id)){
                    $message_data=array(
                        'member_id'=>$this->member_id,
                        'book_id'=>$book['book_id'],
                        'book_sn'=>$book['book_sn'],
                        'message_type'=>'deal',
                        'message_content'=>'编号为'.$book['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_code.'请您妥善保管',
                        'add_time'=>time()
                    );
                    DB::insert('system_message',$message_data);
                }
                //雇主团豆豆收益
                $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
                $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
                $member_add_coin=50;
                $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='payment' AND get_time=$now_date");
                if($member_add_coin+$member_get_coin>=200){
                    $member_add_coin=200-$member_get_coin;
                }
                $member_coin_data=array(
                    'member_id'=>$book['member_id'],
                    'book_id'=>$book['book_id'],
                    'coin_count'=>$member_add_coin,
                    'get_type'=>'payment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin', $member_coin_data);
                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
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
                //家政人员收益统计
                $profit_data = array(
                    'nurse_id' => $book['nurse_id'],
                    'agent_id' => $book['agent_id'],
                    'profit_stage' => 'order',
                    'profit_type' => 1,
                    'profit_amount' => $book['deposit_amount'],
                    'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                    'is_freeze' => 1,
                    'book_id' => $book['book_id'],
                    'book_sn' => $book['book_sn'],
                    'add_time' => time(),
                );
                DB::insert('nurse_profit', $profit_data);
                DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$book['book_amount'].", pool_amount=pool_amount+".$book['book_amount']." WHERE nurse_id='".$book['nurse_id']."'");
                //机构收益
                if(!empty($book['agent_id'])){
                    $agent_profit_data=array(
                        'nurse_id' => $book['nurse_id'],
                        'agent_id' => $book['agent_id'],
                        'profit_stage' => 'order',
                        'profit_type' => 1,
                        'profit_amount' => $book['deposit_amount'],
                        'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                        'is_freeze' => 1,
                        'book_id' => $book['book_id'],
                        'book_sn' => $book['book_sn'],
                        'add_time' => time(),
                    );
                    DB::insert('agent_profit', $agent_profit_data);
                }
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
                //家政人员销售统计
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
                exit(json_encode(array('done'=>'true')));
            } else {
                $this->showmessage('预约付款失败', 'index.php?act=index.php?act=member_book', 'error');
            }
        } else {
            exit(json_encode(array('done'=>'true')));
        }
    }

//	public function weixinOp() {
//		$book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
//		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
//		if($book['book_state'] == 20) {
//        //在岗码生成
//        $book_code=num6();
//        $book_code_array=array(
//        'member_id'=>$this->member_id,
//        'book_id'=>$book['book_id'],
//        'book_sn'=>$book['book_sn'],
//        'code_value'=>$book_code,
//        'code_state'=>0,
//        'add_time'=>time()
//        );
//        $book_code_id=DB::insert('book_code',$book_code_array);
    //站内交易消息插入
//        if(!empty($book_code_id)){
//        $message_data=array(
//        'member_id'=>$this->member_id,
//        'book_id'=>$book['book_id'],
//        'book_sn'=>$book['book_sn'],
//        'message_type'=>'deal',
//        'message_content'=>'编号为'.$book['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_code.'请您妥善保管',
//        'add_time'=>time()
//        );
//        DB::insert('system_message',$message_data);
//        }
//		    //雇主团豆豆收益
//            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
//            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
//            $member_add_coin=50;
//            $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='payment' AND get_time=$now_date");
//			if($member_add_coin+$member_get_coin>=200){
//			    $member_add_coin=200-$member_get_coin;
//            }
//            $member_coin_data=array(
//                'member_id'=>$book['member_id'],
//                'book_id'=>$book['book_id'],
//                'coin_count'=>$member_add_coin,
//                'get_type'=>'payment',
//                'true_time'=>time(),
//                'get_time'=>$now_date
//            );
//            DB::insert('member_coin', $member_coin_data);
//            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
//            $this->showmessage('预约付款成功', 'index.php?act=index.php?act=member_book');
//		} else {
//			$this->showmessage('预约付款失败', 'index.php?act=index.php?act=member_book', 'error');
//		}
//	}

	public function checkstateOp() {
		$book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
		if($book['book_state'] == 20) {
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('done'=>'false')));
		}
	}
}

?>