function changeQuantity() {
	var quantity = parseInt($('#cart_item').val());
	var spec_goods_price = $('#spec_goods_price').val();
	var spec_goods_storage = $('#spec_goods_storage').val();
	$('.decrement').removeClass('disabled');
	$('.increment').removeClass('disabled');
	if(isNaN(quantity) || quantity <= 1) {
		$('.decrement').addClass('disabled');
		quantity = 1;
	}
	if(quantity > parseInt(spec_goods_storage)) {		
		$('.increment').addClass('disabled');
		quantity = spec_goods_storage;
	}
	$('#cart_item').val(quantity);
	var cart_goods_amount = parseFloat(spec_goods_price)*parseInt(quantity);
	$('#cart_price').html('￥'+cart_goods_amount.toFixed(2));
	$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
}

var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var spec_id = $('#spec_id').val();
	var quantity = $('#quantity').val();
	var payment_code = $('#payment_code').val();
	var extend_type = $('#extend_type').val();
	var coupon_id = $('#coupon_id').val();
	var red_id = $('#red_id').val();
	var order_invoice = $('#order_invoice').val();
	var invoice_title = $('#invoice_title').val();
	var invoice_content = $('#invoice_content').val();
	var invoice_membername = $('#invoice_membername').val();
	var invoice_provinceid = $('#invoice_provinceid').val();
	var invoice_cityid = $('#invoice_cityid').val();
	var invoice_areaid = $('#invoice_areaid').val();
	var invoice_address = $('#invoice_address').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'spec_id' : spec_id,
		'quantity' : quantity,
		'payment_code' : payment_code,		
		'extend_type' : extend_type,
		'coupon_id' : coupon_id,
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
		url : 'index.php?act=buynow&op=order',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=payment&order_sn='+data.order_sn;
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
	var spec_id = $('#spec_id').val();
	var quantity = $('#quantity').val();
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
		url : 'index.php?act=buynow&op=address_add&spec_id='+spec_id+'&quantity='+quantity,
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			add_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('.address-list').load('index.php?act=buynow&op=address');
					$('.address-info').html(data.address);
					$('.transport-box').html(data.transport);
					var extend_type = $('#extend_type').val();
					if(extend_type != '') {
						if($('.transport-box').find('[extend_type='+extend_type+']').length == 0) {
							$('.transport-box').find('li')[0].click();
						} else {
							$('.transport-box').find('[extend_type='+extend_type+']').click();
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
	var spec_id = $('#spec_id').val();
	var quantity = $('#quantity').val();
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
		url : 'index.php?act=buynow&op=address_edit&spec_id='+spec_id+'&quantity='+quantity,
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			edit_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('.address-list').load('index.php?act=buynow&op=address');
					$('.address-info').html(data.address);
					$('.transport-box').html(data.transport);
					var extend_type = $('#extend_type').val();
					if(extend_type != '') {
						if($('.transport-box').find('[extend_type='+extend_type+']').length == 0) {
							$('.transport-box').find('li')[0].click();
						} else {
							$('.transport-box').find('[extend_type='+extend_type+']').click();
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
	$('.decrement').on('click', function() {
		if(!$(this).hasClass('disabled')) {
			var quantity = parseInt($('#cart_item').val())-1;
			var spec_goods_price = $('#spec_goods_price').val();			
			if(quantity <= 1) {
				$(this).addClass('disabled');
				quantity = 1;
			}
			$('.increment').removeClass('disabled');
			$('#cart_item').val(quantity);
			var cart_goods_amount = parseFloat(spec_goods_price)*parseInt(quantity);
			$('#cart_price').html('￥'+cart_goods_amount.toFixed(2));
			$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
		}
	});

	$('.increment').on('click', function() {
		if(!$(this).hasClass('disabled')) {
			var quantity = parseInt($('#cart_item').val())+1;
			var spec_goods_price = $('#spec_goods_price').val();
			var spec_goods_storage = $('#spec_goods_storage').val();
			if(quantity > parseInt(spec_goods_storage)) {
				$(this).addClass('disabled');
				quantity = spec_goods_storage;				
			}
			$('.decrement').removeClass('disabled');
			$('#cart_item').val(quantity);
			var cart_goods_amount = parseFloat(spec_goods_price)*parseInt(quantity);
			$('#cart_price').html('￥'+cart_goods_amount.toFixed(2));
			$('#cart_goods_amount').html(cart_goods_amount.toFixed(2));
		}
	});
	
	$('.sumbit-btn').on('click', function() {
		var spec_id = $('#spec_id').val();
		var quantity = $('#cart_item').val();
		window.location = 'index.php?act=buynow&op=step2&spec_id='+spec_id+'&quantity='+quantity;
	});
	
	$('.address-add').on('click', function() {
		Custombox.open({
			target: 'index.php?act=buynow&op=address_add',
			effect: 'blur',
			overlayClose: true,
			speed:500,
			overlaySpeed: 300,
		});															  			 
	});
	
	$('.address-list').on('click', '.address-edit', function() {
		var address_id = $(this).attr('address_id');
		Custombox.open({
			target: 'index.php?act=buynow&op=address_edit&address_id='+address_id,
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
			url : 'index.php?act=buynow&op=address_del',
			data : submitData,
			contentType: 'application/x-www-form-urlencoded; charset=utf-8',
			dataType : 'json',
			success : function(data){
				del_submit_btn = false;
				if(data.done == 'true') {
					$('.address-list').load('index.php?act=buynow&op=address');
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
		var spec_id = $('#spec_id').val();
		var quantity = $('#quantity').val();
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
			url : 'index.php?act=buynow&op=address_default&spec_id='+spec_id+'&quantity='+quantity,
			data : submitData,
			contentType: 'application/x-www-form-urlencoded; charset=utf-8',
			dataType : 'json',
			success : function(data){
				default_submit_btn = false;
				if(data.done == 'true') {
					$('.address-list').load('index.php?act=buynow&op=address');
					$('.address-info').html(data.address);
					$('.transport-box').html(data.transport);
					var extend_type = $('#extend_type').val();
					if(extend_type != '') {
						if($('.transport-box').find('[extend_type='+extend_type+']').length == 0) {
							$('.transport-box').find('li')[0].click();
						} else {
							$('.transport-box').find('[extend_type='+extend_type+']').click();
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
		var extend_type = $(this).attr('extend_type');
		var extend_price = $(this).attr('extend_price');
		$('#extend_type').val(extend_type);				
		$('#extend_price').val(extend_price);
		var transport_amount = parseFloat(extend_price);
		$('#transport_amount').html('￥'+transport_amount.toFixed(2));
		var coupon_price = $('#coupon_price').val();
		var red_price = $('#red_price').val();
		var discount_amount = parseFloat(red_price)+parseFloat(coupon_price);
		var goods_amount = $('#order_amount').attr('goods_amount');
		var order_amount = parseFloat(goods_amount)+parseFloat(transport_amount)-parseFloat(discount_amount);
		$('#order_amount').html('￥'+order_amount.toFixed(2));
	});
        
	$('.coupon-box').on('click', '.radio', function() {
		var coupon_id = $(this).attr('coupon_id');
		var coupon_price = $(this).attr('coupon_price');
		var red_price = $('#red_price').val();
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('#coupon_id').val('');				
			$('#coupon_price').val('0');
			var coupon_price = 0;
			var discount_amount = parseFloat(red_price)+parseFloat(coupon_price);
			$('#discount_amount').html('-￥'+discount_amount.toFixed(2));
		} else {
			$(this).addClass('active');
			$(this).siblings('.radio').removeClass('active');
			$('#coupon_id').val(coupon_id);				
			$('#coupon_price').val(coupon_price);
			var coupon_price = parseFloat(coupon_price);
			var discount_amount = parseFloat(red_price)+parseFloat(coupon_price);
			$('#discount_amount').html('-￥'+discount_amount.toFixed(2));
		}
		var transport_amount = $('#extend_price').val();
		var goods_amount = $('#order_amount').attr('goods_amount');
		var order_amount = parseFloat(goods_amount)+parseFloat(transport_amount)-parseFloat(discount_amount);
		$('#order_amount').html('￥'+order_amount.toFixed(2));
	});
	
	$('.red-box').on('click', '.radio', function() {
		var red_id = $(this).attr('red_id');
		var red_price = $(this).attr('red_price');
		var coupon_price = $('#coupon_price').val();
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('#red_id').val('');				
			$('#red_price').val('0');
			var red_price = 0;
			var discount_amount = parseFloat(red_price)+parseFloat(coupon_price);
			$('#discount_amount').html('-￥'+discount_amount.toFixed(2));
		} else {
			$(this).addClass('active');
			$(this).siblings('.radio').removeClass('active');
			$('#red_id').val(red_id);				
			$('#red_price').val(red_price);
			var red_price = parseFloat(red_price);
			var discount_amount = parseFloat(red_price)+parseFloat(coupon_price);
			$('#discount_amount').html('-￥'+discount_amount.toFixed(2));
		}
		var transport_amount = $('#extend_price').val();
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
		$('.transport-box').find('li')[0].click();
	}
});