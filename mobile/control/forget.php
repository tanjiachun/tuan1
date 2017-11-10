<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class forgetControl extends BaseMobileControl {
    /**
     * 找回密码
     * 请求方法：POST
     * 请求地址：/mobile.php?act=forget
     * 身份验证：是
     * @param member_phone
     * @param phone_code
     * @param member_password
     */
	public function indexOp() {
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		$phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
		$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
		if(empty($member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号必须填写', 'data'=>array())));
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号格式不正确', 'data'=>array())));
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
		if(empty($member)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号不存在', 'data'=>array())));	
		}
		if(empty($phone_code)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'验证码必须填写', 'data'=>array())));	
		}

		if(empty($member_password)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'密码必须填写', 'data'=>array())));	
		}

        $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'find_passwd' ORDER BY postdate DESC LIMIT 0,1");

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

		DB::update('member', array('member_password' => md5($member_password)), array('member_id' => $member['member_id']));

		exit(json_encode(array('code'=>'0', 'msg'=>'找回密码成功')));
	}
}

?>