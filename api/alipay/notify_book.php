<?php
error_reporting(0);
define('InMall', true);
define('MALL_ROOT', substr(dirname(__FILE__), 0, -10));

require_once MALL_ROOT.'/config.ini.php';
require_once MALL_ROOT.'/system/database/mysql.php';
$class = 'db_mysql';
$db = & DB::object($class);
$db->set_config($config['db']);
$db->connect();

if(file_exists(MALL_ROOT.'/cache/cache_setting.php')){
	require_once(MALL_ROOT."/cache/cache_setting.php");
} else {
	$query = DB::query("SELECT * FROM ".DB::table('setting'));
	while($value = DB::fetch($query)) {
		$setting[$value['setting_key']] = $value['setting_value'];	
	}
}

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
			$book_data['book_state'] = 30;
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
//			$profit_data = array(
//				'nurse_id' => $book['nurse_id'],
//				'agent_id' => $book['agent_id'],
//				'profit_stage' => 'order',
//				'profit_type' => 1,
//				'profit_amount' => $book['deposit_amount'],
//				'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
//				'is_freeze' => 1,
//				'book_id' => $book['book_id'],
//				'book_sn' => $book['book_sn'],
//				'add_time' => time(),
//			);
//			DB::insert('nurse_profit', $profit_data);
//			DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$book['book_amount'].", pool_amount=pool_amount+".$book['book_amount']." WHERE nurse_id='".$book['nurse_id']."'");
//			//机构收益统计
//			if(!empty($book['agent_id'])){
//			    $agent_profit_data=array(
//                    'nurse_id' => $book['nurse_id'],
//                    'agent_id' => $book['agent_id'],
//                    'profit_stage' => 'order',
//                    'profit_type' => 1,
//                    'profit_amount' => $book['deposit_amount'],
//                    'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
//                    'is_freeze' => 1,
//                    'book_id' => $book['book_id'],
//                    'book_sn' => $book['book_sn'],
//                    'add_time' => time(),
//                );
//                DB::insert('agent_profit', $agent_profit_data);
//            }
			//养老金收益
			$setting['first_oldage_rate'] = floatval($setting['first_oldage_rate']);
			if($setting['first_oldage_rate'] > 0)	{
				$oldage_price = priceformat($book['book_amount']*$setting['first_oldage_rate']);
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
			echo 'success';
		} else {
			echo 'fail';
		}
	} else {
		echo 'success';
	}
} else {
	echo 'fail';
}
?>