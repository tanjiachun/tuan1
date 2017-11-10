<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurseControl extends BaseMobileControl {
	public function indexOp() {
        $error_data = (object)array();
        $nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $nurse = DB::fetch_first("SELECT nurse_id, member_id,agent_id, member_phone, nurse_name, nurse_image, nurse_type, nurse_price, nurse_days, nurse_age, nurse_education, birth_cityname, nurse_cityname, nurse_areaname, nurse_qa, nurse_qa_image, nurse_content, nurse_tags, nurse_desc, grade_id, nurse_score, nurse_viewnum, nurse_favoritenum, nurse_booknum, nurse_salenum, nurse_commentnum, work_state, nurse_state, nurse_time FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse) || $nurse['nurse_state'] != 1) {
			exit(json_encode(array('code'=>'1', 'msg'=>'阿姨已下架', 'data'=>$error_data)));
		}
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
        $nurse['result_url']=url();
		$nurse['share_title'] = $nurse['nurse_name'];
		$nurse['share_desc'] = $nurse['nurse_content'];
		$nurse['share_url'] = SiteUrl.'/mobile.php?act=share&nurse_id='.$nurse['nurse_id'];
		$nurse['nurse_qa'] = empty($nurse['nurse_qa']) ? array() : unserialize($nurse['nurse_qa']);
		$nurse['nurse_qa_image'] = empty($nurse['nurse_qa_image']) ? array() : unserialize($nurse['nurse_qa_image']);
		$nurse['nurse_tags'] = empty($nurse['nurse_tags']) ? array() : unserialize($nurse['nurse_tags']);
		$nurse['nurse_time'] = date('Y-m-d H:i', $nurse['nurse_time']);
		$grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE grade_id='".$nurse['grade_id']."'");
		$nurse['grade_name'] = $grade['grade_name'];
		$nurse['grade_icon'] = $grade['grade_icon'];
		$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$nurse['agent_id']."'");
		$nurse['agent_name'] = $agent['agent_name'];
		$nurse['agent_qq'] = $agent['agent_qq'];
		$nurse['agent_phone'] = $agent['agent_phone'];
		$member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$nurse['member_id']."'");
		$nurse['member_sex']=$member['member_sex'];
        $good_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_level='good'");
        $middle_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_level='middle'");
        $bad_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_level='bad'");
        $hasImg_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_image!=''");
        $nurse['good_count']=$good_count;
        $nurse['middle_count']=$middle_count;
        $nurse['bad_count']=$bad_count;
        $nurse['hasImg_count']=$hasImg_count;

        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' LIMIT 0, 10");
        while($value = DB::fetch($query)) {
            $member_ids[] = $value['member_id'];
            $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
            $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
            $value['comment_time'] = date('Y-m-d H:i', $value['comment_time']);
            $comment_list[] = $value;
        }
        if(!empty($member_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
                $member_list[$value['member_id']] = $value;
            }
        }
        foreach($comment_list as $key => $value) {
            $comment_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
            $comment_list[$key]['member_avatar'] = $member_list[$value['member_id']]['member_avatar'];
        }
        $comment_data = array(
            'comment_count' => empty($comment_count) ? array() : $comment_count,
            'comment_list' => empty($comment_list) ? array() : $comment_list,
        );
        $nurse['comment_data']=$comment_data;


		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='activity' AND red_rule_starttime<=$time AND red_rule_endtime>=$time");
		while($value = DB::fetch($query)) {
			if($value['red_t_cate_id'] == 0 || $value['red_t_cate_id'] == 1) {
				$red = DB::fetch_first("SELECT * FROM ".DB::table("red")." WHERE member_id='$this->member_id' AND red_t_id='".$value['red_t_id']."'");
				$value['red_t_state'] = empty($red) ? 0 : 1;
				$red_list[] =$value;
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
		$data = array(
			'nurse' => $nurse,
			'red_list' => empty($red_list) ? array() : $red_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功','data'=>$data)));
	}

//	public function comment_allOp(){
//        $nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
//        $query = DB::query("SELECT comment_level, COUNT(*) as count FROM ".DB::table('nurse_comment')." WHERE nurse_id='$nurse_id' GROUP BY comment_level");
//        while($value = DB::fetch($query)) {
//            $comment_count[$value['comment_level']] = $value['count'];
//        }
//        $query=$query = DB::query("SELECT * FROM ".DB::table('nurse_comment')." WHERE nurse_id='$nurse_id'" );
//        while($value = DB::fetch($query)) {
//            $member_ids[] = $value['member_id'];
//            $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
//            $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
//            $value['comment_time'] = date('Y-m-d H:i', $value['comment_time']);
//            $comment_list[] = $value;
//        }
//        if(!empty($member_ids)) {
//            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
//            while($value = DB::fetch($query)) {
//                $value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
//                $member_list[$value['member_id']] = $value;
//            }
//        }
//        foreach($comment_list as $key => $value) {
//            $comment_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
//            $comment_list[$key]['member_avatar'] = $member_list[$value['member_id']]['member_avatar'];
//        }
//        $data = array(
//            'comment_count' => empty($comment_count) ? array() : $comment_count,
//            'comment_list' => empty($comment_list) ? array() : $comment_list,
//        );
//        exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
//    }

	public function commentOp() {
		$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
		$query = DB::query("SELECT comment_level, COUNT(*) as count FROM ".DB::table('nurse_comment')." WHERE nurse_id='$nurse_id' GROUP BY comment_level");
		while($value = DB::fetch($query)) {
			$comment_count[$value['comment_level']] = $value['count'];
		}
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE nurse_id='$nurse_id'";
		$field_value = !in_array($_POST['field_value'], array('all', 'good', 'middle', 'bad')) ? 'all' : $_POST['field_value'];
		if($field_value == 'good') {
			$wheresql .= " AND comment_level='good'";
		} elseif($field_value == 'middle') {
			$wheresql .= " AND comment_level='middle'";
		} elseif($field_value == 'bad') {
			$wheresql .= " AND comment_level='bad'";
		}
		$query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$value['comment_time'] = date('Y-m-d H:i', $value['comment_time']);
			$comment_list[] = $value;	
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
				$member_list[$value['member_id']] = $value;
			}
		}
		foreach($comment_list as $key => $value) {
			$comment_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
			$comment_list[$key]['member_avatar'] = $member_list[$value['member_id']]['member_avatar'];
		}
		$data = array(
			'comment_count' => empty($comment_count) ? array() : $comment_count,
			'comment_list' => empty($comment_list) ? array() : $comment_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function registerOp() {
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
		if(!empty($nurse)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'您已经申请阿姨了', 'data'=>array())));	
		}
		$nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
		$member_phone = empty($_POST['member_phone']) ? '' : $_POST['member_phone'];
		$nurse_cardid = empty($_POST['nurse_cardid']) ? '' : $_POST['nurse_cardid'];
		$nurse_qa_image = empty($_POST['nurse_qa_image']) ? '' : $_POST['nurse_qa_image'];
		$nurse_qa_image = empty($nurse_qa_image) ? array() : explode(',', $nurse_qa_image);
		if(empty($nurse_name)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'您的姓名必须填写', 'data'=>array())));
		}
		if(empty($nurse_cardid)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'身份证号码必须填写', 'data'=>array())));
		}
		if(!checkcard($nurse_cardid)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'身份证号码格式不正确', 'data'=>array())));
		}
		$agent = DB::fetch_first("SELECT * FROM ".DB::table("agent")." WHERE is_own=1");
		$grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE nurse_score=0");
		$data = array(
			'member_id' => $this->member_id,
			'member_phone' => $member_phone,
			'agent_id' => $agent['agent_id'],
			'nurse_name' => $nurse_name,
			'nurse_cardid' => $nurse_cardid,
			'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
			'grade_id' => $grade['grade_id'],
			'nurse_state' => 0,
			'nurse_time' => time(),
		);
		$nurse_id = DB::insert('nurse', $data, 1);
		if(!empty($nurse_id)) {
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}		
	}

	public function get_stateOp(){
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$nurse_status= empty($_GET['nurse_state']) ? 0 : intval($_GET['nurse_state']);
		$time=date('Y-m-d H:i:s',time());
		if($nurse_status=='0'){
			DB::query("UPDATE ".DB::table('nurse')." SET status_available=status_available+1 WHERE nurse_id='$nurse_id'");
		}
		if($nurse_status=='1'){
			DB::query("UPDATE ".DB::table('nurse')." SET status_working=status_working+1 WHERE nurse_id='$nurse_id'");
		}
		if($nurse_status=='2'){
			DB::query("UPDATE ".DB::table('nurse')." SET status_called=status_called+1 WHERE nurse_id='$nurse_id'");
		}
		DB::query("UPDATE ".DB::table('nurse')." SET status_time='$time' WHERE nurse_id='$nurse_id'");
		DB::query("UPDATE ".DB::table('record_getPhone')." SET nurse_status=$nurse_status WHERE member_id='$this->member_id' AND  nurse_id='$nurse_id'");
		exit(json_encode(array('done'=>'true')));
	}

	public function phoneOp() {
		$nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		$member_phone = empty($nurse['member_phone']) ? '' : $nurse['member_phone'];
		$data = array(
			'member_phone' => $member_phone,
		);
		exit(json_encode(array('code'=>'0',  'msg'=>'操作成功', 'data'=>$data)));
	}

	public function auditOp(){
	    $nurse_id=empty($_POST['nurse_id']) ? 0 : $_POST['nurse_id'];
	    if(empty($nurse_id)){
	        exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
	    $agent_id=empty($_POST['agent_id']) ? 0 : $_POST['agent_id'];
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在')));
        }
        $audit=DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='$agent_id' AND nurse_id='$nurse_id' AND nurse_state=1");
        if(empty($audit)) {
            $data = array(
                'agent_id' => $agent_id,
                'nurse_id' => $nurse_id,
                'agent_state' => 0,
                'nurse_state' => 1,
                'invitation_count' => 1,
                'staff_time' => time()
            );
            $staff_id=DB::insert('staff_audit', $data, 1);
            if(!empty($staff_id)){
                exit(json_encode(array('code'=>0,'msg'=>'申请成功')));
            }else{
                exit(json_encode(array('code'=>1,'msg'=>'申请失败')));
            }
        }else{
            if(intval($audit['invitation_count']>=3)){
                exit(json_encode(array('code'=>1,'msg'=>'您已经申请超过三次,无法继续申请')));
            }else{
                $staff_id=$audit['staff_id'];
                DB::query("UPDATE ".DB::table('staff_audit')." SET agent_audit_state=0 WHERE staff_id='$staff_id'");
                DB::query("UPDATE ".DB::table('staff_audit')." SET invitation_count=invitation_count+1 WHERE staff_id='$staff_id'");
                exit(json_encode(array('code'=>0,'msg'=>'再次申请成功')));
            }
        }
    }

}

?>