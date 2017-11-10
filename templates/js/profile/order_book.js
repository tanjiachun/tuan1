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
		url : 'index.php?act=order&op=book_cancel',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			cancel_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+cancel_id).html('已取消');
					$('#opr_'+cancel_id).remove();
					window.location.reload();
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
		url : 'index.php?act=order&op=book_refund',
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
		url : 'index.php?act=order&op=book_finish',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			finish_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+finish_id).html('待评价');
					$('#opr_'+finish_id).html('<a href="index.php?act=order&op=book_comment&book_id='+finish_id+'" class="btn btn-primary">立即评价</a>');
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
	var book_id = $('#book_id').val();
	var comment_level = $('#comment_level').val();
	var comment_honest_star = $('[name="comment_honest_star"]:checked').val();
	var comment_love_star = $('[name="comment_love_star"]:checked').val();
	var comment_content = $('#comment_content').val();
	var i = 0;
	var tag_ids = {};
	$('.tag-item').each(function() {
		if($(this).hasClass('tag-checked')) {
			tag_ids[i] = $(this).attr('tag_id');
			i++;
		}
	});
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
		showalert('请至少写点你的服务感受');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'book_id' : book_id,
		'comment_level' : comment_level,
		'comment_honest_star' : comment_honest_star,
		'comment_love_star' : comment_love_star,
		'tag_ids' : tag_ids,
		'comment_content' : comment_content,
		'comment_image' : comment_image,		
	};
	if(comment_submit_btn) return;
	comment_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=book_comment',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			comment_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('评价成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=order&op=book';
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
	$('.book-cancel').on('click', function() {
		var book_id = $(this).attr('book_id');
		$('#cancel_id').val(book_id);
		Custombox.open({
			target : '#cancel-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.book-refund').on('click', function() {
		var book_id = $(this).attr('book_id');
		$('#refund_id').val(book_id);
		Custombox.open({
			target : '#refund-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.book-finish').on('click', function() {
		var book_id = $(this).attr('book_id');
		$('#finish_id').val(book_id);
		Custombox.open({
			target : '#finish-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.tag-box').on('click', '.tag-item', function() {
		if($(this).hasClass('tag-checked')) {
			$(this).removeClass('tag-checked');	
		} else {
			$(this).addClass('tag-checked');
		}
	});
});