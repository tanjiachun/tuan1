function spec_image_show(obj){
	var V = obj.parent().find('.active');
	if(V.length == 0) {
		obj.siblings('.sku-image-file').hide();
	} else {
		obj.siblings('.sku-image-file').show();
	}
	var S = $('.spec_image_'+obj.attr('sp_value_id'));
	if(S.css('display') == 'none'){
		S.show();
	}else{
		S.hide();
	}
}

function price_interval() {
	var price_max = 0.01;
	var price_min = 10000000;
	$('input[data_type="price"]').each(function() {
		if($(this).val() != '') {
			price_max = Math.max(price_max, $(this).val());
			price_min = Math.min(price_min, $(this).val());
		}
	});
	if(price_max > price_min) {
		$('input[name="goods_price_interval"]').val(price_min.toFixed(2)+' - '+price_max.toFixed(2));
	} else {
		$('input[name="goods_price_interval"]').val('');
	}
	if(price_min != 10000000) {
		$('input[name="goods_price"]').val(price_min.toFixed(2));
	}
}

function storage_sum(){
	var storage = 0;
	$('input[data_type="storage"]').each(function() {
		if($(this).val() != '') {
			storage += parseInt($(this).val());
		}
	});
	$('input[name="goods_storage"]').val(storage);
}

function checksubmit() {
	var goods_name = $('#goods_name').val();
	var class_id = $('#class_id').val();
	var goods_price = $('#goods_price').val();
	var goods_storage = $('#goods_storage').val();
	if(goods_name == '') {
		$('#goods_name').focus();
		showerror('goods_name', '请输入商品名称');
		return;
	}
	if(class_id == '') {
		showerror('class_id', '请选择商品类别');
		return;
	}
	if(goods_price == '') {
		$('#goods_price').focus();
		showerror('goods_price', '请输入商品价格');
		return;		
	}
	showsuccess('goods_price');	
	if(goods_storage == '') {
		$('#goods_storage').focus();
		showerror('goods_storage', '请输入商品库存');
		return;		
	}
	showsuccess('goods_storage');
	$('#goods_form').submit();
}

var del_submit_btn = false;
function delsubmit() {
	var formhash = $('#formhash').val();
	var del_ids = $('#del_ids').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'del_ids' : del_ids,
	};
	if(del_submit_btn) return;
	del_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_goods&op=del',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			del_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					for(var i=0; i<data.del_ids; i++) {
						$('#goods_'+data.del_ids[i]).remove();
					}					
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

var unshow_submit_btn = false;
function unshowsubmit() {
	var formhash = $('#formhash').val();
	var unshow_ids = $('#unshow_ids').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'unshow_ids' : unshow_ids,
	};
	if(unshow_submit_btn) return;
	unshow_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_goods&op=unshow',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			unshow_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					for(var i=0; i<data.unshow_ids; i++) {
						$('#goods_'+data.unshow_ids[i]).remove();
					}					
					$('.alert-box .tip-title').html('下架成功');
					$('.alert-box').show();
					setTimeout(function() {
						$('.alert-box .tip-title').html('');
						$('.alert-box').hide();
					}, 2000);
				});
			} else {
				showwarning('unshow-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			unshow_submit_btn = false;
			showwarning('unshow-box', '网路不稳定，请稍候重试');
		}
	});
}

var show_submit_btn = false;
function showsubmit() {
	var formhash = $('#formhash').val();
	var show_ids = $('#show_ids').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'show_ids' : show_ids,
	};
	if(show_submit_btn) return;
	show_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_goods&op=show',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			show_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					for(var i=0; i<data.show_ids; i++) {
						$('#goods_'+data.show_ids[i]).remove();
					}					
					$('.alert-box .tip-title').html('上架成功');
					$('.alert-box').show();
					setTimeout(function() {
						$('.alert-box .tip-title').html('');
						$('.alert-box').hide();
					}, 2000);
				});
			} else {
				showwarning('show-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			show_submit_btn = false;
			showwarning('show-box', '网路不稳定，请稍候重试');
		}
	});
}

