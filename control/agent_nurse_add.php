<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_nurse_addControl extends BaseAgentControl {
    public function indexOp(){
        if(empty($this->agent_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$this->agent_id'");
        $agent['agent_qa_image'] = empty($agent['agent_qa_image'] ) ? array() : unserialize($agent['agent_qa_image'] );
        $agent['agent_service_image'] = empty($agent['agent_service_image'] ) ? array() : unserialize($agent['agent_service_image'] );
        $agent['agent_other_phone_choose'] = empty($agent['agent_other_phone_choose'] ) ? array() : unserialize($agent['agent_other_phone_choose'] );
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$this->agent_id' AND nurse_state=1");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND refund_amount=0");
        $question_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='$this->agent_id' AND answer_content=''");
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_nurse_add'));
    }
    public function addOp() {
        if(submitcheck()) {
            $agent_id=$this->agent_id;
            $nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
            $nurse_sex = empty($_POST['nurse_sex']) ? 0 : intval($_POST['nurse_sex']);
            $agent_phone = empty($_POST['agent_phone']) ? 0 : $_POST['agent_phone'];
            $nurse_age = empty($_POST['nurse_age']) ? '' : $_POST['nurse_age'];
            $birth_cityname = empty($_POST['birth_cityname']) ? '' : $_POST['birth_cityname'];
            $nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
            $service_type = empty($_POST['service_type']) ? '' : $_POST['service_type'];
            $nurse_special_service = empty($_POST['nurse_special_service']) ? '' : $_POST['nurse_special_service'];
            $nurse_price = empty($_POST['nurse_price']) ? '' : $_POST['nurse_price'];
            $nurse_content = empty($_POST['nurse_content']) ? '' : $_POST['nurse_content'];
//            $nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
            $nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
            $nurse_cardid_image = empty($_POST['nurse_cardid_image']) ? '' : $_POST['nurse_cardid_image'];
            $nurse_cardid_person_image = empty($_POST['nurse_cardid_person_image']) ? '' : $_POST['nurse_cardid_person_image'];
            $nurse_qa_image = empty($_POST['nurse_qa_image']) ? array() : $_POST['nurse_qa_image'];
            $nurse_work_exe=empty($_POST['nurse_work_exe']) ? array() : $_POST['nurse_work_exe'];
            $car_weight_list=empty($_POST['car_weight_list']) ? array() : $_POST['car_weight_list'];
            $car_price_list=empty($_POST['car_price_list']) ? array() : $_POST['car_price_list'];
            $students_state=empty($_POST['students_state']) ? 0 : intval($_POST['students_state']);
            $students_sale=empty($_POST['students_sale']) ? 0 : intval($_POST['students_sale']);
            $promise_state = empty($_POST['promise_state']) ? 0 : intval($_POST['promise_state']);
            $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
            $phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
            if(empty($nurse_name)) {
                exit(json_encode(array('id'=>'nurse_name', 'msg'=>'请输入家政人员姓名')));
            }
            if(empty($nurse_sex)) {
                exit(json_encode(array('id'=>'nurse_sex', 'msg'=>'请选择家政人员性别')));
            }
            if(empty($nurse_age)) {
                exit(json_encode(array('id'=>'nurse_age', 'msg'=>'请输入您的年龄')));
            }
            if(empty($birth_cityname)) {
                exit(json_encode(array('id'=>'nurse_citynam', 'msg'=>'请输入家政人员籍贯')));
            }
            if(empty($service_type)) {
                exit(json_encode(array('id'=>'service_type', 'msg'=>'请选择职业分类')));
            }
            if(empty($nurse_price)) {
                exit(json_encode(array('id'=>'nurse_price', 'msg'=>'请输入期待薪资')));
            }
            if(empty($nurse_content)) {
                exit(json_encode(array('id'=>'nurse_content', 'msg'=>'请输入个人简介')));
            }
            if(empty($nurse_image)) {
                exit(json_encode(array('id'=>'nurse_image', 'msg'=>'请上传个人照片')));
            }
            if(empty($nurse_cardid_image)) {
                exit(json_encode(array('id'=>'nurse_cardid_image', 'msg'=>'请上传身份证正面照')));
            }
            if(empty($nurse_cardid_person_image)) {
                exit(json_encode(array('id'=>'nurse_cardid_person_image', 'msg'=>'请上传手持身份证照')));
            }
            if(empty($member_phone)) {
                exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号必须填写')));
            }
            if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
                exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号格式不正确')));
            }

            if(!preg_match('/^1[0-9]{10}$/', $agent_phone)) {
                exit(json_encode(array('id'=>'member_phone', 'msg'=>'机构手机号格式不正确')));
            }

            // 检测用户手机号码是否已经注册
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
            if(!empty($member)) {
                exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号已经注册了')));
            }
            if(empty($phone_code)) {
                exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码必须填写')));
            }

            $code = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$member_phone' AND code_type = 'domestic_staff_join' ORDER BY postdate DESC LIMIT 0,1");

            // 对比数据库验证码
            $text_param = json_decode($code['text_param'], true);
            if(empty($text_param) || $text_param['code'] != $phone_code){
                exit(json_encode(array('id'=>'phone_code','msg'=>'验证码错误，请重新输入！')));
            }

            // 检测验证码是否已经被使用
            if($code['postdate'] != $code['lastupdate']){
                exit(json_encode(array('id'=>'phone_code','msg'=>'验证码已经被使用，请重新获取！')));
            }

            // 检测验证码有效性
            if((time() - strtotime($code['postdate'])) > 120){
                exit(json_encode(array('id'=>'phone_code','msg'=>'验证码已过期，请重新获取！')));
            }


            $member_password = num6();
//            $member_password = substr($member_phone, -6);
            $member_sn = makesn(4);
            $im_accid = random(32);
            $im_token = random(32);
            $data = array(
                'member_phone' => $member_phone,
                'member_password' => md5($member_password),
                'member_time' => time(),
                'member_token' => md5($member_sn),
                'yx_accid' => $im_accid,
                'yx_token' => $im_token,
            );
            $member_id = DB::insert('member', $data, 1);
            if(empty($member_id)) {
                exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
            }
            //创建云信ID
            $im_init_data = array(
                'accid' => $im_accid,
                'token' => $im_token
            );
            $nim = new NimUser();
            $nim->create($im_init_data);

            $red_template = DB::fetch_first("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='new'");
            if(!empty($red_template) && $red_template['red_t_total'] > $red_template['red_t_giveout']) {
                if($red_template['red_t_period_type'] == 'duration') {
                    $red_template['red_t_starttime'] = strtotime(date('Y-m-d'));
                    $red_template['red_t_endtime'] = $red_template['red_t_starttime']+3600*24*($red_template['red_t_days']+1)-1;
                }
                $red_data = array(
                    'red_sn' => makesn(2),
                    'member_id' => $member_id,
                    'red_t_id' => $red_template['red_t_id'],
                    'red_title' => $red_template['red_t_title'],
                    'red_price' => $red_template['red_t_price'],
                    'red_starttime' => $red_template['red_t_starttime'],
                    'red_endtime' => $red_template['red_t_endtime'],
                    'red_limit' => $red_template['red_t_limit'],
                    'red_cate_id' => $red_template['red_t_cate_id'],
                    'red_state' => 0,
                    'red_addtime' => time(),
                );
                $red_id = DB::insert('red', $red_data, 1);
                if(!empty($red_id)) {
                    DB::update('red_template', array('red_t_giveout'=>$red_template['red_t_giveout']+1), array('red_t_id'=>$red_template['red_t_id']));
                }
            }
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
                'agent_id'=>$agent_id,
                'member_id'=>$member_id,
                'nurse_name'=>$nurse_name,
                'agent_phone'=>$agent_phone,
                'nurse_nickname'=>$nurse_nickname,
                'nurse_sex'=>$nurse_sex,
                'nurse_age'=>$nurse_age,
                'birth_cityname' => $birth_cityname,
                'nurse_type'=>$nurse_type,
                'service_type'=>$service_type,
                'nurse_special_service'=>$nurse_special_service,
                'nurse_price'=>$nurse_price,
                'nurse_content'=>$nurse_content,
                'nurse_discount'=>$nurse_discount,
                'nurse_image' => $nurse_image,
                'nurse_cardid_image' => $nurse_cardid_image,
                'nurse_cardid_person_image' => $nurse_cardid_person_image,
                'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
                'nurse_work_exe'=>empty($nurse_work_exe) ? '' : serialize($nurse_work_exe),
                'car_weight_list'=>empty($car_weight_list) ? '' : serialize($car_weight_list),
                'car_price_list'=>empty($car_price_list) ? '' : serialize($car_price_list),
                'students_state'=>$students_state,
                'students_sale'=>$students_sale,
                'promise_state'=>$promise_state,
                'member_phone'=>$member_phone,
                'grade_id'=>1,
                'nurse_score'=>90,
                'nurse_state' => 0,
                'nurse_time' => time()
            );
            $nurse_id = DB::insert('nurse', $data, 1);
            // 更新验证码使用时间
            DB::update('code_queue', array('lastupdate' => date('Y-m-d H:i:s')), array('queue_id' => $code['queue_id']));
            if(!empty($nurse_id)) {
                exit(json_encode(array('done'=>'true')));
            } else {
                exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
            }
        }

    }
}

?>