<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class miscControl extends BaseMobileControl {
	public function versionOp() {
		$version = DB::result_first("SELECT setting_value FROM ".DB::table('setting')." WHERE setting_key='$version'");
		$data = array(
			'version' => intval($version)
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function uploadOp() {
		$error_data = (object)array();
		require_once(MALL_ROOT.'/system/libraries/upload.php');
		$upload = new Upload();	
		$upload->init($_FILES['image_file'], 'mobile');
		$attach = $upload->attach;
		if(!$upload->error()) {
			$upload->save();
		}
		if($upload->error()) {
			exit(json_encode(array('code'=>'1', 'msg'=>$upload->errormessage(), 'data'=>$error_data)));
		}
		$file_path = 'data/mobile/'.$attach['attachment'];
		$thumb = empty($_POST['thumb']) ? 0 : intval($_POST['thumb']);
		if(!empty($thumb)) {
			require_once(MALL_ROOT.'/system/libraries/image.php');
			$image = new Image();
			$image->Thumb($file_path, '', 240, 240);
		}
		$data = array(
			'filepath' => $file_path
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'上传成功', 'data'=>$data)));	
	}
	
	public function getdistrictidOp() {
		$error_data = (object)array();
		$district_name = empty($_POST['district_name']) ? '' : $_POST['district_name'];
		$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_name like '%".$district_name."%'");
		if(empty($district)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未找到地址', 'data'=>$error_data)));
		} else {
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$district)));
		}
	}
	
	public function districtOp() {
		$district_id = empty($_POST['district_id']) ? 0 : intval($_POST['district_id']);
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$district_id' ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$district_list[] = $value;
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$district_list)));
	}
	
	public function cityOp() {
		$query = DB::query("SELECT parent_id, COUNT(*) as num FROM ".DB::table('district')." WHERE district_level=3 GROUP BY parent_id");
		while($value = DB::fetch($query)) {
			$district_list[$value['parent_id']] = $value['num'];
		}
		$query = DB::query("SELECT district_id, district_name, district_ipname, district_letter, parent_id FROM ".DB::table('district')." WHERE district_id in ('1', '2', '9', '22', '32', '33', '34')");
		while($value = DB::fetch($query)) {
			$value['is_child'] = 1;
			$city_list[] = $value;
		}
		$query = DB::query("SELECT district_id, district_name, district_ipname, district_letter, parent_id FROM ".DB::table('district')." WHERE district_level=2");
		while($value = DB::fetch($query)) {
			if(!in_array($value['parent_id'], array('1', '2', '9', '22', '32', '33', '34'))) {
				$value['is_child'] = empty($district_list[$value['district_id']]) ? 0 : 1;
				$city_list[] = $value;
			}
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$city_list)));
	}



    public function test_codeOp(){
        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
        if(empty($member_phone)) {
            exit(json_encode(array('msg'=>'手机号必须填写')));
        }
        if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
            exit(json_encode(array('msg'=>'手机号格式不正确')));
        }
        $current_time = strtotime(date('Y-m-d'));
        $code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$member_phone'");
        if(!empty($code)) {
            if($code['current_time'] != $current_time) {
                DB::update('test_code', array('current_time'=>$current_time, 'code_times'=>0), array('code_id'=>$code['code_id']));
                $code['times'] = 0;
            }
            if($code['times'] > 10) {
                exit(json_encode(array('msg'=>'手机号一天只能发送10次')));
            }
            $duration = time() - $code['code_addtime'];
            if($duration <= 60) {
                exit(json_encode(array('msg'=>'每隔60秒可以发送1次')));
            }
        }
        $code_value = $this->createCode();
        $content = '{"code":"'.$code_value.'", "product":"团家政"}';
        $smsstate = sendsms($member_phone, 'SMS_75755201', $content);
        if($smsstate) {
            if(!empty($code)) {
                DB::update('test_code', array('code_value'=>$code_value, 'code_times'=>$code['code_times']+1, 'code_addtime'=>time()), array('code_id'=>$code['code_id']));
            } else {
                $data = array(
                    'code_phone' => $member_phone,
                    'code_value' => $code_value,
                    'current_time' => $current_time,
                    'code_times' => 1,
                    'code_addtime' => time(),
                );
                DB::insert('test_code', $data);
            }
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg'=>'短信发送失败')));
        }
    }

