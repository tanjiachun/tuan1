<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_centerbbControl extends BaseAgentControl {
	public function indexOp() {
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id'");
		$pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND book_state=10");
		$payment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND book_state=20");
		$finish_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND book_state=30");
		$cancel_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND book_state=0");
		$this->agent['plat_amount'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND profit_type=1");
		$this->agent['pool_amount'] = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND is_freeze=1");
		$income = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND profit_type=1 AND is_freeze=0");
		$expend = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$this->agent_id' AND profit_type=0 AND is_freeze=0");
		$this->agent['available_amount'] = $income-$expend;
		$query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' ORDER BY add_time DESC LIMIT 0, 8");
		while($value = DB::fetch($query)) {
			$nurse_ids[] = $value['nurse_id'];
			$book_list[] = $value;
		}
		if(!empty($nurse_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
			while($value = DB::fetch($query)) {
				$nurse_list[$value['nurse_id']] = $value;
			}
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('agent_centerbb'));
	}
	public function get_countOp(){
	    $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id'");
	    exit(json_encode(array('count' =>$count)));
    }
}

?>