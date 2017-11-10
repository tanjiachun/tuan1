<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_bookControl extends BaseProfileControl {
	public function indexOp() {
		if(empty($this->nurse_id)) {
			$this->showmessage('您还没注册成为家政人员', 'index.php?act=register&next_step=nurse', 'info');
		}
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=nurse_book";
		$wheresql = " WHERE nurse_id='$this->nurse_id'";
		$state = !in_array($_GET['state'],array('pending', 'payment', 'finish', 'cancel')) ? '' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'pending') {
			$wheresql .= " AND book_state=10";
		} elseif($state == 'payment') {
			$wheresql .= " AND book_state=20";
		} elseif($state == 'finish') {
			$wheresql .= " AND book_state=30";
		} elseif($state == 'cancel') {
			$wheresql .= " AND book_state=0";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= "&search_name=".urlencode($search_name);
			$wheresql .= " AND (book_sn like '%".$search_name."%' OR book_phone like '%".$search_name."%')";	
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$value['book_message'] = nl2br($value['book_message']);
			$value['book_service'] = empty($value['book_service']) ? array() : unserialize($value['book_service']);
			$value['invoice_content'] = empty($value['invoice_content']) ? array() : unserialize($value['invoice_content']);
			$book_service = array();
			foreach($value['book_service'] as $subkey => $subvalue) {
				$book_service[] = $subvalue['service_name'];
			}
			$value['book_service'] = empty($book_service) ? '' : implode(' ', $book_service);
			$book_list[] = $value;
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$member_list[$value['member_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('nurse_book'));
	}
}

?>