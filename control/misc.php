<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class miscControl {
	public function qrcodeOp() {
		require_once(MALL_ROOT.'/static/qrcode/qrcode.php');
		$value=urldecode($_GET['l']);
		$errorCorrectionLevel = empty($_GET['e']) ?  'L' : $_GET['e'];
		$matrixPointSize = empty($_GET['size']) ?  '5' : intval($_GET['size']);
		QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);
		exit;
	}

	public function checkcardOp() {
		$card_id = empty($_GET['card_id']) ? '' : $_GET['card_id'];
		if(checkcard($card_id)) {
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'身份证号码格式不正确')));
		}
	}

	public function first_provinceOp() {
		$province_id = empty($_GET['province_id']) ? 0 : intval($_GET['province_id']);
		if(!empty($province_id)) {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$province_id' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$city_list[] = $value;
			}
		}
		$html = '';
		if(!empty($city_list)) {
			$html .= '<div class="select-class">';
			$html .= '<a href="javascript:;" class="select-choice">-城市-<i class="select-arrow"></i></a>';
			$html .= '<div class="select-list" style="display: none">';
			$html .= '<ul>';
			$html .= '<li field_value="">-城市-</li>';
			foreach($city_list as $key => $value) {
				$html .= '<li field_value="'.$value['district_id'].'">'.$value['district_name'].'</li>';
			}
			$html .= '</ul>';
			$html .= '</div>';
			$html .= '</div>';
		}
		exit(json_encode(array('done'=>'true', 'html'=>$html)));
	}

	public function first_cityOp() {
		$city_id = empty($_GET['city_id']) ? 0 : intval($_GET['city_id']);
		if(!empty($city_id)) {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$city_id' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$area_list[] = $value;
			}
		}
		$html = '';
		if(!empty($area_list)) {
			$html .= '<div class="select-class">';
			$html .= '<a href="javascript:;" class="select-choice">-州县-<i class="select-arrow"></i></a>';
			$html .= '<div class="select-list" style="display: none">';
			$html .= '<ul class="area_box">';
			$html .= '<li field_value="">-州县-</li>';
			foreach($area_list as $key => $value) {
				$html .= '<li field_value="'.$value['district_id'].'">'.$value['district_name'].'</li>';
			}
			$html .= '</ul>';
			$html .= '</div>';
			$html .= '</div>';
		}
		exit(json_encode(array('done'=>'true', 'html'=>$html)));
	}

	public function second_provinceOp() {
		$province_id = empty($_GET['province_id']) ? 0 : intval($_GET['province_id']);
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$province_id' ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$city_list[] = $value;
		}
		$html = '';
		if(!empty($city_list)) {
			$html .= '<option value="">-城市-</option>';
			foreach($city_list as $key => $value) {
				$html .= '<option value="'.$value['district_id'].'">'.$value['district_name'].'</option>';
			}
		}
		exit(json_encode(array('done'=>'true', 'html'=>$html)));
	}

	public function second_cityOp() {
		$city_id = empty($_GET['city_id']) ? 0 : intval($_GET['city_id']);
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$city_id' ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$area_list[] = $value;
		}
		$html = '';
		if(!empty($area_list)) {
			$html = '<option value="">-州县-</option>';
			foreach($area_list as $key => $value) {
				$html .= '<option value="'.$value['district_id'].'">'.$value['district_name'].'</option>';
			}
		}
		exit(json_encode(array('done'=>'true', 'html'=>$html)));
	}

	public function first_seccodeOp() {
		$onlineip = getip();
		$authkey = md5($GLOBALS['config']['security']['authkey'].$_SERVER['HTTP_USER_AGENT'].$onlineip);
		$seccode = random(6, 1);
		$firstseccodeinit = authcode($seccode, 'ENCODE', $authkey);
		dsetcookie('firstseccodeinit', $firstseccodeinit, 360);
		ob_end_clean();
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		include_once MALL_ROOT.'/system/libraries/seccode.php';
		$code = new Seccode();
		$code->code = $seccode;
		$code->type = 0;
		$code->width = 100;
		$code->height = 30;
		$code->background = 3;
		$code->adulterate = 1;
		$code->ttf = 1;
		$code->angle = 1;
		$code->warping = 0;
		$code->scatter = 0;
		$code->color = 1;
		$code->size = 0;
		$code->shadow = 1;
		$code->fontpath = MALL_ROOT.'/static/seccode/font/';
		$code->datapath = MALL_ROOT.'/static/seccode/';
		$code->display();
	}

	public function second_seccodeOp() {
		$onlineip = getip();
		$authkey = md5($GLOBALS['config']['security']['authkey'].$_SERVER['HTTP_USER_AGENT'].$onlineip);
		$seccode = random(6, 1);
		$secondseccodeinit = authcode($seccode, 'ENCODE', $authkey);
		dsetcookie('secondseccodeinit', $secondseccodeinit, 360);
		ob_end_clean();
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		include_once MALL_ROOT.'/system/libraries/seccode.php';
		$code = new Seccode();
		$code->code = $seccode;
		$code->type = 0;
		$code->width = 100;
		$code->height = 30;
		$code->background = 3;
		$code->adulterate = 1;
		$code->ttf = 1;
		$code->angle = 1;
		$code->warping = 0;
		$code->scatter = 0;
		$code->color = 1;
		$code->size = 0;
		$code->shadow = 1;
		$code->fontpath = MALL_ROOT.'/static/seccode/font/';
		$code->datapath = MALL_ROOT.'/static/seccode/';
		$code->display();
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


	public function first_codeOp() {
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		if(empty($member_phone)) {
			exit(json_encode(array('msg'=>'手机号必须填写')));
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('msg'=>'手机号格式不正确')));
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
		if(!empty($member)) {
			exit(json_encode(array('msg'=>'手机号已经绑定了')));
		}
		$current_time = strtotime(date('Y-m-d'));
		$code = DB::fetch_first("SELECT * FROM ".DB::table('code')." WHERE code_phone='$member_phone'");
		if(!empty($code)) {
			if($code['current_time'] != $current_time) {
				DB::update('code', array('current_time'=>$current_time, 'code_times'=>0), array('code_id'=>$code['code_id']));
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
            exit(json_encode(array('done'=>'true')));
        } else {
			exit(json_encode(array('msg'=>'短信发送失败')));
		}
	}

	public function second_codeOp() {
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		if(empty($member_phone)) {
			exit(json_encode(array('msg'=>'手机号必须填写')));
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('msg'=>'手机号格式不正确')));
		}
		$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
		if(empty($member)) {
			exit(json_encode(array('msg'=>'手机号不存在')));
		}
		$current_time = strtotime(date('Y-m-d'));
		$code = DB::fetch_first("SELECT * FROM ".DB::table('code')." WHERE code_phone='$member_phone'");
		if(!empty($code)) {
			if($code['current_time'] != $current_time) {
				DB::update('code', array('current_time'=>$current_time, 'code_times'=>0), array('code_id'=>$code['code_id']));
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
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'短信发送失败')));
		}
	}

	public function uploadOp() {
		$field_id = empty($_POST['field_id']) ? 0 : intval($_POST['field_id']);
		$file_name = !in_array($_POST['file_name'], array('goods', 'store', 'plat', 'nurse', 'agent')) ? 'temp' : $_POST['file_name'];
		require_once(MALL_ROOT."/system/libraries/upload.php");
		$upload = new Upload();
		$upload->init($_FILES[$_POST['id']], $file_name);
		$attach = $upload->attach;
		if(!$upload->error()) {
			$upload->save();
		}
		if($upload->error()) {
			exit(json_encode(array('msg'=>$upload->error())));
		}
		$file_path = 'data/'.$file_name.'/'.$attach['attachment'];
		$thumb = empty($_GET['thumb']) ? 0 : intval($_GET['thumb']);
		if(!empty($thumb)) {
			require_once(MALL_ROOT."/system/libraries/image.php");
			$image = new Image();
			$image->Thumb($file_path, '', 240, 240);
		}
		exit(json_encode(array('done'=>'true', 'file_path'=>$file_path, 'field_id'=>$field_id)));
	}

	public function kindeditorOp() {
		require_once(MALL_ROOT."/system/libraries/upload.php");
		$upload = new Upload();
		$upload->init($_FILES['imgFile'], 'album');
		$attach = $upload->attach;
		if(!$upload->error()) {
			$upload->save();
		}
		if($upload->error()) {
			exit(json_encode(array('error'=>1, 'message'=>$upload->error())));
		}
		$url = 'data/album/'.$attach['attachment'];
		exit(json_encode(array('error'=>0, 'url'=>$url)));
	}


    /**
     * 初始化会员信息，通过cookies得到用户信息
     */
    private function init_member() {
        $mallauth = dgetcookie('mallauth');
        $auth_data = explode('\t', authcode($mallauth, 'DECODE'));
        list($member_password, $member_id) = empty($auth_data) || count($auth_data) < 2 ? array('', '') : $auth_data;
        if(!empty($member_password) && !empty($member_id)) {
            $cookie_member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
            if(!empty($cookie_member) && $cookie_member['member_password'] == $member_password) {
                $this->member_id = $cookie_member['member_id'];
                $this->member = $cookie_member;
            }
        }
        if(!empty($this->member_id)) {
            $this->nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
            $this->nurse_id = empty($this->nurse['nurse_id']) ? 0 : $this->nurse['nurse_id'];
        }
    }

    /**
     * 生成随机验证码
     * @param int $length
     * @return string
     *
     */
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
        $member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
        if(empty($member_phone)) {
            exit(json_encode(array('msg'=>'手机号必须填写')));
        }
        if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
            exit(json_encode(array('msg'=>'手机号格式不正确')));
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
            exit(json_encode(array('msg'=>'您的验证码获取过于频繁，请稍后再试！')));
        }

        // 限制60秒只能发送一次同类型验证码
        $last_send = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$phone_number' AND code_type = '$template_code' ORDER BY postdate DESC");
        if(!empty($last_send) && strtotime($last_send['postdate'])+60 > time())
        {
            $wait_time = 60 -(time()- strtotime($last_send['postdate']));
            exit(json_encode(array('msg'=>'每隔60秒可以发送1次', 'time'=>$wait_time)));
        }
        $content =  array(
            'code' => $this->createCode()
        );
        $text_send = new Simple_SMS($phone_number, $template_code, $content);
        $send_result = $text_send->async_send();
        if($send_result){
            exit(json_encode(array('done'=>'true')));
        }else{
            exit(json_encode(array('msg'=>'短信发送失败')));
        }
    }




    /**
     * 注册页面发送短信
     *
     */
	public function get_register_codeOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测用户信息
        if($this->get_member_by_phone($member_phone) !== false){
            exit(json_encode(array('msg'=>'手机号已经注册了')));
        }

        // 注册验证码
        $this->send_text_code($member_phone, 'register_code');
    }

    /**
     * 验证码登陆发送短信函数
     *
     */
    public function login_codeOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测用户信息
        if($this->get_member_by_phone($member_phone) === false){
            exit(json_encode(array('msg'=>'该手机号码还没有注册，请先注册！')));
        }

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'login_code');
    }



    /**
     * 修改登录密码短信验证码请求接口
     *
     */
    public function login_pwd_codeOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测用户信息
        if($this->get_member_by_phone($member_phone) === false){
            exit(json_encode(array('msg'=>'该手机号码还没有注册，请先注册！')));
        }

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'passwd_modify');
    }


    /**
     * 修改支付密码短信验证码请求接口
     *
     */
    public function pay_pwd_codeOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测用户信息
        if($this->get_member_by_phone($member_phone) === false){
            exit(json_encode(array('msg'=>'该手机号码还没有注册，请先注册！')));
        }

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'pay_passwd_modify');
    }

    /**
     * 用户实名认证短信验证码请求接口
     *
     */
    public function member_verify_codeOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测用户信息
        if($this->get_member_by_phone($member_phone) === false){
            exit(json_encode(array('msg'=>'该手机号码还没有注册，请先注册！')));
        }

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'account_verify');
    }

    /**
     * 机构添加家政人员短信验证码接口
     *
     */
    public function agent_nurse_add_codeOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测手机号码是否已经被注册
        if($this->get_member_by_phone($member_phone) !== false){
            exit(json_encode(array('msg'=>'该手机号码已经被注册，请使用其它号码！')));
        }

        $this->init_member();
        if(empty($this->member_id)){
            exit(json_encode(array('msg'=>'短信发送失败，请先登陆！')));
        }
        $agent_info = DB::fetch_first("SELECT agent_id, member_id FROM ".DB::table('agent')." WHERE member_id = '$this->member_id'");
        if(empty($agent_info)){
            exit(json_encode(array('msg'=>'您还没有权限执行此操作，请先申请注册机构！')));
        }

        // 短信验证码登陆
        $this->send_text_code($member_phone, 'domestic_staff_join');
    }

    /**
     * 忘记密码短信接口
     */
    public function forget_pwd_codeOp(){
        //检测用户号码
        $member_phone=$this->check_member_phone();
        // 检测用户信息
        if($this->get_member_by_phone($member_phone) === false){
            exit(json_encode(array('msg'=>'该手机号码还没有注册，请先注册！')));
        }
        //发送验证码
        $this->send_text_code($member_phone, 'find_passwd');
    }

    //注册团家政账户
    public function registerOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();

        // 检测手机号码是否已经被注册
        if($this->get_member_by_phone($member_phone) !== false){
            exit(json_encode(array('msg'=>'该手机号码已经被注册，请使用其它号码！')));
        }
        //发送验证码
        $this->send_text_code($member_phone, 'domestic_staff_join');
    }
    public function agent_registerOp(){
        // 检测用户号码
        $member_phone = $this->check_member_phone();
        //发送验证码
        $this->send_text_code($member_phone, 'domestic_staff_join');
    }

}
?>