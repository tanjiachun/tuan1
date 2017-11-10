<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class recommendControl extends BaseAdminControl {
	public function indexOp() {
		include(template('recommend'));
	}
	
	public function nurseOp() {
		if(submitcheck()) {
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			$index_nurse = empty($setting['index_nurse']) ? array() : unserialize($setting['index_nurse']);
			$type = !in_array($_POST['type'], array('inside', 'outside', 'illness', 'hour')) ? 'inside' : $_POST['type'];			
			$nurse_ids = empty($_POST['nurse_ids']) ? '' : $_POST['nurse_ids'];
			if(!empty($nurse_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."') AND nurse_state=1");
				while($value = DB::fetch($query)) {
					$nurse_list[$value['nurse_id']] = $value;
				}
			}
			$index_nurse[$type] = array();
			foreach($nurse_ids as $key => $value) {
				if(!empty($nurse_list[$value])) {
					$index_nurse[$type][] = $value;
				}
			}
			$index_nurse = empty($index_nurse) ? '' : serialize($index_nurse);
			$district_ids = array('1', '2', '9', '22', '32', '33', '34');
			if($type == 'inside') {
				foreach($nurse_ids as $key => $value) {
					if(!empty($nurse_list[$value])) {
						if(in_array($nurse_list[$value]['nurse_provinceid'], $district_ids)) {
							$index_inside[$nurse_list[$value]['nurse_provinceid']][] = $value;
						} else {
							$index_inside[$nurse_list[$value]['nurse_cityid']][] = $value;
							
						}
						if(!empty($nurse_list[$value]['nurse_areaid'])) {
							$app_inside[$nurse_list[$value]['nurse_areaid']][] = $value;
						} else {
							$app_inside[$nurse_list[$value]['nurse_cityid']][] = $value;
						}
					}
				}
				$index_inside = empty($index_inside) ? '' : serialize($index_inside);
				$app_inside = empty($app_inside) ? '' : serialize($app_inside);
				DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('index_nurse', '".$index_nurse."'), ('index_inside', '".$index_inside."'), ('app_inside', '".$app_inside."')");
			} elseif($type == 'outside') {
				foreach($nurse_ids as $key => $value) {
					if(!empty($nurse_list[$value])) {
						if(in_array($nurse_list[$value]['nurse_provinceid'], $district_ids)) {
							$index_outside[$nurse_list[$value]['nurse_provinceid']][] = $value;
						} else {
							$index_outside[$nurse_list[$value]['nurse_cityid']][] = $value;
						}
						if(!empty($nurse_list[$value]['nurse_areaid'])) {
							$app_outside[$nurse_list[$value]['nurse_areaid']][] = $value;
						} else {
							$app_outside[$nurse_list[$value]['nurse_cityid']][] = $value;
						}
					}
				}
				$index_outside = empty($index_outside) ? '' : serialize($index_outside);
				$app_outside = empty($app_outside) ? '' : serialize($app_outside);
				DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('index_nurse', '".$index_nurse."'), ('index_outside', '".$index_outside."'), ('app_outside', '".$app_outside."')");
			} elseif($type == 'illness') {
				foreach($nurse_ids as $key => $value) {
					if(!empty($nurse_list[$value])) {
						if(in_array($nurse_list[$value]['nurse_provinceid'], $district_ids)) {
							$index_illness[$nurse_list[$value]['nurse_provinceid']][] = $value;
						} else {
							$index_illness[$nurse_list[$value]['nurse_cityid']][] = $value;
						}
						if(!empty($nurse_list[$value]['nurse_areaid'])) {
							$app_illness[$nurse_list[$value]['nurse_areaid']][] = $value;
						} else {
							$app_illness[$nurse_list[$value]['nurse_cityid']][] = $value;
						}
					}
				}
				$index_illness = empty($index_illness) ? '' : serialize($index_illness);
				$app_illness = empty($app_illness) ? '' : serialize($app_illness);
				DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('index_nurse', '".$index_nurse."'), ('index_illness', '".$index_illness."'), ('app_illness', '".$app_illness."')");
			} elseif($type == 'hour') {
				foreach($nurse_ids as $key => $value) {
					if(!empty($nurse_list[$value])) {
						if(in_array($nurse_list[$value]['nurse_provinceid'], $district_ids)) {
							$index_hour[$nurse_list[$value]['nurse_provinceid']][] = $value;
						} else {
							$index_hour[$nurse_list[$value]['nurse_cityid']][] = $value;
						}
						if(!empty($nurse_list[$value]['nurse_areaid'])) {
							$app_hour[$nurse_list[$value]['nurse_areaid']][] = $value;
						} else {
							$app_hour[$nurse_list[$value]['nurse_cityid']][] = $value;
						}
					}
				}
				$index_hour = empty($index_hour) ? '' : serialize($index_hour);
				$app_hour = empty($app_hour) ? '' : serialize($app_hour);
				DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('index_nurse', '".$index_nurse."'), ('index_hour', '".$index_hour."'), ('app_hour', '".$app_hour."')");
			}
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
			showdialog('保存成功', 'admin.php?act=recommend', 'succ');
		} else {
			$type = !in_array($_GET['type'], array('inside', 'outside', 'illness', 'hour')) ? 'inside' : $_GET['type'];
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			$setting['index_nurse'] = empty($setting['index_nurse']) ? array() : unserialize($setting['index_nurse']);
			if(!empty($setting['index_nurse'][$type])) {
				$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $setting['index_nurse'][$type])."') AND nurse_state=1");
				while($value = DB::fetch($query)) {
					$agent_ids[] = $value['agent_id'];
					$nurse_list[$value['nurse_id']] = $value;
				}			
			}
			if(!empty($agent_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
				while($value = DB::fetch($query)) {
					$agent_list[$value['agent_id']] = $value['agent_name'];
				}
			}
			foreach($setting['index_nurse'][$type] as $key => $value) {
				if(!empty($nurse_list[$value])) {
					$recommend_nurse[] = $nurse_list[$value];	
				}
			}
			include(template('recommend_nurse'));
		}
	}
	
	public function goodsOp() {
		if(submitcheck()) {
			$goods_ids = empty($_POST['goods_ids']) ? '' : $_POST['goods_ids'];
			if(!empty($goods_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids)."') AND goods_show=1 AND goods_state=1");
				while($value = DB::fetch($query)) {
					$goods_list[$value['goods_id']] = $value;
				}
			}
			foreach($goods_ids as $key => $value) {
				if(!empty($goods_list[$value])) {
					$index_goods[] = $value;
				}
			}
			$index_goods = empty($index_goods) ? '' : serialize($index_goods);
			DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('index_goods', '".$index_goods."')");
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
			showdialog('保存成功', 'admin.php?act=recommend', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			$setting['index_goods'] = empty($setting['index_goods']) ? array() : unserialize($setting['index_goods']);
			if(!empty($setting['index_goods'])) {
				$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $setting['index_goods'])."') AND goods_show=1 AND goods_state=1");
				while($value = DB::fetch($query)) {
					$store_ids[] = $value['store_id'];
					$goods_list[$value['goods_id']] = $value;
				}			
			}
			if(!empty($store_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
				while($value = DB::fetch($query)) {
					$store_list[$value['store_id']] = $value['store_name'];
				}
			}
			foreach($setting['index_goods'] as $key => $value) {
				if(!empty($goods_list[$value])) {
					$recommend_goods[] = $goods_list[$value];	
				}
			}
			include(template('recommend_goods'));
		}
	}
	
	public function pensionOp() {
		if(submitcheck()) {
			$pension_ids = empty($_POST['pension_ids']) ? '' : $_POST['pension_ids'];
			if(!empty($pension_ids)) {
				$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $pension_ids)."') AND pension_state=1");
				while($value = DB::fetch($query)) {
					$pension_list[$value['pension_id']] = $value;
				}
			}
			foreach($pension_ids as $key => $value) {
				if(!empty($pension_list[$value])) {
					$index_pension[] = $value;
				}
			}
			$index_pension = empty($index_pension) ? '' : serialize($index_pension);
			DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('index_pension', '".$index_pension."')");
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
			showdialog('保存成功', 'admin.php?act=recommend', 'succ');
		} else {
			$pension_scale = array('1'=>'50以下', '2'=>'50-100', '3'=>'100以上');
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			$setting['index_pension'] = empty($setting['index_pension']) ? array() : unserialize($setting['index_pension']);
			if(!empty($setting['index_pension'])) {
				$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $setting['index_pension'])."') AND pension_state=1");
				while($value = DB::fetch($query)) {
					$pension_list[$value['pension_id']] = $value;
				}			
			}
			foreach($setting['index_pension'] as $key => $value) {
				if(!empty($pension_list[$value])) {
					$recommend_pension[] = $pension_list[$value];	
				}
			}
			include(template('recommend_pension'));
		}
	}
}
?>