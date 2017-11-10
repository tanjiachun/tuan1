<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class profileControl extends BaseProfileControl {
	public function indexOp() {
		if(submitcheck()) {
			$member_nickname = empty($_POST['member_nickname']) ? '' : $_POST['member_nickname'];
			$member_avatar = empty($_POST['member_avatar']) ? '' : $_POST['member_avatar'];
			$member_sex = empty($_POST['member_sex']) ? 0 : intval($_POST['member_sex']);
			$member_birthyear = empty($_POST['member_birthyear']) ? 0 : intval($_POST['member_birthyear']);
			$member_birthmonth = empty($_POST['member_birthmonth']) ? 0 : intval($_POST['member_birthmonth']);
			$member_birthday = empty($_POST['member_birthday']) ? 0 : intval($_POST['member_birthday']);
			$member_truename = empty($_POST['member_truename']) ? '' : $_POST['member_truename'];
			$member_cardid = empty($_POST['member_cardid']) ? '' : $_POST['member_cardid'];
			$data = array(
				'member_nickname' => $member_nickname,
				'member_avatar' => $member_avatar,
				'member_sex' => $member_sex,
				'member_birthyear' => $member_birthyear,
				'member_birthmonth' => $member_birthmonth,
				'member_birthday' => $member_birthday,
				'member_truename' => $member_truename,
				'member_cardid' => $member_cardid,
			);
			DB::update('member', $data, array('member_id'=>$this->member_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('card')." ORDER BY card_predeposit ASC");
			while($value = DB::fetch($query)) {
				if($this->member['card_predeposit'] < $value['card_predeposit']) {
					break;
				} else {
					$card = $value;
				}
			}
			$current_year = date('Y');
			$days = 31;
			if(!empty($this->member['member_birthyear']) && !empty($this->member['member_birthmonth'])) {
				if($this->member['member_birthmonth'] == 2) {
					if($this->member['member_birthyear']%400 == 0 || ($this->member['member_birthyear']%4 == 0 && $this->member['member_birthyear']%100 != 0)) {
						$days = 29;
					} else {
						$days = 28;
					}
				} else {
					$months = array('1', '3', '5', '7', '8', '10', '12');
					if(in_array($this->member['member_birthmonth'], $months)) {
						$days = 31;
					} else {
						$days = 30;
					}
				}
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('profile'));
		}	
	}
}

?>