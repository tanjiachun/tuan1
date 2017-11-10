function showerror(msg) {
	$('.alert').html(msg);
	$(".alert-box").show();
	setTimeout(function() {
		$('.alert').html('');
		$(".alert-box").hide();
	}, 1500);
}

var submit_btn = false;
function checklogin() {
	var formhash = $("#formhash").val();
	var admin_name = $("#admin_name").val();
	var admin_password = $("#admin_password").val();
	var sec_code = $("#sec_code").val();
	var cookietime = $("#cookietime:checked").val();
	if(admin_name == '') {
		showerror('请输入用户名');
		return;	
	}
	if(admin_password == '') {
		showerror('请输入密码');
		return;		
	}
	// if(sec_code == '') {
	// 	showerror('请输入验证码');
	// 	return;
	// }
	var submitData = {
		"form_submit" : "ok",
		"formhash" : formhash,
		"admin_name" : admin_name,
		"admin_password" : admin_password,
		"sec_code" : sec_code,
		"cookietime" : cookietime,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : "POST",
		url : 'admin.php?act=login',
		data : submitData,
		contentType: "application/x-www-form-urlencoded; charset=utf-8",
		dataType : "json",
		success : function(data){
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'admin.php';
			} else {
				showerror(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			submit_btn = false;
			showerror('网路不稳定，请稍候重试');
		}
	});
}