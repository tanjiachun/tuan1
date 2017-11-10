<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class ppensionControl extends BaseAdminControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=ppension";
		$wheresql = " WHERE pension_state=1";
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$wheresql .= " AND pension_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension').$wheresql." ORDER BY pension_time ASC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$pension_ids[] = $value['pension_id'];
			$pension_list[] = $value;
		}
		if(!empty($pension_ids)) {
			$query = DB::query("SELECT pension_id, COUNT(*) as room_number FROM ".DB::table('pension_room')." WHERE pension_id in ('".implode("','", $pension_ids)."') GROUP BY pension_id");
			while($value = DB::fetch($query)) {
				$room_list[$value['pension_id']] = $value['room_number'];
			}
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('ppension'));
	}
	
	public function profitOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$pension_id = empty($_GET['pension_id']) ? 0 : intval($_GET['pension_id']);
		$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='$pension_id'");
		$mpurl = "admin.php?act=ppension&op=profit&pension_id=".$pension_id;		
		$wheresql = " WHERE pension_id='$pension_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_profit').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension_profit').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			if($value['profit_type'] == '1') {
				$value['mark'] = '+';
			} else {
				$value['mark'] = '-';
			}
			$profit_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('ppension_profit'));
	}
	
	public function cashOp() {
		if(submitcheck()) {
			$pension_id = empty($_POST['pension_id']) ? 0 : intval($_POST['pension_id']);
			$cash_amount = empty($_POST['cash_amount']) ? 0 : intval($_POST['cash_amount']);
			$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='$pension_id'");
			if(empty($pension)) {
				exit(json_encode(array('msg'=>'机构不存在')));
			}
			if(empty($cash_amount)) {
				exit(json_encode(array('msg'=>'请输入汇款金额')));
			}
			if($pension['available_amount'] < $cash_amount) {
				exit(json_encode(array('msg'=>'可用金额不足汇款金额')));
			}
			$profit_data = array(
				'pension_id' => $pension['pension_id'],
				'profit_stage' => 'cash',
				'profit_type' => 0,
				'profit_amount' => $cash_amount,
				'profit_desc' => '机构收益提现',
				'is_freeze' => 0,
				'add_time' => time(),
			);
			DB::insert('pension_profit', $profit_data);
			DB::query("UPDATE ".DB::table('pension')." SET available_amount=available_amount-$cash_amount WHERE pension_id='".$pension['pension_id']."'");
			$log_data = array(
				'log_desc' => $this->admin['admin_name'].'给机构 '.$pension['pension_name'].' 汇款，汇款金额：'.$cash_amount.'元',
				'admin_id' => $this->admin_id,
				'admin_name' => $this->admin['admin_name'],
				'log_time' => time(),
			);
			DB::insert('admin_log', $log_data);
			exit(json_encode(array('done'=>'true', 'available'=>priceformat($pension['available_amount']-$cash_amount))));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
}
?>