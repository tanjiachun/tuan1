<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class packageControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$package_id = empty($_POST['package_id']) ? array() : $_POST['package_id'];
			$package_amount = empty($_POST['package_amount']) ? array() : $_POST['package_amount'];
			$discount_amount = empty($_POST['discount_amount']) ? array() : $_POST['discount_amount'];
			$query = DB::query("SELECT * FROM ".DB::table("pd_package"));
			while($value = DB::fetch($query)) {
				$package_id_all[] = $value['package_id'];
			}
			foreach($package_amount as $key => $value) {
				$package_amount[$key] = intval($package_amount[$key]);
				if(!empty($package_amount[$key])) {
					$data = array(
						'package_amount' => $package_amount[$key],
						'discount_amount' => intval($discount_amount[$key]),				
					);
					if(empty($package_id[$key])) {
						DB::insert('pd_package', $data);
					} else {
						DB::update('pd_package', $data, array('package_id'=>$package_id[$key]));
					}
				}
			}
			$package_id_in = array_diff($package_id_all, $package_id);
			if(!empty($package_id_in)) {
				DB::query("DELETE FROM ".DB::table('pd_package')." WHERE package_id in ('".implode("','", $package_id_in)."')");	
			}
			showdialog('保存成功', 'admin.php?act=package', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("pd_package")." ORDER BY package_amount ASC");
			while($value = DB::fetch($query)) {
				$package_list[] = $value;	
			}
			include(template('package'));
		}
	}
}
?>