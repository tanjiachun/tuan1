<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class bespokeControl extends BaseHomeControl {
	public function indexOp() {
		if(empty($this->member_id)) {
			$this->showmessage('您还未登录了', 'index.php?act=login', 'info');
		}
		$person_array = array('1'=>'自理', '2'=>'半自理', '3'=>'全自理', '4'=>'特护');
		$room_id = empty($_GET['room_id']) ? 0 : intval($_GET['room_id']);
		$room = DB::fetch_first("SELECT * FROM ".DB::table('pension_room')." WHERE room_id='$room_id'");
		if(empty($room)) {
			$this->showmessage('请选择要预定的房间', 'index.php?act=pension&pension='.$room['pension_id'], 'info');
		}
		$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='".$room['pension_id']."'");
		if($pension['pension_state'] != 1) {
			$this->showmessage('养老机构暂时不能接受预定', 'index.php?act=index&op=pension', 'info');	
		}
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$province_list[] = $value;
		}
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND red_state=0 AND red_cate_id in ('0', '3') AND red_starttime<=$time AND red_endtime>=$time");
		while($value = DB::fetch($query)) {
			if($value['red_limit'] > 0) {
				if($value['red_limit'] <= $room['room_price']) {
					$red_list[] = $value;
				}
			} else {
				$red_list[] = $value;
			}
		}
		$curmodule = 'home';
		$bodyclass = '';
		include(template('bespoke'));
	}
	
	public function redOp() {
		$room_price = empty($_GET['room_price']) ? 0 : intval($_GET['room_price']);
		$live_duration = empty($_GET['live_duration']) ? 0 : intval($_GET['live_duration']);
		$bed_number = empty($_GET['bed_number']) ? 0 : intval($_GET['bed_number']);
		$red_id = empty($_GET['red_id']) ? 0 : intval($_GET['red_id']);
		$red_limit = $room_price*$live_duration*$bed_number;
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND red_state=0 AND red_cate_id in ('0', '3') AND red_starttime<=$time AND red_endtime>=$time");
		while($value = DB::fetch($query)) {
			if($value['red_limit'] > 0) {
				if($value['red_limit'] <= $red_limit) {
					$red_list[] = $value;
				}
			} else {
				$red_list[] = $value;
			}
		}
		$html = '';
		$select_red = 'false';
		if(!empty($red_list)) {
			$html .= '<div class="hr"></div>';
			$html .= '<div class="step-tit">';
			$html .= '<h3>我的红包</h3>';
			$html .= '</div>';
			foreach($red_list as $key => $value) {
				if($value['red_id'] == $red_id) {
					$html .= '<div class="use-coupon-item radio active" red_id="'.$value['red_id'].'" red_price="'.$value['red_price'].'" red_title="'.$value['red_title'].'"><i class="iconfont icon-type"></i>'.$value['red_title'].'（'.$value['red_price'].'元）</div>';
					$select_red = 'true';
				} else {
					$html .= '<div class="use-coupon-item radio" red_id="'.$value['red_id'].'" red_price="'.$value['red_price'].'" red_title="'.$value['red_title'].'"><i class="iconfont icon-type"></i>'.$value['red_title'].'（'.$value['red_price'].'元）</div>';
				}	
			}
		}	
		exit(json_encode(array('done'=>'true', 'html'=>$html, 'select_red'=>$select_red)));
	}
	
	public function orderOp() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}
			$room_id = empty($_POST['room_id']) ? 0 : intval($_POST['room_id']);
			$bespoke_name = empty($_POST['bespoke_name']) ? '' : $_POST['bespoke_name'];
			$bespoke_phone = empty($_POST['bespoke_phone']) ? '' : $_POST['bespoke_phone'];
			$live_time = empty($_POST['live_time']) ? 0 : strtotime($_POST['live_time']);
			$live_duration = empty($_POST['live_duration']) ? 0 : intval($_POST['live_duration']);
			$bed_number = empty($_POST['bed_number']) ? 0 : intval($_POST['bed_number']);
			$contact_name = empty($_POST['contact_name']) ? array() : $_POST['contact_name'];
			$contact_cardid = empty($_POST['contact_cardid']) ? array() : $_POST['contact_cardid'];
			if(empty($room_id)) {
				exit(json_encode(array('id'=>'system', 'msg'=>'请选择要预定的房间')));
			}
			$room = DB::fetch_first("SELECT * FROM ".DB::table('pension_room')." WHERE room_id='$room_id'");
			if(empty($room)) {
				exit(json_encode(array('id'=>'system', 'msg'=>'请选择要预定的房间')));
			}
			$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='".$room['pension_id']."'");
			if($pension['pension_state'] != 1) {
				exit(json_encode(array('id'=>'system', 'msg'=>'该养老机构暂时不能接受预定')));
			}
			if(empty($bespoke_name)) {
				exit(json_encode(array('id'=>'bespoke_name', 'msg'=>'请输入预定人姓名')));
			}
			if(empty($bespoke_phone)) {
				exit(json_encode(array('id'=>'bespoke_phone', 'msg'=>'请输入预定人手机号')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $bespoke_phone)) {
				exit(json_encode(array('id'=>'bespoke_phone', 'msg'=>'预定人手机号格式不正确')));
			}
			if(empty($live_time)) {
				exit(json_encode(array('id'=>'live_time', 'msg'=>'请输入入驻时间')));
			}
			if(empty($live_duration)) {
				exit(json_encode(array('id'=>'live_duration', 'msg'=>'请输入入驻时长')));
			}
			if(empty($bed_number)) {
				exit(json_encode(array('id'=>'bed_number', 'msg'=>'请输入需要床位')));
			}
			$room_surplus = $room['room_storage']-$room['room_used'];
			if($room_surplus < $bed_number) {
				exit(json_encode(array('id'=>'bed_number', 'msg'=>'床位不足了，只剩下'.$room_surplus.'位了')));
			}
			$name_count = count($contact_name);
			$cardid_count = count($contact_cardid);
			if($name_count != $cardid_count) {
				exit(json_encode(array('id'=>'system', 'msg'=>'参数错误')));
			}
			if($name_count != $bed_number) {
				exit(json_encode(array('id'=>'system', 'msg'=>'参数错误')));	
			}
			$bespoke_message = array();
			foreach($contact_name as $key => $value) {
				if(empty($value)) {
					exit(json_encode(array('id'=>'system', 'msg'=>'请输入姓名')));
				}
				$bespoke_message[$key]['name'] = $value;
			}
			foreach($contact_cardid as $key => $value) {
				if(empty($value)) {
					exit(json_encode(array('id'=>'system', 'msg'=>'请输入身份证号码')));
				}
				$bespoke_message[$key]['cardid'] = $value;
			}
			$bespoke_invoice = !in_array($_POST['bespoke_invoice'], array('yes', 'no')) ? 'no' : $_POST['bespoke_invoice'];
			$invoice_title = empty($_POST['invoice_title']) ? '' : $_POST['invoice_title'];
			$invoice_content = empty($_POST['invoice_content']) ? '' : $_POST['invoice_content'];
			$invoice_membername = empty($_POST['invoice_membername']) ? '' : $_POST['invoice_membername'];
			$invoice_provinceid = empty($_POST['invoice_provinceid']) ? 0 : intval($_POST['invoice_provinceid']);
			$invoice_cityid = empty($_POST['invoice_cityid']) ? 0 : intval($_POST['invoice_cityid']);
			$invoice_areaid = empty($_POST['invoice_areaid']) ? 0 : intval($_POST['invoice_areaid']);
			$invoice_address = empty($_POST['invoice_address']) ? '' : $_POST['invoice_address'];
			if($bespoke_invoice == 'yes') {
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
			$red_limit = $room['room_price']*$live_duration*$bed_number;
			$time = time();
			$red_id = empty($_POST['red_id']) ? 0 : intval($_POST['red_id']);
			$red_amount = 0;
			$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE red_id='$red_id'");
			if(!empty($red) && $red['member_id'] == $this->member_id && in_array($red['red_cate_id'], array('0', '3')) && empty($red['red_state']) && $red['red_starttime'] <= $time && $red['red_endtime'] >= $time) {
				if($red['red_limit'] > 0) {
					if($red['red_limit'] <= $red_limit) {
						$red_amount = $red['red_price'];
						$bespoke_red_id = $red['red_id'];
					}
				} else {
					$red_amount = $red['red_price'];
					$bespoke_red_id = $red['red_id'];
				}
			}
			$bespoke_sn = makesn(9);
			$out_sn = date('YmdHis').random(18);
			$data = array(
				'bespoke_sn' => $bespoke_sn,
				'out_sn' => $out_sn,
				'room_id' => $room_id,
				'pension_id' => $pension['pension_id'],
				'member_id' => $this->member_id,
				'member_phone' => $this->member['member_phone'],
				'bespoke_name' => $bespoke_name,
				'bespoke_phone' => $bespoke_phone,
				'live_time' => $live_time,
				'live_duration' => $live_duration,
				'bed_number' => $bed_number,
				'bespoke_message' => empty($bespoke_message) ? '' : serialize($bespoke_message),
				'invoice_content' => $invoice_content,
				'red_amount' => $red_amount,
				'bespoke_amount' => $room['room_price']*$live_duration*$bed_number-$red_amount,
				'bespoke_state' => 10,
				'add_time' => time(),
			);
			$bespoke_id = DB::insert('pension_bespoke', $data, 1);
			if(!empty($bespoke_id)) {
				if(!empty($bespoke_red_id)) {
					DB::update('red', array('use_type'=>3, 'use_id'=>$bespoke_id, 'red_state'=>1),array('red_id'=>$red['red_id']));
					DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used+1 WHERE red_t_id='".$red['red_t_id']."'");
				}
				exit(json_encode(array('done'=>'true', 'bespoke_sn'=>$bespoke_sn)));
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
			$bespoke_sn = empty($_POST['bespoke_sn']) ? '' : $_POST['bespoke_sn'];
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_sn='$bespoke_sn'");
			if(empty($bespoke) || $bespoke['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'预定不存在')));
			}
			if($bespoke['bespoke_state'] != '10') {
				exit(json_encode(array('msg'=>'预定还不能支付')));
			}
			$payment_code = !in_array($_POST['payment_code'], array('alipay', 'weixin', 'predeposit')) ? 'alipay' : $_POST['payment_code'];
			if($payment_code == 'predeposit') {
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$bespoke['member_id']."'");
				if($member['available_predeposit'] < $bespoke['bespoke_amount']) {
					exit(json_encode(array('msg'=>'余额不足，请选择其他支付方式')));
				}
				$data = array(
					'pdl_memberid' => $member['member_id'],
					'pdl_memberphone' => $member['member_phone'],
					'pdl_stage' => 'bespoke',
					'pdl_type' => 0,
					'pdl_price' => $bespoke['bespoke_amount'],
					'pdl_predeposit' => $member['available_predeposit']-$bespoke['bespoke_amount'],
					'pdl_desc' => '预定房间，预定单号: '.$bespoke['bespoke_sn'],
					'pdl_addtime' => time(),
				);
				DB::insert('pd_log', $data);
				DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit-".$bespoke['bespoke_amount']." WHERE member_id='".$member['member_id']."'");
				$bespoke_data = array();
				$bespoke_data['payment_name'] = '余额支付';
				$bespoke_data['payment_code'] = 'predeposit';				
				$bespoke_data['bespoke_state'] = 20;
				$bespoke_data['payment_time'] = time();
				DB::update('pension_bespoke', $bespoke_data, array('bespoke_id'=>$bespoke['bespoke_id']));
				//红包
				$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='reward' ORDER BY red_t_amount DESC");
				while($value = DB::fetch($query)) {
					if($value['red_t_total'] > $value['red_t_giveout'] && $value['red_t_amount'] <= $bespoke['bespoke_amount']) {
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
				//机构收益统计
				$profit_data = array(
					'pension_id' => $bespoke['pension_id'],
					'profit_stage' => 'order',
					'profit_type' => 1,
					'profit_amount' => $bespoke['bespoke_amount'],
					'profit_desc' => $bespoke['bespoke_name'].'预定房间，预定单号：'.$bespoke['bespoke_sn'],
					'is_freeze' => 1,
					'bespoke_id' => $bespoke['bespoke_id'],
					'bespoke_sn' => $bespoke['bespoke_sn'],
					'add_time' => time(),
				);
				DB::insert('pension_profit', $profit_data);
				DB::query("UPDATE ".DB::table('pension')." SET plat_amount=plat_amount+".$bespoke['bespoke_amount'].", pool_amount=pool_amount+".$bespoke['bespoke_amount']." WHERE pension_id='".$bespoke['pension_id']."'");
				//养老金收益
				$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
				if($this->setting['first_oldage_rate'] > 0)	{
					$oldage_price = priceformat($bespoke['bespoke_amount']*$this->setting['first_oldage_rate']);
					$oldage_data = array(
						'member_id' => $member['member_id'],
						'oldage_stage' => 'consume',
						'oldage_type' => 1,
						'oldage_price' => $oldage_price,
						'oldage_balance' => $member['oldage_amount']+$oldage_price,
						'oldage_desc' => '消费了'.$bespoke['bespoke_amount'].'元，获得'.$oldage_price.'元养老金',
						'oldage_addtime' => time(),
					);
					DB::insert('oldage', $oldage_data);
					DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
				}		
				//机构销售统计
				DB::query("UPDATE ".DB::table('pension')." SET pension_salenum=pension_salenum+1 WHERE pension_id='".$bespoke['pension_id']."'");
				$date = date('Ymd');
				$pension_stat = DB::fetch_first("SELECT * FROM ".DB::table('pension_stat')." WHERE pension_id='".$bespoke['pension_id']."' AND date='$date'");
				if(empty($pension_stat)) {
					$pension_stat_array = array(
						'pension_id' => $bespoke['pension_id'],
						'date' => $date,
						'salenum' => 1,
					);
					DB::insert('pension_stat', $pension_stat_array);
				} else {
					$pension_stat_array = array(
						'salenum' => $pension_stat['salenum']+$bespoke['bed_number'],
					);
					DB::update('pension_stat', $pension_stat_array, array('pension_id'=>$bespoke['pension_id'], 'date'=>$date));
				}
			} else {
				DB::update('pension_bespoke', array('payment_code'=>$payment_code), array('bespoke_id'=>$bespoke['bespoke_id']));
			}
			exit(json_encode(array('done'=>'true', 'bespoke_sn'=>$bespoke_sn)));
		} else {
			if(empty($this->member_id)) {
				$this->showmessage('您还未登录了', 'index.php?act=login', 'info');
			}
			$bespoke_sn = empty($_GET['bespoke_sn']) ? '' : $_GET['bespoke_sn'];
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_sn='$bespoke_sn'");
			if(empty($bespoke) || $bespoke['member_id'] != $this->member_id) {
				$this->showmessage('预定不存在', 'index.php?act=order&op=bespoke&state=pending', 'error');
			}
			if($bespoke['bespoke_state'] != '10') {
				$this->showmessage('预定还不能支付', 'index.php?act=order&op=bespoke&state=pending', 'info');
			}
			$room = DB::fetch_first("SELECT * FROM ".DB::table('pension_room')." WHERE room_id='".$bespoke['room_id']."'");
			$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='".$bespoke['pension_id']."'");
			$curmodule = 'home';
			$bodyclass = '';
			include(template('bespoke_payment'));
		}
	}
	
	public function alipayOp() {
		require_once MALL_ROOT.'/api/alipay/alipay.php';
		$notifydata = trade_notifycheck();
		if($notifydata['validator']) {
			$out_sn = $notifydata['order_no'];
			$transaction_id = $notifydata['trade_no'];
			$bespoke_amount = $notifydata['price'];
			$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE out_sn='$out_sn'");
			if($bespoke['bespoke_state'] == 10) {
				if(floatval($bespoke_amount) == floatval($bespoke['bespoke_amount'])) {
					$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$bespoke['member_id']."'");
					$bespoke_data = array();
					$bespoke_data['transaction_id'] = $transaction_id;
					$bespoke_data['payment_name'] = '支付宝';
					$bespoke_data['payment_code'] = 'alipay';
					$bespoke_data['bespoke_state'] = 20;
					$bespoke_data['payment_time'] = time();
					DB::update('pension_bespoke', $bespoke_data, array('bespoke_id'=>$bespoke['bespoke_id']));
					//红包
					$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='reward' ORDER BY red_t_amount DESC");
					while($value = DB::fetch($query)) {
						if($value['red_t_total'] > $value['red_t_giveout'] && $value['red_t_amount'] <= $bespoke['bespoke_amount']) {
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
					//机构收益统计
					$profit_data = array(
						'pension_id' => $bespoke['pension_id'],
						'profit_stage' => 'order',
						'profit_type' => 1,
						'profit_amount' => $bespoke['bespoke_amount'],
						'profit_desc' => $bespoke['bespoke_name'].'预定房间，预定单号：'.$bespoke['bespoke_sn'],
						'is_freeze' => 1,
						'bespoke_id' => $bespoke['bespoke_id'],
						'bespoke_sn' => $bespoke['bespoke_sn'],
						'add_time' => time(),
					);
					DB::insert('pension_profit', $profit_data);
					DB::query("UPDATE ".DB::table('pension')." SET plat_amount=plat_amount+".$bespoke['bespoke_amount'].", pool_amount=pool_amount+".$bespoke['bespoke_amount']." WHERE pension_id='".$bespoke['pension_id']."'");
					//养老金收益
					$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
					if($this->setting['first_oldage_rate'] > 0)	{
						$oldage_price = priceformat($bespoke['bespoke_amount']*$this->setting['first_oldage_rate']);
						$oldage_data = array(
							'member_id' => $member['member_id'],
							'oldage_stage' => 'consume',
							'oldage_type' => 1,
							'oldage_price' => $oldage_price,
							'oldage_balance' => $member['oldage_amount']+$oldage_price,
							'oldage_desc' => '消费了'.$bespoke['bespoke_amount'].'元，获得'.$oldage_price.'元养老金',
							'oldage_addtime' => time(),
						);
						DB::insert('oldage', $oldage_data);
						DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
					}			
					//机构销售统计
					DB::query("UPDATE ".DB::table('pension')." SET pension_salenum=pension_salenum+1 WHERE pension_id='".$bespoke['pension_id']."'");
					$date = date('Ymd');
					$pension_stat = DB::fetch_first("SELECT * FROM ".DB::table('pension_stat')." WHERE pension_id='".$bespoke['pension_id']."' AND date='$date'");
					if(empty($pension_stat)) {
						$pension_stat_array = array(
							'pension_id' => $bespoke['pension_id'],
							'date' => $date,
							'salenum' => 1,
						);
						DB::insert('pension_stat', $pension_stat_array);
					} else {
						$pension_stat_array = array(
							'salenum' => $pension_stat['salenum']+$bespoke['bed_number'],
						);
						DB::update('pension_stat', $pension_stat_array, array('pension_id'=>$bespoke['pension_id'], 'date'=>$date));
					}
					$this->showmessage('预定付款成功', 'index.php?act=order&op=bespoke');
				} else {
					$this->showmessage('预定付款失败', 'index.php?act=order&op=bespoke', 'error');
				}
			} else {
				$this->showmessage('预定付款成功', 'index.php?act=order&op=bespoke');
			}
		} else {
			$this->showmessage('预定付款失败', 'index.php?act=order&op=bespoke', 'error');
		}
	}
	
	public function weixinOp() {
		$bespoke_sn = empty($_GET['bespoke_sn']) ? '' : $_GET['bespoke_sn'];
		$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_sn='$bespoke_sn'");
		if($bespoke['bespoke_state'] == 20) {
			$this->showmessage('预约付款成功', 'index.php?act=order&op=bespoke');
		} else {
			$this->showmessage('预约付款失败', 'index.php?act=order&op=bespoke', 'error');
		}
	}
	
	public function checkstateOp() {
		$bespoke_sn = empty($_GET['bespoke_sn']) ? '' : $_GET['bespoke_sn'];
		$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_sn='$bespoke_sn'");
		if($bespoke['bespoke_state'] == 20) {
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('done'=>'false')));
		}
	}
}

?>