<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurseControl extends BaseHomeControl {
	public function indexOp() {
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		if(empty($nurse)) {
			$this->showmessage('阿姨不存在', 'index.php?act=index&op=nurse', 'error');
		}
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
		$nurse['nurse_qa'] = empty($nurse['nurse_qa']) ? array() : unserialize($nurse['nurse_qa']);
		$nurse['nurse_qa_image'] = empty($nurse['nurse_qa_image']) ? array() : unserialize($nurse['nurse_qa_image']);
		$nurse['nurse_desc'] = nl2br($nurse['nurse_desc']);
		$nurse['nurse_demand'] = nl2br($nurse['nurse_demand']);
		$nurse['nurse_content'] = nl2br($nurse['nurse_content']);
		$grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE grade_id='".$nurse['grade_id']."'");
		$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$nurse['agent_id']."'");
		$level_array = array('good'=>'好评 ', 'middle'=>'中评', 'bad'=>'差评');
		$query = DB::query("SELECT * FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."'");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
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
		DB::query("UPDATE ".DB::table('nurse')." SET nurse_viewnum=nurse_viewnum+1 WHERE nurse_id='".$nurse['nurse_id']."'");
		$date = date('Ymd');
		$nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='".$nurse['nurse_id']."' AND date='$date'");
		if(empty($nurse_stat)) {
			$nurse_stat_array = array(
				'nurse_id' => $nurse['nurse_id'],
				'date' => $date,
				'viewnum' => 1,
			);
			DB::insert('nurse_stat', $nurse_stat_array);
		} else {
			$nurse_stat_array = array(
				'viewnum' => $nurse_stat['viewnum']+1,
			);
			DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$nurse['nurse_id'], 'date'=>$date));
		}
		$curmodule = 'home';
		$bodyclass = '';
		include(template('nurse'));
	}
	
	public function commentOp() {
		$level_array = array('good'=>'好评 ', 'middle'=>'中评', 'bad'=>'差评');
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$wheresql = " WHERE nurse_id='$nurse_id'";
		$field_value = !in_array($_GET['field_value'], array('all', 'good', 'middle', 'bad')) ? 'all' : $_GET['field_value'];
		if($field_value == 'good') {
			$wheresql .= " AND comment_level='good'";
		} elseif($field_value == 'middle') {
			$wheresql .= " AND comment_level='middle'";
		} elseif($field_value == 'bad') {
			$wheresql .= " AND comment_level='bad'";
		}		
		$query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql);
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
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
		include(template('nurse_comment'));
	}
	
	public function favoriteOp() {
		if(empty($this->member)) {
			exit(json_encode(array('done'=>'login')));
		}
		$fav_id = empty($_GET['fav_id']) ? 0 : intval($_GET['fav_id']);
		if(empty($fav_id)) {
			exit(json_encode(array('msg'=>'阿姨不存在')));
		}
		$fav = DB::fetch_first("SELECT * FROM ".DB::table('favorite')." WHERE member_id='$this->member_id' AND fav_id='$fav_id' AND fav_type='nurse'");
		if(empty($fav)) {
			$data = array(
				'member_id' => $this->member_id,
				'fav_id' => $fav_id,
				'fav_type' => 'nurse',
				'fav_time' => time(),
			);
			DB::insert('favorite', $data);
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_favoritenum=nurse_favoritenum+1 WHERE nurse_id='$fav_id'");
		} else {
			exit(json_encode(array('msg'=>'您已经收藏了')));	
		}
		exit(json_encode(array('done'=>'true')));
	}
	
	public function registerOp() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'register')));
			}
			if(!empty($this->nurse_id)) {
				exit(json_encode(array('done'=>'nurse')));
			}
			$from_phone = empty($_POST['from_phone']) ? '' : $_POST['from_phone'];
			$nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
			$nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
			$nurse_age = empty($_POST['nurse_age']) ? 0 : intval($_POST['nurse_age']);
			$birth_provinceid = empty($_POST['birth_provinceid']) ? 0 : intval($_POST['birth_provinceid']);
			$birth_cityid = empty($_POST['birth_cityid']) ? 0 : intval($_POST['birth_cityid']);
			$birth_areaid = empty($_POST['birth_areaid']) ? 0 : intval($_POST['birth_areaid']);
			$nurse_provinceid = empty($_POST['nurse_provinceid']) ? 0 : intval($_POST['nurse_provinceid']);
			$nurse_cityid = empty($_POST['nurse_cityid']) ? 0 : intval($_POST['nurse_cityid']);
			$nurse_areaid = empty($_POST['nurse_areaid']) ? 0 : intval($_POST['nurse_areaid']);
			$nurse_address = empty($_POST['nurse_address']) ? '' : $_POST['nurse_address'];
			$nurse_education = empty($_POST['nurse_education']) ? 0 : intval($_POST['nurse_education']);
			$nurse_price = empty($_POST['nurse_price']) ? 0 : intval($_POST['nurse_price']);
			$nurse_days = empty($_POST['nurse_days']) ? 0 : intval($_POST['nurse_days']);
			$nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
			$nurse_cardid = empty($_POST['nurse_cardid']) ? '' : $_POST['nurse_cardid'];
			$nurse_cardid_image = empty($_POST['nurse_cardid_image']) ? '' : $_POST['nurse_cardid_image'];
			$nurse_qa = empty($_POST['nurse_qa']) ? array() : $_POST['nurse_qa'];
			$nurse_qa_image = empty($_POST['nurse_qa_image']) ? array() : $_POST['nurse_qa_image'];
			$nurse_demand = empty($_POST['nurse_demand']) ? '' : $_POST['nurse_demand'];
			$nurse_content = empty($_POST['nurse_content']) ? '' : $_POST['nurse_content'];
			$nurse_desc = empty($_POST['nurse_desc']) ? '' : $_POST['nurse_desc'];
			if(!empty($from_phone)) {
				if(!preg_match('/^1[0-9]{10}$/', $from_phone)) {
					exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号格式不正确')));
				}
				if($from_phone == $this->member['member_phone']) {
					exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号不能是自己的')));
				}
				$from_member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$from_phone'");
				if(empty($from_member)) {
					exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号不存在')));	
				}
				$from_nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='".$from_member['member_id']."'");
				if(empty($from_nurse)) {
					exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人还不是阿姨看护')));	
				}
			}
			if(empty($nurse_name)) {
				exit(json_encode(array('id'=>'nurse_name', 'msg'=>'您的姓名必须填写')));
			}
			if(empty($nurse_type)) {
				exit(json_encode(array('id'=>'nurse_type', 'msg'=>'看护类别必须填写')));
			}
			if(empty($nurse_age)) {
				exit(json_encode(array('id'=>'nurse_age', 'msg'=>'您的年龄必须填写')));
			}
			if(empty($birth_provinceid)) {
				exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$birth_cityid'");
			if(empty($district_count)) {
				if(empty($birth_cityid)) {
					exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
				}
				$birth_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$birth_provinceid'");
			} else {
				if(empty($birth_areaid)) {
					exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
				}
				$birth_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$birth_cityid'");
			}
			if(empty($nurse_provinceid)) {
				exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid'");
			if(empty($district_count)) {
				if(empty($nurse_cityid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
				}
				$nurse_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
			} else {
				if(empty($nurse_areaid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
				}
				$nurse_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
			}			
			if(empty($nurse_address)) {
				exit(json_encode(array('id'=>'nurse_address', 'msg'=>'详细地址必须填写')));
			}
			if(empty($nurse_education)) {
				exit(json_encode(array('id'=>'nurse_education', 'msg'=>'工作年限必须填写')));
			}
			if($nurse_price <= 0) {
				exit(json_encode(array('id'=>'nurse_price', 'msg'=>'月薪必须填写')));
			}
			if($nurse_days <= 0) {
				exit(json_encode(array('id'=>'nurse_days', 'msg'=>'每月工作天数必须填写')));
			}
			if(empty($nurse_image)) {
				exit(json_encode(array('id'=>'nurse_image', 'msg'=>'个人照片必须上传')));
			}
			if(empty($nurse_cardid)) {
				exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'身份证号码必须填写')));
			}
			if(empty($nurse_cardid_image)) {
				exit(json_encode(array('id'=>'nurse_cardid_image', 'msg'=>'手持身份证照必须上传')));
			}
			if(empty($nurse_content)) {
				exit(json_encode(array('id'=>'nurse_content', 'msg'=>'服务项目必须填写')));
			}
			$birth_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$birth_provinceid'");
			$birth_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$birth_cityid'");
			$birth_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$birth_areaid'");
			$birth_areainfo = $birth_provincename.$birth_cityname.$birth_areaname;
			$nurse_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
			$nurse_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
			$nurse_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_areaid'");
			$nurse_areainfo = $nurse_provincename.$nurse_cityname.$nurse_areaname;
			$agent = DB::fetch_first("SELECT * FROM ".DB::table("agent")." WHERE is_own=1");
			$nurse_grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE nurse_score=0");
			$data = array(
				'member_id' => $this->member_id,
				'member_phone' => $this->member['member_phone'],
				'agent_id' => $agent['agent_id'],
				'nurse_name' => $nurse_name,
				'nurse_image' => $nurse_image,
				'nurse_type' => $nurse_type,
				'nurse_price' => $nurse_price,
				'nurse_days' => $nurse_days,
				'nurse_age' => $nurse_age,
				'nurse_education' => $nurse_education,
				'birth_provinceid' => $birth_provinceid,
				'birth_cityid' => $birth_cityid,
				'birth_areaid' => $birth_areaid,
				'birth_areainfo' => $birth_areainfo,
				'birth_cityname' => $birth_city,
				'nurse_provinceid' => $nurse_provinceid,
				'nurse_cityid' => $nurse_cityid,
				'nurse_areaid' => $nurse_areaid,
				'nurse_areainfo' => $nurse_areainfo,
				'nurse_cityname' => $nurse_city,
				'nurse_address' => $nurse_address,
				'nurse_cardid' => $nurse_cardid,
				'nurse_cardid_image' => $nurse_cardid_image,
				'nurse_qa' => empty($nurse_qa) ? '' : serialize($nurse_qa),
				'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
				'nurse_demand' => $nurse_demand,
				'nurse_content' => $nurse_content,
				'nurse_desc' => $nurse_desc,
				'grade_id' => $nurse_grade['grade_id'],
				'from_memberid' => $from_member['member_id'],
				'nurse_state' => 0,
				'nurse_time' => time(),
			);
			$nurse_id = DB::insert('nurse', $data, 1);
			if(!empty($nurse_id)) {
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			if(empty($this->member_id)) {
				@header("Location: index.php?act=register&next_step=nurse");
				exit;	
			}
			if(!empty($this->nurse_id)) {
				@header("Location: index.php?act=nurse_center");
				exit;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('nurse_register'));
		}
	}
	
	public function step2Op() {
		if(empty($this->nurse_id)) {				
			@header('Location: index.php?act=nurse&op=register');
			exit;
		}
		$curmodule = 'member';
		$bodyclass = '';
		include(template('nurse_register_step2'));
	}
	
	public function checknameOp() {
		$member_phone = empty($_GET['member_phone']) ? '' : $_GET['member_phone'];
		if(!empty($member_phone)) {
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('msg'=>'推荐人手机号格式不正确')));
			}
			if($member_phone == $this->member['member_phone']) {
				exit(json_encode(array('msg'=>'推荐人手机号不能是自己的')));
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
			if(empty($member)) {
				exit(json_encode(array('msg'=>'推荐人手机号不存在')));	
			}
			$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='".$member['member_id']."'");
			if(empty($nurse)) {
				exit(json_encode(array('msg'=>'推荐人还不是阿姨看护')));	
			}
		}
		exit(json_encode(array('done'=>'true')));
	}
}

?>