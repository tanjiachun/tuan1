<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pensionControl extends BaseMobileControl {
	public function indexOp() {
		$pension_id = empty($_POST['pension_id']) ? 0 : intval($_POST['pension_id']);
		$pension = DB::fetch_first("SELECT pension_id, pension_name, pension_type, pension_nature, pension_person, pension_bed, pension_cover, pension_price, pension_phone, pension_image, pension_image_more, pension_areainfo, pension_address, pension_summary, pension_scale, pension_score, pension_viewnum, pension_salenum, pension_commentnum, pension_time FROM ".DB::table('pension')." WHERE pension_id='$pension_id'");
		if(empty($pension)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'机构不存在', 'data'=>array())));
		}
		$pension_field = DB::fetch_first("SELECT * FROM ".DB::table('pension_field')." WHERE pension_id='$pension_id'");
		$pension['pension_image_more'] = empty($pension['pension_image_more']) ? array() : unserialize($pension['pension_image_more']);
		$pension['near_image'] = empty($pension_field['near_image']) ? array() : unserialize($pension_field['near_image']);
		$pension['equipment_image'] = empty($pension_field['equipment_image']) ? array() : unserialize($pension_field['equipment_image']);
		$pension['service_image'] = empty($pension_field['service_image']) ? array() : unserialize($pension_field['service_image']);
		$query = DB::query("SELECT * FROM ".DB::table('pension_room')." WHERE pension_id = '$pension_id'");
		while($value = DB::fetch($query)) {
			$value['room_image_more'] = empty($value['room_image_more']) ? array() : unserialize($value['room_image_more']);
			$value['room_support'] = empty($value['room_support']) ? array() : unserialize($value['room_support']);
			$room_list[] = $value;
		}
		$pension['pension_room'] = $room_list;
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='activity' AND red_rule_starttime<=$time AND red_rule_endtime>=$time");
		while($value = DB::fetch($query)) {
			if($value['red_t_cate_id'] == 0 || $value['red_t_cate_id'] == 3) {
				$red = DB::fetch_first("SELECT * FROM ".DB::table("red")." WHERE member_id='$this->member_id' AND red_t_id='".$value['red_t_id']."'");
				$value['red_t_state'] = empty($red) ? 0 : 1;
				$red_list[] =$value;
			}
		}
		DB::query("UPDATE ".DB::table('pension')." SET pension_viewnum=pension_viewnum+1 WHERE pension_id='".$pension['pension_id']."'");
		$date = date('Ymd');
		$pension_stat = DB::fetch_first("SELECT * FROM ".DB::table('pension_stat')." WHERE pension_id='".$pension['pension_id']."' AND date='$date'");
		if(empty($pension_stat)) {
			$pension_stat_array = array(
				'pension_id' => $pension['pension_id'],
				'date' => $date,
				'viewnum' => 1,
			);
			DB::insert('pension_stat', $pension_stat_array);
		} else {
			$pension_stat_array = array(
				'viewnum' => $pension_stat['viewnum']+1,
			);
			DB::update('pension_stat', $pension_stat_array, array('pension_id'=>$pension['pension_id'], 'date'=>$date));
		}
		$data = array(
			'pension' => $pension,
			'red_list' => $red_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function commentOp() {
		$pension_id = empty($_POST['pension_id']) ? 0 : intval($_POST['pension_id']);
		$query = DB::query("SELECT comment_level, COUNT(*) as count FROM ".DB::table('pension_comment')." WHERE pension_id='$pension_id' GROUP BY comment_level");
		while($value = DB::fetch($query)) {
			$comment_count[$value['comment_level']] = $value['count'];
		}
		$wheresql = " WHERE pension_id='$pension_id'";
		$field_value = !in_array($_POST['field_value'], array('all', 'good', 'middle', 'bad')) ? 'all' : $_POST['field_value'];
		if($field_value == 'good') {
			$wheresql .= " AND comment_level='good'";
		} elseif($field_value == 'middle') {
			$wheresql .= " AND comment_level='middle'";
		} elseif($field_value == 'bad') {
			$wheresql .= " AND comment_level='bad'";
		}	
		$query = DB::query("SELECT * FROM ".DB::table('pension_comment').$wheresql);
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
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
		foreach($comment_list as $key => $value) {
			$comment_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
			$comment_list[$key]['member_avatar'] = $member_list[$value['member_id']]['member_avatar'];
		}
		$data = array(
			'comment_count' => $comment_count,
			'comment_list' => $comment_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>