<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_real_nameControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $curmodule = 'home';
        $bodyclass = '';
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $member_name = $member['member_truename'] ? $member['member_truename'] : $member['member_phone'];

        include(template('member_real_name'));
    }
    public function real_nameOp(){
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if($member['member_real_state']==0 && !empty($member['member_cardid_image'])){
            exit(json_encode(array('msg'=>'资料正在审核，请勿重复提交')));
        }
        $member_truename=empty($_POST['member_truename']) ? '' : $_POST['member_truename'];
        $member_cardid_image=empty($_POST['member_cardid_image']) ? '' : $_POST['member_cardid_image'];
        $member_cardid_back_image=empty($_POST['member_cardid_back_image']) ? '' : $_POST['member_cardid_back_image'];
        $member_cardid_person_image=empty($_POST['member_cardid_person_image']) ? '' : $_POST['member_cardid_person_image'];
        if(empty($member_truename)){
            exit(json_encode(array('msg'=>'请填写真实有效的姓名')));
        }
        if(empty($member_cardid_image)){
            exit(json_encode(array('msg'=>'请上传身份证正面照')));
        }
        if(empty($member_cardid_person_image)){
            exit(json_encode(array('msg'=>'请上传手持身份证照')));
        }
        $data=array(
            'member_truename'=>$member_truename,
            'member_cardid_image'=>$member_cardid_image,
            'member_cardid_back_image'=>$member_cardid_back_image,
            'member_cardid_person_image'=>$member_cardid_person_image
        );
        DB::update('member', $data, array('member_id'=>$this->member_id));
        exit(json_encode(array('done'=>'true')));
    }
}

?>