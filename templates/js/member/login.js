function showprompt(msg) {
	var mode = isundefined(msg) ? 'succ' : 'error';
	if(mode == 'succ') {
		$('.login-tip').html('');
		if($('.login-tip').hasClass('tip-display')) {		
			$('.login-tip').removeClass('tip-display');			
		}
	} else {
		$('.login-tip').html('<i class="iconfont icon-type"></i>'+msg);
		if(!$('.login-tip').hasClass('tip-display')) {
			$('.login-tip').addClass('tip-display');
		}
	}
}


var submit_btn = false;
function checklogin() {
	var formhash = $('#formhash').val();
	var refer = $('#refer').val();
	var member_phone = $('#member_phone').val();
	var member_password = $('#member_password').val();
	var cookietime = $('#cookietime:checked').val();
	if(member_phone == '') {
		showprompt('手机号必须填写');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(member_phone)) {
		showprompt('手机号格式不正确');
		return;
	}
	if(member_password == '') {
		showprompt('密码必须填写');
		return;		
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'member_phone' : member_phone,
		'member_password' : member_password,
		'cookietime' : cookietime,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=login',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {	
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = refer;
			} else if(data.done == 'login') {
				window.location.href = refer;
			} else {
				showprompt(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

$(function() {		
	$('#member_phone').on('blur', function() {
		var member_phone = $('#member_phone').val();
		if(member_phone == '') {
			showprompt('手机号必须填写');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(member_phone)) {
			showprompt('手机号格式不正确');
			return;
		}
		$.getJSON('index.php?act=login&op=checkname', {'member_phone':member_phone}, function(data){
			if(data.done == 'true') {
				showprompt();
			} else {
				showprompt(data.msg);
			}
		});
	});
	
	$('#member_password').on('blur', function() {
		var member_password = $('#member_password').val();
		if(member_password == '') {
			showprompt('密码必须填写');
			return;
		}
		showprompt();
	});
});