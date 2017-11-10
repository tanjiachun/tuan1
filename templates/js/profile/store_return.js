var agree_submit_btn = false;
function agreesubmit() {
	var formhash = $('#formhash').val();
	var agree_id = $('#agree_id').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'agree_id' : agree_id,
	};
	if(agree_submit_btn) return;
	agree_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_return&op=agree',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			agree_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+agree_id).html('已同意');
					$('#opr_'+agree_id).remove();
				});
			} else {
				showwarning('agree-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			agree_submit_btn = false;
			showwarning('agree-box', '网路不稳定，请稍候重试');
		}
	});
}

var refuse_submit_btn = false;
function refusesubmit() {
	var formhash = $('#formhash').val();
	var refuse_id = $('#refuse_id').val();
	var seller_message = $('#seller_message').val();
	if(seller_message == '') {
		showwarning('refuse-box', '请输入拒绝理由');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'refuse_id' : refuse_id,
		'seller_message' : seller_message,
	};
	if(refuse_submit_btn) return;
	refuse_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_return&op=refuse',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			refuse_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+refuse_id).html('不同意');
					$('#opr_'+refuse_id).remove();
				});
			} else {
				showwarning('refuse-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			refuse_submit_btn = false;
			showwarning('refuse-box', '网路不稳定，请稍候重试');
		}
	});
}

var receive_submit_btn = false;
function receivesubmit() {
	var formhash = $('#formhash').val();
	var receive_id = $('#receive_id').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'receive_id' : receive_id,
	};
	if(receive_submit_btn) return;
	receive_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_return&op=receive',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			receive_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+receive_id).html('商家已收货');
					$('#opr_'+receive_id).remove();
				});
			} else {
				showwarning('receive-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			receive_submit_btn = false;
			showwarning('receive-box', '网路不稳定，请稍候重试');
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
		url : 'index.php?act=store_return&op=deliver',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			deliver_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+deliver_id).html('卖家已发货');
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

var finish_submit_btn = false;
function finishsubmit() {
	var formhash = $('#formhash').val();
	var finish_id = $('#finish_id').val();
	var return_amount = $('#return_amount').val();
	if(return_amount == '') {
		showwarning('finish-box', '请输入退款金额');	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'finish_id' : finish_id,
		'return_amount' : return_amount,
	};
	if(finish_submit_btn) return;
	finish_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_return&op=finish',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			finish_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+finish_id).html('已同意');
					$('#opr_'+finish_id).remove();
				});
			} else {
				showwarning('finish-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			finish_submit_btn = false;
			showwarning('finish-box', '网路不稳定，请稍候重试');
		}
	});
}

$(function() {
	$('.return-agree').on('click', function() {
		var return_id = $(this).attr('return_id');
		$('#agree_id').val(return_id);
		Custombox.open({
			target : '#agree-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.return-refuse').on('click', function() {
		var return_id = $(this).attr('return_id');
		$('#refuse_id').val(return_id);
		Custombox.open({
			target : '#refuse-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.return-receive').on('click', function() {
		var return_id = $(this).attr('return_id');
		$('#receive_id').val(return_id);
		Custombox.open({
			target : '#receive-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.return-deliver').on('click', function() {
		var return_id = $(this).attr('return_id');
		$('#deliver_id').val(return_id);
		Custombox.open({
			target : '#deliver-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.return-finish').on('click', function() {
		var return_id = $(this).attr('return_id');
		$('#finish_id').val(return_id);
		Custombox.open({
			target : '#finish-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
});