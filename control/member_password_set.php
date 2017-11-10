<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_password_setControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }

        $curmodule = 'home';
        $bodyclass = '';
        include(template('member_password_set'));
    }

    public function login_setOp(){
        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
        $login_phone_code = empty($_POST['login_phone_code']) ? '' : $_POST['login_phone_code'];
        $login_password = empty($_POST['login_password']) ? '' : $_POST['login_password'];
        $login_password2 = empty($_POST['login_password2']) ? '' : $_POST['login_password2'];
        if(empty($login_phone_code)){
            exit(json_encode(array('id'=>'login_phone_code', 'msg'=>'验证码必须填写')));
        }
        if(empty($login_password)){
            exit(json_encode(array('id'=>'login_password', 'msg'=>'密码必须填写')));
        }
        if(empty($login_password2)){
            exit(json_encode(array('id'=>'login_password2', 'msg'=>'密码必须填写')));
        }
        if($login_password!==$login_password2){
            exit(json_encode(array('id'=>'login_password2', 'msg'=>'两次密码必须相同')));
        }

        // 获取数据库中验证码信息
        $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'passwd_modify' ORDER BY postdate DESC LIMIT 0,1");

        // 对比数据库验证码
        $text_param = json_decode($code['text_param'], true);
        if(empty($text_param) || $text_param['code'] != $login_phone_code){
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

        $member_password = md5($login_password);
        DB::query("UPDATE ".DB::table('member')." SET member_password='$member_password' WHERE member_id='$this->member_id'");
        exit(json_encode(array('done'=>'true')));
    }

    public function pay_setOp(){
        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
        $pay_phone_code = empty($_POST['pay_phone_code']) ? '' : $_POST['pay_phone_code'];
        $pay_password = empty($_POST['pay_password']) ? '' : $_POST['pay_password'];
        $pay_password2 = empty($_POST['pay_password2']) ? '' : $_POST['pay_password2'];
        if(empty($pay_phone_code)){
            exit(json_encode(array('id'=>'pay_phone_code', 'msg'=>'验证码必须填写')));
        }
        if(empty($pay_password)){
            exit(json_encode(array('id'=>'pay_password', 'msg'=>'密码必须填写')));
        }
        if(empty($pay_password2)){
            exit(json_encode(array('id'=>'pay_password2', 'msg'=>'密码必须填写')));
        }
        if($pay_password!==$pay_password2){
            exit(json_encode(array('id'=>'pay_password2', 'msg'=>'两次密码必须相同')));
        }

        // 获取数据库中验证码信息
        $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'pay_passwd_modify' ORDER BY postdate DESC LIMIT 0,1");

        // 对比数据库验证码
        $text_param = json_decode($code['text_param'], true);
        if(empty($text_param) || $text_param['code'] != $pay_phone_code){
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

        $member_pay_password=md5($pay_password);
        DB::query("UPDATE ".DB::table('member')." SET member_pay_password='$member_pay_password' WHERE member_id='$this->member_id'");
        exit(json_encode(array('done'=>'true')));
    }

}

?>