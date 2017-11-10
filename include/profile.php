<?php
if(!defined("InMall")) {
	exit("Access Invalid!");
}

class BaseProfileControl {
	protected $setting = array();
	protected $member_id = 0;
	protected $member = array();
	protected $nurse_id = 0;
	protected $nurse = array();
	protected $article_list = array();
	protected $link_list = array();

	public function __construct() {
		$this->init_setting();
		$this->init_member();				
		$this->init_footer();
		$this->init_login();
		$this->order_receive();
		$this->order_return();
		$this->order_finish();
	}
	
	private function init_setting() {
		if(file_exists(MALL_ROOT.'/cache/cache_setting.php')){
			require_once(MALL_ROOT."/cache/cache_setting.php");
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('setting'));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
		}
		$this->setting = $setting;
	}
	
	private function init_member() {
		$mallauth = dgetcookie('mallauth');
		$auth_data = explode('\t', authcode($mallauth, 'DECODE'));		
		list($member_password, $member_id) = empty($auth_data) || count($auth_data) < 2 ? array('', '') : $auth_data;
		if(!empty($member_password) && !empty($member_id)) {
			$cookie_member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
			if(!empty($cookie_member) && $cookie_member['member_password'] == $member_password) {
				$this->member_id = $cookie_member['member_id'];
				$this->member = $cookie_member;
			}
		}
	}
	
	private function init_footer() {
		$query = DB::query("SELECT * FROM ".DB::table('article')." WHERE article_recommend=1 ORDER BY article_sort ASC");
		while($value = DB::fetch($query)) {
			$this->article_list[] = $value;	
		}
		$query = DB::query("SELECT * FROM ".DB::table('link')." ORDER BY link_sort ASC");
		while($value = DB::fetch($query)) {
			$this->link_list[$value['link_type']][] = $value;	
		}
		$this->setting['first_province_list'] = empty($this->setting['first_province_list']) ? array() : unserialize($this->setting['first_province_list']);
		$this->setting['second_province_list'] = empty($this->setting['second_province_list']) ? array() : unserialize($this->setting['second_province_list']);
	}
	
	private function init_login() {
		if(empty($this->member_id)) {
			$this->showmessage('您还未登录', 'index.php?act=login', 'info');	
		}
		$this->nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
		$this->nurse_id = empty($this->nurse['nurse_id']) ? 0 : $this->nurse['nurse_id'];
	}
	
	private function order_receive() {
		$expire_time = time()-604800;
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_state='30' AND return_state=0 AND shipping_time<='".$expire_time."' LIMIT 0, 1");
		if(!empty($order)) {
			$order_data = array();
			$order_data['order_state'] = 40;
			$order_data['receive_time'] = time();
			DB::update('order', $order_data, array('order_id'=>$order['order_id']));
			$log_data = array();
			$log_data['order_id'] = $order['order_id'];
			$log_data['order_state'] = 40;
			$log_data['order_intro'] = '订单收货';
			$log_data['state_info'] = '到时收货';
			$log_data['log_time'] = time();
			Db::insert('order_log', $log_data);
		}
	}
	
	private function order_return() {
		$expire_time = time()-604800;
		$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_state=1 AND physical_state=3 AND seller_shipping_time<='".$expire_time."' LIMIT 0, 1");	
		if(!empty($order_return)) {
			DB::update('order_return', array('physical_state'=>4), array('return_id'=>$order_return['return_id']));
			$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='".$order_return['return_id']."'");
			$order_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_goods')." WHERE rec_id='".$order_return_goods['rec_id']."'");
			$goods_returnnum = $order_goods['goods_returnnum']-$order_return_goods['goods_returnnum'];
			$goods_return_state = $goods_returnnum>0 ? 1 : 0;
			DB::update('order_goods', array('goods_returnnum'=>$goods_returnnum, 'goods_return_state'=>$goods_return_state), array('rec_id'=>$order_return_goods['rec_id']));
			$return_state = 0;
			$query = DB::query("SELECT * FROM ".DB::table('order_return')." WHERE order_id='".$order_return['order_id']."'");
			while($value = DB::fetch($query)) {
				if($value['return_state'] == 1 && $value['physical_state'] != 4) {
					$return_state = 1;
				} elseif($value['return_state'] == 3) {
					$return_state = 1;
				}
			}
			DB::update('order', array('return_state'=>$return_state), array('order_id'=>$order_return['order_id']));	
		}
	}
	
	private function order_finish() {
		$expire_time = time()-604800;
		$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_state='40' AND shipping_time<='".$expire_time."' LIMIT 0, 1");
		if(!empty($order)) {
			$order_data = array();
			$order_data['order_state'] = 50;
			$order_data['finish_time'] = time();
			DB::update('order', $order_data, array('order_id'=>$order['order_id']));
			$log_data = array();
			$log_data['order_id'] = $order['order_id'];
			$log_data['order_state'] = 50;
			$log_data['order_intro'] = '订单完成';
			$log_data['state_info'] = '到时完成';
			$log_data['log_time'] = time();
			Db::insert('order_log', $log_data);
			$profit = DB::fetch_first("SELECT * FROM ".DB::table('store_profit')." WHERE order_id='".$order['order_id']."'");
			if(!empty($profit)) {
				$profit_data = array(
					'is_freeze' => 0,
					'add_time' => time(),
				);
				DB::update('store_profit', $profit_data, array('profit_id'=>$profit['profit_id']));
				DB::query("UPDATE ".DB::table('store')." SET pool_amount=pool_amount-".$profit['profit_amount'].", available_amount=available_amount+".$profit['profit_amount']." WHERE store_id='".$order['store_id']."'");	
			}
		}
	}
	
	protected function showmessage($msg, $url='', $msg_type='succ') {
		$curmodule = 'home';
		$bodyclass = 'gray-bg';
		include template('showmessage');
		exit();
	}
}
?>