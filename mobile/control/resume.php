<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class resumeControl extends BaseMobileControl {
	public function indexOp() {
		$error_data = (object)array();
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
		if(empty($nurse)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'暂无简历', 'data'=>$error_data)));
		} else {
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$nurse)));	
		}
	}
	
	public function saveOp() {
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
		if(empty($nurse)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请先申请为阿姨看护', 'data'=>array())));	
		}
		$nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
		$nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		$nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
		$nurse_age = empty($_POST['nurse_age']) ? 0 : intval($_POST['nurse_age']);
		$nurse_education = empty($_POST['nurse_education']) ? 0 : intval($_POST['nurse_education']);
		$nurse_provinceid = empty($_POST['nurse_provinceid']) ? 0 : intval($_POST['nurse_provinceid']);
		$nurse_cityid = empty($_POST['nurse_cityid']) ? 0 : intval($_POST['nurse_cityid']);
		$nurse_areaid = empty($_POST['nurse_areaid']) ? 0 : intval($_POST['nurse_areaid']);
		$nurse_address = empty($_POST['nurse_address']) ? '' : $_POST['nurse_address'];
		$nurse_price = empty($_POST['nurse_price']) ? 0 : intval($_POST['nurse_price']);
		$nurse_days = empty($_POST['nurse_days']) ? 0 : intval($_POST['nurse_days']);
		$nurse_content = empty($_POST['nurse_content']) ? '' : $_POST['nurse_content'];
		if(empty($nurse_name)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的姓名', 'data'=>array())));
		}
		if(empty($nurse_image)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请上传个人照片', 'data'=>array())));
		}
		if(empty($member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的手机号', 'data'=>array())));
		}
		if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'手机号格式不正确', 'data'=>array())));
		}
		if(empty($nurse_type)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择看护类别', 'data'=>array())));
		}
		if(empty($nurse_education)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入工作年限', 'data'=>array())));
		}
		if(empty($nurse_age)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入您的年龄', 'data'=>array())));
		}
		if($nurse_price <= 0) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入月薪', 'data'=>array())));
		}
		if($nurse_days <= 0) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入每月工作天数', 'data'=>array())));
		}
		if(empty($nurse_provinceid)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择现居地址', 'data'=>array())));
		}
		$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid'");
		if(empty($district_count)) {
			if(empty($nurse_cityid)) {
				exit(json_encode(array('code'=>'1', 'msg'=>'请选择现居地址', 'data'=>array())));
			}
			$nurse_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
		} else {
			if(empty($nurse_areaid)) {
				exit(json_encode(array('code'=>'1', 'msg'=>'请选择现居地址', 'data'=>array())));
			}
			$nurse_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
		}
		if(empty($nurse_address)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入详细地址', 'data'=>array())));
		}
		if(empty($nurse_content)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请输入服务项目', 'data'=>array())));
		}
		$nurse_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
		$nurse_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
		$nurse_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_areaid'");
		$nurse_areainfo = $nurse_provincename.$nurse_cityname.$nurse_areaname;
		$data = array(
			'member_phone' => $member_phone,
			'nurse_name' => $nurse_name,
			'nurse_image' => $nurse_image,
			'nurse_type' => $nurse_type,
			'nurse_price' => $nurse_price,
			'nurse_days' => $nurse_days,
			'nurse_age' => $nurse_age,
			'nurse_education' => $nurse_education,
			'nurse_provinceid' => $nurse_provinceid,
			'nurse_cityid' => $nurse_cityid,
			'nurse_areaid' => $nurse_areaid,
			'nurse_areainfo' => $nurse_areainfo,
			'nurse_cityname' => $nurse_city,
			'nurse_address' => $nurse_address,
			'nurse_content' => $nurse_content,
			'nurse_state' => 0,
		);
		DB::update('nurse', $data, array('nurse_id'=>$nurse['nurse_id']));
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));	
	}
}

?>