<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class redControl extends BaseMobileControl {
	public function indexOp() {
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('red').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('red').$wheresql." ORDER BY red_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			if($value['red_state'] == 0 && $value['red_endtime']<time()) {
				$value['red_state'] = 2;	
			}
			$value['red_starttime'] = date('Y-m-d H:i', $value['red_starttime']);
			$value['red_endtime'] = date('Y-m-d H:i', $value['red_endtime']);
			$red_list[] = $value;
		}
		$data = array(
			'red_count' => $count,
			'red_list' => empty($red_list) ? array() : $red_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function getOp() {
		$red_t_id = empty($_POST['red_t_id']) ? 0 : intval($_POST['red_t_id']);
		$red_template = DB::fetch_first("SELECT * FROM ".DB::table('red_template')." WHERE red_t_id='$red_t_id'");
		if(empty($red_template) || $red_template['red_t_type'] != 'activity') {
			exit(json_encode(array('code'=>'1', 'msg'=>'红包不存在', 'data'=>array())));
		}
		if($red_template['red_rule_starttime'] > time()) {
			exit(json_encode(array('code'=>'1','msg'=>'领取活动未开始', 'data'=>array())));
		}
		if($red_template['red_rule_endtime'] < time()) {
			exit(json_encode(array('code'=>'1','msg'=>'领取活动已结束', 'data'=>array())));
		}
		if($red_template['red_t_total'] <= $red_template['red_t_giveout']) {
			exit(json_encode(array('code'=>'1','msg'=>'抱歉了，已经被领完了', 'data'=>array())));
		}
		$red = DB::fetch_first("SELECT * FROM ".DB::table("red")." WHERE member_id='$this->member_id' AND red_t_id='$red_t_id'");		
		if(!empty($red)) {
			exit(json_encode(array('code'=>'1','msg'=>'您已经领取过了', 'data'=>array())));
		}
		if($red_template['red_t_period_type'] == 'duration') {
			$red_template['red_t_starttime'] = strtotime(date('Y-m-d'));
			$red_template['red_t_endtime'] = $red_template['red_t_starttime']+3600*24*($red_template['red_t_days']+1)-1;
		}
		$red_data = array(
			'red_sn' => makesn(2),
			'member_id' => $this->member_id,
			'red_t_id' => $red_template['red_t_id'],
			'red_title' => $red_template['red_t_title'],
			'red_price' => $red_template['red_t_price'],
			'red_starttime' => $red_template['red_t_starttime'],
			'red_endtime' => $red_template['red_t_endtime'],
			'red_limit' => $red_template['red_t_limit'],
			'red_cate_id' => $red_template['red_t_cate_id'],
			'red_state' => 0,
			'is_read' => 1,
			'red_addtime' => time(),
		);
		$red_id = DB::insert('red', $red_data, 1);
		if(!empty($red_id)) {
			DB::update('red_template', array('red_t_giveout'=>$red_template['red_t_giveout']+1), array('red_t_id'=>$red_template['red_t_id']));
			exit(json_encode(array('code'=>'0', 'msg'=>'领取成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}	
	}
}

?>