<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pnurseControl extends BaseAdminControl {
	public function indexOp() {
	    $total_income = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=0 ORDER BY finance_time DESC");
	    $book_income = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_type='book' AND finance_state=0 ORDER BY finance_time DESC");
	    $recharge_income = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_type='recharge' AND finance_state=0 ORDER BY finance_time DESC");
	    $bail_income = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_type='bail' AND finance_state=0 ORDER BY finance_time DESC");
	    $total_defray = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=1 ORDER BY finance_time DESC");
        $deposit_defray = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_type='deposit' AND finance_state=1 ORDER BY finance_time DESC");
        $refund_defray = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_type='refund' AND finance_state=1 ORDER BY finance_time DESC");
        $coin_defray = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_type='coin' AND finance_state=1 ORDER BY finance_time DESC");
        $day_begintime = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $day_income = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=0 AND finance_time>=$day_begintime ORDER BY finance_time DESC");
        $day_defray = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=1 AND finance_time>=$day_begintime ORDER BY finance_time DESC");
        $mouth_begintime = mktime(0,0,0,date('m'),1,date('Y'));
        $month_income = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=0 AND finance_time>=$mouth_begintime ORDER BY finance_time DESC");
        $month_defray = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=1 AND finance_time>=$mouth_begintime ORDER BY finance_time DESC");
        $year_begintime =mktime(0,0,0,1,1,date("Y",time()));;
        $year_income = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=0 AND finance_time>=$year_begintime ORDER BY finance_time DESC");
        $year_defray = DB::result_first("SELECT SUM(finance_amount) FROM ".DB::table('finance')." WHERE finance_state=1 AND finance_time>=$year_begintime ORDER BY finance_time DESC");
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=pnurse";
		$wheresql = "";
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
//		if(!empty($search_name)) {
//			$mpurl .= '&search_name='.urlencode($search_name);
//			$wheresql .= " AND nurse_name like '%".$search_name."%' OR member_phone like '%".$search_name."%'";
//		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('finance').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('finance').$wheresql." ORDER BY finance_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$book_ids[]=$value['book_id'];
			$member_ids[]=$value['member_id'];
			$nurse_ids[]=$value['nurse_id'];
            if($value['finance_state'] == '0') {
                $value['mark'] = '+';
                $value['markclass'] = 'red';
            } else {
                $value['mark'] = '-';
                $value['markclass'] = 'green';
            }
			$finance_list[] = $value;
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value;
			}
		}
		if(!empty($book_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $book_ids)."')");
            while($value = DB::fetch($query)) {
                $book_list[$value['book_id']] = $value;
            }
        }
        if(!empty($member_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $member_list[$value['member_id']] = $value;
            }
        }
        if(!empty($nurse_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
		$type_array = array('coin'=>'团豆豆', 'refund'=>'退款', 'deposit'=>'提现', 'bail'=>'保证金','recharge'=>'充值','book'=>'订单');
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('pnurse'));
	}
	
	public function profitOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		$mpurl = "admin.php?act=pnurse&op=profit&nurse_id=".$nurse_id;		
		$wheresql = " WHERE nurse_id='$nurse_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_profit').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse_profit').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			if($value['profit_type'] == '1') {
				$value['mark'] = '+';
			} else {
				$value['mark'] = '-';
			}
			$profit_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('pnurse_profit'));
	}
	
	public function endtimeOp() {
		if(submitcheck()) {
			$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
			if(empty($nurse)) {
				exit(json_encode(array('msg'=>'阿姨不存在')));
			}
			if(empty($this->setting['nurse_fee'])) {
				exit(json_encode(array('msg'=>'请先设置年费')));
			}
			if($nurse['available_amount'] < $this->setting['nurse_fee']) {
				exit(json_encode(array('msg'=>'可用金额不足年费')));
			}
			if(empty($nurse['nurse_endtime'])) {
				$nurse['nurse_endtime'] = DB::result_first("SELECT payment_time FROM ".DB::table('nurse_book')." WHERE nurse_id='$nurse_id' AND book_state>=20 ORDER BY payment_time ASC");
			}
			$end_year = date('Y', $nurse['nurse_endtime'])+1;
			$end_month = date('m', $nurse['nurse_endtime']);
			$end_day = date('d', $nurse['nurse_endtime']);
			$nurse_endtime = strtotime($end_year.'-'.$end_month.'-'.$end_day);
			$profit_data = array(
				'nurse_id' => $nurse['nurse_id'],
				'agent_id' => $nurse['agent_id'],
				'profit_stage' => 'year',
				'profit_type' => 0,
				'profit_amount' => $this->setting['nurse_fee'],
				'profit_desc' => '阿姨年费缴纳',
				'is_freeze' => 0,
				'add_time' => time(),
			);
			DB::insert('nurse_profit', $profit_data);
			DB::query("UPDATE ".DB::table('nurse')." SET available_amount=available_amount-".$this->setting['nurse_fee'].", nurse_endtime=$nurse_endtime WHERE nurse_id='".$nurse['nurse_id']."'");
			$log_data = array(
				'log_desc' => $this->admin['admin_name'].'向阿姨 '.$nurse['nurse_name'].' 收缴年费，到期时间为：'.$end_year.'-'.$end_month.'-'.$end_day,
				'admin_id' => $this->admin_id,
				'admin_name' => $this->admin['admin_name'],
				'log_time' => time(),
			);
			DB::insert('admin_log', $log_data);
			exit(json_encode(array('done'=>'true', 'available'=>priceformat($nurse['available_amount']-$this->setting['nurse_fee']), 'endtime'=>$end_year.'-'.$end_month.'-'.$end_day)));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function cashOp() {
		if(submitcheck()) {
			$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
			$cash_amount = empty($_POST['cash_amount']) ? 0 : intval($_POST['cash_amount']);
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
			if(empty($nurse)) {
				exit(json_encode(array('msg'=>'阿姨不存在')));
			}
			if(empty($cash_amount)) {
				exit(json_encode(array('msg'=>'请输入汇款金额')));
			}
			if($nurse['available_amount'] < $cash_amount) {
				exit(json_encode(array('msg'=>'可用金额不足汇款金额')));
			}
			$profit_data = array(
				'nurse_id' => $nurse['nurse_id'],
				'agent_id' => $nurse['agent_id'],
				'profit_stage' => 'cash',
				'profit_type' => 0,
				'profit_amount' => $cash_amount,
				'profit_desc' => '阿姨收益提现',
				'is_freeze' => 0,
				'add_time' => time(),
			);
			DB::insert('nurse_profit', $profit_data);
			DB::query("UPDATE ".DB::table('nurse')." SET available_amount=available_amount-$cash_amount WHERE nurse_id='".$nurse['nurse_id']."'");
			$log_data = array(
				'log_desc' => $this->admin['admin_name'].'给阿姨 '.$nurse['nurse_name'].' 汇款，汇款金额：'.$cash_amount.'元',
				'admin_id' => $this->admin_id,
				'admin_name' => $this->admin['admin_name'],
				'log_time' => time(),
			);
			DB::insert('admin_log', $log_data);
			exit(json_encode(array('done'=>'true', 'available'=>priceformat($nurse['available_amount']-$cash_amount))));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
}
?>