<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class redControl extends BaseAdminControl {
	public function indexOp() {
		$article_title = empty($_GET['article_title']) ? '' : $_GET['article_title'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=red";
		if(!empty($red_t_title)) {			
			$mpurl .= "&red_t_title=".urlencode($red_t_title);
			$wheresql = " WHERE red_t_title like '%".$red_t_title."%'";	
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('red_template').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('red_template').$wheresql." ORDER BY red_t_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$red_template_list[] = $value;
		}
		$red_cate_array = array('0'=>'全场通用', '1'=>'阿姨看护专用', '2'=>'养老商品专用', '3'=>'养老机构专用');
		$red_type_array = array('new'=>'新用户红包', 'activity'=>'活动红包', 'reward'=>'奖励红包');
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('red'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$red_t_title = empty($_POST['red_t_title']) ? '' : $_POST['red_t_title'];
			$red_t_price = empty($_POST['red_t_price']) ? '' : $_POST['red_t_price'];
			$red_t_period_type = !in_array($_POST['red_t_period_type'], array('duration', 'timezone')) ? '' : $_POST['red_t_period_type'];
			$red_t_days = empty($_POST['red_t_days']) ? 0 : intval($_POST['red_t_days']);
			$red_t_starttime = empty($_POST['red_t_starttime']) ? 0 : strtotime($_POST['red_t_starttime']);
			$red_t_endtime = empty($_POST['red_t_endtime']) ? 0 : strtotime($_POST['red_t_endtime']);
			$red_t_limit = empty($_POST['red_t_limit']) ? 0 : floatval($_POST['red_t_limit']);
			$red_t_cate_id = empty($_POST['red_t_cate_id']) ? 0 : intval($_POST['red_t_cate_id']);
			$red_t_total = empty($_POST['red_t_total']) ? 0 : intval($_POST['red_t_total']);
			$red_t_type = !in_array($_POST['red_t_type'], array('new', 'activity', 'reward')) ? '' : $_POST['red_t_type'];
			$red_rule_starttime = empty($_POST['red_rule_starttime']) ? 0 : strtotime($_POST['red_rule_starttime']);
			$red_rule_endtime = empty($_POST['red_rule_endtime']) ? 0 : strtotime($_POST['red_rule_endtime']);
			$red_t_amount = empty($_POST['red_t_amount']) ? 0 : floatval($_POST['red_t_amount']);
			if(empty($red_t_title)) {
				showdialog('请输入红包名称');
			}
			if(empty($red_t_price)) {
				showdialog('请输入红包面额');
			}
			if(empty($red_t_period_type)) {
				showdialog('请选择红包有效期');
			}
			if($red_t_period_type == 'duration' && empty($red_t_days)) {
				showdialog('请输入天数');
			}
			if($red_t_period_type == 'timezone' && (empty($red_t_starttime) || empty($red_t_endtime))) {
				showdialog('请输入有效时间');
			}
			if(empty($red_t_type)) {
				showdialog('请选择红包类型');
			}
			if($red_t_period_type == 'activity' && (empty($red_rule_starttime) || empty($red_rule_endtime))) {
				showdialog('请输入活动时间');
			}
			if($red_t_period_type == 'reward' && empty($red_t_amount)) {
				showdialog('请输入奖励金额限制');
			}
			$data = array(
				'red_t_title' => $red_t_title,
				'red_t_price' => $red_t_price,
				'red_t_period_type' => $red_t_period_type,
				'red_t_days' => $red_t_days,
				'red_t_starttime' => $red_t_starttime,
				'red_t_endtime' => $red_t_endtime+3600*24-1,
				'red_t_limit' => $red_t_limit,
				'red_t_cate_id' => $red_t_cate_id,
				'red_t_total' => $red_t_total,
				'red_t_type' => $red_t_type,
				'red_rule_starttime' => $red_rule_starttime,
				'red_rule_endtime' => $red_rule_endtime+3600*24-1,
				'red_t_amount' => $red_t_amount,
				'red_t_addtime' => time(),
			);
			$red_t_id = DB::insert('red_template', $data, 1);
			showdialog('保存成功', 'admin.php?act=red', 'succ');
		} else {
			include(template('red_add'));
		}
	}
	
	public function editOp() {
		if(submitcheck()) {
			$red_t_id = empty($_POST['red_t_id']) ? 0 : intval($_POST['red_t_id']);
			$red_t_title = empty($_POST['red_t_title']) ? '' : $_POST['red_t_title'];
			$red_t_price = empty($_POST['red_t_price']) ? '' : $_POST['red_t_price'];
			$red_t_period_type = !in_array($_POST['red_t_period_type'], array('duration', 'timezone')) ? '' : $_POST['red_t_period_type'];
			$red_t_days = empty($_POST['red_t_days']) ? 0 : intval($_POST['red_t_days']);
			$red_t_starttime = empty($_POST['red_t_starttime']) ? 0 : strtotime($_POST['red_t_starttime']);
			$red_t_endtime = empty($_POST['red_t_endtime']) ? 0 : strtotime($_POST['red_t_endtime']);
			$red_t_limit = empty($_POST['red_t_limit']) ? 0 : floatval($_POST['red_t_limit']);
			$red_t_cate_id = empty($_POST['red_t_cate_id']) ? 0 : intval($_POST['red_t_cate_id']);
			$red_t_total = empty($_POST['red_t_total']) ? 0 : intval($_POST['red_t_total']);
			$red_t_type = !in_array($_POST['red_t_type'], array('new', 'activity', 'reward')) ? '' : $_POST['red_t_type'];
			$red_rule_starttime = empty($_POST['red_rule_starttime']) ? 0 : strtotime($_POST['red_rule_starttime']);
			$red_rule_endtime = empty($_POST['red_rule_endtime']) ? 0 : strtotime($_POST['red_rule_endtime']);
			$red_t_amount = empty($_POST['red_t_amount']) ? 0 : floatval($_POST['red_t_amount']);
			if(empty($red_t_title)) {
				showdialog('请输入红包名称');
			}
			if(empty($red_t_price)) {
				showdialog('请输入红包面额');
			}
			if(empty($red_t_period_type)) {
				showdialog('请选择红包有效期');
			}
			if($red_t_period_type == 'duration' && empty($red_t_days)) {
				showdialog('请输入天数');
			}
			if($red_t_period_type == 'timezone' && (empty($red_t_starttime) || empty($red_t_endtime))) {
				showdialog('请输入有效时间');
			}
			if(empty($red_t_type)) {
				showdialog('请选择红包类型');
			}
			if($red_t_period_type == 'activity' && (empty($red_rule_starttime) || empty($red_rule_endtime))) {
				showdialog('请输入活动时间');
			}
			if($red_t_period_type == 'reward' && empty($red_t_amount)) {
				showdialog('请输入奖励金额限制');
			}
			$data = array(
				'red_t_title' => $red_t_title,
				'red_t_price' => $red_t_price,
				'red_t_period_type' => $red_t_period_type,
				'red_t_days' => $red_t_days,
				'red_t_starttime' => $red_t_starttime,
				'red_t_endtime' => $red_t_endtime+3600*24-1,
				'red_t_limit' => $red_t_limit,
				'red_t_cate_id' => $red_t_cate_id,
				'red_t_total' => $red_t_total,
				'red_t_type' => $red_t_type,
				'red_rule_starttime' => $red_rule_starttime,
				'red_rule_endtime' => $red_rule_endtime+3600*24-1,
				'red_t_amount' => $red_t_amount,
			);
			DB::update('red_template', $data, array('red_t_id'=>$red_t_id));
			showdialog('保存成功', 'admin.php?act=red', 'succ');
		} else {
			$red_t_id = empty($_GET['red_t_id']) ? 0 : intval($_GET['red_t_id']);
			$red_template = DB::fetch_first("SELECT * FROM ".DB::table("red_template")." WHERE red_t_id='$red_t_id'");
			include(template('red_edit'));
		}
	}
	
	public function delOp() {
		$red_t_ids = empty($_GET['red_t_ids']) ? '' : $_GET['red_t_ids'];
		$red_t_ids_arr = explode(",", $red_t_ids);
		foreach($red_t_ids_arr as $key => $value) {
			$red_t_ids_in[] = intval($value);
		}
		DB::query("DELETE FROM ".DB::table('red_template')." WHERE red_t_id in ('".implode("','", $red_t_ids)."')");
		showdialog('删除成功', 'admin.php?act=red', 'succ');
	}
	
	public function recordOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$red_t_id = empty($_GET['red_t_id']) ? 0 : intval($_GET['red_t_id']);
		$mpurl = "index.php?act=red&op=record&red_t_id=$red_t_id";
		$wheresql = " WHERE red_t_id='$red_t_id'";
		$state = !in_array($_GET['state'], array('giveout', 'outused', 'used')) ? 'giveout' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'outused') {
			$wheresql .= " AND red_state=0";
		} elseif($state == 'used') {
			$wheresql .= " AND red_state=1";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('red').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('red').$wheresql." ORDER BY red_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$red_list[] = $value;
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$member_list[$value['member_id']] = $value;
			}	
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('red_record'));
	}
}
?>