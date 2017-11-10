<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class loginControl extends BaseMobileControl {
	public function indexOp() {
		$error_data = (object)array();
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
		if(empty($member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号必须填写', 'data'=>$error_data)));
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号格式不正确', 'data'=>$error_data)));
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
		if(empty($member)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号不存在', 'data'=>$error_data)));	
		}
		if(empty($member_password)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'密码必须填写', 'data'=>$error_data)));	
		}		
		if($member['member_password'] != md5($member_password)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'密码错误', 'data'=>$error_data)));	
		}
		$data = array(
			'token' => $member['member_token'],
			'yx_accid' => $member['yx_accid'],
			'yx_token' => $member['yx_token'],
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'登陆成功', 'data'=>$data)));
	}

    /**
     * @todo 内部测试修改短信验证码登陆
     */
	public function code_loginOp(){
        $error_data = (object)array();
        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
        $phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
        if(empty($member_phone)) {
            exit(json_encode(array('code'=>'1', 'msg'=>'手机号必须填写', 'data'=>$error_data)));
        }
        if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
            exit(json_encode(array('code'=>'1', 'msg'=>'手机号格式不正确', 'data'=>$error_data)));
        }
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
        if(empty($member)) {
            exit(json_encode(array('code'=>'1', 'msg'=>'手机号码未注册，请先注册！', 'data'=>$error_data)));
        }
        if(empty($phone_code)) {
            exit(json_encode(array('code'=>'1', 'msg'=>'验证码必须填写', 'data'=>$error_data)));
        }
        $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'login_code' ORDER BY postdate DESC LIMIT 0,1");

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
        $data = array(
            'token' => $member['member_token'],
            'yx_accid' => $member['yx_accid'],
            'yx_token' => $member['yx_token'],
        );
        exit(json_encode(array('code'=>'0', 'msg'=>'登陆成功', 'data'=>$data)));

    }
	
	public function thirdOp() {
		$error_data = (object)array();
		$login_type = !in_array($_POST['login_type'], array('qq', 'weixin')) ? 'qq' : $_POST['login_type'];
		$login_id = empty($_POST['login_id']) ? '' : $_POST['login_id'];
		if(empty($login_id)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'授权信息必须填写', 'data'=>$error_data)));	
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE login_type='$login_type' AND login_id='$login_id'");
		if(!empty($member)) {
			$data = array(
				'token' => $member['member_token']
			);
			exit(json_encode(array('code'=>'0', 'msg'=>'登陆成功', 'data'=>$data)));
		} else {
			$data = array(
				'token' => ''
			);
			exit(json_encode(array('code'=>'0', 'msg'=>'手机号未绑定', 'data'=>$data)));
		}
	}
}

?>