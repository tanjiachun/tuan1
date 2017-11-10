function showprompt(msg) {
	$('.alert-box .tip-title').html(msg);
	$('.alert-box').show();
	setTimeout(function() {
		$('.alert-box .tip-title').html('');
		$('.alert-box').hide();
	}, 2000);	
}

function changeQuantity(cart_id) {
	var input = $('#cart_item_'+cart_id);
	var quantity = input.val();
	var cart_goods_amount = $('#cart_goods_amount').html();
	if(quantity != '') {
		$.getJSON('index.php?act=cart&op=update&cart_id='+cart_id+'&quantity='+quantity, function(result) {
			if(result.done == 'true') {
				input.val(result.quantity);
				input.attr('orig', result.quantity);
				$('#cart_price_'+cart_id).html('￥'+result.goods_amount.toFixed(2));
				cart_goods_amount = parseFloat(cart_goods_amount)+parseFloat(result.diff_amount);
				$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
				input.siblings('.decrement').removeClass('disabled');
				input.siblings('.increment').removeClass('disabled');
				if(result.quantity <= 1) {
					input.siblings('.decrement').addClass('disabled');
				}
				if(result.short_storage != '') {
					input.siblings('.increment').addClass('disabled');
					showprompt(result.short_storage);	
				}
			} else if(data.done == 'login') {
				Custombox.open({
					target : '#login-box',
					effect : 'blur',
					overlayClose : true,
					speed : 500,
					overlaySpeed : 300,
					open: function () {
						setTimeout(function(){
							window.location.href = 'index.php?act=login';
						}, 3000);
					},
				});
			} else {
				var orig = input.attr('orig');
				input.val(orig);
				showprompt(result.msg);
			}
		});
	}
}

var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var cart_ids = $('#cart_ids').val();
	var payment_code = $('#payment_code').val();
	var red_id = $('#red_id').val();
	var order_invoice = $('#order_invoice').val();
	var invoice_title = $('#invoice_title').val();
	var invoice_content = $('#invoice_content').val();
	var invoice_membername = $('#invoice_membername').val();
	var invoice_provinceid = $('#invoice_provinceid').val();
	var invoice_cityid = $('#invoice_cityid').val();
	var invoice_areaid = $('#invoice_areaid').val();
	var invoice_address = $('#invoice_address').val();
	var extend_types = {};
	$('.extend_type').each(function() {
		var store_id = $(this).attr('store_id');
		extend_types[store_id] = $(this).val();
	});
	var coupon_ids = {};
	$('.coupon_id').each(function() {
		var store_id = $(this).attr('store_id');
		coupon_ids[store_id] = $(this).val();
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'cart_ids' : cart_ids,
		'payment_code' : payment_code,		
		'extend_types' : extend_types,
		'coupon_ids' : coupon_ids,
		'red_id' : red_id,
		'order_invoice' : order_invoice,
		'invoice_title' : invoice_title,
		'invoice_content' : invoice_content,
		'invoice_membername' : invoice_membername,
		'invoice_provinceid' : invoice_provinceid,
		'invoice_cityid' : invoice_cityid,
		'invoice_areaid' : invoice_areaid,
		'invoice_address' : invoice_address,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=cart&op=order',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=payment&op=payment&order_sn='+data.order_sn;
			} else if(data.done == 'login') {
				Custombox.open({
					target : '#login-box',
					effect : 'blur',
					overlayClose : true,
					speed : 500,
					overlaySpeed : 300,
					open: function () {
						setTimeout(function(){
							window.location.href = 'index.php?act=login';
						}, 3000);
					},
				});
			} else {
				if(data.id == 'system') {
					showalert(data.msg);
				} else {
					showerror(data.id, data.msg);	
				}
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

var add_submit_btn = false;
function addsubmit() {
	var formhash = $('#formhash').val();
	var true_name = $('#true_name').val();
	var mobile_phone = $('#mobile_phone').val();
	var province_id = $('#province_id').val();
	var city_id = $('#city_id').val();
	var area_id = $('#area_id').val();
	var address_info = $('#address_info').val();
	if(true_name == '') {
		showwarning('address-box', '请输入联系人');
		return;	
	}
	if(mobile_phone == '') {
		showwarning('address-box', '请输入电话');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(mobile_phone)) {
		showwarning('address-box', '电话格式不正确');
		return;
	}
	if(province_id == '' || city_id == '') {
		showwarning('address-box', '请选择所在地区');
		return;	
	}
	if(address_info == '') {
		showwarning('address-box', '请输入详细地址');
		return;	
	}	
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'true_name' : true_name,
		'mobile_phone' : mobile_phone,
		'province_id' : province_id,
		'city_id' : city_id,
		'area_id' : area_id,
		'address_info' : address_info
	};
	if(add_submit_btn) return;
	add_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=cart&op=address_add',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			add_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('.address-list').load('index.php?act=cart&op=address');
					$('.address-info').html(data.address);
					for(var i=0; i<data.transport.length; i++) {
						$('#transport_'+data.transport[i].store_id).html(data.transport[i].html);
						var extend_type = $('#extend_type_'+data.transport[i].store_id).val();
						if(extend_type != '') {
							if($('#transport_'+data.transport[i].store_id).find('[extend_type='+extend_type+']').length == 0) {
								$('#transport_'+data.transport[i].store_id).find('li')[0].click();
							} else {
								$('#transport_'+data.transport[i].store_id).find('[extend_type='+extend_type+']').click();
							}
						}
					}
				});
			} else {
				showwarning('address-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			add_submit_btn = false;
			showwarning('address-box', '网路不稳定，请稍候重试');
		}
	});
}

