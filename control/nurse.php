<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
class nurseControl extends BaseHomeControl {
	public function indexOp() {
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 4;
        $start = ($page-1)*$perpage;
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		$nurse_accid=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$nurse['member_id']."'");
		if(empty($nurse)) {
			$this->showmessage('家政人员不存在', 'index.php?act=index&op=nurse', 'error');
		}
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
		$nurse['nurse_tags'] = empty($nurse['nurse_tags']) ? array() : unserialize($nurse['nurse_tags']);
		$nurse['nurse_qa_image'] = empty($nurse['nurse_qa_image']) ? array() : unserialize($nurse['nurse_qa_image']);
		$nurse['nurse_work_exe']=empty($nurse['nurse_work_exe']) ? array() : unserialize($nurse['nurse_work_exe']);
		$nurse['car_weight_list']=empty($nurse['car_weight_list']) ? array() : unserialize($nurse['car_weight_list']);
		$nurse['car_price_list']=empty($nurse['car_price_list']) ? array() : unserialize($nurse['car_price_list']);
		$nurse['nurse_desc'] = nl2br($nurse['nurse_desc']);
		$nurse['nurse_demand'] = nl2br($nurse['nurse_demand']);
		$nurse['nurse_content'] = nl2br($nurse['nurse_content']);
		if($nurse['nurse_type']==1 || $nurse['nurse_type']==2 || $nurse['nurse_type']==5 || $nurse['nurse_type']==6){
		    if($nurse['nurse_sex']==2){
                $nurse['nurse_name']=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0阿姨", $nurse['nurse_name'],1);
            }else{
                $nurse['nurse_name']=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0师傅", $nurse['nurse_name'],1);
            }
        }elseif ($nurse['nurse_type']==3 || $nurse['nurse_type']==4){
            $nurse['nurse_name']=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0点工", $nurse['nurse_name'],1);
        }elseif ($nurse['nurse_type']==7 || $nurse['nurse_type']==8 || $nurse['nurse_type']==9 || $nurse['nurse_type']==10){
            $nurse['nurse_name']=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0师傅", $nurse['nurse_name'],1);
        }elseif ($nurse['nurse_type']==11 || $nurse['nurse_type']==12 || $nurse['nurse_type']==16){
            $nurse['nurse_name']=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0老师", $nurse['nurse_name'],1);
        }elseif ($nurse['nurse_type']==13 || $nurse['nurse_type']==14){
            $nurse['nurse_name']=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0护工", $nurse['nurse_name'],1);
        }elseif ($nurse['nurse_type']==15){
            $nurse['nurse_name']=preg_replace("/[\x{4e00}-\x{9fa5}]/iu", "\\0管家", $nurse['nurse_name'],1);
        }
        $nurse['nurse_name']=substr ($nurse['nurse_name'],0,9);
		$grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE grade_id='".$nurse['grade_id']."'");
		$agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$nurse['agent_id']."'");
        $agent_accid=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$agent['member_id']."'");
		if(!empty($agent)){
            $agent_grade = DB::fetch_first("SELECT * FROM ".DB::table('agent_grade')." WHERE agent_score<=".$agent['agent_score']." ORDER BY agent_score DESC");
        }
		$level_array = array('good'=>'好评 ', 'middle'=>'中评', 'bad'=>'差评');
		$query = DB::query("SELECT * FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' ORDER BY comment_time DESC LIMIT $start,$perpage");
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."'");
        $success_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='".$nurse['nurse_id']."' AND book_state=30");
        $good_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_level='good'");
        $middle_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_level='middle'");
        $bad_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_level='bad'");
        $hasImg_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='".$nurse['nurse_id']."' AND comment_image!=''");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$book_ids[] = $value['book_id'];
			$value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$comment_list[] = $value;
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
				$member_list[$value['member_id']] = $value;
			}
		}
		if(!empty($book_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $book_ids)."')");
            while($value = DB::fetch($query)) {
                $book_list[$value['book_id']] = $value;
            }
        }
		DB::query("UPDATE ".DB::table('nurse')." SET nurse_viewnum=nurse_viewnum+1 WHERE nurse_id='".$nurse['nurse_id']."'");
        if(!empty($this->member_id)){
            $footprint=DB::fetch_first("SELECT * FROM ".DB::table('member_footprint')." WHERE nurse_id='$nurse_id' AND member_id='$this->member_id'");
            if(empty($footprint)){
                $footprint_data=array(
                    'member_id'=>$this->member_id,
                    'nurse_id'=>$nurse_id,
                    'footprint_time'=>time()
                );
                DB::insert('member_footprint', $footprint_data);
            }else{
                $footprint_time=time();
                DB::query("UPDATE ".DB::table('member_footprint')." SET footprint_count=footprint_count+1,footprint_time='$footprint_time' WHERE nurse_id='$nurse_id' AND member_id='$this->member_id'");
            }
        }
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
        $multi = multi($count, $perpage, $page, '',selectevaluate);
		$curmodule = 'home';
		$bodyclass = '';
		include(template('nurse'));
	}
	
	public function commentOp() {
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 4;
        $start = ($page-1)*$perpage;
		$level_array = array('good'=>'好评 ', 'middle'=>'中评', 'bad'=>'差评');
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$wheresql = " WHERE nurse_id='$nurse_id'";
		$field_value = !in_array($_GET['field_value'], array('all', 'good', 'middle', 'bad','hasimg')) ? 'all' : $_GET['field_value'];
		if($field_value == 'good') {
			$wheresql .= " AND comment_level='good'";
		} elseif($field_value == 'middle') {
			$wheresql .= " AND comment_level='middle'";
		} elseif($field_value == 'bad') {
			$wheresql .= " AND comment_level='bad'";
		}elseif ($field_value=="hasimg"){
            $wheresql .= " AND comment_image!=''";
        }
        $content= empty($_GET['content']) ? '' : $_GET['content'];
        if($content==''){
            $wheresql .= " AND comment_content!=''";
        }else{
            $wheresql .= " AND comment_content=''";
        }
        $value=!in_array($_GET['value'], array('score', 'time')) ? 'time' : $_GET['value'];
		if($value=='score'){
            $wheresql .= " ORDER BY comment_score DESC";
        }elseif($value=='time'){
            $wheresql .= " ORDER BY comment_time DESC";
        }
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql);
        $multi = multi($count, $perpage, $page, '',selectevaluate);
		$query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql." LIMIT $start,$perpage");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$book_ids[] = $value['book_id'];
			$value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$comment_list[] = $value;	
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
				$member_list[$value['member_id']] = $value;
			}
		}
        if(!empty($book_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $book_ids)."')");
            while($value = DB::fetch($query)) {
                $book_list[$value['book_id']] = $value;
            }
        }
		include(template('nurse_comment'));
	}
    public function collectOp() {
        if(empty($this->member)) {
            exit(json_encode(array('done'=>'login')));
        }
        $nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        if(empty($nurse_id)) {
            exit(json_encode(array('msg'=>'家政人员不存在')));
        }
        $collect = DB::fetch_first("SELECT * FROM ".DB::table('member_collect')." WHERE member_id='$this->member_id' AND nurse_id='$nurse_id'");
        if(empty($collect)) {
            $data = array(
                'member_id' => $this->member_id,
                'nurse_id' => $nurse_id,
                'agent_id'=>$agent_id,
                'collect_time' => time(),
            );
            DB::insert('member_collect', $data);
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg'=>'您已经收藏了')));
        }
    }
	public function favoriteOp() {
		if(empty($this->member)) {
			exit(json_encode(array('done'=>'login')));
		}
		$fav_id = empty($_GET['fav_id']) ? 0 : intval($_GET['fav_id']);
		if(empty($fav_id)) {
			exit(json_encode(array('msg'=>'家政人员不存在')));
		}
		$fav = DB::fetch_first("SELECT * FROM ".DB::table('favorite')." WHERE member_id='$this->member_id' AND fav_id='$fav_id' AND fav_type='nurse'");
		if(empty($fav)) {
			$data = array(
				'member_id' => $this->member_id,
				'fav_id' => $fav_id,
				'fav_type' => 'nurse',
				'fav_time' => time(),
			);
			DB::insert('favorite', $data);
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_favoritenum=nurse_favoritenum+1 WHERE nurse_id='$fav_id'");
		} else {
			exit(json_encode(array('msg'=>'您已经关注了')));
		}
		exit(json_encode(array('done'=>'true')));
	}
    public function focusOp(){
        if(empty($this->member)) {
            exit(json_encode(array('done'=>'login')));
        }
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        if(empty($agent_id)){
            exit(json_encode(array('msg'=>'家政机构不存在')));
        }
        $focus= DB::fetch_first("SELECT * FROM ".DB::table('focus')." WHERE member_id='$this->member_id' AND agent_id='$agent_id' AND focus_type='agent'");
        if(empty($focus)) {
            $data = array(
                'member_id' => $this->member_id,
                'agent_id' => $agent_id,
                'focus_type' => 'agent',
                'focus_time' => time(),
            );
            DB::insert('focus', $data);
            DB::query("UPDATE ".DB::table('agent')." SET agent_focusnum=agent_focusnum+1 WHERE agent_id='$agent_id'");
        } else {
            exit(json_encode(array('msg'=>'您已经关注了')));
        }
        exit(json_encode(array('done'=>'true')));
    }
	public function get_phoneOp(){
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		if(empty($this->member_id)) {
        	exit(json_encode(array('done'=>'login')));
        }else if(empty($nurse_id)){
        	exit(json_encode(array('msg'=>'家政人员不存在')));
        }else{
         	$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
         	$member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member'");
         	$time=date('Y-m-d H:i:s',time());
         	$ip = $_SERVER["REMOTE_ADDR"];
 			$data=array(
 				'nurse_id'=>$nurse_id,
 				'nurse_name'=>$nurse['nurse_name'],
 				'member_id' => $this->member_id,
 				'member_nickname'=>$member['member_nickname'],
 				'get_time'=>$time,
				'get_ip'=>$ip,
				'nurse_status'=>'3'
 			);
 			DB::insert('record_getPhone',$data,1);
 			DB::query("UPDATE ".DB::table('nurse')." SET status_time='$time' WHERE nurse_id='$nurse_id'");
			exit(json_encode(array('done'=>'succ')));
        }
	}
	public function search_phoneOp() {
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		exit(json_encode(array('done'=>'true', 'nurse_phone'=>$nurse['member_phone'])));
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
	public function registerOp() {
		if(submitcheck()) {
			if(empty($this->member_id)) {
				exit(json_encode(array('done'=>'register')));
			}
			if(!empty($this->nurse_id)) {
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
			$nurse_longtitude = empty($_POST['nurse_longtitude']) ? '' : $_POST['nurse_longtitude'];
			$nurse_latitude = empty($_POST['nurse_latitude']) ? '' : $_POST['nurse_latitude'];
			$nurse_education = empty($_POST['nurse_education']) ? 0 : intval($_POST['nurse_education']);
			$nurse_price = empty($_POST['nurse_price']) ? 0 : intval($_POST['nurse_price']);
			$nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
			$nurse_cardid = empty($_POST['nurse_cardid']) ? '' : $_POST['nurse_cardid'];
			$nurse_cardid_image = empty($_POST['nurse_cardid_image']) ? '' : $_POST['nurse_cardid_image'];
			$nurse_qa_image = empty($_POST['nurse_qa_image']) ? array() : $_POST['nurse_qa_image'];
			$nurse_content = empty($_POST['nurse_content']) ? '' : $_POST['nurse_content'];
			$from_phone = empty($_POST['from_phone']) ? '' : $_POST['from_phone'];
			if(empty($nurse_name)) {
				exit(json_encode(array('id'=>'nurse_name', 'msg'=>'您的姓名必须填写')));
			}
			if(empty($member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'您的手机号必须填写')));
			}
			if(!preg_match('/^1[0-9]{10}$/', $member_phone)) {
				exit(json_encode(array('id'=>'member_phone', 'msg'=>'您的手机号格式不正确')));
			}
			if(empty($nurse_type)) {
				exit(json_encode(array('id'=>'nurse_type', 'msg'=>'看护类别必须填写')));
			}
            if(empty($service_type)) {
                exit(json_encode(array('id'=>'nurse_type', 'msg'=>'服务类别必须填写')));
            }
			if(empty($nurse_age)) {
				exit(json_encode(array('id'=>'nurse_age', 'msg'=>'您的年龄必须填写')));
			}
			// if(empty($birth_provinceid)) {
				// exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
			// }
			// $district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$birth_cityid'");
			// if(empty($district_count)) {
				// if(empty($birth_cityid)) {
					// exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
				// }
			// } else {
				// if(empty($birth_areaid)) {
					// exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
				// }
			// }
			if(empty($nurse_provinceid)) {
				exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid'");
			if(empty($district_count)) {
				if(empty($nurse_cityid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
				}
			} else {
				if(empty($nurse_areaid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
				}
			}			
			if(empty($nurse_address)) {
				exit(json_encode(array('id'=>'nurse_address', 'msg'=>'详细地址必须填写')));
			}
			// if($nurse_education <= 0) {
				// exit(json_encode(array('id'=>'nurse_education', 'msg'=>'工作年限必须填写')));
			// }
			if($nurse_price <= 0) {
				exit(json_encode(array('id'=>'nurse_price', 'msg'=>'期望薪资必须填写')));
			}
			// if(empty($nurse_image)) {
				// exit(json_encode(array('id'=>'nurse_image', 'msg'=>'个人照片必须上传')));
			// }
			// if(empty($nurse_cardid)) {
				// exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'身份证号码必须填写')));
			// }
			// if(!checkcard($nurse_cardid)) {
				// exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'身份证号码格式不正确')));
			// }
			/*
			if(empty($nurse_cardid_image)) {
				exit(json_encode(array('id'=>'nurse_cardid_image', 'msg'=>'手持身份证照必须上传')));
			}
			*/
			// if(empty($nurse_content)) {
				// exit(json_encode(array('id'=>'nurse_content', 'msg'=>'服务项目必须填写')));
			// }
			if(!empty($from_phone)) {
				if(!preg_match('/^1[0-9]{10}$/', $from_phone)) {
					exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号格式不正确')));
				}
				if($from_phone == $this->member['member_phone']) {
					exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号不能是自己的')));
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
			$agent = DB::fetch_first("SELECT * FROM ".DB::table("agent")." WHERE is_own=1");
			$nurse_grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE nurse_score=0");
			$status_time=date('Y-m-d H:i:s',time());
			$data = array(
				'member_id' => $this->member_id,
				'agent_id' => $agent['agent_id'],
				'nurse_name' => $nurse_name,
				'member_phone' => $member_phone,
				'nurse_image' =>$nurse_image,
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
				'nurse_longtitude'=>$nurse_longtitude,
				'nurse_latitude'=>$nurse_latitude,
				'nurse_cardid' => $nurse_cardid,
				'nurse_cardid_image' => $nurse_cardid_image,
				'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
				'nurse_content' => $nurse_content,
				'grade_id' => $nurse_grade['grade_id'],
				'from_phone' => $from_phone,
				'nurse_state' => 0,
				'nurse_time' => time(),
				'status_available'=>0,
				'status_working'=>0,
				'status_called'=>0,
				'state_cideci'=>0,
				'status_time'=>$status_time
			);
			$nurse_id = DB::insert('nurse', $data, 1);
			if(!empty($nurse_id)) {
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			if(empty($this->member_id)) {
				@header("Location: index.php?act=register&next_step=nurse");
				exit;	
			}
			if(!empty($this->nurse_id)) {
				@header("Location: index.php?act=nurse_center");
				exit;
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[] = $value;
			}
			$curmodule = 'member';
			$bodyclass = '';
			include(template('nurse_register'));
		}
	}
	
	    public function registerAdminOp() {
        if(submitcheck()) {
            if(empty($this->member_id)) {
                exit(json_encode(array('done'=>'register')));
            }
            if(!empty($this->nurse_id)) {
                exit(json_encode(array('done'=>'nurse')));
            }
            $from_phone = empty($_POST['from_phone']) ? '' : $_POST['from_phone'];
            $nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
            $nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
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
            $nurse_days = empty($_POST['nurse_days']) ? 0 : intval($_POST['nurse_days']);
            $nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
            $nurse_cardid = empty($_POST['nurse_cardid']) ? '' : $_POST['nurse_cardid'];
            $nurse_cardid_image = empty($_POST['nurse_cardid_image']) ? '' : $_POST['nurse_cardid_image'];
            $nurse_qa = empty($_POST['nurse_qa']) ? array() : $_POST['nurse_qa'];
            $nurse_qa_image = empty($_POST['nurse_qa_image']) ? array() : $_POST['nurse_qa_image'];
            $nurse_demand = empty($_POST['nurse_demand']) ? '' : $_POST['nurse_demand'];
            $nurse_content = empty($_POST['nurse_content']) ? '' : $_POST['nurse_content'];
            $nurse_desc = empty($_POST['nurse_desc']) ? '' : $_POST['nurse_desc'];
            if(!empty($from_phone)) {
                if(!preg_match('/^1[0-9]{10}$/', $from_phone)) {
                    exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号格式不正确')));
                }
                if($from_phone == $this->member['member_phone']) {
                    exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号不能是自己的')));
                }
                $from_member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_phone='$from_phone'");
                if(empty($from_member)) {
                    exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人手机号不存在')));
                }
                $from_nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='".$from_member['member_id']."'");
                if(empty($from_nurse)) {
                    exit(json_encode(array('id'=>'from_phone', 'msg'=>'推荐人还不是阿姨看护')));
                }
            }
			if(empty($nurse_name)) {
				exit(json_encode(array('id'=>'nurse_name', 'msg'=>'您的姓名必须填写')));
			}
			if(empty($nurse_type)) {
				exit(json_encode(array('id'=>'nurse_type', 'msg'=>'看护类别必须填写')));
			}
//			if(empty($nurse_age)) {
//				exit(json_encode(array('id'=>'nurse_age', 'msg'=>'您的年龄必须填写')));
//			}
//			if(empty($birth_provinceid)) {
//				exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
//			}
//			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$birth_cityid'");
//			if(empty($district_count)) {
//				if(empty($birth_cityid)) {
//					exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
//				}
//				$birth_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$birth_provinceid'");
//			} else {
//				if(empty($birth_areaid)) {
//					exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'出生地址必须填写')));
//				}
//				$birth_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$birth_cityid'");
//			}
			if(empty($nurse_provinceid)) {
				exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
			}
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid'");
			if(empty($district_count)) {
				if(empty($nurse_cityid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
				}
				$nurse_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
			} else {
				if(empty($nurse_areaid)) {
					exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'现居地址必须填写')));
				}
				$nurse_city = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
			}
			if(empty($nurse_address)) {
				exit(json_encode(array('id'=>'nurse_address', 'msg'=>'详细地址必须填写')));
			}
//			if(empty($nurse_education)) {
//				exit(json_encode(array('id'=>'nurse_education', 'msg'=>'工作年限必须填写')));
//			}
			if($nurse_price <= 0) {
				exit(json_encode(array('id'=>'nurse_price', 'msg'=>'月薪必须填写')));
			}
//			if($nurse_days <= 0) {
//				exit(json_encode(array('id'=>'nurse_days', 'msg'=>'每月工作天数必须填写')));
//			}
//			if(empty($nurse_image)) {
//				exit(json_encode(array('id'=>'nurse_image', 'msg'=>'个人照片必须上传')));
//			}
//			if(empty($nurse_cardid)) {
//				exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'身份证号码必须填写')));
//			}
//			if(empty($nurse_cardid_image)) {
//				exit(json_encode(array('id'=>'nurse_cardid_image', 'msg'=>'手持身份证照必须上传')));
//			}
//			if(empty($nurse_content)) {
//				exit(json_encode(array('id'=>'nurse_content', 'msg'=>'服务项目必须填写')));
//			}
            $birth_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$birth_provinceid'");
            $birth_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$birth_cityid'");
            $birth_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$birth_areaid'");
            $birth_areainfo = $birth_provincename.$birth_cityname.$birth_areaname;
            $nurse_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
            $nurse_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
            $nurse_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_areaid'");
            $nurse_areainfo = $nurse_provincename.$nurse_cityname.$nurse_areaname;
            $agent = DB::fetch_first("SELECT * FROM ".DB::table("agent")." WHERE is_own=1");
            $nurse_grade = DB::fetch_first("SELECT * FROM ".DB::table("nurse_grade")." WHERE nurse_score=0");
            $data = array(
                'member_id' => $this->member_id,
                'member_phone' => $this->member['member_phone'],
                'agent_id' => $agent['agent_id'],
                'nurse_name' => $nurse_name,
                'nurse_image' => $nurse_image,
                'nurse_type' => $nurse_type,
                'nurse_price' => $nurse_price,
                'nurse_days' => $nurse_days,
                'nurse_age' => $nurse_age,
                'nurse_education' => $nurse_education,
                'birth_provinceid' => $birth_provinceid,
                'birth_cityid' => $birth_cityid,
                'birth_areaid' => $birth_areaid,
                'birth_areainfo' => $birth_areainfo,
                'birth_cityname' => '',
                'nurse_provinceid' => $nurse_provinceid,
                'nurse_cityid' => $nurse_cityid,
                'nurse_areaid' => $nurse_areaid,
                'nurse_areainfo' => $nurse_areainfo,
                'nurse_cityname' => $nurse_city,
                'nurse_address' => $nurse_address,
                'nurse_cardid' => $nurse_cardid,
                'nurse_cardid_image' => $nurse_cardid_image,
                'nurse_qa' => empty($nurse_qa) ? '' : serialize($nurse_qa),
                'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
                'nurse_demand' => $nurse_demand,
                'nurse_content' => $nurse_content,
                'nurse_desc' => $nurse_desc,
                'grade_id' => $nurse_grade['grade_id'],
                'from_memberid' => $from_member['member_id'],
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
            if(empty($this->member_id)) {
                @header("Location: index.php?act=register&next_step=nurse");
                exit;
            }
            if(!empty($this->nurse_id)) {
                @header("Location: index.php?act=nurse_center");
                exit;
            }
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                $province_list[] = $value;
            }
            $curmodule = 'member';
            $bodyclass = '';
            include(template('nurse_registerAdmin'));
        }
    }
	
	public function step2Op() {
		if(empty($this->nurse_id)) {				
			@header('Location: index.php?act=nurse&op=register');
			exit;
		}
		$curmodule = 'member';
		$bodyclass = '';
		include(template('nurse_register_step2'));
	}

	public function collect_addOp(){
        if(empty($this->member_id)) {
            exit(json_encode(array('msg'=>'您还未登录了')));
        }
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $nurse_id=empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
        $agent_id=empty($_POST['agent_id']) ? 0 : intval($_POST['agent_id']);
        $nurse_type=empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
        $work_duration=empty($_POST['work_duration']) ? 0 : intval($_POST['work_duration']);
        $work_duration_days=empty($_POST['work_duration_days']) ? 0 : intval($_POST['work_duration_days']);
        $work_duration_hours=empty($_POST['work_duration_hours']) ? 0 : intval($_POST['work_duration_hours']);
        $work_duration_mins=empty($_POST['work_duration_mins']) ? 0 : intval($_POST['work_duration_mins']);
        $work_area=empty($_POST['work_area']) ? 0 : intval($_POST['work_area']);
        $work_person=empty($_POST['work_person']) ? 0 : intval($_POST['work_person']);
        $work_machine=empty($_POST['work_machine']) ? 0 : intval($_POST['work_machine']);
        $work_cars=empty($_POST['work_cars']) ? 0 : intval($_POST['work_cars']);
        $car_price=empty($_POST['car_price']) ? 0 : intval($_POST['car_price']);
        $work_students=empty($_POST['work_students']) ? 0 : intval($_POST['work_students']);
        $service_price=empty($_POST['service_price']) ? 0 : intval($_POST['service_price']);
        $nurse_price=empty($_POST['nurse_price']) ? 0 : intval($_POST['nurse_price']);
        $total_price=empty($_POST['total_price']) ? 0 : intval($_POST['total_price']);
        $nurse_discount=empty($_POST['nurse_discount']) ? 1 : intval($_POST['nurse_discount']);
        $collect_details=empty($_POST['collect_details']) ? '' : $_POST['collect_details'];
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if($nurse['state_cideci']==2 || $nurse['state_cideci']==4){
            exit(json_encode(array('msg'=>'该家政人员正在工作中')));
        }
//        $member_favourite = DB::fetch_first("SELECT * FROM ".DB::table('member_favourite')." WHERE nurse_id='$nurse_id' AND show_state=0 AND pay_state=0");
//        if(!empty($member_favourite)){
//            exit(json_encode(array('msg'=>'该家政人员你已经预约过了')));
//        }
        $data=array(
            'favourite_type'=>'order',
            'member_id'=>$this->member_id,
            'nurse_id'=>$nurse_id,
            'agent_id'=>$agent_id,
            'nurse_type'=>$nurse_type,
            'member_phone'=>$member['member_phone'],
            'work_duration'=>$work_duration,
            'work_duration_days'=>$work_duration_days,
            'work_duration_hours'=>$work_duration_hours,
            'work_duration_mins'=>$work_duration_mins,
            'work_area'=>$work_area,
            'work_person'=>$work_person,
            'work_machine'=>$work_machine,
            'work_cars'=>$work_cars,
            'car_price'=>$car_price,
            'work_students'=>$work_students,
            'service_price'=>$service_price,
            'nurse_price'=>$nurse_price,
            'total_price'=>$total_price,
            'nurse_discount'=>$nurse_discount,
            'collect_details'=>$collect_details,
            'add_time'=>time()
        );
        $collect_id=DB::insert('member_favourite', $data, 1);
        if(!empty($collect_id)){
            exit(json_encode(array('done'=>'true','collect_id'=>$collect_id)));
        }
    }

    /**
     * 加入服务车/购物车
     * */
    public function favourite_addOp(){
        if(empty($this->member_id)) {
            exit(json_encode(array('msg'=>'您还未登录了')));
        }
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $nurse_id=empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
        $agent_id=empty($_POST['agent_id']) ? 0 : intval($_POST['agent_id']);
        $nurse_type=empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
        $work_duration=empty($_POST['work_duration']) ? 0 : intval($_POST['work_duration']);
        $work_duration_days=empty($_POST['work_duration_days']) ? 0 : intval($_POST['work_duration_days']);
        $work_duration_hours=empty($_POST['work_duration_hours']) ? 0 : intval($_POST['work_duration_hours']);
        $work_duration_mins=empty($_POST['work_duration_mins']) ? 0 : intval($_POST['work_duration_mins']);
        $work_area=empty($_POST['work_area']) ? 0 : intval($_POST['work_area']);
        $work_person=empty($_POST['work_person']) ? 0 : intval($_POST['work_person']);
        $work_machine=empty($_POST['work_machine']) ? 0 : intval($_POST['work_machine']);
        $work_cars=empty($_POST['work_cars']) ? 0 : intval($_POST['work_cars']);
        $car_price=empty($_POST['car_price']) ? 0 : intval($_POST['car_price']);
        $work_students=empty($_POST['work_students']) ? 0 : intval($_POST['work_students']);
        $service_price=empty($_POST['service_price']) ? 0 : intval($_POST['service_price']);
        $nurse_price=empty($_POST['nurse_price']) ? 0 : intval($_POST['nurse_price']);
        $total_price=empty($_POST['total_price']) ? 0 : intval($_POST['total_price']);
        $nurse_discount=empty($_POST['nurse_discount']) ? 1 : intval($_POST['nurse_discount']);
        $collect_details=empty($_POST['collect_details']) ? '' : $_POST['collect_details'];
        $member_favourite = DB::fetch_first("SELECT * FROM ".DB::table('member_favourite')." WHERE nurse_id='$nurse_id' AND show_state=0 AND pay_state=0 AND favourite_type='favourite'");
        if(!empty($member_favourite)){
            exit(json_encode(array('msg'=>'该家政人员你已经收藏过了')));
        }
        $data=array(
            'favourite_type'=>'favourite',
            'member_id'=>$this->member_id,
            'nurse_id'=>$nurse_id,
            'agent_id'=>$agent_id,
            'nurse_type'=>$nurse_type,
            'member_phone'=>$member['member_phone'],
            'work_duration'=>$work_duration,
            'work_duration_days'=>$work_duration_days,
            'work_duration_hours'=>$work_duration_hours,
            'work_duration_mins'=>$work_duration_mins,
            'work_area'=>$work_area,
            'work_person'=>$work_person,
            'work_machine'=>$work_machine,
            'work_cars'=>$work_cars,
            'car_price'=>$car_price,
            'work_students'=>$work_students,
            'service_price'=>$service_price,
            'nurse_price'=>$nurse_price,
            'total_price'=>$total_price,
            'nurse_discount'=>$nurse_discount,
            'collect_details'=>$collect_details,
            'add_time'=>time()
        );
        $collect_id=DB::insert('member_favourite', $data, 1);
        if(!empty($collect_id)){
            exit(json_encode(array('done'=>'true','collect_id'=>$collect_id)));
        }
    }

}

?>