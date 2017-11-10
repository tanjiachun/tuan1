var address_submit_btn = false;
function addresssubmit() {
	var formhash = $('#formhash').val();
	var order_id = $('#order_id').val();
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
		'order_id' : order_id,
		'true_name' : true_name,
		'mobile_phone' : mobile_phone,
		'province_id' : province_id,
		'city_id' : city_id,
		'area_id' : area_id,
		'address_info' : address_info
	};
	if(address_submit_btn) return;
	address_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_order&op=address',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			address_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#truename_'+order_id).html(data.true_name);
					$('#mobilephone_'+order_id).html(data.mobile_phone);
					if($('#address_'+order_id).length != 0) {
						$('#address_'+order_id).html(data.address);
					}
				});
			} else {
				showwarning('address-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			address_submit_btn = false;
			showwarning('address-box', '网路不稳定，请稍候重试');
		}
	});
}

var cancel_submit_btn = false;
function cancelsubmit() {
	var formhash = $('#formhash').val();
	var cancel_id = $('#cancel_id').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'cancel_id' : cancel_id,
	};
	if(cancel_submit_btn) return;
	cancel_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_order&op=cancel',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			cancel_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+cancel_id).html('已取消');
					$('#opr_'+cancel_id).remove();
				});
			} else {
				showwarning('cancel-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			cancel_submit_btn = false;
			showwarning('cancel-box', '网路不稳定，请稍候重试');
		}
	});
}

var refund_submit_btn = false;
function refundsubmit() {
	var formhash = $('#formhash').val();
	var refund_id = $('#refund_id').val();
	var refund_amount = $('#refund_amount').val();
	var refund_state = $('#refund_state').val(); 
	if(refund_state != 1 && refund_state != 2) {
		showwarning('refund-box', '请选择处理方式');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'refund_id' : refund_id,
		'refund_amount' : refund_amount,
		'refund_state' : refund_state,
	};
	if(refund_submit_btn) return;
	refund_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_order&op=refund',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			refund_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					if(refund_state == 1) {
						$('#state_'+refund_id).html('已退款');
					} else if(refund_state == 2) {
						$('#state_'+refund_id).html('已拒绝');
					}
					$('#opr_'+refund_id).remove();
				});
			} else {
				showwarning('refund-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			refund_submit_btn = false;
			showwarning('refund-box', '网路不稳定，请稍候重试');
		}
	});
}

var deliver_submit_btn = false;
function deliversubmit() {
	var formhash = $('#formhash').val();
	var deliver_id = $('#deliver_id').val();
	var express_id = $('#express_id').val();
	var shipping_code = $('#shipping_code').val();
	if(express_id == '') {
		showwarning('deliver-box', '请选择快递公司');
	}
	if(shipping_code == '') {
		showwarning('deliver-box', '请输入快递编号');
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'deliver_id' : deliver_id,
		'express_id' : express_id,
		'shipping_code' : shipping_code
	};
	if(deliver_submit_btn) return;
	deliver_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_order&op=deliver',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			deliver_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+deliver_id).html('已发货');
					$('#opr_'+deliver_id).remove();
				});
			} else {
				showwarning('deliver-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			deliver_submit_btn = false;
			showwarning('deliver-box', '网路不稳定，请稍候重试');
		}
	});
}

$(function() {
	$('.edit-address').on('click', function() {
		var order_id = $(this).attr('order_id');
		Custombox.open({
			target : 'index.php?act=store_order&op=address&order_id='+order_id,
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});															  			 
	});

	$('.order-cancel').on('click', function() {
		var order_id = $(this).attr('order_id');
		$('#cancel_id').val(order_id);
		Custombox.open({
			target : '#cancel-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.order-refund').on('click', function() {
		var order_id = $(this).attr('order_id');
		var refund_amount = $(this).attr('refund_amount');
		var refund_reason = $(this).attr('refund_reason');
		var refund_message = $(this).attr('refund_message');
		$('#refund_id').val(order_id);
		$('#refund_amount').val(refund_amount);
		$('#refund_reason').html(refund_reason);
		$('#refund_message').html(refund_message);
		Custombox.open({
			target : '#refund-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.order-deliver').on('click', function() {
		var order_id = $(this).attr('order_id');
		$('#deliver_id').val(order_id);
		Custombox.open({
			target : '#deliver-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
});