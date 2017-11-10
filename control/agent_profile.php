<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_profileControl extends BaseAgentControl {
	public function indexOp() {
		if(submitcheck()) {
			$agent_name = empty($_POST['agent_name']) ? '' : $_POST['agent_name'];
			$agent_logo = empty($_POST['agent_logo']) ? '' : $_POST['agent_logo'];
			$agent_banner = empty($_POST['agent_banner']) ? '' : $_POST['agent_banner'];
			$agent_qq = empty($_POST['agent_qq']) ? '' : $_POST['agent_qq'];
			$agent_phone = empty($_POST['agent_phone']) ? '' : $_POST['agent_phone'];
			$agent_provinceid = empty($_POST['agent_provinceid']) ? 0 : intval($_POST['agent_provinceid']);
			$agent_cityid = empty($_POST['agent_cityid']) ? 0 : intval($_POST['agent_cityid']);
			$agent_areaid = empty($_POST['agent_areaid']) ? 0 : intval($_POST['agent_areaid']);
			$agent_address = empty($_POST['agent_address']) ? '' : $_POST['agent_address'];
			$agent_content = empty($_POST['agent_content']) ? '' : $_POST['agent_content'];
			if(empty($agent_name)) {
				exit(json_encode(array('id'=>'agent_name', 'msg'=>'请输入你的机构名称')));
			}
			if(empty($agent_qq)) {
				exit(json_encode(array('id'=>'agent_qq', 'msg'=>'输入你的客服QQ')));
			}
			if(empty($agent_provinceid)) {
				exit(json_encode(array('id'=>'agent_provinceid', 'msg'=>'请选择所在地区')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$agent_cityid'");
			if(empty($district_count)) {
				if(empty($agent_cityid)) {
					exit(json_encode(array('id'=>'agent_provinceid', 'msg'=>'请选择所在地区')));
				}	
			} else {
				if(empty($agent_areaid)) {
					exit(json_encode(array('id'=>'agent_provinceid', 'msg'=>'请选择所在地区')));
				}
			}
			if(empty($agent_address)) {
				exit(json_encode(array('id'=>'agent_address', 'msg'=>'请输入详细地址')));
			}
			if(empty($agent_content)) {
				exit(json_encode(array('id'=>'agent_content', 'msg'=>'请输入服务描述')));
			}
			$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$agent_provinceid'");
			$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$agent_cityid'");
			$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$agent_areaid'");
			$agent_areainfo = $province_name.$city_name.$area_name;
			$data = array(
				'agent_name' => $agent_name,
				'agent_logo' => $agent_logo,
				'agent_banner' => $agent_banner,
				'agent_qq' => $agent_qq,
				'agent_phone' => $agent_phone,
				'agent_provinceid' => $agent_provinceid,
				'agent_cityid' => $agent_cityid,
				'agent_areaid' => $agent_areaid,
				'agent_areainfo' => $agent_areainfo,
				'agent_address' => $agent_address,
				'agent_content' => $agent_content,
			);
			DB::update('agent', $data, array('agent_id'=>$this->agent_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				if($value['district_id'] == $this->agent['agent_provinceid']) {
					$agent_provinceid = $value['district_id'];
					$agent_provincename = $value['district_name'];	
				}
				$province_list[] = $value;
			}
			if(!empty($agent_provinceid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$agent_provinceid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->agent['agent_cityid']) {
						$agent_cityid = $value['district_id'];
						$agent_cityname = $value['district_name'];	
					}
					$agent_city_list[] = $value;
				}
			}
			if(!empty($agent_cityid)) {
				$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$agent_cityid' ORDER BY district_sort ASC");
				while($value = DB::fetch($query)) {
					if($value['district_id'] == $this->agent['agent_areaid']) {
						$agent_areaid = $value['district_id'];
						$agent_areaname = $value['district_name'];	
					}
					$agent_area_list[] = $value;
				}
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('agent_profile'));
		}
	}
}

?>