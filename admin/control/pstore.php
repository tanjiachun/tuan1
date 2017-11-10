<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pstoreControl extends BaseAdminControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=pstore";
		$wheresql = " WHERE store_state=1";
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
		include(template('pstore'));
	}
	
	public function profitOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$store_id = empty($_GET['store_id']) ? 0 : intval($_GET['store_id']);
		$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='$store_id'");
		$mpurl = "admin.php?act=pstore&op=profit&store_id=".$store_id;		
		$wheresql = " WHERE store_id='$store_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('store_profit').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('store_profit').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			if($value['profit_type'] == '1') {
				$value['mark'] = '+';
			} else {
				$value['mark'] = '-';
			}
			$profit_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('pstore_profit'));
	}
	
	public function cashOp() {
		if(submitcheck()) {
			$store_id = empty($_POST['store_id']) ? 0 : intval($_POST['store_id']);
			$cash_amount = empty($_POST['cash_amount']) ? 0 : intval($_POST['cash_amount']);
			$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='$store_id'");
			if(empty($store)) {
				exit(json_encode(array('msg'=>'店铺不存在')));
			}
			if(empty($cash_amount)) {
				exit(json_encode(array('msg'=>'请输入汇款金额')));
			}
			if($store['available_amount'] < $cash_amount) {
				exit(json_encode(array('msg'=>'可用金额不足汇款金额')));
			}
			$profit_data = array(
				'store_id' => $store['store_id'],
				'profit_stage' => 'cash',
				'profit_type' => 0,
				'profit_amount' => $cash_amount,
				'profit_desc' => '店铺收益提现',
				'is_freeze' => 0,
				'add_time' => time(),
			);
			DB::insert('store_profit', $profit_data);
			DB::query("UPDATE ".DB::table('store')." SET available_amount=available_amount-$cash_amount WHERE store_id='".$store['store_id']."'");
			$log_data = array(
				'log_desc' => $this->admin['admin_name'].'给店铺 '.$store['store_name'].' 汇款，汇款金额：'.$cash_amount.'元',
				'admin_id' => $this->admin_id,
				'admin_name' => $this->admin['admin_name'],
				'log_time' => time(),
			);
			DB::insert('admin_log', $log_data);
			exit(json_encode(array('done'=>'true', 'available'=>priceformat($store['available_amount']-$cash_amount))));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
}
?>