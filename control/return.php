<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class returnControl extends BaseProfileControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=return";
		$wheresql = " WHERE member_id='$this->member_id'";
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= "&search_name=$search_name";
			$wheresql .= " AND return_sn like '%$search_name%'";	
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order_return').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('order_return').$wheresql." ORDER BY return_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$return_ids[] = $value['return_id'];
			$order_ids[] = $value['order_id'];
			$value['return_image'] = empty($value['return_image']) ? '' : unserialize($value['return_image']);
			$return_list[] = $value;
		}
		if(!empty($return_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id in ('".implode("','", $return_ids)."')");
			while($value = DB::fetch($query)) {
				$return_goods[$value['return_id']] = $value;
			}
		}
		if(!empty($order_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order_address')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_address[$value['order_id']] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('express')." ORDER BY express_order ASC");
		while($value = DB::fetch($query)) {
			$express_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('return'));
	}
	
	public function physicalOp() {
		$return_id = empty($_GET['return_id']) ? 0 : intval($_GET['return_id']);
		$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$return_id'");
		if(empty($order_return) || $order_return['member_id'] != $this->member_id) {
			$this->showmessage('退货单不存在', 'index.php?act=return', 'error');
		}
		$order_return['return_image'] = empty($order_return['return_image']) ? '' : unserialize($order_return['return_image']);
		$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='$return_id'");
		if(!empty($order_return['express_code']) && !empty($order_return['shipping_code'])) {
			$url = 'http://www.kuaidi100.com/query?type='.$order_return['express_code'].'&postid='.$order_return['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
			$content = geturlfile($url);
			$delivery = json_decode($content, true);
			$delivery_data = array_reverse($delivery['data']);
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('return_physical'));
	}
	
	public function seller_physicalOp() {
		$return_id = empty($_GET['return_id']) ? 0 : intval($_GET['return_id']);
		$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$return_id'");
		if(empty($order_return) || $order_return['member_id'] != $this->member_id) {
			$this->showmessage('退货单不存在', 'index.php?act=return', 'error');
		}
		$order_return['return_image'] = empty($order_return['return_image']) ? '' : unserialize($order_return['return_image']);
		$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='$return_id'");
		if(!empty($order_return['seller_express_code']) && !empty($order_return['seller_shipping_code'])) {
			$url = 'http://www.kuaidi100.com/query?type='.$order_return['seller_express_code'].'&postid='.$order_return['seller_shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
			$content = geturlfile($url);
			$delivery = json_decode($content, true);
			$delivery_data = array_reverse($delivery['data']);
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('return_seller_physical'));
	} 
	
	public function deliverOp() {
		if(submitcheck()) {
			$deliver_id = empty($_POST['deliver_id']) ? 0 : $_POST['deliver_id'];
			$express_id = empty($_POST['express_id']) ? 0 : intval($_POST['express_id']);
			$shipping_code = empty($_POST['shipping_code']) ? '' : $_POST['shipping_code'];
			if(empty($express_id)) {
				exit(json_encode(array('msg'=>'请选择快递公司')));
			}
			if(empty($shipping_code)) {
				exit(json_encode(array('msg'=>'请输入快递编号')));
			}
			$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$deliver_id'");
			if(empty($order_return) || $order_return['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'退货单不存在')));
			}			
			if($order_return['return_state'] != '1' || $order_return['physical_state'] != '0') {
				exit(json_encode(array('msg'=>'退货单不能发货')));	
			}
			$express = DB::fetch_first("SELECT * FROM ".DB::table('express')." WHERE express_id='$express_id'");
			$express_name = $express['express_name'];
			$express_code = $express['express_code'];
			$data = array(
				'physical_state' => 1,
				'express_name'=>$express_name,
				'express_code'=>$express_code,
				'shipping_code'=>$shipping_code,
				'shipping_time'=>time()
			);
			DB::update('order_return', $data, array('return_id'=>$deliver_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
	
	public function finishOp() {
		if(submitcheck()) {
			$finish_id = empty($_POST['finish_id']) ? 0 : $_POST['finish_id'];
			$order_return = DB::fetch_first("SELECT * FROM ".DB::table('order_return')." WHERE return_id='$finish_id'");
			if(empty($order_return) || $order_return['member_id'] != $this->member_id) {
				exit(json_encode(array('msg'=>'退货单不存在')));
			}			
			if($order_return['return_state'] != '1' || $order_return['physical_state'] != '3') {
				exit(json_encode(array('msg'=>'退货单不能完成')));	
			}
			DB::update('order_return', array('physical_state'=>4), array('return_id'=>$finish_id));
			$order_return_goods = DB::fetch_first("SELECT * FROM ".DB::table('order_return_goods')." WHERE return_id='$finish_id'");
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
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));	
		}
	}
}

?>