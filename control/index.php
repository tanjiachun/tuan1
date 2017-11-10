<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class indexControl extends BaseHomeControl {
	public function indexOp() {
        $choose_time=time()-86400;
        $query=DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE add_time <='".$choose_time."' ORDER BY add_time DESC");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE add_time <='".$choose_time."' ORDER BY add_time DESC");
	    while($value=DB::fetch($query)){
            $value['member_phone']=preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
            $value['count']=$book_count;
            $book_list[]=$value;
        }

        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment')." ORDER BY comment_time DESC");
        while($value = DB::fetch($query)) {
            $member_ids[] = $value['member_id'];
            $nurse_ids[] = $value['nurse_id'];
            $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
            $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
            $comment_list[] = $value;
        }
        if(!empty($member_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $value['nurse_areaname']=$value['nurse_areaname'];
                $member_list[$value['nurse_id']] = $value;
            }
        }
		$nurse_count = 0;
//        $this->member['member_phone']=preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $this->member['member_phone']);
		if($this->district['district_level'] == 1) {
			$nurse_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE nurse_state=1 AND nurse_provinceid='".$this->district['district_id']."'");
		} elseif($this->district['district_level'] == 2) {
			$nurse_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE nurse_state=1 AND nurse_cityid='".$this->district['district_id']."'");
		}elseif($this->district['district_level']==3){
            $nurse_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE nurse_state=1 AND nurse_areaid='".$this->district['district_id']."'");
        }
		$mallmessage = dgetcookie('mallmessage');
		if(empty($mallmessage) && !empty($this->member_id)) {
			$message = DB::fetch_first("SELECT * FROM ".DB::table('message')." WHERE member_id='$this->member_id' AND is_read=0");
			if(!empty($message)) {
				DB::update('message', array('is_read'=>1), array('message_id'=>$message['message_id']));
			}
		}
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
		$this->setting['banner_image'] = empty($this->setting['banner_image']) ? array() : unserialize($this->setting['banner_image']);
        $this->setting['banner_url'] = empty($this->setting['banner_url']) ? array() : unserialize($this->setting['banner_url']);
        $this->setting['nav_image'] = empty($this->setting['nav_image']) ? array() : unserialize($this->setting['nav_image']);
        $this->setting['nav_url'] = empty($this->setting['nav_url']) ? array() : unserialize($this->setting['nav_url']);
        $this->setting['nav_name'] = empty($this->setting['nav_name']) ? array() : unserialize($this->setting['nav_name']);
        $this->setting['hot_image'] = empty($this->setting['hot_image']) ? array() : unserialize($this->setting['hot_image']);
        $this->setting['hot_url'] = empty($this->setting['hot_url']) ? array() : unserialize($this->setting['hot_url']);
		$this->setting['service_qq'] = empty($this->setting['service_qq']) ? array() : unserialize($this->setting['service_qq']);
		$query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
		while($value = DB::fetch($query)) {
			$grade_list[$value['grade_id']] = $value;	
		}
		if($this->setting['index_style'] == 0) {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$this->district['district_id']."' ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$district_list[] = $value;	
			}
			$page = 1;$perpage = 20;$start = 0;
			$wheresql = " WHERE nurse_state=1";
			if($this->district['district_level'] == 1) {
				$wheresql .= " AND nurse_provinceid='".$this->district['district_id']."'";
			} elseif($this->district['district_level'] == 2) {
				$wheresql .= " AND nurse_cityid='".$this->district['district_id']."'";
			}elseif ($this->district['district_level']==3){
                $wheresql .= " AND nurse_areaid='".$this->district['district_id']."'";
            }
			$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
			$query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." ORDER BY nurse_time DESC LIMIT $start, $perpage");
			while($value = DB::fetch($query)) {
				$agent_ids[] = $value['agent_id'];
				$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
				$value['comment_score'] = priceformat($value['nurse_score']/$value['nurse_commentnum']);
				$nurse_list[] = $value;	
			}
			if(!empty($agent_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
				while($value = DB::fetch($query)) {
					$agent_list[$value['agent_id']] = $value['agent_name'];
				}
			}
			$multi = multi($count, $perpage, $page, '', 'selectnurse');
			$maxpage = ceil($count/$perpage);
			$curmodule = 'home';
			$bodyclass = '';
			include(template('index_first'));
		} else {	
			$query = DB::query("SELECT * FROM ".DB::table("class")." WHERE class_type='goods' ORDER BY class_sort ASC");
			while($value = DB::fetch($query)) {
				$class_list[] = $value;	
			}
			$this->setting['index_inside'] = empty($this->setting['index_inside']) ? array() : unserialize($this->setting['index_inside']);
			$this->setting['index_outside'] = empty($this->setting['index_outside']) ? array() : unserialize($this->setting['index_outside']);
			$this->setting['index_illness'] = empty($this->setting['index_illness']) ? array() : unserialize($this->setting['index_illness']);
			$this->setting['index_hour'] = empty($this->setting['index_hour']) ? array() : unserialize($this->setting['index_hour']);
			foreach($this->setting['index_inside'][$this->district['district_id']] as $key => $value) {
				$nurse_ids[] = intval($value);
			}
			foreach($this->setting['index_outside'][$this->district['district_id']] as $key => $value) {
				$nurse_ids[] = intval($value);
			}
			foreach($this->setting['index_illness'][$this->district['district_id']] as $key => $value) {
				$nurse_ids[] = intval($value);
			}
			foreach($this->setting['index_hour'][$this->district['district_id']] as $key => $value) {
				$nurse_ids[] = intval($value);
			}
			if(!empty($nurse_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."') AND nurse_state=1");
				while($value = DB::fetch($query)) {
					$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
					$nurse_list[$value['nurse_id']] = $value;
				}			
			}
			foreach($this->setting['index_inside'][$this->district['district_id']] as $key => $value) {
				if(!empty($nurse_list[$value])) {
					if(empty($key)) {
						$first_nurse = $nurse_list[$value];
					} else {
						$index_inside_list[] = $nurse_list[$value];
					}
				}
			}
			foreach($this->setting['index_outside'][$this->district['district_id']] as $key => $value) {
				if(!empty($nurse_list[$value])) {
					$index_outside_list[] = $nurse_list[$value];
				}
			}
			foreach($this->setting['index_illness'][$this->district['district_id']] as $key => $value) {
				if(!empty($nurse_list[$value])) {
					$index_illness_list[] = $nurse_list[$value];
				}
			}
			foreach($this->setting['index_hour'][$this->district['district_id']] as $key => $value) {
				if(!empty($nurse_list[$value])) {
					$index_hour_list[] = $nurse_list[$value];
				}
			}
			$this->setting['index_goods'] = empty($this->setting['index_goods']) ? array() : unserialize($this->setting['index_goods']);
			if(!empty($this->setting['index_goods'])) {
				$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $this->setting['index_goods'])."') AND goods_show=1 AND goods_state=1");
				while($value = DB::fetch($query)) {
					$goods_list[$value['goods_id']] = $value;
				}			
			}
			foreach($this->setting['index_goods'] as $key => $value) {
				if(!empty($goods_list[$value])) {
					$i = floor($key/6);
					$j = floor($key/3);
					$recommend_goods_list[$i][$j][] = $goods_list[$value];	
				}
			}
			$this->setting['index_pension'] = empty($this->setting['index_pension']) ? array() : unserialize($this->setting['index_pension']);
			if(!empty($this->setting['index_pension'])) {
				$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $this->setting['index_pension'])."') AND pension_state=1");
				while($value = DB::fetch($query)) {
					$pension_list[$value['pension_id']] = $value;
				}			
			}
			foreach($this->setting['index_pension'] as $key => $value) {
				if(!empty($pension_list[$value])) {
					$recommend_pension_list[] = $pension_list[$value];	
				}
			}		
			$curmodule = 'home';
			$bodyclass = '';
			include(template('index'));
		}
	}
	public function login_moduleOp(){
	    $member_id=empty($_GET['member_id']) ? 0 : intval($_GET['member_id']);
	    $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
        $card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member['member_score']." ORDER BY card_predeposit DESC");
	    $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$member_id' AND book_state=10");
	    $comment_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE member_id='$member_id'");
	    $refund_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$member_id' AND refund_state=1 AND refund_time=0");
	    $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$member_id'");
	    $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$member_id'");
	    $agent_book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='".$agent["agent_id"]."' AND book_state=10");
        $agent_question_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='".$agent["agent_id"]."' AND answer_content=''");
	    $agent_audit_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('staff_audit')." WHERE agent_id='".$agent["agent_id"]."' AND agent_id='".$agent["agent_id"]."' AND nurse_state=1 AND agent_audit_state=0 ");
        $login_module_states_html='';
        if(!empty($agent)){
            $login_module_states_html.='<div class="agent_enter">';
            $login_module_states_html.='<span></span>';
            $login_module_states_html.='<a href="index.php?act=agent_center">进入机构管理中心</a>';
            $login_module_states_html.='<img src="templates/images/enter.png" alt="">';
            $login_module_states_html.='</div>';
            $login_module_states_html.='<div class="agent_message">';
            $login_module_states_html.='<div class="left agent_message_set">';
            $login_module_states_html.='<p>(<span>'.$agent_question_count.'</span>)</p>';
            $login_module_states_html.='<p><a href="index.php?act=agent_question">机构问答</a></p>';
            $login_module_states_html.='</div>';
            $login_module_states_html.='<div class="left agent_message_set">';
            $login_module_states_html.='<p>(<span>'.$agent_book_count.'</span>)</p>';
            $login_module_states_html.='<p><a href="index.php?act=agent_book">未付款订单</a></p>';
            $login_module_states_html.='</div>';
            $login_module_states_html.='<div class="left agent_message_set">';
            $login_module_states_html.='<p>(<span>'.$agent_audit_count.'</span>)</p>';
            $login_module_states_html.='<p><a href="index.php?act=agent_nurse_audit">新员工审核</a></p>';
            $login_module_states_html.='</div>';
            $login_module_states_html.='</div>';
        }else{
            $login_module_states_html.='<div class="shop_enter">';
            $login_module_states_html.='<span></span>';
            $login_module_states_html.='<a href="index.php?act=agent">机&nbsp;&nbsp;构&nbsp;&nbsp;入&nbsp;&nbsp;驻</a>';
            $login_module_states_html.='<img src="templates/images/enter.png" alt="">';
            $login_module_states_html.='</div>';
        }
