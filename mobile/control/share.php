<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class shareControl {
	public function indexOp() {
		if(file_exists(MALL_ROOT.'/cache/cache_setting.php')){
			require_once(MALL_ROOT."/cache/cache_setting.php");
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('setting'));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
		}
		$nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
		$nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
		include(template('share'));
	}
}

?>