var edit_submit_btn = false;
function editsubmit() {
	var formhash = $('#formhash').val();
	var address_id = $('#address_id').val();
	var true_name = $('#true_name').val();
	var mobile_phone = $('#mobile_phone').val();
	var province_id = $('#province_id').val();
	var city_id = $('#city_id').val();
	var area_id = $('#area_id').val();
	var address_info = $('#address_info').val();
	if(true_name == '') {
		showwarning('address-box', '请输入联系人');
		return;	
	}
	if(mobile_phone == '') {
		showwarning('address-box', '请输入电话');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(mobile_phone)) {
		showwarning('address-box', '电话格式不正确');
		return;
	}
	if(province_id == '' || city_id == '') {
		showwarning('address-box', '请选择所在地区');
		return;	
	}
	if(address_info == '') {
		showwarning('address-box', '请输入详细地址');
		return;	
	}	
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'address_id' : address_id,
		'true_name' : true_name,
		'mobile_phone' : mobile_phone,
		'province_id' : province_id,
		'city_id' : city_id,
		'area_id' : area_id,
		'address_info' : address_info
	};
	if(edit_submit_btn) return;
	edit_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=cart&op=address_edit',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			edit_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('.address-list').load('index.php?act=cart&op=address');
					$('.address-info').html(data.address);
					for(var i=0; i<data.transport.length; i++) {
						$('#transport_'+data.transport[i].store_id).html(data.transport[i].html);
						var extend_type = $('#extend_type_'+data.transport[i].store_id).val();
						if(extend_type != '') {
							if($('#transport_'+data.transport[i].store_id).find('[extend_type='+extend_type+']').length == 0) {
								$('#transport_'+data.transport[i].store_id).find('li')[0].click();
							} else {
								$('#transport_'+data.transport[i].store_id).find('[extend_type='+extend_type+']').click();
							}
						}
					}
				});
			} else {
				showwarning('address-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			edit_submit_btn = false;
			showwarning('address-box', '网路不稳定，请稍候重试');
		}
	});
}

