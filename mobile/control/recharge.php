<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class rechargeControl extends BaseMobileControl {
	public function indexOp() {
		$error_data = (object)array();
		$package_id = empty($_POST['package_id']) ? 0 : intval($_POST['package_id']);
		$pdr_amount = empty($_POST['pdr_amount']) ? 0 : intval($_POST['pdr_amount']);
		$pdr_payment_code = !in_array($_POST['pdr_payment_code'], array('alipay', 'weixin')) ? 'alipay' : $_POST['pdr_payment_code'];
		if(empty($package_id)) {
			if(empty($pdr_amount)) {
				exit(json_encode(array('code'=>'1', 'msg'=>'请输入充值金额', 'data'=>$error_data)));
			}
			if($pdr_amount < 10) {
				exit(json_encode(array('code'=>'1', 'msg'=>'充值金额不能小于10', 'data'=>$error_data)));
			}
			if($pdr_amount > 50000) {
				exit(json_encode(array('code'=>'1', 'msg'=>'充值金额不能大于50000', 'data'=>$error_data)));
			}
			$pdr_discount = 0;
		} else {
			$package = DB::fetch_first("SELECT * FROM ".DB::table("pd_package")." WHERE package_id='$package_id'");
			if(empty($package)) {
				exit(json_encode(array('code'=>'1', 'msg'=>'请输入充值金额', 'data'=>$error_data)));
			}
			$pdr_amount = $package['package_amount'];
			$pdr_discount = $package['discount_amount'];
		}
		if(empty($pdr_payment_code)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择充值方式', 'data'=>$error_data)));
		}
		$pdr_out_sn = date('YmdHis').random(18);
		$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_memberid='$this->member_id' AND pdr_payment_code='$pdr_payment_code' AND pdr_payment_state=0");
		$data = array(		
			'pdr_amount' => $pdr_amount,
			'pdr_discount' => $pdr_discount,
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
			$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_id='$pdr_id'");
			if($recharge['pdr_payment_code'] == 'alipay') {
				require_once MALL_ROOT.'/api/alipay/alipay.php';
				$args= array(
					'subject' => '我要充值'.$recharge['pdr_amount'].'元',
					'body' => '我要充值'.$recharge['pdr_amount'].'元',
					'notify_url' => SiteUrl.'/api/alipay/app_recharge.php',
					'out_trade_no' => $recharge['pdr_out_sn'],
					'total_fee' => $recharge['pdr_amount'],
				);
				$url = app_payurl($args);
				$data = array(
					'signurl' => $url,
				);
				exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
			} elseif($pdr_payment_code == 'weixin') {
				require_once MALL_ROOT.'/api/weixin/weixin.php';
				$unifiedOrder = new UnifiedOrder_pub();
				$unifiedOrder->setParameter("body", '我要充值'.$recharge['pdr_amount'].'元');
				$unifiedOrder->setParameter("out_trade_no", $recharge['pdr_out_sn']);
				$unifiedOrder->setParameter("total_fee", $recharge['pdr_amount']*100);
				$unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/app_recharge.php');
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
					'packageValue' => 'Sign=WXPay',
					'sign' => $param['sign'],
				);
				$data = array(
					'signurl' => $result,
				);
				exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
			}
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'充值失败', 'data'=>$error_data)));
		}
	}
	
	public function packageOp() {
		$query = DB::query("SELECT * FROM ".DB::table("pd_package")." ORDER BY package_amount ASC");
		while($value = DB::fetch($query)) {
			$package_list[] = $value;
		}
		$data = array(
			'available_predeposit' => $this->member['available_predeposit'],
			'package_list' => empty($package_list) ? array() : $package_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));	
	}
	
	public function recordOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE pdl_memberid='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pd_log').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pd_log').$wheresql." ORDER BY pdl_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$value['pdl_addtime'] = date('Y-m-d H:i', $value['pdl_addtime']);
			$pd_list[] = $value;
		}
		$data = array(
			'pd_amount' => $count,
			'pd_list' => empty($pd_list) ? array() : $pd_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>