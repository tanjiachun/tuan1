var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var pdr_amount = $('#pdr_amount').val();
	var pdr_payment_code = $('#pdr_payment_code').val();
	if(pdr_amount == '') {
		showalert('请输入充值金额');
		return;
	}
	var regu = /^\d+$/;
	if(!regu.test(pdr_amount)) {
		showalert('充值金额必须是正整数');
		return;
	}
	if(pdr_amount < 10) {
		showalert('充值金额不能小于10');
		return;
	}
	if(pdr_amount > 50000) {
		showalert('充值金额不能大于50000');
		return;
	}
	if(pdr_payment_code == '') {
		showalert('请选择充值方式');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'pdr_amount' : pdr_amount,
		'pdr_payment_code' : pdr_payment_code,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=recharge',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {	
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=payment&op=recharge&pdr_sn='+data.pdr_sn;
			} else {
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			submit_btn = false;
			showmeAlert('网路不稳定，请稍候重试');
		}
	});
}