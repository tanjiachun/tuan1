<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class centerControl extends BaseProfileControl {
	public function indexOp() {
		$time = time();		
		$book_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id'");
		$order_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('order')." WHERE member_id='$this->member_id'");
		$bespoke_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_bespoke')." WHERE member_id='$this->member_id'");
		$coupon_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('coupon')." WHERE member_id='$this->member_id' AND coupon_starttime<=$time AND coupon_endtime>=$time AND coupon_state=0");
		$red_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND red_starttime<=$time AND red_endtime>=$time AND red_state=0");
		$card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$this->member['card_predeposit']." ORDER BY card_predeposit DESC");
		$query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' ORDER BY add_time DESC LIMIT 0, 3");
		while($value = DB::fetch($query)) {
			$nurse_ids[] = $value['nurse_id'];
			$book_list[] = $value;
		}
		if(!empty($nurse_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
			while($value = DB::fetch($query)) {
				$nurse_list[$value['nurse_id']] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('order')." WHERE member_id='$this->member_id' ORDER BY add_time DESC LIMIT 0, 3");
		while($value = DB::fetch($query)) {
			$order_ids[] = $value['order_id'];
			$order_list[] = $value;
		}
		if(!empty($order_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('order_address')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_address[$value['order_id']] = $value;
			}
			$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id in ('".implode("','", $order_ids)."')");
			while($value = DB::fetch($query)) {
				$order_goods[$value['order_id']][] = $value;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('pension_bespoke')." WHERE member_id='$this->member_id' ORDER BY add_time DESC LIMIT 0, 3");
		while($value = DB::fetch($query)) {
			$pension_ids[] = $value['pension_id'];
			$room_ids[] = $value['room_id'];
			$bespoke_list[] = $value;
		}
		if(!empty($pension_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $pension_ids)."')");
			while($value = DB::fetch($query)) {
				$pension_list[$value['pension_id']] = $value;
			}
		}
		if(!empty($room_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('pension_room')." WHERE room_id in ('".implode("','", $room_ids)."')");
			while($value = DB::fetch($query)) {
				$room_list[$value['room_id']] = $value;
			}
		}
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('center'));
	}
}

?>