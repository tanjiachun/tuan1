<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class storeControl extends BaseHomeControl {
	public function indexOp() {
		if(!empty($this->member_id)) {
			$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE member_id='$this->member_id'");
			if(empty($store)) {
				@header("Location: index.php?act=store&op=step2");
				exit;
			} else {
				@header("Location: index.php?act=store_center");
				exit;
			}
		} else {
			@header("Location: index.php?act=store&op=login");
			exit;
		}
	}
	
	public function loginOp() {
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));	
			}
			$login_phone = empty($_POST['login_phone']) ? '' : $_POST['login_phone'];
			$login_password = empty($_POST['login_password']) ? '' : $_POST['login_password'];
			if(empty($login_phone)) {
				exit(json_encode(array('id'=>'login_phone', 'msg'=>'手机号必须填写')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $login_phone)) {
				exit(json_encode(array('id'=>'login_phone', 'msg'=>'手机号格式不正确')));
			}
			if(empty($login_password)) {
				exit(json_encode(array('id'=>'login_password', 'msg'=>'密码必须填写')));	
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$login_phone'");
			if(empty($member)) {
				exit(json_encode(array('id'=>'login_phone', 'msg'=>'手机号不存在')));	
			}
			if($member['member_password'] != md5($login_password)) {
				exit(json_encode(array('id'=>'login_password', 'msg'=>'密码错误')));	
			}
			dsetcookie('mallauth', authcode($member['member_password'].'\t'.$member['member_id'], 'ENCODE'), 259200);
			exit(json_encode(array('done'=>'true')));
		} else {
			if(!empty($this->member_id)) {
				$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE member_id='$this->member_id'");
				if(empty($store)) {
					@header("Location: index.php?act=store&op=step2");
					exit;
				} else {
					@header("Location: index.php?act=store_center");
					exit;
				}
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('store_login'));
		}
	}
	
	public function registerOp() {
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));	
			}
			$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
			$phone_code = empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
			$member_password = empty($_POST['member_password']) ? '' : $_POST['member_password'];
			$member_password2 = empty($_POST['member_password2']) ? '' : $_POST['member_password2'];
			if(empty($member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号必须填写')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号格式不正确')));
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
			if(!empty($member)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号已经注册了')));	
			}
			if(empty($phone_code)) {
				exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码必须填写')));	
			}
			$code = DB::fetch_first("SELECT * FROM ".DB::table('code')." WHERE code_phone='$member_phone' AND code_value='$phone_code'");
			if(empty($code)) {	
				exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码不正确')));
			}
			if(empty($member_password)) {
				exit(json_encode(array('id'=>'member_passwd', 'msg'=>'密码必须填写')));	
			}
			if($member_password != $member_password2) {
				exit(json_encode(array('id'=>'member_password2', 'msg'=>'两次密码必须保证一致')));	
			}
			$member_sn = makesn(4);
			$data = array(
				'member_phone' => $member_phone,
				'member_password' => md5($member_password),
				'member_time' => time(),
				'member_token' => md5($member_sn),
			);
			$member_id = DB::insert('member', $data, 1);
			if(empty($member_id)) {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
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
			dsetcookie('mallauth', authcode($data['member_password'].'\t'.$member_id, 'ENCODE'), 259200);
			exit(json_encode(array('done'=>'true')));
		} else {
			if(!empty($this->member_id)) {
				$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE member_id='$this->member_id'");
				if(empty($store)) {
					@header("Location: index.php?act=store&op=step2");
					exit;
				} else {
					@header("Location: index.php?act=store_center");
					exit;
				}	
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('store_register'));
		}
	}
	
	public function step2Op() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}
			$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE member_id='$this->member_id'");
			if(!empty($store)) {
				exit(json_encode(array('done'=>'store')));
			}
			$store_name = empty($_POST['store_name']) ? '' : $_POST['store_name'];
			$class_id = empty($_POST['class_id']) ? 0 : intval($_POST['class_id']);
			$store_provinceid = empty($_POST['store_provinceid']) ? 0 : intval($_POST['store_provinceid']);
			$store_cityid = empty($_POST['store_cityid']) ? 0 : intval($_POST['store_cityid']);
			$store_areaid = empty($_POST['store_areaid']) ? 0 : intval($_POST['store_areaid']);
			$store_address = empty($_POST['store_address']) ? '' : $_POST['store_address'];
			$store_content = empty($_POST['store_content']) ? '' : $_POST['store_content'];
			$store_cardid = empty($_POST['store_cardid']) ? '' : $_POST['store_cardid'];
			$store_cardid_image = empty($_POST['store_cardid_image']) ? '' : $_POST['store_cardid_image'];
			$store_qa_image = empty($_POST['store_qa_image']) ? array() : $_POST['store_qa_image'];
			if(empty($store_name)) {
				exit(json_encode(array('id'=>'store_name', 'msg'=>'店铺名称必须填写')));
			}
			if(empty($class_id)) {
				exit(json_encode(array('id'=>'class_id', 'msg'=>'产品类别必须填写')));
			}
			if(empty($store_provinceid)) {
				exit(json_encode(array('id'=>'store_provinceid', 'msg'=>'所在地区必须填写')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$store_cityid'");
			if(empty($district_count)) {
				if(empty($store_cityid)) {
					exit(json_encode(array('id'=>'store_provinceid', 'msg'=>'所在地区必须填写')));
				}	
			} else {
				if(empty($store_areaid)) {
					exit(json_encode(array('id'=>'store_provinceid', 'msg'=>'所在地区必须填写')));
				}
			}
			if(empty($store_address)) {
				exit(json_encode(array('id'=>'store_address', 'msg'=>'详细地址必须填写')));
			}
			if(empty($store_content)) {
				exit(json_encode(array('id'=>'store_content', 'msg'=>'主营业务必须填写')));
			}
			if(empty($store_cardid)) {
				exit(json_encode(array('id'=>'store_cardid', 'msg'=>'身份证号码必须填写')));
			}
			if(empty($store_cardid_image)) {
				exit(json_encode(array('id'=>'store_cardid_image', 'msg'=>'手持身份证照必须上传')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$store_provinceid'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$store_cityid'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$store_areaid'");
			$store_areainfo = $province_name.$city_name.$area_name;
			$data = array(
				'member_id' => $this->member_id,
				'store_name' => $store_name,
				'class_id' => $class_id,
				'store_provinceid' => $store_provinceid,
				'store_cityid' => $store_cityid,
				'store_areaid' => $store_areaid,
				'store_areainfo' => $store_areainfo,
				'store_address' => $store_address,
				'store_content' => $store_content,
				'store_cardid' => $store_cardid,
				'store_cardid_image' => $store_cardid_image,
				'store_qa_image' => empty($store_qa_image) ? '' : serialize($store_qa_image),
				'kd_rename' => '快递',
				'es_rename' => 'EMS',
				'py_rename' => '平邮',
				'store_time' => time(),
			);
			$store_id = DB::insert('store', $data, 1);
			if(!empty($store_id)) {
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			if(empty($this->member_id)) {
				@header("Location: index.php?act=store&op=login");
				exit;	
			}
			$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE member_id='$this->member_id'");
			if(!empty($store)) {
				@header("Location: index.php?act=store_center");
				exit;
			}
			$query = DB::query("SELECT * FROM ".DB::table('class')." WHERE class_type='store' ORDER BY class_sort ASC");
			while($value = DB::fetch($query)) {
				$class_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('store_register_step2'));
		}
	}
	
	public function step3Op() {
		$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE member_id='$this->member_id'");
		if(empty($store)) {
			@header("Location: index.php?act=store&op=step2");
			exit;
		}
		$curmodule = 'member';
		$bodyclass = '';
		include(template('store_register_step3'));
	}
}

?>