<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class registerControl extends BaseHomeControl {
	public function indexOp() {
	    // 注册验证
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));	
			}
			$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
			$phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
			$pwd = empty($_POST['pwd']) ? '' : $_POST['pwd'];
			$pwd2 = empty($_POST['pwd2']) ? '' : $_POST['pwd2'];
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
			if(empty($phone_code)) {
				exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码必须填写')));	
			}
//			$code = DB::fetch_first("SELECT * FROM ".DB::table('code')." WHERE code_phone='$member_phone' AND code_value='$phone_code'");
//			if(empty($code)) {
//				exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码不正确')));
//			}
            // 获取数据库中验证码信息
            $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'domestic_staff_join' ORDER BY postdate DESC LIMIT 0,1");

            // 对比数据库验证码
            $text_param = json_decode($code['text_param'], true);
            if(empty($text_param) || $text_param['code'] != $phone_code){
                exit(json_encode(array('msg'=>'验证码错误，请重新输入！')));
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

			if(empty($pwd)){
			    exit(json_encode(array('id'=>'phone_pwd','msg'=>'请填写密码')));
            }
            if(empty($pwd2)){
			    exit(json_encode(array('id'=>'phone_pwd2','msg'=>'请填写确认密码')));
            }
            if($pwd!==$pwd2){
                exit(json_encode(array('id'=>'phone_pwd2','msg'=>'请确保密码一致')));
            }
//            $member_password=num6();
//            $member_password = substr($member_phone, -6);
            $member_password=$pwd;
			$member_sn = makesn(4);
			$im_accid = random(32);
			$im_token = random(32);
			$data = array(
				'member_phone' => $member_phone,
				'member_password' => md5($member_password),
				'member_coin'=>2666,
				'member_time' => time(),
				'member_token' => md5($member_sn),
				'yx_accid' => $im_accid,
				'yx_token' => $im_token,
			);
			$member_id = DB::insert('member', $data, 1);
			if(empty($member_id)) {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));	
			}
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            //团豆豆表数据录入
			$coin_data=array(
			    'member_id'=>$member_id,
                'coin_count'=>2666,
                'get_type'=>'register',
                'get_state'=>0,
                'true_time'=>time(),
                'get_time'=>$now_date
            );
			DB::insert('member_coin',$coin_data);
            // 创建云信账号
			$im_init_data = array(
                'accid' => $im_accid,
                'token' => $im_token
            );
            $nim = new NimUser();
            $nim->create($im_init_data);

//            $content1 = '{"tel":"'.$member_phone.'", "pwd":"'.$member_password.'"}';
//            $smsstate1 = sendsms($member_phone, 'SMS_80840012', $content1);
			dsetcookie('mallauth', authcode($data['member_password'].'\t'.$member_id, 'ENCODE'), 259200);
			exit(json_encode(array('done'=>'true')));
		} else {
		    // 默认注册模板页面
			$next_step = !in_array($_GET['next_step'], array('normal', 'nurse')) ? 'normal' : $_GET['next_step'];
			if(!empty($this->member_id)) {
				if($next_step == 'nurse') {
					@header("Location: index.php?act=nurse&op=register");
					exit;	
				} else {
					$this->showmessage('您已经登录了', 'index.php');	
				}
			}
			$curmodule = 'home';
			$bodyclass = '';
			include(template('register'));
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
	
	public function privacyOp() {
		$curmodule = 'home';
		$bodyclass = '';
		include(template('register_privacy_agreement'));
	}
	
	public function useOp() {
		$curmodule = 'home';
		$bodyclass = '';
		include(template('register_use_agreement'));
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