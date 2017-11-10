<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class cardControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$card_id = empty($_POST['card_id']) ? array() : $_POST['card_id'];
			$card_name = empty($_POST['card_name']) ? array() : $_POST['card_name'];
			$card_icon = empty($_POST['card_icon']) ? array() : $_POST['card_icon'];
			$card_predeposit = empty($_POST['card_predeposit']) ? array() : $_POST['card_predeposit'];
			$discount_rate = empty($_POST['discount_rate']) ? array() : $_POST['discount_rate'];
			$query = DB::query("SELECT * FROM ".DB::table("card"));
			while($value = DB::fetch($query)) {
				$card_id_all[] = $value['card_id'];
			}
			foreach($card_name as $key => $value) {
				if(!empty($card_name[$key])) {
					$data = array(
						'card_name' => $card_name[$key],
						'card_icon' => $card_icon[$key],
						'card_predeposit' => intval($card_predeposit[$key]),
						'discount_rate' => floatval($discount_rate[$key]),						
					);
					if(empty($card_id[$key])) {
						DB::insert('card', $data);
					} else {
						DB::update('card', $data, array('card_id'=>$card_id[$key]));
					}
				}
			}
			$card_id_in = array_diff($card_id_all, $card_id);
			if(!empty($card_id_in)) {
				DB::query("DELETE FROM ".DB::table('card')." WHERE card_id in ('".implode("','", $card_id_in)."')");	
			}
			showdialog('保存成功', 'admin.php?act=card', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("card")." ORDER BY card_predeposit ASC");
			while($value = DB::fetch($query)) {
				$card_list[] = $value;	
			}
			include(template('card'));
		}
	}
}
?>