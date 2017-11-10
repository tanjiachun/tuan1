var submit_btn = false;
function checkforget() {
	var formhash = $('#formhash').val();
	var member_phone = $('#member_phone').val();
	var phone_code = $('#phone_code').val();
	if(member_phone == '') {
		showerror('member_phone', '手机号必须填写');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(member_phone)) {
		showerror('member_phone', '手机号格式不正确');
		return;
	}
	if(phone_code == '') {
		showerror('phone_code', '验证码必须填写');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'member_phone' : member_phone,
		'phone_code' : phone_code,
	};
	console.log(submitData);
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=forget',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=forget&op=step2';
			} else if(data.done == 'login') {
				// window.location.href = 'index.php?act=forget';
				console.log(1);
			} else {
				// showerror(data.id, data.msg);
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

var edit_submit_btn = false;
function checkedit() {
	var formhash = $('#formhash').val();
	var member_password = $('#member_password').val();
	var member_password2 = $('#member_password2').val();
	if(member_password == '') {
		showerror('member_password', '密码必须填写');
		return;		
	}
	if(member_password.length<9){
		showerror('member_password','密码不能小于9位');
		return;
	}else{
		showsuccess('member_password');
	}
    var reg = new RegExp("\\s");
    if(member_password.substr(0).match(reg)!=null){
        showerror('member_password','密码不能存在空格');
        return;
    }else{
        showsuccess('member_password');
    }
	if(member_password != member_password2) {
		showerror('member_password2', '两次密码必须保证一致');
		return;		
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'member_password' : member_password,
		'member_password2' : member_password2,
	};
	if(edit_submit_btn) return;
	edit_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=forget&op=step2',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			edit_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=forget&op=step3';
			} else if(data.done == 'login') {
				window.location.href = 'index.php?act=forget';
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
			edit_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
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
		$.ajax({
			type : 'POST',
			url : 'index.php?act=misc&op=forget_pwd_code',
			data : submitData,
			contentType: 'application/x-www-form-urlencoded; charset=utf-8',
			dataType : 'json',
			success : function(data){
				code_btn = false;
				if(data.done == 'true') {
					var second = 60;
					$('.take-code').addClass('acquired');
					$('.take-code').html('重新获取('+ second +'秒)');
					var progress = setInterval(function(){
						if(second <= 0) {
							$('.take-code').removeClass('acquired');
							$('.take-code').html('重新获取');
							clearInterval(progress);
						} else {
							second--;
							$('.take-code').html('重新获取('+ second +'秒)');							
						}
					},1000);
				} else {
					showerror('member_phone', data.msg);					
				}
			},
			timeout : 15000,
			error : function(xhr, type){
				code_btn = false;
				showalert('网路不稳定，请稍候重试');
			}
		});
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
		$.getJSON('index.php?act=login&op=checkname', {'member_phone':member_phone}, function(data){
			if(data.done == 'true') {
				showsuccess('member_phone');
			} else {
				showerror('member_phone', data.msg);
			}
		});
	});
	
	$('#phone_code').on('blur', function() {
		var phone_code = $('#phone_code').val();
		if(phone_code != '') {
			showsuccess('phone_code');
		}
	});
	
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