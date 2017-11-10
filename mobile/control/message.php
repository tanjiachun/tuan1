<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class messageControl extends BaseMobileControl {
	public function indexOp() {
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('message')." WHERE member_id='$this->member_id'");
		$query = DB::query("SELECT * FROM ".DB::table('message')." WHERE member_id='$this->member_id' ORDER BY message_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$value['message_time'] = date('Y-m-d H:i', $value['message_time']);
			$message_list[] = $value;
		}
		DB::update('message', array('is_read'=>1), array('member_id'=>$this->member_id));
		$data = array(
			'message_count' => $count,
			'message_list' => empty($message_list) ? array() : $message_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function addOp() {
		$book_sn = empty($_POST['book_sn']) ? '' : $_POST['book_sn'];
		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
		$data = array(
			'member_id' => $this->member_id,
			'message_title' => '请等待阿姨联系您，进一步沟通服务细节', 
			'message_content' => '您有未支付预订单，单号：'.$book['book_sn'],
			'from_type' => 1,
			'from_id' => $book['book_id'],
			'message_time' => time(),
		);
		DB::insert('message', $data);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	public function readOp() {
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('message')." WHERE member_id='$this->member_id' AND is_read=0");
		$message = DB::fetch_first("SELECT * FROM ".DB::table('message')." WHERE member_id='$this->member_id' AND is_read=0");
		if(!empty($message)) {
			$message['message_time'] = date('Y-m-d H:i', $message['message_time']);
			DB::update('message', array('is_read'=>1), array('message_id'=>$message['message_id']));
		}
		$data = array(
			'count' => $count,
			'message' => empty($message) ? (object)array() : $message,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>