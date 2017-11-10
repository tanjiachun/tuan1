var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var pdc_amount = $('#pdc_amount').val();
	if(pdc_amount == '') {
		showalert('请输入提现金额');
		return;
	}
	var regu = /^\d+$/;
	if(!regu.test(pdc_amount)) {
		showalert('提现金额必须是正整数');
		return;
	}
	if(pdc_amount < 10) {
		showalert('提现金额不能小于10');
		return;
	}
	if(pdc_amount > 50000) {
		showalert('提现金额不能大于50000');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'pdc_amount' : pdc_amount,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=cash',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {	
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=cash&op=step2';
			} else {
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

var cash_submit_btn = false;
function checkcash() {
	var formhash = $('#formhash').val();
	var pdc_code = $('#pdc_code').val();
	var alipay_card = $('#alipay_card').val();
	var weixin_card = $('#weixin_card').val(); 
	var bank_membername = $('#bank_membername').val();                
	var bank_deposit = $('#bank_deposit').val();
	var bank_card = $('#bank_card').val();
	if(pdc_code == 'alipay') {
		if(alipay_card == '') {
			showalert('请输入支付宝帐号');
			return;
		}
	} else if(pdc_code == 'weixin') {
		if(weixin_card == '') {
			showalert('请输入微信号');
			return;
		}
	} else if(pdc_code == 'bank') {
		if(bank_membername == '') {
			showalert('请输入收款人');
			return;	
		}
		if(bank_deposit == '') {
			showalert('请输入开户行');
			return;	
		}	
		if(bank_card == '') {
			showalert('请输入银行卡号');
			return;	
		}	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'pdc_code' : pdc_code,
		'alipay_card' : alipay_card,
		'weixin_card' : weixin_card,
		'bank_membername' : bank_membername,
		'bank_deposit' : bank_deposit,
		'bank_card' : bank_card,
	};
	if(cash_submit_btn) return;
	cash_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=cash&op=step2',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			cash_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('我们已经接收到您的提现申请');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=predeposit';
				}, 1000);
			} else {
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			cash_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});			
}

$(function() {
	$('.cash-box').on('click', '.select-choice', function() {
		$(this).siblings('.select-list').toggle();
	});
			   
	$('.cash-box').on('click', 'li', function() {
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');		
		if(field_value == 'alipay') {
			$('.alipay').show();
			$('.weixin').hide();
			$('.bank').hide();
		} else if(field_value == 'weixin') {
			$('.alipay').hide();
			$('.weixin').show();
			$('.bank').hide();
		} else if(field_value == 'bank') {
			$('.alipay').hide();
			$('.weixin').hide();
			$('.bank').show();
		}
	});
});