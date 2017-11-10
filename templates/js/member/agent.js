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
		url : 'index.php?act=agent&op=register',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			register_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=agent&op=step2';
			} else if(data.done == 'login') {
				window.location.href = 'index.php?act=agent&op=step2';
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
		url : 'index.php?act=agent&op=login',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			login_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=agent&op=step2';
			} else if(data.done == 'login') {
				window.location.href = 'index.php?act=agent&op=step2';
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
	var agent_name = $('#agent_name').val();
	var agent_provinceid = $('#agent_provinceid').val();
	var agent_cityid = $('#agent_cityid').val();
	var agent_areaid = $('#agent_areaid').val();
	var agent_address = $('#agent_address').val();
	var agent_longtitude=longtitude;
	var agent_latitude=latitude;
	console.log(agent_longtitude);
	console.log(agent_latitude);
	var agent_content = $('#agent_content').val();
	var agent_cardid = $('#agent_cardid').val();
	var agent_cardid_image = $('#agent_cardid_image').val();
	var agent_code_image = $('#agent_code_image').val();
	var i = 0;
	var agent_qa_image = {};
	$('.image_2').each(function() {
		agent_qa_image[i] = $(this).val();
		i++;
	});
	if(agent_name == '') {
		$('#agent_name').focus();
		showerror('agent_name', '机构名称必须填写');
		return;	
	}
	if(agent_cityid == '') {
		$('#agent_name').focus();
		showerror('agent_provinceid', '所在地区必须填写');
		return;
	}
	if(agent_address == '') {
		$('#agent_address').focus();
		showerror('agent_address', '详细地址必须填写');
		return;		
	}
	if(agent_content == '') {
		$('#agent_content').focus();
		showerror('agent_content', '服务描述必须填写');
		return;		
	}
	if(agent_cardid == '') {
		$('#agent_cardid').focus();
		showerror('agent_cardid', '身份证号码必须填写');
		return;
	}
	if(agent_cardid_image == '') {
		showerror('agent_cardid_image', '手持身份证照必须上传');
		return;		
	}
	if(agent_code_image == '') {
		showerror('agent_code_image', '组织机构代码证必须上传');
		return;		
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'agent_name' : agent_name,
		'agent_provinceid' : agent_provinceid,
		'agent_cityid' : agent_cityid,
		'agent_areaid' : agent_areaid,
		'agent_address' : agent_address,
		'agent_longtitude':agent_longtitude,
		'agent_latitude':agent_latitude,
		'agent_content' : agent_content,
		'agent_cardid' : agent_cardid,
		'agent_cardid_image' : agent_cardid_image,
		'agent_code_image' : agent_code_image,
		'agent_qa_image' : agent_qa_image,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=agent&op=step2',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=agent&op=step3';
			} else if(data.done == 'login') {
				window.location.href = 'index.php?act=agent&op=login';
			} else if(data.done == 'agent') {
				window.location.href = 'index.php?act=agent_center';
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
	
	// var code_btn = false;
	// $('.take-code').on('click', function() {
	// 	if($('.take-code').hasClass('acquired')) return;
	// 	var member_phone = $('#member_phone').val();
	// 	if(member_phone == '') {
	// 		showerror('member_phone', '手机号必须填写');
	// 		return;
	// 	}
	// 	var regu = /^[1][0-9]{10}$/;
	// 	if(!regu.test(member_phone)) {
	// 		showerror('member_phone', '手机号格式不正确');
	// 		return;
	// 	}
	// 	if(code_btn) return;
	// 	code_btn = true;
	// 	var submitData = {
	// 		'member_phone' : member_phone
	// 	};
	// 	$.ajax({
	// 		type : 'POST',
	// 		url : 'index.php?act=misc&op=first_code',
	// 		data : submitData,
	// 		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
	// 		dataType : 'json',
	// 		success : function(data){
	// 			console.log(data);
	// 			code_btn = false;
	// 			if(data.done == 'true') {
	// 				var second = 60;
	// 				$('.take-code').addClass('acquired');
	// 				$('.take-code').html('重新获取('+ second +'秒)');
	// 				var progress = setInterval(function(){
	// 					if(second <= 0) {
	// 						$('.take-code').removeClass('acquired');
	// 						$('.take-code').html('重新获取');
	// 						clearInterval(progress);
	// 					} else {
	// 						second--;
	// 						$('.take-code').html('重新获取('+ second +'秒)');
	// 					}
	// 				},1000);
	// 			} else {
	// 				showerror('member_phone', data.msg);
	// 			}
	// 		},
	// 		timeout : 15000,
	// 		error : function(xhr, type){
	// 			code_btn = false;
	// 			showalert('网路不稳定，请稍候重试');
	// 		}
	// 	});
	// });
		
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
	
	$('#agent_name').on('blur', function() {
		var agent_name = $('#agent_name').val();
		if(agent_name == '') {
			showerror('agent_name', '机构名称必须填写');
			return;
		}
		showsuccess('agent_name');
	});
	
	$('#agent_address').on('blur', function() {
		var agent_address = $('#agent_address').val();
		if(agent_address == '') {
			showerror('agent_address', '详细地址必须填写');
			return;
		}
		showsuccess('agent_address');
	});
	
	$('#agent_content').on('blur', function() {
		var agent_content = $('#agent_content').val();
		if(agent_content != '') {
			showsuccess('agent_content');
		}
	});
	
	$('#agent_cardid').on('blur', function() {
		var agent_cardid = $('#agent_cardid').val();
		if(agent_cardid == '') {
			showerror('agent_cardid', '身份证号码必须填写');
			return;
		}
		$.getJSON('index.php?act=misc&op=checkcard', {'card_id':agent_cardid}, function(data){
			if(data.done == 'true') {
				showsuccess('agent_cardid');
			} else {
				showerror('agent_cardid', data.msg);
			}
		});
	});
});