<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class articleControl extends BaseAdminControl {
	public function indexOp() {
		$article_title = empty($_GET['article_title']) ? '' : $_GET['article_title'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=article";
		if(!empty($article_title)) {			
			$mpurl .= "&article_title=".urlencode($article_title);
			$wheresql = " WHERE article_title like '%".$article_title."%'";	
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('article').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('article').$wheresql." ORDER BY article_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$article_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('article'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$article_title = empty($_POST['article_title']) ? '' : $_POST['article_title'];
			$article_content = empty($_POST['article_content']) ? '' : $_POST['article_content'];
			$article_sort = empty($_POST['article_sort']) ? 0 : intval($_POST['article_sort']);
			$article_recommend = empty($_POST['article_recommend']) ? 0 : intval($_POST['article_recommend']);
			if(empty($article_title)) {
				showdialog('请输入文章标题');
			}
			if(empty($article_content)) {
				showdialog('请输入文章内容');
			}
			$data = array(
				'article_title' => $article_title,
				'article_content' => $article_content,
				'article_sort' => $article_sort,
				'article_recommend' => $article_recommend,
				'article_time' => time(),
			);
			$article_id = DB::insert('article', $data, 1);
			showdialog('保存成功', 'admin.php?act=article', 'succ');
		} else {
			include(template('article_add'));
		}
	}
	
	public function editOp() {
		if(submitcheck()) {
			$article_id = empty($_POST['article_id']) ? 0 : intval($_POST['article_id']);
			$article_title = empty($_POST['article_title']) ? '' : $_POST['article_title'];
			$article_content = empty($_POST['article_content']) ? '' : $_POST['article_content'];
			$article_sort = empty($_POST['article_sort']) ? 0 : intval($_POST['article_sort']);
			$article_recommend = empty($_POST['article_recommend']) ? 0 : intval($_POST['article_recommend']);
			if(empty($article_title)) {
				showdialog('请输入文章标题');
			}
			if(empty($article_content)) {
				showdialog('请输入文章内容');
			}
			$data = array(
				'article_title' => $article_title,
				'article_content' => $article_content,
				'article_sort' => $article_sort,
				'article_recommend' => $article_recommend,
			);
			DB::update('article', $data, array('article_id'=>$article_id));
			showdialog('保存成功', 'admin.php?act=article', 'succ');
		} else {
			$article_id = empty($_GET['article_id']) ? 0 : intval($_GET['article_id']);
			$article = DB::fetch_first("SELECT * FROM ".DB::table("article")." WHERE article_id='$article_id'");
			include(template('article_edit'));
		}
	}
	
	public function delOp() {
		$article_ids = empty($_GET['article_ids']) ? '' : $_GET['article_ids'];
		$article_ids_arr = explode(",", $article_ids);
		foreach($article_ids_arr as $key => $value) {
			$article_ids_in[] = intval($value);
		}
		DB::query("DELETE FROM ".DB::table('article')." WHERE article_id in ('".implode("','", $article_ids_in)."')");
		showdialog('删除成功', 'admin.php?act=article', 'succ');
	}
}
?>