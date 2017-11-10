<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agentControl extends BaseAdminControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=agent";
		$state = in_array($_GET['state'], array('show', 'pending', 'unshow')) ? $_GET['state'] : 'show';
		if($state == 'show') {
			$mpurl .= '&state=show';
			$wheresql = " WHERE agent_state=1";
		} elseif($state == 'pending') {
			$mpurl .= '&state=pending';
			$wheresql = " WHERE agent_state=0";
		} elseif($state == 'unshow') {
			$mpurl .= '&state=unshow';
			$wheresql = " WHERE agent_state=2";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&agent_name='.urlencode($search_name);
			$wheresql .= " AND agent_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('agent').$wheresql." ORDER BY agent_time ASC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$value['agent_qa_image'] = empty($value['agent_qa_image']) ? array() : unserialize($value['agent_qa_image']);
			$agent_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('agent'));
	}
	
	public function unshowOp() {
		$agent_ids = empty($_GET['agent_ids']) ? '' : $_GET['agent_ids'];
		$agent_ids_arr = explode(",", $agent_ids);
		foreach($agent_ids_arr as $key => $value) {
			$agent_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('agent')." SET agent_state=2 WHERE agent_id in ('".implode("','", $agent_ids_in)."')");
		showdialog('关闭成功', 'admin.php?act=agent', 'succ');	
	}
	
	public function showOp() {
		$agent_ids = empty($_GET['agent_ids']) ? '' : $_GET['agent_ids'];
		$agent_ids_arr = explode(",", $agent_ids);
		foreach($agent_ids_arr as $key => $value) {
			$agent_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('agent')." SET agent_state=1,revise_state=1 WHERE agent_id in ('".implode("','", $agent_ids_in)."')");
		showdialog('开启成功', 'admin.php?act=agent&state=unshow', 'succ');	
	}
}
?>