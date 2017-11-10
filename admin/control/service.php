<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class serviceControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$service_id = empty($_POST['service_id']) ? array() : $_POST['service_id'];
			$service_name = empty($_POST['service_name']) ? array() : $_POST['service_name'];
			$service_sort = empty($_POST['service_sort']) ? array() : $_POST['service_sort'];
			$service_price = empty($_POST['service_price']) ? array() : $_POST['service_price'];
			$query = DB::query("SELECT * FROM ".DB::table("nurse_service"));
			while($value = DB::fetch($query)) {
				$service_id_all[] = $value['service_id'];
			}
			foreach($service_name as $key => $value) {
				if(!empty($service_name[$key])) {
					$data = array(
						'service_name' => $service_name[$key],
						'service_sort' => $service_sort[$key],
						'service_price' => intval($service_price[$key]),				
					);
					if(empty($service_id[$key])) {
						DB::insert('nurse_service', $data);
					} else {
						DB::update('nurse_service', $data, array('service_id'=>$service_id[$key]));
					}
				}
			}
			$service_id_in = array_diff($service_id_all, $service_id);
			if(!empty($service_id_in)) {
				DB::query("DELETE FROM ".DB::table('nurse_service')." WHERE service_id in ('".implode("','", $service_id_in)."')");	
			}
			showdialog('保存成功', 'admin.php?act=service', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("nurse_service")." ORDER BY service_id ASC");
			while($value = DB::fetch($query)) {
				$service_list[] = $value;	
			}
			include(template('nurse_service'));
		}
	}
}
?>