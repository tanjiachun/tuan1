<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class gclassControl extends BaseAdminControl {
	public function indexOp() {
		$query = DB::query("SELECT * FROM ".DB::table("class")." WHERE class_type='goods' ORDER BY class_id ASC");
		while($value = DB::fetch($query)) {
			$class_list[] = $value;	
		}
		include(template('goods_class'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$class_name = empty($_POST['class_name']) ? '' : $_POST['class_name'];
			$class_sort = empty($_POST['class_sort']) ? 0 : intval($_POST['class_sort']);
			$brand_id = empty($_POST['brand_id']) ? array() : $_POST['brand_id'];
			$brand_sort = empty($_POST['brand_sort']) ? array() : $_POST['brand_sort'];
			$brand_name = empty($_POST['brand_name']) ? array() : $_POST['brand_name'];
			$type_id = empty($_POST['type_id']) ? array() : $_POST['type_id'];
			$type_sort = empty($_POST['type_sort']) ? array() : $_POST['type_sort'];
			$type_name = empty($_POST['type_name']) ? array() : $_POST['type_name'];
			$attr_id = empty($_POST['attr_id']) ? array() : $_POST['attr_id'];
			$attr_sort = empty($_POST['attr_sort']) ? array() : $_POST['attr_sort'];
			$attr_name = empty($_POST['attr_name']) ? array() : $_POST['attr_name'];
			$attr_value_name = empty($_POST['attr_value_name']) ? array() : $_POST['attr_value_name'];
			if(empty($class_name)) {
				showdialog('请输入分类名称');
			}
			$data = array(
				'class_name' => $class_name,
				'class_sort' => $class_sort,
				'class_type' => 'goods',
			);
			$class_id = DB::insert('class', $data, 1);
			foreach($brand_name as $key => $value) {
				if(!empty($brand_name[$key])) {
					$data = array(
						'brand_name' => $brand_name[$key],
						'class_id' => $class_id,
						'brand_sort' => $brand_sort[$key],
					);
					DB::insert('brand', $data);
				}	
			}
			foreach($type_name as $key => $value) {
				if(!empty($type_name[$key])) {
					$data = array(
						'type_name' => $type_name[$key],
						'class_id' => $class_id,
						'type_sort' => $type_sort[$key],
					);
					DB::insert('type', $data);
				}	
			}
			foreach($attr_name as $key => $value) {
				if(!empty($attr_name[$key])) {
					$attr_value = array();
					foreach($attr_value_name[$key] as $subkey => $subvalue) {
						if(!empty($attr_value_name[$key][$subkey])) {
							$attr_value[] = $attr_value_name[$key][$subkey];
						}
					}
					$data = array(
						'attr_name' => $attr_name[$key],
						'class_id' => $class_id,
						'attr_value' => empty($attr_value) ? '' : serialize($attr_value),
						'attr_sort' => $attr_sort[$key],
					);
					DB::insert('attribute', $data);
				}
			}
			showdialog('保存成功', 'admin.php?act=gclass', 'succ');
		} else {
			include(template('goods_class_add'));
		}
	}
	
	public function editOp() {
		if(submitcheck()) {
			$class_id = empty($_POST['class_id']) ? '' : $_POST['class_id'];
			$class_name = empty($_POST['class_name']) ? '' : $_POST['class_name'];
			$class_sort = empty($_POST['class_sort']) ? 0 : intval($_POST['class_sort']);
			$brand_id = empty($_POST['brand_id']) ? array() : $_POST['brand_id'];
			$brand_sort = empty($_POST['brand_sort']) ? array() : $_POST['brand_sort'];
			$brand_name = empty($_POST['brand_name']) ? array() : $_POST['brand_name'];
			$type_id = empty($_POST['type_id']) ? array() : $_POST['type_id'];
			$type_sort = empty($_POST['type_sort']) ? array() : $_POST['type_sort'];
			$type_name = empty($_POST['type_name']) ? array() : $_POST['type_name'];
			$attr_id = empty($_POST['attr_id']) ? array() : $_POST['attr_id'];
			$attr_sort = empty($_POST['attr_sort']) ? array() : $_POST['attr_sort'];
			$attr_name = empty($_POST['attr_name']) ? array() : $_POST['attr_name'];
			$attr_value_name = empty($_POST['attr_value_name']) ? array() : $_POST['attr_value_name'];
			if(empty($class_name)) {
				showdialog('请输入分类名称');
			}
			$data = array(
				'class_name' => $class_name,
				'class_sort' => $class_sort,
			);
			DB::update('class', $data, array('class_id'=>$class_id));
			$query = DB::query("SELECT * FROM ".DB::table("brand")." WHERE class_id='$class_id'");
			while($value = DB::fetch($query)) {
				$brand_id_all[] = $value['brand_id'];	
			}
			foreach($brand_name as $key => $value) {
				if(!empty($brand_name[$key])) {
					if(!empty($brand_id[$key])) {
						$data = array(
							'brand_name' => $brand_name[$key],
							'brand_sort' => $brand_sort[$key],
						);
						DB::update('brand', $data, array('brand_id'=>$brand_id[$key]));
					} else {									
						$data = array(
							'brand_name' => $brand_name[$key],
							'class_id' => $class_id,
							'brand_sort' => $brand_sort[$key],
						);
						DB::insert('brand', $data);
					}
				}	
			}
			$brand_id_in = array_diff($brand_id_all, $brand_id);
			if(!empty($brand_id_in)) {
				DB::query("DELETE FROM ".DB::table('brand')." WHERE brand_id in ('".implode("','", $brand_id_in)."')");	
			}
			$query = DB::query("SELECT * FROM ".DB::table("type")." WHERE class_id='$class_id'");
			while($value = DB::fetch($query)) {
				$type_id_all[] = $value['type_id'];	
			}
			foreach($type_name as $key => $value) {
				if(!empty($type_name[$key])) {
					if(!empty($type_id[$key])) {
						$data = array(
							'type_name' => $type_name[$key],
							'type_sort' => $type_sort[$key],
						);
						DB::update('type', $data, array('type_id'=>$type_id[$key]));
					} else {									
						$data = array(
							'type_name' => $type_name[$key],
							'class_id' => $class_id,
							'type_sort' => $type_sort[$key],
						);
						DB::insert('type', $data);
					}
				}	
			}
			$type_id_in = array_diff($type_id_all, $type_id);
			if(!empty($type_id_in)) {
				DB::query("DELETE FROM ".DB::table('type')." WHERE type_id in ('".implode("','", $type_id_in)."')");	
			}
			$query = DB::query("SELECT * FROM ".DB::table("attribute")." WHERE class_id='$class_id'");
			while($value = DB::fetch($query)) {
				$attr_id_all[] = $value['attr_id'];
			}
			foreach($attr_name as $key => $value) {
				if(!empty($attr_name[$key])) {
					$attr_value = array();
					foreach($attr_value_name[$key] as $subkey => $subvalue) {
						if(!empty($attr_value_name[$key][$subkey])) {
							$attr_value[] = $attr_value_name[$key][$subkey];
						}
					}
					if(!empty($attr_id[$key])) {
						$data = array(
							'attr_name' => $attr_name[$key],
							'attr_value' => empty($attr_value) ? '' : serialize($attr_value),
							'attr_sort' => $attr_sort[$key],
						);
						DB::update('attribute', $data, array('attr_id'=>$attr_id[$key]));
					} else {									
						$data = array(
							'attr_name' => $attr_name[$key],
							'class_id' => $class_id,
							'attr_value' => empty($attr_value) ? '' : serialize($attr_value),
							'attr_sort' => $attr_sort[$key],
						);
						DB::insert('attribute', $data);
					}
				}
			}
			$attr_id_in = array_diff($attr_id_all, $attr_id);
			if(!empty($attr_id_in)) {
				DB::query("DELETE FROM ".DB::table('attribute')." WHERE attr_id in ('".implode("','", $attr_id_in)."')");	
			}
			showdialog('保存成功', 'admin.php?act=gclass', 'succ');
		} else {
			$class_id = empty($_GET['class_id']) ? 0 : intval($_GET['class_id']);
			$class = DB::fetch_first("SELECT * FROM ".DB::table('class')." WHERE class_id='$class_id'");
			$query = DB::query("SELECT * FROM ".DB::table("brand")." WHERE class_id='$class_id' ORDER BY brand_id ASC");
			while($value = DB::fetch($query)) {
				$brand_list[] = $value;	
			}
			$query = DB::query("SELECT * FROM ".DB::table("type")." WHERE class_id='$class_id' ORDER BY type_id ASC");
			while($value = DB::fetch($query)) {
				$type_list[] = $value;	
			}
			$query = DB::query("SELECT * FROM ".DB::table("attribute")." WHERE class_id='$class_id' ORDER BY attr_id ASC");
			while($value = DB::fetch($query)) {
				$value['attr_value'] = empty($value['attr_value']) ? array() : unserialize($value['attr_value']);
				$attr_list[] = $value;	
			}
			include(template('goods_class_edit'));
		}
	}
	
	public function delOp() {
		$class_ids = empty($_GET['class_ids']) ? '' : $_GET['class_ids'];
		$class_ids_arr = explode(",", $class_ids);
		foreach($class_ids_arr as $key => $value) {
			$class_ids_in[] = intval($value);
		}
		DB::query("DELETE FROM ".DB::table('class')." WHERE class_id in ('".implode("','", $class_ids_in)."')");
		DB::query("DELETE FROM ".DB::table('brand')." WHERE class_id in ('".implode("','", $class_ids_in)."')");
		DB::query("DELETE FROM ".DB::table('attribute')." WHERE class_id in ('".implode("','", $class_ids_in)."')");
		showdialog('删除成功', 'admin.php?act=gclass', 'succ');
	}
}
?>