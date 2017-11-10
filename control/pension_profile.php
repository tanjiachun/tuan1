<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pension_profileControl extends BasePensionControl {
	public function indexOp() {
		if(submitcheck()) {
			$pension_name = empty($_POST['pension_name']) ? '' : $_POST['pension_name'];
			$pension_logo = empty($_POST['pension_logo']) ? '' : $_POST['pension_logo'];
			$pension_image = empty($_POST['pension_image']) ? '' : $_POST['pension_image'];
			$pension_image_more = empty($_POST['pension_image_more']) ? array() : $_POST['pension_image_more'];
			$pension_type = empty($_POST['pension_type']) ? 0 : intval($_POST['pension_type']);
			$pension_nature = empty($_POST['pension_nature']) ? 0 : intval($_POST['pension_nature']);
			$pension_person = empty($_POST['pension_person']) ? 0 : intval($_POST['pension_person']);
			$pension_bed = empty($_POST['pension_bed']) ? '' : $_POST['pension_bed'];
			$pension_cover = empty($_POST['pension_cover']) ? '' : $_POST['pension_cover'];
			$pension_price = empty($_POST['pension_price']) ? '' : $_POST['pension_price'];
			$pension_phone = empty($_POST['pension_phone']) ? '' : $_POST['pension_phone'];
			$pension_provinceid = empty($_POST['pension_provinceid']) ? 0 : intval($_POST['pension_provinceid']);
			$pension_cityid = empty($_POST['pension_cityid']) ? 0 : intval($_POST['pension_cityid']);
			$pension_areaid = empty($_POST['pension_areaid']) ? 0 : intval($_POST['pension_areaid']);
			$pension_address = empty($_POST['pension_address']) ? '' : $_POST['pension_address'];
			$pension_address = empty($_POST['pension_address']) ? '' : $_POST['pension_address'];
			$pension_summary = empty($_POST['pension_summary']) ? '' : $_POST['pension_summary'];
			if(empty($pension_name)) {
				exit(json_encode(array('id'=>'pension_name', 'msg'=>'请输入你的机构名称')));
			}
			if(empty($pension_image)) {
				exit(json_encode(array('id'=>'pension_image', 'msg'=>'请上传机构封面')));
			}
			if(empty($pension_type)) {
				exit(json_encode(array('id'=>'pension_type', 'msg'=>'请选择机构类型')));
			}
			if(empty($pension_nature)) {
				exit(json_encode(array('id'=>'pension_nature', 'msg'=>'请选择机构性质')));
			}
			if(empty($pension_person)) {
				exit(json_encode(array('id'=>'pension_person', 'msg'=>'请选择收住对象')));
			}
			if(empty($pension_bed)) {
				exit(json_encode(array('id'=>'pension_bed', 'msg'=>'请输入床位数量')));
			}
			if(empty($pension_cover)) {
				exit(json_encode(array('id'=>'pension_cover', 'msg'=>'请输入占地面积')));
			}
			if(empty($pension_price)) {
				exit(json_encode(array('id'=>'pension_price', 'msg'=>'请输入收费标准')));
			}
			if(empty($pension_phone)) {
				exit(json_encode(array('id'=>'pension_phone', 'msg'=>'请输入联系电话')));
			}
			if(empty($pension_provinceid)) {
				exit(json_encode(array('id'=>'pension_provinceid', 'msg'=>'请选择所在地区')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$pension_cityid'");
			if(empty($district_count)) {
				if(empty($pension_cityid)) {
					exit(json_encode(array('id'=>'pension_provinceid', 'msg'=>'请选择所在地区')));
				}	
			} else {
				if(empty($pension_areaid)) {
					exit(json_encode(array('id'=>'pension_provinceid', 'msg'=>'请选择所在地区')));
				}
			}
			if(empty($pension_address)) {
				exit(json_encode(array('id'=>'pension_address', 'msg'=>'请输入详细地址')));
			}
			if(empty($pension_summary)) {
				exit(json_encode(array('id'=>'pension_summary', 'msg'=>'请输入机构概述')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$pension_provinceid'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$pension_cityid'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$pension_areaid'");
			$pension_areainfo = $province_name.$city_name.$area_name;
			$data = array(
				'pension_name' => $pension_name,
				'pension_logo' => $pension_logo,
				'pension_image' => $pension_image,
				'pension_image_more' => empty($pension_image_more) ? '' : serialize($pension_image_more),
				'pension_type' => $pension_type,
				'pension_nature' => $pension_nature,
				'pension_person' => $pension_person,
				'pension_bed' => $pension_bed,
				'pension_cover' => $pension_cover,
				'pension_price' => $pension_price,
				'pension_phone' => $pension_phone,
				'pension_provinceid' => $pension_provinceid,
				'pension_cityid' => $pension_cityid,
				'pension_areaid' => $pension_areaid,
				'pension_areainfo' => $pension_areainfo,
				'pension_address' => $pension_address,
				'pension_summary' => $pension_summary,
			);
			DB::update('pension', $data, array('pension_id'=>$this->pension_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$type_array = array('1'=>'养老产业园', '2'=>'老年公寓', '3'=>'护理院', '4'=>'托老所', '5'=>'养老院', '6'=>'敬老院');
			$nature_array = array('1'=>'民办', '2'=>'公办');
			$person_array = array('1'=>'自理', '2'=>'半自理', '3'=>'全自理', '4'=>'特护');
			$this->pension['pension_type'] = empty($this->pension['pension_type']) ? '' : $this->pension['pension_type'];
			$this->pension['pension_nature'] = empty($this->pension['pension_nature']) ? '' : $this->pension['pension_nature'];
			$this->pension['pension_person'] = empty($this->pension['pension_person']) ? '' : $this->pension['pension_person'];
			$this->pension['pension_bed'] = empty($this->pension['pension_bed']) ? '' : $this->pension['pension_bed'];
			$this->pension['pension_cover'] = empty($this->pension['pension_cover']) ? '' : $this->pension['pension_cover'];
			$this->pension['pension_price'] = empty($this->pension['pension_price']) ? '' : $this->pension['pension_price'];
			$this->pension['pension_image_more'] = empty($this->pension['pension_image_more']) ? array() : unserialize($this->pension['pension_image_more']);
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				if($value['district_id'] == $this->pension['pension_provinceid']) {
					$pension_provinceid = $value['district_id'];
					$pension_provincename = $value['district_name'];	
				}
				$pension_province_list[] = $value;
			}
			if(!empty($pension_provinceid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$pension_provinceid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->pension['pension_cityid']) {
						$pension_cityid = $value['district_id'];
						$pension_cityname = $value['district_name'];	
					}
					$pension_city_list[] = $value;
				}
			}
			if(!empty($pension_cityid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$pension_cityid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->pension['pension_areaid']) {
						$pension_areaid = $value['district_id'];
						$pension_areaname = $value['district_name'];	
					}
					$pension_area_list[] = $value;
				}
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('pension_profile'));
		}	
	}
	
	public function nearOp() {
		if(submitcheck()) {
			$near_image = empty($_POST['near_image']) ? array() : $_POST['near_image'];
			$near_content = empty($_POST['near_content']) ? '' : $_POST['near_content'];
			$data = array(
				'near_image' => empty($near_image) ? '' : serialize($near_image),
				'near_content' => $near_content,
			);
			DB::update('pension_field', $data, array('pension_id'=>$this->pension_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$pension_field = DB::fetch_first("SELECT * FROM ".DB::table('pension_field')." WHERE pension_id='$this->pension_id'");
			$pension_field['near_image'] = empty($pension_field['near_image']) ? array() : unserialize($pension_field['near_image']);
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('pension_near'));
		}
	}
	
	public function equipmentOp() {
		if(submitcheck()) {
			$equipment_image = empty($_POST['equipment_image']) ? array() : $_POST['equipment_image'];
			$equipment_content = empty($_POST['equipment_content']) ? '' : $_POST['equipment_content'];
			$data = array(
				'equipment_image' => empty($equipment_image) ? '' : serialize($equipment_image),
				'equipment_content' => $equipment_content,
			);
			DB::update('pension_field', $data, array('pension_id'=>$this->pension_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$pension_field = DB::fetch_first("SELECT * FROM ".DB::table('pension_field')." WHERE pension_id='$this->pension_id'");
			$pension_field['equipment_image'] = empty($pension_field['equipment_image']) ? array() : unserialize($pension_field['equipment_image']);
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('pension_equipment'));
		}
	}
	
	public function serviceOp() {
		if(submitcheck()) {
			$service_image = empty($_POST['service_image']) ? array() : $_POST['service_image'];
			$service_content = empty($_POST['service_content']) ? '' : $_POST['service_content'];
			$data = array(
				'service_image' => empty($service_image) ? '' : serialize($service_image),
				'service_content' => $service_content,
			);
			DB::update('pension_field', $data, array('pension_id'=>$this->pension_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$pension_field = DB::fetch_first("SELECT * FROM ".DB::table('pension_field')." WHERE pension_id='$this->pension_id'");
			$pension_field['service_image'] = empty($pension_field['service_image']) ? array() : unserialize($pension_field['service_image']);
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('pension_service'));
		}
	}
}

?>