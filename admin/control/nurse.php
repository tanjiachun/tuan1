<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurseControl extends BaseAdminControl {
	public function state_cideciOp(){
		$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
		$state_cideci=empty($_POST['state_cideci']) ? 0 : intval($_POST['state_cideci']);
		DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=$state_cideci WHERE nurse_id=$nurse_id");
		exit(json_encode(array('nurse_id'=>$nurse_id,'state_cideci'=>$state_cideci)));
	}
	public function state_bainOp(){
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        exit(json_encode(array('done'=>'true','state_cideci'=>$nurse['state_cideci'])));
	}
	public function indexOp() {
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=nurse";
		$state = in_array($_GET['state'], array('status','pending', 'working', 'customer', 'unshow')) ? $_GET['state'] : 'status';
		if($state=='staus'){
			$mpurl.='$state=status';

		}elseif($state == 'pending') {
			$mpurl .= '&state=pending';
			$wheresql = " WHERE nurse_state=1 AND work_state=0";
		} elseif($state == 'working') {	
			$mpurl .= '&state=working';
			$wheresql = " WHERE nurse_state=1 AND work_state=1";
		} elseif($state == 'customer') {	
			$mpurl .= '&state=customer';
			$wheresql = " WHERE nurse_state=1 AND complaint_state=1";
		} elseif($state == 'unshow') {
			$mpurl .= '&state=unshow';
			$wheresql = " WHERE nurse_state=2";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$wheresql .= " AND nurse_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." ORDER BY status_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['nurse_qa_image'] = empty($value['nurse_qa_image']) ? array() : unserialize($value['nurse_qa_image']);
			$nurse_list[] = $value;
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}	
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('nurse'));
	}
	public function get_statusOp(){
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$get_time = DB::fetch_first("SELECT get_time,nurse_status FROM ".DB::table('record_getPhone')." WHERE nurse_id='$nurse_id' AND nurse_status!='3' ORDER BY get_time DESC");
		exit(json_encode(array('get_time'=>$get_time)));
	}
	public function unshowOp() {
		$state = in_array($_GET['state'], array('pending', 'working', 'customer')) ? $_GET['state'] : 'pending';
		$nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
		$nurse_ids_arr = explode(",", $nurse_ids);
		foreach($nurse_ids_arr as $key => $value) {
			$nurse_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('nurse')." SET nurse_state=2 WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		showdialog('冻结成功', 'admin.php?act=nurse&state='.$state, 'succ');	
	}
	
	public function resolveOp() {
		$nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
		$nurse_ids_arr = explode(",", $nurse_ids);
		foreach($nurse_ids_arr as $key => $value) {
			$nurse_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('nurse')." SET complaint_state=0 WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		showdialog('解决成功', 'admin.php?act=nurse&state=customer', 'succ');
	}
	
	public function showOp() {
		$nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
		$nurse_ids_arr = explode(",", $nurse_ids);
		foreach($nurse_ids_arr as $key => $value) {
			$nurse_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('nurse')." SET nurse_state=1 WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		showdialog('开启成功', 'admin.php?act=nurse&state=unshow', 'succ');	
	}
	
	public function delOp() {
		$nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
		$nurse_ids_arr = explode(",", $nurse_ids);
		foreach($nurse_ids_arr as $key => $value) {
			$nurse_ids_in[] = intval($value);
		}
		DB::query("DELETE FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		DB::query("DELETE FROM ".DB::table('nurse_book')." WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		DB::query("DELETE FROM ".DB::table('nurse_comment')." WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		DB::query("DELETE FROM ".DB::table('nurse_profit')." WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		DB::query("DELETE FROM ".DB::table('nurse_stat')." WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		showdialog('删除成功', 'admin.php?act=nurse&state=unshow', 'succ');	
	}
	
	public function pendingOp() {
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=nurse&op=pending";
		$wheresql = " WHERE nurse_state=0";
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$wheresql .= " AND nurse_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." ORDER BY nurse_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['nurse_qa_image'] = empty($value['nurse_qa_image']) ? array() : unserialize($value['nurse_qa_image']);
			$nurse_list[] = $value;
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}	
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('nurse_pending'));
	}
	
	public function pending_unshowOp() {
		$nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
		$nurse_ids_arr = explode(",", $nurse_ids);
		foreach($nurse_ids_arr as $key => $value) {
			$nurse_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('nurse')." SET nurse_state=2 WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		showdialog('审核拒绝成功', 'admin.php?act=nurse&op=pending', 'succ');	
	}
	
	public function pending_showOp() {
		$nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
		$nurse_ids_arr = explode(",", $nurse_ids);
		foreach($nurse_ids_arr as $key => $value) {
			$nurse_ids_in[] = intval($value);
		}
		DB::query("UPDATE ".DB::table('nurse')." SET nurse_state=1 WHERE nurse_id in ('".implode("','", $nurse_ids_in)."')");
		showdialog('审核通过成功', 'admin.php?act=nurse&op=pending', 'succ');	
	}
}
?>