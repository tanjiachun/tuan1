<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_specControl extends BaseStoreControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_spec";
		$wheresql = " WHERE store_id='$this->store_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('spec').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('spec').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$spec_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_spec'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$sp_name = empty($_POST['sp_name']) ? '' : $_POST['sp_name'];
			$sp_format = empty($_POST['sp_format']) ? '' : $_POST['sp_format'];
			$sp_sort = empty($_POST['sp_sort']) ? 0 : intval($_POST['sp_sort']);
			$sp_value_id = empty($_POST['sp_value_id']) ? array() : $_POST['sp_value_id'];
			$sp_value_sort = empty($_POST['sp_value_sort']) ? array() : $_POST['sp_value_sort'];
			$sp_value_name = empty($_POST['sp_value_name']) ? array() : $_POST['sp_value_name'];
			if(empty($sp_name)) {
				exit(json_encode(array('msg'=>'请输入规格名称')));
			}
			$sp_value_arr = array();
			foreach($sp_value_name as $key => $value) {
				if(!empty($value)) {
					$sp_value_arr[] = $value;	
				}
			}
			if(empty($sp_value_arr)) {
				exit(json_encode(array('msg'=>'请至少填写一个规格值')));
			}
			$data = array(
				'store_id' => $this->store_id,
				'sp_name' => $sp_name,
				'sp_value' => implode(',', $sp_value_arr),
				'sp_format' => $sp_format,
				'sp_sort' => $sp_sort,
			);
			$sp_id = DB::insert('spec', $data, 1);
			foreach($sp_value_name as $key => $value) {
				if(!empty($value)) {
					$data = array(
						'sp_id' => $sp_id,
						'sp_value_name' => $value,						
						'sp_value_sort' => $sp_value_sort[$key],
					);
					DB::insert('spec_value', $data);
				}
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_spec_add'));
		}
	}
	
	public function editOp() {
		if(submitcheck()) {
			$sp_id = empty($_POST['sp_id']) ? 0 : intval($_POST['sp_id']);
			$sp_name = empty($_POST['sp_name']) ? '' : $_POST['sp_name'];
			$sp_format = empty($_POST['sp_format']) ? '' : $_POST['sp_format'];
			$sp_sort = empty($_POST['sp_sort']) ? 0 : intval($_POST['sp_sort']);
			$sp_value_id = empty($_POST['sp_value_id']) ? array() : $_POST['sp_value_id'];
			$sp_value_sort = empty($_POST['sp_value_sort']) ? array() : $_POST['sp_value_sort'];
			$sp_value_name = empty($_POST['sp_value_name']) ? array() : $_POST['sp_value_name'];
			if(empty($sp_name)) {
				exit(json_encode(array('msg'=>'请输入规格名称')));
			}
			$sp_value_arr = array();
			foreach($sp_value_name as $key => $value) {
				if(!empty($value)) {
					$sp_value_arr[] = $value;	
				}
			}
			if(empty($sp_value_arr)) {
				exit(json_encode(array('msg'=>'请至少填写一个规格值')));
			}
			$sp = DB::fetch_first("SELECT * FROM ".DB::table('spec')." WHERE sp_id='$sp_id'");
			if(empty($sp) || $sp['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'规格不存在')));
			}
			$query = DB::query("SELECT * FROM ".DB::table('spec_value')." WHERE sp_id='".$sp_id."'");
			while($value = DB::fetch($query)) {
				$sp_value_id_all[] = $value['sp_value_id'];
			}	
			foreach($sp_value_name as $key => $value) {
				if(!empty($value)) {
					if(!empty($sp_value_id[$key])) {
						$data = array(
							'sp_value_name' => $value,
							'sp_value_sort' => $sp_value_sort[$key],
						);
						DB::update('spec_value', $data, array('sp_value_id'=>intval($sp_value_id[$key]), 'sp_id'=>$sp_id));
					} else {									
						$data = array(
							'sp_id' => $sp_id,
							'sp_value_name' => $value,							
							'sp_value_sort' => $sp_value_sort[$key],
						);
						DB::insert('spec_value', $data);
					}
				}
			}
			$sp_value_id_in = array_diff($sp_value_id_all, $sp_value_id);
			if(!empty($sp_value_id_in)) {
				DB::query("DELETE FROM ".DB::table('spec_value')." WHERE sp_value_id in ('".implode("','", $sp_value_id_in)."')");	
			}		
			$data = array(
				'sp_name' => $sp_name,
				'sp_value' => implode(',', $sp_value_arr),
				'sp_format' => $sp_format,
				'sp_sort' => $sp_sort,
			);
			DB::update('spec', $data, array('sp_id'=>$sp_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$sp_id = empty($_GET['sp_id']) ? 0 : intval($_GET['sp_id']);
			$sp = DB::fetch_first("SELECT * FROM ".DB::table('spec')." WHERE sp_id='$sp_id' AND store_id='$this->store_id'");
			$query = DB::query("SELECT * FROM ".DB::table('spec_value')." WHERE sp_id='".$sp['sp_id']."' ORDER BY sp_value_id ASC");
			while($value = DB::fetch($query)) {
				$spec_value_list[] = $value;
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_spec_edit'));
		}
	}
	
	public function delOp() {
		if(submitcheck()) {
			$del_id = empty($_POST['del_id']) ? 0 : intval($_POST['del_id']);
			$sp = DB::fetch_first("SELECT * FROM ".DB::table('spec')." WHERE sp_id='$del_id'");
			if(empty($sp) || $sp['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'规格不存在')));
			}
			DB::query("DELETE FROM ".DB::table('spec')." WHERE sp_id='$del_id'");
			DB::query("DELETE FROM ".DB::table('spec_value')." WHERE sp_id='$del_id'");
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
}

?>