var add_submit_btn = false;
function addsubmit() {
	var formhash = $('#formhash').val();
	var room_name = $('#room_name').val();
	var room_image = $('#room_image').val();
	var room_price = $('#room_price').val();
	var room_storage = $('#room_storage').val();
	var room_sort = $('#room_sort').val();
	var room_equipment = $('#room_equipment').val();
	var room_service = $('#room_service').val();
	var room_desc = $('#room_desc').val();
	var i = 0;
	var room_image_more = {};
	$('.image_1').each(function() {
		room_image_more[i] = $(this).val();
		i++;
	});
	var i = 0;
	var room_support = {};
	$('.support').each(function() {
		if($(this).hasClass('active')) {
			room_support[i] = $(this).attr('support');
			i++;
		}
	});
	if(room_name == '') {
		$('#room_name').focus();
		showerror('room_name', '请输入你的房间名称');
		return;	
	}
	if(room_image == '') {
		showerror('room_image', '请上传你的房间图片');
		return;		
	}
	if(room_price == '') {
		$('#room_price').focus();
		showerror('room_price', '请输入你的房间价格');
		return;	
	}
	if(room_storage == '') {
		$('#room_storage').focus();
		showerror('room_storage', '请输入你的床位数');
		return;
	}	
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'room_name' : room_name,
		'room_image' : room_image,
		'room_image_more' : room_image_more,
		'room_price' : room_price,
		'room_storage' : room_storage,
		'room_sort' : room_sort,
		'room_support' : room_support,
		'room_equipment' : room_equipment,
		'room_service' : room_service,
		'room_desc' : room_desc,
	};
	if(add_submit_btn) return;
	add_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=pension_room&op=add',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			add_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=pension_room';
				}, 1000);
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
			add_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

var edit_submit_btn = false;
function editsubmit() {
	var formhash = $('#formhash').val();
	var room_id = $('#room_id').val();
	var room_name = $('#room_name').val();
	var room_image = $('#room_image').val();
	var room_price = $('#room_price').val();
	var room_storage = $('#room_storage').val();
	var room_sort = $('#room_sort').val();
	var room_equipment = $('#room_equipment').val();
	var room_service = $('#room_service').val();
	var room_desc = $('#room_desc').val();
	var i = 0;
	var room_image_more = {};
	$('.image_1').each(function() {
		room_image_more[i] = $(this).val();
		i++;
	});
	var i = 0;
	var room_support = {};
	$('.support').each(function() {
		if($(this).hasClass('active')) {
			room_support[i] = $(this).attr('support');
			i++;
		}
	});
	if(room_name == '') {
		$('#room_name').focus();
		showerror('room_name', '请输入你的房间名称');
		return;	
	}
	if(room_image == '') {
		showerror('room_image', '请上传你的房间图片');
		return;		
	}
	if(room_price == '') {
		$('#room_price').focus();
		showerror('room_price', '请输入你的房间价格');
		return;	
	}
	if(room_storage == '') {
		$('#room_storage').focus();
		showerror('room_storage', '请输入你的床位数');
		return;
	}	
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'room_id' : room_id,
		'room_name' : room_name,
		'room_image' : room_image,
		'room_image_more' : room_image_more,
		'room_price' : room_price,
		'room_storage' : room_storage,
		'room_sort' : room_sort,
		'room_support' : room_support,
		'room_equipment' : room_equipment,
		'room_service' : room_service,
		'room_desc' : room_desc,
	};
	if(edit_submit_btn) return;
	edit_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=pension_room&op=edit',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			edit_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('修改成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=pension_room';
				}, 1000);
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
		url : 'index.php?act=pension_room&op=del',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			del_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#room_'+del_id).remove();
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

$(function() {	
	$('.support').on('click', function() {
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');	
		}
	});
	
	$('#room_name').on('blur', function() {
		var room_name = $('#room_name').val();
		if(room_name == '') {
			showerror('room_name', '请输入你的房间名称');
			return;
		}
		showsuccess('room_name');
	});
	
	$('#room_price').on('blur', function() {
		var room_price = $('#room_price').val();
		if(room_price == '') {
			showerror('room_price', '请输入你的房间价格');
			return;
		}
		showsuccess('room_price');
	});
	
	$('#room_storage').on('blur', function() {
		var room_storage = $('#room_storage').val();
		if(room_storage == '') {
			showerror('room_storage', '请输入你的房间库存');
			return;
		}
		showsuccess('room_storage');
	});
	
	$('.room-list').on('click', '.room-del', function() {
		var room_id = $(this).attr('room_id');
		$('#del_id').val(room_id);
		Custombox.open({
			target : '#del-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});														  			 
	});
});