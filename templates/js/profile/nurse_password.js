var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var old_password = $('#old_password').val();
	var member_password = $('#member_password').val();
	var member_password2 = $('#member_password2').val();
	if(old_password == '') {
		showerror('old_password', '请输入原密码');
		return;	
	}
	if(member_password == '') {
		showerror('member_password', '请输入新密码');
		return;		
	}
	if(member_password != member_password2) {
		showerror('member_password2', '请保证两次密码一致');
		return;		
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'old_password' : old_password,
		'member_password' : member_password,
		'member_password2' : member_password2,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=nurse_password',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('修改成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=login';
				}, 1000);
			} else if(data.done == 'nurse') {
				window.location.href = 'index.php?act=register&next_step=nurse';	
			} else {
				showerror(data.id, data.msg);
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
	$('#old_password').on('blur', function() {
		var old_password = $('#old_password').val();
		if(old_password == '') {
			showerror('old_password', '请输入原密码');
			return;
		}
		showsuccess('old_password');
	});
	
	$('#member_password').on('blur', function() {
		var member_password = $('#member_password').val();
		if(member_password == '') {
			showerror('member_password', '请输入新密码');
			return;
		}
		showsuccess('member_password');
	});
	
	$('#member_password2').on('blur', function() {
		var member_password = $('#member_password').val();
		var member_password2 = $('#member_password2').val();
		if(member_password != member_password2) {
			showerror('member_password2', '请保证两次密码一致');
			return;		
		}
		showsuccess('member_password2');
	});
});