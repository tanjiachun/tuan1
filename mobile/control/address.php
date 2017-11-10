<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class addressControl extends BaseMobileControl {
	public function indexOp() {
        $this->check_authority();
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
		$query = DB::query("SELECT * FROM ".DB::table('member_address')." WHERE member_id='$this->member_id' AND show_state=0 ORDER BY address_time DESC");
		while($value = DB::fetch($query)) {
			$address_list[] = $value;
		}
		exit(json_encode(array('code'=>0, 'msg'=>'操作成功', 'data'=>$address_list)));
	}
	
	public function addOp() {
        $this->check_authority();
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_address')." WHERE member_id='$this->member_id'");
	    if($count>=10){
            exit(json_encode(array('code'=>1, 'msg'=>'最多只能添加十条地址', 'data'=>array())));
        }
		$address_member_name = empty($_POST['address_member_name']) ? '' : $_POST['address_member_name'];
		$address_phone = empty($_POST['address_phone']) ? '' : $_POST['address_phone'];
		$member_provinceid = empty($_POST['member_provinceid']) ? 0 : intval($_POST['member_provinceid']);
		$member_cityid = empty($_POST['member_cityid']) ? 0 : intval($_POST['member_cityid']);
		$member_areaid = empty($_POST['member_areaid']) ? 0 : intval($_POST['member_areaid']);
		$address_content = empty($_POST['address_content']) ? '' : $_POST['address_content'];
		$is_choose = empty($_POST['is_choose']) ? 0 : intval($_POST['is_choose']);
		if(empty($address_member_name)) {
			exit(json_encode(array('code'=>1, 'msg'=>'请输入联系人', 'data'=>array())));
		}
		if(empty($address_phone)) {
			exit(json_encode(array('code'=>1, 'msg'=>'请输入电话', 'data'=>array())));
		}
		if(!preg_match('/^1[0-9]{10}$/', $address_phone)) {
			exit(json_encode(array('code'=>1, 'msg'=>'电话格式不正确', 'data'=>array())));
		}
		if(empty($member_provinceid) || empty($member_cityid)) {
			exit(json_encode(array('code'=>1, 'msg'=>'请选择所在地区', 'data'=>array())));
		}
		$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$member_cityid'");
		if(!empty($district_count) && empty($member_areaid)) {
			exit(json_encode(array('code'=>1, 'msg'=>'请选择所在地区', 'data'=>array())));
		}
		if(empty($address_content)) {
			exit(json_encode(array('code'=>1, 'msg'=>'请输入详细地址', 'data'=>array())));
		}
		$province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_provinceid'");
		$city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_cityid'");
		$area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_areaid'");
		$member_areainfo = $province_name.$city_name.$area_name;
		$data = array(
			'member_id' => $this->member_id,
			'address_member_name' => $address_member_name,
			'address_phone' => $address_phone,
			'member_provinceid' => $member_provinceid,
			'member_cityid' => $member_cityid,
			'member_areaid' => $member_areaid,
			'member_areainfo' => $member_areainfo,
			'address_content' => $address_content,
            'address_time'=>time()
		);
		$address_id = DB::insert('member_address', $data, 1);
		if(!empty($address_id)) {
            if($is_choose== 1) {
                DB::update('member', array('show_address_id'=>$address_id), array('member_id'=>$this->member_id));
                DB::update('member_address', array('choose_state'=>0), array('member_id'=>$this->member_id));
                DB::update('member_address', array('choose_state'=>1), array('member_address_id'=>$address_id,'member_id'=>$this->member_id));
            }
			exit(json_encode(array('code'=>0, 'msg'=>'操作成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>1, 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}
	}
	
	public function editOp() {
        $this->check_authority();
		$member_address_id = empty($_POST['member_address_id']) ? 0 : intval($_POST['member_address_id']);
        $address_member_name = empty($_POST['address_member_name']) ? '' : $_POST['address_member_name'];
        $address_phone = empty($_POST['address_phone']) ? '' : $_POST['address_phone'];
        $member_provinceid = empty($_POST['member_provinceid']) ? 0 : intval($_POST['member_provinceid']);
        $member_cityid = empty($_POST['member_cityid']) ? 0 : intval($_POST['member_cityid']);
        $member_areaid = empty($_POST['member_areaid']) ? 0 : intval($_POST['member_areaid']);
        $address_content = empty($_POST['address_content']) ? '' : $_POST['address_content'];
        $is_choose = empty($_POST['is_choose']) ? 0 : intval($_POST['is_choose']);
        if(empty($address_member_name)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请输入联系人', 'data'=>array())));
        }
        if(empty($address_phone)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请输入电话', 'data'=>array())));
        }
        if(!preg_match('/^1[0-9]{10}$/', $address_phone)) {
            exit(json_encode(array('code'=>1, 'msg'=>'电话格式不正确', 'data'=>array())));
        }
        if(empty($member_provinceid) || empty($member_cityid)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请选择所在地区', 'data'=>array())));
        }
        $district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$member_cityid'");
        if(!empty($district_count) && empty($member_areaid)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请选择所在地区', 'data'=>array())));
        }
        if(empty($address_content)) {
            exit(json_encode(array('code'=>1, 'msg'=>'请输入详细地址', 'data'=>array())));
        }
        $province_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_provinceid'");
        $city_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_cityid'");
        $area_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_areaid'");
        $member_areainfo = $province_name.$city_name.$area_name;
        $data = array(
            'address_member_name' => $address_member_name,
            'address_phone' => $address_phone,
            'member_provinceid' => $member_provinceid,
            'member_cityid' => $member_cityid,
            'member_areaid' => $member_areaid,
            'member_areainfo' => $member_areainfo,
            'address_content' => $address_content,
        );
        DB::update('member_address', $data, array('member_address_id'=>$member_address_id, 'member_id'=>$this->member_id));
        if($is_choose == 1) {
            DB::update('address', array('show_address_id'=>$member_address_id), array('member_id'=>$this->member_id));
            DB::update('member_address', array('choose_state'=>0), array('member_id'=>$this->member_id));
            DB::update('member_address', array('choose_state'=>1), array('member_address_id'=>$member_address_id,'member_id'=>$this->member_id));
        }
		exit(json_encode(array('code'=>0, 'msg'=>'操作成功', 'data'=>array())));
	}
	
	public function delOp() {
        $this->check_authority();
		$member_address_id = empty($_POST['member_address_id']) ? 0 : intval($_POST['member_address_id']);
        DB::update('member_address', array('show_state'=>1), array('member_address_id'=>$member_address_id, 'member_id'=>$this->member_id));
		exit(json_encode(array('code'=>0, 'msg'=>'删除成功', 'data'=>array())));
	}
	
	public function chooseOp() {
        $this->check_authority();
        $member_address_id = empty($_POST['member_address_id']) ? 0 : intval($_POST['member_address_id']);
        DB::update('member', array('show_address_id'=>$member_address_id), array('member_id'=>$this->member_id));
        DB::update('member_address', array('choose_state'=>0), array('member_id'=>$this->member_id));
        DB::update('member_address', array('choose_state'=>1), array('show_address_id'=>$member_address_id,'member_id'=>$this->member_id));
        exit(json_encode(array('code'=>0, 'msg'=>'设置成功', 'data'=>array())));
	}
}

?>