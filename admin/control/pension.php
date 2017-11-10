<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pensionControl extends BaseAdminControl {
	public function indexOp() {
		$pension_scale = array('1'=>'50以下', '2'=>'50-100', '3'=>'100以上');
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=pension";
		$state = in_array($_GET['state'], array('show', 'pending', 'unshow')) ? $_GET['state'] : 'show';
		if($state == 'show') {
			$mpurl .= '&state=show';
			$wheresql = " WHERE pension_state=1";
		} elseif($state == 'pending') {
			$mpurl .= '&state=pending';
			$wheresql = " WHERE pension_state=0";
		} elseif($state == 'unshow') {
			$mpurl .= '&state=unshow';
			$wheresql = " WHERE pension_state=2";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&pension_name='.urlencode($search_name);
			$wheresql .= " AND pension_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension').$wheresql." ORDER BY pension_time ASC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$value['pension_qa_image'] = empty($value['pension_qa_image']) ? array() : unserialize($value['pension_qa_image']);
			$pension_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('pension'));
	}
	
	public function unshowOp() {
		$pension_ids = empty($_GET['pension_ids']) ? '' : $_GET['pension_ids'];
		$pension_ids_arr = explode(",", $pension_ids);
		foreach($pension_ids_arr as $key => $value) {
			$pension_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('pension')." SET pension_state=2 WHERE pension_id in ('".implode("','", $pension_ids_in)."')");
		showdialog('关闭成功', 'admin.php?act=pension', 'succ');	
	}
	
	public function showOp() {
		$pension_ids = empty($_GET['pension_ids']) ? '' : $_GET['pension_ids'];
		$pension_ids_arr = explode(",", $pension_ids);
		foreach($pension_ids_arr as $key => $value) {
			$pension_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('pension')." SET pension_state=1 WHERE pension_id in ('".implode("','", $pension_ids_in)."')");
		showdialog('开启成功', 'admin.php?act=pension&state=unshow', 'succ');	
	}
}
?>