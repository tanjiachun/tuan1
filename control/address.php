<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class addressControl extends BaseProfileControl {
	public function indexOp() {
		$address_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('address')." WHERE member_id='$this->member_id'");
		$query = DB::query("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' ORDER BY address_id DESC");
		while($value = DB::fetch($query)) {
			$address_list[] = $value;
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('address'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$true_name = empty($_POST['true_name']) ? '' : $_POST['true_name'];
			$mobile_phone = empty($_POST['mobile_phone']) ? '' : $_POST['mobile_phone'];
			$province_id = empty($_POST['province_id']) ? 0 : intval($_POST['province_id']);
			$city_id = empty($_POST['city_id']) ? 0 : intval($_POST['city_id']);
			$area_id = empty($_POST['area_id']) ? 0 : intval($_POST['area_id']);
			$address_info = empty($_POST['address_info']) ? '' : $_POST['address_info'];
			if(empty($true_name)) {
				exit(json_encode(array('msg'=>'请输入联系人')));
			}
			if(empty($mobile_phone)) {
				exit(json_encode(array('msg'=>'请输入电话')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $mobile_phone)) {
				exit(json_encode(array('msg'=>'电话格式不正确')));
			}
			if(empty($province_id) || empty($city_id)) {
				exit(json_encode(array('msg'=>'请选择所在地区')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$city_id'");
			if(!empty($district_count) && empty($area_id)) {
				exit(json_encode(array('msg'=>'请选择所在地区')));
			}
			if(empty($address_info)) {
				exit(json_encode(array('msg'=>'请输入详细地址')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$province_id'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$city_id'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$area_id'");
			$area_info = $province_name.$city_name.$area_name;
			$data = array(
				'member_id' => $this->member_id,
				'true_name' => $true_name,
				'mobile_phone' => $mobile_phone,
				'province_id' => $province_id,
				'city_id' => $city_id,
				'area_id' => $area_id,
				'area_info' => $area_info,
				'address_info' => $address_info,
			);
			$address_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('address')." WHERE member_id='$this->member_id'");
			if(empty($address_count)) {
				$data['address_default'] = 1;
			}
			$address_id = DB::insert('address', $data, 1);
			if(!empty($address_id)) {
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			include(template('address_add'));
		}
	}
	
	public function editOp() {
		if(submitcheck()) {
			$address_id = empty($_POST['address_id']) ? 0 : intval($_POST['address_id']);
			$true_name = empty($_POST['true_name']) ? '' : $_POST['true_name'];
			$mobile_phone = empty($_POST['mobile_phone']) ? '' : $_POST['mobile_phone'];
			$province_id = empty($_POST['province_id']) ? 0 : intval($_POST['province_id']);
			$city_id = empty($_POST['city_id']) ? 0 : intval($_POST['city_id']);
			$area_id = empty($_POST['area_id']) ? 0 : intval($_POST['area_id']);
			$address_info = empty($_POST['address_info']) ? '' : $_POST['address_info'];
			if(empty($true_name)) {
				exit(json_encode(array('msg'=>'请输入联系人')));
			}
			if(empty($mobile_phone)) {
				exit(json_encode(array('msg'=>'请输入电话')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $mobile_phone)) {
				exit(json_encode(array('msg'=>'电话格式不正确')));
			}
			if(empty($province_id) || empty($city_id)) {
				exit(json_encode(array('msg'=>'请选择所在地区')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$city_id'");
			if(!empty($district_count) && empty($area_id)) {
				exit(json_encode(array('msg'=>'请选择所在地区')));
			}
			if(empty($address_info)) {
				exit(json_encode(array('msg'=>'请输入详细地址')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$province_id'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$city_id'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$area_id'");
			$area_info = $province_name.$city_name.$area_name;
			$data = array(
				'true_name' => $true_name,
				'mobile_phone' => $mobile_phone,
				'province_id' => $province_id,
				'city_id' => $city_id,
				'area_id' => $area_id,
				'area_info' => $area_info,
				'address_info' => $address_info,
			);
			DB::update('address', $data, array('address_id'=>$address_id, 'member_id'=>$this->member_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$address_id = empty($_GET['address_id']) ? 0 : intval($_GET['address_id']);
			$address = DB::fetch_first("SELECT * FROM ".DB::table('address')." WHERE address_id='$address_id' AND member_id='$this->member_id'");
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$address['province_id']."' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$city_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$address['city_id']."' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$area_list[] = $value;
			}
			include(template('address_edit'));
		}
	}
	
	public function delOp() {
		if(submitcheck()) {
			$del_id = empty($_POST['del_id']) ? 0 : intval($_POST['del_id']);
			DB::delete('address', array('address_id'=>$del_id, 'member_id'=>$this->member_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function defaultOp() {
		if(submitcheck()) {
			$default_id = empty($_POST['default_id']) ? 0 : intval($_POST['default_id']);
			DB::update('address', array('address_default'=>0), array('member_id'=>$this->member_id));
			DB::update('address', array('address_default'=>1), array('address_id'=>$default_id, 'member_id'=>$this->member_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function addressOp() {
		$query = DB::query("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' ORDER BY address_id DESC");
		while($value = DB::fetch($query)) {
			$address_list[] = $value;
		}
		include(template('address.index'));
	}
}

?>