$(function() {
	$('.cartall').on('click', function() {
		if($(this).hasClass('active')) {
			$('.cartall').removeClass('active');
			$('.storeall').removeClass('active');
			$('.cartitem').removeClass('active');
			$('.sumbit-btn').addClass('disbled');
		} else {
			$('.cartall').addClass('active');
			$('.storeall').addClass('active');
			$('.cartitem').addClass('active');
			$('.sumbit-btn').removeClass('disbled');
		}
		var cart_goods_num = 0;
		var cart_goods_amount = 0;
		$('.cartitem').each(function() {
			if($(this).hasClass('active')) {
				var cart_id = $(this).attr('cart_id');
				var spec_goods_price = $(this).attr('spec_goods_price');
				var quantity = $('#cart_item_'+cart_id).val();
				cart_goods_num++;
				cart_goods_amount = parseFloat(cart_goods_amount)+parseFloat(spec_goods_price)*parseInt(quantity);				
			}
		});
		$('#cart_goods_num').html(cart_goods_num);
		$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
	});
	
	$('.storeall').on('click', function() {
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$(this).parent().parent().find('.cartitem').removeClass('active');
		} else {
			$(this).addClass('active');
			$(this).parent().parent().find('.cartitem').addClass('active');
		}
		$('.sumbit-btn').addClass('disbled');
		$('.cartitem').each(function() {
			if($(this).hasClass('active')) {
				$('.sumbit-btn').removeClass('disbled');
			}
		});
		var cart_goods_num = 0;
		var cart_goods_amount = 0;
		$('.cartitem').each(function() {
			if($(this).hasClass('active')) {
				var cart_id = $(this).attr('cart_id');
				var spec_goods_price = $(this).attr('spec_goods_price');
				var quantity = $('#cart_item_'+cart_id).val();
				cart_goods_num++;
				cart_goods_amount = parseFloat(cart_goods_amount)+parseFloat(spec_goods_price)*parseInt(quantity);				
			}
		});
		$('#cart_goods_num').html(cart_goods_num);
		$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
	});
	
	$('.cartitem').on('click', function() {
		var cart_id = $(this).attr('cart_id');
		var spec_goods_price = $(this).attr('spec_goods_price');
		var quantity = $('#cart_item_'+cart_id).val();
		var cart_goods_num = $('#cart_goods_num').html();
		var cart_goods_amount = $('#cart_goods_amount').html();
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			cart_goods_num = parseInt(cart_goods_num)-1;
			cart_goods_amount = parseFloat(cart_goods_amount)-parseFloat(spec_goods_price)*parseInt(quantity);
			$('#cart_goods_num').html(cart_goods_num);
			$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
		} else {
			cart_goods_num = parseInt(cart_goods_num)+1;
			cart_goods_amount = parseFloat(cart_goods_amount)+parseFloat(spec_goods_price)*parseInt(quantity);
			$('#cart_goods_num').html(cart_goods_num);
			$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
			$(this).addClass('active');
		}
		$('.sumbit-btn').addClass('disbled');
		$('.cartitem').each(function() {
			if($(this).hasClass('active')) {
				$('.sumbit-btn').removeClass('disbled');
			}
		});
	});
	
	var cart_decrement_btn = false;
	$('.decrement').on('click', function() {
		if(!$(this).hasClass('disabled')) {
			var decrement = $(this);
			var cart_id = $(this).attr('cart_id');
			var quantity = parseInt($('#cart_item_'+cart_id).val())-1;
			if(quantity <= 1) {
				decrement.addClass('disabled');
				quantity = 1;
			}
			var cart_goods_amount = $('#cart_goods_amount').html();
			if(cart_decrement_btn) return;
			cart_decrement_btn = true;
			$.getJSON('index.php?act=cart&op=update&cart_id='+cart_id+'&quantity='+quantity, function(result) {
				cart_decrement_btn = false;
				if(result.done == 'true') {
					$('#cart_item_'+cart_id).val(result.quantity);
					$('#cart_item_'+cart_id).attr('orig', result.quantity);
					$('#cart_price_'+cart_id).html('￥'+result.goods_amount.toFixed(2));
					cart_goods_amount = parseFloat(cart_goods_amount)+parseFloat(result.diff_amount);
					$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));					
					decrement.siblings('.increment').removeClass('disabled');
				} else if(data.done == 'login') {
					Custombox.open({
						target : '#login-box',
						effect : 'blur',
						overlayClose : true,
						speed : 500,
						overlaySpeed : 300,
						open: function () {
							setTimeout(function(){
								window.location.href = 'index.php?act=login';
							}, 3000);
						},
					});
				} else {
					var orig = $('#cart_item_'+cart_id).attr('orig');
					$('#cart_item_'+cart_id).val(orig);
					showprompt(result.msg);
				}
			});
		}
	});
	
	var cart_increment_btn = false;
	$('.increment').on('click', function() {
		if(!$(this).hasClass('disabled')) {
			var increment = $(this);
			var cart_id = $(this).attr('cart_id');
			var quantity = parseInt($('#cart_item_'+cart_id).val())+1;
			var cart_goods_amount = $('#cart_goods_amount').html();
			if(cart_increment_btn) return;
			cart_increment_btn = true;
			$.getJSON('index.php?act=cart&op=update&cart_id='+cart_id+'&quantity='+quantity, function(result) {
				cart_increment_btn = false;
				if(result.done == 'true') {
					$('#cart_item_'+cart_id).val(result.quantity);
					$('#cart_item_'+cart_id).attr('orig', result.quantity);
					$('#cart_price_'+cart_id).html('￥'+result.goods_amount.toFixed(2));
					cart_goods_amount = parseFloat(cart_goods_amount)+parseFloat(result.diff_amount);
					$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
					increment.siblings('.decrement').removeClass('disabled');
					if(result.short_storage != '') {
						increment.addClass('disabled');
						showprompt(result.short_storage);	
					}
				} else if(data.done == 'login') {
					Custombox.open({
						target : '#login-box',
						effect : 'blur',
						overlayClose : true,
						speed : 500,
						overlaySpeed : 300,
						open: function () {
							setTimeout(function(){
								window.location.href = 'index.php?act=login';
							}, 3000);
						},
					});
				} else {
					var orig = $('#cart_item_'+cart_id).attr('orig');
					$('#cart_item_'+cart_id).val(orig);
					showprompt(result.msg);
				}
			});
		}
	});
	
	var cart_del_btn = false;
	$('.cart-item-del').on('click', function() {
		var cart_id = $(this).attr('cart_id');
		var store_id = $(this).attr('store_id');
		var spec_goods_price = $(this).attr('spec_goods_price');
		var cart_goods_num = $('#cart_goods_num').html();
		var cart_goods_amount = $('#cart_goods_amount').html();
		if(cart_del_btn) return;
		cart_del_btn = true;
		$.getJSON('index.php?act=cart&op=del&cart_ids='+cart_id, function(result) {							 
			cart_del_btn = false;
			if(result.done == 'true') {
				cart_goods_num = parseInt(cart_goods_num)-1;
				$('#cart_goods_num').html(cart_goods_num);
				var quantity = $('#cart_item_'+cart_id).val();
				cart_goods_amount = parseFloat(cart_goods_amount)-parseFloat(spec_goods_price)*parseInt(quantity);
				$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
				$('#cart_'+cart_id).remove();
				if($('#store_'+store_id).find('.cart-list').length == 0) {
					$('#store_'+store_id).remove();	
				}
				if($('.cart-list').length == 0) {
					window.location = 'index.php?act=cart';	
				}
				$('.sumbit-btn').addClass('disbled');
				$('.cartitem').each(function() {
					if($(this).hasClass('active')) {
						$('.sumbit-btn').removeClass('disbled');
					}
				});
			} else if(data.done == 'login') {
				Custombox.open({
					target : '#login-box',
					effect : 'blur',
					overlayClose : true,
					speed : 500,
					overlaySpeed : 300,
					open: function () {
						setTimeout(function(){
							window.location.href = 'index.php?act=login';
						}, 3000);
					},
				});
			}
		});
	});
	
	var cart_drop_btn = false;
	$('.cart-del').on('click', function() {
		var cart_goods_num = $('#cart_goods_num').html();
		var cart_goods_amount = $('#cart_goods_amount').html();
		var cart_ids = '';
		$('.cartitem').each(function() {
			if($(this).hasClass('active')) {
				cart_ids += $(this).attr('cart_id') + ',';
			}
		});
		if(cart_ids == '') {
			showprompt('请选择要删除的商品');
			return;
		}
		cart_ids = cart_ids.substr(0, (cart_ids.length-1));
		if(cart_drop_btn) return;
		cart_drop_btn = true;
		$.getJSON('index.php?act=cart&op=del&cart_ids='+cart_ids, function(result) {
			cart_drop_btn = false;
			if(result.done == 'true') {
				$('.cartitem').each(function() {
					if($(this).hasClass('active')) {
						var cart_id = $(this).attr('cart_id');
						var store_id = $(this).attr('store_id');
						var spec_goods_price = $(this).attr('spec_goods_price');
						var quantity = $('#cart_item_'+cart_id).val();
						cart_goods_num = parseInt(cart_goods_num)-1;
						cart_goods_amount = parseFloat(cart_goods_amount)-parseFloat(spec_goods_price)*parseInt(quantity);
						$('#cart_'+cart_id).remove();
						if($('#store_'+store_id).find('.cart-list').length == 0) {
							$('#store_'+store_id).remove();	
						}				
					}
				});
				if($('.cart-list').length == 0) {
					window.location = 'index.php?act=cart';	
				} else {
					$('#cart_goods_num').html(cart_goods_num);
					$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));	
				}
				$('.sumbit-btn').addClass('disbled');
				$('.cartitem').each(function() {
					if($(this).hasClass('active')) {
						$('.sumbit-btn').removeClass('disbled');
					}
				});
			} else if(data.done == 'login') {
				Custombox.open({
					target : '#login-box',
					effect : 'blur',
					overlayClose : true,
					speed : 500,
					overlaySpeed : 300,
					open: function () {
						setTimeout(function(){
							window.location.href = 'index.php?act=login';
						}, 3000);
					},
				});
			} else {
				showprompt(result.msg);
			}
		});
	});
	
	$('.sumbit-btn').on('click', function() {
		if($(this).hasClass('disbled')) return;
		var cart_ids = '';
		$('.cartitem').each(function() {
			if($(this).hasClass('active')) {
				cart_ids += $(this).attr('cart_id') + ',';
			}
		});
		if(cart_ids == '') {
			showprompt('请选择要购买的商品');
			return;
		}
		cart_ids = cart_ids.substr(0, (cart_ids.length-1));
		window.location = 'index.php?act=cart&op=step2&cart_ids='+cart_ids;
	});
	
	$('.address-add').on('click', function() {
		Custombox.open({
			target: 'index.php?act=cart&op=address_add',
			effect: 'blur',
			overlayClose: true,
			speed:500,
			overlaySpeed: 300,
		});															  			 
	});
	
	$('.address-list').on('click', '.address-edit', function() {
		var address_id = $(this).attr('address_id');
		Custombox.open({
			target: 'index.php?act=cart&op=address_edit&address_id='+address_id,
			effect: 'blur',
			overlayClose: true,
			speed:500,
			overlaySpeed: 300,
		});															  			 
	});
	
	var del_submit_btn = false;
	$('.address-list').on('click', '.address-del', function() {
		var formhash = $('#formhash').val();
		var address_id = $(this).attr('address_id');
		var submitData = {
			'form_submit' : 'ok',
			'formhash' : formhash,
			'address_id' : address_id,
		};
		if(del_submit_btn) return;
		del_submit_btn = true;
		$.ajax({
			type : 'POST',
			url : 'index.php?act=cart&op=address_del',
			data : submitData,
			contentType: 'application/x-www-form-urlencoded; charset=utf-8',
			dataType : 'json',
			success : function(data){
				del_submit_btn = false;
				if(data.done == 'true') {
					$('.address-list').load('index.php?act=cart&op=address');
				} else if(data.done == 'login') {
					Custombox.open({
						target : '#login-box',
						effect : 'blur',
						overlayClose : true,
						speed : 500,
						overlaySpeed : 300,
						open: function () {
							setTimeout(function(){
								window.location.href = 'index.php?act=login';
							}, 3000);
						},
					});
				} else {
					showwarning('del-box', data.msg);
				}
			},
			timeout : 15000,
			error : function(xhr, type){
				del_submit_btn = false;
				showwarning('del-box', '网路不稳定，请稍候重试');
			}
		});													  			 
	});
	
	var default_submit_btn = false;
	$('.address-list').on('click', '.address-default', function() {
		var formhash = $('#formhash').val();
		var address_id = $(this).attr('address_id');
		var submitData = {
			'form_submit' : 'ok',
			'formhash' : formhash,
			'address_id' : address_id,
		};
		if(default_submit_btn) return;
		default_submit_btn = true;
		$.ajax({
			type : 'POST',
			url : 'index.php?act=cart&op=address_default',
			data : submitData,
			contentType: 'application/x-www-form-urlencoded; charset=utf-8',
			dataType : 'json',
			success : function(data){
				default_submit_btn = false;
				if(data.done == 'true') {
					$('.address-list').load('index.php?act=cart&op=address');
					$('.address-info').html(data.address);
					for(var i=0; i<data.transport.length; i++) {
						$('#transport_'+data.transport[i].store_id).html(data.transport[i].html);
						var extend_type = $('#extend_type_'+data.transport[i].store_id).val();
						if(extend_type != '') {
							if($('#transport_'+data.transport[i].store_id).find('[extend_type='+extend_type+']').length == 0) {
								$('#transport_'+data.transport[i].store_id).find('li')[0].click();
							} else {
								$('#transport_'+data.transport[i].store_id).find('[extend_type='+extend_type+']').click();
							}
						}
					}
				} else if(data.done == 'login') {
					Custombox.open({
						target : '#login-box',
						effect : 'blur',
						overlayClose : true,
						speed : 500,
						overlaySpeed : 300,
						open: function () {
							setTimeout(function(){
								window.location.href = 'index.php?act=login';
							}, 3000);
						},
					});
				} else {
					showwarning('default-box', data.msg);
				}
			},
			timeout : 15000,
			error : function(xhr, type){
				default_submit_btn = false;
				showwarning('default-box', '网路不稳定，请稍候重试');
			}
		});												  			 
	});
	
	$('.payment-box').on('click', 'li', function() {
		var payment_code = $(this).attr('payment_code');
		$('#payment_code').val(payment_code);
		$(this).addClass('active');
		$(this).siblings('li').removeClass('active');
	});
	
	$('.transport-box').on('click', 'li', function() {
		if($(this).hasClass('active')) return;
		$(this).addClass('active');
		$(this).siblings('li').removeClass('active');
		var store_id = $(this).attr('store_id');
		var extend_type = $(this).attr('extend_type');
		var extend_price = $(this).attr('extend_price');
		$('#extend_type_'+store_id).val(extend_type);				
		$('#extend_price_'+store_id).val(extend_price);
		var transport_amount = 0;
		$('.extend_price').each(function() {
			var extend_price = parseFloat($(this).val());
			if(!isNaN(extend_price)) {
				transport_amount = transport_amount+extend_price;
			}
		});
		$('#transport_amount').html('￥'+transport_amount.toFixed(2));
		var coupon_amount = 0;
		$('.coupon_price').each(function() {
			var coupon_price = parseFloat($(this).val());
			if(!isNaN(coupon_price)) {
				coupon_amount = coupon_amount+coupon_price;
			}
		});
		var red_price = $('#red_price').val();
		var discount_amount = parseFloat(red_price)+parseFloat(coupon_amount);
		$('#discount_amount').html('-￥'+discount_amount.toFixed(2));
		var goods_amount = $('#order_amount').attr('goods_amount');
		var order_amount = parseFloat(goods_amount)+parseFloat(transport_amount)-parseFloat(discount_amount);
		$('#order_amount').html('￥'+order_amount.toFixed(2));
	});
	
	$('.coupon-box').on('click', '.select-choice', function() {
		$(this).siblings('.select-list').toggle();
	});
        
	$('.coupon-box').on('click', '.radio', function() {
		var store_id = $(this).attr('store_id');
		var coupon_id = $(this).attr('coupon_id');
		var coupon_price = $(this).attr('coupon_price');
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('#coupon_id_'+store_id).val('');				
			$('#coupon_price_'+store_id).val('');
			var coupon_title = '请选择优惠券';
		} else {
			$(this).addClass('active');
			$(this).siblings('.radio').removeClass('active');
			$('#coupon_id_'+store_id).val(coupon_id);				
			$('#coupon_price_'+store_id).val(coupon_price);
			var coupon_title = $(this).attr('coupon_title');
		}
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html(coupon_title+'<i class="select-arrow"></i>');
		var transport_amount = 0;
		$('.extend_price').each(function() {
			var extend_price = parseFloat($(this).val());
			if(!isNaN(extend_price)) {
				transport_amount = transport_amount+extend_price;
			}
		});
		$('#transport_amount').html('￥'+transport_amount.toFixed(2));
		var coupon_amount = 0;
		$('.coupon_price').each(function() {
			var coupon_price = parseFloat($(this).val());
			if(!isNaN(coupon_price)) {
				coupon_amount = coupon_amount+coupon_price;
			}
		});
		var red_price = $('#red_price').val();
		var discount_amount = parseFloat(red_price)+parseFloat(coupon_amount);
		$('#discount_amount').html('-￥'+discount_amount.toFixed(2));
		var goods_amount = $('#order_amount').attr('goods_amount');
		var order_amount = parseFloat(goods_amount)+parseFloat(transport_amount)-parseFloat(discount_amount);
		$('#order_amount').html('￥'+order_amount.toFixed(2));
	});
	
	$('.red-box').on('click', '.radio', function() {
		var red_id = $(this).attr('red_id');
		var red_price = $(this).attr('red_price');
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('#red_id').val('');				
			$('#red_price').val('0');
		} else {
			$(this).addClass('active');
			$(this).siblings('.radio').removeClass('active');
			$('#red_id').val(red_id);				
			$('#red_price').val(red_price);
		}
		var transport_amount = 0;
		$('.extend_price').each(function() {
			var extend_price = parseFloat($(this).val());
			if(!isNaN(extend_price)) {
				transport_amount = transport_amount+extend_price;
			}
		});
		$('#transport_amount').html('￥'+transport_amount.toFixed(2));
		var coupon_amount = 0;
		$('.coupon_price').each(function() {
			var coupon_price = parseFloat($(this).val());
			if(!isNaN(coupon_price)) {
				coupon_amount = coupon_amount+coupon_price;
			}
		});
		var red_price = $('#red_price').val();
		var discount_amount = parseFloat(red_price)+parseFloat(coupon_amount);
		$('#discount_amount').html('-￥'+discount_amount.toFixed(2));
		var goods_amount = $('#order_amount').attr('goods_amount');
		var order_amount = parseFloat(goods_amount)+parseFloat(transport_amount)-parseFloat(discount_amount);
		$('#order_amount').html('￥'+order_amount.toFixed(2));
	});
	
	$('.invoice-radio-box').on('click', '.radio', function() {
		var order_invoice = $(this).attr('order_invoice');
		$('#order_invoice').val(order_invoice);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		if(order_invoice == 'yes') {
			$('.invoice-form').show();
		} else {
			$('.invoice-form').hide();
		}
	});
	
	$('#invoice_title').on('blur', function() {
		var invoice_title = $('#invoice_title').val();
		if(invoice_title == '') {
			showerror('invoice_title', '请输入发票抬头');
			return;
		}
		showsuccess('invoice_title');
	});
	
	$('#invoice_content').on('blur', function() {
		var invoice_content = $('#invoice_content').val();
		if(invoice_content == '') {
			showerror('invoice_content', '请输入发票明细');
			return;
		}
		showsuccess('invoice_content');
	});
	
	$('#invoice_membername').on('blur', function() {
		var invoice_membername = $('#invoice_membername').val();
		if(invoice_membername == '') {
			showerror('invoice_membername', '请输入收件人');
			return;
		}
		showsuccess('invoice_membername');
	});
	
	$('#invoice_address').on('blur', function() {
		var invoice_address = $('#invoice_address').val();
		if(invoice_address == '') {
			showerror('invoice_address', '请输入邮寄地址');
			return;
		}
		showsuccess('invoice_address');
	});
	
	if($('.transport-box').length != 0) {
		$('.transport-box').each(function() {
			$(this).find('li')[0].click();
		});
	}
});