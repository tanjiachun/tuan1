<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_real_nameControl extends BaseMobileControl {
    public function indexOp(){
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
    }
    public function real_name_step1Op(){
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(!empty($member) && $member['member_real_state'] == '1'){
            exit(json_encode(array('msg'=>'您的实名信息已经完善！')));
        }

        $member_truename=empty($_POST['member_truename']) ? '' : $_POST['member_truename'];
        $member_cardid=empty($_POST['member_cardid']) ? '' : $_POST['member_cardid'];
        $member_cardid_image = empty($_POST['member_cardid_image']) ? '' : $_POST['member_cardid_image'];
        $member_cardid_back_image = empty($_POST['member_cardid_back_image']) ? '' : $_POST['member_cardid_back_image'];
        $member_cardid_person_image = empty($_POST['member_cardid_person_image']) ? '' : $_POST['member_cardid_person_image'];
        if(empty($member_truename)){
            exit(json_encode(array('code'=>1,'msg'=>'姓名不能为空')));
        }
        if(empty($member_cardid)){
            exit(json_encode(array('code'=>1,'msg'=>'身份证号不能为空')));
        }
        if(empty($member_cardid_image)){
            exit(json_encode(array('code'=>1,'msg'=>'身份证正面照不能为空')));
        }
        if(empty($member_cardid_back_image)){
            exit(json_encode(array('code'=>1,'msg'=>'身份证反面照不能为空')));
        }
        if(empty($member_cardid_person_image)){
            exit(json_encode(array('code'=>1,'msg'=>'手持身份证照不能为空')));
        }
        $member_face_image_path = MALL_ROOT.'/'.$member_cardid_image;
        $member_back_image_path = MALL_ROOT.'/'.$member_cardid_back_image;

        if(!file_exists($member_face_image_path) && !file_exists($member_back_image_path)){
            exit(json_encode(array('code'=>1,'msg'=>'上传失败，请稍后重试')));
        }

        $identity_result = get_identity_ocr(base64EncodeImage($member_face_image_path), base64EncodeImage($member_back_image_path));
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
                'member_truename'=>$member_truename,
                'member_cardid'=>$member_cardid,
                'member_cardid_image' => $member_cardid_image,
                'member_cardid_back_image' => $member_cardid_back_image,
                'member_cardid_person_image'=>$member_cardid_person_image,
                'member_real_state' => 1,
            );
            DB::update('member', $data, array('member_id'=>$this->member_id));

            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        }

        exit(json_encode(array('code'=>1,'msg'=>'身份证信息与实名信息不符合，请确认无误后重试！')));
    }
}

?>