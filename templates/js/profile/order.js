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
		url : 'index.php?act=order&op=cancel',
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
	var refund_reason = $('#refund_reason').val(); 
	var refund_message = $('#refund_message').val();
	if(refund_reason == '') {
		showwarning('refund-box', '请选择退款原因');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'refund_id' : refund_id,
		'refund_reason' : refund_reason,
		'refund_message' : refund_message,
	};
	if(refund_submit_btn) return;
	refund_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=refund',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			refund_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+refund_id).html('待退款');
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

var return_submit_btn = false;
function returnsubmit() {
	var formhash = $('#formhash').val();
	var order_id = $('#order_id').val();
	var return_type = {};
	var return_goodsnum = {};
	var return_content = {};
	var return_image = {};
	$('[name="rec_id[]"]').each(function() {
		var rec_id = $(this).val();
		return_type[rec_id] = $('[name="return_type['+rec_id+']"]').val();
		return_goodsnum[rec_id] = $('[name="return_goodsnum['+rec_id+']"]').val();
		return_content[rec_id] = $('[name="return_content['+rec_id+']"]').val();
		return_image[rec_id] = {};
		var i = 0;
		$('.image_'+rec_id).each(function() {
			return_image[rec_id][i] = $(this).val();
			i++;
		});
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'order_id' : order_id,
		'return_type' : return_type,
		'return_goodsnum' : return_goodsnum,
		'return_content' : return_content,
		'return_image' : return_image,		
	};
	if(return_submit_btn) return;
	return_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=return',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			return_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('提交成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=order';
				}, 1000);
			} else {
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			return_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
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
		url : 'index.php?act=order&op=receive',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			receive_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+receive_id).html('已收货');
					$('#opr_'+receive_id).html('<a href="index.php?act=order&op=comment&order_id='+receive_id+'" class="btn btn-primary">立即评价</a><p><a href="index.php?act=order&op=return&order_id='+receive_id+'">我要退换货</a></p>');
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

var comment_submit_btn = false;
function commentsubmit() {
	var formhash = $('#formhash').val();
	var order_id = $('#order_id').val();
	var comment_level = {};
	var comment_score = {};
	var comment_content = {};
	var comment_image = {};
	$('[name="rec_id[]"]').each(function() {
		var rec_id = $(this).val();
		comment_level[rec_id] = $('[name="comment_level['+rec_id+']"]').val();
		comment_score[rec_id] = $('[name="comment_score['+rec_id+']"]:checked').val();
		comment_content[rec_id] = $('[name="comment_content['+rec_id+']"]').val();
		comment_image[rec_id] = {};
		var i = 0;
		$('.image_'+rec_id).each(function() {
			comment_image[rec_id][i] = $(this).val();
			i++;
		});
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'order_id' : order_id,
		'comment_level' : comment_level,
		'comment_score' : comment_score,
		'comment_content' : comment_content,
		'comment_image' : comment_image,		
	};
	if(comment_submit_btn) return;
	comment_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=comment',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			comment_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('评价成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=order';
				}, 1000);
			} else {
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			comment_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

function changeQuantity(obj, max_stock) {
	var quantity = parseInt($(obj).val());
	var max_stock = parseInt($(obj).attr('max_stock'));
	if(isNaN(quantity) || quantity < 1) {
		$(obj).val(1);
	} else {
		if(quantity > max_stock) {
			$(obj).val(max_stock);
		}
	}
}

$(function() {
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
		$('#refund_id').val(order_id);
		Custombox.open({
			target : '#refund-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.order-receive').on('click', function() {
		var order_id = $(this).attr('order_id');
		$('#receive_id').val(order_id);
		Custombox.open({
			target : '#receive-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.tag-box').on('click', '.tag-item', function() {
		var field_value = $(this).attr('field_value');
		$(this).siblings('input').val(field_value);
		$(this).addClass('tag-checked');
		$(this).siblings('.tag-item').removeClass('tag-checked');
	});
});