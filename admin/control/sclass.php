<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class sclassControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$class_id = empty($_POST['class_id']) ? array() : $_POST['class_id'];
			$class_name = empty($_POST['class_name']) ? array() : $_POST['class_name'];
			$class_sort = empty($_POST['class_sort']) ? array() : $_POST['class_sort'];
			$query = DB::query("SELECT * FROM ".DB::table("class")." WHERE class_type='store'");
			while($value = DB::fetch($query)) {
				$class_id_all[] = $value['class_id'];
			}
			foreach($class_name as $key => $value) {
				if(!empty($class_name[$key])) {
					$data = array(
						'class_name' => $class_name[$key],
						'class_sort' => intval($class_sort[$key]),
						'class_type' => 'store',						
					);
					if(empty($class_id[$key])) {
						DB::insert('class', $data);
					} else {
						DB::update('class', $data, array('class_id'=>$class_id[$key]));
					}
				}
			}
			$class_id_in = array_diff($class_id_all, $class_id);
			if(!empty($class_id_in)) {
				DB::query("DELETE FROM ".DB::table('class')." WHERE class_id in ('".implode("','", $class_id_in)."')");
			}
			showdialog('保存成功', 'admin.php?act=sclass', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("class")." WHERE class_type='store'");
			while($value = DB::fetch($query)) {
				$class_list[] = $value;	
			}
			include(template('store_class'));
		}
	}
}
?>