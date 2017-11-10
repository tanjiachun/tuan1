<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class rechargeControl extends BaseProfileControl {
	public function indexOp() {
		if(submitcheck()) {
			$pdr_amount = empty($_POST['pdr_amount']) ? 0 : intval($_POST['pdr_amount']);
			$pdr_payment_code = !in_array($_POST['pdr_payment_code'], array('weixin', 'alipay')) ? '' : $_POST['pdr_payment_code'];
			if(empty($pdr_amount)) {
				exit(json_encode(array('msg'=>'请输入充值金额')));
			}
			if($pdr_amount < 10) {
				exit(json_encode(array('msg'=>'充值金额不能小于10')));
			}
			if($pdr_amount > 50000) {
				exit(json_encode(array('msg'=>'充值金额不能大于50000')));
			}
			if(empty($pdr_payment_code)) {
				exit(json_encode(array('msg'=>'请选择充值方式')));
			}
			$pdr_out_sn = date('YmdHis').random(18);
			$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_memberid='$this->member_id' AND pdr_payment_code='$pdr_payment_code' AND pdr_payment_state=0");
			$data = array(		
				'pdr_amount' => $pdr_amount,
				'pdr_out_sn' => $pdr_out_sn,
				'pdr_add_time' => time(),
				'pdr_payment_code' => $pdr_payment_code,
			);
			if(empty($recharge)) {
				$pdr_sn = makesn(5);
				$data['pdr_sn'] = $pdr_sn;
				$data['pdr_memberid'] = $this->member_id;
				$data['pdr_memberphone'] = $this->member['member_phone'];
				$data['pdr_payment_code'] = $pdr_payment_code;
				$pdr_id = DB::insert('pd_recharge', $data, 1);
			} else {
				$pdr_sn = $recharge['pdr_sn'];
				DB::update('pd_recharge', $data, array('pdr_id'=>$recharge['pdr_id']));
				$pdr_id = $recharge['pdr_id'];
			}
			if(!empty($pdr_id)) {
				exit(json_encode(array('done'=>'true', 'pdr_sn'=>$pdr_sn)));
			} else {
				exit(json_encode(array('msg'=>'网路繁忙，请稍候重试')));	
			}
		} else {
			$curmodule = 'home';
			$bodyclass = 'gray-bg';
			include(template('recharge'));
		}
	}
	
	public function alipayOp() {
		require_once MALL_ROOT.'/api/alipay/alipay.php';
		$notifydata = trade_notifycheck();
		if($notifydata['validator']) {
			$pdr_out_sn = $notifydata['order_no'];
			$pdr_transaction_id = $notifydata['trade_no'];
			$pdr_amount = $notifydata['price'];
			$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_out_sn='$pdr_out_sn'");
			if($recharge['pdr_payment_state'] == 0) {
				if(!empty($recharge) && floatval($pdr_amount) == floatval($recharge['pdr_amount'])) {		
					$data = array();
					$data['pdr_transaction_id'] = $pdr_transaction_id;
					$data['pdr_payment_state'] = 1;
					$data['pdr_payment_time'] = time();			
					DB::update('pd_recharge', $data, array('pdr_id'=>$recharge['pdr_id']));
					$pdr_amount = $recharge['pdr_amount']+$recharge['pdr_discount'];
					$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$recharge['pdr_memberid']."'");
					$data_log = array();
					$data_log['pdl_memberid'] = $member['member_id'];
					$data_log['pdl_memberphone'] = $member['member_phone'];
					$data_log['pdl_stage'] = 'recharge';
					$data_log['pdl_type'] = 1;
					$data_log['pdl_price'] = $pdr_amount;
					$data_log['pdl_predeposit'] = $member['available_predeposit']+$pdr_amount;
					$data_log['pdl_desc'] = '钱包充值。支付方式：支付宝，充值单号: '.$pdr_transaction_id;
					$data_log['pdl_addtime'] = time();
					DB::insert('pd_log', $data_log);
					DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+$pdr_amount, card_predeposit=card_predeposit+$pdr_amount WHERE member_id='".$member['member_id']."'");
					//财务表插入数据（收入）
                    $finance_data=array(
                        'finance_type'=>'recharge',
                        'member_id'=>$recharge['pdr_memberid'],
                        'finance_state'=>0,
                        'finance_amount'=>$recharge['pdr_amount'],
                        'finance_time'=>time()
                    );
                    DB::insert('finance',$finance_data);
					$this->showmessage('充值成功', 'index.php?act=predeposit');
				} else {
					$this->showmessage('充值失败', 'index.php?act=recharge', 'error');
				}
			} else {
				$this->showmessage('充值成功', 'index.php?act=predeposit');
			}
		} else {
			$this->showmessage('充值失败', 'index.php?act=recharge', 'error');
		}
	}
	
	public function weixinOp() {
		$pdr_sn = empty($_GET['pdr_sn']) ? '' : $_GET['pdr_sn'];
		$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_sn='$pdr_sn'");
		if($recharge['pdr_payment_state'] == 1) {
			$this->showmessage('充值成功', 'index.php?act=predeposit');
		} else {
			$this->showmessage('充值失败', 'index.php?act=recharge', 'error');
		}
	}
	
	public function checkstateOp() {
		$pdr_sn = empty($_GET['pdr_sn']) ? '' : $_GET['pdr_sn'];
		$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_sn='$pdr_sn'");
		if($recharge['pdr_payment_state'] == 1) {
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('done'=>'false')));
		}
	}
}

?>