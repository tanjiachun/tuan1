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
		url : 'index.php?act=pension_bespoke&op=refund',
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
	
	$('.bespoke-refund').on('click', function() {
		var bespoke_id = $(this).attr('bespoke_id');
		var refund_amount = $(this).attr('refund_amount');
		var refund_reason = $(this).attr('refund_reason');
		$('#refund_id').val(bespoke_id);
		$('#refund_amount').val(refund_amount);
		$('#refund_reason').html(refund_reason);
		Custombox.open({
			target : '#refund-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
});