<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class predepositControl extends BaseProfileControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=predeposit";
		$wheresql = " WHERE pdl_memberid='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pd_log').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pd_log').$wheresql." ORDER BY pdl_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			if($value['pdl_type'] == '1') {
				$value['mark'] = '+';
				$value['markclass'] = 'red';
			} else {
				$value['mark'] = '-';
				$value['markclass'] = 'green';
			}
			$pd_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'home';
		$bodyclass = 'gray-bg';
		include(template('predeposit'));	
	}
}

?>