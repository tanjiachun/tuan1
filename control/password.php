<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class passwordControl extends BaseProfileControl {
	public function indexOp() {
		if(submitcheck()) {
			$old_password = empty($_POST['old_password']) ? '' : $_POST['old_password'];
			$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
			$member_password2 = empty($_POST['member_password2']) ? '' : $_POST['member_password2'];
			if(empty($old_password)) {
				exit(json_encode(array('id'=>'old_password', 'msg'=>'请输入原密码')));
			}
			if(empty($member_password)) {
				exit(json_encode(array('id'=>'member_password', 'msg'=>'请输入新密码')));
			}
			if($member_password != $member_password2) {
				exit(json_encode(array('id'=>'member_password2', 'msg'=>'请保证两次密码一致')));
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table("member")." WHERE member_id='$this->member_id'");
			if($member['member_password'] != md5($old_password)) {
				exit(json_encode(array('id'=>'old_password', 'msg'=>'请保证原密码正确')));
			}
			$data = array(
				'member_password' => md5($member_password),
			);
			DB::update('member', $data, array('member_id'=>$this->member_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('password'));
		}
	}
}

?>