<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class registerControl extends BaseMobileControl {
	public function indexOp() {
		$error_data = (object)array();
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		$phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
//		$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
		if(empty($member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号必须填写', 'data'=>$error_data)));
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号格式不正确', 'data'=>$error_data)));
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
		if(!empty($member)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号已经注册了', 'data'=>$error_data)));	
		}
		if(empty($phone_code)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'验证码必须填写', 'data'=>$error_data)));	
		}
//		$code = DB::fetch_first("SELECT * FROM ".DB::table('code')." WHERE code_phone='$member_phone' AND code_value='$phone_code'");
//		if(empty($code)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'验证码不正确', 'data'=>$error_data)));
//		}

        $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'register_code' ORDER BY postdate DESC LIMIT 0,1");

        // 对比数据库验证码
        $text_param = json_decode($code['text_param'], true);
        if(empty($text_param) || $text_param['code'] != $phone_code){
            exit(json_encode(array('code'=>'1','msg'=>'验证码错误，请重新输入！')));
        }

        // 检测验证码是否已经被使用
        if($code['postdate'] != $code['lastupdate']){
            exit(json_encode(array('code'=>'1','msg'=>'验证码已经被使用，请重新获取！')));
        }

        // 检测验证码有效性
        if((time() - strtotime($code['postdate'])) > 120){
            exit(json_encode(array('code'=>'1','msg'=>'验证码已过期，请重新获取！')));
        }

        // 更新验证码使用时间
        DB::update('code_queue', array('lastupdate' => date('Y-m-d H:i:s')), array('queue_id' => $code['queue_id']));

//		if(empty($member_password)) {
//			exit(json_encode(array('code'=>'1', 'msg'=>'密码必须填写', 'data'=>$error_data)));
//		}
//        $member_password=empty($_POST['member_password']) ? '' : $_POST['member_password'];
//        $member_password2=empty($_POST['member_password2']) ? '' : $_POST['member_password2'];
//        if(empty($member_password)){
//            exit(json_encode(array('code'=>1,'msg'=>'密码不能为空')));
//        }
//        if(empty($member_password2)){
//            exit(json_encode(array('code'=>1,'msg'=>'请输入确认密码')));
//        }
//        if($member_password!=$member_password2){
//            exit(json_encode(array('code'=>1,'msg'=>'两次密码不一致')));
//        }
//        $member_password = substr($member_phone, -6);

		$member_sn = makesn(4);
        $im_accid = random(32);
        $im_token = random(32);

		$data = array(
			'member_phone' => $member_phone,
			'member_coin'=>2688,
			'member_password' => md5($member_sn),
			'member_time' => time(),
			'member_token' => md5($member_sn),
            'yx_accid' => $im_accid,
            'yx_token' => $im_token,
		);
		$member_id = DB::insert('member', $data, 1);
		if(empty($member_id)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>$error_data)));
		}
        // 创建云信账号
        $im_init_data = array(
            'accid' => $im_accid,
            'token' => $im_token
        );
        $nim = new NimUser();
        $nim->create($im_init_data);

//        $content1 = '{"tel":"'.$member_phone.'", "pwd":"'.$member_password.'"}';
//        $smsstate1 = sendsms($member_phone, 'SMS_80840012', $content1);
//		$red_template = DB::fetch_first("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='new'");
//		if(!empty($red_template) && $red_template['red_t_total'] > $red_template['red_t_giveout']) {
//			if($red_template['red_t_period_type'] == 'duration') {
//				$red_template['red_t_starttime'] = strtotime(date('Y-m-d'));
//				$red_template['red_t_endtime'] = $red_template['red_t_starttime']+3600*24*($red_template['red_t_days']+1)-1;
//			}
//			$red_data = array(
//				'red_sn' => makesn(2),
//				'member_id' => $member_id,
//				'red_t_id' => $red_template['red_t_id'],
//				'red_title' => $red_template['red_t_title'],
//				'red_price' => $red_template['red_t_price'],
//				'red_starttime' => $red_template['red_t_starttime'],
//				'red_endtime' => $red_template['red_t_endtime'],
//				'red_limit' => $red_template['red_t_limit'],
//				'red_cate_id' => $red_template['red_t_cate_id'],
//				'red_state' => 0,
//				'red_addtime' => time(),
//			);
//			$red_id = DB::insert('red', $red_data, 1);
//			if(!empty($red_id)) {
//				DB::update('red_template', array('red_t_giveout'=>$red_template['red_t_giveout']+1), array('red_t_id'=>$red_template['red_t_id']));
//			}
//		}
		$data = array(
			'token' => md5($member_sn)
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'注册成功', 'data'=>$data)));
	}
	
	public function thirdOp() {
		$error_data = (object)array();
		$login_type = !in_array($_POST['login_type'], array('qq', 'weixin')) ? 'qq' : $_POST['login_type'];
		$login_id = empty($_POST['login_id']) ? '' : $_POST['login_id'];
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		$phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
		$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
		if(empty($login_id)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'授权信息必须填写', 'data'=>$error_data)));	
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE login_type='$login_type' AND login_id='$login_id'");
		if(!empty($member)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号已绑定', 'data'=>$error_data)));
		}
		if(empty($member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号必须填写', 'data'=>$error_data)));	
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号格式不正确', 'data'=>$error_data)));
		}
		if(empty($phone_code)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'验证码必须填写', 'data'=>$error_data)));	
		}
		$code = DB::fetch_first("SELECT * FROM ".DB::table('code')." WHERE code_phone='$member_phone' AND code_value='$phone_code'");
		if(empty($code)) {	
			exit(json_encode(array('code'=>'1', 'msg'=>'验证码不正确', 'data'=>$error_data)));
		}
		if(empty($member_password)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'密码必须填写', 'data'=>$error_data)));	
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
		if(!empty($member)) {
			DB::update('member', array('login_type'=>$login_type, 'login_id'=>$login_id), array('member_id'=>$member['member_id']));
			$data = array(
				'token' => $member['member_token']
			);
			exit(json_encode(array('code'=>'0', 'msg'=>'绑定成功', 'data'=>$data)));
		}		
		$member_sn = makesn(4);
		$data = array(
			'member_phone' => $member_phone,
			'member_password' => md5($member_password),
			'member_time' => time(),
			'member_token' => md5($member_sn),
			'login_type' => $login_type,
			'login_id' => $login_id,			
		);
		$member_id = DB::insert('member', $data, 1);
		if(empty($member_id)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>$error_data)));
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
		$data = array(
			'token' => md5($member_sn)
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'绑定成功', 'data'=>$data)));
	}
}

?>