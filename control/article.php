<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class articleControl extends BaseHomeControl {
	public function indexOp() {
		$article_id = empty($_GET['article_id']) ? 0 : intval($_GET['article_id']);
		if(!empty($article_id)) {
			$article = DB::fetch_first("SELECT * FROM ".DB::table('article')." WHERE article_id='$article_id'");
		} else {
			$article = DB::fetch_first("SELECT * FROM ".DB::table('article')." ORDER BY article_sort ASC");
		}
		$article['article_content'] = htmlspecialchars_decode($article['article_content']);
		$query = DB::query("SELECT * FROM ".DB::table('article')." ORDER BY article_sort ASC");
		while($value = DB::fetch($query)) {
			$article_list[] = $value;	
		}
		$curmodule = 'home';
		$bodyclass = '';
		include(template('article'));
	}
}

?>