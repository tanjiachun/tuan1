<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class login_codeControl extends BaseHomeControl {
    public function indexOp() {
        if(submitcheck()) {
            if(!empty($this->member_id)) {
                exit(json_encode(array('done'=>'login')));
            }
            $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
            $phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
            if(empty($member_phone)) {
                exit(json_encode(array('msg'=>'手机号必须填写')));
            }
            if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
                exit(json_encode(array('msg'=>'手机号格式不正确')));
            }
            if(empty($phone_code)) {
                exit(json_encode(array('msg'=>'验证码必须填写')));
            }
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
            if(empty($member)) {
                exit(json_encode(array('msg'=>'手机号不存在')));
            }
            $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'login_code' ORDER BY postdate DESC LIMIT 0,1");

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

            //设置cookies
            dsetcookie('mallauth', authcode($member['member_password'].'\t'.$member['member_id'], 'ENCODE'));
            exit(json_encode(array('done'=>'true')));
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
            $curmodule = 'member';
            $bodyclass = '';
            include(template('login_code'));
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
            exit(json_encode(array('msg'=>'该手机号码还没有注册，请先注册！')));
        }
        exit(json_encode(array('done'=>'true')));
    }
}

?>