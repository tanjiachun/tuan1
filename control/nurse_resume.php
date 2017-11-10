<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_resumeControl extends BaseProfileControl {
	public function indexOp() {
		if(submitcheck()) {
			if(empty($this->nurse_id)) {
				exit(json_encode(array('done'=>'nurse')));
			}
			$nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
			$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
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
			if(empty($nurse_name)) {
				exit(json_encode(array('id'=>'nurse_name', 'msg'=>'请输入您的姓名')));
			}
			if(empty($member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'请输入您的手机号')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'您的手机号格式不正确')));
			}
			if(empty($nurse_type)) {
				exit(json_encode(array('id'=>'nurse_type', 'msg'=>'请选择看护类别')));
			}
            if(empty($service_type)) {
                exit(json_encode(array('id'=>'service_type', 'msg'=>'服务类别必须填写')));
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
				exit(json_encode(array('id'=>'nurse_price', 'msg'=>'请输入期望薪资')));
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
			$data = array(
				'nurse_name' => $nurse_name,
				'member_phone' => $member_phone,
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
				'nurse_state' => 0,
			);
			DB::update('nurse', $data, array('nurse_id'=>$this->nurse['nurse_id']));
			exit(json_encode(array('done'=>'true')));
		} else {
			if(empty($this->nurse_id)) {
				$this->showmessage('您还没注册成为家政人员', 'index.php?act=register&next_step=nurse', 'info');
			}
            $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
			$this->nurse['nurse_qa'] = empty($this->nurse['nurse_qa']) ? array() : unserialize($this->nurse['nurse_qa']);
			$this->nurse['nurse_qa_image'] = empty($this->nurse['nurse_qa_image']) ? array() : unserialize($this->nurse['nurse_qa_image']);
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				if($value['district_id'] == $this->nurse['nurse_provinceid']) {
					$nurse_provinceid = $value['district_id'];
					$nurse_provincename = $value['district_name'];
				}
				if($value['district_id'] == $this->nurse['birth_provinceid']) {
					$birth_provinceid = $value['district_id'];
					$birth_provincename = $value['district_name'];
				}
				$province_list[] = $value;
			}
			if(!empty($nurse_provinceid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$nurse_provinceid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->nurse['nurse_cityid']) {
						$nurse_cityid = $value['district_id'];
						$nurse_cityname = $value['district_name'];	
					}
					$nurse_city_list[] = $value;
				}
			}
			if(!empty($birth_provinceid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$birth_provinceid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->nurse['birth_cityid']) {
						$birth_cityid = $value['district_id'];
						$birth_cityname = $value['district_name'];	
					}
					$birth_city_list[] = $value;
				}
			}
			if(!empty($nurse_cityid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->nurse['nurse_areaid']) {
						$nurse_areaid = $value['district_id'];
						$nurse_areaname = $value['district_name'];	
					}
					$nurse_area_list[] = $value;
				}
			}
			if(!empty($birth_cityid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$birth_cityid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->nurse['birth_areaid']) {
						$birth_areaid = $value['district_id'];
						$birth_areaname = $value['district_name'];	
					}
					$birth_area_list[] = $value;
				}
			}
			$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$this->nurse['agent_id']."'");
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('nurse_resume'));
		}	
	}
}

?>