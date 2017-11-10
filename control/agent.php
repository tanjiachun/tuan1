<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agentControl extends BaseHomeControl {
	public function indexOp() {
		if(!empty($this->member_id)) {
			$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
			if(empty($agent)) {
				@header("Location: index.php?act=agent&op=step2");
				exit;
			} else {
				@header("Location: index.php?act=agent_center");
				exit;
			}
		} else {
//			@header("Location: index.php?act=agent&op=login");
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
			exit;
		}
	}
	
	public function loginOp() {
		if(submitcheck()) {
			if(!empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));	
			}
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
			if(!empty($nurse)){
			    exit(json_encode(array('id'=>'system','msg'=>'您已经是家政人员，无法申请成为机构')));
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
				$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
				if(empty($agent)) {
					@header("Location: index.php?act=agent&op=step2");
					exit;
				} else {
					@header("Location: index.php?act=agent_center");
					exit;
				}	
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('agent_login'));
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
				$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
				if(empty($agent)) {
					@header("Location: index.php?act=agent&op=step2");
					exit;
				} else {
					@header("Location: index.php?act=agent_center");
					exit;
				}	
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('agent_register'));
		}
	}
	
	public function step2Op() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
			$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
			if(!empty($agent)) {
				exit(json_encode(array('done'=>'agent')));
			}
			$agent_name = empty($_POST['agent_name']) ? '' : $_POST['agent_name'];
			$owner_name = empty($_POST['owner_name']) ? '' : $_POST['owner_name'];
			$agent_provinceid = empty($_POST['agent_provinceid']) ? 0 : intval($_POST['agent_provinceid']);
			$agent_cityid = empty($_POST['agent_cityid']) ? 0 : intval($_POST['agent_cityid']);
			$agent_areaid = empty($_POST['agent_areaid']) ? 0 : intval($_POST['agent_areaid']);
			$agent_address = empty($_POST['agent_address']) ? '' : $_POST['agent_address'];
            $agent_location = empty($_POST['agent_location']) ? '' : $_POST['agent_location'];
            $agent_code_image = empty($_POST['agent_code_image']) ? '' : $_POST['agent_code_image'];
            $agent_person_image = empty($_POST['agent_person_image']) ? '' : $_POST['agent_person_image'];
            $agent_person_code_image = empty($_POST['agent_person_code_image']) ? '' : $_POST['agent_person_code_image'];
            $agent_sign_image = empty($_POST['agent_sign_image']) ? '' : $_POST['agent_sign_image'];

            $card_phone=empty($_POST['card_phone']) ? '' : $_POST['card_phone'];
            $phone_code=empty($_POST['phone_code']) ? '' : $_POST['phone_code'];
			if(empty($agent_name)) {
				exit(json_encode(array('id'=>'agent_name', 'msg'=>'机构名称必须填写')));
			}
            if(empty($owner_name)) {
                exit(json_encode(array('id'=>'owner_name', 'msg'=>'法人姓名必须填写')));
            }
			if(empty($agent_provinceid)) {
				exit(json_encode(array('id'=>'agent_provinceid', 'msg'=>'所在地区必须填写')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$agent_cityid'");
			if(empty($district_count)) {
				if(empty($agent_cityid)) {
					exit(json_encode(array('id'=>'agent_provinceid', 'msg'=>'所在地区必须填写')));
				}	
			} else {
				if(empty($agent_areaid)) {
					exit(json_encode(array('id'=>'agent_provinceid', 'msg'=>'所在地区必须填写')));
				}
			}
			if(empty($agent_address)) {
				exit(json_encode(array('id'=>'agent_address', 'msg'=>'详细地址必须填写')));
			}
//            if(empty($agent_phone)) {
//                exit(json_encode(array('id'=>'agent_phone', 'msg'=>'机构号码必须填写')));
//            }
            if(empty($agent_code_image)) {
                exit(json_encode(array('id'=>'agent_code_image', 'msg'=>'营业执照正本必须上传')));
            }
            if(empty($agent_person_image)) {
                exit(json_encode(array('id'=>'agent_person_image', 'msg'=>'法人身份证正面必须上传')));
            }
            if(empty($agent_person_code_image)) {
                exit(json_encode(array('id'=>'agent_person_code_image', 'msg'=>'法人手持营业执照必须上传')));
            }
            if(empty($agent_sign_image)){
                exit(json_encode(array('id'=>'agent_sign_image', 'msg'=>'机构门口照片必须上传')));
            }
            if(empty($card_phone)){
                exit(json_encode(array('id'=>'card_phone', 'msg'=>'预留手机号必须填写')));
            }
            if(empty($phone_code)) {
                exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码必须填写')));
            }
            $code = DB::fetch_first("SELECT * FROM ".DB::table('test_code')." WHERE code_phone='$card_phone' AND code_value='$phone_code'");
            if(empty($code)) {
                exit(json_encode(array('id'=>'phone_code', 'msg'=>'验证码不正确')));
            }
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$agent_provinceid'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$agent_cityid'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$agent_areaid'");
			$agent_areainfo = $province_name.$city_name.$area_name;
			$data = array(
				'member_id' => $this->member_id,
				'member_phone'=>$member['member_phone'],
				'agent_name' => $agent_name,
				'owner_name'=>$owner_name,
				'agent_provinceid' => $agent_provinceid,
				'agent_cityid' => $agent_cityid,
				'agent_areaid' => $agent_areaid,
				'agent_areainfo' => $agent_areainfo,
				'agent_address' => $agent_address,
				'agent_location'=>$agent_location,
//				'agent_phone'=>$agent_phone,
                'agent_score'=>90,
				'agent_code_image' => $agent_code_image,
				'agent_person_image'=>$agent_person_image,
				'agent_person_code_image'=>$agent_person_code_image,
				'agent_sign_image'=>$agent_sign_image,
				'card_phone'=>$card_phone,
				'agent_time' => time(),
			);
//			'agent_qa_image' => empty($agent_qa_image) ? '' : serialize($agent_qa_image),
            $agent_image_path = MALL_ROOT.'/'.$agent_code_image;
            $agent_card_image_code = base64EncodeImage($agent_image_path);
            $verify_data = company_card_verify($agent_card_image_code);

            if($verify_data){
                if(!empty($verify_data['name']) && $verify_data['name'] != $agent_name){
                    exit(json_encode(array('id'=>'system', 'msg'=>'您上传的资料有误，请检查后重试！')));
                }
                if(!empty($verify_data['name']) && $verify_data['name'] != $agent_name){
                    exit(json_encode(array('id'=>'system', 'msg'=>'您上传的资料有误，请检查后重试！')));
                }
            }else{
                exit(json_encode(array('id'=>'system', 'msg'=>'您上传的营业执照信息无法识别，请更换更清晰的图片后重试！')));
            }
            $data['api_result'] = json_encode($verify_data);
			$agent_id = DB::insert('agent', $data, 1);
			if(!empty($agent_id)) {
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			if(empty($this->member_id)) {
				@header("Location: index.php?act=agent&op=register");
				exit;	
			}
			$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
			if(!empty($agent)) {
				@header("Location: index.php?act=agent_center");
				exit;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$curmodule = 'home';
			$bodyclass = '';
			include(template('agent_register_step2'));
		}
	}
	
	public function step3Op() {
		$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
		if(empty($agent)) {
			@header("Location: index.php?act=agent&op=step2");
			exit;
		}
		$curmodule = 'member';
		$bodyclass = '';
		include(template('agent_register_step3'));
	}
}

?>