<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class paymentControl extends BaseHomeControl {
	public function indexOp() {
		$order_sn = empty($_GET['order_sn']) ? '' : $_GET['order_sn'];
		$order_temp = DB::fetch_first("SELECT * FROM ".DB::table('order_temp')." WHERE order_sn='$order_sn'");
		if(empty($order_temp) || $order_temp['member_id'] != $this->member_id) {
			$this->showmessage('该订单不存在', 'index.php?act=order', 'error');	
		}
		if($order_temp['order_state'] == '20') {
			$this->showmessage('支付成功', 'index.php?act=order');	
		}
		$order_ids = explode(',', $order_temp['order_ids']);
		$order_address = DB::fetch_first("SELECT * FROM ".DB::table('order_address')." WHERE order_id='".$order_ids[0]."'");
		if($order_temp['payment_code'] == 'alipay') {
			$param = array(
				'subject' => '我要购买'.$order_temp['order_goods_name'],
				'out_trade_no' => $order_temp['out_sn'],
				'price' => $order_temp['order_amount'],
				'notify_url' => SiteUrl.'/api/alipay/notify_trade.php',
				'return_url' => SiteUrl.'/index.php?act=payment&op=alipay',
			);
			require_once MALL_ROOT.'/api/alipay/alipay.php';
			$requesturl = get_payurl($param);
			echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">document.getElementById(\'payform\').submit();</script>';
			exit;
		} elseif($order_temp['payment_code'] == 'weixin') {
			require_once MALL_ROOT.'/api/weixin/weixin.php';
			$unifiedOrder = new UnifiedOrder_pub();
			$unifiedOrder->setParameter("body", '我要购买'.$order_temp['order_goods_name']);
			$unifiedOrder->setParameter("out_trade_no", $order_temp['out_sn']);
			$unifiedOrder->setParameter("total_fee", $order_temp['order_amount']*100);
			$unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/notify_trade.php');
			$unifiedOrder->setParameter("trade_type", 'NATIVE');
			$result = $unifiedOrder->postXml();
			$result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
			$code_url = $result->code_url;
			$curmodule = 'home';
			$bodyclass = '';
			include(template('payment_weixin'));	
		}
	}
	
	public function alipayOp() {
		require_once MALL_ROOT.'/api/alipay/alipay.php';
		$notifydata = trade_notifycheck();
		if($notifydata['validator']) {
			$out_sn = $notifydata['order_no'];
			$transaction_id = $notifydata['trade_no'];
			$order_amount = $notifydata['price'];
			$order_temp = DB::fetch_first("SELECT * FROM ".DB::table('order_temp')." WHERE out_sn='$out_sn'");
			if($order_temp['order_state'] == 10) {
				if(floatval($order_amount) == floatval($order_temp['order_amount'])) {
					$order_ids = explode(',', $order_temp['order_ids']);
					foreach($order_ids as $order_id) {
						$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
						$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
						$order_data = array();
						$order_data['transaction_id'] = $transaction_id;
						$order_data['payment_name'] = '支付宝';
						$order_data['payment_code'] = 'alipay';
						$order_data['order_state'] = 20;
						$order_data['payment_time'] = time();
						DB::update('order', $order_data, array('order_id'=>$order['order_id']));
						$log_data = array();
						$log_data['order_id'] = $order['order_id'];
						$log_data['order_state'] = 20;
						$log_data['order_intro'] = '订单付款';
						$log_data['state_info'] = '支付方式：支付宝';
						$log_data['log_time'] = time();
						Db::insert('order_log', $log_data);
						//优惠券
						$time = time();
						$query = DB::query("SELECT * FROM ".DB::table('coupon_template')." WHERE store_id='".$order['store_id']."' AND coupon_rule_type='buy' AND coupon_rule_starttime<=$time AND coupon_rule_endtime>=$time ORDER BY coupon_rule_amount DESC");
						while($value = DB::fetch($query)) {
							if($value['coupon_t_total'] > $value['coupon_t_giveout'] && $value['coupon_rule_amount'] <= $order['order_amount']) {
								$coupon_template = $value;
								$coupon_t_id = $value['coupon_t_id'];
								break;
							}
						}
						if(!empty($coupon_t_id)) {
							if($coupon_template['coupon_t_period_type'] == 'duration') {
								$coupon_template['coupon_t_starttime'] = strtotime(date('Y-m-d'));
								$coupon_template['coupon_t_endtime'] = $coupon_template['coupon_t_starttime']+3600*24*($coupon_template['coupon_t_days']+1)-1;
							}
							$coupon_data = array(
								'coupon_sn' => makesn(3),
								'member_id' => $member['member_id'],
								'store_id' => $coupon_template['store_id'],
								'coupon_t_id' => $coupon_template['coupon_t_id'],
								'coupon_title' => $coupon_template['coupon_t_title'],
								'coupon_desc' => $coupon_template['coupon_t_desc'],
								'coupon_starttime' => $coupon_template['coupon_t_starttime'],
								'coupon_endtime' => $coupon_template['coupon_t_endtime'],
								'coupon_price_type' => $coupon_template['coupon_t_price_type'],
								'coupon_price' => $coupon_template['coupon_t_price'],
								'coupon_limit' => $coupon_template['coupon_t_limit'],
								'coupon_goods_id' => $coupon_template['coupon_t_goods_id'],
								'coupon_state' => 0,
								'coupon_addtime' => time(),
							);
							$coupon_id = DB::insert('coupon', $coupon_data, 1);
							if(!empty($coupon_id)) {
								DB::update('coupon_template', array('coupon_t_giveout'=>$coupon_template['coupon_t_giveout']+1), array('coupon_t_id'=>$coupon_template['coupon_t_id']));
							}
						}
						//商家收益统计
						$profit_data = array(
							'store_id' => $order['store_id'],
							'profit_stage' => 'order',
							'profit_type' => 1,
							'profit_amount' => $order['order_amount'],
							'profit_desc' => $order['member_phone'].'购买商品，订单单号：'.$order['order_sn'],
							'is_freeze' => 1,
							'order_id' => $order['order_id'],
							'order_sn' => $order['order_sn'],
							'add_time' => time(),
						);
						DB::insert('store_profit', $profit_data);
						DB::query("UPDATE ".DB::table('store')." SET plat_amount=plat_amount+".$order['order_amount'].", pool_amount=pool_amount+".$order['order_amount']." WHERE store_id='".$order['store_id']."'");
						//养老金收益
						$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
						if($this->setting['first_oldage_rate'] > 0)	{
							$oldage_price = priceformat($order['order_amount']*$this->setting['first_oldage_rate']);
							$oldage_data = array(
								'member_id' => $member['member_id'],
								'oldage_stage' => 'consume',					
								'oldage_type' => 1,
								'oldage_price' => $oldage_price,
								'oldage_balance' => $member['oldage_amount']+$oldage_price,
								'oldage_desc' => '消费了'.$order['order_amount'].'元，获得'.$oldage_price.'元养老金',
								'oldage_addtime' => time(),
							);
							DB::insert('oldage', $oldage_data);
							DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
						}
						//商品销售统计
						$date = date('Ymd');
						$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='".$order['order_id']."'");
						while($value = DB::fetch($query)) {
							$order_goods[] = $value;
						}
						foreach($order_goods as $key => $value) {
							$goods_stat = DB::fetch_first("SELECT * FROM ".DB::table('goods_stat')." WHERE goods_id='".$value['goods_id']."' AND date='$date'");
							if(empty($goods_stat)) {
								$goods_stat_array = array(
									'goods_id' => $value['goods_id'],
									'date' => $date,
									'salenum' => $value['goods_num'],
								);
								DB::insert('goods_stat', $goods_stat_array);
							} else {
								$goods_stat_array = array(
									'salenum' => $goods_stat['salenum']+$value['goods_num'],
								);
								DB::update('goods_stat', $goods_stat_array, array('goods_id'=>$goods['goods_id'], 'date'=>$date));
							}
						}
					}
					//红包
					$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='reward' ORDER BY red_t_amount DESC");
					while($value = DB::fetch($query)) {
						if($value['red_t_total'] > $value['red_t_giveout'] && $value['red_t_amount'] <= $order_temp['order_amount']) {
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
					$this->showmessage('订单付款成功', 'index.php?act=order');
				} else {
					$this->showmessage('订单付款失败', 'index.php?act=order', 'error');
				}
			} else {
				$this->showmessage('订单付款成功', 'index.php?act=order');
			}
		} else {
			$this->showmessage('订单付款失败', 'index.php?act=order', 'error');
		}
	}
	
	public function weixinOp() {
		$order_sn = empty($_GET['order_sn']) ? '' : $_GET['order_sn'];
		$order_temp = DB::fetch_first("SELECT * FROM ".DB::table('order_temp')." WHERE order_sn='$order_sn'");
		if($order_temp['order_state'] == '20') {
			$this->showmessage('订单付款成功', 'index.php?act=order');
		} else {
			$this->showmessage('订单付款失败', 'index.php?act=order', 'error');
		}
	}
	
	public function checkstateOp() {
		$order_sn = empty($_GET['order_sn']) ? '' : $_GET['order_sn'];
		$order_temp = DB::fetch_first("SELECT * FROM ".DB::table('order_temp')." WHERE order_sn='$order_sn'");
		if($order_temp['order_state'] == 20) {
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('done'=>'false')));
		}
	}
	
	public function bookOp() {
		$book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
		if(empty($book) || $book['member_id'] != $this->member_id) {
			$this->showmessage('预约单不存在', 'index.php?act=member_book', 'error');
		}
		if($book['book_state'] == '20') {
			$this->showmessage('预约付款成功', 'index.php?act=member_book');
		}
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
		if($book['payment_code'] == 'alipay') {
			$param = array(
				'subject' => '我要预约'.$nurse['nurse_name'],
				'out_trade_no' => $book['out_sn'],
				'price' => $book['book_amount'],
				'notify_url' => SiteUrl.'/api/alipay/notify_book.php',
				'return_url' => SiteUrl.'/index.php?act=book&op=alipay',
			);
			require_once MALL_ROOT.'/api/alipay/alipay.php';
			$requesturl = get_payurl($param);
			echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">document.getElementById(\'payform\').submit();</script>';
			exit;
		} elseif($book['payment_code'] == 'weixin') {
			require_once MALL_ROOT.'/api/weixin/weixin.php';
			$unifiedOrder = new UnifiedOrder_pub();
			$unifiedOrder->setParameter("body", '我要预约'.$nurse['nurse_name']);
			$unifiedOrder->setParameter("out_trade_no", $book['out_sn']);
			$unifiedOrder->setParameter("total_fee", $book['book_amount']*100);
			$unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/notify_book.php');
			$unifiedOrder->setParameter("trade_type", 'NATIVE');
			$result = $unifiedOrder->postXml();
            $result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
            $code_url = $result->code_url;
			$curmodule = 'home';
			$bodyclass = '';
			include(template('payment_book_weixin'));
		}
	}
    public function agentOp() {
        $book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
        if(empty($book) || $book['member_id'] != $this->member_id) {
            $this->showmessage('预约单不存在', 'index.php?act=agent_marketing', 'error');
        }
        if($book['book_state'] == '20') {
            $this->showmessage('预约付款成功', 'index.php?act=agent_marketing');
        }
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$book['agent_id']."'");
        if($book['payment_code'] == 'alipay') {
            $param = array(
                'subject' => '缴纳保证金',
                'out_trade_no' => $book['out_sn'],
                'price' => $book['book_amount'],
                'notify_url' => SiteUrl.'/api/alipay/notify_agent.php',
                'return_url' => SiteUrl.'/index.php?act=agent_marketing&op=alipay',
            );
            require_once MALL_ROOT.'/api/alipay/alipay.php';
            $requesturl = get_payurl($param);
            echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">document.getElementById(\'payform\').submit();</script>';
            exit;
        } elseif($book['payment_code'] == 'weixin') {
            require_once MALL_ROOT.'/api/weixin/weixin.php';
            $unifiedOrder = new UnifiedOrder_pub();
            $unifiedOrder->setParameter("body", '缴纳保证金');
            $unifiedOrder->setParameter("out_trade_no", $book['out_sn']);
            $unifiedOrder->setParameter("total_fee", $book['book_amount']*100);
            $unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/notify_agent.php');
            $unifiedOrder->setParameter("trade_type", 'NATIVE');
            $result = $unifiedOrder->postXml();
            $result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
            $code_url = $result->code_url;
            $curmodule = 'home';
            $bodyclass = '';
            include(template('payment_agent_weixin'));
        }
    }
	public function orderOp() {
		$order_sn = empty($_GET['order_sn']) ? '' : $_GET['order_sn'];
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_sn='$order_sn'");
		if(empty($order) || $order['member_id'] != $this->member_id) {
			$this->showmessage('该订单不存在', 'index.php?act=order', 'error');	
		}
		if($order['order_state'] == '20') {
			$this->showmessage('支付成功', 'index.php?act=order');	
		}
		$order_address = DB::fetch_first("SELECT * FROM ".DB::table('order_address')." WHERE order_id='".$order['order_id']."'");
		if($order['payment_code'] == 'alipay') {
			$param = array(
				'subject' => '我要购买'.$order['order_goods_name'],
				'out_trade_no' => $order['out_sn'],
				'price' => $order['order_amount'],
				'notify_url' => SiteUrl.'/api/alipay/notify_order.php',
				'return_url' => SiteUrl.'/index.php?act=order&op=alipay',
			);
			require_once MALL_ROOT.'/api/alipay/alipay.php';
			$requesturl = get_payurl($param);
			echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">document.getElementById(\'payform\').submit();</script>';
			exit;
		} elseif($order['payment_code'] == 'weixin') {
			require_once MALL_ROOT.'/api/weixin/weixin.php';
			$unifiedOrder = new UnifiedOrder_pub();
			$unifiedOrder->setParameter("body", '我要购买'.$order['order_goods_name']);
			$unifiedOrder->setParameter("out_trade_no", $order['out_sn']);
			$unifiedOrder->setParameter("total_fee", $order['order_amount']*100);
			$unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/notify_order.php');
			$unifiedOrder->setParameter("trade_type", 'NATIVE');
			$result = $unifiedOrder->postXml();
			$result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
			$code_url = $result->code_url;
			$curmodule = 'home';
			$bodyclass = '';
			include(template('payment_order_weixin'));
		}
	}
	
	public function bespokeOp() {
		$bespoke_sn = empty($_GET['bespoke_sn']) ? '' : $_GET['bespoke_sn'];
		$bespoke = DB::fetch_first("SELECT * FROM ".DB::table('pension_bespoke')." WHERE bespoke_sn='$bespoke_sn'");
		if(empty($bespoke) || $bespoke['member_id'] != $this->member_id) {
			$this->showmessage('该预定不存在', 'index.php?act=order&op=bespoke', 'error');
		}
		if($bespoke['bespoke_state'] == '20') {
			$this->showmessage('预定付款成功', 'index.php?act=order&op=bespoke&op=finish');	
		}
		$room = DB::fetch_first("SELECT * FROM ".DB::table('pension_room')." WHERE room_id='".$bespoke['room_id']."'");
		$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='".$bespoke['pension_id']."'");
		if($bespoke['payment_code'] == 'alipay') {
			$param = array(
				'subject' => '我要预定'.$pension['pension_name'].'的'.$room['room_name'],
				'out_trade_no' => $bespoke['out_sn'],
				'price' => $bespoke['bespoke_amount'],
				'notify_url' => SiteUrl.'/api/alipay/notify_bespoke.php',
				'return_url' => SiteUrl.'/index.php?act=bespoke&op=alipay',
			);
			require_once MALL_ROOT.'/api/alipay/alipay.php';
			$requesturl = get_payurl($param);
			echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">document.getElementById(\'payform\').submit();</script>';
			exit;
		} elseif($bespoke['payment_code'] == 'weixin') {
			require_once MALL_ROOT.'/api/weixin/weixin.php';
			$unifiedOrder = new UnifiedOrder_pub();
			$unifiedOrder->setParameter("body", '我要预定'.$pension['pension_name'].'的'.$room['room_name']);
			$unifiedOrder->setParameter("out_trade_no", $bespoke['out_sn']);
			$unifiedOrder->setParameter("total_fee", $bespoke['bespoke_amount']*100);
			$unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/notify_bespoke.php');
			$unifiedOrder->setParameter("trade_type", 'NATIVE');
			$result = $unifiedOrder->postXml();
			$result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
			$code_url = $result->code_url;
			$curmodule = 'home';
			$bodyclass = '';
			include(template('payment_bespoke_weixin'));
		}
	}
	
	public function rechargeOp() {
		$pdr_sn = empty($_GET['pdr_sn']) ? '' : $_GET['pdr_sn'];
		$recharge = DB::fetch_first("SELECT * FROM ".DB::table('pd_recharge')." WHERE pdr_sn='$pdr_sn'");
		if(empty($recharge) || $recharge['pdr_memberid'] != $this->member_id) {
			$this->showmessage('充值失败', 'index.php?act=recharge', 'error');	
		}
		if($recharge['pdr_payment_code'] == 'alipay') {
			$param = array(
				'subject' => '我要充值'.$recharge['pdr_amount'],
				'out_trade_no' => $recharge['pdr_out_sn'],
				'price' => $recharge['pdr_amount'],
				'notify_url' => SiteUrl.'/api/alipay/notify_recharge.php',
				'return_url' => SiteUrl.'/index.php?act=recharge&op=alipay',
			);
			require_once MALL_ROOT.'/api/alipay/alipay.php';
			$requesturl = get_payurl($param);
			echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">document.getElementById(\'payform\').submit();</script>';
			exit;
		} elseif($recharge['pdr_payment_code'] == 'weixin') {
			require_once MALL_ROOT.'/api/weixin/weixin.php';
			$unifiedOrder = new UnifiedOrder_pub();
			$unifiedOrder->setParameter("body", '我要充值'.$recharge['pdr_amount']);
			$unifiedOrder->setParameter("out_trade_no", $recharge['pdr_out_sn']);
			$unifiedOrder->setParameter("total_fee", $recharge['pdr_amount']*100);
			$unifiedOrder->setParameter("notify_url", SiteUrl.'/api/weixin/notify_recharge.php');
			$unifiedOrder->setParameter("trade_type", 'NATIVE');
			$result = $unifiedOrder->postXml();
			$result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
			$code_url = $result->code_url;
			$curmodule = 'home';
			$bodyclass = '';
			include(template('payment_recharge_weixin'));	
		}
	}
}

?>