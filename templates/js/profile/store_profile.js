var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var store_name = $('#store_name').val();
	var store_logo = $('#store_logo').val();
	var store_banner = $('#store_banner').val();
	var store_provinceid = $('#store_provinceid').val();
	var store_cityid = $('#store_cityid').val();
	var store_areaid = $('#store_areaid').val();
	var store_address = $('#store_address').val();
	var store_content = $('#store_content').val();
	var store_qq = $('#store_qq').val();
	var store_ww = $('#store_ww').val();
	var store_phone = $('#store_phone').val();
	if(store_name == '') {
		showerror('store_name', '店铺名称必须填写');
		return;	
	}
	if(store_cityid == '') {
		showerror('store_provinceid', '请选择所在地区');
		return;
	}
	if(store_address == '') {
		$('#store_address').focus();
		showerror('store_address', '请输入详细地址');
		return;
	}
	if(store_content == '') {
		showerror('store_content', '主营业务必须填写');
		return;		
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'store_name' : store_name,
		'store_logo' : store_logo,
		'store_banner' : store_banner,
		'store_provinceid' : store_provinceid,
		'store_cityid' : store_cityid,
		'store_areaid' : store_areaid,
		'store_address' : store_address,
		'store_content' : store_content,
		'store_qq' : store_qq,
		'store_ww' : store_ww,
		'store_phone' : store_phone,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_profile',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('修改成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_profile';
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
	$('#store_name').on('blur', function() {
		var store_name = $('#store_name').val();
		if(store_name == '') {
			showerror('store_name', '店铺名称必须填写');
			return;
		}
		showsuccess('store_name');
	});
	
	$('#store_content').on('blur', function() {
		var store_content = $('#store_content').val();
		if(store_content != '') {
			showsuccess('store_content');
		}
	});
});