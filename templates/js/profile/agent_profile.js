var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var agent_name = $('#agent_name').val();
	var agent_logo = $('#agent_logo').val();
	var agent_banner = $('#agent_banner').val();
	var agent_qq = $('#agent_qq').val();
	var agent_phone = $('#agent_phone').val();
	var agent_provinceid = $('#agent_provinceid').val();
	var agent_cityid = $('#agent_cityid').val();
	var agent_areaid = $('#agent_areaid').val();
	var agent_address = $('#agent_address').val();
	var agent_content = $('#agent_content').val();
	if(agent_name == '') {
		$('#agent_name').focus();
		showerror('agent_name', '请输入你的机构名称');
		return;	
	}
	if(agent_qq == '') {
		$('#agent_qq').focus();
		showerror('agent_qq', '输入你的客服QQ');
		return;	
	}
	if(agent_phone == '') {
		$('#agent_phone').focus();
		showerror('agent_phone', '输入你的联系电话');
		return;	
	}
	if(agent_cityid == '') {
		showerror('agent_provinceid', '请选择所在地区');
		return;
	}
	if(agent_address == '') {
		$('#agent_address').focus();
		showerror('agent_address', '请输入详细地址');
		return;
	}
	if(agent_content == '') {
		$('#agent_content').focus();
		showerror('agent_content', '请输入服务描述');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'agent_name' : agent_name,
		'agent_logo' : agent_logo,
		'agent_banner' : agent_banner,
		'agent_qq' : agent_qq,
		'agent_phone' : agent_phone,
		'agent_provinceid' : agent_provinceid,
		'agent_cityid' : agent_cityid,
		'agent_areaid' : agent_areaid,
		'agent_address' : agent_address,
		'agent_content' : agent_content,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=agent_profile',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('修改成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=agent_profile';
				}, 1000);
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
	$('#agent_name').on('blur', function() {
		var agent_name = $('#agent_name').val();
		if(agent_name == '') {
			showerror('agent_name', '请输入你的机构名称');
			return;
		}
		showsuccess('agent_name');
	});
	
	$('#agent_qq').on('blur', function() {
		var agent_qq = $('#agent_qq').val();
		if(agent_qq == '') {
			showerror('agent_qq', '输入你的客服QQ');
			return;
		}
		showsuccess('agent_qq');
	});
	
	$('#agent_address').on('blur', function() {
		var agent_address = $('#agent_address').val();
		if(agent_address == '') {
			showerror('agent_address', '请输入详细地址');
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
});