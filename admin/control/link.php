<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class linkControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$link_id = empty($_POST['link_id']) ? array() : $_POST['link_id'];
			$link_sort = empty($_POST['link_sort']) ? array() : $_POST['link_sort'];
			$link_name = empty($_POST['link_name']) ? array() : $_POST['link_name'];
			$link_url = empty($_POST['link_url']) ? array() : $_POST['link_url'];
			$query = DB::query("SELECT * FROM ".DB::table("link")." WHERE link_type='text'");
			while($value = DB::fetch($query)) {
				$link_id_all[] = $value['link_id'];
			}
			foreach($link_name as $key => $value) {
				if(!empty($link_name[$key])) {
					$data = array(
						'link_name' => $link_name[$key],
						'link_url' => $link_url[$key],
						'link_type' => 'text',
						'link_sort' => $link_sort[$key],						
					);
					if(empty($link_id[$key])) {
						DB::insert('link', $data);
					} else {
						DB::update('link', $data, array('link_id'=>$link_id[$key]));
					}
				}
			}
			$link_id_in = array_diff($link_id_all, $link_id);
			if(!empty($link_id_in)) {
				DB::query("DELETE FROM ".DB::table('link')." WHERE link_id in ('".implode("','", $link_id_in)."')");
			}
			showdialog('保存成功', 'admin.php?act=link', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("link")." WHERE link_type='text' ORDER BY link_id ASC");
			while($value = DB::fetch($query)) {
				$link_list[] = $value;	
			}
			include(template('link'));
		}
	}
}
?>