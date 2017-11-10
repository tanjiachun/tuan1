<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class loginControl extends BaseHomeControl {
	public function indexOp() {
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));	
			}
			$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
			$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
			$cookietime = empty($_POST['cookietime']) ? 0 : intval($_POST['cookietime']);
			if(empty($member_phone)) {
				exit(json_encode(array('msg'=>'手机号必须填写')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('msg'=>'手机号格式不正确')));
			}
			if(empty($member_password)) {
				exit(json_encode(array('msg'=>'密码必须填写')));	
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
			if(empty($member)) {
				exit(json_encode(array('msg'=>'手机号不存在')));	
			}
			if($member['member_password'] != md5($member_password)) {
				exit(json_encode(array('msg'=>'账号或密码错误')));
			}
			if(!empty($cookietime)){
                dsetcookie('member_phone', $member_phone);
            }
			dsetcookie('mallauth', authcode($member['member_password'].'\t'.$member['member_id'], 'ENCODE'), $cookietime ? 259200 : 0);
			exit(json_encode(array('done'=>'true','yx_accid'=>$member['yx_accid'],'yx_token'=>$member['yx_token'])));
		} else {
			if(!empty($this->nurse_id)) {
				if(!empty($this->member_id)) {
					@header("Location: index.php?act=nurse_center");
					exit;
				}
				$refer = 'index.php?act=nurse_center';
			} else {
				if(!empty($this->member_id)) {
					$this->showmessage('您已经登录了', 'index.php');
				}
				$refer = dreferer();
			}
			$member_phone=dgetcookie('member_phone');
			$curmodule = 'member';
			$bodyclass = '';
			include(template('login'));
		}
	}
	
	public function checknameOp() {
		$member_phone = empty($_GET['member_phone']) ? '' : $_GET['member_phone'];
		if(empty($member_phone)) {
			exit(json_encode(array('msg'=>'手机号必须填写')));
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('msg'=>'手机号格式不正确')));
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
		if(empty($member)) {
			exit(json_encode(array('msg'=>'手机号不存在')));	
		}
		exit(json_encode(array('done'=>'true')));
	}
}

?>