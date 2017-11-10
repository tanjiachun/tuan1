<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class adminControl extends BaseAdminControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 20;
		$start = ($page-1)*$perpage;
		$mpurl = "admin.php?act=admin";
		$search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
		if(!empty($search_name)) {
			$mpurl .= '&search_name='.urlencode($search_name);
			$wheresql .= " AND admin_name like '%".$search_name."%'";
		}
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('admin').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('admin').$wheresql." ORDER BY add_time ASC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$admin_list[] = $value;
		}
		$multi = simplepage($count, $perpage, $page, $mpurl);
		include(template('admin'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$admin_name = empty($_POST['admin_name']) ? '' : $_POST['admin_name'];
			$admin_password = empty($_POST['admin_password']) ? '' : $_POST['admin_password'];
			$admin_password2 = empty($_POST['admin_password2']) ? '' : $_POST['admin_password2'];
			$admin_permission_array = empty($_POST['admin_permission']) ? array() : $_POST['admin_permission'];
			$admin = DB::fetch_first("SELECT * FROM ".DB::table("admin")." WHERE admin_name='$admin_name'");
			if(!empty($admin)) {
				showdialog('用户名已存在');
			}
			if(empty($admin_password)) {
				showdialog('请输入新密码');
			}
			if($admin_password != $admin_password2) {
				showdialog('请保证两次密码一致');
			}
			foreach($admin_permission_array as $key => $value) {
				if(!empty($value)) {
					$admin_permission[] = $value;
				}
			}
			$data = array(
				'admin_name' => $admin_name,
				'admin_password' => md5($admin_password),
				'admin_permission' => implode('|', $admin_permission),
				'admin_system' => 0,
				'add_time' => time(),
			);
			$admin_id = DB::insert('admin', $data, 1);
			showdialog('保存成功', 'admin.php?act=admin', 'succ');
		} else {
			include(template('admin_add'));
		}
	}
	
	public function editOp() {
		if(submitcheck()) {
			$admin_id = empty($_POST['admin_id']) ? 0 : intval($_POST['admin_id']);
			$admin_password = empty($_POST['admin_password']) ? '' : $_POST['admin_password'];
			$admin_password2 = empty($_POST['admin_password2']) ? '' : $_POST['admin_password2'];
			$admin_permission_array = empty($_POST['admin_permission']) ? array() : $_POST['admin_permission'];
			if($admin_password != $admin_password2) {
				showdialog('请保证两次密码一致');
			}
			$data = array();
			if(!empty($admin_password)) {
				$data['admin_password'] = md5($admin_password);
			}
			foreach($admin_permission_array as $key => $value) {
				if(!empty($value)) {
					$admin_permission[] = $value;
				}
			}
			$data['admin_permission'] = implode('|', $admin_permission);
			DB::update('admin', $data, array('admin_id'=>$admin_id));
			showdialog('保存成功', 'admin.php?act=admin', 'succ');
		} else {
			$admin_id = empty($_GET['admin_id']) ? 0 : intval($_GET['admin_id']);
			$admin = DB::fetch_first("SELECT * FROM ".DB::table("admin")." WHERE admin_id='$admin_id'");
			$admin['admin_permission'] = explode('|', $admin['admin_permission']);
			include(template('admin_edit'));
		}
	}
	
	public function delOp() {
		$admin_ids = empty($_GET['admin_ids']) ? '' : $_GET['admin_ids'];
		$admin_ids_arr = explode(",", $admin_ids);
		foreach($admin_ids_arr as $key => $value) {
			$admin_ids_in[] = intval($value);
		}
		DB::query("DELETE FROM ".DB::table('admin')." WHERE admin_id in ('".implode("','", $admin_ids_in)."') AND admin_system=0");
		showdialog('删除成功', 'admin.php?act=admin', 'succ');
	}
}
?>