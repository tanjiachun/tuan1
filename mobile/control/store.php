<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class storeControl extends BaseMobileControl {
	public function indexOp() {
		$store_id = empty($_POST['store_id']) ? 0 : intval($_POST['store_id']);	
		$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE store_id='$store_id'");
		if(empty($store)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'店铺不存在', 'data'=>'')));
		}
		$goods_field = 'goods_id, goods_name, class_id, brand_id, type_id, goods_price, goods_original_price, goods_storage, goods_image, goods_score, goods_salenum, goods_viewnum, goods_favoritenum, goods_commentnum, goods_add_time';
		$query = DB::query("SELECT $goods_field FROM ".DB::table('goods')." WHERE store_id='".$store['store_id']."' AND goods_show=1 AND goods_state=1 ORDER BY goods_add_time DESC");
		while($value = DB::fetch($query)) {
			$brand_ids[] = $value['brand_id'];
			$type_ids[] = $value['type_id'];
			$value['goods_add_time'] = date('Y-m-d H:i', $value['goods_add_time']);
			$goods_list[] = $value;
		}
		$query = DB::query("SELECT * FROM ".DB::table('class')." WHERE class_type='goods' ORDER BY class_sort ASC");
		while($value = DB::fetch($query)) {
			$class_list[$value['class_id']] = $value;
		}
		if(!empty($brand_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('brand')." WHERE brand_id in ('".implode("','", $brand_ids)."')");
			while($value = DB::fetch($query)) {
				$brand_list[$value['brand_id']] = $value['brand_name'];
			}
		}
		if(!empty($type_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('type')." WHERE type_id in ('".implode("','", $type_ids)."')");
			while($value = DB::fetch($query)) {
				$type_list[$value['type_id']] = $value['type_name'];
			}
		}
		foreach($goods_list as $key => $value) {
			$goods_list[$key]['class_name'] = $class_list[$value['class_id']];
			$goods_list[$key]['brand_name'] = $brand_list[$value['brand_id']];
			$goods_list[$key]['type_name'] = $type_list[$value['type_id']];
		}
		$data = array(
			'store' => $store,
			'goods_list' => $goods_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function registerOp() {
		$store = DB::fetch_first("SELECT * FROM ".DB::table('store')." WHERE member_id='$this->member_id'");
		if(!empty($store)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'您已经申请商家了', 'data'=>array())));
		}
		$store_name = empty($_POST['store_name']) ? '' : $_POST['store_name'];
		$store_phone = empty($_POST['store_phone']) ? '' : $_POST['store_phone'];
		$store_cardid = empty($_POST['store_cardid']) ? '' : $_POST['store_cardid'];
		$store_qa_image = empty($_POST['store_qa_image']) ? '' : $_POST['store_qa_image'];
		$store_qa_image = empty($store_qa_image) ? array() : explode(',', $store_qa_image);
		if(empty($store_name)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'店铺名称必须填写', 'data'=>array())));
		}
		if(empty($store_cardid)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'身份证号码必须填写', 'data'=>array())));
		}
		if(!checkcard($store_cardid)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'身份证号码格式不正确', 'data'=>array())));
		}
		$data = array(
			'member_id' => $this->member_id,
			'store_name' => $store_name,
			'store_cardid' => $store_cardid,
			'store_qa_image' => empty($store_qa_image) ? '' : serialize($store_qa_image),
			'store_phone' => $store_phone,
			'kd_rename' => '快递',
			'es_rename' => 'EMS',
			'py_rename' => '平邮',
			'store_time' => time(),
		);
		$store_id = DB::insert('store', $data, 1);
		if(!empty($store_id)) {
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}
	}
}

?>