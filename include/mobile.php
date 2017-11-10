<?php
if(!defined("InMall")) {
	exit("Access Invalid!");
}

class BaseMobileControl {
	protected $setting = array();
	protected $member_id = 0;
	protected $member = array();

	public function __construct() {
		$this->init_setting();
		// @todo 修改api所有页面请求
        if(!empty($_SERVER['HTTP_TOKEN'])){
            $this->init_member($_SERVER['HTTP_TOKEN']);
        }elseif(empty($_SERVER['HTTP_TOKEN']) && !empty($_POST['token'])){
            $this->init_member($_POST['token']);
        }

		$this->init_card();
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

    /**
     * 根据token获取会员相关信息
     */
	private function init_member($token) {
		// if(!in_array($_GET['act'], array('misc', 'register', 'login', 'forget', 'index'))) {
		if(!empty($token)) {
			if(empty($token)) {
				exit(json_encode(array('code'=>'1', 'msg'=>'用户不存在', 'data'=>(object)array())));
			}
			$this->member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_token='$token'");
			if(!empty($this->member)){
                $this->member_id = $this->member['member_id'];
            }

			if(empty($this->member_id)) {
//				exit(json_encode(array('code'=>'1', 'msg'=>'登陆状态已过期，请重新登陆。', 'data'=>(object)array())));
			}
		}
	}
	
	private function init_card() {
		if(!empty($this->member_id)) {
			$this->card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$this->member['member_score']." ORDER BY card_predeposit DESC");
		}
	}

	/**
     * 检测会员登陆状态
     */
	protected function check_authority(){
	    if(empty($this->member_id) || empty($this->member)){
            exit(json_encode(array('code'=> 1, 'msg'=>'请先登录', data=>(object)array())));
        }
    }

    /**
     * 过滤空值 // 在64位平台直接强制转换int类型即可
     */
    protected function get_num_var($num){
        return empty($num) ? 0 : $num;
    }

}
?>