var price_submit_btn = false;
function pricesubmit() {
	var formhash = $('#formhash').val();
	var goods_id = $('#goods_id').val();
	var spec_price = {};
	$('.spec_goods_price').each(function() {
		var spec_id = $(this).attr('spec_id');
		spec_price[spec_id] = $(this).val();
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'goods_id' : goods_id,
		'spec_price' : spec_price,
	};
	if(price_submit_btn) return;
	price_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_goods&op=price',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			price_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#price_'+goods_id).html(data.price);			 
				});
			} else {
				showwarning('price-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			price_submit_btn = false;
			showwarning('price-box', '网路不稳定，请稍候重试');
		}
	});
}

var storage_submit_btn = false;
function storagesubmit() {
	var formhash = $('#formhash').val();
	var goods_id = $('#goods_id').val();
	var spec_storage = {};
	$('.spec_goods_storage').each(function() {
		var spec_id = $(this).attr('spec_id');
		spec_storage[spec_id] = $(this).val();
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'goods_id' : goods_id,
		'spec_storage' : spec_storage,
	};
	if(storage_submit_btn) return;
	storage_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_goods&op=storage',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			storage_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#storage_'+goods_id).html(data.storage);			 
				});
			} else {
				showwarning('storage-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			storage_submit_btn = false;
			showwarning('storage-box', '网路不稳定，请稍候重试');
		}
	});
}

function image_upload(id, field_id) {
	$.ajaxFileUpload({
		url : 'index.php?act=misc&op=upload&thumb=0',
		secureuri : false,
		fileElementId : id,
		dataType : 'JSON',
		data:{id : id, field_id : field_id, file_name : file_name},
		success : function(data, status) {
			var data = $.parseJSON(data);
			if(data.done == 'true') {
				var html = '<div class="sku-atom"><span class="sku-img"><img src="'+data.file_path+'"></span><span class="close-modal" onclick="image_close(\''+data.field_id+'\')">×</span></div>';
				$('#spec_image_'+data.field_id).val(data.file_path);
				$('#show_image_'+data.field_id).html(html);
			} else {
				showalert(data.msg);	
			}
		},
		error : function(data, status, e) {
			showalert(e);
		}
	});
	return false;
}

function image_close(field_id) {
	var html = '<div class="sku-atom-file"><i class="iconfont icon-camera"></i><span class="img-upload"><input type="file" id="file_'+field_id+'" name="file_'+field_id+'" hidefocus="true" maxlength="0" onchange="image_upload(\'file_'+field_id+'\', \''+field_id+'\')"></span></div>';
	$('#show_image_'+field_id).html(html);
}

