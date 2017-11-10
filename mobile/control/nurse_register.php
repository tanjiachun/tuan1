<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_registerControl extends BaseMobileControl {
    public function indexOp(){
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(!empty($member) && $member['member_real_state'] == '1'){
            exit(json_encode(array('code'=>0,'msg'=>'您的实名信息已经完善！','data'=>array('member_truename'=>$member['member_truename'],'member_sex'=>$member['member_sex'],'member_phone'=>$member['member_phone']))));
        }else{
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
                $result = array(
                    'code' => '1',
                    'msg' => '身份证号码识别失败',
                    'data' => array(),
                );
                $check_result  = check_identity($member_cardid);
                if(!empty($check_result['area']))
                {
                    $result['code'] = '0';
                    $result['msg'] = '身份证信息识别成功';
                    $result['data'] = $check_result;
                }
                exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$check_result)));
            }
            exit(json_encode(array('code'=>1,'msg'=>'身份证信息与实名信息不符合，请确认无误后重试！')));
        }

    }

    public function step2Op(){
        $this->check_authority();
        $nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
        $nurse_sex = empty($_POST['nurse_sex']) ? 0 : intval($_POST['nurse_sex']);
        $nurse_age = empty($_POST['nurse_age']) ? '' : $_POST['nurse_age'];
        $birth_cityname = empty($_POST['birth_cityname']) ? '' : $_POST['birth_cityname'];
        $nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
        $service_type = empty($_POST['service_type']) ? '' : $_POST['service_type'];
        $nurse_special_service = empty($_POST['nurse_special_service']) ? '' : $_POST['nurse_special_service'];
        $nurse_price = empty($_POST['nurse_price']) ? '' : $_POST['nurse_price'];
        $nurse_cardid_image = empty($_POST['nurse_cardid_image']) ? '' : $_POST['nurse_cardid_image'];
        $nurse_cardid_back_image = empty($_POST['nurse_cardid_back_image']) ? '' : $_POST['nurse_cardid_back_image'];
        $nurse_cardid_person_image = empty($_POST['nurse_cardid_person_image']) ? '' : $_POST['nurse_cardid_person_image'];
        $car_weight_list=empty($_POST['car_weight_list']) ? array() : $_POST['car_weight_list'];
        $car_price_list=empty($_POST['car_price_list']) ? array() : $_POST['car_price_list'];
        $students_state=empty($_POST['students_state']) ? 0 : intval($_POST['students_state']);
        $students_sale=empty($_POST['students_sale']) ? 0 : intval($_POST['students_sale']);
        $promise_state = empty($_POST['promise_state']) ? 0 : intval($_POST['promise_state']);
//        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
//        $phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
        $agent_id = empty($_POST['agent_id']) ? '' : $_POST['agent_id'];
        if(empty($nurse_name)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请输入家政人员姓名')));
        }
        if(empty($nurse_sex)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请选择家政人员性别')));
        }
        if(empty($nurse_age)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请输入您的年龄')));
        }
        if(empty($birth_cityname)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请输入家政人员籍贯')));
        }
        if(empty($service_type)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请选择职业分类')));
        }
        if(empty($nurse_price)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请输入期待薪资')));
        }
        if(empty($nurse_cardid_image)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请上传身份证正面照')));
        }
        if(empty($nurse_cardid_back_image)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请上传身份证正面照')));
        }
        if(empty($nurse_cardid_person_image)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请上传手持身份证照')));
        }
//        if(empty($member_phone)) {
//            exit(json_encode(array('code'=>1, 'msg'=>'手机号必须填写')));
//        }
//        if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
//            exit(json_encode(array('code'=>1, 'msg'=>'手机号格式不正确')));
//        }
//        if(empty($phone_code)){
//            exit(json_encode(array('code'=>1,'msg'=>'验证码必须填写')));
//        }

//        // 检测用户手机号码是否已经注册
//        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
//        if(!empty($member)) {
//            exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号已经注册了')));
//        }
//        if(empty($phone_code)) {
//            exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码必须填写')));
//        }
//
//        $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'domestic_staff_join' ORDER BY postdate DESC LIMIT 0,1");
//
//        // 对比数据库验证码
//        $text_param = json_decode($code['text_param'], true);
//        if(empty($text_param) || $text_param['code'] != $phone_code){
//            exit(json_encode(array('id'=>'phone_code','msg'=>'验证码错误，请重新输入！')));
//        }
//
//        // 检测验证码是否已经被使用
//        if($code['postdate'] != $code['lastupdate']){
//            exit(json_encode(array('id'=>'phone_code','msg'=>'验证码已经被使用，请重新获取！')));
//        }
//
//        // 检测验证码有效性
//        if((time() - strtotime($code['postdate'])) > 120){
//            exit(json_encode(array('id'=>'phone_code','msg'=>'验证码已过期，请重新获取！')));
//        }

        // 名字 正则
        if($nurse_type==1 || $nurse_type==2 || $nurse_type==5 || $nurse_type==6){
            if($nurse_sex==2){
                $nurse_nickname=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0阿姨", $nurse_name,1);
            }else{
                $nurse_nickname=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0师傅", $nurse_name,1);
            }
        }elseif ($nurse_type==3 || $nurse_type==4){
            $nurse_nickname=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0点工", $nurse_name,1);
        }elseif ($nurse_type==7 || $nurse_type==8 || $nurse_type==9 || $nurse_type==10){
            $nurse_nickname=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0师傅", $nurse_name,1);
        }elseif ($nurse_type==11 || $nurse_type==12 || $nurse_type==16){
            $nurse_nickname=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0老师", $nurse_name,1);
        }elseif ($nurse_type==13 || $nurse_type==14){
            $nurse_nickname=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0护工", $nurse_name,1);
        }elseif ($nurse_type==15){
            $nurse_nickname=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0管家", $nurse_name,1);
        }
        $nurse_nickname=substr ($nurse_nickname,0,9);
        $nurse_discount=0.95;
        if($nurse_type==1 || $nurse_type==2 || $nurse_type==11 || $nurse_type==12 || $nurse_type==13 || $nurse_type==14 || $nurse_type==15 || $nurse_type== 16){
            $nurse_discount=0.8;
        }
        $data=array(
            'member_id'=>$this->member_id,
            'nurse_name'=>$nurse_name,
            'nurse_nickname'=>$nurse_nickname,
            'nurse_sex'=>$nurse_sex,
            'nurse_age'=>$nurse_age,
            'birth_cityname' => $birth_cityname,
            'nurse_type'=>$nurse_type,
            'service_type'=>$service_type,
            'nurse_special_service'=>$nurse_special_service,
            'nurse_price'=>$nurse_price,
            'nurse_discount'=>$nurse_discount,
            'nurse_cardid_image' => $nurse_cardid_image,
            'nurse_cardid_back_image' => $nurse_cardid_back_image,
            'nurse_cardid_person_image' => $nurse_cardid_person_image,
            'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
            'nurse_work_exe'=>empty($nurse_work_exe) ? '' : serialize($nurse_work_exe),
            'car_weight_list'=>empty($car_weight_list) ? '' : serialize($car_weight_list),
            'car_price_list'=>empty($car_price_list) ? '' : serialize($car_price_list),
            'students_state'=>$students_state,
            'students_sale'=>$students_sale,
            'promise_state'=>$promise_state,
            'member_phone'=>$this->member['member_phone'],
            'grade_id'=>1,
            'nurse_score'=>90,
            'nurse_state' => 0,
            'nurse_time' => time()
        );
        $nurse_id = DB::insert('nurse', $data, 1);
        // 更新验证码使用时间
//        DB::update('code_queue', array('lastupdate' => date('Y-m-d H:i:s')), array('queue_id' => $code['queue_id']));
        if(!empty($nurse_id)) {
            if(!empty($agent_id)){
                $audit_data=array(
                    'agent_id'=>$agent_id,
                    'nurse_id'=>$nurse_id,
                    'nurse_state'=>1,
                    'staff_time'=>time()
                );
                DB::insert('staff_audit',$audit_data);
            }
            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        } else {
            exit(json_encode(array('code'=>1, 'msg'=>'网路不稳定，请稍候重试')));
        }
    }
}

?>