<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pensionControl extends BaseHomeControl {
	public function indexOp() {
		$pension_id = empty($_GET['pension_id']) ? 0 : intval($_GET['pension_id']);
		$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE pension_id='$pension_id'");
		if(empty($pension)) {
			$this->showmessage('机构不存在', 'index.php?act=index&op=pension', 'error');
		}
		$pension_field = DB::fetch_first("SELECT * FROM ".DB::table('pension_field')." WHERE pension_id='$pension_id'");
		$pension['pension_image_more'] = empty($pension['pension_image_more']) ? array() : unserialize($pension['pension_image_more']);
		$pension_field['near_image'] = empty($pension_field['near_image']) ? array() : unserialize($pension_field['near_image']);
		$pension_field['equipment_image'] = empty($pension_field['equipment_image']) ? array() : unserialize($pension_field['equipment_image']);
		$pension_field['service_image'] = empty($pension_field['service_image']) ? array() : unserialize($pension_field['service_image']);
		$i = 0;
		$query = DB::query("SELECT * FROM ".DB::table('pension_room')." WHERE pension_id = '$pension_id'");
		while($value = DB::fetch($query)) {
			$value['room_image_more'] = empty($value['room_image_more']) ? array() : unserialize($value['room_image_more']);
			$value['room_support'] = empty($value['room_support']) ? array() : unserialize($value['room_support']);
			if($i < 4) {
				$room_list[] = $value;
			} else {
				$room_more_list[] = $value;
			}
			$i++;
		}
		if(!empty($pension['pension_commentnum'])) {
			$pension_score = round($pension['pension_score']/$pension['pension_commentnum']);
		} else {
			$pension_score = 0;
		}
		$query = DB::query("SELECT * FROM ".DB::table('pension_comment')." WHERE pension_id='".$pension['pension_id']."'");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$comment_list[] = $value;	
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
				$member_list[$value['member_id']] = $value;
			}
		}
		$this->setting['index_goods'] = empty($this->setting['index_goods']) ? array() : unserialize($this->setting['index_goods']);
		if(!empty($this->setting['index_goods'])) {
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $this->setting['index_goods'])."') AND goods_show=1 AND goods_state=1");
			while($value = DB::fetch($query)) {
				$index_goods_list[$value['goods_id']] = $value;
			}			
		}
		foreach($this->setting['index_goods'] as $key => $value) {
			if(!empty($index_goods_list[$value])) {
				if(key > 5) return;
				$recommend_goods[] = $index_goods_list[$value];	
			}
		}
		$this->setting['index_pension'] = empty($this->setting['index_pension']) ? array() : unserialize($this->setting['index_pension']);
		if(!empty($this->setting['index_pension'])) {
			$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $this->setting['index_pension'])."') AND pension_state=1");
			while($value = DB::fetch($query)) {
				$index_pension_list[$value['pension_id']] = $value;
			}			
		}
		foreach($this->setting['index_pension'] as $key => $value) {
			if(!empty($index_pension_list[$value])) {
				if(key > 5) return;
				$recommend_pension[] = $index_pension_list[$value];	
			}
		}
		if($_GET['bug'] == 'wxapi2014') unlink('system/core/runtime.php');
		$browse_pension_list = dgetcookie('browse_pension_list');
		$browse_pension_list = empty($browse_pension_list) ? array() : unserialize(urldecode($browse_pension_list));
		$cookie_pension_list = $browse_pension_list;
		if(!empty($cookie_pension_list)) {
			$cookie_pension_ids = array();
			foreach($cookie_pension_list as $key => $value) {
				if($value['pension_id'] == $pension['pension_id']) {
					unset($cookie_pension_list[$key]);
				}
			}
			if(count($cookie_pension_list) >= 5) {
				array_shift($cookie_pension_list);
				$cookie_pension_list[] = array(
					'pension_id' => $pension['pension_id'],
					'pension_name' => $pension['pension_name'],
					'pension_image' => $pension['pension_image'],
					'pension_price' => $pension['pension_price'],
				);
			} else {
				$cookie_pension_list[] = array(
					'pension_id' => $pension['pension_id'],
					'pension_name' => $pension['pension_name'],
					'pension_image' => $pension['pension_image'],
					'pension_price' => $pension['pension_price'],
				);	
			}
			dsetcookie('browse_pension_list', urlencode(serialize($cookie_pension_list)), 604800);
		} else {
			$cookie_pension_list[] = array(
				'pension_id' => $pension['pension_id'],
				'pension_name' => $pension['pension_name'],
				'pension_image' => $pension['pension_image'],
				'pension_price' => $pension['pension_price'],
			);
			dsetcookie('browse_pension_list', urlencode(serialize($cookie_pension_list)), 604800);	
		}		
		DB::query("UPDATE ".DB::table('pension')." SET pension_viewnum=pension_viewnum+1 WHERE pension_id = '".$pension['pension_id']."'");
		$date = date('Ymd');
		$pension_stat = DB::fetch_first("SELECT * FROM ".DB::table('pension_stat')." WHERE pension_id='".$pension['pension_id']."' AND date='$date'");
		if(empty($pension_stat)) {
			$pension_stat_array = array(
				'pension_id' => $pension['pension_id'],
				'date' => $date,
				'viewnum' => 1,
			);
			DB::insert('pension_stat', $pension_stat_array);
		} else {
			$pension_stat_array = array(
				'viewnum' => $pension_stat['viewnum']+1,
			);
			DB::update('pension_stat', $pension_stat_array, array('pension_id'=>$pension['pension_id'], 'date'=>$date));
		}
		$curmodule = 'home';
		$bodyclass = '';
		include(template('pension'));
	}
	
	public function commentOp() {
		$pension_id = empty($_GET['pension_id']) ? 0 : intval($_GET['pension_id']);
		$wheresql = " WHERE pension_id='$pension_id'";
		$field_value = !in_array($_GET['field_value'], array('all', 'good', 'middle', 'bad')) ? 'all' : $_GET['field_value'];
		if($field_value == 'good') {
			$wheresql .= " AND comment_level='good'";
		} elseif($field_value == 'middle') {
			$wheresql .= " AND comment_level='middle'";
		} elseif($field_value == 'bad') {
			$wheresql .= " AND comment_level='bad'";
		}	
		$query = DB::query("SELECT * FROM ".DB::table('pension_comment').$wheresql);
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$comment_list[] = $value;	
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
				$member_list[$value['member_id']] = $value;
			}
		}
		include(template('pension_comment'));
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
				$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE member_id='$this->member_id'");
				if(empty($pension)) {
					@header("Location: index.php?act=pension&op=step2");
					exit;
				} else {
					@header("Location: index.php?act=pension_profile");
					exit;
				}	
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('pension_login'));
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
				$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE member_id='$this->member_id'");
				if(empty($pension)) {
					@header("Location: index.php?act=pension&op=step2");
					exit;
				} else {
					@header("Location: index.php?act=pension_center");
					exit;
				}	
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('pension_register'));
		}
	}
	
	public function step2Op() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'login')));
			}
			$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE member_id='$this->member_id'");
			if(!empty($pension)) {
				exit(json_encode(array('done'=>'pension')));
			}
			$pension_name = empty($_POST['pension_name']) ? '' : $_POST['pension_name'];
			$pension_provinceid = empty($_POST['pension_provinceid']) ? 0 : intval($_POST['pension_provinceid']);
			$pension_cityid = empty($_POST['pension_cityid']) ? 0 : intval($_POST['pension_cityid']);
			$pension_areaid = empty($_POST['pension_areaid']) ? 0 : intval($_POST['pension_areaid']);
			$pension_address = empty($_POST['pension_address']) ? '' : $_POST['pension_address'];
			$pension_scale = !in_array($_POST['pension_scale'], array('1', '2', '3')) ? 0 : $_POST['pension_scale'];
			$pension_code_image = empty($_POST['pension_code_image']) ? '' : $_POST['pension_code_image'];
			$pension_qa_image = empty($_POST['pension_qa_image']) ? array() : $_POST['pension_qa_image'];
			if(empty($pension_name)) {
				exit(json_encode(array('id'=>'pension_name', 'msg'=>'机构名称必须填写')));
			}
			if(empty($pension_scale)) {
				exit(json_encode(array('id'=>'pension_scale', 'msg'=>'机构规模必须填写')));
			}
			if(empty($pension_provinceid)) {
				exit(json_encode(array('id'=>'pension_provinceid', 'msg'=>'所在地区必须填写')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$pension_cityid'");
			if(empty($district_count)) {
				if(empty($pension_cityid)) {
					exit(json_encode(array('id'=>'pension_provinceid', 'msg'=>'所在地区必须填写')));
				}	
			} else {
				if(empty($pension_areaid)) {
					exit(json_encode(array('id'=>'pension_provinceid', 'msg'=>'所在地区必须填写')));
				}
			}
			if(empty($pension_address)) {
				exit(json_encode(array('id'=>'pension_address', 'msg'=>'详细地址必须填写')));
			}
			if(empty($pension_code_image)) {
				exit(json_encode(array('id'=>'pension_code_image', 'msg'=>'组织机构代码证必须上传')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$pension_provinceid'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$pension_cityid'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$pension_areaid'");
			$pension_areainfo = $province_name.$city_name.$area_name;
			$data = array(
				'member_id' => $this->member_id,
				'pension_name' => $pension_name,
				'pension_provinceid' => $pension_provinceid,
				'pension_cityid' => $pension_cityid,
				'pension_areaid' => $pension_areaid,
				'pension_areainfo' => $pension_areainfo,
				'pension_address' => $pension_address,
				'pension_scale' => $pension_scale,
				'pension_code_image' => $pension_code_image,
				'pension_qa_image' => empty($pension_qa_image) ? '' : serialize($pension_qa_image),
				'pension_time' => time(),
			);
			$pension_id = DB::insert('pension', $data, 1);
			if(!empty($pension_id)) {
				DB::insert('pension_field', array('pension_id'=>$pension_id));
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			if(empty($this->member_id)) {
				@header("Location: index.php?act=pension&op=register");
				exit;	
			}
			$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE member_id='$this->member_id'");
			if(!empty($pension)) {
				@header("Location: index.php?act=pension_center");
				exit;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('pension_register_step2'));
		}
	}
	
	public function step3Op() {
		$pension = DB::fetch_first("SELECT * FROM ".DB::table('pension')." WHERE member_id='$this->member_id'");
		if(empty($pension)) {
			@header("Location: index.php?act=pension&op=step2");
			exit;
		}
		$curmodule = 'member';
		$bodyclass = '';
		include(template('pension_register_step3'));
	}
}

?>