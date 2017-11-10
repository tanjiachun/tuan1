var add_submit_btn = false;
function addsubmit() {
	var formhash = $('#formhash').val();
	var sp_name = $('#sp_name').val();
	var sp_sort = $('#sp_sort').val();
	var sp_format = $('#sp_format').val();
	if(sp_name == '') {
		showalert('请输入规格名称');
		return;	
	}
	var i = 0;
	var sp_value_id = {};
	$('[name="sp_value_id[]"]').each(function() {
		sp_value_id[i] = $(this).val();
		i++;
	});
	var i = 0;
	var sp_value_sort = {};
	$('[name="sp_value_sort[]"]').each(function() {
		sp_value_sort[i] = $(this).val();
		i++;
	});
	var i = 0;
	var sp_value_name = {};
	$('[name="sp_value_name[]"]').each(function() {
		sp_value_name[i] = $(this).val();
		i++;
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'sp_name' : sp_name,
		'sp_sort' : sp_sort,
		'sp_format' : sp_format,
		'sp_value_id' : sp_value_id,
		'sp_value_sort' : sp_value_sort,
		'sp_value_name' : sp_value_name
	};
	if(add_submit_btn) return;
	add_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_spec&op=add',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			add_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_spec';
				}, 1000);
			} else {
				showalert(data.msg);
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
	var sp_id = $('#sp_id').val();
	var sp_name = $('#sp_name').val();
	var sp_sort = $('#sp_sort').val();
	var sp_format = $('#sp_format').val();
	if(sp_name == '') {
		showalert('请输入规格名称');
		return;	
	}
	var i = 0;
	var sp_value_id = {};
	$('[name="sp_value_id[]"]').each(function() {
		sp_value_id[i] = $(this).val();
		i++;
	});
	var i = 0;
	var sp_value_sort = {};
	$('[name="sp_value_sort[]"]').each(function() {
		sp_value_sort[i] = $(this).val();
		i++;
	});
	var i = 0;
	var sp_value_name = {};
	$('[name="sp_value_name[]"]').each(function() {
		sp_value_name[i] = $(this).val();
		i++;
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'sp_id' : sp_id,
		'sp_name' : sp_name,
		'sp_sort' : sp_sort,
		'sp_format' : sp_format,
		'sp_value_id' : sp_value_id,
		'sp_value_sort' : sp_value_sort,
		'sp_value_name' : sp_value_name
	};
	if(edit_submit_btn) return;
	edit_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_spec&op=edit',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			edit_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_spec';
				}, 1000);
			} else {
				showalert(data.msg);
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
		url : 'index.php?act=store_spec&op=del',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			del_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#spec_'+del_id).remove();
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
	$('.sp-value-add').on('click', function() {
		var html = '<tr>';
		html += '<td><input type="hidden" name="sp_value_id[]" value="0" /><input type="text" name="sp_value_sort[]" class="form-input w-50" name="" value=""></td>';
		html += '<td><input type="text" name="sp_value_name[]" class="form-input w-400" name="" value=""></td>'
		html += '<td><a class="bluelink sp-value-del" href="javascript:;">删除</a></td>';
		html += '</tr>';
		$('.sp-value').append(html);
	});
	
	$(".sp-value").on('click', '.sp-value-del', function() {
		$(this).parent().parent().remove();	
	});
	
	$('.spec-list').on('click', '.spec-del', function() {
		var sp_id = $(this).attr('sp_id');
		$('#del_id').val(sp_id);
		Custombox.open({
			target : '#del-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});														  			 
	});
});