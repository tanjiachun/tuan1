<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class couponControl extends BaseProfileControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 12;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=coupon";
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('coupon').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('coupon').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$store_ids[] = $value['store_id'];
			$value['coupon_goods_id'] = empty($value['coupon_goods_id']) ? array() : explode(',', $value['coupon_goods_id']);
			foreach($value['coupon_goods_id'] as $goods_id) {
				$goods_ids[] = $goods_id;
			}
			$coupon_list[] = $value;
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		if(!empty($goods_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids)."')");
			while($value = DB::fetch($query)) {
				$goods_list[$value['goods_id']] = $value['goods_name'];
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('coupon'));
	}
}

?>