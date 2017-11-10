<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class oldageControl extends BaseMobileControl {
	public function indexOp() {
		$page = empty($_POST['page']) ? 0 : intval($_POST['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$wheresql = " WHERE member_id='$this->member_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('oldage').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('oldage').$wheresql." ORDER BY oldage_addtime DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$value['oldage_addtime'] = date('Y-m-d H:i', $value['oldage_addtime']);
			$oldage_list[] = $value;
		}
		$data = array(
			'oldage_amount' => $this->member['oldage_amount'],
			'oldage_list' => empty($oldage_list) ? array() : $oldage_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
}

?>