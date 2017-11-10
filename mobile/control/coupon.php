<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class couponControl extends BaseMobileControl {
	public function indexOp() {
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('coupon').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('coupon').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$store_ids[] = $value['store_id'];
			$value['coupon_goods_id'] = empty($value['coupon_goods_id']) ? array() : explode(',', $value['coupon_goods_id']);
			foreach($value['coupon_goods_id'] as $goods_id) {
				$goods_ids[] = $goods_id;
			}
			if($value['coupon_state'] == 0 && $value['coupon_endtime']<time()) {
				$value['coupon_state'] = 2;	
			}
			$value['coupon_starttime'] = date('Y-m-d H:i', $value['coupon_starttime']);
			$value['coupon_endtime'] = date('Y-m-d H:i', $value['coupon_endtime']);
			$coupon_list[] = $value;
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		if(!empty($goods_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids)."')");
			while($value = DB::fetch($query)) {
				$goods_list[$value['goods_id']] = $value['goods_name'];
			}
		}
		foreach($coupon_list as $key => $value) {
			$coupon_discount_goods = array();
			if(!empty($value['coupon_goods_id'])) {
				foreach($value['coupon_goods_id'] as $goods_id) {
            		$coupon_discount_goods[] = $goods_list[$goods_id];
				}
			}
			$coupon_list[$key]['coupon_discount_goods'] = $coupon_discount_goods;
			$coupon_list[$key]['store_name'] = $store_list[$value['store_id']];		
		}
		$data = array(
			'coupon_count' => $count,
			'coupon_list' => empty($coupon_list) ? array() : $coupon_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function getOp() {
		$coupon_t_id = empty($_POST['coupon_t_id']) ? 0 : intval($_POST['coupon_t_id']);
		$coupon_template = DB::fetch_first("SELECT * FROM ".DB::table('coupon_template')." WHERE coupon_t_id='$coupon_t_id'");
		if(empty($coupon_template) || $coupon_template['coupon_rule_type'] != 'free') {
			exit(json_encode(array('code'=>'1', 'msg'=>'优惠券不存在', 'data'=>array())));
		}
		if($coupon_template['coupon_rule_starttime'] > time()) {
			exit(json_encode(array('code'=>'1', 'msg'=>'领取活动未开始', 'data'=>array())));
		}
		if($coupon_template['coupon_rule_endtime'] < time()) {
			exit(json_encode(array('code'=>'1', 'msg'=>'领取活动已结束', 'data'=>array())));
		}
		if($coupon_template['coupon_t_total'] <= $coupon_template['coupon_t_giveout']) {
			exit(json_encode(array('code'=>'1', 'msg'=>'抱歉了，已经被领完了', 'data'=>array())));
		}
		if(!empty($coupon_template['coupon_rule_eachlimit'])) {
			$numbers = DB::result_first("SELECT COUNT(*) FROM ".DB::table("coupon")." WHERE member_id='$this->member_id' AND coupon_t_id='$coupon_t_id'");		
			if($numbers >= $coupon_template['coupon_rule_eachlimit']) {
				exit(json_encode(array('code'=>'1', 'msg'=>'抱歉了，您已经领取过了', 'data'=>array())));
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
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}
	}
}

?>