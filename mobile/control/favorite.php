<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class favoriteControl extends BaseMobileControl {
	public function indexOp() {
		$fav_id = empty($_POST['fav_id']) ? 0 : intval($_POST['fav_id']);
		$fav_type = !in_array($_POST['fav_type'], array('goods', 'nurse')) ? '' : $_POST['fav_type'];
		if(empty($fav_id) || empty($fav_type)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要关注的对象', 'data'=>array())));
		}
		$fav = DB::fetch_first("SELECT * FROM ".DB::table('favorite')." WHERE member_id='$this->member_id' AND fav_id='$fav_id' AND fav_type='$fav_type'");
		if(!empty($fav)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'您已经关注了', 'data'=>array())));
		}
		$data = array(
			'member_id' => $this->member_id,
			'fav_id' => $fav_id,
			'fav_type' => $fav_type,
			'fav_time' => time(),
		);
		DB::insert('favorite', $data);
		if($fav_type == 'goods') {
			DB::query("UPDATE ".DB::table('goods')." SET goods_favoritenum=goods_favoritenum+1 WHERE goods_id='$fav_id'");
		} elseif($fav_type == 'nurse') {
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_favoritenum=nurse_favoritenum+1 WHERE nurse_id='$fav_id'");
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'关注成功', 'data'=>array())));
	}
	
	public function cancelOp() {
		$fav_id = empty($_POST['fav_id']) ? 0 : intval($_POST['fav_id']);
		$fav_type = !in_array($_POST['fav_type'], array('goods', 'nurse')) ? '' : $_POST['fav_type'];
		if(empty($fav_id) || empty($fav_type)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要取消的对象', 'data'=>array())));
		}
		$fav = DB::fetch_first("SELECT * FROM ".DB::table('favorite')." WHERE member_id='$this->member_id' AND fav_id='$fav_id' AND fav_type='$fav_type'");
		if(empty($fav)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'您已经取消关注了', 'data'=>array())));
		}
		DB::delete('favorite', array('member_id'=>$this->member_id, 'fav_id'=>$fav_id, 'fav_type'=>$fav_type));
		if($fav_type == 'goods') {
			DB::query("UPDATE ".DB::table('goods')." SET goods_favoritenum=goods_favoritenum-1 WHERE goods_id='$fav_id'");
		} elseif($fav_type == 'nurse') {
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_favoritenum=nurse_favoritenum-1 WHERE nurse_id='$fav_id'");
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'取消成功', 'data'=>array())));	
	}
}

?>