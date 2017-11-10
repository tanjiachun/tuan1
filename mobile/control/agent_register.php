<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_registerControl extends BaseMobileControl {
    public function indexOp(){
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
        if(!empty($agent)) {
            exit(json_encode(array('code'=>1,'msg'=>'你已经是家政机构')));
        }
        $agent_name = empty($_POST['agent_name']) ? '' : $_POST['agent_name'];
        $owner_name = empty($_POST['owner_name']) ? '' : $_POST['owner_name'];
        $agent_address = empty($_POST['agent_address']) ? '' : $_POST['agent_address'];
        $agent_location = empty($_POST['agent_location']) ? '' : $_POST['agent_location'];
        $agent_person_image = empty($_POST['agent_person_image']) ? '' : $_POST['agent_person_image'];
        $agent_person_back_image = empty($_POST['agent_person_back_image']) ? '' : $_POST['agent_person_back_image'];
        $agent_code_image = empty($_POST['agent_code_image']) ? '' : $_POST['agent_code_image'];
        $agent_person_code_image = empty($_POST['agent_person_code_image']) ? '' : $_POST['agent_person_code_image'];
        if(empty($agent_name)){
            exit(json_encode(array('code'=>1,'msg'=>'机构名称必须填写')));
        }
        if(empty($owner_name)){
            exit(json_encode(array('code'=>1,'msg'=>'法人姓名必须填写')));
        }
        if(empty($agent_address)){
            exit(json_encode(array('code'=>1,'msg'=>'机构地址必须填写')));
        }
        if(empty($agent_person_image)) {
            exit(json_encode(array('code'=>1, 'msg'=>'法人身份证正面必须上传')));
        }
        if(empty($agent_person_back_image)){
            exit(json_encode(array('code'=>1, 'msg'=>'法人身份证反面必须上传')));
        }
        if(empty($agent_code_image)){
            exit(json_encode(array('code'=>1,'msg'=>'营业执照正面必须上传')));
        }
        if(empty($agent_person_code_image)) {
            exit(json_encode(array('code'=>1, 'msg'=>'法人手持营业执照必须上传')));
        }
        $data = array(
            'member_id' => $this->member_id,
            'member_phone'=>$member['member_phone'],
            'agent_name' => $agent_name,
            'owner_name'=>$owner_name,
            'agent_address' => $agent_address,
            'agent_location'=>$agent_location,
            'agent_score'=>90,
            'agent_code_image' => $agent_code_image,
            'agent_person_image'=>$agent_person_image,
            'agent_person_back_image'=>$agent_person_back_image,
            'agent_person_code_image'=>$agent_person_code_image,
            'agent_time' => time(),
        );
        $agent_image_path = MALL_ROOT.'/'.$agent_code_image;
        $agent_card_image_code = base64EncodeImage($agent_image_path);
        $verify_data = company_card_verify($agent_card_image_code);
        if($verify_data){
            if(!empty($verify_data['name']) && $verify_data['name'] != $agent_name){
                exit(json_encode(array('code'=>1, 'msg'=>'您上传的资料有误，请检查后重试！')));
            }
            if(!empty($verify_data['name']) && $verify_data['name'] != $agent_name){
                exit(json_encode(array('code'=>1, 'msg'=>'您上传的资料有误，请检查后重试！')));
            }
        }else{
            exit(json_encode(array('code'=>1, 'msg'=>'您上传的营业执照信息无法识别，请更换更清晰的图片后重试！')));
        }
        $data['api_result'] = json_encode($verify_data);
        $agent_id = DB::insert('agent', $data, 1);
        if(!empty($agent_id)) {
            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        } else {
            exit(json_encode(array('code'=>1, 'msg'=>'网路不稳定，请稍候重试')));
        }
    }

}
?>