<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class loginControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			
			$admin_name = empty($_POST['admin_name']) ? '' : $_POST['admin_name'];
			$admin_password = empty($_POST['admin_password']) ? '' : $_POST['admin_password'];
			$sec_code = empty($_POST['sec_code']) ? '' : $_POST['sec_code'];
			$cookietime = empty($_POST['cookietime']) ? 0 : intval($_POST['cookietime']);
			if(empty($admin_name)) {
				exit(json_encode(array('msg'=>'请输入用户名')));
			}
			if(empty($admin_password)) {
				exit(json_encode(array('msg'=>'请输入密码')));	
			}
//			if(empty($sec_code)) {
//				exit(json_encode(array('msg'=>'请输入验证码')));
//			}
			$seccodeinit = dgetcookie('seccodeinit');
			$onlineip = getip();
			$authkey = md5($GLOBALS['config']['security']['authkey'].$_SERVER['HTTP_USER_AGENT'].$onlineip);			
			$seccodeinit = authcode($seccodeinit, 'DECODE', $authkey);
//			include_once MALL_ROOT.'/system/libraries/seccode.php';
//			Seccode::seccodeconvert($seccodeinit);
//			if(empty($seccodeinit) || strtolower($seccodeinit) != strtolower($sec_code)) {
//				exit(json_encode(array('msg'=>'请输入正确的验证码')));
//			}
			$member = DB::fetch_first("SELECT * FROM ".DB::table('admin')." WHERE admin_name='$admin_name'");
			if(empty($member)) {
				exit(json_encode(array('msg'=>'用户名不存在')));	
			}
			if($member['admin_password'] != md5($admin_password)) {
				exit(json_encode(array('msg'=>'密码错误')));	
			}
			dsetcookie('malladminauth', authcode($member['admin_password'].'\t'.$member['admin_id'], 'ENCODE'), $cookietime ? 259200 : 0);
			$log_data = array(
				'log_desc' => '登录管理后台',
				'admin_id' => $member['admin_id'],
				'admin_name' => $member['admin_name'],
				'log_time' => time(),
			);
			DB::insert('admin_log', $log_data);
			exit(json_encode(array('done'=>'true')));
		} else {
			include(template('login'));
		}
	}
	
	public function seccodeOp() {
		$onlineip = getip();
		$authkey = md5($GLOBALS['config']['security']['authkey'].$_SERVER['HTTP_USER_AGENT'].$onlineip);
		$seccode = random(6, 1);
		$seccodeinit = authcode($seccode, 'ENCODE', $authkey);
		dsetcookie('seccodeinit', $seccodeinit, 360);
		ob_end_clean();
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");	
		include_once MALL_ROOT.'/system/libraries/seccode.php';
		$code = new Seccode();
		$code->code = $seccode;
		$code->type = 0;
		$code->width = 100;
		$code->height = 30;
		$code->background = 3;
		$code->adulterate = 1;
		$code->ttf = 1;
		$code->angle = 1;
		$code->warping = 0;
		$code->scatter = 0;
		$code->color = 1;
		$code->size = 0;
		$code->shadow = 1;
		$code->fontpath = MALL_ROOT.'/static/seccode/font/';
		$code->datapath = MALL_ROOT.'/static/seccode/';
		$code->display();
	}
}

?>