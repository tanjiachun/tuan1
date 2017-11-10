<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class commentControl extends BaseProfileControl {
	public function indexOp() {
		$level_array = array('good'=>'好评 ', 'middle'=>'中评', 'bad'=>'差评');
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 12;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=comment";
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$nurse_ids[] = $value['nurse_id'];
			$value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$comment_list[] = $value;
		}
		if(!empty($nurse_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
			while($value = DB::fetch($query)) {
				$nurse_list[$value['nurse_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('comment_nurse'));
	}
	
	public function goodsOp() {
		$level_array = array('good'=>'好评 ', 'middle'=>'中评', 'bad'=>'差评');
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 12;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=comment&op=goods";
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('goods_comment').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('goods_comment').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$goods_ids[] = $value['goods_id'];
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$comment_list[] = $value;
		}
		if(!empty($goods_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids)."')");
			while($value = DB::fetch($query)) {
				$goods_list[$value['goods_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('comment_goods'));
	}
	
	public function pensionOp() {
		$level_array = array('good'=>'好评 ', 'middle'=>'中评', 'bad'=>'差评');
		$person_array = array('1'=>'自理', '2'=>'半自理', '3'=>'全自理', '4'=>'特护');
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 12;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=comment&op=pension";
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_comment').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension_comment').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$pension_ids[] = $value['pension_id'];
			$value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
			$comment_list[] = $value;
		}
		if(!empty($pension_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('pension')." WHERE pension_id in ('".implode("','", $pension_ids)."')");
			while($value = DB::fetch($query)) {
				$pension_list[$value['pension_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('comment_pension'));
	}
}

?>