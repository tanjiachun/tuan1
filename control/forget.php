<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class forgetControl extends BaseHomeControl {
	public function indexOp() {
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));	
			}
			$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
			$phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
			if(empty($member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号必须填写')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号格式不正确')));
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
			if(empty($member)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号不存在')));	
			}
			if(empty($phone_code)) {
				exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码必须填写')));	
			}
//			$code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$member_phone' AND code_value='$phone_code'");
//			if(empty($code)) {
//				exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码不正确')));
//			}
            $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'find_passwd' ORDER BY postdate DESC LIMIT 0,1");

            // 对比数据库验证码
            $text_param = json_decode($code['text_param'], true);
            if(empty($text_param) || $text_param['code'] != $phone_code) {
                exit(json_encode(array('msg' => '验证码错误，请重新输入！')));
            }
            // 检测验证码是否已经被使用
            if($code['postdate'] != $code['lastupdate']){
                exit(json_encode(array('msg'=>'验证码已经被使用，请重新获取！')));
            }

            // 检测验证码有效性
            if((time() - strtotime($code['postdate'])) > 120){
                exit(json_encode(array('msg'=>'验证码已过期，请重新获取！')));
            }

            // 更新验证码使用时间
            DB::update('code_queue', array('lastupdate' => date('Y-m-d H:i:s')), array('queue_id' => $code['queue_id']));

			dsetcookie('mallforget', authcode($member['member_phone'].'\t'.$member['member_id'], 'ENCODE'), 3600);
			exit(json_encode(array('done'=>'true')));
		} else {
			if(!empty($this->member_id)) {
				$this->showmessage('您已经登录了', 'index.php');	
			}
			$curmodule = 'home';
			$bodyclass = '';
			include(template('forget_step1'));
		}
	}
	
	public function step2Op() {
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));	
			}
			$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
			$member_password2 = empty($_POST['member_password2']) ? '' : $_POST['member_password2'];
			if(empty($member_password)) {
				exit(json_encode(array('id'=>'member_password', 'msg'=>'密码必须填写')));	
			}
			if($member_password != $member_password2) {
				exit(json_encode(array('id'=>'member_password2','msg'=>'两次密码必须保证一致')));	
			}
			$mallforget = dgetcookie('mallforget');
			$param = explode('\t', authcode($mallforget, 'DECODE'));
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$param[1]'");
			if(empty($member) || $member['member_phone'] != $param[0]) {
				exit(json_encode(array('id'=>'system', 'msg'=>'参数错误')));	
			}
			DB::update('member', array('member_password'=>md5($member_password)), array('member_id'=>$member['member_id']));
			dsetcookie('mallforget', '', -3600);
			exit(json_encode(array('done'=>'true')));
		} else {
			$mallforget = dgetcookie('mallforget');
			$param = explode('\t', authcode($mallforget, 'DECODE'));
			if(empty($param[1])) {				
				@header('Location: index.php?act=forget');
				exit;
			}
			$curmodule = 'home';
			$bodyclass = '';
			include(template('forget_step2'));
		}
	}
	
	public function step3Op() {
		if(!empty($this->member_id)) {
			$this->showmessage('您已经登录了', 'index.php');	
		}
		$curmodule = 'home';
		$bodyclass = '';
		include(template('forget_step3'));
	}
}

?>