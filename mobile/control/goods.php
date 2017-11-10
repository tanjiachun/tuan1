<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class goodsControl extends BaseMobileControl {
	public function indexOp() {
		$goods_id = empty($_POST['goods_id']) ? 0 : intval($_POST['goods_id']);
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." WHERE goods_id='$goods_id'");
		if(empty($goods)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品不存在', 'data'=>array())));
		}
		$goods['store_name'] = DB::result_first("SELECT store_name FROM ".DB::table('store')." WHERE store_id='".$goods['store_id']."'");
		$goods['class_name'] = DB::result_first("SELECT class_name FROM ".DB::table('class')." WHERE class_id='".$goods['class_id']."'");
		$goods['brand_name'] = DB::result_first("SELECT brand_name FROM ".DB::table('brand')." WHERE brand_id='".$goods['brand_id']."'");
		$goods['type_name'] = DB::result_first("SELECT type_name FROM ".DB::table('type')." WHERE type_id='".$goods['type_id']."'");
		$goods['goods_body'] = htmlspecialchars_decode($goods['goods_body']);
		$goods['goods_image_more'] = empty($goods['goods_image_more']) ? array() : unserialize($goods['goods_image_more']);
		$goods['goods_attr'] = empty($goods['goods_attr']) ? array() : unserialize($goods['goods_attr']);
		$attr_ids = array_keys($goods['goods_attr']);
		if(!empty($attr_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('attribute')." WHERE attr_id in ('".implode("','", $attr_ids)."')");
			while($value = DB::fetch($query)) {
				$attr_list[$value['attr_id']] = $value['attr_name'];
			}
		}
		foreach($goods['goods_attr'] as $key => $value) {
			$goods_attr[] = $attr_list[$key].'：'.$value;
		}
		$goods['goods_attr'] = empty($goods_attr) ? array() : $goods_attr;
		$goods['goods_freight'] = 0;
		if($goods['goods_postage'] == 'transport') {
			if(!empty($goods['transport_id'])) {
				$goods['goods_freight'] = DB::result_first("SELECT MIN(extend_sprice) FROM ".DB::table('transport_extend')." WHERE transport_id='".$goods['transport_id']."'");
			}
		} elseif($goods['goods_postage'] == 'freight') {
			$goods['goods_freight'] = $goods['kd_price'] > 0 ? $goods['kd_price'] : 0;
			if($goods['es_price'] > 0 && $goods['es_price'] < $goods['goods_freight']) {
				$goods['goods_freight'] = $goods['es_price'];
			}
			if($goods['py_price'] > 0 && $goods['py_price'] < $goods['goods_freight']) {
				$goods['goods_freight'] = $goods['py_price'];
			}
		}
		$goods['oldage_price'] = 0;
		$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
		if($this->setting['first_oldage_rate'] > 0)	{
			$goods['oldage_price'] = priceformat($goods['goods_price']*$this->setting['first_oldage_rate']);
		}
		$query = DB::query("SELECT * FROM ".DB::table('goods_spec')." WHERE goods_id='".$goods['goods_id']."'");
		while($value = DB::fetch($query)) {
			$spec_array[] = $value;	
		}
		if(!empty($spec_array) && is_array($spec_array)) {
			foreach($spec_array as $key => $value){
				$spec_name = empty($value['spec_name']) ? array() : unserialize($value['spec_name']);
				$spec_goods_spec = empty($value['spec_goods_spec']) ? array() : unserialize($value['spec_goods_spec']);
				$spec_list[] = $value;
			}
		}
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('coupon_template')." WHERE store_id='".$goods['store_id']."' AND coupon_rule_type='free' AND coupon_rule_starttime<=$time AND coupon_rule_endtime>=$time");
		while($value = DB::fetch($query)) {
			$value['coupon_t_goods_id'] = empty($value['coupon_t_goods_id']) ? array() : explode(',', $value['coupon_t_goods_id']);
			if(empty($value['coupon_t_goods_id']) || in_array($goods['goods_id'], $value['coupon_t_goods_id'])) {
				$coupon_list[] =$value;
			}
		}
		foreach($coupon_list as $key => $value) {
			if(!empty($value['coupon_rule_eachlimit'])) {
				$numbers = DB::result_first("SELECT COUNT(*) FROM ".DB::table("coupon")." WHERE member_id='$this->member_id' AND coupon_t_id='".$value['coupon_t_id']."'");		
				$coupon_list[$key]['coupon_t_state'] = $numbers >= $coupon_template['coupon_rule_eachlimit'] ? 1: 0;
			}
		}
		DB::query("UPDATE ".DB::table('goods')." SET goods_viewnum=goods_viewnum+1 WHERE goods_id='".$goods['goods_id']."'");
		$date = date('Ymd');
		$goods_stat = DB::fetch_first("SELECT * FROM ".DB::table('goods_stat')." WHERE goods_id='".$goods['goods_id']."' AND date='$date'");
		if(empty($goods_stat)) {
			$goods_stat_array = array(
				'goods_id' => $goods['goods_id'],
				'date' => $date,
				'viewnum' => 1,
			);
			DB::insert('goods_stat', $goods_stat_array);
		} else {
			$goods_stat_array = array(
				'viewnum' => $goods_stat['viewnum']+1,
			);
			DB::update('goods_stat', $goods_stat_array, array('goods_id'=>$goods['goods_id'], 'date'=>$date));
		}
		$data = array(
			'goods' => $goods,
			'spec_list' => $spec_list,
			'coupon_list' => $coupon_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function commentOp() {
		$goods_id = empty($_POST['goods_id']) ? 0 : intval($_POST['goods_id']);
		$query = DB::query("SELECT comment_level, COUNT(*) as count FROM ".DB::table('goods_comment')." WHERE goods_id='$goods_id' GROUP BY comment_level");
		while($value = DB::fetch($query)) {
			$comment_count[$value['comment_level']] = $value['count'];
		}
		$wheresql = " WHERE goods_id='$goods_id'";
		$field_value = !in_array($_POST['field_value'], array('all', 'good', 'middle', 'bad')) ? 'all' : $_POST['field_value'];
		if($field_value == 'good') {
			$wheresql .= " AND comment_level='good'";
		} elseif($field_value == 'middle') {
			$wheresql .= " AND comment_level='middle'";
		} elseif($field_value == 'bad') {
			$wheresql .= " AND comment_level='bad'";
		}
		$query = DB::query("SELECT * FROM ".DB::table('goods_comment').$wheresql);
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
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
			'comment_count' => $comment_count,
			'comment_list' => $comment_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>