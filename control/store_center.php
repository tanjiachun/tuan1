<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_centerControl extends BaseStoreControl {
	public function indexOp() {
		$pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE store_id='$this->store_id' AND order_state=10");
		$payment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE store_id='$this->store_id' AND order_state=20");
		$deliver_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE store_id='$this->store_id' AND order_state=30");
		$return_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order_return')." WHERE store_id='$this->store_id'");
		$query = DB::query("SELECT * FROM ".DB::table('order')." WHERE store_id='$this->store_id' AND order_state=20 ORDER BY add_time DESC LIMIT 0, 3");
		while($value = DB::fetch($query)) {
			$order_ids[] = $value['order_id'];
			$order_list[] = $value;
		}
		if(!empty($order_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order_address')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_address[$value['order_id']] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_goods[$value['order_id']][] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('order_return')." WHERE store_id='$this->store_id' ORDER BY return_addtime DESC LIMIT 0, 3");
		while($value = DB::fetch($query)) {
			$return_ids[] = $value['return_id'];
			$order_ids[] = $value['order_id'];
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
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_center'));
	}
}

?>