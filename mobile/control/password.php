<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class passwordControl extends BaseMobileControl {
	public function indexOp() {
	    // 登陆验证
	    $this->check_authority();
		$old_password = empty($_POST['old_password']) ? '' : $_POST['old_password'];
		$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
		$member_password2 = empty($_POST['member_password2']) ? '' : $_POST['member_password2'];
		if(empty($old_password)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入原密码', 'data'=>array())));
		}
		if(empty($member_password)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入新密码', 'data'=>array())));
		}
		if($member_password != $member_password2) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请保证两次密码一致', 'data'=>array())));
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table("member")." WHERE member_id='$this->member_id'");
		if($member['member_password'] != md5($old_password)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请保证原密码正确', 'data'=>array())));
		}
		$data = array(
			'member_password' => md5($member_password),
		);
		DB::update('member', $data, array('member_id'=>$this->member_id));
		exit(json_encode(array('code'=>'0', 'msg'=>'修改密码成功', 'data'=>array())));
	}
	public function forgetOp(){
	    $member_phone=empty($_POST['member_phone']) ? '' : $_POST['mmeber_phone'];
	    $phone_code=empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
        $member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
        $member_password2 = empty($_POST['member_password2']) ? '' : $_POST['member_password2'];
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
        if(empty($member_phone)){
            exit(json_encode(array('code'=>1,'msg'=>'手机号必须填写','data'=>array())));
        }
        if(empty($phone_code)){
            exit(json_encode(array('code'=>1,'msg'=>'手机号验证码必须填写','data'=>array())));
        }
        $code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$member_phone' AND code_value='$phone_code'");
        if(empty($code)) {
            exit(json_encode(array('code'=>'1', 'msg'=>'验证码不正确','data'=>array())));
        }

        if(empty($member_password)){
            exit(json_encode(array('code'=>1,'msg'=>'密码不能为空','data'=>array())));
        }
        if($member_password!=$member_password2){
            exit(json_encode(array('code'=>1,'msg'=>'两次密码不一样','data'=>array())));
        }
        DB::update('member', array('member_password'=>md5($member_password)), array('member_phone'=>$member['member_phone']));
        exit(json_encode(array('code'=>0,'msg'=>'修改成功','data'=>array())));
    }

    /**
     * 修改登录密码第一步
     * 请求方法：POST
     * 请求地址：/mobile.php?act=password&op=login_pwd_set_step1
     * 身份验证：是
     */
    public function login_pwd_set_step1Op(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $member_phone = $this->member['member_phone'];
            $phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
            if(empty($member_phone)){
                exit(json_encode(array('code'=>1,'msg'=>'手机号不能为空')));
            }
            if(empty($phone_code)){
                exit(json_encode(array('code'=>1,'msg'=>'验证码不能为空')));
            }
            // 获取数据库中验证码信息
            $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'passwd_modify' ORDER BY postdate DESC LIMIT 0,1");

            // 对比数据库验证码
            $text_param = json_decode($code['text_param'], true);
            if(empty($text_param) || $text_param['code'] != $phone_code){
                exit(json_encode(array('code'=>1,'msg'=>'验证码错误，请重新输入！')));
            }

            // 检测验证码是否已经被使用
            if($code['postdate'] != $code['lastupdate']){
                exit(json_encode(array('code'=>1,'msg'=>'验证码已经被使用，请重新获取！')));
            }

            // 检测验证码有效性
            if((time() - strtotime($code['postdate'])) > 120){
                exit(json_encode(array('code'=>1,'msg'=>'验证码已过期，请重新获取！')));
            }

            // 更新验证码使用时间
            DB::update('code_queue', array('lastupdate' => date('Y-m-d H:i:s')), array('queue_id' => $code['queue_id']));

            exit(json_encode(array('code'=>0, 'msg'=>'验证码正确')));
        }else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误')));
        }

    }

    /**
     * 修改登录密码第2步
     * 请求方法：POST
     * 请求地址：/mobile.php?act=password&op=login_pwd_set_step2
     * 身份验证：是
     */
    public function login_pwd_set_step2Op(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $member_new_password = empty($_POST['member_new_password']) ? '' : $_POST['member_new_password'];
            $member_new_password2 = empty($_POST['member_new_password2']) ? '' : $_POST['member_new_password2'];
            if(empty($member_new_password)){
                exit(json_encode(array('code'=>1, 'msg'=>'请输入新密码')));
            }
            if(empty($member_new_password2)){
                exit(json_encode(array('code'=>1, 'msg'=>'请确认密码')));
            }
            if($member_new_password != $member_new_password2){
                exit(json_encode(array('code'=>1, 'msg'=>'两次密码输入不一致')));
            }
            $new_pwd = md5($member_new_password);
            DB::query("UPDATE ".DB::table('member')." SET member_password='$new_pwd' WHERE member_id='$this->member_id'");
            exit(json_encode(array('code'=>0, 'msg'=>'密码修改成功！')));
        }else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误')));
        }

    }


    /**
     * 修改支付密码第1步
     * 请求方法：POST
     * 请求地址：/mobile.php?act=password&op=pay_pwd_set_step1
     * 身份验证：是
     */
    public function pay_pwd_set_step1Op(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $member_phone = $this->member['member_phone'];
            $phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
            if(empty($member_phone)){
                exit(json_encode(array('code'=>1,'msg'=>'手机号不能为空')));
            }
            if(empty($phone_code)){
                exit(json_encode(array('code'=>1,'msg'=>'验证码不能为空')));
            }
            // 获取数据库中验证码信息
            $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'pay_passwd_modify' ORDER BY postdate DESC LIMIT 0,1");

            // 对比数据库验证码
            $text_param = json_decode($code['text_param'], true);
            if(empty($text_param) || $text_param['code'] != $phone_code){
                exit(json_encode(array('code'=>1,'msg'=>'验证码错误，请重新输入！')));
            }

            // 检测验证码是否已经被使用
            if($code['postdate'] != $code['lastupdate']){
                exit(json_encode(array('code'=>1,'msg'=>'验证码已经被使用，请重新获取！')));
            }

            // 检测验证码有效性
            if((time() - strtotime($code['postdate'])) > 120){
                exit(json_encode(array('code'=>1,'msg'=>'验证码已过期，请重新获取！')));
            }

            // 更新验证码使用时间
            DB::update('code_queue', array('lastupdate' => date('Y-m-d H:i:s')), array('queue_id' => $code['queue_id']));

            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        }else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误')));
        }

    }

    /**
     * 修改支付密码第2步
     * 请求方法：POST
     * 请求地址：/mobile.php?act=password&op=pay_pwd_set_step2
     * 身份验证：是
     */
    public function pay_pwd_set_step2Op(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $member_new_password = empty($_POST['member_new_password']) ? '' : $_POST['member_new_password'];
            $member_new_password2 = empty($_POST['member_new_password2']) ? '' : $_POST['member_new_password2'];
            if(empty($member_new_password)){
                exit(json_encode(array('code'=>1,'msg'=>'请输入新密码')));
            }
            if(empty($member_new_password2)){
                exit(json_encode(array('code'=>1,'msg'=>'请输入确认密码')));
            }
            if($member_new_password!=$member_new_password2){
                exit(json_encode(array('code'=>1,'msg'=>'两次密码输入不一致')));
            }
            $new_pwd = md5($member_new_password);
            DB::query("UPDATE ".DB::table('member')." SET member_pay_password='$new_pwd' WHERE member_id = '$this->member_id'");
            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        }else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误')));
        }
    }

