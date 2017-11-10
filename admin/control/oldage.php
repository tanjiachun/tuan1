<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class oldageControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$first_oldage_rate = empty($_POST['first_oldage_rate']) ? 0 : floatval($_POST['first_oldage_rate']);
			$second_oldage_rate = empty($_POST['second_oldage_rate']) ? 0 : floatval($_POST['second_oldage_rate']);
			DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('first_oldage_rate', '".$first_oldage_rate."'), ('second_oldage_rate', '".$second_oldage_rate."')");
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
			showdialog('保存成功', 'admin.php?act=oldage', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			include(template('oldage'));
		}
	}
}
?>