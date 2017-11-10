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
		url : 'index.php?act=order&op=bespoke_cancel',
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
	if(refund_reason == '') {
		showwarning('refund-box', '请输入退款原因');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'refund_id' : refund_id,
		'refund_reason' : refund_reason,
	};
	if(refund_submit_btn) return;
	refund_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=bespoke_refund',
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

var finish_submit_btn = false;
function finishsubmit() {
	var formhash = $('#formhash').val();
	var finish_id = $('#finish_id').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'finish_id' : finish_id,
	};
	if(finish_submit_btn) return;
	finish_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=bespoke_finish',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			finish_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+finish_id).html('待评价');
					$('#opr_'+finish_id).html('<a href="index.php?act=order&op=bespoke_comment&bespoke_id='+finish_id+'" class="btn btn-primary">立即评价</a>');
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

var comment_submit_btn = false;
function commentsubmit() {
	var formhash = $('#formhash').val();
	var bespoke_id = $('#bespoke_id').val();
	var comment_level = $('#comment_level').val();
	var comment_score = $('[name="comment_score"]:checked').val();
	var comment_content = $('#comment_content').val();
	var i = 0;
	var comment_image = {};
	$('.image_0').each(function() {
		comment_image[i] = $(this).val();
		i++;
	});
	if(comment_level == '') {
		showalert('请选择满意度评分');
		return;	
	}
	if(comment_content == '') {
		showalert('请至少写点你的感受');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'bespoke_id' : bespoke_id,
		'comment_level' : comment_level,
		'comment_score' : comment_score,
		'comment_content' : comment_content,
		'comment_image' : comment_image,		
	};
	if(comment_submit_btn) return;
	comment_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=bespoke_comment',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			comment_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('评价成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=order&op=bespoke';
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

$(function() {
	$('.tooltips').on('click', function() {
		var bespoke_id = $(this).attr('bespoke_id');
		var invoice_content = $('#invoice_content_'+bespoke_id).html();
		var self = this;
		$.pt({
			target: self,
			width : 400,
			position: 't',
    		align: 'r',
			autoClose: false,
			leaveClose: true,
			content: invoice_content
		});
	});
	
	$('.bespoke-cancel').on('click', function() {
		var bespoke_id = $(this).attr('bespoke_id');
		$('#cancel_id').val(bespoke_id);
		Custombox.open({
			target : '#cancel-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.bespoke-refund').on('click', function() {
		var bespoke_id = $(this).attr('bespoke_id');
		$('#refund_id').val(bespoke_id);
		Custombox.open({
			target : '#refund-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.bespoke-finish').on('click', function() {
		var bespoke_id = $(this).attr('bespoke_id');
		$('#finish_id').val(bespoke_id);
		Custombox.open({
			target : '#finish-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
});