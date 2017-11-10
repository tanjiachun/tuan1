<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_couponControl extends BaseStoreControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_coupon";
		$wheresql = " WHERE store_id='$this->store_id'";
		$state = !in_array($_GET['state'], array('on', 'start', 'end')) ? 'on' : $_GET['state'];
		$current_time = time();
		if($state == 'on') {
			$mpurl .= "&state=on";
			$wheresql .= " AND coupon_rule_starttime<='$current_time' AND coupon_rule_endtime>='$current_time'";
		} elseif($state == 'start') {
			$mpurl .= "&state=start";
			$wheresql .= " AND coupon_rule_starttime>'$current_time'";
		} elseif($state == 'end') {
			$mpurl .= "&state=end";
			$wheresql .= " AND coupon_rule_endtime<'$current_time'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('coupon_template').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('coupon_template').$wheresql." ORDER BY coupon_t_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$coupon_template_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_coupon'));	
	}
	
	public function addOp() {
		if(submitcheck()) {
			$coupon_t_title = empty($_POST['coupon_t_title']) ? '' : $_POST['coupon_t_title'];
			$coupon_t_total = empty($_POST['coupon_t_total']) ? 0 : intval($_POST['coupon_t_total']);
			$coupon_t_price_type = !in_array($_POST['coupon_t_price_type'], array('cash', 'discount')) ? 'cash' : $_POST['coupon_t_price_type'];
			$coupon_t_price = empty($_POST['coupon_t_price']) ? 0 : floatval($_POST['coupon_t_price']);
			$coupon_t_period_type = !in_array($_POST['coupon_t_period_type'], array('duration', 'timezone')) ? 'duration' : $_POST['coupon_t_period_type'];
			$coupon_t_days = empty($_POST['coupon_t_days']) ? 0 : intval($_POST['coupon_t_days']);
			$coupon_t_starttime = empty($_POST['coupon_t_starttime']) ? '' : strtotime($_POST['coupon_t_starttime']);
			$coupon_t_endtime = empty($_POST['coupon_t_endtime']) ? '' : strtotime($_POST['coupon_t_endtime']);
			$coupon_t_limit = empty($_POST['coupon_t_limit']) ? 0 : intval($_POST['coupon_t_limit']);
			$coupon_t_desc = empty($_POST['coupon_t_desc']) ? '' : $_POST['coupon_t_desc'];
			$coupon_t_goods_type = !in_array($_POST['coupon_t_goods_type'], array('store', 'goods')) ? 'store' : $_POST['coupon_t_goods_type'];
			$coupon_rule_starttime = empty($_POST['coupon_rule_starttime']) ? '' : strtotime($_POST['coupon_rule_starttime']);
			$coupon_rule_endtime = empty($_POST['coupon_rule_endtime']) ? '' : strtotime($_POST['coupon_rule_endtime']);
			$coupon_rule_type = !in_array($_POST['coupon_rule_type'], array('buy', 'free')) ? 'buy' : $_POST['coupon_rule_type'];
			$coupon_rule_amount = empty($_POST['coupon_rule_amount']) ? 0 : intval($_POST['coupon_rule_amount']);
			$coupon_rule_eachlimit = empty($_POST['coupon_rule_eachlimit']) ? 0 : intval($_POST['coupon_rule_eachlimit']);
			$coupon_t_goods_id = empty($_POST['coupon_t_goods_id']) ? array() : $_POST['coupon_t_goods_id'];
			if(empty($coupon_t_title)) {
				exit(json_encode(array('id'=>'coupon_t_title', 'msg'=>'请输入优惠券名称')));
			}
			if(empty($coupon_t_total)) {
				exit(json_encode(array('id'=>'coupon_t_total', 'msg'=>'请输入发放总量')));
			}
			if(empty($coupon_t_price)) {
				exit(json_encode(array('id'=>'coupon_t_price', 'msg'=>'请输入面值')));
			}		
			if($coupon_t_goods_type == 'store') {
				$coupon_t_goods_id = array();	
			}
			$data = array(
				'coupon_t_title' => $coupon_t_title,
				'store_id' => $this->store_id,
				'coupon_t_desc' => $coupon_t_desc,
				'coupon_t_period_type' => $coupon_t_period_type,
				'coupon_t_days' => $coupon_t_days,
				'coupon_t_starttime' => $coupon_t_starttime,
				'coupon_t_endtime' => $coupon_t_endtime+3600*24-1,
				'coupon_t_price_type' => $coupon_t_price_type,
				'coupon_t_price' => $coupon_t_price,
				'coupon_t_limit' => $coupon_t_limit,
				'coupon_t_goods_id' => empty($coupon_t_goods_id) ? '' : implode(',', $coupon_t_goods_id),
				'coupon_t_total' => $coupon_t_total,
				'coupon_rule_starttime' => $coupon_rule_starttime,
				'coupon_rule_endtime' => $coupon_rule_endtime+3600*24-1,
				'coupon_rule_type' => $coupon_rule_type,
				'coupon_rule_amount' => $coupon_rule_amount,
				'coupon_rule_eachlimit' => $coupon_rule_eachlimit,
				'coupon_t_addtime' => time(),
			);
			$coupon_t_id = DB::insert('coupon_template', $data, 1);
			if(!empty($coupon_t_id)) {
				exit(json_encode(array('done'=>'true')));	
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_coupon_add'));
		}	
	}
	
	public function editOp() {
		if(submitcheck()) {
			$coupon_t_id = empty($_POST['coupon_t_id']) ? 0 : intval($_POST['coupon_t_id']);
			$coupon_t_title = empty($_POST['coupon_t_title']) ? '' : $_POST['coupon_t_title'];
			$coupon_t_total = empty($_POST['coupon_t_total']) ? 0 : intval($_POST['coupon_t_total']);
			$coupon_t_price_type = !in_array($_POST['coupon_t_price_type'], array('cash', 'discount')) ? 'cash' : $_POST['coupon_t_price_type'];
			$coupon_t_price = empty($_POST['coupon_t_price']) ? 0 : floatval($_POST['coupon_t_price']);
			$coupon_t_period_type = !in_array($_POST['coupon_t_period_type'], array('duration', 'timezone')) ? 'duration' : $_POST['coupon_t_period_type'];
			$coupon_t_days = empty($_POST['coupon_t_days']) ? 0 : intval($_POST['coupon_t_days']);
			$coupon_t_starttime = empty($_POST['coupon_t_starttime']) ? '' : strtotime($_POST['coupon_t_starttime']);
			$coupon_t_endtime = empty($_POST['coupon_t_endtime']) ? '' : strtotime($_POST['coupon_t_endtime']);
			$coupon_t_limit = empty($_POST['coupon_t_limit']) ? 0 : intval($_POST['coupon_t_limit']);
			$coupon_t_desc = empty($_POST['coupon_t_desc']) ? '' : $_POST['coupon_t_desc'];
			$coupon_t_goods_type = !in_array($_POST['coupon_t_goods_type'], array('store', 'goods')) ? 'store' : $_POST['coupon_t_goods_type'];
			$coupon_rule_starttime = empty($_POST['coupon_rule_starttime']) ? '' : strtotime($_POST['coupon_rule_starttime']);
			$coupon_rule_endtime = empty($_POST['coupon_rule_endtime']) ? '' : strtotime($_POST['coupon_rule_endtime']);
			$coupon_rule_type = !in_array($_POST['coupon_rule_type'], array('buy', 'free')) ? 'buy' : $_POST['coupon_rule_type'];
			$coupon_rule_amount = empty($_POST['coupon_rule_amount']) ? 0 : intval($_POST['coupon_rule_amount']);
			$coupon_rule_eachlimit = empty($_POST['coupon_rule_eachlimit']) ? 0 : intval($_POST['coupon_rule_eachlimit']);
			$coupon_t_goods_id = empty($_POST['coupon_t_goods_id']) ? array() : $_POST['coupon_t_goods_id'];
			if(empty($coupon_t_title)) {
				exit(json_encode(array('id'=>'coupon_t_title', 'msg'=>'请输入优惠券名称')));
			}
			if(empty($coupon_t_total)) {
				exit(json_encode(array('id'=>'coupon_t_total', 'msg'=>'请输入发放总量')));
			}
			if(empty($coupon_t_price)) {
				exit(json_encode(array('id'=>'coupon_t_price', 'msg'=>'请输入面值')));
			}		
			if($coupon_t_goods_type == 'store') {
				$coupon_t_goods_id = array();	
			}
			$data = array(
				'coupon_t_title' => $coupon_t_title,
				'coupon_t_desc' => $coupon_t_desc,
				'coupon_t_period_type' => $coupon_t_period_type,
				'coupon_t_days' => $coupon_t_days,
				'coupon_t_starttime' => $coupon_t_starttime,
				'coupon_t_endtime' => $coupon_t_endtime+3600*24-1,
				'coupon_t_price_type' => $coupon_t_price_type,
				'coupon_t_price' => $coupon_t_price,
				'coupon_t_limit' => $coupon_t_limit,
				'coupon_t_goods_id' => empty($coupon_t_goods_id) ? '' : implode(',', $coupon_t_goods_id),
				'coupon_t_total' => $coupon_t_total,
				'coupon_rule_starttime' => $coupon_rule_starttime,
				'coupon_rule_endtime' => $coupon_rule_endtime+3600*24-1,
				'coupon_rule_type' => $coupon_rule_type,
				'coupon_rule_amount' => $coupon_rule_amount,
				'coupon_rule_eachlimit' => $coupon_rule_eachlimit,
			);
			DB::update('coupon_template', $data, array('coupon_t_id'=>$coupon_t_id, 'store_id' => $this->store_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$coupon_t_id = empty($_GET['coupon_t_id']) ? 0 : intval($_GET['coupon_t_id']);
			$coupon_template = DB::fetch_first("SELECT * FROM ".DB::table('coupon_template')." WHERE coupon_t_id='$coupon_t_id' AND store_id='$this->store_id'");
			if(!empty($coupon_template['coupon_t_goods_id'])) {
				$goods_ids_array = explode(',', $coupon_template['coupon_t_goods_id']);
				foreach($goods_ids_array as $key => $value) {
					if(!empty($value)) {
						$goods_ids[] = intval($value);	
					}
				}
				if(!empty($goods_ids)) {
					$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids)."')");
					while($value = DB::fetch($query)) {
						if($value['store_id'] == $this->store_id && $value['goods_show'] == 1 && $value['goods_state'] == 1) {
							$goods_list[$value['goods_id']] = $value;
						}
					}
				}
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_coupon_edit'));
		}
	}
	
	public function delOp() {
		if(submitcheck()) {
			$del_id = empty($_POST['del_id']) ? 0 : intval($_POST['del_id']);
			$coupon_template = DB::fetch_first("SELECT * FROM ".DB::table('coupon_template')." WHERE coupon_t_id='$del_id'");
			if(empty($coupon_template) || $coupon_template['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'优惠券不存在')));
			}
			DB::query("DELETE FROM ".DB::table('coupon_template')." WHERE coupon_t_id='$del_id'");
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function recordOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$coupon_t_id = empty($_GET['coupon_t_id']) ? 0 : intval($_GET['coupon_t_id']);
		$mpurl = "index.php?act=store_coupon&op=record&coupon_t_id=$coupon_t_id";
		$wheresql = " WHERE store_id='$this->store_id' AND coupon_t_id='$coupon_t_id'";
		$state = !in_array($_GET['state'], array('giveout', 'outused', 'used')) ? 'giveout' : $_GET['state'];
		$mpurl .= "&state=$state";
		if($state == 'outused') {
			$wheresql .= " AND coupon_state=0";
		} elseif($state == 'used') {
			$wheresql .= " AND coupon_state=1";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('coupon').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('coupon').$wheresql." ORDER BY coupon_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$member_ids[] = $value['member_id'];
			$coupon_list[] = $value;
		}
		if(!empty($member_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
			while($value = DB::fetch($query)) {
				$member_list[$value['member_id']] = $value;
			}	
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_coupon_record'));	
	}
}

?>