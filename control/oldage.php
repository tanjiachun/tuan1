<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class oldageControl extends BaseProfileControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=oldage";
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('oldage').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('oldage').$wheresql." ORDER BY oldage_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			if($value['oldage_type'] == '1') {
				$value['mark'] = '+';
				$value['markclass'] = 'red';
			} else {
				$value['mark'] = '-';
				$value['markclass'] = 'green';
			}
			$oldage_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('oldage'));
	}
}

?>