//    public function login_pwd_codeOp(){
//        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
//        if(empty($member_phone)) {
//            exit(json_encode(array('msg'=>'手机号必须填写')));
//        }
//        if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
//            exit(json_encode(array('msg'=>'手机号格式不正确')));
//        }
//        $current_time = strtotime(date('Y-m-d'));
//        $code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$member_phone'");
//        if(!empty($code)) {
//            if($code['current_time'] != $current_time) {
//                DB::update('test_code', array('current_time'=>$current_time, 'code_times'=>0), array('code_id'=>$code['code_id']));
//                $code['times'] = 0;
//            }
//            if($code['times'] > 10) {
//                exit(json_encode(array('msg'=>'手机号一天只能发送10次')));
//            }
//            $duration = time() - $code['code_addtime'];
//            if($duration <= 60) {
//                exit(json_encode(array('msg'=>'每隔60秒可以发送1次')));
//            }
//        }
//        $code_value = $this->createCode();
//        $content = '{"code":"'.$code_value.'", "product":"团家政"}';
//        $smsstate = sendsms($member_phone, 'SMS_77270074', $content);
//        if($smsstate) {
//            if(!empty($code)) {
//                DB::update('test_code', array('code_value'=>$code_value, 'code_times'=>$code['code_times']+1, 'code_addtime'=>time()), array('code_id'=>$code['code_id']));
//            } else {
//                $data = array(
//                    'code_phone' => $member_phone,
//                    'code_value' => $code_value,
//                    'current_time' => $current_time,
//                    'code_times' => 1,
//                    'code_addtime' => time(),
//                );
//                DB::insert('test_code', $data);
//            }
//            exit(json_encode(array('done'=>'true')));
//        } else {
//            exit(json_encode(array('msg'=>'短信发送失败')));
//        }
//    }