//	    if(empty($agent)&&!empty($nurse)){
//	        $login_module_states_html.='<div class="nurse_enter">';
//	        $login_module_states_html.='<span></span>';
//	        $login_module_states_html.='<a href="index.php?act=nurse_center">进入家政人员管理平台</a>';
//	        $login_module_states_html.='<img src="templates/images/enter.png" alt="">';
//	        $login_module_states_html.='</div>';
//	        $login_module_states_html.='<div class="myStateSet"><span>我的状态设置</span></div>';
//	        $login_module_states_html.='<div class="login_nurse_set">';
//	        if($nurse['state_cideci']==0||$nurse['state_cideci']==1){
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span class="stateOn" data="1">待业中</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="2">已在岗</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="3">假期中</span>';
//                $login_module_states_html.='</div>';
//            }else if($nurse['state_cideci']==2){
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="1">待业中</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span class="stateOn" data="2">已在岗</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="3">假期中</span>';
//                $login_module_states_html.='</div>';
//            }else if($nurse['state_cideci']==3){
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="1">待业中</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="2">已在岗</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span class="stateOn" data="3">假期中</span>';
//                $login_module_states_html.='</div>';
//            }else{
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="1">待业中</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="2">已在岗</span>';
//                $login_module_states_html.='</div>';
//                $login_module_states_html.='<div class="login_nurseState_set">';
//                $login_module_states_html.='<span data="3">假期中</span>';
//                $login_module_states_html.='</div>';
//            }
//	        $login_module_states_html.='</div>';
//        }else if(empty($nurse)&&!empty($agent)){
//            $login_module_states_html.='<div class="agent_enter">';
//            $login_module_states_html.='<span></span>';
//            $login_module_states_html.='<a href="index.php?act=agent_center">进入机构管理中心</a>';
//            $login_module_states_html.='<img src="templates/images/enter.png" alt="">';
//            $login_module_states_html.='</div>';
//            $login_module_states_html.='<div class="agent_message">';
//            $login_module_states_html.='<div class="left agent_message_set">';
//            $login_module_states_html.='<p>(<span>25</span>)</p>';
//            $login_module_states_html.='<p><a>机构消息</a></p>';
//            $login_module_states_html.='</div>';
//            $login_module_states_html.='<div class="left agent_message_set">';
//            $login_module_states_html.='<p>(<span>'.$agent_book_count.'</span>)</p>';
//            $login_module_states_html.='<p><a href="index.php?act=agent_book">未付款订单</a></p>';
//            $login_module_states_html.='</div>';
//            $login_module_states_html.='<div class="left agent_message_set">';
//            $login_module_states_html.='<p>(<span>6</span>)</p>';
//            $login_module_states_html.='<p><a href="index.php?act=agent_nurse_audit">新员工审核</a></p>';
//            $login_module_states_html.='</div>';
//            $login_module_states_html.='</div>';
//        }else{
////            $login_module_states_html.='<div class="apply_enter">';
////            $login_module_states_html.='<span></span>';
////            $login_module_states_html.='<a href="index.php?act=nurse&op=register">申请家政工作</a>';
////            $login_module_states_html.='<img src="templates/images/enter.png" alt="">';
////            $login_module_states_html.='</div>';
//            $login_module_states_html.='<div class="shop_enter">';
//            $login_module_states_html.='<span></span>';
//            $login_module_states_html.='<a href="index.php?act=agent">机&nbsp;&nbsp;构&nbsp;&nbsp;入&nbsp;&nbsp;驻</a>';
//            $login_module_states_html.='<img src="templates/images/enter.png" alt="">';
//            $login_module_states_html.='</div>';
//        }
        $login_module_state_html='';
        $login_module_state_html.='<div class="left state_num">';
        $login_module_state_html.='<p><span>'.$book_count.'</span></p>';
        $login_module_state_html.='<p><a href="index.php?act=member_book&state=pending">待付款</a></p>';
        $login_module_state_html.='</div>';
        $login_module_state_html.='<div class="left state_num">';
        $login_module_state_html.='<p><span>0</span></p>';
        $login_module_state_html.='<p><a href="index.php?act=member_book&state=duty">待上岗</a></p>';
        $login_module_state_html.='</div>';
        $login_module_state_html.='<div class="left state_num">';
        $login_module_state_html.='<p><span>'.$comment_count.'</span></p>';
        $login_module_state_html.='<p><a href="index.php?act=member_comment">评价</a></p>';
        $login_module_state_html.='</div>';
        $login_module_state_html.='<div class="left state_num">';
        $login_module_state_html.='<p><span>'.$refund_count.'</span></p>';
        $login_module_state_html.='<p><a href="index.php?act=member_book&state=payment">退款</a></p>';
        $login_module_state_html.='</div>';

        exit(json_encode(array('card'=>$card,'login_module_state_html'=>$login_module_state_html,'login_module_states_html'=>$login_module_states_html)));
    }
    public function login_setStateOp(){
        $member_id=empty($_GET['member_id']) ? 0 : intval($_GET['member_id']);
        $state_cideci=empty($_GET['state_cideci']) ? 0 : intval($_GET['state_cideci']);
        if(!empty($member_id)){
            DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=$state_cideci WHERE member_id=$member_id");
            exit(json_encode(array('done'=>'true')));
        }else{

        }
    }

	public function nurseOp() {
		$query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
		while($value = DB::fetch($query)) {
			$grade_list[$value['grade_id']] = $value;
		}
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$this->district['district_id']."' ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$district_list[] = $value;
		}
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
		$nurse_type = in_array($_GET['nurse_type']) ? 0 : intval($_GET['nurse_type']);
        $service_type = empty($_GET['service_type']) ? '' : $_GET['service_type'];
        $keyword = empty($_GET['keyword']) ? '' : $_GET['keyword'];
		$page = 1;$perpage = 20;$start = 0;
        $wheresql='';
		$wheresql = " WHERE nurse_state=1";
		if($this->district['district_level'] == 1) {
			$wheresql .= " AND nurse_provinceid='".$this->district['district_id']."'";
		} elseif($this->district['district_level'] == 2) {
			$wheresql .= " AND nurse_cityid='".$this->district['district_id']."'";
		}
		if(!empty($nurse_type)) {
			$wheresql .= " AND nurse_type='$nurse_type'";
		}
		if(!empty($service_type)){
            $wheresql .= " AND service_type like '%".$service_type."%'";
        }
        if(!empty($keyword)){
            $wheresql .= " AND nurse_content like '%".$keyword."%' OR nurse_special_service like '%".$keyword."%'";
        }
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." ORDER BY nurse_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
			$value['comment_score'] = priceformat($value['nurse_score']/$value['nurse_commentnum']);
			$nurse_list[] = $value;
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}
		}
		$multi = multi($count, $perpage, $page, '', 'selectnurse');
		$multiTop=multiTop($count, $perpage, $page, '', 'selectnurse');
		$maxpage = ceil($count/$perpage);
		$curmodule = 'home';
		$bodyclass = '';
		include(template('index_nurse'));
	}

	public function nurse_searchOp() {
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
		$query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
		while($value = DB::fetch($query)) {
			$grade_list[$value['grade_id']] = $value;	
		}
		$district_id = empty($_GET['district_id']) ? 0 : intval($_GET['district_id']);
		$keyword = empty($_GET['keyword']) ? '' : $_GET['keyword'];
        $service_type = empty($_GET['service_type']) ? '' : $_GET['service_type'];
		$nurse_type = empty($_GET['nurse_type']) ? 0 : intval($_GET['nurse_type']);
		$nurse_education = empty($_GET['nurse_education']) ? '' : $_GET['nurse_education'];
		$nurse_price = empty($_GET['nurse_price']) ? '' : $_GET['nurse_price'];
		$nurse_age = empty($_GET['nurse_age']) ? '' : $_GET['nurse_age'];
		$grade_id = empty($_GET['grade_id']) ? 0 : intval($_GET['grade_id']);
		$sort_field = !in_array($_GET['sort_field'], array('time', 'education', 'price', 'age', 'new')) ? 'time' : $_GET['sort_field'];
		$time_sort = !in_array($_GET['time_sort'], array('asc', 'desc')) ? 'desc' : $_GET['time_sort'];
		$education_sort = !in_array($_GET['education_sort'], array('asc', 'desc')) ? 'desc' : $_GET['education_sort'];
		$price_sort = !in_array($_GET['price_sort'], array('asc', 'desc')) ? 'desc' : $_GET['price_sort'];
		$age_sort = !in_array($_GET['age_sort'], array('asc', 'desc')) ? 'desc' : $_GET['age_sort'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE nurse_state=1";
		if(!empty($district_id)) {
			$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$district_id'");
			if($district['district_level'] == 2) {
				$wheresql .= " AND nurse_cityid='$district_id'";
			} elseif($district['district_level'] == 3) {
				$wheresql .= " AND nurse_areaid='$district_id'";
			}
		} else {
			if($this->district['district_level'] == 1) {
				$wheresql .= " AND nurse_provinceid='".$this->district['district_id']."'";
			} elseif($this->district['district_level'] == 2) {
				$wheresql .= " AND nurse_cityid='".$this->district['district_id']."'";
			}	
		}
		if(!empty($keyword)) {
			$wheresql .= " AND nurse_content like '%".$keyword."%'";
		}
		if(!empty($nurse_type)) {
			$wheresql .= " AND nurse_type='$nurse_type'";
		}
        if(!empty($service_type)) {
            $wheresql .= " AND service_type like '%".$service_type."%'";
        }
		if(!empty($nurse_education)) {
			$education_array = explode('-', $nurse_education);
			if(!empty($education_array[0])) {
				$wheresql .= " AND nurse_education>='$education_array[0]'";
			}
			if(!empty($education_array[1])) {
				$wheresql .= " AND nurse_education<='$education_array[1]'";
			}
		}
		if(!empty($nurse_price)) {
			$price_array = explode('-', $nurse_price);
			if(!empty($price_array[0])) {
				$wheresql .= " AND nurse_price>='$price_array[0]'";
			}
			if(!empty($price_array[1])) {
				$wheresql .= " AND nurse_price<='$price_array[1]'";
			}
		}
		if(!empty($nurse_age)) {
			$age_array = explode('-', $nurse_age);
			if(!empty($age_array[0])) {
				$wheresql .= " AND nurse_age>='$age_array[0]'";
			}
			if(!empty($age_array[1])) {
				$wheresql .= " AND nurse_age<='$age_array[1]'";
			}
		}
		if(!empty($grade_id)) {
			$wheresql .= " AND grade_id='$grade_id'";
		}
		if($sort_field == 'time') {
			$ordersql = " ORDER BY nurse_time $time_sort";
		} elseif($sort_field == 'education') {
			$ordersql = " ORDER BY nurse_education $education_sort";
		} elseif($sort_field == 'price') {
			$ordersql = " ORDER BY nurse_price $price_sort";
		} elseif($sort_field == 'age') {
			$ordersql = " ORDER BY nurse_age $age_sort";
		} elseif($sort_field == 'new') {
			$ordersql = " ORDER BY nurse_time DESC";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql.$ordersql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
			$value['comment_score'] = priceformat($value['nurse_score']/$value['nurse_commentnum']);
			$nurse_list[] = $value;	
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}
		}
		$multi = multi($count, $perpage, $page, '', 'selectnurse');
		$maxpage = ceil($count/$perpage);
		$nurse_html = '';
		if(empty($nurse_list)) {
       		$nurse_html .= '<div class="no-shop"><dl><dt></dt><dd><p>抱歉，没有找到符合条件的看护人员</p><p>您可以适当减少筛选条件</p></dd></dl></div>';
        } else {
            $nurse_html .= '<ul>';
            foreach($nurse_list as $key => $value) {
				$nurse_html .= '<li>';
               	$nurse_html .= '<div class="nurse-img">';
               	if($value['nurse_image']==''){
                    $nurse_html .= '<a target="_blank" href="index.php?act=nurse&nurse_id='.$value['nurse_id'].'"><img src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></a>';
                } else {
                    $nurse_html .= '<a target="_blank" href="index.php?act=nurse&nurse_id='.$value['nurse_id'].'"><img src="'.$value['nurse_image'].'"></a>';
                }
                $nurse_html.='<div class="nurse-salary">';
                if($value['nurse_type'] == 3) {
                    $nurse_html.='<span class="nurse-price">¥<strong>'.$value['nurse_price'].'</strong>/时</span>';
                }else if ($value['nurse_type']==4){
                    $nurse_html.='<span class="nurse-price">¥<strong>'.$value['nurse_price'].'</strong>/平方</span>';
                }
                else{
                    $nurse_html.='<span class="nurse-price">¥<strong>'.$value['nurse_price'].'</strong>/月</span>';
                }
                $nurse_html.='</div>';
                $nurse_html.='</div>';
                $nurse_html .='<div class="clear"></div>';
               	$nurse_html.='<div class="nurse-type">';
               	$nurse_html.='<a target="_blank" href="index.php?act=nurse&nurse_id='.$value['nurse_id'].'" class="nurse-name">'.$value['nurse_nickname'].'</a><span>'.$value['service_type'].'</span>';
                $nurse_html.='<span class="nurse_content">'.$value['nurse_content'].'</span>';
               	$nurse_html.='</div>';
               	$nurse_html.='<div class="nurse-evaluate">';
               	$nurse_html.='<span class="level-box"><a href="index.php?act=nurse_trust_grade&nurse_id='.$value['nurse_id'].'"><img src="'.$grade_list[$value['grade_id']]['grade_icon'].'" alt=""></a></span>';
               	$nurse_html.='<span class="nurse-score">';
                $nurse_html.='评价 ('.$value['nurse_commentnum'].')';
               	$nurse_html.='</span>';
               	$nurse_html.=' <span class="book_count right">';
               	$nurse_html.=''.$value['nurse_salenum'].'人已付款';
               	$nurse_html.='</span>';
               	$nurse_html.='</div>';
               	$nurse_html.='<div class="nurse-certified">';
               	if($value['agent_id'] == 0){
               	    $nurse_html.='<span style="text-decoration: underline;">个人</span>';
                }else{
                    $nurse_html.=' <span><a style="text-decoration: underline;" href="index.php?act=agent_show&agent_id='.$value['agent_id'].'">'.$agent_list[$value['agent_id']].'</a></span>';
                }
               	$nurse_html.='</div>';
                $nurse_html .= '</li>';
            }
            $nurse_html .= '</ul>';
        }
		$nurse_multi_html = '';
		$nurse_multi_html .= $multi;
		$multiTop_html='';
		$multiTop=multiTop($count, $perpage, $page, '', 'selectnurse');
		$multiTop_html.=$multiTop;
		exit(json_encode(array('done'=>'true','count'=>$count,'multiTop_html'=>$multiTop_html, 'nurse_page_html'=>$nurse_page_html, 'nurse_count_html'=>$nurse_count_html, 'nurse_html'=>$nurse_html, 'nurse_multi_html'=>$nurse_multi_html)));
	}
	
	function goodsOp() {
		$class_id = empty($_GET['class_id']) ? 0 : intval($_GET['class_id']);
		$query = DB::query("SELECT * FROM ".DB::table('class')." WHERE class_type='goods' ORDER BY class_sort ASC");
		while($value = DB::fetch($query)) {
			$class_id = empty($class_id) ? $value['class_id'] : $class_id;
			$class_list[] = $value;
		}
		$brand_id = empty($_GET['brand_id']) ? 0 : intval($_GET['brand_id']);
		$query = DB::query("SELECT * FROM ".DB::table('brand')." WHERE class_id='$class_id' ORDER BY brand_sort ASC");
		while($value = DB::fetch($query)) {
			if($value['brand_id'] == $brand_id) {
				$brand_name = $value['brand_name'];	
			}
			$brand_list[] = $value;
		}
		$query = DB::query("SELECT * FROM ".DB::table('type')." WHERE class_id='$class_id' ORDER BY type_sort ASC");
		while($value = DB::fetch($query)) {
			$type_list[] = $value;
		}
		$page = 1;$perpage = 12;$start = 0;
		$wheresql = " WHERE class_id='$class_id' AND goods_state=1 AND goods_show=1";
		if(!empty($brand_id)) {
			$wheresql .= " AND brand_id='$brand_id'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('goods').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('goods').$wheresql." ORDER BY goods_add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$store_ids[] = $value['store_id'];			
			$goods_list[] = $value;	
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		$multi = multi($count, $perpage, $page, '', 'selectgoods');
		$maxpage = ceil($count/$perpage);
		$curmodule = 'home';
		$bodyclass = '';
		include(template('index_goods'));
	}
	
	function goods_searchOp() {
		$class_id = empty($_GET['class_id']) ? 0 : intval($_GET['class_id']);
		if(empty($class_id)) {
			$class = DB::fetch_first("SELECT * FROM ".DB::table('class')." WHERE class_type='goods' ORDER BY class_sort ASC");
			$class_id = $class['class_id'];
		}
		$type_id = empty($_GET['type_id']) ? 0 : intval($_GET['type_id']);
		$brand_id = empty($_GET['brand_id']) ? 0 : intval($_GET['brand_id']);
		$goods_price = empty($_GET['goods_price']) ? '' : $_GET['goods_price'];
		$sort_field = !in_array($_GET['sort_field'], array('time', 'view', 'price')) ? 'time' : $_GET['sort_field'];
		$time_sort = !in_array($_GET['time_sort'], array('asc', 'desc')) ? 'desc' : $_GET['time_sort'];
		$view_sort = !in_array($_GET['view_sort'], array('asc', 'desc')) ? 'desc' : $_GET['view_sort'];
		$price_sort = !in_array($_GET['price_sort'], array('asc', 'desc')) ? 'desc' : $_GET['price_sort'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 12;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE class_id='$class_id' AND goods_state=1 AND goods_show=1";
		if(!empty($type_id)) {
			$wheresql .= " AND type_id='$type_id'";
		}
		if(!empty($brand_id)) {
			$wheresql .= " AND brand_id='$brand_id'";
		}
		if(!empty($goods_price)) {
			$price_array = explode('-', $goods_price);
			if(!empty($price_array[0])) {
				$wheresql .= " AND goods_price>='$price_array[0]'";
			}
			if(!empty($price_array[1])) {
				$wheresql .= " AND goods_price<='$price_array[1]'";
			}
		}
		if($sort_field == 'time') {
			$ordersql = " ORDER BY goods_add_time $time_sort";
		} elseif($sort_field == 'price') {
			$ordersql = " ORDER BY goods_price $price_sort";
		} elseif($sort_field == 'view') {
			$ordersql = " ORDER BY goods_viewnum $view_sort";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('goods').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('goods').$wheresql.$ordersql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$store_ids[] = $value['store_id'];
			$goods_list[] = $value;	
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		$multi = multi($count, $perpage, $page, '', 'selectgoods');
		$maxpage = ceil($count/$perpage);
		$goods_page_html = '';
		$goods_page_html .= '<span class="fp-text"><b>'.$page.'</b><em>/</em><i>'.$maxpage.'</i></span>';
		if($page == 1) {
        	$goods_page_html .= '<a class="fp-prev disabled" href="javascript:;">&lt;</a>';
        } else {
			$goods_page_html .= '<a class="fp-prev" href="javascript:;" onclick="selectgoods(this, \'page\', \''.($page-1).'\');">&lt;</a>';
		}
		if($page == $maxpage) {
			$goods_page_html .= '<a class="fp-next disabled" href="javascript:;">&gt;</a>';
		} else {
			$goods_page_html .= '<a class="fp-next" href="javascript:;" onclick="selectgoods(this, \'page\', \''.($page+1).'\');">&gt;</a>';
		}
		$goods_count_html = '';
		$goods_count_html .= '共<span id="J_resCount" class="num">'.$count.'</span>件商品';
		$goods_html = '';
		if(empty($goods_list)) {
       		$goods_html .= '<div class="no-shop"><dl><dt></dt><dd><p>抱歉，没有找到符合条件的商品</p><p>您可以适当减少筛选条件</p></dd></dl></div>';
        } else {
            $goods_html .= '<ul class="clearfix">';
            foreach($goods_list as $key => $value) {
            	$goods_html .= '<li class="gl-item">';
               	$goods_html .= '<div class="gl-item-img">';
                $goods_html .= '<a href="index.php?act=goods&goods_id='.$value['goods_id'].'"><img src="'.$value['goods_image'].'"></a>';
                $goods_html .= '</div>';
                $goods_html .= '<div class="p-price">';
                $goods_html .= '<strong><em>￥</em><i>'.$value['goods_price'].'</i></strong> ';
                if($value['goods_original_price'] > 0) {
                	$goods_html .= '<del><em>￥</em><i>'.$value['goods_original_price'].'</i></del>';
                }
                $goods_html .= '</div>';
                $goods_html .= '<div class="p-name">';
                $goods_html .= '<a href="index.php?act=goods&goods_id='.$value['goods_id'].'">'.$value['goods_name'].'</a>';
                $goods_html .= '</div>';
                $goods_html .= '<div class="p-commit">已有'.$value['goods_salenum'].'人购买</div>';
                $goods_html .= '<div class="p-shop"><em class="self-support">'.$store_list[$value['store_id']].'</em></div>';
                $goods_html .= '</li>';
            }
            $goods_html .= '</ul>';
        }
		$goods_html .= $multi;
		exit(json_encode(array('done'=>'true', 'goods_page_html'=>$goods_page_html, 'goods_count_html'=>$goods_count_html, 'goods_html'=>$goods_html)));
	}
	
	function pensionOp() {
		$district_id = empty($_GET['district_id']) ? 0 : intval($_GET['district_id']);
		if(!empty($district_id)) {
			$district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$district_id'");
			$district_id = empty($district_count) ? $this->district['district_id'] : $district_id;
		} else {
			$district_id = $this->district['district_id'];
		}
		$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$district_id'");	
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$district_id' ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$district_list[] = $value;	
		}
		$page = 1;$perpage = 6;$start = 0;
		$wheresql = " WHERE pension_state=1";
		if($district['district_level'] == 1) {
			$wheresql .= " AND pension_provinceid='".$district['district_id']."'";
		} elseif($district['district_level'] == 2) {
			$wheresql .= " AND pension_cityid='".$district['district_id']."'";
		}
		$type_array = array('1'=>'养老产业园', '2'=>'老年公寓', '3'=>'护理院', '4'=>'托老所', '5'=>'养老院', '6'=>'敬老院');
		$pension_type = in_array($_GET['pension_type']) ? 0 : intval($_GET['pension_type']);
		if(!empty($pension_type)) {
			$wheresql .= " AND pension_type='$pension_type'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension').$wheresql." ORDER BY pension_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {	
			$pension_ids[] = $value['pension_id'];		
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
		$this->setting['index_goods'] = empty($this->setting['index_goods']) ? array() : unserialize($this->setting['index_goods']);
		if(!empty($this->setting['index_goods'])) {
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $this->setting['index_goods'])."') AND goods_show=1 AND goods_state=1");
			while($value = DB::fetch($query)) {
				$index_goods_list[$value['goods_id']] = $value;
			}			
		}
		foreach($this->setting['index_goods'] as $key => $value) {
			if(!empty($index_goods_list[$value])) {
				if(key > 5) return;
				$recommend_goods[] = $index_goods_list[$value];	
			}
		}
		$this->setting['index_pension'] = empty($this->setting['index_pension']) ? array() : unserialize($this->setting['index_pension']);
		if(!empty($this->setting['index_pension'])) {
			$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $this->setting['index_pension'])."') AND pension_state=1");
			while($value = DB::fetch($query)) {
				$index_pension_list[$value['pension_id']] = $value;
			}			
		}
		foreach($this->setting['index_pension'] as $key => $value) {
			if(!empty($index_pension_list[$value])) {
				if(key > 5) return;
				$recommend_pension[] = $index_pension_list[$value];	
			}
		}
		$browse_pension_list = dgetcookie('browse_pension_list');
		$browse_pension_list = empty($browse_pension_list) ? array() : unserialize(urldecode($browse_pension_list));
		$multi = multi($count, $perpage, $page, '', 'selectpension');
		$maxpage = ceil($count/$perpage);
		$curmodule = 'home';
		$bodyclass = '';
		include(template('index_pension'));
	}

	public function pension_searchOp() {
		$district_id = empty($_GET['district_id']) ? 0 : intval($_GET['district_id']);
		$pension_type = empty($_GET['pension_type']) ? 0 : intval($_GET['pension_type']);
		$pension_nature = empty($_GET['pension_nature']) ? 0 : intval($_GET['pension_nature']);
		$pension_person = empty($_GET['pension_person']) ? 0 : intval($_GET['pension_person']);
		$pension_bed = empty($_GET['pension_bed']) ? '' : $_GET['pension_bed'];
		$pension_price = empty($_GET['pension_price']) ? '' : $_GET['pension_price'];
		$sort_field = !in_array($_GET['sort_field'], array('time', 'view', 'price')) ? 'time' : $_GET['sort_field'];
		$time_sort = !in_array($_GET['time_sort'], array('asc', 'desc')) ? 'desc' : $_GET['time_sort'];
		$view_sort = !in_array($_GET['view_sort'], array('asc', 'desc')) ? 'desc' : $_GET['view_sort'];
		$price_sort = !in_array($_GET['price_sort'], array('asc', 'desc')) ? 'desc' : $_GET['price_sort'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 6;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE pension_state=1";
		if(empty($district_id)) {
			$district_id = $this->district['district_id'];
		}
		$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$district_id'");	
		if($district['district_level'] == 1) {
			$wheresql .= " AND pension_provinceid='".$district['district_id']."'";
		} elseif($district['district_level'] == 2) {
			$wheresql .= " AND pension_cityid='".$district['district_id']."'";
		} elseif($district['district_level'] == 3) {
			$wheresql .= " AND pension_areaid='".$district['district_id']."'";
		}
		if(!empty($pension_type)) {
			$wheresql .= " AND pension_type='$pension_type'";
		}
		if(!empty($pension_nature)) {
			$wheresql .= " AND pension_nature='$pension_nature'";
		}
		if(!empty($pension_person)) {
			$wheresql .= " AND pension_person='$pension_person'";
		}
		if(!empty($pension_bed)) {
			$bed_array = explode('-', $pension_bed);
			if(!empty($bed_array[0])) {
				$wheresql .= " AND pension_bed>='$bed_array[0]'";
			}
			if(!empty($bed_array[1])) {
				$wheresql .= " AND pension_bed<='$bed_array[1]'";
			}
		}
		if(!empty($pension_price)) {
			$price_array = explode('-', $pension_price);
			if(!empty($price_array[0])) {
				$wheresql .= " AND pension_price>='$price_array[0]'";
			}
			if(!empty($price_array[1])) {
				$wheresql .= " AND pension_price<='$price_array[1]'";
			}
		}
		if($sort_field == 'time') {
			$ordersql = " ORDER BY pension_time $time_sort";
		} elseif($sort_field == 'view') {
			$ordersql = " ORDER BY pension_viewnum $view_sort";
		} elseif($sort_field == 'price') {
			$ordersql = " ORDER BY pension_price $price_sort";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension').$wheresql.$ordersql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {	
			$pension_ids[] = $value['pension_id'];		
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
		$multi = multi($count, $perpage, $page, '', 'selectpension');
		$maxpage = ceil($count/$perpage);
		$pension_page_html = '';
		$pension_page_html .= '<span class="fp-text"><b>'.$page.'</b><em>/</em><i>'.$maxpage.'</i></span>';
		if($page == 1) {
        	$pension_page_html .= '<a class="fp-prev disabled" href="javascript:;">&lt;</a>';
        } else {
			$pension_page_html .= '<a class="fp-prev" href="javascript:;" onclick="selectpension(this, \'page\', \''.($page-1).'\');">&lt;</a>';
		}
		if($page == $maxpage) {
			$pension_page_html .= '<a class="fp-next disabled" href="javascript:;">&gt;</a>';
		} else {
			$pension_page_html .= '<a class="fp-next" href="javascript:;" onclick="selectpension(this, \'page\', \''.($page+1).'\');">&gt;</a>';
		}
		$pension_count_html = '';
		$pension_count_html .= '共<span id="J_resCount" class="num">'.$count.'</span>家养老机构';
		$pension_html = '';
		if(empty($pension_list)) {
       		$pension_html .= '<div class="no-shop"><dl><dt></dt><dd><p>抱歉，没有找到符合条件的养老机构</p><p>您可以适当减少筛选条件</p></dd></dl></div>';
        } else {
            foreach($pension_list as $key => $value) {
				$pension_html .= '<div class="agency-item">';
               	$pension_html .= '<div class="agency-item-top clearfix">';
				$pension_html .= '<div class="item-top-left">';
				$pension_html .= '<a href="index.php?act=pension&pension_id='.$value['pension_id'].'"><img src="'.$value['pension_image'].'" width="350px" height="200px"></a>';
                $pension_html .= '</div>';
                $pension_html .= '<div class="item-top-right">';
               	$pension_html .= '<h1><a href="index.php?act=pension&pension_id='.$value['pension_id'].'">'.$value['pension_name'].'</a></h1>';
                $pension_html .= '<div class="item-desc">'.$value['pension_summary'].'</div>';
                $pension_html .= '<div class="item-info">区域：<u>'.$value['pension_areainfo'].'</u><i><u class="zdmj">占地面积：</u>'.$value['pension_cover'].'万平方米</i></div>';
                $pension_html .= '<div class="item-info">地址：'.$value['pension_address'].'<i><u class="zdmj">床位：</u>'.$value['pension_bed'].'</i></div>';
                $pension_html .= '<div class="map-commit"><a href="javascrupt:;" onclick="showMap(\''.$value['pension_areainfo'].'\', \''.$value['pension_address'].'\');"><i class="iconfont icon-city"></i>查看地图</a> <a><i class="iconfont icon-talk"></i>'.$value['pension_commentnum'].'评价</a><p><a><i class="iconfont icon-tel"></i>'.$value['pension_phone'].'</a></p></div>';
                $pension_html .= '</div>';
				$pension_html .= '</div>';
				$pension_html .= '<div class="agency-item-foot">';
				foreach($room_list[$value['pension_id']] as $subkey => $subvalue) {
					$pension_html .= '<div class="item-support-list">';
					$pension_html .= '<ul class="clearfix">';
					$pension_html .= '<li class="item-pic"><img src="'.$subvalue['room_image'].'"></li>';
					$pension_html .= '<li class="item-title"><a href="javascript:;">'.$subvalue['room_name'].'</a></li>';
					$pension_html .= '<li class="item-device"><dl>';
					if(in_array('dn', $subvalue['room_support'])) {
						$pension_html .= '<dt><span><i class="iconfont icon-computer"></i></span><p>电脑</p></dt>';
					}
					if(in_array('wf', $subvalue['room_support'])) {
						$pension_html .= '<dt><span><i class="iconfont icon-wifi"></i></span><p>WIFI</p></dt>';
					}
					if(in_array('ds', $subvalue['room_support'])) {
						$pension_html .= '<dt><span><i class="iconfont icon-tv"></i></span><p>电视</p></dt>';
					}
					if(in_array('yx', $subvalue['room_support'])) {
						$pension_html .= '<dt><span><i class="iconfont icon-chest"></i></span><p>药箱</p></dt>';
					}
					if(in_array('ly', $subvalue['room_support'])) {
						$pension_html .= '<dt><span><i class="iconfont icon-chair"></i></span><p>轮椅</p></dt>';
					}
					if(in_array('cy', $subvalue['room_support'])) {
						$pension_html .= '<dt><span><i class="iconfont icon-meet"></i></span><p>餐饮</p></dt>';
					}
					$pension_html .= '</dl></li>';
					$pension_html .= '<li class="item-price"><strong>￥'.$subvalue['room_price'].'</strong>元/月</li>';
					$pension_html .= '</ul>';
					$pension_html .= '<div class="support-desc">';
					$pension_html .= '<div class="support-img clearfix">';
					$pension_html .= '<dl>';
					foreach($subvalue['room_image_more'] as $k => $val) {
						$pension_html .= '<dt><img src="'.$val.'"></dt>';
					}
					$pension_html .= '</dl>';
					$pension_html .= '</div>';
					$pension_html .= '<div class="support-info">';
					$pension_html .= '<p><label>客房介绍：</label>'.(empty($subvalue['room_desc']) ? '无' : $subvalue['room_desc']).'</p>';
					$pension_html .= '<p><label>房间其他设施：</label>'.(empty($subvalue['room_equipment']) ? '无' : $subvalue['room_equipment']).'</p>';
					$pension_html .= '<p><label>免费客房服务：</label>'.(empty($subvalue['room_service']) ? '无' : $subvalue['room_service']).'</p>';
					$pension_html .= '</div>';
					$pension_html .= '</div>';
					$pension_html .= '</div>';
				}
				$pension_html .= '<div class="item-btn"><a class="btn btn-primary" href="index.php?act=pension&pension_id='.$value['pension_id'].'">点击预定</a></div>';
				$pension_html .= '</div>';
            	$pension_html .= '</div>';
			}            
        }
		$pension_html .= $multi;
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$district_id' ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$district_list[] = $value;	
		}
		$district_html = '';
		foreach($district_list as $key => $value) {
			$district_html .= '<li><a href="javascript:;" onclick="selectpension(this, \'district_id\', \''.$value['district_id'].'\');">'.$value['district_name'].'</a></li>';
		}
		exit(json_encode(array('done'=>'true', 'pension_page_html'=>$pension_page_html, 'pension_count_html'=>$pension_count_html, 'pension_html'=>$pension_html, 'district_html'=>$district_html)));
	}
	
	public function redOp() {
		if(empty($this->member_id)) {
			exit(json_encode(array('done'=>'false')));	
		}
		$time = time();
		$red_template = DB::fetch_first("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='activity' AND red_rule_starttime<=$time AND red_rule_endtime>=$time");
		if(!empty($red_template) && $red_template['red_t_total'] > $red_template['red_t_giveout']) {
			$red = DB::fetch_first("SELECT * FROM ".DB::table("red")." WHERE member_id='$this->member_id' AND red_t_id='".$red_template['red_t_id']."'");
			if(empty($red)) {
				if($red_template['red_t_period_type'] == 'duration') {
					$red_template['red_t_starttime'] = strtotime(date('Y-m-d'));
					$red_template['red_t_endtime'] = $red_template['red_t_starttime']+3600*24*($red_template['red_t_days']+1)-1;
				}
				$red_data = array(
					'red_sn' => makesn(2),
					'member_id' => $this->member_id,
					'red_t_id' => $red_template['red_t_id'],
					'red_title' => $red_template['red_t_title'],
					'red_price' => $red_template['red_t_price'],
					'red_starttime' => $red_template['red_t_starttime'],
					'red_endtime' => $red_template['red_t_endtime'],
					'red_limit' => $red_template['red_t_limit'],
					'red_cate_id' => $red_template['red_t_cate_id'],
					'red_state' => 0,
					'red_addtime' => time(),
				);
				$red_id = DB::insert('red', $red_data, 1);
				if(!empty($red_id)) {
					DB::update('red_template', array('red_t_giveout'=>$red_template['red_t_giveout']+1), array('red_t_id'=>$red_template['red_t_id']));
				}
			}
		}
		$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND is_read=0");
		if(!empty($red)) {
			DB::update('red', array('is_read'=>1), array('red_id'=>$red['red_id']));	
			exit(json_encode(array('done'=>'true', 'red_price'=>$red['red_price'])));
		} else {
			exit(json_encode(array('done'=>'false')));
		}
	}
}

?>