var add_submit_btn = false;
function addsubmit() {
	var formhash = $('#formhash').val();
	var true_name = $('#true_name').val();
	var mobile_phone = $('#mobile_phone').val();
	var province_id = $('#province_id').val();
	var city_id = $('#city_id').val();
	var area_id = $('#area_id').val();
	var address_info = $('#address_info').val();
	if(true_name == '') {
		showwarning('address-box', '请输入联系人');
		return;	
	}
	if(mobile_phone == '') {
		showwarning('address-box', '请输入电话');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(mobile_phone)) {
		showwarning('address-box', '电话格式不正确');
		return;
	}
	if(province_id == '' || city_id == '') {
		showwarning('address-box', '请选择所在地区');
		return;	
	}
	if(address_info == '') {
		showwarning('address-box', '请输入详细地址');
		return;	
	}	
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'true_name' : true_name,
		'mobile_phone' : mobile_phone,
		'province_id' : province_id,
		'city_id' : city_id,
		'area_id' : area_id,
		'address_info' : address_info
	};
	if(add_submit_btn) return;
	add_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=address&op=add',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			add_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					var address_count = $('#address_count').val();
					address_count = parseInt(address_count) + 1;
					$('#address_count').val(address_count);
					$('.address-list').load('index.php?act=address&op=address');	
				});
			} else {
				showwarning('address-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			add_submit_btn = false;
			showwarning('address-box', '网路不稳定，请稍候重试');
		}
	});
}

var edit_submit_btn = false;
function editsubmit() {
	var formhash = $('#formhash').val();
	var address_id = $('#address_id').val();
	var true_name = $('#true_name').val();
	var mobile_phone = $('#mobile_phone').val();
	var province_id = $('#province_id').val();
	var city_id = $('#city_id').val();
	var area_id = $('#area_id').val();
	var address_info = $('#address_info').val();
	if(true_name == '') {
		showwarning('address-box', '请输入联系人');
		return;	
	}
	if(mobile_phone == '') {
		showwarning('address-box', '请输入电话');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(mobile_phone)) {
		showwarning('address-box', '电话格式不正确');
		return;
	}
	if(province_id == '' || city_id == '') {
		showwarning('address-box', '请选择所在地区');
		return;	
	}
	if(address_info == '') {
		showwarning('address-box', '请输入详细地址');
		return;	
	}	
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'address_id' : address_id,
		'true_name' : true_name,
		'mobile_phone' : mobile_phone,
		'province_id' : province_id,
		'city_id' : city_id,
		'area_id' : area_id,
		'address_info' : address_info
	};
	if(edit_submit_btn) return;
	edit_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=address&op=edit',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			edit_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('.address-list').load('index.php?act=address&op=address');	
				});
			} else {
				showwarning('address-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			edit_submit_btn = false;
			showwarning('address-box', '网路不稳定，请稍候重试');
		}
	});
}

var del_submit_btn = false;
function delsubmit() {
	var formhash = $('#formhash').val();
	var del_id = $('#del_id').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'del_id' : del_id,
	};
	if(del_submit_btn) return;
	del_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=address&op=del',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			del_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('.address-list').load('index.php?act=address&op=address');
					$('.alert-box .tip-title').html('删除成功');
					$('.alert-box').show();
					setTimeout(function() {
						$('.alert-box .tip-title').html('');
						$('.alert-box').hide();
					}, 2000);
				});
			} else {
				showwarning('del-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			del_submit_btn = false;
			showwarning('del-box', '网路不稳定，请稍候重试');
		}
	});
}

var default_submit_btn = false;
function defaultsubmit() {
	var formhash = $('#formhash').val();
	var default_id = $('#default_id').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'default_id' : default_id,
	};
	if(default_submit_btn) return;
	default_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=address&op=default',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			default_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('.address-list').load('index.php?act=address&op=address');
					$('.alert-box .tip-title').html('设置成功');
					$('.alert-box').show();
					setTimeout(function() {
						$('.alert-box .tip-title').html('');
						$('.alert-box').hide();
					}, 2000);
				});
			} else {
				showwarning('default-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			default_submit_btn = false;
			showwarning('default-box', '网路不稳定，请稍候重试');
		}
	});
}

$(function() {
	$('.address-add').on('click', function() {
		Custombox.open({
			target: 'index.php?act=address&op=add',
			effect: 'blur',
			overlayClose: true,
			speed:500,
			overlaySpeed: 300,
		});															  			 
	});
	
	$('.address-list').on('click', '.address-edit', function() {
		var address_id = $(this).attr('address_id');
		Custombox.open({
			target: 'index.php?act=address&op=edit&address_id='+address_id,
			effect: 'blur',
			overlayClose: true,
			speed:500,
			overlaySpeed: 300,
		});															  			 
	});
	
	$('.address-list').on('click', '.address-del', function() {
		var address_id = $(this).attr('address_id');
		$('#del_id').val(address_id);
		Custombox.open({
			target : '#del-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});														  			 
	});
	
	$('.address-list').on('click', '.address-default', function() {
		var address_id = $(this).attr('address_id');
		$('#default_id').val(address_id);
		Custombox.open({
			target : '#default-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});													  			 
	});
});