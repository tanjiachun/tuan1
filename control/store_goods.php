<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_goodsControl extends BaseStoreControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_goods";
		$wheresql = " WHERE store_id='$this->store_id' AND goods_state=1 AND goods_show=1";
		$goods_name = empty($_GET['goods_name']) ? '' : $_GET['goods_name'];
		if(!empty($goods_name)) {
			$mpurl .= '&goods_name='.urlencode($goods_name);
			$wheresql .= " AND goods_name like '%".$goods_name."%'";				
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('goods').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('goods').$wheresql." ORDER BY goods_add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$goods_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_goods'));
	}
	
	public function goods_unshowOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_goods&op=goods_unshow";
		$wheresql = " WHERE store_id='$this->store_id'";
		$state = in_array($_GET['state'], array('unshow', 'pending', 'illegal')) ? $_GET['state'] : 'unshow';
		if($state == 'unshow') {
			$mpurl .= '&state=unshow';
			$wheresql .= " AND goods_state=1 AND goods_show=0";
		} elseif($state == 'pending') {
			$mpurl .= '&state=pending';
			$wheresql .= " AND goods_state=3";
		} elseif($state == 'illegal') {
			$mpurl .= '&state=illegal';
			$wheresql .= " AND goods_state=2";
		}
		$goods_name = empty($_GET['goods_name']) ? '' : $_GET['goods_name'];
		if(!empty($goods_name)) {
			$mpurl .= '&goods_name='.urlencode($goods_name);
			$wheresql .= " AND goods_name like '%".$goods_name."%'";				
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('goods').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('goods').$wheresql." ORDER BY goods_add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$goods_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_goods_unshow'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$goods_name = empty($_POST['goods_name']) ? '' : $_POST['goods_name'];
			$class_id = empty($_POST['class_id']) ? 0 : intval($_POST['class_id']);
			$brand_id = empty($_POST['brand_id']) ? 0 : intval($_POST['brand_id']);
			$type_id = empty($_POST['type_id']) ? 0 : intval($_POST['type_id']);
			$goods_price = empty($_POST['goods_price']) ? 0 : floatval($_POST['goods_price']);
			$goods_price_interval = empty($_POST['goods_price_interval']) ? '' : $_POST['goods_price_interval'];
			$goods_storage = empty($_POST['goods_storage']) ? 0 : intval($_POST['goods_storage']);
			$goods_serial = empty($_POST['goods_serial']) ? '' : $_POST['goods_serial'];
			$goods_postage = in_array($_POST['goods_postage'], array('freight', 'transport')) ? $_POST['goods_postage'] : 'freight';
			$kd_price = empty($_POST['kd_price']) ? 0 : floatval($_POST['kd_price']);
			$es_price = empty($_POST['es_price']) ? 0 : floatval($_POST['es_price']);
			$py_price = empty($_POST['py_price']) ? 0 : floatval($_POST['py_price']);
			$transport_id = empty($_POST['transport_id']) ? 0 : intval($_POST['transport_id']);
			$goods_image = empty($_POST['goods_image']) ? '' : $_POST['goods_image'];
			$goods_image_more = empty($_POST['image_1']) ? array() : $_POST['image_1'];
			$goods_body = empty($_POST['goods_body']) ? '' : $_POST['goods_body'];
			$spec = empty($_POST['spec']) ? array() : $_POST['spec'];
			$goods_attr = empty($_POST['goods_attr']) ? array() : $_POST['goods_attr'];
			$goods_promotion_type = in_array($_POST['goods_promotion_type'], array('normal', 'cheap', 'group')) ? $_POST['goods_promotion_type'] : 'normal';
			if($goods_promotion_type == 'cheap') {
				$goods_promotion_type = 1;
				$goods_original_price = empty($_POST['goods_original_price']) ? 0 : floatval($_POST['goods_original_price']);
			} elseif($goods_promotion_type == 'group') {
				$goods_promotion_type = 2;
				$goods_group_number = empty($_POST['goods_group_number']) ? 0 : intval($_POST['goods_group_number']);
				$goods_group_price = empty($_POST['goods_group_price']) ? 0 : floatval($_POST['goods_group_price']);
			} else {
				$goods_promotion_type = 0;
			}
			if(empty($spec)) {
				$spec_name = array();
				$spec_value = array();
				$spec_image = array();
			} else {
				$spec_name = empty($_POST['spec_name']) ? array() : $_POST['spec_name'];				
				$spec_value_array = empty($_POST['spec_value']) ? array() : $_POST['spec_value'];
				foreach($spec_value_array as $key => $value) {
					foreach($value as $subkey => $subvalue) {
						if(!empty($subvalue)) {
							$spec_value[$key][$subkey] = $subvalue;
						}
					}
				}
				$spec_image_array = empty($_POST['spec_image']) ? array() : $_POST['spec_image'];
				foreach($spec_image_array as $key => $value) {
					if(!empty($value)) {
						$spec_image[$key] = $value;
					}
				}	
			}
			if(empty($goods_name)) {
				$this->showmessage('请输入商品名称', 'index.php?act=store_goods&op=add', 'error');
			}
			if($goods_price <= 0) {
				$this->showmessage('请输入商品价格', 'index.php?act=store_goods&op=add', 'error');
			}
			if($goods_storage <= 0) {
				$this->showmessage('请输入商品库存', 'index.php?act=store_goods&op=add', 'error');
			}
			$goods_data = array(
				'goods_name' => $goods_name,
				'store_id' => $this->store_id,
				'class_id' => $class_id,
				'brand_id' => $brand_id,
				'type_id' => $type_id,
				'spec_open' => 1,
				'spec_name' => empty($spec_name) ? '' : serialize($spec_name),
				'spec_value' => empty($spec_value) ? '' : serialize($spec_value),
				'spec_image' => empty($spec_image) ? '' : serialize($spec_image),
				'goods_price' => $goods_price,
				'goods_price_interval' => $goods_price_interval,
				'goods_storage' => $goods_storage,
				'goods_serial' => $goods_serial,
				'goods_image' => $goods_image,
				'goods_image_more' => empty($goods_image_more) ? '' : serialize($goods_image_more),
				'goods_body' => $goods_body,
				'goods_attr' => empty($goods_attr) ? '' : serialize($goods_attr),
				'goods_postage' => $goods_postage,
				'kd_price' => $kd_price,
				'es_price' => $es_price,
				'py_price' => $py_price,
				'transport_id' => $transport_id,
				'goods_promotion_type' => $goods_promotion_type,
				'goods_original_price' => $goods_original_price,
				'goods_group_number' => $goods_group_number,
				'goods_group_price' => $goods_group_price,
				'goods_show' => 1,
				'goods_state' => 3,
				'goods_add_time' => time(),
			);
			$goods_id = DB::insert('goods', $goods_data, 1);
			if(!empty($goods_id)) {
				if(!empty($spec)) {
					foreach($spec as $key => $value) {
						$param = array();
						$param['goods_id'] = $goods_id;
						$param['spec_name'] = empty($spec_name) ? '' : serialize($spec_name);
						$param['spec_goods_spec'] = empty($value['sp_value']) ? '' : serialize($value['sp_value']);
						$param['spec_goods_price'] = floatval($value['price']);
						$param['spec_goods_storage'] = intval($value['storage']);
						$param['spec_goods_serial'] = $value['serial'];
						$insert_id = DB::insert('goods_spec', $param, 1);
						$spec_id = empty($spec_id) ? $insert_id : $spec_id;
						$spec_open = 1;
					}
				} else {
					$param = array();
					$param['goods_id'] = $goods_id;
					$param['spec_name'] = '';
					$param['spec_goods_spec'] = '';
					$param['spec_goods_price'] = $goods_data['goods_price'];
					$param['spec_goods_storage'] = $goods_data['goods_storage'];
					$param['spec_goods_serial'] = $goods_data['goods_serial'];
					$spec_id = DB::insert('goods_spec', $param, 1);
					$spec_open = 0;
				}
				DB::update('goods', array('spec_id'=>$spec_id, 'spec_open'=>$spec_open), array('goods_id'=>$goods_id));
			}
			$this->showmessage('发布成功，等待管理员审核', 'index.php?act=store_goods&op=goods_unshow&state=pending');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('class')." WHERE class_type='goods' ORDER BY class_sort ASC");
			while($value = DB::fetch($query)) {
				$class_list[] = $value;	
			}
			$query = DB::query("SELECT * FROM ".DB::table('spec')." WHERE store_id='$this->store_id' ORDER BY sp_sort ASC");
			while($value = DB::fetch($query)) {
				$spec_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('transport')." WHERE store_id='$this->store_id' ORDER BY upgrade_time DESC");
			while($value = DB::fetch($query)) {
				$transport_list[] = $value;
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_goods_add'));
		}	
	}
	
	public function editOp() {
		if(submitcheck()) {
			$goods_id = empty($_POST['goods_id']) ? 0 : intval($_POST['goods_id']);
			$goods_name = empty($_POST['goods_name']) ? '' : $_POST['goods_name'];
			$class_id = empty($_POST['class_id']) ? 0 : intval($_POST['class_id']);
			$brand_id = empty($_POST['brand_id']) ? 0 : intval($_POST['brand_id']);
			$type_id = empty($_POST['type_id']) ? 0 : intval($_POST['type_id']);
			$goods_price = empty($_POST['goods_price']) ? 0 : floatval($_POST['goods_price']);
			$goods_price_interval = empty($_POST['goods_price_interval']) ? '' : $_POST['goods_price_interval'];
			$goods_storage = empty($_POST['goods_storage']) ? 0 : intval($_POST['goods_storage']);
			$goods_serial = empty($_POST['goods_serial']) ? '' : $_POST['goods_serial'];
			$goods_postage = in_array($_POST['goods_postage'], array('freight', 'transport')) ? $_POST['goods_postage'] : 'freight';
			$kd_price = empty($_POST['kd_price']) ? 0 : floatval($_POST['kd_price']);
			$es_price = empty($_POST['es_price']) ? 0 : floatval($_POST['es_price']);
			$py_price = empty($_POST['py_price']) ? 0 : floatval($_POST['py_price']);
			$transport_id = empty($_POST['transport_id']) ? 0 : intval($_POST['transport_id']);
			$goods_image = empty($_POST['goods_image']) ? '' : $_POST['goods_image'];
			$goods_image_more = empty($_POST['image_1']) ? array() : $_POST['image_1'];
			$goods_body = empty($_POST['goods_body']) ? '' : $_POST['goods_body'];
			$spec = empty($_POST['spec']) ? array() : $_POST['spec'];
			$goods_attr = empty($_POST['goods_attr']) ? array() : $_POST['goods_attr'];
			$goods_promotion_type = in_array($_POST['goods_promotion_type'], array('normal', 'cheap', 'group')) ? $_POST['goods_promotion_type'] : 'normal';
			if($goods_promotion_type == 'cheap') {
				$goods_promotion_type = 1;
				$goods_original_price = empty($_POST['goods_original_price']) ? 0 : floatval($_POST['goods_original_price']);
			} elseif($goods_promotion_type == 'group') {
				$goods_promotion_type = 2;
				$goods_group_number = empty($_POST['goods_group_number']) ? 0 : intval($_POST['goods_group_number']);
				$goods_group_price = empty($_POST['goods_group_price']) ? 0 : floatval($_POST['goods_group_price']);
			} else {
				$goods_promotion_type = 0;
			}
			if(empty($spec)) {
				$spec_name = array();
				$spec_value = array();
				$spec_image = array();
			} else {
				$spec_name = empty($_POST['spec_name']) ? array() : $_POST['spec_name'];				
				$spec_value_array = empty($_POST['spec_value']) ? array() : $_POST['spec_value'];
				foreach($spec_value_array as $key => $value) {
					foreach($value as $subkey => $subvalue) {
						if(!empty($subvalue)) {
							$spec_value[$key][$subkey] = $subvalue;
						}
					}
				}
				$spec_image_array = empty($_POST['spec_image']) ? array() : $_POST['spec_image'];
				foreach($spec_image_array as $key => $value) {
					if(!empty($value)) {
						$spec_image[$key] = $value;
					}
				}		
			}
			if(empty($goods_name)) {
				$this->showmessage('请输入商品名称', 'index.php?act=store_goods&op=edit&goods_id='.$goods_id, 'error');
			}
			if($goods_price <= 0) {
				$this->showmessage('请输入商品价格', 'index.php?act=store_goods&op=edit&goods_id='.$goods_id, 'error');
			}
			if($goods_storage <= 0) {
				$this->showmessage('请输入商品库存', 'index.php?act=store_goods&op=edit&goods_id='.$goods_id, 'error');
			}
			$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." WHERE goods_id='$goods_id'");
			if(empty($goods) || $goods['store_id'] != $this->store_id) {
				$this->showmessage('商品不存在', 'index.php?act=store_goods', 'error');
			}
			$goods_data = array(
				'goods_name' => $goods_name,
				'class_id' => $class_id,
				'brand_id' => $brand_id,
				'type_id' => $type_id,
				'spec_open' => 1,
				'spec_name' => empty($spec_name) ? '' : serialize($spec_name),
				'spec_value' => empty($spec_value) ? '' : serialize($spec_value),
				'spec_image' => empty($spec_image) ? '' : serialize($spec_image),
				'goods_price' => $goods_price,
				'goods_price_interval' => $goods_price_interval,
				'goods_storage' => $goods_storage,
				'goods_serial' => $goods_serial,
				'goods_image' => $goods_image,
				'goods_image_more' => empty($goods_image_more) ? '' : serialize($goods_image_more),
				'goods_body' => $goods_body,
				'goods_attr' => empty($goods_attr) ? '' : serialize($goods_attr),
				'goods_postage' => $goods_postage,
				'kd_price' => $kd_price,
				'es_price' => $es_price,
				'py_price' => $py_price,
				'transport_id' => $transport_id,
				'goods_promotion_type' => $goods_promotion_type,
				'goods_original_price' => $goods_original_price,
				'goods_group_number' => $goods_group_number,
				'goods_group_price' => $goods_group_price,
			);
			DB::update('goods', $goods_data, array('goods_id'=>$goods_id));
			if(!empty($goods_id)) {
				$spec_id_array = array();
				if(!empty($spec)) {
					foreach($spec as $key => $value) {
						$spec_goods_spec = empty($value['sp_value']) ? '' : serialize($value['sp_value']);
						$spec_id = DB::result_first("SELECT spec_id FROM ".DB::table("goods_spec")." WHERE goods_id = '$goods_id' AND spec_goods_spec='".$spec_goods_spec."'");
						if(!empty($spec_id)) {
							$param = array();
							$param['goods_id'] = $goods_id;
							$param['spec_name']	= empty($spec_name) ? '' : serialize($spec_name);
							$param['spec_goods_spec'] = empty($value['sp_value']) ? '' : serialize($value['sp_value']);
							$param['spec_goods_price'] = floatval($value['price']);
							$param['spec_goods_storage'] = intval($value['storage']);
							$param['spec_goods_serial'] = $value['serial'];	
							DB::update('goods_spec', $param, array('spec_id'=>$spec_id));
							$spec_id_array[] = $spec_id;
						} else {
							$param = array();
							$param['goods_id'] = $goods_id;
							$param['spec_name']	= empty($spec_name) ? '' : serialize($spec_name);
							$param['spec_goods_spec'] = empty($value['sp_value']) ? '' : serialize($value['sp_value']);
							$param['spec_goods_price'] = floatval($value['price']);
							$param['spec_goods_storage'] = intval($value['storage']);
							$param['spec_goods_serial'] = $value['serial'];
							$insert_id = DB::insert('goods_spec', $param, 1);
							$spec_id_array[] = $insert_id;
						}
					}
					DB::query("DELETE FROM ".DB::table('goods_spec')." WHERE goods_id='$goods_id' AND spec_id not in ('".implode("','", $spec_id_array)."')");
					DB::update('goods', array('spec_id'=>$spec_id_array[0]), array('goods_id'=>$goods_id));
				} else {
					$param = array();
					$param['goods_id'] = $goods_id;
					$param['spec_name'] = '';
					$param['spec_goods_spec'] = '';
					$param['spec_goods_price'] = $goods_data['goods_price'];
					$param['spec_goods_storage'] = $goods_data['goods_storage'];
					$param['spec_goods_serial'] = $goods_data['goods_serial'];
					DB::update('goods_spec', $param, array('spec_id'=>$goods['spec_id']));
					DB::query("DELETE FROM ".DB::table('goods_spec')." WHERE goods_id='$goods_id' AND spec_id!='".$goods['spec_id']."'");
					DB::update('goods', array('spec_open'=>0), array('goods_id'=>$goods_id));
				}	
			}
			if($goods['goods_state'] == 1) {
				if($goods['goods_show'] == 1) {
					$this->showmessage('编辑成功', 'index.php?act=store_goods');
				} else {
					$this->showmessage('编辑成功', 'index.php?act=store_goods&op=goods_unshow');
				}	
			} elseif($goods['goods_state'] == 2) {
				$this->showmessage('编辑成功', 'index.php?act=store_goods&op=goods_unshow&state=illegal');
			} elseif($goods['goods_state'] == 3) {
				$this->showmessage('编辑成功', 'index.php?act=store_goods&op=goods_unshow&state=pending');
			}
		} else {
			$goods_id = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
			$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." WHERE goods_id='$goods_id' AND store_id='$this->store_id'");
			$goods['goods_image_more'] = empty($goods['goods_image_more']) ? array() : unserialize($goods['goods_image_more']);
			$goods['spec_image'] = empty($goods['spec_image']) ? array() : unserialize($goods['spec_image']);
			$goods['goods_attr'] = empty($goods['goods_attr']) ? array() : unserialize($goods['goods_attr']);
			$goods['goods_promotion_type'] = $goods['goods_promotion_type'] == 1 ? 'cheap' : ($goods['goods_promotion_type'] == 2 ? 'group' : 'normal');
			$query = DB::query("SELECT * FROM ".DB::table('class')." WHERE class_type='goods' ORDER BY class_sort ASC");
			while($value = DB::fetch($query)) {
				if($goods['class_id'] == $value['class_id']) {
					$class_name = $value['class_name'];
				}
				$class_list[] = $value;	
			}
			$query = DB::query("SELECT * FROM ".DB::table('spec')." WHERE store_id='$this->store_id' ORDER BY sp_sort ASC");
			while($value = DB::fetch($query)) {
				$store_spec_list[] = $value;
			}
			$sp_ids = array();
			$current_spec_name = empty($goods['spec_name']) ? array() : unserialize($goods['spec_name']);
			if(is_array($current_spec_name) && !empty($current_spec_name)) {
				foreach($current_spec_name as $key => $value) {
					$sp_ids[] = intval($key);	
				}
			}
			$query = DB::query("SELECT * FROM ".DB::table('spec')." WHERE sp_id in ('".implode("','", $sp_ids)."') ORDER BY sp_sort ASC");
			while($value = DB::fetch($query)) {
				$spec_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('spec_value')." WHERE sp_id in ('".implode("','", $sp_ids)."') ORDER BY sp_value_sort ASC");
			while($value = DB::fetch($query)) {
				$spec_value_list[$value['sp_id']][] = $value;
			}
			$sign_i = count($spec_list);
			$spec_checked_array = empty($goods['spec_value']) ? array() : unserialize($goods['spec_value']);
			foreach($spec_checked_array as $key => $value) {
				foreach($value as $subkey => $subvalue) {
					$spec_checked[$subkey] = $subvalue;
				}
			}
			$query = DB::query("SELECT * FROM ".DB::table('goods_spec')." WHERE goods_id='".$goods['goods_id']."'");
			while($value = DB::fetch($query)) {
				preg_match_all("/i:(\d+)/s", $value['spec_goods_spec'], $matchs);
				sort($matchs[1]);
				$id = str_replace(',','',implode(',',$matchs[1]));
				$spec_value['i_'.$id.'|price'] = $value['spec_goods_price'];
				$spec_value['i_'.$id.'|storage'] = $value['spec_goods_storage'];
				$spec_value['i_'.$id.'|serial'] = $value['spec_goods_serial'];
			}
			$query = DB::query("SELECT * FROM ".DB::table('transport')." WHERE store_id='$this->store_id' ORDER BY upgrade_time DESC");
			while($value = DB::fetch($query)) {
				if($goods['transport_id'] == $value['transport_id']) {
					$transport_name = $value['transport_name'];
				}
				$transport_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('brand')." WHERE class_id='".$goods['class_id']."' ORDER BY brand_sort ASC");
			while($value = DB::fetch($query)) {
				if($value['brand_id'] == $goods['brand_id']) {
					$brand_name = $value['brand_name'];	
				}
				$brand_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('type')." WHERE class_id='".$goods['class_id']."' ORDER BY type_sort ASC");
			while($value = DB::fetch($query)) {
				if($value['type_id'] == $goods['type_id']) {
					$type_name = $value['type_name'];	
				}
				$type_list[] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('attribute')." WHERE class_id='".$goods['class_id']."' ORDER BY attr_sort ASC");
			while($value = DB::fetch($query)) {
				$value['attr_value'] = empty($value['attr_value']) ? array() : unserialize($value['attr_value']);
				$attr_list[] = $value;
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_goods_edit'));
		}	
	}
	
	public function delOp() {
		if(submitcheck()) {
			$del_ids = empty($_POST['del_ids']) ? '' : $_POST['del_ids'];
			$del_ids = explode(',', $del_ids);
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $del_ids)."')");
			while($value = DB::fetch($query)) {
				if($value['store_id'] == $this->store_id) {
					$goods_ids[] = $value['goods_id'];
				}
			}
			if(empty($goods_ids)) {
				exit(json_encode(array('msg'=>'请至少选择一个商品')));	
			}
			DB::query("DELETE FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids)."')");
			DB::query("DELETE FROM ".DB::table('goods_spec')." WHERE goods_id in ('".implode("','", $goods_ids)."')");
			exit(json_encode(array('done'=>'true', 'del_ids'=>$goods_ids)));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function unshowOp() {
		if(submitcheck()) {
			$unshow_ids = empty($_POST['unshow_ids']) ? '' : $_POST['unshow_ids'];
			$unshow_ids = explode(',', $unshow_ids);
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $unshow_ids)."')");
			while($value = DB::fetch($query)) {
				if($value['store_id'] == $this->store_id) {
					$goods_ids[] = $value['goods_id'];
				}
			}
			if(empty($goods_ids)) {
				exit(json_encode(array('msg'=>'请至少选择一个商品')));	
			}
			DB::query("UPDATE ".DB::table('goods')." SET goods_show=0 WHERE goods_id in ('".implode("','", $goods_ids)."')");
			exit(json_encode(array('done'=>'true', 'unshow_ids'=>$goods_ids)));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function showOp() {
		if(submitcheck()) {
			$show_ids = empty($_POST['show_ids']) ? '' : $_POST['show_ids'];
			$show_ids = explode(',', $show_ids);
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $show_ids)."')");
			while($value = DB::fetch($query)) {
				if($value['store_id'] == $this->store_id) {
					$goods_ids[] = $value['goods_id'];
				}
			}
			if(empty($goods_ids)) {
				exit(json_encode(array('msg'=>'请至少选择一个商品')));	
			}
			DB::query("UPDATE ".DB::table('goods')." SET goods_show=1 WHERE goods_id in ('".implode("','", $goods_ids)."')");
			exit(json_encode(array('done'=>'true', 'show_ids'=>$goods_ids)));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function priceOp() {
		if(submitcheck()) {
			$goods_id = empty($_POST['goods_id']) ? 0 : intval($_POST['goods_id']);
			$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." WHERE goods_id='$goods_id'");
			if(empty($goods) || $goods['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'商品不存在')));
			}
			$min_price = $max_price = 0;
			$spec_price = empty($_POST['spec_price']) ? array() : $_POST['spec_price'];
			foreach($spec_price as $key => $value) {
				$min_price = empty($min_price) || $min_price>$value ? $value : $min_price;
				$max_price = empty($max_price) || $max_price<$value ? $value : $max_price;
				DB::update('goods_spec', array('spec_goods_price'=>$value), array('spec_id'=>$key, 'goods_id'=>$goods_id));
			}
			if($goods['spec_open'] == 1) {
				DB::update('goods', array('goods_price'=>$min_price, 'goods_price_interval'=>$min_price.' - '.$max_price), array('goods_id'=>$goods_id));	
			} else {
				DB::update('goods', array('goods_price'=>$min_price), array('goods_id'=>$goods_id));
			}
			exit(json_encode(array('done'=>'true', 'price'=>$min_price)));
		} else {
			$goods_id = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
			$query = DB::query("SELECT * FROM ".DB::table('goods_spec')." WHERE goods_id='$goods_id'");
			while($value = DB::fetch($query)) {
				$value['spec_goods_spec'] = empty($value['spec_goods_spec']) ? array() : unserialize($value['spec_goods_spec']);
				if(!empty($value['spec_goods_spec'])) {					
					$value['spec_goods_spec'] = implode(' ', $value['spec_goods_spec']);
				} else {
					$value['spec_goods_spec'] = '商品价格';
				}
				$spec_value[] = $value;
			}
			include(template('store_goods_price'));
		}
	}
	
	public function storageOp() {
		if(submitcheck()) {
			$goods_id = empty($_POST['goods_id']) ? 0 : intval($_POST['goods_id']);
			$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." WHERE goods_id='$goods_id'");
			if(empty($goods) || $goods['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'商品不存在')));
			}
			$spec_storage = empty($_POST['spec_storage']) ? array() : $_POST['spec_storage'];
			foreach($spec_storage as $key => $value) {
				$goods_storage += $value;
				DB::update('goods_spec', array('spec_goods_storage'=>$value), array('spec_id'=>$key, 'goods_id'=>$goods_id));
			}
			DB::update('goods', array('goods_storage'=>$goods_storage), array('goods_id'=>$goods_id));
			exit(json_encode(array('done'=>'true', 'storage'=>$goods_storage)));
		} else {
			$goods_id = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
			$query = DB::query("SELECT * FROM ".DB::table('goods_spec')." WHERE goods_id='$goods_id'");
			while($value = DB::fetch($query)) {
				$value['spec_goods_spec'] = empty($value['spec_goods_spec']) ? array() : unserialize($value['spec_goods_spec']);
				if(!empty($value['spec_goods_spec'])) {					
					$value['spec_goods_spec'] = implode(' ', $value['spec_goods_spec']);
				} else {
					$value['spec_goods_spec'] = '商品库存';
				}
				$spec_value[] = $value;
			}
			include(template('store_goods_storage'));
		}
	}
	
	public function attrOp() {
		$class_id = empty($_GET['class_id']) ? 0 : intval($_GET['class_id']);
		$query = DB::query("SELECT * FROM ".DB::table('brand')." WHERE class_id='$class_id' ORDER BY brand_sort ASC");
		while($value = DB::fetch($query)) {
			$brand_list[] = $value;
		}
		$query = DB::query("SELECT * FROM ".DB::table('type')." WHERE class_id='$class_id' ORDER BY type_sort ASC");
		while($value = DB::fetch($query)) {
			$type_list[] = $value;
		}
		$query = DB::query("SELECT * FROM ".DB::table('attribute')." WHERE class_id='$class_id' ORDER BY attr_sort ASC");
		while($value = DB::fetch($query)) {
			$value['attr_value'] = empty($value['attr_value']) ? array() : unserialize($value['attr_value']);
			$attr_list[] = $value;
		}
		$html = '';
		if(!empty($brand_list) || !empty($type_list) || !empty($attr_list)) {
			$html .= '<div class="edit-body-title">商品属性</div>';
			$html .= '<div class="edit-body-con">';
			$html .= '<div class="form-list">';
			if(!empty($brand_list)) {
				$html .= '<div class="form-item clearfix">';
				$html .= '<label>商品品牌：</label>';
				$html .= '<div class="form-item-value">';
				$html .= '<div class="select-class">';
				$html .= '<a href="javascript:;" class="select-choice">请选择<i class="select-arrow"></i></a>';
				$html .= '<div class="select-list" style="display: none">';
				$html .= '<ul>';
				$html .= '<li field_value="" field_key="brand_id">请选择</li>';
				foreach($brand_list as $key => $value) {
					$html .= '<li field_value="'.$value['brand_id'].'" field_key="brand_id">'.$value['brand_name'].'</li>';
				}
				$html .= '</ul>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '<input type="hidden" id="brand_id" name="brand_id" value="">';
				$html .= '</div>';
				$html .= '</div>';
			}
			if(!empty($type_list)) {
				$html .= '<div class="form-item clearfix">';
				$html .= '<label>商品类型：</label>';
				$html .= '<div class="form-item-value">';
				$html .= '<div class="select-class">';
				$html .= '<a href="javascript:;" class="select-choice">请选择<i class="select-arrow"></i></a>';
				$html .= '<div class="select-list" style="display: none">';
				$html .= '<ul>';
				$html .= '<li field_value="" field_key="type_id">请选择</li>';
				foreach($type_list as $key => $value) {
					$html .= '<li field_value="'.$value['type_id'].'" field_key="type_id">'.$value['type_name'].'</li>';
				}
				$html .= '</ul>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '<input type="hidden" id="type_id" name="type_id" value="">';
				$html .= '</div>';
				$html .= '</div>';
			}
			if(!empty($attr_list)) {
				foreach($attr_list as $key => $value) {
					if(!empty($value['attr_value'])) {
						$html .= '<div class="form-item clearfix">';
						$html .= '<label>'.$value['attr_name'].'：</label>';
						$html .= '<div class="form-item-value">';
						$html .= '<div class="select-class">';
						$html .= '<a href="javascript:;" class="select-choice">请选择<i class="select-arrow"></i></a>';
						$html .= '<div class="select-list" style="display: none">';
						$html .= '<ul>';
						$html .= '<li field_value="" field_key="attr_'.$value['attr_id'].'"">请选择</li>';
						foreach($value['attr_value'] as $subkey => $subvalue) {
							$html .= '<li field_value="'.$subvalue.'" field_key="attr_'.$value['attr_id'].'">'.$subvalue.'</li>';
						}
						$html .= '</ul>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<input type="hidden" id="attr_'.$value['attr_id'].'" name="goods_attr['.$value['attr_id'].']" value="">';
						$html .= '</div>';
						$html .= '</div>';
					} else {
						$html .= '<div class="form-item clearfix">';
						$html .= '<label>'.$value['attr_name'].'：</label>';
						$html .= '<div class="form-item-value">';
						$html .= '<input type="text" id="attr_'.$value['attr_id'].'" name="goods_attr['.$value['attr_id'].']" class="form-input w-100" value="">';
						$html .= '</div>';
						$html .= '</div>';
					}
				}
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}
		exit(json_encode(array('done'=>'true', 'html'=>$html)));
	}
	
	public function specOp() {
		$items = empty($_GET['items']) ? array() : $_GET['items'];
		foreach(explode(",", $items) as $key => $value) {
			$value = intval($value);
			if(!empty($value)) {
				$sp_ids[] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('spec')." WHERE sp_id in ('".implode("','", $sp_ids)."') ORDER BY sp_sort ASC");
		while($value = DB::fetch($query)) {
			if($value['store_id'] == $this->store_id) {
				$spec_list[] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('spec_value')." WHERE sp_id in ('".implode("','", $sp_ids)."') ORDER BY sp_value_sort ASC");
		while($value = DB::fetch($query)) {
			$spec_value_list[$value['sp_id']][] = $value;
		}
		$sign_i = count($spec_list);
		include(template('store_goods.spec'));
	}
	
	public function goodsOp() {
		$goods_ids_str = empty($_GET['goods_ids']) ? '' : $_GET['goods_ids'];
		$goods_ids = explode(",", $goods_ids_str);
		$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE store_id='$this->store_id' AND goods_show=1 AND goods_state=1 ORDER BY goods_add_time DESC");
		while($value = DB::fetch($query)) {
			$goods_list[] = $value;
		}
		include(template('store_goods.index'));
	}
	
	private function recursionspec($len, $sign) {
		if($len < $sign){
			echo "for (var i_".$len."=0; i_".$len."<spec_group_checked[".$len."].length; i_".$len."++){td_".(intval($len)+1)." = spec_group_checked[".$len."][i_".$len."];\n";
			$len++;
			$this->recursionSpec($len,$sign);
		} else {
			echo "var tmp_spec_td = new Array();\n";
			for($i=0; $i< $len; $i++){
				echo "tmp_spec_td[".($i)."] = td_".($i+1)."[1];\n";
			}
			echo "tmp_spec_td.sort(function(a,b){return a-b});\n";
			echo "var spec_bunch = 'i_';\n";
			for($i=0; $i< $len; $i++){
				echo "spec_bunch += tmp_spec_td[".($i)."];\n";
			}
			for($i=0; $i< $len; $i++){
				echo "str +='<td><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_".($i+1)."[1]+']\" value='+td_".($i+1)."[0]+' />'+td_".($i+1)."[0]+'</td>';\n";
			}
			echo "str +='<td><input class=\"form-input w-10-8\" type=\"text\" name=\"spec['+spec_bunch+'][price]\" data_type=\"price\" mall_type=\"'+spec_bunch+'|price\" value=\"\" /></td><td><input class=\"form-input w-10-8\" type=\"text\" name=\"spec['+spec_bunch+'][storage]\" data_type=\"storage\" mall_type=\"'+spec_bunch+'|storage\" value=\"\" /></td><td><input class=\"form-input w-10-8\" type=\"text\" name=\"spec['+spec_bunch+'][serial]\" data_type=\"serial\" mall_type=\"'+spec_bunch+'|serial\" value=\"\" /></td></tr>';\n";
			for($i=0; $i< $len; $i++){
				echo "}\n";
			}
		}
	}
}

?>