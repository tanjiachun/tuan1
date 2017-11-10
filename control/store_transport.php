<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class store_transportControl extends BaseStoreControl {
	public function indexOp() {
		$extend_name = array(
			'kd' => $this->store['kd_rename'],
			'es' => $this->store['es_rename'],
			'py' => $this->store['py_rename'],
		);
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 10;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=store_transport";
		$wheresql = " WHERE store_id='$this->store_id'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('transport').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('transport').$wheresql." ORDER BY upgrade_time DESC LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$transport_ids[] = $value['transport_id'];
			$transport_list[] = $value;
		}
		if(!empty($transport_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('transport_extend')." WHERE transport_id in ('".implode("','", $transport_ids)."') ORDER BY extend_id ASC");
			while($value = DB::fetch($query)) {
				$extend_list[$value['transport_id']][$value['extend_type']][] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('store_transport'));
	}
	
	public function addOp() {
		if(submitcheck()) {
			$transport_name = empty($_POST['transport_name']) ? '' : $_POST['transport_name'];
			$extend_type = empty($_POST['extend_type']) ? array() : $_POST['extend_type'];
			$extend_area = empty($_POST['extend_area']) ? array() : $_POST['extend_area'];
			$extend_snum = empty($_POST['extend_snum']) ? array() : $_POST['extend_snum'];
			$extend_sprice = empty($_POST['extend_sprice']) ? array() : $_POST['extend_sprice'];
			$extend_xnum = empty($_POST['extend_xnum']) ? array() : $_POST['extend_xnum'];
			$extend_xprice = empty($_POST['extend_xprice']) ? array() : $_POST['extend_xprice'];
			if(empty($transport_name)) {
				exit(json_encode(array('msg'=>'请输入运费模板名称')));
			}	
			if(empty($extend_type)) {
				exit(json_encode(array('msg'=>'请选择邮寄方式')));
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE district_level=2 ORDER BY district_sort");
			while($value = DB::fetch($query)) {
				$city_list[$value['district_id']] = $value['parent_id'];	
			}
			$data = array(
				'store_id' => $this->store_id,
				'transport_name' => $transport_name,
				'upgrade_time' => time(),
			);
			$transport_id = DB::insert('transport', $data, 1);
			foreach($extend_type as $key => $item) {
				if(in_array($item, array('kd', 'es', 'py'))) {
					foreach($extend_area[$item] as $subkey => $subvalue) {
						$data = array(
							'transport_id' => $transport_id,
							'extend_type' => $item,
							'extend_snum' => $extend_snum[$item][$subkey],
							'extend_sprice' => $extend_sprice[$item][$subkey],
							'extend_xnum' => $extend_xnum[$item][$subkey],
							'extend_xprice' => $extend_xprice[$item][$subkey],
						);
						if(empty($subkey)) {
							$data['area_id'] = '';
							$data['top_area_id'] = '';
							$data['area_name'] = '全国';
							$data['is_default'] = 1;
							DB::insert('transport_extend', $data);
						} else {
							$areas = explode('|||', $extend_area[$item][$subkey]);
							if(!empty($areas[0]) && !empty($areas[1])) {
								$data['area_id'] = $areas[0];
								$data['area_name'] = $areas[1];
								$data['is_default'] = 2;
								$province = array();
								$tmp = explode(',', $areas[0]);
								if(!empty($tmp) && is_array($tmp)) {
									foreach($tmp as $t) {
										$pid = $city_list[$t];
										if(!empty($pid) && !in_array($pid, $province_ids)) {
											$province_ids[] = $pid;
										}
									}
								}
								if(count($province_ids)>0){
									$data['top_area_id'] = ','.implode(',', $province_ids).',';
								}else{
									$data['top_area_id'] = '';
								}
								DB::insert('transport_extend', $data);
							}
						}
					}
				}
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[$value['district_id']] = $value;	
			}
			$area_list = array(
				'0' => array('15', '10', '11', '12', '9'),
				'1' => array('3', '4', '5', '1', '2'),
				'2' => array('16', '17', '18', '14'),
				'3' => array('19', '13', '20', '21'),
				'4' => array('6', '8', '7'),
				'5' => array('27', '31', '28', '30', '29'),
				'6' => array('23', '25', '24', '26', '22'),
				'7' => array('33', '34', '32'),
			);
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE district_level=2 ORDER BY district_sort");
			while($value = DB::fetch($query)) {
				$city_list[$value['parent_id']][] = $value;	
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_transport_add'));
		}	
	}
	
	public function editOp() {
		if(submitcheck()) {
			$transport_id = empty($_POST['transport_id']) ? 0 : intval($_POST['transport_id']);
			$transport_name = empty($_POST['transport_name']) ? '' : $_POST['transport_name'];
			$extend_type = empty($_POST['extend_type']) ? array() : $_POST['extend_type'];
			$extend_area = empty($_POST['extend_area']) ? array() : $_POST['extend_area'];
			$extend_snum = empty($_POST['extend_snum']) ? array() : $_POST['extend_snum'];
			$extend_sprice = empty($_POST['extend_sprice']) ? array() : $_POST['extend_sprice'];
			$extend_xnum = empty($_POST['extend_xnum']) ? array() : $_POST['extend_xnum'];
			$extend_xprice = empty($_POST['extend_xprice']) ? array() : $_POST['extend_xprice'];
			if(empty($transport_name)) {
				exit(json_encode(array('msg'=>'请输入运费模板名称')));
			}	
			if(empty($extend_type)) {
				exit(json_encode(array('msg'=>'请选择邮寄方式')));
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE district_level=2 ORDER BY district_sort");
			while($value = DB::fetch($query)) {
				$city_list[$value['district_id']] = $value['parent_id'];	
			}
			$transport = DB::fetch_first("SELECT * FROM ".DB::table('transport')." WHERE transport_id='$transport_id'");
			if(empty($transport) || $transport['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'运费模版不存在')));
			}
			$data = array(
				'transport_name' => $transport_name,
				'upgrade_time' => time(),
			);
			DB::update('transport', $data, array('transport_id'=>$transport_id));
			DB::query("DELETE FROM ".DB::table('transport_extend')." WHERE transport_id='$transport_id'");
			foreach($extend_type as $key => $item) {
				if(in_array($item, array('kd', 'es', 'py'))) {
					foreach($extend_area[$item] as $subkey => $subvalue) {
						$data = array(
							'transport_id' => $transport_id,
							'extend_type' => $item,
							'extend_snum' => $extend_snum[$item][$subkey],
							'extend_sprice' => $extend_sprice[$item][$subkey],
							'extend_xnum' => $extend_xnum[$item][$subkey],
							'extend_xprice' => $extend_xprice[$item][$subkey],
						);
						if(empty($subkey)) {
							$data['area_id'] = '';
							$data['top_area_id'] = '';
							$data['area_name'] = '全国';
							$data['is_default'] = 1;
							DB::insert('transport_extend', $data);
						} else {
							$areas = explode('|||', $extend_area[$item][$subkey]);
							if(!empty($areas[0]) && !empty($areas[1])) {
								$data['area_id'] = $areas[0];
								$data['area_name'] = $areas[1];
								$data['is_default'] = 2;
								$province = array();
								$tmp = explode(',', $areas[0]);
								if(!empty($tmp) && is_array($tmp)) {
									foreach($tmp as $t) {
										$pid = $city_list[$t];
										if(!empty($pid) && !in_array($pid, $province_ids)) {
											$province_ids[] = $pid;
										}
									}
								}
								if(count($province_ids)>0){
									$data['top_area_id'] = ','.implode(',', $province_ids).',';
								}else{
									$data['top_area_id'] = '';
								}
								DB::insert('transport_extend', $data);
							}
						}
					}
				}
			}
			exit(json_encode(array('done'=>'true')));
		} else {
			$transport_id = empty($_GET['transport_id']) ? 0 : intval($_GET['transport_id']);
			$transport = DB::fetch_first("SELECT * FROM ".DB::table('transport')." WHERE transport_id='$transport_id' AND store_id='$this->store_id'");
			$query = DB::query("SELECT * FROM ".DB::table('transport_extend')." WHERE transport_id='".$transport['transport_id']."' ORDER BY extend_id ASC");
			while($value = DB::fetch($query)) {
				$value['extend_area'] = $value['area_id'].'|||'.$value['area_name'];
				if($value['is_default'] == 1) {
					$extend_list[$value['extend_type']][0] = $value;
				} else {
					$extend_list[$value['extend_type']][1][] = $value;
				}
				$extend_type[$value['extend_type']] = $value['extend_type'];
			}
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
			while($value = DB::fetch($query)) {
				$province_list[$value['district_id']] = $value;	
			}
			$area_list = array(
				'0' => array('15', '10', '11', '12', '9'),
				'1' => array('3', '4', '5', '1', '2'),
				'2' => array('16', '17', '18', '14'),
				'3' => array('19', '13', '20', '21'),
				'4' => array('6', '8', '7'),
				'5' => array('27', '31', '28', '30', '29'),
				'6' => array('23', '25', '24', '26', '22'),
				'7' => array('33', '34', '32'),
			);
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE district_level=2 ORDER BY district_sort");
			while($value = DB::fetch($query)) {
				$city_list[$value['parent_id']][] = $value;	
			}
			$curmodule = 'profile';
			$bodyclass = 'gray-bg';
			include(template('store_transport_edit'));
		}	
	}
	
	public function delOp() {
		if(submitcheck()) {
			$del_id = empty($_POST['del_id']) ? 0 : intval($_POST['del_id']);
			$transport = DB::fetch_first("SELECT * FROM ".DB::table('transport')." WHERE transport_id='$del_id'");
			if(empty($transport) || $transport['store_id'] != $this->store_id) {
				exit(json_encode(array('msg'=>'运费模版不存在')));
			}
			DB::query("DELETE FROM ".DB::table('transport')." WHERE transport_id='$del_id'");
			DB::query("DELETE FROM ".DB::table('transport_extend')." WHERE transport_id='$del_id'");
			exit(json_encode(array('done'=>'true')));
		} else {
			exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
		}
	}
	
	public function nameOp() {
		if(submitcheck()) {
			$field_array = array('kd'=>'kd_rename', 'es'=>'es_rename', 'py'=>'py_rename');
			$extend_type = !in_array($_POST['extend_type'], array('kd', 'es', 'py')) ? 'kd' : $_POST['extend_type'];
			$extend_name = empty($_POST['extend_name']) ? '' : $_POST['extend_name'];
			if($extend_type == 'kd') {
				if(empty($extend_name)) {
					exit(json_encode(array('msg'=>'请输入快递重命名')));
				}
				DB::update('store', array('kd_rename'=>$extend_name), array('store_id'=>$this->store_id));
			} elseif($extend_type == 'es') {
				if(empty($extend_name)) {
					exit(json_encode(array('msg'=>'请输入EMS重命名')));
				}
				DB::update('store', array('es_rename'=>$extend_name), array('store_id'=>$this->store_id));
			} elseif($extend_type == 'py') {
				if(empty($extend_name)) {
					exit(json_encode(array('msg'=>'请输入平邮重命名')));
				}
				DB::update('store', array('py_rename'=>$extend_name), array('store_id'=>$this->store_id));
			}
			exit(json_encode(array('done'=>'true', 'extend_name'=>$extend_name)));
		} else {
			$name_array = array('kd'=>'快递重命名', 'es'=>'EMS重命名', 'py'=>'平邮重命名');
			$extend_type = !in_array($_GET['extend_type'], array('kd', 'es', 'py')) ? 'kd' : $_GET['extend_type'];
			if($extend_type == 'kd') {
				$extend_name = $this->store['kd_rename'];
			} elseif($extend_type == 'es') {
				$extend_name = $this->store['es_rename'];
			} elseif($extend_type == 'py') {
				$extend_name = $this->store['py_rename'];
			}
			include(template('store_transport_name'));
		}
	}
}

?>