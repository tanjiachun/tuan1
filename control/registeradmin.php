<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class registerAdminControl extends BaseHomeControl {
	public function indexOp() {
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}

			$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
			$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
			$member_password2 = empty($_POST['member_password2']) ? '' : $_POST['member_password2'];
			if(empty($member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号必须填写')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号格式不正确')));
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
			if(!empty($member)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号已经注册了')));	
			}

			if(empty($member_password)) {
				exit(json_encode(array('id'=>'member_passwd', 'msg'=>'密码必须填写')));	
			}
			if($member_password != $member_password2) {
				exit(json_encode(array('id'=>'member_password2', 'msg'=>'两次密码必须保证一致')));	
			}

			$member_sn = makesn(4);
			$data = array(
				'member_phone' => $member_phone,
				'member_password' => md5($member_password),
				'member_time' => time(),
				'member_token' => md5($member_sn),
			);
			$member_id = DB::insert('member', $data, 1);
			if(empty($member_id)) {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
			$red_template = DB::fetch_first("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='new'");
			if(!empty($red_template) && $red_template['red_t_total'] > $red_template['red_t_giveout']) {
				if($red_template['red_t_period_type'] == 'duration') {
					$red_template['red_t_starttime'] = strtotime(date('Y-m-d'));
					$red_template['red_t_endtime'] = $red_template['red_t_starttime']+3600*24*($red_template['red_t_days']+1)-1;
				}
				$red_data = array(
					'red_sn' => makesn(2),
					'member_id' => $member_id,
					'red_t_id' => $red_template['red_t_id'],
					'red_title' => $red_template['red_t_title'],
					'red_price' => $red_template['red_t_price'],
					'red_starttime' => $red_template['red_t_starttime'],
					'red_endtime' => $red_template['red_t_endtime'],
					'red_limit' => $red_template['red_t_limit'],
					'red_cate_id' => $red_template['red_t_cate_id'],
					'red_state' => 0,
					'red_addtime' => time(),
				);
				$red_id = DB::insert('red', $red_data, 1);
				if(!empty($red_id)) {
					DB::update('red_template', array('red_t_giveout'=>$red_template['red_t_giveout']+1), array('red_t_id'=>$red_template['red_t_id']));
				}
			}
			dsetcookie('mallauth', authcode($data['member_password'].'\t'.$member_id, 'ENCODE'), 259200);


            exit(json_encode(array('done'=>'true')));
		} else {
			$next_step = !in_array($_GET['next_step'], array('normal', 'nurse')) ? 'normal' : $_GET['next_step'];
			if(!empty($this->member_id)) {
				if($next_step == 'nurse') {
					@header("Location: index.php?act=nurse&op=registerAdmin");
					exit;	
				} else {
					$this->showmessage('您已经登录了', 'index.php');	
				}
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('registerAdmin'));
		}
	}
	
	public function step2Op() {
		if(empty($this->member_id)) {				
			@header('Location: index.php?act=register');
			exit;
		}
		$curmodule = 'member';
		$bodyclass = '';
		include(template('register_step2'));
	}
	
	public function agreementOp() {
		$curmodule = 'home';
		$bodyclass = '';
		include(template('register_agreement'));
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
		if(!empty($member)) {
			exit(json_encode(array('msg'=>'手机号已经注册了')));	
		}
		exit(json_encode(array('done'=>'true')));
	}
}

?>