<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class tagControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$tag_id = empty($_POST['tag_id']) ? array() : $_POST['tag_id'];
			$tag_name = empty($_POST['tag_name']) ? array() : $_POST['tag_name'];
			$tag_sort = empty($_POST['tag_sort']) ? array() : $_POST['tag_sort'];
			$tag_score = empty($_POST['tag_score']) ? array() : $_POST['tag_score'];
			$query = DB::query("SELECT * FROM ".DB::table("nurse_tag"));
			while($value = DB::fetch($query)) {
				$tag_id_all[] = $value['tag_id'];
			}
			foreach($tag_name as $key => $value) {
				if(!empty($tag_name[$key])) {
					$data = array(
						'tag_name' => $tag_name[$key],
						'tag_sort' => $tag_sort[$key],
						'tag_score' => intval($tag_score[$key]),				
					);
					if(empty($tag_id[$key])) {
						DB::insert('nurse_tag', $data);
					} else {
						DB::update('nurse_tag', $data, array('tag_id'=>$tag_id[$key]));
					}
				}
			}
			$tag_id_in = array_diff($tag_id_all, $tag_id);
			if(!empty($tag_id_in)) {
				DB::query("DELETE FROM ".DB::table('nurse_tag')." WHERE tag_id in ('".implode("','", $tag_id_in)."')");	
			}
			showdialog('保存成功', 'admin.php?act=tag', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("nurse_tag")." ORDER BY tag_id ASC");
			while($value = DB::fetch($query)) {
				$tag_list[] = $value;	
			}
			include(template('nurse_tag'));
		}
	}
}
?>