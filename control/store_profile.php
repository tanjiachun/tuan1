<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_profileControl extends BaseStoreControl {
	public function indexOp() {
		if(submitcheck()) {
			$store_name = empty($_POST['store_name']) ? '' : $_POST['store_name'];
			$store_logo = empty($_POST['store_logo']) ? '' : $_POST['store_logo'];
			$store_banner = empty($_POST['store_banner']) ? '' : $_POST['store_banner'];
			$store_provinceid = empty($_POST['store_provinceid']) ? 0 : intval($_POST['store_provinceid']);
			$store_cityid = empty($_POST['store_cityid']) ? 0 : intval($_POST['store_cityid']);
			$store_areaid = empty($_POST['store_areaid']) ? 0 : intval($_POST['store_areaid']);
			$store_address = empty($_POST['store_address']) ? '' : $_POST['store_address'];
			$store_content = empty($_POST['store_content']) ? '' : $_POST['store_content'];
			$store_qq = empty($_POST['store_qq']) ? '' : $_POST['store_qq'];
			$store_ww = empty($_POST['store_ww']) ? '' : $_POST['store_ww'];
			$store_phone = empty($_POST['store_phone']) ? '' : $_POST['store_phone'];
			if(empty($store_name)) {
				exit(json_encode(array('id'=>'store_name', 'msg'=>'店铺名称必须填写')));
			}
			if(empty($store_provinceid)) {
				exit(json_encode(array('id'=>'store_provinceid', 'msg'=>'请选择所在地区')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$store_cityid'");
			if(empty($district_count)) {
				if(empty($store_cityid)) {
					exit(json_encode(array('id'=>'store_provinceid', 'msg'=>'请选择所在地区')));
				}	
			} else {
				if(empty($store_areaid)) {
					exit(json_encode(array('id'=>'store_provinceid', 'msg'=>'请选择所在地区')));
				}
			}
			if(empty($store_address)) {
				exit(json_encode(array('id'=>'store_address', 'msg'=>'请输入详细地址')));
			}
			if(empty($store_content)) {
				exit(json_encode(array('id'=>'store_content', 'msg'=>'主营业务必须填写')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$store_provinceid'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$store_cityid'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$store_areaid'");
			$store_areainfo = $province_name.$city_name.$area_name;
			$data = array(
				'store_name' => $store_name,
				'store_logo' => $store_logo,
				'store_banner' => $store_banner,
				'store_provinceid' => $store_provinceid,
				'store_cityid' => $store_cityid,
				'store_areaid' => $store_areaid,
				'store_areainfo' => $store_areainfo,
				'store_address' => $store_address,
				'store_content' => $store_content,
				'store_qq' => $store_qq,
				'store_ww' => $store_ww,
				'store_phone' => $store_phone,
			);
			DB::update('store', $data, array('store_id'=>$this->store_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				if($value['district_id'] == $this->store['store_provinceid']) {
					$store_provinceid = $value['district_id'];
					$store_provincename = $value['district_name'];	
				}
				$province_list[] = $value;
			}
			if(!empty($store_provinceid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$store_provinceid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->store['store_cityid']) {
						$store_cityid = $value['district_id'];
						$store_cityname = $value['district_name'];	
					}
					$store_city_list[] = $value;
				}
			}
			if(!empty($store_cityid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$store_cityid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->store['store_areaid']) {
						$store_areaid = $value['district_id'];
						$store_areaname = $value['district_name'];	
					}
					$store_area_list[] = $value;
				}
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_profile'));
		}
	}
}

?>