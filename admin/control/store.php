<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class storeControl extends BaseAdminControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=store";
		$wheresql = " WHERE store_state=1";
		$state = in_array($_GET['state'], array('show', 'unshow')) ? $_GET['state'] : 'show';
		if($state == 'show') {
			$mpurl .= '&state=show';
			$wheresql = " WHERE store_state=1";
		} elseif($state == 'unshow') {
			$mpurl .= '&state=unshow';
			$wheresql = " WHERE store_state=2";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$wheresql .= " AND store_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('store').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('store').$wheresql." ORDER BY store_time ASC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$class_ids[] = $value['class_id'];
			$store_list[] = $value;
		}
		if(!empty($class_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('class')." WHERE class_id in ('".implode("','", $class_ids)."')");
			while($value = DB::fetch($query)) {
				$class_list[$value['class_id']] = $value['class_name'];
			}	
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('store'));
	}
	
	public function unshowOp() {
		$store_ids = empty($_GET['store_ids']) ? '' : $_GET['store_ids'];
		$store_ids_arr = explode(",", $store_ids);
		foreach($store_ids_arr as $key => $value) {
			$store_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('store')." SET store_state=2 WHERE store_id in ('".implode("','", $store_ids_in)."')");
		showdialog('关闭成功', 'admin.php?act=store', 'succ');	
	}
	
	public function showOp() {
		$store_ids = empty($_GET['store_ids']) ? '' : $_GET['store_ids'];
		$store_ids_arr = explode(",", $store_ids);
		foreach($store_ids_arr as $key => $value) {
			$store_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('store')." SET store_state=1 WHERE store_id in ('".implode("','", $store_ids_in)."')");
		showdialog('开启成功', 'admin.php?act=store&state=unshow', 'succ');	
	}
}
?>