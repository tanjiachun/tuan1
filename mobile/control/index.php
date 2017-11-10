<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class indexControl extends BaseMobileControl {
	public function nurseOp() {
		$error_data = (object)array();
		$district_name = empty($_GET['district_name']) ? '宿迁' : $_GET['district_name'];
		if(empty($district_name)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_name like '%".$district_name."%'");
		if(empty($district)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$district_ids = array('1', '2', '9', '22', '32', '33', '34');
		if($district['district_level'] == 1 && !in_array($district['district_id'], $district_ids)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$this->setting['app_nurse_image'] = empty($this->setting['app_nurse_image']) ? array() : unserialize($this->setting['app_nurse_image']);
		foreach($this->setting['app_nurse_image'] as $key => $value) {
			if(!empty($value)) {
				$banner_list[] = $value;	
			}
		}
        $this->setting['app_nurse_url'] = empty($this->setting['app_nurse_url']) ? array() : unserialize($this->setting['app_nurse_url']);
        foreach($this->setting['app_nurse_url'] as $key => $value) {
            if(!empty($value)) {
                $banner_list_url[] = $value;
            }
        }
        $this->setting['app_goods_image'] = empty($this->setting['app_goods_image']) ? array() : unserialize($this->setting['app_goods_image']);
        foreach($this->setting['app_goods_image'] as $key => $value) {
            if(!empty($value)) {
                $hot_list[] = $value;
            }
        }
        $this->setting['app_goods_url'] = empty($this->setting['app_goods_url']) ? array() : unserialize($this->setting['app_goods_url']);
        foreach($this->setting['app_goods_url'] as $key => $value) {
            if(!empty($value)) {
                $hot_list_url[] = $value;
            }
        }
        $this->setting['app_pension_image'] = empty($this->setting['app_pension_image']) ? array() : unserialize($this->setting['app_pension_image']);
        foreach($this->setting['app_pension_image'] as $key => $value) {
            if(!empty($value)) {
                $hotproduct_list[] = $value;
            }
        }
        $this->setting['app_pension_url'] = empty($this->setting['app_pension_url']) ? array() : unserialize($this->setting['app_pension_url']);
        foreach($this->setting['app_pension_url'] as $key => $value) {
            if(!empty($value)) {
                $hotproduct_list_url[] = $value;
            }
        }
		$query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
		while($value = DB::fetch($query)) {
			$grade_list[$value['grade_id']] = $value;	
		}
		$nurse_field = 'nurse_id, agent_id, member_phone, nurse_name, nurse_image, nurse_type, nurse_price, nurse_days, nurse_age, nurse_education, birth_cityname, nurse_cityname, nurse_content, nurse_tags, nurse_desc, grade_id, nurse_score, nurse_viewnum, nurse_favoritenum, nurse_booknum, nurse_salenum, nurse_commentnum, work_state, nurse_time';
		$wheresql = " WHERE nurse_state=1";
		if($district['district_level'] == 1) {
			$wheresql .= " AND nurse_provinceid='".$district['district_id']."'";
		} elseif($district['district_level'] == 2) {
			$wheresql .= " AND nurse_cityid='".$district['district_id']."'";
		} elseif($district['district_level'] == 3) {
			$wheresql .= " AND nurse_areaid='".$district['district_id']."'";
		}
		$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." ORDER BY nurse_score DESC LIMIT 0, 3");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
			$super_list[] = $value;
		}
        $choose_time=time()-86400;
        $query=DB::query("SELECT book_id,member_phone,add_time FROM ".DB::table('nurse_book')." WHERE add_time <='".$choose_time."' ORDER BY add_time DESC");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE add_time <='".$choose_time."' ORDER BY add_time DESC");
        while($value=DB::fetch($query)){
            $value['member_phone']=preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
            $value['count']=$book_count;
            $value['details'] = "[".$value['member_phone']."] ".date('Y-m-d H:i',$value['add_time'])."已下单";
            $book_list[]=$value;
        }

        $query = DB::query("SELECT comment_id,comment_content,member_id,nurse_id FROM ".DB::table('nurse_comment')." ORDER BY comment_time DESC");
        while($value = DB::fetch($query)) {
            $member_ids[] = $value['member_id'];
            $nurse_ids[] = $value['nurse_id'];
            $comment_list[] = $value;
        }
        if(!empty($nurse_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        foreach ($comment_list as $key => $value){
            $comment_list[$key]['nurse_areaname'] = $nurse_list[$value['nurse_id']]['nurse_areaname'];
        }
//		$recommend_list['inside'] = array();
//		$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." AND nurse_type=1 ORDER BY nurse_score DESC LIMIT 0, 3");
//		while($value = DB::fetch($query)) {
//			$agent_ids[] = $value['agent_id'];
//			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
//			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
//			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
//			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
//			$recommend_list['inside'][] = $value;
//		}
//		$recommend_list['outside'] = array();
//		$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." AND nurse_type=2 ORDER BY nurse_score DESC LIMIT 0, 3");
//		while($value = DB::fetch($query)) {
//			$agent_ids[] = $value['agent_id'];
//			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
//			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
//			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
//			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
//			$recommend_list['outside'][] = $value;
//		}
//		$recommend_list['illness'] = array();
//		$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." AND nurse_type=3 ORDER BY nurse_score DESC LIMIT 0, 3");
//		while($value = DB::fetch($query)) {
//			$agent_ids[] = $value['agent_id'];
//			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
//			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
//			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
//			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
//			$recommend_list['illness'][] = $value;
//		}
//		$recommend_list['hour'] = array();
//		$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." AND nurse_type=4 ORDER BY nurse_score DESC LIMIT 0, 3");
//		while($value = DB::fetch($query)) {
//			$agent_ids[] = $value['agent_id'];
//			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
//			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
//			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
//			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
//			$recommend_list['hour'][] = $value;
//		}
		$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." ORDER BY nurse_score DESC LIMIT 0, 6");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
			$recommend_list['inside'][] = $value;
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}
		}
		foreach($super_list as $key => $value) {
			$super_list[$key]['agent_name'] = $agent_list[$value['agent_id']];
		}
		foreach($recommend_list as $type => $nurse) {
			foreach($nurse as $key => $value) {
				$recommend_list[$type][$key]['agent_name'] = $agent_list[$value['agent_id']];
			}
		}
        $query = DB::query("SELECT * FROM ".DB::table("nurse")." ORDER BY nurse_score ASC");
        while($value = DB::fetch($query)) {
            $member_list[$value['nurse_id']] = $value;
        }
//        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment')." ORDER BY comment_time DESC");
//        while($value = DB::fetch($query)) {
//            $member_ids[] = $value['member_id'];
//            $nurse_ids[] = $value['nurse_id'];
//            $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
//            $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
//            $value['nurse_areaname']=$member_list[$value['nurse_id']]['nurse_areaname'];
//            $comment_list[] = $value;
//        }
//
//        $query=DB::query("SELECT * FROM ".DB::table("nurse_book")." ORDER BY add_time DESC");
//        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." ORDER BY add_time ASC");
//        while($value=DB::fetch($query)){
//            $value['member_phone']=preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
//            $value['count']=$book_count;
//            $book_list[]=$value;
//        }
//        for($i=0;$i<count($banner_list);$i++){
//            $banner_list[$i]=SiteUrl.'/'.$banner_list[$i];
//        }
		$data = array(
		    'result_url'=>SiteUrl,
			'banner_list' => empty($banner_list) ? array() : $banner_list,
			'banner_list_url' => empty($banner_list_url) ? array() : $banner_list_url,
			'hot_list' => empty($hot_list) ? array() : $hot_list,
			'hot_list_url' => empty($hot_list_url) ? array() : $hot_list_url,
			'hotproduct_list' => empty($hotproduct_list) ? array() : $hotproduct_list,
			'hotproduct_list_url' => empty($hotproduct_list_url) ? array() : $hotproduct_list_url,
			'app_desc' => $this->setting['app_desc'],
			'adv_image' => $this->setting['adv_image'],
			'super_list' => empty($super_list) ? array() : $super_list,
			'recommend_list' => $recommend_list,
			'book_list'=>$book_list,
			'comment_list'=>$comment_list,
			'share_title' => empty($this->setting['share_title']) ? '' : $this->setting['share_title'],
			'share_desc' =>  empty($this->setting['share_desc']) ? '' : $this->setting['share_desc'],
			'share_url' => SiteUrl.'/mobile.php?act=share',
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function nurse_firstOp() {
		$error_data = (object)array();
		$district_name = empty($_POST['district_name']) ? '宿迁' : $_POST['district_name'];
		if(empty($district_name)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_name like '%".$district_name."%'");
		if(empty($district)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$district_ids = array('1', '2', '9', '22', '32', '33', '34');
		if($district['district_level'] == 1 && !in_array($district['district_id'], $district_ids)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$this->setting['app_nurse_image'] = empty($this->setting['app_nurse_image']) ? array() : unserialize($this->setting['app_nurse_image']);
		foreach($this->setting['app_nurse_image'] as $key => $value) {
			if(!empty($value)) {
				$banner_list[] = $value;	
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
		while($value = DB::fetch($query)) {
			$grade_list[$value['grade_id']] = $value;	
		}
		$nurse_field = 'nurse_id, agent_id, member_phone, nurse_name, nurse_image, nurse_type, nurse_price, nurse_days, nurse_age, nurse_education, birth_cityname, nurse_cityname, nurse_content, nurse_tags, nurse_desc, grade_id, nurse_score, nurse_viewnum, nurse_favoritenum, nurse_booknum, nurse_salenum, nurse_commentnum, work_state, nurse_time';
		$wheresql = " WHERE nurse_state=1";
		if($district['district_level'] == 1) {
			$wheresql .= " AND nurse_provinceid='".$district['district_id']."'";
		} elseif($district['district_level'] == 2) {
			$wheresql .= " AND nurse_cityid='".$district['district_id']."'";
		} elseif($district['district_level'] == 3) {
			$wheresql .= " AND nurse_areaid='".$district['district_id']."'";
		}
		$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." ORDER BY nurse_score DESC LIMIT 0, 4");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
			$super_list[] = $value;
		}
		$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='".$district['district_id']."'");
		if(empty($district_count)) {
			$inside_list = empty($this->setting['app_inside']) ? array() : unserialize($this->setting['app_inside']);
			$outside_list = empty($this->setting['app_outside']) ? array() : unserialize($this->setting['app_outside']);
			$illness_list = empty($this->setting['app_illness']) ? array() : unserialize($this->setting['app_illness']);
			$hour_list = empty($this->setting['app_hour']) ? array() : unserialize($this->setting['app_hour']);
		} else {
			$inside_list = empty($this->setting['index_inside']) ? array() : unserialize($this->setting['index_inside']);
			$outside_list = empty($this->setting['index_outside']) ? array() : unserialize($this->setting['index_outside']);
			$illness_list = empty($this->setting['index_illness']) ? array() : unserialize($this->setting['index_illness']);
			$hour_list = empty($this->setting['index_hour']) ? array() : unserialize($this->setting['index_hour']);
		}
		foreach($inside_list[$district['district_id']] as $key => $value) {
			$nurse_ids[] = intval($value);
		}
		foreach($outside_list[$district['district_id']] as $key => $value) {
			$nurse_ids[] = intval($value);
		}
		foreach($illness_list[$district['district_id']] as $key => $value) {
			$nurse_ids[] = intval($value);
		}
		foreach($hour_list[$district['district_id']] as $key => $value) {
			$nurse_ids[] = intval($value);
		}
		if(!empty($nurse_ids)) {
			$query = DB::query("SELECT $nurse_field FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."') AND nurse_state=1");
			while($value = DB::fetch($query)) {
				$agent_ids[] = $value['agent_id'];
				$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
				$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
				$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
				$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
				$nurse_list[$value['nurse_id']] = $value;
			}			
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}
		}
		foreach($super_list as $key => $value) {
			$super_list[$key]['agent_name'] = $agent_list[$value['agent_id']];
		}
		foreach($nurse_list as $key => $value) {
			$nurse_list[$key]['agent_name'] = $agent_list[$value['agent_id']];
		}
		$recommend_list['inside'] = array();
		foreach($inside_list[$district['district_id']] as $key => $value) {
			if(!empty($nurse_list[$value])) {
				$recommend_list['inside'][] = $nurse_list[$value];	
			}
		}
		$recommend_list['outside'] = array();
		foreach($outside_list[$district['district_id']] as $key => $value) {
			if(!empty($nurse_list[$value])) {
				$recommend_list['outside'][] = $nurse_list[$value];	
			}
		}
		$recommend_list['illness'] = array();
		foreach($illness_list[$district['district_id']] as $key => $value) {
			if(!empty($nurse_list[$value])) {
				$recommend_list['illness'][] = $nurse_list[$value];	
			}
		}
		$recommend_list['hour'] = array();
		foreach($hour_list[$district['district_id']] as $key => $value) {
			if(!empty($nurse_list[$value])) {
				$recommend_list['hour'][] = $nurse_list[$value];	
			}
		}
		$data = array(			
			'banner_list' => empty($banner_list) ? array() : $banner_list,
			'app_desc' => $this->setting['app_desc'],
			'adv_image' => $this->setting['adv_image'],
			'super_list' => empty($super_list) ? array() : $super_list,
			'recommend_list' => $recommend_list,
			'share_title' => $this->setting['share_title'],
			'share_desc' =>  $this->setting['share_desc'],
			'share_url' => SiteUrl.'/mobile.php?act=share',
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function nurse_searchOp() {
		$error_data = (object)array();
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$district_name = empty($_POST['district_name']) ? '宿迁' : $_POST['district_name'];
		if(empty($district_name)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_name like '%".$district_name."%'");
		if(empty($district)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$district_ids = array('1', '2', '9', '22', '32', '33', '34');
		if($district['district_level'] == 1 && !in_array($district['district_id'], $district_ids)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'未选择城市', 'data'=>$error_data)));
		}
		$query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
		while($value = DB::fetch($query)) {
			$grade_list[$value['grade_id']] = $value;	
		}
		$nurse_field = 'nurse_id, agent_id, member_phone, nurse_name, nurse_image, nurse_type, nurse_price, nurse_days, nurse_age, nurse_education, birth_cityname, nurse_cityname, nurse_content, nurse_tags, nurse_desc, grade_id, nurse_score, nurse_viewnum, nurse_favoritenum, nurse_booknum, nurse_salenum, nurse_commentnum, work_state, nurse_time';
		$wheresql = " WHERE nurse_state=1";
		if($district['district_level'] == 1) {
			$wheresql .= " AND nurse_provinceid='".$district['district_id']."'";
		} elseif($district['district_level'] == 2) {
			$wheresql .= " AND nurse_cityid='".$district['district_id']."'";
		} elseif($district['district_level'] == 3) {
			$wheresql .= " AND nurse_areaid='".$district['district_id']."'";
		}
		$nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
		if(!empty($nurse_type)) {
			$wheresql .= " AND nurse_type='$nurse_type'";
		}
		$keyword = empty($_POST['keyword']) ? '' : $_POST['keyword'];
		if(!empty($keyword)) {
			$wheresql .= " AND (nurse_name like '%".$keyword."%' OR nurse_content like '%".$keyword."%' OR nurse_special_service like '%".$keyword."%')";
		}
		$sex=empty($_POST['sex']) ? 0 : intval($_POST['sex']);
		if(!empty($sex)){
            $wheresql .= " AND nurse_sex='$sex'";
        }
		if($page == 1) {
			$this->setting['app_nurse_image'] = empty($this->setting['app_nurse_image']) ? array() : unserialize($this->setting['app_nurse_image']);
			foreach($this->setting['app_nurse_image'] as $key => $value) {
				if(!empty($value)) {
					$banner_list[] = $value;	
				}
			}
			$query = DB::query("SELECT $nurse_field FROM ".DB::table("nurse").$wheresql." ORDER BY nurse_score DESC LIMIT 0, 3");
			while($value = DB::fetch($query)) {
				$agent_ids[] = $value['agent_id'];
				$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
				$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
				$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
				$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
				$recommend_list[] = $value;
			}		
		}
		$nurse_list = array();
		$sort_field = !in_array($_POST['sort_field'], array('all','score', 'education', 'price', 'age', 'work')) ? 'all' : $_POST['sort_field'];
		$score_sort = !in_array($_POST['score_sort'], array('asc', 'desc')) ? 'desc' : $_POST['score_sort'];
		$education_sort = !in_array($_POST['education_sort'], array('asc', 'desc')) ? 'desc' : $_POST['education_sort'];
		$price_sort = !in_array($_POST['price_sort'], array('asc', 'desc')) ? 'desc' : $_POST['price_sort'];
		$age_sort = !in_array($_POST['age_sort'], array('asc', 'desc')) ? 'desc' : $_POST['age_sort'];
		$work_sort = !in_array($_POST['work_sort'], array('asc', 'desc')) ? 'desc' : $_POST['work_sort'];
		if($sort_field == 'all'){
            $ordersql = " ORDER BY nurse_score desc";
        }elseif($sort_field == 'score') {
			$ordersql = " ORDER BY nurse_score $score_sort";
		} elseif($sort_field == 'education') {
			$ordersql = " ORDER BY nurse_education $education_sort";
		} elseif($sort_field == 'price') {
			$ordersql = " ORDER BY nurse_price $price_sort";
		} elseif($sort_field == 'age') {
			$ordersql = " ORDER BY nurse_age $age_sort";
		} elseif($sort_field == 'work') {
			$ordersql = " ORDER BY work_state $work_sort";
		}
		$query = DB::query("SELECT $nurse_field FROM ".DB::table('nurse').$wheresql.$ordersql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['grade_name'] = $grade_list[$value['grade_id']]['grade_name'];
			$value['grade_icon'] = $grade_list[$value['grade_id']]['grade_icon'];
			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
			$value['nurse_time'] = date('Y-m-d H:i', $value['nurse_time']);
			$nurse_list[] = $value;	
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}
		}
		foreach($recommend_list as $key => $value) {
			$recommend_list[$key]['agent_name'] = $agent_list[$value['agent_id']];
		}
		foreach($nurse_list as $key => $value) {
			$nurse_list[$key]['agent_name'] = $agent_list[$value['agent_id']];
		}
		$data = array(
//			'banner_list' => empty($banner_list) ? array() : $banner_list,
//			'recommend_list' => empty($recommend_list) ? array() : $recommend_list,
			'nurse_list' => empty($nurse_list) ? array() : $nurse_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	function goodsOp() {
		$this->setting['app_goods_image'] = empty($this->setting['app_goods_image']) ? array() : unserialize($this->setting['app_goods_image']);
		foreach($this->setting['app_goods_image'] as $key => $value) {
			if(!empty($value)) {
				$banner_list[] = $value;	
			}
		}
		$goods_field = 'goods_id, goods_name, store_id, class_id, brand_id, type_id, goods_price, goods_original_price, goods_storage, goods_image, goods_score, goods_salenum, goods_viewnum, goods_favoritenum, goods_commentnum, goods_add_time';
		$query = DB::query("SELECT * FROM ".DB::table('class')." WHERE class_type='goods' ORDER BY class_sort ASC");
		while($value = DB::fetch($query)) {
			$class_list[$value['class_id']] = $value;
		}
		$this->setting['index_goods'] = empty($this->setting['index_goods']) ? array() : unserialize($this->setting['index_goods']);
		if(!empty($this->setting['index_goods'])) {
			$query = DB::query("SELECT $goods_field FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $this->setting['index_goods'])."') AND goods_show=1 AND goods_state=1");
			while($value = DB::fetch($query)) {
				$store_ids[] = $value['store_id'];
				$brand_ids[] = $value['brand_id'];
				$type_ids[] = $value['type_id'];
				$value['class_name'] = $class_list[$value['class_id']]['class_name'];
				$value['goods_add_time'] = date('Y-m-d H:i', $value['goods_add_time']);
				$recommend_goods_list[$value['goods_id']] = $value;
			}		
		}
		foreach($this->setting['index_goods'] as $key => $value) {
			if(!empty($recommend_goods_list[$value])) {
				$recommend_list[] = $recommend_goods_list[$value];
			}
		}
		$query = DB::query("SELECT $goods_field FROM ".DB::table('goods')." WHERE goods_promotion_type=1 AND goods_show=1 AND goods_state=1 ORDER BY goods_add_time DESC");
		while($value = DB::fetch($query)) {
			$store_ids[] = $value['store_id'];
			$brand_ids[] = $value['brand_id'];
			$type_ids[] = $value['type_id'];
			$value['class_name'] = $class_list[$value['class_id']]['class_name'];
			$value['goods_add_time'] = date('Y-m-d H:i', $value['goods_add_time']);
			$cheap_list[] = $value;
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		if(!empty($brand_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('brand')." WHERE brand_id in ('".implode("','", $brand_ids)."')");
			while($value = DB::fetch($query)) {
				$brand_list[$value['brand_id']] = $value['brand_name'];
			}
		}
		if(!empty($type_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('type')." WHERE type_id in ('".implode("','", $type_ids)."')");
			while($value = DB::fetch($query)) {
				$type_list[$value['type_id']] = $value['type_name'];
			}
		}
		foreach($recommend_list as $key => $value) {
			$recommend_list[$key]['store_name'] = $store_list[$value['store_id']];
			$recommend_list[$key]['brand_name'] = $brand_list[$value['brand_id']];
			$recommend_list[$key]['type_name'] = $type_list[$value['type_id']];
		}
		foreach($cheap_list as $key => $value) {
			$cheap_list[$key]['store_name'] = $store_list[$value['store_id']];
			$cheap_list[$key]['brand_name'] = $brand_list[$value['brand_id']];
			$cheap_list[$key]['type_name'] = $type_list[$value['type_id']];
		}
		$data = array(
			'banner_list' => empty($banner_list) ? array() : $banner_list,
			'recommend_list' => empty($recommend_list) ? array() : $recommend_list,
			'cheap_list' => empty($cheap_list) ? array() : $cheap_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function pensionOp() {
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$pension_field = 'pension_id, pension_name, pension_type, pension_nature, pension_person, pension_bed, pension_cover, pension_price, pension_phone, pension_image, pension_areainfo, pension_address, pension_summary, pension_scale, pension_score, pension_viewnum, pension_salenum, pension_commentnum, pension_time';
		if($page == 1) {
			$this->setting['app_pension_image'] = empty($this->setting['app_pension_image']) ? array() : unserialize($this->setting['app_pension_image']);
			foreach($this->setting['app_pension_image'] as $key => $value) {
				if(!empty($value)) {
					$banner_list[] = $value;	
				}
			}
			$this->setting['index_pension'] = empty($this->setting['index_pension']) ? array() : unserialize($this->setting['index_pension']);
			if(!empty($this->setting['index_pension'])) {
				$query = DB::query("SELECT $pension_field FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $this->setting['index_pension'])."') AND pension_state=1");
				while($value = DB::fetch($query)) {
					$value['pension_time'] = date('Y-m-d H:i', $value['pension_time']);
					$recommend_pension_list[$value['pension_id']] = $value;
				}			
			}
			foreach($this->setting['index_pension'] as $key => $value) {
				if(!empty($recommend_pension_list[$value])) {
					$recommend_list[] = $recommend_pension_list[$value];	
				}
			}
		}
		$wheresql = " WHERE pension_state=1";
		$query = DB::query("SELECT $pension_field FROM ".DB::table('pension').$wheresql." ORDER BY pension_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {	
			$pension_ids[] = $value['pension_id'];
			$value['pension_time'] = date('Y-m-d H:i', $value['pension_time']);	
			$pension_list[] = $value;	
		}
		if(!empty($pension_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('pension_room')." WHERE pension_id in ('".implode("','", $pension_ids)."')");
			while($value = DB::fetch($query)) {
				$value['room_image_more'] = empty($value['room_image_more']) ? array() : unserialize($value['room_image_more']);
				$value['room_support'] = empty($value['room_support']) ? array() : unserialize($value['room_support']);
				$room_list[$value['pension_id']][] = $value;
			}
		}
		foreach($pension_list as $key => $value) {
			$pension_list[$key]['pension_room'] = $room_list[$value['pension_id']];
		}
		$data = array(
			'banner_list' => empty($banner_list) ? array() : $banner_list,
			'recommend_list' => empty($recommend_list) ? array() : $recommend_list,
			'pension_list' => empty($pension_list) ? array() : $pension_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>