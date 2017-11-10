<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class pension_roomControl extends BasePensionControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=pension_room";
		$wheresql = " WHERE pension_id='$this->pension_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('pension_room').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('pension_room').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$room_list[] = $value;
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('pension_room'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$room_name = empty($_POST['room_name']) ? '' : $_POST['room_name'];
			$room_image = empty($_POST['room_image']) ? '' : $_POST['room_image']; 
			$room_image_more = empty($_POST['room_image_more']) ? array() : $_POST['room_image_more']; 
			$room_price = empty($_POST['room_price']) ? 0 : intval($_POST['room_price']);
			$room_storage = empty($_POST['room_storage']) ? 0 : intval($_POST['room_storage']);
			$room_support = empty($_POST['room_support']) ? '' : $_POST['room_support'];
			$room_equipment = empty($_POST['room_equipment']) ? '' : $_POST['room_equipment'];
			$room_service = empty($_POST['room_service']) ? '' : $_POST['room_service'];
			$room_desc = empty($_POST['room_desc']) ? '' : $_POST['room_desc'];
			$room_sort = empty($_POST['room_sort']) ? 0 : intval($_POST['room_sort']);
			if(empty($room_name)) {
				exit(json_encode(array('id'=>'room_name', 'msg'=>'请输入你的房间名称')));
			}
			if(empty($room_image)) {
				exit(json_encode(array('id'=>'room_image', 'msg'=>'请上传你的房间图片')));	
			}
			if(empty($room_price)) {
				exit(json_encode(array('id'=>'room_price', 'msg'=>'请输入你的房间价格')));	
			}
			if(empty($room_storage)) {
				exit(json_encode(array('id'=>'room_storage', 'msg'=>'请输入你的床位数')));	
			}
			$data = array(
				'pension_id' => $this->pension_id,
				'room_name' => $room_name,
				'room_image' => $room_image,
				'room_image_more' => empty($room_image_more) ? '' : serialize($room_image_more),
				'room_price' => $room_price,
				'room_storage' => $room_storage,
				'room_support' => empty($room_support) ? '' : serialize($room_support),
				'room_equipment' => $room_equipment,
				'room_service' => $room_service,
				'room_desc' => $room_desc,
				'room_sort' => $room_sort,
				'room_addtime' => time(),
			);
			$room_id = DB::insert('pension_room', $data, 1);
			if(!empty($room_id)) {
				exit(json_encode(array('done'=>'true')));
			} else {
				exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
			}
		} else {
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('pension_room_add'));
		}
	}
	
	public function editOp() {
		if(submitcheck()) {
			$room_id = empty($_POST['room_id']) ? 0 : intval($_POST['room_id']);
			$room_name = empty($_POST['room_name']) ? '' : $_POST['room_name'];
			$room_image = empty($_POST['room_image']) ? '' : $_POST['room_image']; 
			$room_image_more = empty($_POST['room_image_more']) ? array() : $_POST['room_image_more']; 
			$room_price = empty($_POST['room_price']) ? 0 : intval($_POST['room_price']);
			$room_storage = empty($_POST['room_storage']) ? 0 : intval($_POST['room_storage']);
			$room_support = empty($_POST['room_support']) ? '' : $_POST['room_support'];
			$room_equipment = empty($_POST['room_equipment']) ? '' : $_POST['room_equipment'];
			$room_service = empty($_POST['room_service']) ? '' : $_POST['room_service'];
			$room_desc = empty($_POST['room_desc']) ? '' : $_POST['room_desc'];
			$room_sort = empty($_POST['room_sort']) ? 0 : intval($_POST['room_sort']);
			if(empty($room_name)) {
				exit(json_encode(array('id'=>'room_name', 'msg'=>'请输入你的房间名称')));
			}
			if(empty($room_image)) {
				exit(json_encode(array('id'=>'room_image', 'msg'=>'请上传你的房间图片')));	
			}
			if(empty($room_price)) {
				exit(json_encode(array('id'=>'room_price', 'msg'=>'请输入你的房间价格')));	
			}
			if(empty($room_storage)) {
				exit(json_encode(array('id'=>'room_storage', 'msg'=>'请输入你的床位数')));	
			}
			$data = array(
				'room_name' => $room_name,
				'room_image' => $room_image,
				'room_image_more' => empty($room_image_more) ? '' : serialize($room_image_more),
				'room_price' => $room_price,
				'room_storage' => $room_storage,
				'room_support' => empty($room_support) ? '' : serialize($room_support),
				'room_equipment' => $room_equipment,
				'room_service' => $room_service,
				'room_desc' => $room_desc,
				'room_sort' => $room_sort,
			);
			DB::update('pension_room', $data, array('room_id'=>$room_id, 'pension_id'=>$this->pension_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$room_id = empty($_GET['room_id']) ? 0 : intval($_GET['room_id']);
			$room = DB::fetch_first("SELECT * FROM ".DB::table('pension_room')." WHERE room_id='$room_id' AND pension_id='$this->pension_id'");
			$room['room_image_more'] = empty($room['room_image_more']) ? array() : unserialize($room['room_image_more']);
			$room['room_support'] = empty($room['room_support']) ? array() : unserialize($room['room_support']);
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('pension_room_edit'));
		}
	}
	
	public function delOp() {
		if(submitcheck()) {
			$del_id = empty($_POST['del_id']) ? 0 : intval($_POST['del_id']);
			$room = DB::fetch_first("SELECT * FROM ".DB::table('pension_room')." WHERE room_id='$del_id'");
			if(empty($room) || $room['pension_id'] != $this->pension_id) {
				exit(json_encode(array('msg'=>'房间不存在')));
			}
			DB::query("DELETE FROM ".DB::table('pension_room')." WHERE room_id='$del_id'");
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
}

?>