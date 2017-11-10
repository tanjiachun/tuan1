<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class profileControl extends BaseMobileControl {
	public function indexOp() {
        $this->check_authority();
//        $this->member_id=403;
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
	    if(empty($member)){
            exit(json_encode(array('code'=>1, 'msg'=>'用户不存在')));
        }
        $member_id=$member['member_id'];
        $order_pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$member_id' AND order_state=10");
        $order_receive_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$member_id' AND order_state in ('20', '30')");
        $order_comment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$member_id' AND order_state=40 AND comment_state=0");
        $book_pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$member_id' AND book_state=10");
        $book_receive_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$member_id' AND book_state=20");
        $book_comment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$member_id' AND book_state=30 AND comment_state=0");
        $pending_count = $order_pending_count+$book_pending_count;
        $receive_count = $order_receive_count+$book_receive_count;
        $comment_count = $order_comment_count+$book_comment_count;
        $return_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order_return')." WHERE member_id='$member_id'");
        $red_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('red')." WHERE member_id='$member_id'");
        $favorite_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('favorite')." WHERE member_id='$member_id'");
        $card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member['member_score']." ORDER BY card_predeposit DESC");
        $query = DB::query("SELECT * FROM ".DB::table('nurse_tag'));
        while($value = DB::fetch($query)) {
            $tag_list[] = $value;
        }
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$member_id'");
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$member_id'");
        $data = array(
            'token' => $member['member_token'],
            'member_phone' => $member['member_phone'],
            'member_nickname' => $member['member_nickname'],
            'member_avatar' => $member['member_avatar'],
            'available_predeposit' => $member['available_predeposit'],
            'member_coin'=>$member['member_coin'],
            'member_score'=>intval($member['member_score']),
            'card_name' => $card['card_name'],
            'card_icon' => $card['card_icon'],
            'book_comment_count'=>intval($book_comment_count),
            'favorite_count' => intval($favorite_count),
            'is_nurse' => empty($nurse) ? 0 : 1,
            'is_agent' => empty($agent) ? 0: 1,
            'share_url' => SiteUrl.'/mobile.php?act=share',
        );
//		$order_pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND order_state=10");
//		$order_receive_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND order_state in ('20', '30')");
//		$order_comment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id' AND order_state=40 AND comment_state=0");
//		$book_pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND book_state=10");
//		$book_receive_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND book_state=20");
//		$book_comment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND book_state=30 AND comment_state=0");
//		$pending_count = $order_pending_count+$book_pending_count;
//		$receive_count = $order_receive_count+$book_receive_count;
//		$comment_count = $order_comment_count+$book_comment_count;
//		$return_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order_return')." WHERE member_id='$this->member_id'");
//		$red_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('red')." WHERE member_id='$this->member_id'");
//		$favorite_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('favorite')." WHERE member_id='$this->member_id'");
//		$card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$this->member['member_score']." ORDER BY card_predeposit DESC");
//		$query = DB::query("SELECT * FROM ".DB::table('nurse_tag'));
//		while($value = DB::fetch($query)) {
//			$tag_list[] = $value;
//		}
//		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
//		$data = array(
//			'token' => $this->member['member_token'],
//			'member_phone' => $this->member['member_phone'],
//			'member_nickname' => $this->member['member_nickname'],
//			'member_avatar' => $this->member['member_avatar'],
//			'member_sex' => $this->member['member_sex'],
//			'available_predeposit' => $this->member['available_predeposit'],
//			'oldage_amount' => $this->member['oldage_amount'],
//			'card_name' => $card['card_name'],
//			'card_icon' => $card['card_icon'],
//			'pending_count' => $pending_count,
//			'receive_count' => $receive_count,
//			'comment_count' => $comment_count,
//			'return_count' => $return_count,
//			'red_count' => $red_count,
//			'favorite_count' => $favorite_count,
//			'tag_list' => $tag_list,
//			'is_nurse' => empty($nurse) ? 0 : 1,
//			'share_title' => $this->setting['share_title'],
//			'share_desc' =>  $this->setting['share_desc'],
//			'share_url' => SiteUrl.'/mobile.php?act=share',
//		);
		exit(json_encode(array('code'=>0, 'msg'=>'操作成功', 'data'=>$data)));
	}

	public function editOp() {
        $this->check_authority();
		$member_nickname = empty($_POST['member_nickname']) ? '' : $_POST['member_nickname'];
		$member_avatar = empty($_POST['member_avatar']) ? '' : $_POST['member_avatar'];
		$member_sex = empty($_POST['member_sex']) ? 0 : intval($_POST['member_sex']);
		$data = array();
		if(!empty($member_nickname)) {
			$data['member_nickname'] = $member_nickname;
		}
		if(!empty($member_avatar)) {
			$data['member_avatar'] = $member_avatar;
		}
		if(!empty($member_sex)) {
			$data['member_sex'] = $member_sex;
		}
		if(!empty($data)) {
			DB::update('member', $data, array('member_id'=>$this->member_id));
		}
		exit(json_encode(array('code'=>0, 'msg'=>'操作成功', 'data'=>array())));
	}
	public function avatar_setOp(){
        $this->check_authority();
	    $member_avatar=empty($_POST['member_avatar']) ? '' : $_POST['member_avatar'];
	    if(empty($member_avatar)){
	        exit(json_encode(array('code'=>1,'msg'=>'头像不能为空')));
        }
        DB::query("UPDATE ".DB::table('member')." SET member_avatar='$member_avatar' WHERE member_id='$this->member_id'");
	    exit(json_encode(array('code'=>0,'msg'=>'修改成功','member_avatar'=>$member_avatar)));
    }
    public function nickname_setOp(){
        $this->check_authority();
        $member_nickname=empty($_POST['member_nickname']) ? '' : $_POST['member_nickname'];
        if(empty($member_nickname)){
            exit(json_encode(array('code'=>1,'msg'=>'用户名不能为空')));
        }
        DB::query("UPDATE ".DB::table('member')." SET member_nickname='$member_nickname' WHERE member_id='$this->member_id'");
        exit(json_encode(array('code'=>0,'msg'=>'修改成功','member_nickname'=>$member_nickname)));
    }
}

?>