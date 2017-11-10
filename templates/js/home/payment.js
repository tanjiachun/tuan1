var book_submit_btn = false;
function booksubmit() {
	var formhash = $('#formhash').val();
	var book_sn = $('#book_sn').val();
	var payment_code = $('#payment_code').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'book_sn' : book_sn,
		'payment_code' : payment_code,
	};
	if(book_submit_btn) return;
	book_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=book&op=payment',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			book_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=payment&op=book&book_sn='+data.book_sn;
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
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			book_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

var order_submit_btn = false;
function ordersubmit() {
	var formhash = $('#formhash').val();
	var order_sn = $('#order_sn').val();
	var payment_code = $('#payment_code').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'order_sn' : order_sn,
		'payment_code' : payment_code,
	};
	if(order_submit_btn) return;
	order_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=order&op=payment',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			order_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=payment&op=order&order_sn='+data.order_sn;
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
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			order_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

var bespoke_submit_btn = false;
function bespokesubmit() {
	var formhash = $('#formhash').val();
	var bespoke_sn = $('#bespoke_sn').val();
	var payment_code = $('#payment_code').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'bespoke_sn' : bespoke_sn,
		'payment_code' : payment_code,
	};
	if(bespoke_submit_btn) return;
	bespoke_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=bespoke&op=payment',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			bespoke_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=payment&op=bespoke&bespoke_sn='+data.bespoke_sn;
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
				showalert(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			bespoke_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

$(function() {	
	$('.payment-box').on('click', 'li', function() {
		var payment_code = $(this).attr('payment_code');
		$('#payment_code').val(payment_code);
		$(this).addClass('active');
		$(this).siblings('li').removeClass('active');
	});
});