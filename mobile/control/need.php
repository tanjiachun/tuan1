<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class needControl extends BaseMobileControl {
	public function indexOp() {
		$need_name = empty($_POST['need_name']) ? '' : $_POST['need_name'];
		$need_provinceid = empty($_POST['need_provinceid']) ? 0 : intval($_POST['need_provinceid']);
		$need_cityid = empty($_POST['need_cityid']) ? 0 : intval($_POST['need_cityid']);
		$need_areaid = empty($_POST['need_areaid']) ? 0 : intval($_POST['need_areaid']);
		$need_address = empty($_POST['need_address']) ? '' : $_POST['need_address'];
		$nurse_age = empty($_POST['nurse_age']) ? '' : $_POST['nurse_age'];
		$nurse_education = empty($_POST['nurse_education']) ? '' : $_POST['nurse_education'];
		$nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
		$need_content = empty($_POST['need_content']) ? '' : $_POST['need_content'];
		if(empty($need_name)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的称呼', 'data'=>array())));
		}
		if(empty($need_provinceid)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的地点', 'data'=>array())));
		}
		$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$need_cityid'");
		if(empty($district_count)) {
			if(empty($need_cityid)) {
				exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的地点', 'data'=>array())));
			}	
		} else {
			if(empty($need_areaid)) {
				exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的地点', 'data'=>array())));
			}
		}
		if(empty($need_address)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的地点', 'data'=>array())));
		}
		if(empty($nurse_age)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择年龄', 'data'=>array())));
		}
		if(empty($nurse_education)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择工作经验', 'data'=>array())));
		}
		
		if(empty($nurse_type)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择服务类型', 'data'=>array())));
		}
		if(empty($need_content)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的需求', 'data'=>array())));
		}
		$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$need_provinceid'");
		$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$need_cityid'");
		$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$need_areaid'");
		$need_areainfo = $province_name.$city_name.$area_name;
		$data = array(
			'member_id' => $this->member_id,
			'member_phone' => $this->member['member_phone'],
			'need_name' => $need_name,
			'need_provinceid' => $need_provinceid,
			'need_cityid' => $need_cityid,
			'need_areaid' => $need_areaid,
			'need_areainfo' => $need_areainfo,
			'need_address' => $need_address,
			'nurse_age' => $nurse_age,
			'nurse_education' => $nurse_education,
			'nurse_type' => $nurse_type,
			'need_content' => $need_content,
			'add_time' => time(),
		);
		$need_id = DB::insert('nurse_need', $data, 1);
		if(!empty($need_id)) {
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}
	}
}

?>