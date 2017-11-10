<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_profitControl extends BaseAgentControl {
	public function indexOp() {
        if(empty($this->agent_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$this->agent_id'");
        $agent['agent_qa_image'] = empty($agent['agent_qa_image'] ) ? array() : unserialize($agent['agent_qa_image'] );
        $agent['agent_service_image'] = empty($agent['agent_service_image'] ) ? array() : unserialize($agent['agent_service_image'] );
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$this->agent_id' AND nurse_state=1");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND refund_amount=0");
        $question_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='$this->agent_id' AND answer_content=''");
//        $this->agent['plat_amount'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('agent_profit')." WHERE agent_id='$this->agent_id' AND profit_type=1");
//        $this->agent['plat_refund'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('agent_profit')." WHERE agent_id='$this->agent_id' AND profit_type=0");
//        $this->agent['pool_amount'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('agent_profit')." WHERE agent_id='$this->agent_id' AND is_freeze=1");
//        $income = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('agent_profit')." WHERE agent_id='$this->agent_id' AND profit_type=1 AND is_freeze=0");
//        $expend = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('agent_profit')." WHERE agent_id='$this->agent_id' AND profit_type=0 AND is_freeze=0");
//        $this->agent['available_amount'] = $income-$expend;
		$this->agent['plat_amount'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND profit_type=1");
		$this->agent['plat_refund'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND profit_type=0");
		$this->agent['pool_amount'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND is_freeze=1");
		$income = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND profit_type=1 AND is_freeze=0");
		$expend = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND profit_type=0 AND is_freeze=0");
		$this->agent['available_amount'] = $income-$expend;
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=agent_profit";
		$wheresql = " WHERE agent_id='$this->agent_id'";
		$state = !in_array($_GET['state'],array('income', 'expend', 'freeze')) ? '' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'income') {
			$wheresql .= " AND profit_type=1 AND is_freeze=1";
		} elseif($state == 'expend') {
			$wheresql .= " AND profit_type=0 AND is_freeze=0";
		} elseif($state == 'freeze') {
			$wheresql .= " AND is_freeze=1";
		}
		$nurse_name = empty($_GET['nurse_name']) ? '' : $_GET['nurse_name'];
		if(!empty($nurse_name)) {
			$mpurl .= "&nurse_name=$nurse_name";
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_name like '%".$nurse_name."%'");
			while($value = DB::fetch($query)) {
				$nurse_ids[] = $value['nurse_id'];
			}
			if(!empty($nurse_ids)) {
				$wheresql .= " AND nurse_id in ('".implode("','", $nurse_ids)."')";
			} else {
				$wheresql .= " AND 0";
			}
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
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_profit').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse_profit').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$nurse_ids[] = $value['nurse_id'];
			if($value['profit_type'] == '1') {
				$value['mark'] = '+';
				$value['markclass'] = 'red';
			} else {
				$value['mark'] = '-';
				$value['markclass'] = 'green';
			}
			$profit_list[] = $value;
		}
		if(!empty($nurse_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
			while($value = DB::fetch($query)) {
				$nurse_list[$value['nurse_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'home';
		$bodyclass = 'gray-bg';
		include(template('agent_profit'));
	}
}

?>