//    public function pay_pwd_codeOp(){
//        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
//        if(empty($member_phone)) {
//            exit(json_encode(array('msg'=>'手机号必须填写')));
//        }
//        if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
//            exit(json_encode(array('msg'=>'手机号格式不正确')));
//        }
//        $current_time = strtotime(date('Y-m-d'));
//        $code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$member_phone'");
//        if(!empty($code)) {
//            if($code['current_time'] != $current_time) {
//                DB::update('test_code', array('current_time'=>$current_time, 'code_times'=>0), array('code_id'=>$code['code_id']));
//                $code['times'] = 0;
//            }
//            if($code['times'] > 10) {
//                exit(json_encode(array('msg'=>'手机号一天只能发送10次')));
//            }
//            $duration = time() - $code['code_addtime'];
//            if($duration <= 60) {
//                exit(json_encode(array('msg'=>'每隔60秒可以发送1次')));
//            }
//        }
//        $code_value = $this->createCode();
//        $content = '{"code":"'.$code_value.'", "product":"团家政"}';
//        $smsstate = sendsms($member_phone, 'SMS_94505047', $content);
//        if($smsstate) {
//            if(!empty($code)) {
//                DB::update('test_code', array('code_value'=>$code_value, 'code_times'=>$code['code_times']+1, 'code_addtime'=>time()), array('code_id'=>$code['code_id']));
//            } else {
//                $data = array(
//                    'code_phone' => $member_phone,
//                    'code_value' => $code_value,
//                    'current_time' => $current_time,
//                    'code_times' => 1,
//                    'code_addtime' => time(),
//                );
//                DB::insert('test_code', $data);
//            }
//            exit(json_encode(array('done'=>'true')));
//        } else {
//            exit(json_encode(array('msg'=>'短信发送失败')));
//        }
//    }




	
	public function third_codeOp() {
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		if(empty($member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号必须填写', 'data'=>array())));	
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号格式不正确', 'data'=>array())));
		}
		$current_time = strtotime(date('Y-m-d'));
		$code = DB::fetch_first("SELECT * FROM ".DB::table('code')." WHERE code_phone='$member_phone'");
		if(!empty($code)) {		
			if($code['current_time'] != $current_time) {
				DB::update('code', array('current_time'=>$current_time, 'code_times'=>0), array('code_id'=>$code['code_id']));
				$code['times'] = 0;
			}
			if($code['times'] > 10) {
				exit(json_encode(array('code'=>'1', 'msg'=>'手机号一天只能发送10次', 'data'=>array())));	
			}
			$duration = time() - $code['code_addtime'];
			if($duration <= 60) {
				exit(json_encode(array('code'=>'1', 'msg'=>'每隔60秒可以发送1次', 'data'=>array())));
			}
		}
		$code_value = $this->createCode();
		require_once(MALL_ROOT.'/static/sms/sms.php');
		$smsmessage = new PublishBatchSMSMessage($member_phone, 'SMS_70530439', array('code'=>$code_value));
		$smsstate = $smsmessage->run();
		if($smsstate) {
			if(!empty($code)) {
				DB::update('code', array('code_value'=>$code_value, 'code_times'=>$code['code_times']+1, 'code_addtime'=>time()), array('code_id'=>$code['code_id']));
			} else {
				$data = array(
					'code_phone' => $member_phone,
					'code_value' => $code_value,
					'current_time' => $current_time,
					'code_times' => 1,
					'code_addtime' => time(),
				);
				DB::insert('code', $data);
			}
			exit(json_encode(array('code'=>'0', 'msg'=>'短信发送成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'短信发送失败', 'data'=>array())));
		}
	}
	
	private function createCode($length = 6) {  
		$chars = "0123456789";
		$str="";
		for($i = 0; $i < $length; $i++) {
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}  
		return $str;  
	}

    /**
     * 检测用户手机号码
     * @return string $member_phone
     */
    private function check_member_phone(){
        $member_phone = empty($_REQUEST['member_phone']) ? '' : $_REQUEST['member_phone'];
        if(empty($member_phone)) {
            exit(json_encode(array('code'=>1,'msg'=>'手机号必须填写')));
        }
        if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
            exit(json_encode(array('code'=>1,'msg'=>'手机号格式不正确')));
        }
        return $member_phone;
    }

    /**
     * 检测用户状态
     */
    private function get_member_by_phone($member_phone){
        $member_info = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
        if(!empty($member_info)){
            return $member_info;
        }else{
            return false;
        }
    }

    /**
     * @param string $phone_number
     * @param string  $template_code
     *
     */
    private function send_text_code($phone_number, $template_code){
        $current_time = date('Y-m-d');
        $send_count = DB::fetch_first("SELECT COUNT(phone_number) as num FROM ".DB::table('code_queue')." WHERE phone_number='$phone_number' AND code_type = '$template_code' AND postdate > '$current_time'");
        // 限制每天发送数量
        if(!empty($send_count['num']) && $send_count['num'] >= SMS_SEND_LITMIT){
            exit(json_encode(array('code'=>1,'msg'=>'您的验证码获取过于频繁，请稍后再试！')));
        }

        // 限制60秒只能发送一次同类型验证码
        $last_send = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$phone_number' AND code_type = '$template_code' ORDER BY postdate DESC");
        if(!empty($last_send) && strtotime($last_send['postdate'])+60 > time())
        {
            $wait_time = 60 -(time()- strtotime($last_send['postdate']));
            exit(json_encode(array('code'=>1,'msg'=>'每隔60秒可以发送1次', 'time'=>$wait_time)));
        }
        $content =  array(
            'code' => $this->createCode()
        );
        $text_send = new Simple_SMS($phone_number, $template_code, $content);
        $send_result = $text_send->async_send();
        if($send_result){
            exit(json_encode(array('code'=>0, 'msg'=>'短信发送成功！')));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'短信发送失败！')));
        }
    }

    /**
     * 修改登录密码短信验证码请求接口
     * 请求方法：GET
     * 请求地址：/mobile.php?act=misc&op=login_pwd_code
     * 身份验证：是
     */
    public function login_pwd_codeOp(){
        // 检测用户权限
        $this->check_authority();
        $member_phone = $this->member['member_phone'];

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'passwd_modify');
    }

    /**
     * 修改支付密码短信验证码请求接口
     * 请求方法：GET
     * 请求地址：/mobile.php?act=misc&op=pay_pwd_code
     * 身份验证：是
     */
    public function pay_pwd_codeOp(){
        // 检测用户权限
        $this->check_authority();

        $member_phone = $this->member['member_phone'];

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'pay_passwd_modify');
    }

    /**
     * 找回密码短信验证码请求接口
     * 请求方法：POST/GET
     * 请求地址：/mobile.php?act=misc&op=find_pwd_code
     * 身份验证：是
     * @param member_phone
     */
    public function find_pwd_codeOp(){
        $member_phone = $this->check_member_phone();
        if($this->get_member_by_phone($member_phone) === false){
            exit(json_encode(array('code'=> 1, 'msg'=>'该手机号码还没有注册，请先注册！')));
        }
        // 短信验证码登陆
        $this->send_text_code($member_phone, 'find_passwd');
    }

    /**
     * 用户注册短信验证码请求接口
     * 请求方法：POST/GET
     * 请求地址：/mobile.php?act=misc&op=first_code
     * 身份验证：是
     * @param member_phone
     */
    public function first_codeOp() {
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测用户信息
        if($this->get_member_by_phone($member_phone) !== false){
            exit(json_encode(array('code'=> 1, 'msg'=>'手机号已经注册了')));
        }

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'register_code');

    }

    /**
     * 验证码登陆验证码请求接口
     * 请求方法：POST/GET
     * 请求地址：/mobile.php?act=misc&op=second_code
     * 身份验证：是
     * @param member_phone
     */
    public function second_codeOp() {
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测用户信息
        if($this->get_member_by_phone($member_phone) === false){
            exit(json_encode(array('code'=> 1, 'msg'=>'该手机号码还没有注册，请先注册！')));
        }

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'login_code');
    }


    public function nurse_codeOp(){
        // 检测用户权限
        $this->check_authority();
        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
        // 短信验证码登陆
        $this->send_text_code($member_phone, 'domestic_staff_join');
    }
}
?>