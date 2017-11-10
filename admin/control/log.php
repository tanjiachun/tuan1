<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class logControl extends BaseAdminControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=log";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('admin_log'));
		$query = DB::query("SELECT * FROM ".DB::table('admin_log')." ORDER BY log_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$log_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('log'));
	}
}
?>