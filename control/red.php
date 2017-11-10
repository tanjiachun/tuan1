<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class redControl extends BaseProfileControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=red";
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('red').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('red').$wheresql." ORDER BY red_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$red_list[] = $value;
		}
		$red_cate_array = array('0'=>'全场通用', '1'=>'家政人员看护专用', '2'=>'养老商品专用', '3'=>'养老机构专用');
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('red'));
	}
}

?>