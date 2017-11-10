var submit_btn = false;
function checkregister() {
	var formhash = $('#formhash').val();
	var next_step = 'nurse';
	var member_phone = $('#member_phone').val();
	var phone_code = $('#phone_code').val();
	var member_password = $('#member_password').val();
	var member_password2 = $('#member_password2').val();
	if(member_phone == '') {
		showerror('member_phone', '手机号必须填写');
		return;
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(member_phone)) {
		showerror('member_phone', '手机号格式不正确');
		return;
	}
	// if(phone_code == '') {
	// 	showerror('phone_code', '验证码必须填写');
	// 	return;
	// }
	if(member_password == '') {
		showerror('member_password', '密码必须填写');
		return;		
	}
	if(member_password != member_password2) {
		showerror('member_password2', '两次密码必须保证一致');
		return;		
	}
	if(!$('#agreement').is(':checked')) {
		showerror('agreement', '请阅读养老到家服务协议并同意');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'member_phone' : member_phone,
		'phone_code' : '',
		'member_password' : member_password,
		'member_password2' : member_password2,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=registerAdmin',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
					window.location.href = 'index.php?act=nurse&op=registerAdmin';

			} else if(data.done == 'login') {
					window.location.href = 'index.php?act=nurse&op=registerAdmin';
			} else {
				if(data.id == 'system') {
					showalert(data.msg);
				} else {
					showerror(data.id, data.msg);	
				}
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			submit_btn = false;
            alert(arguments[1]);
			// showalert('网路不稳定，请稍候重试');
		}
	});
}

$(function() {

	var code_btn = false;
	$('.take-code').on('click', function() {
		if($('.take-code').hasClass('acquired')) return;
		var member_phone = $('#member_phone').val();
		if(member_phone == '') {
			showerror('member_phone', '手机号必须填写');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(member_phone)) {
			showerror('member_phone', '手机号格式不正确');
			return;
		}
		if(code_btn) return;
		code_btn = true;
		var submitData = {
			'member_phone' : member_phone
		};

	});
		
	$('#member_phone').on('blur', function() {
		var member_phone = $('#member_phone').val();
		if(member_phone == '') {
			showerror('member_phone', '手机号必须填写');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(member_phone)) {
			showerror('member_phone', '手机号格式不正确');
			return;
		}
		$.getJSON('index.php?act=register&op=checkname', {'member_phone':member_phone}, function(data){
			if(data.done == 'true') {
				showsuccess('member_phone');
			} else {
				showerror('member_phone', data.msg);
			}
		});
	});
	
	// $('#phone_code').on('blur', function() {
	// 	var phone_code = $('#phone_code').val();
	// 	if(phone_code != '') {
	// 		showsuccess('phone_code');
	// 	}
	// });
	
	$('#member_password').on('blur', function() {
		var member_password = $('#member_password').val();
		if(member_password == '') {
			showerror('member_password', '密码必须填写');
			return;
		}
		showsuccess('member_password');
	});
	
	$('#member_password2').on('blur', function() {
		var member_password = $('#member_password').val();
		var member_password2 = $('#member_password2').val();
		if(member_password != member_password2) {
			showerror('member_password2', '两次密码必须保证一致');
			return;		
		}
		showsuccess('member_password2');
	});
});