//    public function login_pwd_set_sOp(){
//        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
//        $login_phone_code = empty($_POST['login_phone_code']) ? '' : $_POST['login_phone_code'];
//        $login_password = empty($_POST['login_password']) ? '' : $_POST['login_password'];
//        $login_password2 = empty($_POST['login_password2']) ? '' : $_POST['login_password2'];
//        if(empty($login_phone_code)){
//            exit(json_encode(array('id'=>'login_phone_code', 'msg'=>'验证码必须填写')));
//        }
//        if(empty($login_password)){
//            exit(json_encode(array('id'=>'login_password', 'msg'=>'密码必须填写')));
//        }
//        if(empty($login_password2)){
//            exit(json_encode(array('id'=>'login_password2', 'msg'=>'密码必须填写')));
//        }
//        if($login_password!==$login_password2){
//            exit(json_encode(array('id'=>'login_password2', 'msg'=>'两次密码必须相同')));
//        }
//        $code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$member_phone' AND code_value='$login_phone_code'");
//        if(empty($code)) {
//            exit(json_encode(array('id'=>'login_phone_code', 'msg'=>'验证码不正确')));
//        }
//        $member_password=md5($login_password);
//        DB::query("UPDATE ".DB::table('member')." SET member_password='$member_password' WHERE member_id='$this->member_id'");
//        exit(json_encode(array('done'=>'true')));
//    }
//
//    public function pay_setOp(){
//        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
//        $pay_phone_code = empty($_POST['pay_phone_code']) ? '' : $_POST['pay_phone_code'];
//        $pay_password = empty($_POST['pay_password']) ? '' : $_POST['pay_password'];
//        $pay_password2 = empty($_POST['pay_password2']) ? '' : $_POST['pay_password2'];
//        if(empty($pay_phone_code)){
//            exit(json_encode(array('id'=>'pay_phone_code', 'msg'=>'验证码必须填写')));
//        }
//        if(empty($pay_password)){
//            exit(json_encode(array('id'=>'pay_password', 'msg'=>'密码必须填写')));
//        }
//        if(empty($pay_password2)){
//            exit(json_encode(array('id'=>'pay_password2', 'msg'=>'密码必须填写')));
//        }
//        if($pay_password!==$pay_password2){
//            exit(json_encode(array('id'=>'pay_password2', 'msg'=>'两次密码必须相同')));
//        }
//        $code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$member_phone' AND code_value='$pay_phone_code'");
//        if(empty($code)) {
//            exit(json_encode(array('id'=>'pay_phone_code', 'msg'=>'验证码不正确')));
//        }
//        $member_pay_password=md5($pay_password);
//        DB::query("UPDATE ".DB::table('member')." SET member_pay_password='$member_pay_password' WHERE member_id='$this->member_id'");
//        exit(json_encode(array('done'=>'true')));
//    }
}

?>
