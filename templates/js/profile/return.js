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
		url : 'index.php?act=return&op=deliver',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			deliver_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+deliver_id).html('买家已发货');				 
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
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'finish_id' : finish_id,
	};
	if(finish_submit_btn) return;
	finish_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=return&op=finish',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			finish_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#state_'+finish_id).html('退换货完成');
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