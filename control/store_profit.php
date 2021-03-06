<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_profitControl extends BaseStoreControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_profit";
		$wheresql = " WHERE store_id='$this->store_id'";
		$state = !in_array($_GET['state'],array('income', 'expend', 'freeze')) ? '' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'income') {
			$wheresql .= " AND profit_type=1 AND is_freeze=0";
		} elseif($state == 'expend') {
			$wheresql .= " AND profit_type=0 AND is_freeze=0";
		} elseif($state == 'freeze') {
			$wheresql .= " AND is_freeze=1";
		}
		$start_time = empty($_GET['start_time']) ? '' : $_GET['start_time'];
		$end_time = empty($_GET['end_time']) ? '' : $_GET['end_time'];
		if(!empty($start_time)) {
			$mpurl .= "&start_time=$start_time";
			$start_time = strtotime($start_time);
			$wheresql .= " AND add_time>=$start_time";
		}
		if(!empty($end_time)) {
			$mpurl .= "&end_time=$end_time";
			$end_time = strtotime($end_time)+3600*24-1;
			$wheresql .= " AND add_time<=".$end_time;
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('store_profit').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('store_profit').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			if($value['profit_type'] == '1') {
				$value['mark'] = '+';
				$value['markclass'] = 'red';
			} else {
				$value['mark'] = '-';
				$value['markclass'] = 'green';
			}
			$profit_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_profit'));
	}
}

?>