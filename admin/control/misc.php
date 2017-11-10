<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class miscControl{
	public function uploadOp() {
		$field_id = empty($_POST['field_id']) ? 0 : intval($_POST['field_id']);
		$file_name = !in_array($_POST['file_name'], array('goods', 'store', 'plat', 'agent')) ? 'temp' : $_POST['file_name'];
		require_once(MALL_ROOT."/system/libraries/upload.php");
		$upload = new Upload();	
		$upload->init($_FILES[$_POST['id']], $file_name);
		$attach = $upload->attach;
		if(!$upload->error()) {
			$upload->save();
		}
		if($upload->error()) {
			exit(json_encode(array('msg'=>$upload->error())));
		}
		$file_path = 'data/'.$file_name.'/'.$attach['attachment'];
		exit(json_encode(array('done'=>'true', 'file_path'=>$file_path, 'field_id'=>$field_id)));	
	}
	
	public function nurseOp() {
		$type = !in_array($_GET['type'], array('inside', 'outside', 'illness', 'hour')) ? 'inside' : $_GET['type'];
		if($type == 'inside') {
			$nurse_type = 1;
		} elseif($type == 'outside') {
			$nurse_type = 2;
		} elseif($type == 'illness') {
			$nurse_type = 3;
		} elseif($type == 'hour') {
			$nurse_type = 4;
		}
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=misc&op=nurse&type=$type";
		$wheresql = " WHERE nurse_state=1 AND nurse_type=$nurse_type";
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_name like '%".$search_name."%'");
			while($value = DB::fetch($query)) {
				$agent_ids[] = $value['agent_id'];
			}
			if(!empty($agent_ids)) {
				$wheresql .= " AND (nurse_name like '%".$search_name."%' OR agent_id in ('".implode("','", $agent_ids)."'))";		
			} else {
				$wheresql .= " AND nurse_name like '%".$search_name."%'";
			}
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." ORDER BY nurse_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$agent_ids[] = $value['agent_id'];
			$nurse_list[] = $value;
		}
		if(!empty($agent_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
			while($value = DB::fetch($query)) {
				$agent_list[$value['agent_id']] = $value['agent_name'];
			}
		}
		$multi = simplemulti($count, $perpage, $page, $mpurl);
		include(template('nurse.index'));
	}
	
	public function goodsOp() {
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=misc&op=goods";
		$wheresql = " WHERE goods_show=1 AND goods_state=1";
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_name like '%".$search_name."%'");
			while($value = DB::fetch($query)) {
				$store_ids[] = $value['store_id'];
			}
			if(!empty($store_ids)) {
				$wheresql .= " AND (goods_name like '%".$search_name."%' OR store_id in ('".implode("','", $store_ids)."'))";		
			} else {
				$wheresql .= " AND goods_name like '%".$search_name."%'";
			}
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('goods').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('goods').$wheresql." ORDER BY goods_add_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$store_ids[] = $value['store_id'];
			$goods_list[] = $value;
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		$multi = simplemulti($count, $perpage, $page, $mpurl);
		include(template('goods.index'));
	}
	
	public function pensionOp() {
		$pension_scale = array('1'=>'50以下', '2'=>'50-100', '3'=>'100以上');
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=misc&op=pension";
		$wheresql = " WHERE pension_state=1";
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$wheresql .= " AND pension_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension').$wheresql." ORDER BY pension_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$pension_list[] = $value;
		}
		$multi = simplemulti($count, $perpage, $page, $mpurl);
		include(template('pension.index'));
	}
}
?>