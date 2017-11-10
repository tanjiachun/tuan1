<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_centerControl extends BaseProfileControl {
	public function indexOp() {
		if(empty($this->nurse_id)) {
			$this->showmessage('您还没注册成为家政人员', 'index.php?act=register&next_step=nurse', 'info');
		}
		$pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$this->nurse_id' AND book_state=10");
		$payment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$this->nurse_id' AND book_state=20");
		$finish_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$this->nurse_id' AND book_state=30");
		$cancel_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$this->nurse_id' AND book_state=0");
		$query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE nurse_id='$this->nurse_id' ORDER BY add_time DESC LIMIT 0, 8");
		while($value = DB::fetch($query)) {
			$book_list[] = $value;
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('nurse_center'));
	}
}

?>