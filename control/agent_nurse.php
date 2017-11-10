<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_nurseControl extends BaseAgentControl {
	public function indexOp() {
		$type_array = array('1'=>'住家保姆', '2'=>'不住家保姆', '3'=>'病后看护', '4'=>'钟点工');
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=agent_nurse";
		$wheresql = " WHERE agent_id='$this->agent_id'";
		$state = in_array($_GET['state'], array('show', 'pending', 'unshow')) ? $_GET['state'] : 'show';
		if($state == 'show') {
			$mpurl .= '&state=show';
			$wheresql .= " AND nurse_state=1";
		} elseif($state == 'pending') {
			$mpurl .= '&state=pending';
			$wheresql .= " AND nurse_state=0";
		} elseif($state == 'unshow') {
			$mpurl .= '&state=unshow';
			$wheresql .= " AND nurse_state=2";
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$wheresql .= " AND (nurse_name like '%".$search_name."%' OR member_phone like '%".$search_name."%')";				
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." ORDER BY nurse_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$nurse_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('agent_nurse'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
			$nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
			$nurse_phone = empty($_POST['nurse_phone']) ? '' : $_POST['nurse_phone'];
			$nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
            $service_type = empty($_POST['service_type']) ? '' : $_POST['service_type'];
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
			$nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
			$nurse_cardid = empty($_POST['nurse_cardid']) ? '' : $_POST['nurse_cardid'];
			$nurse_cardid_image = empty($_POST['nurse_cardid_image']) ? '' : $_POST['nurse_cardid_image'];
			$nurse_qa_image = empty($_POST['nurse_qa_image']) ? array() : $_POST['nurse_qa_image'];
			$nurse_content = empty($_POST['nurse_content']) ? '' : $_POST['nurse_content'];
			if(empty($member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'请输入手机号')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号格式不正确')));
			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$member_phone'");
			if(!empty($member)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'手机号已经注册了')));	
			}
			if(empty($nurse_name)) {
				exit(json_encode(array('id'=>'nurse_name', 'msg'=>'请输入家政人员姓名')));
			}
			if(empty($nurse_phone)) {
				exit(json_encode(array('id'=>'nurse_phone', 'msg'=>'请输入家政人员手机号')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $nurse_phone)) {
				exit(json_encode(array('id'=>'nurse_phone', 'msg'=>'家政人员手机号格式不正确')));
			}
			if(empty($nurse_type)) {
				exit(json_encode(array('id'=>'nurse_type', 'msg'=>'请选择看护类别')));
			}
            if(empty($service_type)) {
                exit(json_encode(array('id'=>'nurse_type', 'msg'=>'服务类别必须填写')));
            }
			if(empty($nurse_age)) {
				exit(json_encode(array('id'=>'nurse_age', 'msg'=>'请输入您的年龄')));
			}
			if(empty($birth_provinceid)) {
				exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'请选择出生地址')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$birth_cityid'");
			if(empty($district_count)) {
				if(empty($birth_cityid)) {
					exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'请选择出生地址')));
				}
			} else {
				if(empty($birth_areaid)) {
					exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'请选择出生地址')));
				}
			}
			if(empty($nurse_provinceid)) {
				exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'请选择现居地址')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid'");
			if(empty($district_count)) {
				if(empty($nurse_cityid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'请选择现居地址')));
				}
			} else {
				if(empty($nurse_areaid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'请选择现居地址')));
				}
			}
			if(empty($nurse_address)) {
				exit(json_encode(array('id'=>'nurse_address', 'msg'=>'请输入详细地址')));
			}
			if($nurse_education <= 0) {
				exit(json_encode(array('id'=>'nurse_education', 'msg'=>'请输入工作年限')));
			}
			if($nurse_price <= 0) {
				exit(json_encode(array('id'=>'nurse_price', 'msg'=>'请输入月薪')));
			}
			if(empty($nurse_image)) {
				exit(json_encode(array('id'=>'nurse_image', 'msg'=>'请上传个人照片')));
			}
			if(empty($nurse_cardid)) {
				exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'请输入身份证号码')));
			}
			/*
			if(!checkcard($nurse_cardid)) {
				exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'身份证号码格式不正确')));
			}
			*/
			if(empty($nurse_cardid_image)) {
				exit(json_encode(array('id'=>'nurse_cardid_image', 'msg'=>'请上传手持身份证照')));
			}
			if(empty($nurse_content)) {
				exit(json_encode(array('id'=>'nurse_content', 'msg'=>'请输入服务项目')));
			}
			$member_password = substr($member_phone, -6);
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
			$district_ids = array('1', '2', '9', '22', '32', '33', '34');
			$birth_province = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$birth_provinceid'");
			$birth_city = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$birth_cityid'");
			$birth_area = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$birth_areaid'");
			$birth_areainfo = $birth_province['district_name'].$birth_city['district_name'].$birth_area['district_name'];
			if(in_array($birth_provinceid , $district_ids)) {
				$birth_cityname = $birth_province['district_ipname'];
			} else {
				$birth_cityname = $birth_city['district_ipname'];
			}
			$nurse_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
			$nurse_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
			$nurse_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_areaid'");
			$nurse_areainfo = $nurse_provincename.$nurse_cityname.$nurse_areaname;
			$nurse_grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE nurse_score=0");
			$data = array(
				'member_id' => $member_id,				
				'agent_id' => $this->agent_id,
				'nurse_name' => $nurse_name,
				'member_phone' => $nurse_phone,
				'nurse_image' => $nurse_image,
				'nurse_type' => $nurse_type,
                'service_type'=>$service_type,
				'nurse_price' => $nurse_price,
				'nurse_age' => $nurse_age,
				'nurse_education' => $nurse_education,
				'birth_provinceid' => $birth_provinceid,
				'birth_cityid' => $birth_cityid,
				'birth_areaid' => $birth_areaid,
				'birth_areainfo' => $birth_areainfo,
				'birth_cityname' => $birth_cityname,
				'nurse_provinceid' => $nurse_provinceid,
				'nurse_cityid' => $nurse_cityid,
				'nurse_areaid' => $nurse_areaid,
				'nurse_areainfo' => $nurse_areainfo,
				'nurse_cityname' => in_array($nurse_provinceid , $district_ids) ? $nurse_provincename : $nurse_cityname,
				'nurse_areaname' => in_array($nurse_provinceid , $district_ids) ? $nurse_cityname : $nurse_areaname,
				'nurse_address' => $nurse_address,
				'nurse_cardid' => $nurse_cardid,
				'nurse_cardid_image' => $nurse_cardid_image,
				'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
				'nurse_content' => $nurse_content,
				'grade_id' => $nurse_grade['grade_id'],
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
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('agent_nurse_add'));
		}	
	}
	

	
	public function unshowOp() {
		if(submitcheck()) {
			$unshow_ids = empty($_POST['unshow_ids']) ? '' : $_POST['unshow_ids'];
			$unshow_ids = explode(',', $unshow_ids);
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $unshow_ids)."')");
			while($value = DB::fetch($query)) {
				if($value['agent_id'] == $this->agent_id) {
					$nurse_ids[] = $value['nurse_id'];
				}
			}
			if(empty($nurse_ids)) {
				exit(json_encode(array('msg'=>'请至少选择一个家政人员')));	
			}
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_state=2 WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
			exit(json_encode(array('done'=>'true', 'unshow_ids'=>$nurse_ids)));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function showOp() {
		if(submitcheck()) {
			$show_ids = empty($_POST['show_ids']) ? '' : $_POST['show_ids'];
			$show_ids = explode(',', $show_ids);
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $show_ids)."')");
			while($value = DB::fetch($query)) {
				if($value['agent_id'] == $this->agent_id) {
					$nurse_ids[] = $value['nurse_id'];
				}
			}
			if(empty($nurse_ids)) {
				exit(json_encode(array('msg'=>'请至少选择一个家政人员')));	
			}
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_state=1 WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
			exit(json_encode(array('done'=>'true', 'show_ids'=>$nurse_ids)));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
}

?>