var register_submit_btn = false;
function checkregister() {
	var formhash = $('#formhash').val();
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
	if(phone_code == '') {
		showerror('phone_code', '验证码必须填写');
		return;
	}
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
		'phone_code' : phone_code,
		'member_password' : member_password,
		'member_password2' : member_password2,
	};
	if(register_submit_btn) return;
	register_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store&op=register',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			register_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=store&op=step2';
			} else if(data.done == 'login') {
				window.location.href = 'index.php?act=store&op=step2';
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
			register_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

var login_submit_btn = false;
function checklogin() {
	var formhash = $('#formhash').val();
	var login_phone = $('#login_phone').val();
	var login_password = $('#login_password').val();
	if(login_phone == '') {
		showerror('login_phone', '手机号必须填写');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(login_phone)) {
		showerror('login_phone', '手机号格式不正确');
		return;
	}
	if(login_password == '') {
		showerror('login_password', '密码必须填写');
		return;		
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'login_phone' : login_phone,
		'login_password' : login_password,
	};
	if(login_submit_btn) return;
	login_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store&op=login',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			login_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=store&op=step2';
			} else if(data.done == 'login') {
				window.location.href = 'index.php?act=store&op=step2';
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
			login_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var store_name = $('#store_name').val();
	var class_id = $('#class_id').val();
	var store_provinceid = $('#store_provinceid').val();
	var store_cityid = $('#store_cityid').val();
	var store_areaid = $('#store_areaid').val();
	var store_address = $('#store_address').val();
	var store_content = $('#store_content').val();
	var store_cardid = $('#store_cardid').val();
	var store_cardid_image = $('#store_cardid_image').val();
	var i = 0;
	var store_qa_image = {};
	$('.image_1').each(function() {
		store_qa_image[i] = $(this).val();
		i++;
	});
	if(store_name == '') {
		$('#store_name').focus();
		showerror('store_name', '店铺名称必须填写');
		return;	
	}
	if(class_id == '') {
		$('#store_name').focus();
		showerror('class_id', '产品类别必须填写');
		return;	
	}
	if(store_cityid == '') {
		$('#store_name').focus();
		showerror('store_provinceid', '所在地区必须填写');
		return;
	}
	if(store_address == '') {
		$('#store_address').focus();
		showerror('store_address', '详细地址必须填写');
		return;		
	}
	if(store_content == '') {
		$('#store_content').focus();
		showerror('store_content', '主营业务必须填写');
		return;		
	}
	if(store_cardid == '') {
		$('#store_cardid').focus();
		showerror('store_cardid', '身份证号码必须填写');
		return;
	}
	if(store_cardid_image == '') {
		showerror('store_cardid_image', '手持身份证照必须上传');
		return;		
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'store_name' : store_name,
		'class_id' : class_id,
		'store_provinceid' : store_provinceid,
		'store_cityid' : store_cityid,
		'store_areaid' : store_areaid,
		'store_address' : store_address,
		'store_content' : store_content,
		'store_cardid' : store_cardid,
		'store_cardid_image' : store_cardid_image,
		'store_qa_image' : store_qa_image,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store&op=step2',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=store&op=step3';
			} else if(data.done == 'login') {
				window.location.href = 'index.php?act=store&op=login';
			} else if(data.done == 'store') {
				window.location.href = 'index.php?act=store_center';
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
	$('#register_tab').on('click', function() {
		if(!$('#register_tab').hasClass('active')) {
			$('#register_tab').addClass('active');
		}
		if($('#login_tab').hasClass('active')) {
			$('#login_tab').removeClass('active');
		}
		$('#register_box').show();
		$('#login_box').hide();
	});
	
	$('#login_tab').on('click', function() {
		if($('#register_tab').hasClass('active')) {
			$('#register_tab').removeClass('active');
		}
		if(!$('#login_tab').hasClass('active')) {
			$('#login_tab').addClass('active');
		}
		$('#register_box').hide();
		$('#login_box').show();
	});
	
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
			url : 'index.php?act=misc&op=first_code',
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
	
	$('#login_phone').on('blur', function() {
		var login_phone = $('#login_phone').val();
		if(login_phone == '') {
			showerror('login_phone', '手机号必须填写');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(login_phone)) {
			showerror('login_phone', '手机号格式不正确');
			return;
		}
		$.getJSON('index.php?act=login&op=checkname', {'member_phone':login_phone}, function(data){
			if(data.done == 'true') {
				showsuccess('login_phone');
			} else {
				showerror('login_phone', data.msg);
			}
		});
	});
	
	$('#login_password').on('blur', function() {
		var login_password = $('#login_password').val();
		if(login_password == '') {
			showerror('login_password', '密码必须填写');
			return;
		}
		showsuccess('login_password');
	});
	
	$('#store_name').on('blur', function() {
		var store_name = $('#store_name').val();
		if(store_name == '') {
			showerror('store_name', '店铺名称必须填写');
			return;
		}
		showsuccess('store_name');
	});
	
	$('#store_address').on('blur', function() {
		var store_address = $('#store_address').val();
		if(store_address == '') {
			showerror('store_address', '详细地址必须填写');
			return;
		}
		showsuccess('store_address');
	});
	
	$('#store_content').on('blur', function() {
		var store_content = $('#store_content').val();
		if(store_content != '') {
			showsuccess('store_content');
		}
	});
	
	$('#store_cardid').on('blur', function() {
		var store_cardid = $('#store_cardid').val();
		if(store_cardid == '') {
			showerror('store_cardid', '身份证号码必须填写');
			return;
		}
		$.getJSON('index.php?act=misc&op=checkcard', {'card_id':store_cardid}, function(data){
			if(data.done == 'true') {
				showsuccess('store_cardid');
			} else {
				showerror('store_cardid', data.msg);
			}
		});
	});
});