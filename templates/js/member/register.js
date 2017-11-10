var submit_btn = false;
function checkregister() {
	var formhash = $('#formhash').val();
	var next_step = $('#next_step').val();
	var member_phone = $('#member_phone').val();
	var phone_code = $('#phone_code').val();
	var pwd=$("#member_pwd").val();
	var pwd2=$("#member_pwd2").val();
    if(!$("#agreen").is(":checked")){
        showerror('agreen', '请阅读相关协议并同意');
        return
    }else{
        showsuccess('agreen');
	}
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
	if(pwd==''){
        showerror('phone_pwd', '密码必须填写');
        return;
	}else{
		showsuccess('phone_pwd');
	}
	if(pwd.length<9){
        showerror('phone_pwd', '密码必须大于9位');
        return;
	}else{
		showsuccess('phone_pwd')
	}
    var reg = new RegExp("\\s");
    if(pwd.substr(0).match(reg)!=null){
        showerror('phone_pwd','密码不能存在空格');
        return;
    }else{
        showsuccess('phone_pwd');
    }
	if(pwd2==''){
        showerror('phone_pwd2', '请再次输入密码');
        return;
	}else{
		showsuccess('phone_pwd2');
	}
	if(pwd!==pwd2){
		showerror('pwd2','请确保两次密码一致');
	}else{
		showsuccess('pwd2');
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'member_phone' : member_phone,
		'phone_code' : phone_code,
		'pwd':pwd,
		'pwd2':pwd2
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=register',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href='index.php?act=register_succ';
			} else if(data.done == 'login') {
				if(next_step == 'normal') {
					window.location.href = 'index.php?act=register';
				} else {
					window.location.href = 'index.php?act=nurse&op=register';
				} 
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
			url : 'index.php?act=misc&op=register',
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
		$.getJSON('index.php?act=register&op=checkname', {'member_phone':member_phone}, function(data){
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