$(function() {
	$('.class-box, .attr-box, .transport-box').on('click', '.select-choice', function() {
		$(this).siblings('.select-list').toggle();
	});
	
	$('.class-box').on('click', 'li', function() {
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');
		if($('.select-box').siblings('.Validform-wrong').hasClass('tip-display')) {	
			$('.select-box').siblings('.Validform-wrong').html('');
			$('.select-box').siblings('.Validform-wrong').removeClass('tip-display');			
		}
		$.getJSON('index.php?act=store_goods&op=attr', {'class_id':field_value}, function(data){
			if(data.done == 'true') {
				$('.attr-body').html(data.html);
			}
		});
	});
	
	$('.attr-box, .transport-box').on('click', 'li', function() {
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');
	});
	
	$('.spec-list').on('click', '.check', function() {
		if($(this).hasClass("active")) {
			$(this).removeClass("active");
		} else {
			$(this).addClass("active");
		}
		$('input[name="goods_price"]').removeAttr('readonly').css('background','');
		$('input[name="goods_storage"]').removeAttr('readonly').css('background','');
		$('input[name="goods_price"]').val('');
		$('input[name="goods_storage"]').val('');
		var items = '';
		$('.spec-list').find('.active').each(function(){
			var sp_id = $(this).attr('sp_id');
			items += sp_id + ','
		});
		items = items.substr(0, (items.length - 1));
		$('.spec-box').load('index.php?act=store_goods&op=spec&items='+items);
	});
	
	$('.spec-box').on('click', '.check', function() {
		var sp_value_id = $(this).attr('sp_value_id');
		var sp_value_name = $(this).attr('sp_value_name');
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$("#sp_value_"+sp_value_id).val('');
		} else {
			$(this).addClass('active');
			$("#sp_value_"+sp_value_id).val(sp_value_name);
		}
		into_array();
		spec_show();
		spec_image_show($(this));
	});
	
	$('.spec-box').on('change', 'input[type="text"]',function() {
		s = $(this).attr('mall_type');
		V[s] = $(this).val();
	});

	$('.spec-box').on('change', 'input[data_type="storage"]', function() {
		storage_sum();
	});

	$('.spec-box').on('change', 'input[data_type="price"]', function() {
		price_interval();
	});
	
	$('.promotion-box').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		if(field_value == 'normal') {
			$('#normal').hide();
		} else if(field_value == 'cheap') {
			$('#normal').show();
			$('#cheap').show();
			$('#group').hide();
		} else if(field_value == 'group') {
			$('#normal').show();
			$('#cheap').hide();
			$('#group').show();
		}
	});
	
	$('.transport-list').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		if(field_value == 'freight') {
			$('#freight').show();
			$('#transport').hide();
		} else if(field_value == 'transport') {
			$('#freight').hide();
			$('#transport').show();
		}
	});
	
	$('#goods_name').on('blur', function() {
		var goods_name = $('#goods_name').val();
		if(goods_name == '') {
			showerror('goods_name', '请输入商品名称');
			return;
		}
		showsuccess('goods_name');
	});
	
	$('.goods-del').on('click', function() {
		if($('.checkitem.active').length == 0) {
            showalert('请至少选择一个商品');
        	return;
        }
		var items = '';
		$('.checkitem.active').each(function(){
			items += $(this).attr('goods_id') + ',';
		});
		items = items.substr(0, (items.length - 1));
		$('#del_ids').val(items);
		Custombox.open({
			target : '#del-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.goods-unshow').on('click', function() {
		if($('.checkitem.active').length == 0) {
            showalert('请至少选择一个商品');
        	return;
        }
		var items = '';
		$('.checkitem.active').each(function(){
			items += $(this).attr('goods_id') + ',';
		});
		items = items.substr(0, (items.length - 1));
		$('#unshow_ids').val(items);
		Custombox.open({
			target : '#unshow-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.goods-show').on('click', function() {
		if($('.checkitem.active').length == 0) {
            showalert('请至少选择一个商品');
        	return;
        }
		var items = '';
		$('.checkitem.active').each(function(){
			items += $(this).attr('goods_id') + ',';
		});
		items = items.substr(0, (items.length - 1));
		$('#show_ids').val(items);
		Custombox.open({
			target : '#show-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});	
	});
	
	$('.edit-price').on('click', function() {
		var goods_id = $(this).attr('goods_id');
		Custombox.open({
			target : 'index.php?act=store_goods&op=price&goods_id='+goods_id,
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});															  			 
	});
	
	$('.edit-storage').on('click', function() {
		var goods_id = $(this).attr('goods_id');
		Custombox.open({
			target : 'index.php?act=store_goods&op=storage&goods_id='+goods_id,
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});															  			 
	});
	
	$('.picture-list').on('click', '.single_close', function() {
		var field_id = $(this).attr('field_id');
		$('#show_image_'+field_id).html('');
		$('#show_image_'+field_id).hide();
		$('.image_'+field_id).val('');
	});
	
	$('.picture-list').on('click', '.multi_close', function() {
		$(this).parent().remove();
	});
});