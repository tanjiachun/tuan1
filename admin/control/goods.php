<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class goodsControl extends BaseAdminControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=goods";
		$state = in_array($_GET['state'], array('show', 'pending', 'unshow')) ? $_GET['state'] : 'show';
		if($state == 'show') {
			$mpurl .= '&state=show';
			$wheresql = " WHERE goods_state=1";
		} elseif($state == 'pending') {
			$mpurl .= '&state=pending';
			$wheresql = " WHERE goods_state=3";
		} elseif($state == 'unshow') {
			$mpurl .= '&state=unshow';
			$wheresql = " WHERE goods_state=2";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_name='$search_name'");
			while($value = DB::fetch($query)) {
				$store_ids[] = $value['store_id'];
			}		
			if(!empty($store_ids)) {
				$wheresql .= " AND (goods_name like '%".$search_name."%' OR store_id in ('".implode("','", $store_ids)."'))";
			} else {
				$wheresql .= " AND goods_name like '%".$search_name."%'";
			}
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('goods').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('goods').$wheresql." ORDER BY goods_add_time ASC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$store_ids[] = $value['store_id'];
			$goods_list[] = $value;
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('goods'));
	}
	
	public function unshowOp() {
		$goods_ids = empty($_GET['goods_ids']) ? '' : $_GET['goods_ids'];
		$goods_ids_arr = explode(",", $goods_ids);
		foreach($goods_ids_arr as $key => $value) {
			$goods_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('goods')." SET goods_state=2 WHERE goods_id in ('".implode("','", $goods_ids_in)."')");
		showdialog('下架成功', 'admin.php?act=goods&state=unshow', 'succ');	
	}
	
	public function showOp() {
		$goods_ids = empty($_GET['goods_ids']) ? '' : $_GET['goods_ids'];
		$goods_ids_arr = explode(",", $goods_ids);
		foreach($goods_ids_arr as $key => $value) {
			$goods_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('goods')." SET goods_state=1 WHERE goods_id in ('".implode("','", $goods_ids_in)."')");
		showdialog('上架成功', 'admin.php?act=goods', 'succ');	
	}
	
	public function delOp() {
		$goods_ids = empty($_GET['goods_ids']) ? '' : $_GET['goods_ids'];
		$goods_ids_arr = explode(",", $goods_ids);
		foreach($goods_ids_arr as $key => $value) {
			$goods_ids_in[] = intval($value);
		}
		DB::query("DELETE FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids_in)."')");
		DB::query("DELETE FROM ".DB::table('goods_spec')." WHERE goods_id in ('".implode("','", $goods_ids_in)."')");
		showdialog('删除成功', 'admin.php?act=goods&op=goods_unshow', 'succ');	
	}
}
?>