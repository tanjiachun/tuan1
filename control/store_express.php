<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_expressControl extends BaseStoreControl {
	public function indexOp() {
		if(submitcheck()) {
			$express_id = empty($_POST['express_id']) ? '' : $_POST['express_id'];
			foreach($express_id as $key => $value) {
				if(!empty($value)) {
					$store_express_array[] = intval($value);
				}
			}
			$store_express = empty($store_express_array) ? '' : serialize($store_express_array);
			DB::update('store', array('store_express'=>$store_express), array('store_id'=>$this->store_id));
			exit(json_encode(array('done'=>'true')));
		} else {
			$this->store['store_express'] = empty($this->store['store_express']) ? array() : unserialize($this->store['store_express']);
			$i = 1;
			$query = DB::query("SELECT * FROM ".DB::table('express')." ORDER BY express_order ASC");
			while($value = DB::fetch($query)) {
				$index = ceil($i/4);
				$express_list[$index][] = $value;
				$i++;
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_express'));
		}	
	}
}

?>