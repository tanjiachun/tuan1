<?php
if(!defined("InMall")) {
	exit("Access Invalid!");
}

class BaseAgentControl {
	protected $setting = array();
	protected $member_id = 0;
	protected $member = array();
	protected $agent_id = 0;
	protected $agent = array();
	protected $article_list = array();
	protected $link_list = array();

	public function __construct() {
		$this->init_setting();
		$this->init_member();
		$this->init_footer();		
		$this->init_login();
		$this->init_agent();		
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
		$mallauth = dgetcookie('mallauth');
		$auth_data = explode('\t', authcode($mallauth, 'DECODE'));		
		list($member_password, $member_id) = empty($auth_data) || count($auth_data) < 2 ? array('', '') : $auth_data;
		if(!empty($member_password) && !empty($member_id)) {
			$cookie_member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
			if(!empty($cookie_member) && $cookie_member['member_password'] == $member_password) {
				$this->member_id = $cookie_member['member_id'];
				$this->member = $cookie_member;
			}
		}
	}
	
	private function init_footer() {
		$query = DB::query("SELECT * FROM ".DB::table('article')." WHERE article_recommend=1 ORDER BY article_sort ASC");
		while($value = DB::fetch($query)) {
			$this->article_list[] = $value;	
		}
		$query = DB::query("SELECT * FROM ".DB::table('link')." ORDER BY link_sort ASC");
		while($value = DB::fetch($query)) {
			$this->link_list[$value['link_type']][] = $value;	
		}
		$this->setting['first_province_list'] = empty($this->setting['first_province_list']) ? array() : unserialize($this->setting['first_province_list']);
		$this->setting['second_province_list'] = empty($this->setting['second_province_list']) ? array() : unserialize($this->setting['second_province_list']);
	}
	
	private function init_login() {
		if(empty($this->member_id)) {
			$this->showmessage('您还未登录', 'index.php?act=login', 'info');
		}
		
	}
	
	private function init_agent() {
		$this->agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
		$this->agent_id = empty($this->agent['agent_id']) ? 0 : $this->agent['agent_id'];
		if(empty($this->agent_id)) {
			$this->showmessage('您还不是家政机构', 'index.php?act=agent&op=register', 'info');
		}
		if($this->agent['agent_state'] != 1) {
			$this->showmessage('您还没通过审核，请耐心等待', '', 'info');
		}
		
	}
	
	protected function showmessage($msg, $url='', $msg_type='succ') {
		$curmodule = 'home';
		$bodyclass = 'gray-bg';
		include template('showmessage');
		exit();
	}
}
?>