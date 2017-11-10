<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class relatedControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$link_id = empty($_POST['link_id']) ? array() : $_POST['link_id'];
			$link_sort = empty($_POST['link_sort']) ? array() : $_POST['link_sort'];
			$link_image = empty($_POST['link_image']) ? array() : $_POST['link_image'];
			$link_url = empty($_POST['link_url']) ? array() : $_POST['link_url'];
			$query = DB::query("SELECT * FROM ".DB::table("link")." WHERE link_type='image'");
			while($value = DB::fetch($query)) {
				$link_id_all[] = $value['link_id'];
			}
			foreach($link_image as $key => $value) {
				if(!empty($link_image[$key])) {
					$data = array(
						'link_image' => $link_image[$key],
						'link_url' => $link_url[$key],
						'link_type' => 'image',
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
			showdialog('保存成功', 'admin.php?act=related', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("link")." WHERE link_type='image' ORDER BY link_id ASC");
			while($value = DB::fetch($query)) {
				$link_list[] = $value;	
			}
			include(template('related'));
		}
	}
}
?>