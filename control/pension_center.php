<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pension_centerControl extends BasePensionControl {
	public function indexOp() {
		$pending_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_bespoke')." WHERE pension_id='$this->pension_id' AND bespoke_state=10");
		$payment_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_bespoke')." WHERE pension_id='$this->pension_id' AND bespoke_state=20");
		$finish_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_bespoke')." WHERE pension_id='$this->pension_id' AND bespoke_state=30");
		$cancel_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_bespoke')." WHERE pension_id='$this->pension_id' AND bespoke_state=0");
		$query = DB::query("SELECT * FROM ".DB::table('pension_bespoke')." WHERE pension_id='$this->pension_id' ORDER BY add_time DESC LIMIT 0, 8");
		while($value = DB::fetch($query)) {
			$room_ids[] = $value['room_id'];
			$bespoke_list[] = $value;
		}
		if(!empty($room_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('pension_room')." WHERE room_id in ('".implode("','", $room_ids)."')");
			while($value = DB::fetch($query)) {
				$room_list[$value['room_id']] = $value;
			}
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('pension_center'));
	}
}

?>