<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class cartControl extends BaseMobileControl {
	public function indexOp() {
		$cart_field = 'cart.*, goods.goods_name, goods.goods_image, goods.spec_open, goods.goods_show, goods.goods_state, goods_spec.spec_name, goods_spec.spec_goods_spec, goods_spec.spec_goods_price, goods_spec.spec_goods_storage';
		$query = DB::query("SELECT $cart_field FROM ".DB::table('cart')." as cart LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON cart.spec_id=goods_spec.spec_id LEFT JOIN ".DB::table('goods')." as goods ON goods_spec.goods_id=goods.goods_id WHERE cart.member_id='$this->member_id'");
		while($value = DB::fetch($query)) {
			if($value['goods_show'] == 1 && $value['goods_state'] == 1 && $value['spec_goods_storage'] > 0) {
				$value['spec_info'] = array();
				if($value['spec_open'] == 1 && !empty($value['spec_name']) && !empty($value['spec_goods_spec'])) {
					$spec_name = empty($value['spec_name']) ? array() : unserialize($value['spec_name']);
					if(!empty($spec_name)) {
						$spec_name = array_values($spec_name);
						$spec_goods_spec = empty($value['spec_goods_spec']) ? array() : unserialize($value['spec_goods_spec']);
						$i = 0;
						foreach($spec_goods_spec as $key => $value) {
							$value['spec_info'][] = $spec_name[$i].":".$value;
							$i++;
						}
					}
				}
				$value['spec_goods_price'] = $this->card['discount_rate'] > 0 ? $value['spec_goods_price']*$this->card['discount_rate']/10 : $value['spec_goods_price'];
				$value['spec_goods_price'] = priceformat($value['spec_goods_price']);
				if($value['goods_num'] > $value['spec_goods_storage']) {
					$value['goods_num'] = $value['spec_goods_storage'];
				}
				$cart_result[] = $value;
			}
		}
		$goods_num = $goods_amount = 0;
		$store_ids = $cart_goods_list = array();
		foreach($cart_result as $key => $value) {
			$goods_num++;
			$goods_amount += $value['spec_goods_price']*$value['goods_num'];	
			$store_ids[$value['store_id']] = $value['store_id'];
			$cart_goods_list[$value['store_id']][] = $value;
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		foreach($store_ids as $store_id) {
			$cart_list[] = array(
				'store_id' => $store_id,
				'store_name' => $store_list[$store_id],
				'goods_list' => $cart_goods_list[$store_id],
			);
		}
		$data = array(
			'goods_num' => $goods_num,
			'goods_amount' => $goods_amount,
			'cart_list' => $cart_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function addOp() {
		$spec_id = empty($_POST['spec_id']) ? 0 : intval($_POST['spec_id']);
		$quantity = empty($_POST['quantity']) ? 0 : intval($_POST['quantity']);
		if($spec_id <= 0) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品不存在', 'data'=>array())));
		}
		if($quantity <= 0) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请填写购买数量', 'data'=>array())));
		}
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='$spec_id'");
		if(empty($goods)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品不存在', 'data'=>array())));
		}
		if($goods['goods_show'] != 1 || $goods['goods_state'] != 1) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品已下架', 'data'=>array())));
		}
		if($goods['spec_goods_storage'] < 1) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品已经售完', 'data'=>array())));
		}
		if($goods['spec_goods_storage'] < $quantity) {
			exit(json_encode(array('code'=>'1', 'msg'=>'最多购买'.$goods['spec_goods_storage'].'件', 'data'=>array())));
		}
		$cart = DB::fetch_first("SELECT * FROM ".DB::table('cart')." WHERE member_id='$this->member_id' AND spec_id='$spec_id'");
		if(empty($cart)) {
			$cart_data = array();
			$cart_data['member_id']	= $this->member_id;
			$cart_data['goods_id'] = $goods['goods_id'];
			$cart_data['store_id'] = $goods['store_id'];
			$cart_data['spec_id'] = $goods['spec_id'];
			$cart_data['goods_num']	= $quantity;
			DB::insert('cart', $cart_data);
			$cart_exist = 1;
		} else {
			$goods_num = $cart['goods_num']+$quantity;
			DB::update('cart', array('goods_num'=>$goods_num), array('cart_id'=>$cart['cart_id']));
			$cart_exist = 0;
		}
		$data = array(
			'cart_exist' => $cart_exist,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function updateOp() {
		$cart_id = empty($_POST['cart_id']) ? 0 : intval($_POST['cart_id']);
		$quantity = empty($_POST['quantity']) ? 0 : intval($_POST['quantity']);
		$cart = DB::fetch_first("SELECT * FROM ".DB::table('cart')." WHERE cart_id='$cart_id'");
		if(empty($cart) || $cart['member_id'] != $this->member_id) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品不存在', 'data'=>array())));
		}
		if($quantity <= 0) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请填写购买数量', 'data'=>array())));
		}
		$goods = DB::fetch_first("SELECT * FROM ".DB::table('goods')." as goods LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON goods.goods_id = goods_spec.goods_id WHERE goods_spec.spec_id='".$cart['spec_id']."'");
		if(empty($goods)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品不存在', 'data'=>array())));
		}
		if($goods['goods_show'] != 1 || $goods['goods_state'] != 1) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品已下架', 'data'=>array())));
		}
		if($goods['spec_goods_storage'] < 1) {
			exit(json_encode(array('code'=>'1', 'msg'=>'商品已经售完', 'data'=>array())));
		}
		$goods['spec_goods_price'] = $this->card['discount_rate'] > 0 ? $goods['spec_goods_price']*$this->card['discount_rate']/10 : $goods['spec_goods_price'];
		$goods['spec_goods_price'] = priceformat($goods['spec_goods_price']);
		$short_storage = '';
		if($goods['spec_goods_storage'] > 0 && $goods['spec_goods_storage'] < $quantity) {
			$quantity = $goods['spec_goods_storage'];
			$short_storage = '库存不足了，先买这么多，过段时间在来看看吧';
		}
		$diff_goods_num = $quantity-$cart['goods_num'];
		$diff_amount = $goods['spec_goods_price']*$diff_goods_num;
		$goods_amount = $goods['spec_goods_price']*$quantity;
		DB::update('cart', array('goods_num'=>$quantity), array('cart_id'=>$cart['cart_id']));
		$data = array(
			'quantity' => $quantity,
			'goods_amount' => $goods_amount,
			'diff_amount' => $diff_amount,
			'short_storage' => $short_storage
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function delOp() {
		$cart_ids_str = empty($_POST['cart_ids']) ? '' : $_POST['cart_ids'];
		if(empty($cart_ids_str)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要删除的商品', 'data'=>array())));
		}
		$cart_ids = explode(",", $cart_ids_str);
		foreach($cart_ids as $key => $cart_id) {
			$cart_id = intval($cart_id);
			if(!empty($cart_id)) {
				DB::delete('cart', array('cart_id'=>$cart_id, 'member_id'=>$this->member_id));
			}
		}
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
	}
	
	function step2Op() {
		$address = DB::fetch_first("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' AND address_default=1");
		$cart_ids_str = empty($_POST['cart_ids']) ? '' : $_POST['cart_ids'];
		$cart_ids_array = explode(",", $cart_ids_str);
		foreach($cart_ids_array as $key => $cart_id) {
			$cart_id = intval($cart_id);
			if(!empty($cart_id)) {
				$cart_ids[] = $cart_id;
			}
		}
		if(empty($cart_ids)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要购买的商品', 'data'=>array())));
		}
		$cart_field = 'cart.*, goods.goods_name, goods.goods_image, goods.spec_open, goods.goods_postage, goods.kd_price, goods.es_price, goods.py_price, goods.transport_id, goods.goods_show, goods.goods_state, goods_spec.spec_name, goods_spec.spec_goods_spec, goods_spec.spec_goods_price, goods_spec.spec_goods_storage';
		$query = DB::query("SELECT $cart_field FROM ".DB::table('cart')." as cart LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON cart.spec_id=goods_spec.spec_id LEFT JOIN ".DB::table('goods')." as goods ON goods_spec.goods_id=goods.goods_id WHERE cart.cart_id in ('".implode("','", $cart_ids)."') AND cart.member_id='$this->member_id'");
		while($value = DB::fetch($query)) {
			if($value['goods_show'] == 1 && $value['goods_state'] == 1 && $value['spec_goods_storage'] > 0) {
				$value['spec_info'] = array();
				if($value['spec_open'] == 1 && !empty($value['spec_name']) && !empty($value['spec_goods_spec'])) {
					$spec_name = empty($value['spec_name']) ? array() : unserialize($value['spec_name']);
					if(!empty($spec_name)) {
						$spec_name = array_values($spec_name);
						$spec_goods_spec = empty($value['spec_goods_spec']) ? array() : unserialize($value['spec_goods_spec']);
						$i = 0;
						foreach($spec_goods_spec as $k => $v) {
							$value['spec_info'][] = $spec_name[$i].":".$v;
							$i++;
						}
					}	
				}
				$value['spec_goods_price'] = $this->card['discount_rate'] > 0 ? $value['spec_goods_price']*$this->card['discount_rate']/10 : $value['spec_goods_price'];
				$value['spec_goods_price'] = priceformat($value['spec_goods_price']);
				if($value['goods_num'] > $value['spec_goods_storage']) {
					$value['goods_num'] = $value['spec_goods_storage'];
				}
				$cart_result[] = $value;
			}
		}
		if(empty($cart_result)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要购买的商品', 'data'=>array())));
		}
		$goods_amount = 0;
		$goods_ids = $store_ids = array();
		$goods_list = $goods_list_amount = $store_goods_amount = $store_goods_list = array();
		foreach($cart_result as $key => $value) {
			$goods_ids[] = $value['goods_id'];
			$store_ids[$value['store_id']] = $value['store_id'];
			$goods_list[$value['goods_id']] = $value;
			$goods_list_amount[$value['goods_id']] += $value['spec_goods_price']*$value['goods_num'];
			$store_goods_amount[$value['store_id']] += $value['spec_goods_price']*$value['goods_num'];
			$store_goods_list[$value['store_id']][$value['goods_id']]['goods_num'] += $value['goods_num'];
			if($value['goods_postage'] == 'freight') {
				$store_goods_list[$value['store_id']][$value['goods_id']]['kd_price'] = $value['kd_price'];
				$store_goods_list[$value['store_id']][$value['goods_id']]['es_price'] = $value['es_price'];
				$store_goods_list[$value['store_id']][$value['goods_id']]['py_price'] = $value['py_price'];
			} else {
				$store_goods_list[$value['store_id']][$value['goods_id']]['transport_id'] = $value['transport_id'];
			}
			$goods_amount += $value['spec_goods_price']*$value['goods_num'];
			$cart_goods_list[$value['store_id']][] = $value;
		}
		if(!empty($store_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('store')." WHERE store_id in ('".implode("','", $store_ids)."')");
			while($value = DB::fetch($query)) {
				$extend_name[$value['store_id']] = array(
					'kd' => $value['kd_rename'],
					'es' => $value['es_rename'],
					'py' => $value['py_rename'],
				);
				$store_list[$value['store_id']] = $value['store_name'];
			}
		}
		if(!empty($store_goods_list)) {
			foreach($store_goods_list as $store_id => $transport_goods_list) {
				$transport_result[$store_id] = $this->transport($transport_goods_list, $address['city_id']);
			}
		}
		foreach($transport_result as $store_id => $transport_value) {
			foreach($transport_value as $key => $value) {
				$param = array();
				$param['extend_name'] = $extend_name[$store_id][$key];
				$param['extend_type'] = $key;
				$param['extend_price'] = $value;
				$transport_list[$store_id][] = $param;
			}	
		}
		$time = time();
		$query = DB::query("SELECT * FROM ".DB::table('coupon')." WHERE member_id='$this->member_id' AND coupon_state=0 AND coupon_starttime<=$time AND coupon_endtime>=$time");
		while($value = DB::fetch($query)) {
			$value['coupon_goods_id'] = empty($value['coupon_goods_id']) ? array() : explode(',', $value['coupon_goods_id']);
			$goods_ids_in = array_intersect($value['coupon_goods_id'], $goods_ids);
			if(empty($value['coupon_goods_id']) || !empty($goods_ids_in)) {
				if($value['coupon_price_type'] == 'cash') {
					$value['coupon_discount'] = $value['coupon_price'];
				} elseif($value['coupon_price_type'] == 'discount') {
					if(empty($value['coupon_goods_id'])) {
						$value['coupon_discount'] = $store_goods_amount[$value['store_id']]*(10-$value['coupon_price'])/10;
						$value['coupon_discount'] = priceformat($value['coupon_discount']);
					} else {
						$coupon_discount_goods = array();
						foreach($goods_ids_in as $goods_id) {
							$coupon_discount = $goods_list_amount[$goods_id]*(10-$value['coupon_price'])/10;
							$coupon_discount = priceformat($coupon_discount);
							$value['coupon_discount'] += $coupon_discount;
							$coupon_discount_goods[] = $goods_list[$goods_id]['goods_name'];
						}
						$value['coupon_discount_goods'] = $coupon_discount_goods;
					}
				}
				if($value['coupon_limit'] > 0) {
					$coupon_limit = 0;
					if(empty($value['coupon_goods_id'])) {
						$coupon_limit = $store_goods_amount[$value['store_id']];
					} else {
						foreach($goods_ids_in as $goods_id) {
							$coupon_limit += $goods_list_amount[$goods_id];
						}
					}
					if($value['coupon_limit'] <= $coupon_limit) {
						$coupon_list[$value['store_id']][] = $value;
					}
				} else {
					$coupon_list[$value['store_id']][] = $value;
				}
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('red')." WHERE member_id='$this->member_id' AND red_state=0 AND red_cate_id in ('0', '2') AND red_starttime<=$time AND red_endtime>=$time");
		while($value = DB::fetch($query)) {
			if($value['red_limit'] > 0) {
				if($value['red_limit'] <= $goods_amount) {
					$red_list[] = $value;
				}
			} else {
				$red_list[] = $value;
			}
		}
		$cart_store_list = array();
		foreach($store_ids as $store_id) {
			$param = array();
			$param['store_id'] = $store_id;
			$param['store_name'] = $store_list[$store_id];
			$param['goods_list'] = $cart_goods_list[$store_id];
			$param['transport_list'] = $transport_list[$store_id];
			$param['coupon_list'] = $coupon_list[$store_id];
			$cart_list[] = $param;
		}
		$data = array(
			'address' => $address,
			'cart_list' => $cart_list,
			'red_list' => $red_list,
		);
		exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>$data)));
	}
	
	public function orderOp() {
		$address = DB::fetch_first("SELECT * FROM ".DB::table('address')." WHERE member_id='$this->member_id' AND address_default=1");
		if(empty($address)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择收货地址', 'data'=>array())));
		}
		$cart_ids_str = empty($_POST['cart_ids']) ? '' : $_POST['cart_ids'];
		$cart_ids_array = explode(",", $cart_ids_str);
		foreach($cart_ids_array as $key => $cart_id) {
			$cart_id = intval($cart_id);
			if(!empty($cart_id)) {
				$cart_ids[] = $cart_id;
			}
		}
		if(empty($cart_ids)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要购买的商品', 'data'=>array())));
		}
		$cart_field = 'cart.*, goods.goods_name, goods.goods_image, goods.spec_open, goods.goods_postage, goods.kd_price, goods.es_price, goods.py_price, goods.transport_id, goods.goods_show, goods.goods_state, goods_spec.spec_name, goods_spec.spec_goods_spec, goods_spec.spec_goods_price, goods_spec.spec_goods_storage';
		$query = DB::query("SELECT $cart_field FROM ".DB::table('cart')." as cart LEFT JOIN ".DB::table('goods_spec')." as goods_spec ON cart.spec_id=goods_spec.spec_id LEFT JOIN ".DB::table('goods')." as goods ON goods_spec.goods_id=goods.goods_id WHERE cart.cart_id in ('".implode("','", $cart_ids)."') AND cart.member_id='$this->member_id'");
		while($value = DB::fetch($query)) {
			if($value['goods_show'] == 1 && $value['goods_state'] == 1 && $value['spec_goods_storage'] > 0) {
				$value['spec_info'] = array();
				if($value['spec_open'] == 1 && !empty($value['spec_name']) && !empty($value['spec_goods_spec'])) {
					$spec_name = empty($value['spec_name']) ? array() : unserialize($value['spec_name']);
					if(!empty($spec_name)) {
						$spec_name = array_values($spec_name);
						$spec_goods_spec = empty($value['spec_goods_spec']) ? array() : unserialize($value['spec_goods_spec']);
						$i = 0;
						foreach($spec_goods_spec as $k => $v) {
							$value['spec_info'][] = $spec_name[$i].":".$v;
							$i++;
						}
					}	
				}
				$value['spec_goods_orig_price'] = $value['spec_goods_price'];
				$value['spec_goods_price'] = $this->card['discount_rate'] > 0 ? $value['spec_goods_price']*$this->card['discount_rate']/10 : $value['spec_goods_price'];
				$value['spec_goods_price'] = priceformat($value['spec_goods_price']);
				if($value['goods_num'] > $value['spec_goods_storage']) {
					$value['goods_num'] = $value['spec_goods_storage'];
				}
				$cart_result[] = $value;
			}
		}
		if(empty($cart_result)) {
			exit(json_encode(array('code'=>'1', 'msg'=>'请选择要购买的商品', 'data'=>array())));
		}
		$goods_orig_amount = $goods_amount = $transport_amount = $coupon_amount = 0;
		$order_goods_name = $store_order_goods_name = $goods_ids = $store_ids = array();
		$goods_list = $goods_list_amount = $store_goods_orig_amount = $store_goods_amount = $store_goods_list = array();
		foreach($cart_result as $key => $value) {
			$order_goods_name[] = $value['goods_name'];
			$store_order_goods_name[$value['store_id']][] = $value['goods_name'];
			$goods_ids[] = $value['goods_id'];
			$store_ids[$value['store_id']] = $value['store_id'];			
			$goods_list[$value['goods_id']] = $value;
			$goods_list_amount[$value['goods_id']] += $value['spec_goods_price']*$value['goods_num'];
			$store_goods_orig_amount[$value['store_id']] += $value['spec_goods_orig_price']*$value['goods_num'];			
			$store_goods_amount[$value['store_id']] += $value['spec_goods_price']*$value['goods_num'];
			$store_goods_list[$value['store_id']][$value['goods_id']]['goods_num'] += $value['goods_num'];
			if($value['goods_postage'] == 'freight') {
				$store_goods_list[$value['store_id']][$value['goods_id']]['kd_price'] = $value['kd_price'];
				$store_goods_list[$value['store_id']][$value['goods_id']]['es_price'] = $value['es_price'];
				$store_goods_list[$value['store_id']][$value['goods_id']]['py_price'] = $value['py_price'];
			} else {
				$store_goods_list[$value['store_id']][$value['goods_id']]['transport_id'] = $value['transport_id'];
			}
			$goods_orig_amount += $value['spec_goods_orig_price']*$value['goods_num'];
			$goods_amount += $value['spec_goods_price']*$value['goods_num'];
			$cart_list[$value['store_id']][] = $value;
		}
		if(!empty($store_goods_list)) {
			foreach($store_goods_list as $store_id => $transport_goods_list) {
				$transport_list[$store_id] = $this->transport($transport_goods_list, $address['city_id']);
			}
		}
		$result = empty($_POST['extend_types']) ? '' : $_POST['extend_types'];
		$extend_types_array = explode('|', $result);
		foreach($extend_types_array as $key => $value) {
			$result = explode(',', $value);
			if(!empty($result[0])) {
				$extend_types[$result[0]] = $result[1];
			}
		}
		foreach($store_ids as $key => $value) {
			if(empty($extend_types[$value]) || !in_array($extend_types[$value], array('kd', 'es', 'py', 'free'))) {
				exit(json_encode(array('code'=>'1', 'msg'=>'请选择配送方式', 'data'=>array())));
			}
		}
		foreach($extend_types as $store_id => $extend_type) {
			if(!empty($transport_list[$store_id])) {
				$transport_amount += $transport_list[$store_id][$extend_type];
				$store_transport_amount[$store_id] = $transport_list[$store_id][$extend_type];
				if($store_transport_amount[$store_id] <= 0) {
					exit(json_encode(array('code'=>'1', 'msg'=>'请选择配送方式', 'data'=>array())));
				}
			}
		}
		$time = time();
		$result = empty($_POST['coupon_ids']) ? '' : $_POST['coupon_ids'];
		$coupon_ids_array = explode('|', $result);
		foreach($coupon_ids_array as $key => $value) {
			$result = explode(',', $value);
			if(!empty($result[0])) {
				$coupon_ids[$result[0]] = $result[1];
			}
		}
		foreach($coupon_ids as $store_id => $coupon_id) {
			$coupon = DB::fetch_first("SELECT * FROM ".DB::table('coupon')." WHERE coupon_id='$coupon_id'");
			if(!empty($coupon) && $coupon['member_id'] == $this->member_id && $coupon['store_id'] == $store_id && empty($coupon['coupon_state']) && $coupon['coupon_starttime'] <= $time && $coupon['coupon_endtime'] >= $time) {
				$coupon['coupon_goods_id'] = empty($coupon['coupon_goods_id']) ? array() : explode(',', $coupon['coupon_goods_id']);
				$goods_ids_in = array_intersect($coupon['coupon_goods_id'], $goods_ids);
				if(empty($coupon['coupon_goods_id']) || !empty($goods_ids_in)) {
					if($coupon['coupon_price_type'] == 'cash') {
						$coupon_discount = $coupon['coupon_price'];
					} elseif($coupon['coupon_price_type'] == 'discount') {
						if(empty($coupon['coupon_goods_id'])) {
							$coupon_discount = $store_goods_amount[$store_id]*(10-$coupon['coupon_price'])/10;
							$coupon_discount = priceformat($coupon_discount);
						} else {
							$coupon_discount = 0;
							foreach($goods_ids_in as $goods_id) {
								$goods_coupon_discount = $goods_list_amount[$goods_id]*(10-$coupon['coupon_price'])/10;
								$goods_coupon_discount = priceformat($goods_coupon_discount);
								$coupon_discount += $goods_coupon_discount;
							}
						}
					}
					if($coupon['coupon_limit'] > 0) {
						$coupon_limit = 0;
						if(empty($coupon['coupon_goods_id'])) {
							$coupon_limit = $store_goods_amount[$store_id];
						} else {
							foreach($goods_ids_in as $goods_id) {
								$coupon_limit += $goods_list_amount[$goods_id];
							}
						}
						if($coupon['coupon_limit'] <= $coupon_limit) {
							$coupon_amount += $coupon_discount;
							$store_coupon_amount[$store_id] = $coupon_discount;
							$coupon_list[$store_id] = $coupon;
						}
					} else {
						$coupon_amount += $coupon_discount;
						$store_coupon_amount[$store_id] = $coupon_discount;
						$coupon_list[$store_id] = $coupon;
					}
				}
			}	
		}
		$red_id = empty($_POST['red_id']) ? 0 : intval($_POST['red_id']);
		$red_amount = 0;
		$red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE red_id='$red_id'");
		if(!empty($red) && $red['member_id'] == $this->member_id && in_array($red['red_cate_id'], array('0', '2')) && empty($red['red_state']) && $red['red_starttime'] <= $time && $red['red_endtime'] >= $time) {
			if($red['red_limit'] > 0) {
				if($red['red_limit'] <= $goods_amount) {
					$red_amount = $red['red_price'];
					$order_red_id = $red['red_id'];
				}
			} else {
				$red_amount = $red['red_price'];
				$order_red_id = $red['red_id'];
			}
		}
		$payment_code = !in_array($_POST['payment_code'], array('alipay', 'weixin', 'predeposit')) ? 'alipay' : $_POST['payment_code'];
		if($payment_code == 'predeposit') {
			$order_amount = $goods_amount+$transport_amount-$coupon_amount-$red_amount;
			if($this->member['available_predeposit'] < $order_amount) {
				exit(json_encode(array('code'=>'1', 'msg'=>'余额不足，请选择其他支付方式', 'data'=>array())));
			}
		}
		foreach($store_ids as $store_id) {
			$order_goods_orig_amount = floatval($store_goods_orig_amount[$store_id]);
			$order_goods_amount = floatval($store_goods_amount[$store_id]);
			$order_transport_amount = floatval($store_transport_amount[$store_id]);
			$order_coupon_amount = floatval($store_coupon_amount[$store_id]);
			$order_red_amount = $red_amount;
			$order_amount = $order_goods_amount+$order_transport_amount-$order_coupon_amount-$order_red_amount;
			$order_sn = makesn();
			$out_sn = date('YmdHis').random(18);
			$order_data = array();
			$order_data['order_sn'] = $order_sn;
			$order_data['out_sn'] = $out_sn;
			$order_data['store_id'] = $store_id;
			$order_data['member_id'] = $this->member_id;
			$order_data['member_phone'] = $this->member['member_phone'];
			$order_data['order_goods_name'] = implode(' ', $store_order_goods_name[$store_id]);
			$order_data['invoice_content'] = $invoice_content;
			$order_data['goods_orig_amount'] = $order_goods_orig_amount;
			$order_data['goods_amount'] = $order_goods_amount;
			$order_data['transport_amount'] = $order_transport_amount;
			$order_data['coupon_amount'] = $order_coupon_amount;
			$order_data['red_amount'] = $order_red_amount;
			$order_data['order_amount'] = $order_amount;
			$order_data['order_state'] = 10;
			$order_data['add_time'] = time();
			$order_id = DB::insert('order', $order_data, 1);
			$order_address_data = array();
			$order_address_data['order_id'] = $order_id;
			$order_address_data['true_name'] = $address['true_name'];
			$order_address_data['mobile_phone'] = $address['mobile_phone'];
			$order_address_data['province_id'] = $address['province_id'];
			$order_address_data['city_id'] = $address['city_id'];
			$order_address_data['area_id'] = $address['area_id'];
			$order_address_data['area_info'] = $address['area_info'];
			$order_address_data['address_info'] = $address['address_info'];			
			DB::insert('order_address', $order_address_data);
			$cart_ids = array();
			foreach($cart_list[$store_id] as $key => $value) {
				$cart_ids[] = $value['cart_id'];
				$order_goods_data = array();
				$order_goods_data['order_id'] = $order_id;
				$order_goods_data['goods_id'] = $value['goods_id'];
				$order_goods_data['goods_name'] = $value['goods_name'];
				$order_goods_data['spec_id'] = $value['spec_id'];
				$order_goods_data['spec_info'] = implode(' ', $value['spec_info']);
				$order_goods_data['goods_image'] = $value['goods_image'];
				$order_goods_data['goods_price'] = $value['spec_goods_price'];
				$order_goods_data['goods_num'] = $value['goods_num'];
				DB::insert('order_goods', $order_goods_data);
				DB::query("UPDATE ".DB::table('goods')." SET goods_storage=goods_storage-".$value['goods_num'].", goods_salenum=goods_salenum+".$value['goods_num']." WHERE goods_id='".$value['goods_id']."'");
				DB::query("UPDATE ".DB::table('goods_spec')." SET spec_goods_storage=spec_goods_storage-".$value['goods_num'].", spec_salenum=spec_salenum+".$value['goods_num']." WHERE spec_id='".$value['spec_id']."'");
			}
			$log_data = array();
			$log_data['order_id'] = $order_id;
			$log_data['order_state'] = '10';
			$log_data['order_intro'] = '订单提交';	
			$log_data['log_time'] = time();
			DB::insert('order_log', $log_data);
			if(!empty($coupon_list[$store_id]['coupon_id'])) {
				DB::update('coupon', array('coupon_orderid'=>$order_id, 'coupon_state'=>1),array('coupon_id'=>$coupon_list[$store_id]['coupon_id']));
				DB::query("UPDATE ".DB::table('coupon_template')." SET coupon_t_used=coupon_t_used+1 WHERE coupon_t_id='".$coupon_list[$store_id]['coupon_t_id']."'");
			}
			if(!empty($order_red_id)) {
				DB::update('red', array('use_type'=>2, 'use_id'=>$order_id, 'red_state'=>1),array('red_id'=>$red['red_id']));
				DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used+1 WHERE red_t_id='".$red['red_t_id']."'");
			}
			$red_amount = 0;
			$order_red_id = 0;
			$order_temp_amount += $order_amount;
			$order_ids[] = $order_id;
		}
		if(!empty($cart_ids)) {
			DB::query("DELETE FROM ".DB::table('cart')." WHERE cart_id in ('".implode("','", $cart_ids)."')");
		}
		if($payment_code == 'predeposit') {
			foreach($order_ids as $order_id) {
				$order = DB::fetch_first("SELECT * FROM ".DB::table('order')." WHERE order_id='$order_id'");
				$member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$order['member_id']."'");
				$data = array(
					'pdl_memberid' => $member['member_id'],
					'pdl_memberphone' => $member['member_phone'],
					'pdl_stage' => 'order',
					'pdl_type' => 0,
					'pdl_price' => $order['order_amount'],
					'pdl_predeposit' => $member['available_predeposit']-$order['order_amount'],
					'pdl_desc' => '购买商品，订单单号: '.$order['order_sn'],
					'pdl_addtime' => time(),
				);
				DB::insert('pd_log', $data);
				DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit-".$order['order_amount']." WHERE member_id='".$member['member_id']."'");
				$order_data = array();
				$order_data['payment_name'] = '余额支付';
				$order_data['payment_code'] = 'predeposit';
				$order_data['order_state'] = 20;
				$order_data['payment_time'] = time();
				DB::update('order', $order_data, array('order_id'=>$order['order_id']));
				$log_data = array();
				$log_data['order_id'] = $order['order_id'];
				$log_data['order_state'] = 20;
				$log_data['order_intro'] = '订单付款';
				$log_data['state_info'] = '支付方式：余额支付';
				$log_data['log_time'] = time();
				Db::insert('order_log', $log_data);
				//优惠券
				$time = time();
				$query = DB::query("SELECT * FROM ".DB::table('coupon_template')." WHERE store_id='".$order['store_id']."' AND coupon_rule_type='buy' AND coupon_rule_starttime<=$time AND coupon_rule_endtime>=$time ORDER BY coupon_rule_amount DESC");
				while($value = DB::fetch($query)) {
					if($value['coupon_t_total'] > $value['coupon_t_giveout'] && $value['coupon_rule_amount'] <= $order['order_amount']) {
						$coupon_template = $value;
						$coupon_t_id = $value['coupon_t_id'];
						break;
					}
				}
				if(!empty($coupon_t_id)) {
					if($coupon_template['coupon_t_period_type'] == 'duration') {
						$coupon_template['coupon_t_starttime'] = strtotime(date('Y-m-d'));
						$coupon_template['coupon_t_endtime'] = $coupon_template['coupon_t_starttime']+3600*24*($coupon_template['coupon_t_days']+1)-1;
					}
					$coupon_data = array(
						'coupon_sn' => makesn(3),
						'member_id' => $member['member_id'],
						'store_id' => $coupon_template['store_id'],
						'coupon_t_id' => $coupon_template['coupon_t_id'],
						'coupon_title' => $coupon_template['coupon_t_title'],
						'coupon_desc' => $coupon_template['coupon_t_desc'],
						'coupon_starttime' => $coupon_template['coupon_t_starttime'],
						'coupon_endtime' => $coupon_template['coupon_t_endtime'],
						'coupon_price_type' => $coupon_template['coupon_t_price_type'],
						'coupon_price' => $coupon_template['coupon_t_price'],
						'coupon_limit' => $coupon_template['coupon_t_limit'],
						'coupon_goods_id' => $coupon_template['coupon_t_goods_id'],
						'coupon_state' => 0,
						'coupon_addtime' => time(),
					);
					$coupon_id = DB::insert('coupon', $coupon_data, 1);
					if(!empty($coupon_id)) {
						DB::update('coupon_template', array('coupon_t_giveout'=>$coupon_template['coupon_t_giveout']+1), array('coupon_t_id'=>$coupon_template['coupon_t_id']));
					}
				}
				//商家收益统计
				$profit_data = array(
					'store_id' => $order['store_id'],
					'profit_stage' => 'order',
					'profit_type' => 1,
					'profit_amount' => $order['order_amount'],
					'profit_desc' => $order['member_phone'].'购买商品，订单单号：'.$order['order_sn'],
					'is_freeze' => 1,
					'order_id' => $order['order_id'],
					'order_sn' => $order['order_sn'],
					'add_time' => time(),
				);
				DB::insert('store_profit', $profit_data);
				DB::query("UPDATE ".DB::table('store')." SET plat_amount=plat_amount+".$order['order_amount'].", pool_amount=pool_amount+".$order['order_amount']." WHERE store_id='".$order['store_id']."'");
				//养老金收益
				$this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
				if($this->setting['first_oldage_rate'] > 0)	{
					$oldage_price = priceformat($order['order_amount']*$this->setting['first_oldage_rate']);
					$oldage_data = array(
						'member_id' => $member['member_id'],
						'oldage_stage' => 'consume',					
						'oldage_type' => 1,
						'oldage_price' => $oldage_price,
						'oldage_balance' => $member['oldage_amount']+$oldage_price,
						'oldage_desc' => '消费了'.$order['order_amount'].'元，获得'.$oldage_price.'元养老金',
						'oldage_addtime' => time(),
					);
					DB::insert('oldage', $oldage_data);
					DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
				}
				//商品销售统计
				$date = date('Ymd');
				$query = DB::query("SELECT * FROM ".DB::table('order_goods')." WHERE order_id='".$order['order_id']."'");
				while($value = DB::fetch($query)) {
					$order_goods[] = $value;
				}
				foreach($order_goods as $key => $value) {
					$goods_stat = DB::fetch_first("SELECT * FROM ".DB::table('goods_stat')." WHERE goods_id='".$value['goods_id']."' AND date='$date'");
					if(empty($goods_stat)) {
						$goods_stat_array = array(
							'goods_id' => $value['goods_id'],
							'date' => $date,
							'salenum' => $value['goods_num'],
						);
						DB::insert('goods_stat', $goods_stat_array);
					} else {
						$goods_stat_array = array(
							'salenum' => $goods_stat['salenum']+$value['goods_num'],
						);
						DB::update('goods_stat', $goods_stat_array, array('goods_id'=>$goods['goods_id'], 'date'=>$date));
					}
				}
			}
			//红包
			$query = DB::query("SELECT * FROM ".DB::table('red_template')." WHERE red_t_type='reward' ORDER BY red_t_amount DESC");
			while($value = DB::fetch($query)) {
				if($value['red_t_total'] > $value['red_t_giveout'] && $value['red_t_amount'] <= $order_temp_amount) {
					$red_template = $value;
					$red_t_id = $value['red_t_id'];
					break;
				}
			}
			if(!empty($red_t_id)) {
				if($red_template['red_t_period_type'] == 'duration') {
					$red_template['red_t_starttime'] = strtotime(date('Y-m-d'));
					$red_template['red_t_endtime'] = $red_template['red_t_starttime']+3600*24*($red_template['red_t_days']+1)-1;
				}
				$red_data = array(
					'red_sn' => makesn(2),
					'member_id' => $member['member_id'],
					'red_t_id' => $red_template['red_t_id'],
					'red_title' => $red_template['red_t_title'],
					'red_price' => $red_template['red_t_price'],
					'red_starttime' => $red_template['red_t_starttime'],
					'red_endtime' => $red_template['red_t_endtime'],
					'red_limit' => $red_template['red_t_limit'],
					'red_cate_id' => $red_template['red_t_cate_id'],
					'red_state' => 0,
					'red_addtime' => time(),
				);
				$red_id = DB::insert('red', $red_data, 1);
				if(!empty($red_id)) {
					DB::update('red_template', array('red_t_giveout'=>$red_template['red_t_giveout']+1), array('red_t_id'=>$red_template['red_t_id']));
				}
			}	
			$order_state = 20;
		} else {
			$order_state = 10;	
		}
		$order_sn = makesn(1);
		$out_sn = date('YmdHis').random(18);
		$order_temp_data = array();
		$order_temp_data['member_id'] = $this->member_id;
		$order_temp_data['order_sn'] = $order_sn;
		$order_temp_data['order_goods_name'] = implode(' ', $order_goods_name);
		$order_temp_data['out_sn'] = $out_sn;
		$order_temp_data['payment_code'] = $payment_code;
		$order_temp_data['order_amount'] = $order_temp_amount;
		$order_temp_data['order_state'] = $order_state;
		$order_temp_data['order_ids'] = empty($order_ids) ? '' : implode(',', $order_ids);
		$order_temp_data['add_time'] = time();
		$order_temp_id = DB::insert('order_temp', $order_temp_data, 1);
		if(!empty($order_temp_id)) {
			exit(json_encode(array('code'=>'0', 'msg'=>'操作成功', 'data'=>array())));
		} else {
			exit(json_encode(array('code'=>'1', 'msg'=>'网路不稳定，请稍候重试', 'data'=>array())));
		}
	}
	
	private function transport($goods_list, $area_id) {
		$tmp = array();	
		$result = array();		
		$result['kd'] = 0;
		$result['es'] = 0;
		$result['py'] = 0;
		foreach($goods_list as $key => $value) {
			if(!empty($value['transport_id'])) {
				$transport_id_in[$value['transport_id']] = $value['transport_id'];			
				$tmp[$value['transport_id']] += intval($value['goods_num']);
			} else {
				$no_transport_goods[] = $value;
			}
		}
		if(empty($transport_id_in)) {
			foreach($no_transport_goods as $key => $value) {
				if(empty($value['transport_id'])) {
					if(is_numeric($value['kd_price']) && $value['kd_price'] > 0) {
						$result['kd'] += $value['kd_price'];
					}
					if(is_numeric($value['es_price']) && $value['es_price'] > 0) {
						$result['es'] += $value['es_price'];
					}
					if(is_numeric($value['py_price']) && $value['py_price'] > 0) {
						$result['py'] += $value['py_price'];
					}
				}
			}
		} else {
			if(!empty($transport_id_in)) {
				$query = DB::query("SELECT * FROM ".DB::table('transport_extend')." WHERE transport_id in ('".implode("','", $transport_id_in)."')");
				while($value = DB::fetch($query)) {
					$extend[] = $value;	
				}
			}
			$new_extend = array();			
			$unset_kd = true;
			$unset_es = true;
			$unset_py = true;
			if(!empty($extend) && is_array($extend)) {
				foreach($extend as $k => $v) {
					$new_extend[$v['transport_id']][] = $v;
					if($v['extend_type'] == 'kd') {
						$unset_kd = false;
					}
					if($v['extend_type'] == 'es') {
						$unset_es = false;
					}
					if($v['extend_type'] == 'py') {
						$unset_py = false;
					}
				}
			}
			foreach($tmp as $key => $value) {
				$calc = array();
				$calc_default = array();
				foreach($new_extend[$key] as $k => $v) {
					if(strpos($v['area_id'], ",".intval($area_id).",") !== false) {
						if($value <= $v['extend_snum']) {
							$calc[$v['extend_type']] = $v['extend_sprice'];
						} else {
							$calc[$v['extend_type']] = sprintf('%.2f', ($v['extend_sprice'] + ceil(($value-$v['extend_snum'])/$v['extend_xnum'])*$v['extend_xprice']));
						}
					}
					if($v['is_default'] == 1) {
						if($value <= $v['extend_snum']) {
							$calc_default[$v['extend_type']] = $v['extend_sprice'];
						} else {
							$calc_default[$v['extend_type']] = sprintf('%.2f', ($v['extend_sprice'] + ceil(($value-$v['extend_snum'])/$v['extend_xnum'])*$v['extend_xprice']));
						}
					}
				}
				foreach(array('kd', 'es', 'py') as $v) {
					if(!isset($calc[$v]) && isset($calc_default[$v])) {
						$calc[$v] = $calc_default[$v];
					}
				}				
				$result['kd'] += $calc['kd'];
				$result['es'] += $calc['es'];
				$result['py'] += $calc['py'];			
			}
			foreach($no_transport_goods as $key => $value) {
				if(empty($value['transport_id'])) {
					if(is_numeric($value['kd_price']) && $value['kd_price'] > 0) {
						$result['kd'] += $value['kd_price'];
					}
					if(is_numeric($value['es_price']) && $value['es_price'] > 0) {
						$result['es'] += $value['es_price'];
					}
					if(is_numeric($value['py_price']) && $value['py_price'] > 0) {
						$result['py'] += $value['py_price'];
					}
				}
			}
		}		
		$result['kd'] = sprintf('%.2f', $result['kd']);
		$result['es'] = sprintf('%.2f', $result['es']);
		$result['py'] = sprintf('%.2f', $result['py']);
		if(is_numeric($result['kd']) && $result['kd'] > 0) {
			if($unset_kd == true) {
				unset($result['kd']);
			}
		} else {
			unset($result['kd']);	
		}
		if(is_numeric($result['es']) && $result['es'] > 0) {
			if($unset_es == true) {
				unset($result['es']);
			}
		} else {
			unset($result['es']);	
		}
		if(is_numeric($result['py']) && $result['py'] > 0) {
			if($unset_py == true) {
				unset($result['py']);
			}
		} else {
			unset($result['py']);	
		}
		return $result;
	}
}

?>