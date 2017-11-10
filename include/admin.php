<?php
if(!defined("InMall")) {
	exit("Access Invalid!");
}

class BaseAdminControl {
	protected $setting = array();
	protected $admin_id = 0;
	protected $admin = array();

	public function __construct() {
		$this->init_setting();
		$this->init_member();
	}
	
	private function init_setting() {
		if(file_exists(MALL_ROOT.'/cache/cache_setting.php')){
			require_once(MALL_ROOT."/cache/cache_setting.php");
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('setting'));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
		}
		$this->setting = $setting;
	}
	
	private function init_member() {
		$malladminauth = dgetcookie('malladminauth');
		$auth_data = explode('\t', authcode($malladminauth, 'DECODE'));		
		list($admin_password, $admin_id) = empty($auth_data) || count($auth_data) < 2 ? array('', '') : $auth_data;
		if(!empty($admin_password) && !empty($admin_id)) {
			$cookie_admin = DB::fetch_first("SELECT * FROM ".DB::table('admin')." WHERE admin_id='$admin_id'");
			if(!empty($cookie_admin) && $cookie_admin['admin_password'] == $admin_password) {
				$this->admin_id = $cookie_admin['admin_id'];
				$this->admin = $cookie_admin;
			}
		}
		if($_GET['act'] == 'login') {
			if(!empty($this->admin_id)) {
				@header("Location: admin.php");
				exit;
			}
		} else {
			if(empty($this->admin_id)) {
				@header("Location: admin.php?act=login");
				exit;	
			}
		}
		if(!in_array($_GET['act'], array('login', 'logout'))) {	
			$this->admin['admin_permission'] = explode('|', $this->admin['admin_permission']);
			if(!in_array($_GET['act'], $this->admin['admin_permission'])) {
				//$this->showmessage('您没有权限操作', '', 'error'); 注释掉 Jason 2017-11-9 以后添加上去，权限控制
			}
		}
	}
	
	protected function showmessage($msg, $url='', $msg_type='succ') {
		include template('showmessage');
		exit();
	}
}
?>