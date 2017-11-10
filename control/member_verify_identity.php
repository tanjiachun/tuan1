<?php
/**
 * 会员实名认证操作页面
 * */
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_verify_identityControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }

        $curmodule = 'home';
        $bodyclass = '';
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");

        switch ($member['member_real_state']) {
            case 0:
                // 未认证状态
                $template_filename = 'member_verify_identity';
                break;
            case 1:
                // 认证成功
                $template_msg = '恭喜您，您的实名信息已完善！';
                $template_filename = 'member_verify_identity_status';
                break;
            case 2:
                // 认证失败
                $template_msg = '对不起，您的实名信息未能通过，请确认无误后重新提交！';
                $template_filename = 'member_verify_identity_status';
                break;
            case 3:
                // 认证中
                $template_msg = '您的实名信息正在审核中，请等待！';
                $template_filename = 'member_verify_identity_status';
                break;
            default:
                header('Location: ./index.php?act=member_center');
        }

        include(template($template_filename));
    }

    /**
     * 实名认证第一步，检测用户提交的身份证号码和验证码并存入数据库
     */
    public function real_name_step1Op(){
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(!empty($member) && $member['member_real_state'] == '1'){
            exit(json_encode(array('msg'=>'您的实名信息已经完善！')));
        }

        $member_truename=empty($_POST['member_truename']) ? '' : $_POST['member_truename'];
        $member_cardid=empty($_POST['member_cardid']) ? '' : $_POST['member_cardid'];
        $member_phone=empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
        $phone_code=empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
        if(empty($member_truename)){
            exit(json_encode(array('msg'=>'姓名不能为空')));
        }
        if(empty($member_cardid)){
            exit(json_encode(array('msg'=>'身份证号不能为空')));
        }
        if(empty($member_phone)){
            exit(json_encode(array('msg'=>'号码不能为空')));
        }
        if(empty($phone_code)){
            exit(json_encode(array('msg'=>'验证码不能为空')));
        }
        // 获取数据库中验证码信息
        $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'account_verify' ORDER BY postdate DESC LIMIT 0,1");

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
        $check_result = check_identity($member_cardid);
        if(empty($check_result['area']))
        {
            exit(json_encode(array('msg'=>'请检查身份证号码是否正确！')));
        }

        DB::update('member', array('member_cardid'=>$member_cardid, 'member_truename'=>$member_truename), array('member_id'=>$this->member_id));

        exit(json_encode(array('done'=>'true')));

    }

    /**
     * 实名认证第二步，识别用户提交的身份证图片并和数据库中的身份证信息对比
     */
    public function real_name_step2Op(){
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(!empty($member) && $member['member_real_state'] == '1'){
            exit(json_encode(array('msg'=>'您的实名信息已经完善！')));
        }
        $member_cardid_image = empty($_POST['member_cardid_image']) ? '' : $_POST['member_cardid_image'];
        $member_cardid_back_image = empty($_POST['member_cardid_back_image']) ? '' : $_POST['member_cardid_back_image'];
        if(empty($member_cardid_image)){
            exit(json_encode(array('msg'=>'身份证正面照不能为空')));
        }
        if(empty($member_cardid_back_image)){
            exit(json_encode(array('msg'=>'身份证反面照不能为空')));
        }
        $member_face_image_path = MALL_ROOT.'/'.$member_cardid_image;
        $member_back_image_path = MALL_ROOT.'/'.$member_cardid_back_image;

        if(!file_exists($member_face_image_path) && !file_exists($member_back_image_path)){
            exit(json_encode(array('msg'=>'上传失败，请稍后重试')));
        }

        $identity_result = get_identity_ocr(base64EncodeImage($member_face_image_path), base64EncodeImage($member_back_image_path));
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");

        // 记录每次获取到的验证数据，方便以后做甄别
        $verify_log = array(
            'member_id' => $this->member_id,
            'verify_date' => date('Y-m-d H:i:s', time()),
            'verify_data' => json_encode($identity_result),
            'individual_data' => json_encode(array(
                'face' => $member_face_image_path,
                'back' => $member_back_image_path
            ))
        );
        DB::insert('member_verify', $verify_log);

        // 对比成功后更新数据库
        if($identity_result['face']['name'] == $member['member_truename'] && $identity_result['face']['num'] == $member['member_cardid'] )
        {
            $data=array(
                'member_cardid_image' => $member_cardid_image,
                'member_cardid_back_image' => $member_cardid_back_image,
                'member_real_state' => 1,
            );
            DB::update('member', $data, array('member_id'=>$this->member_id));

            exit(json_encode(array('done'=>'true')));
        }

        exit(json_encode(array('msg'=>'身份证信息与实名信息不符合，请确认无误后重试！')));

    }
    public function real_name_step3Op(){

    }
}

?>