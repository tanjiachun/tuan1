var add_submit_btn = false;
function addsubmit() {
	var formhash = $('#formhash').val();
	var coupon_t_title = $('#coupon_t_title').val();
	var coupon_t_total = $('#coupon_t_total').val();
	var coupon_t_price_type = $('#coupon_t_price_type').val();
	var coupon_t_price = $('#coupon_t_price').val();
	var coupon_t_period_type = $('#coupon_t_period_type').val();
	var coupon_t_days = $('#coupon_t_days').val();
	var coupon_t_starttime = $('#coupon_t_starttime').val();
	var coupon_t_endtime = $('#coupon_t_endtime').val();
	var coupon_t_limit = $('#coupon_t_limit').val();
	var coupon_t_desc = $('#coupon_t_desc').val();
	var coupon_t_goods_type = $('#coupon_t_goods_type').val();
	var coupon_rule_starttime = $('#coupon_rule_starttime').val();
	var coupon_rule_endtime = $('#coupon_rule_endtime').val();
	var coupon_rule_type = $('#coupon_rule_type').val();
	var coupon_rule_amount = $('#coupon_rule_amount').val();
	var coupon_rule_eachlimit = $('#coupon_rule_eachlimit').val();
	var i = 0;
	var coupon_t_goods_id = {};
	$('[name="goods_ids[]"]').each(function() {
		coupon_t_goods_id[i] = $(this).val();
		i++;			   
	});
	if(coupon_t_title == '') {
		$('#coupon_t_title').focus();
		showerror('coupon_t_title', '请输入优惠券名称');
		return;	
	}
	if(coupon_t_total == '') {
		$('#coupon_t_total').focus();
		showerror('coupon_t_total', '请输入发放总量');
		return;	
	}
	if(coupon_t_price == '') {
		$('#coupon_t_price').focus();
		showerror('coupon_t_price', '请输入面值');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'coupon_t_title' : coupon_t_title,
		'coupon_t_total' : coupon_t_total,
		'coupon_t_price_type' : coupon_t_price_type,
		'coupon_t_price' : coupon_t_price,
		'coupon_t_period_type' : coupon_t_period_type,
		'coupon_t_days' : coupon_t_days,
		'coupon_t_starttime' : coupon_t_starttime,
		'coupon_t_endtime' : coupon_t_endtime,
		'coupon_t_limit' : coupon_t_limit,
		'coupon_t_desc' : coupon_t_desc,
		'coupon_t_goods_type' : coupon_t_goods_type,
		'coupon_rule_starttime' : coupon_rule_starttime,
		'coupon_rule_endtime' : coupon_rule_endtime,
		'coupon_rule_type' : coupon_rule_type,
		'coupon_rule_amount' : coupon_rule_amount,
		'coupon_rule_eachlimit' : coupon_rule_eachlimit,
		'coupon_t_goods_id' : coupon_t_goods_id,
	};
	if(add_submit_btn) return;
	add_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_coupon&op=add',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			add_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_coupon';
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
	var coupon_t_id = $('#coupon_t_id').val();
	var coupon_t_title = $('#coupon_t_title').val();
	var coupon_t_total = $('#coupon_t_total').val();
	var coupon_t_price_type = $('#coupon_t_price_type').val();
	var coupon_t_price = $('#coupon_t_price').val();
	var coupon_t_period_type = $('#coupon_t_period_type').val();
	var coupon_t_days = $('#coupon_t_days').val();
	var coupon_t_starttime = $('#coupon_t_starttime').val();
	var coupon_t_endtime = $('#coupon_t_endtime').val();
	var coupon_t_limit = $('#coupon_t_limit').val();
	var coupon_t_desc = $('#coupon_t_desc').val();
	var coupon_t_goods_type = $('#coupon_t_goods_type').val();
	var coupon_rule_starttime = $('#coupon_rule_starttime').val();
	var coupon_rule_endtime = $('#coupon_rule_endtime').val();
	var coupon_rule_type = $('#coupon_rule_type').val();
	var coupon_rule_amount = $('#coupon_rule_amount').val();
	var coupon_rule_eachlimit = $('#coupon_rule_eachlimit').val();
	var i = 0;
	var coupon_t_goods_id = {};
	$('[name="goods_ids[]"]').each(function() {
		coupon_t_goods_id[i] = $(this).val();
		i++;				   
	});
	if(coupon_t_title == '') {
		$('#coupon_t_title').focus();
		showerror('coupon_t_title', '请输入优惠券名称');
		return;	
	}
	if(coupon_t_total == '') {
		$('#coupon_t_total').focus();
		showerror('coupon_t_total', '请输入发放总量');
		return;	
	}
	if(coupon_t_price == '') {
		$('#coupon_t_price').focus();
		showerror('coupon_t_price', '请输入面值');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'coupon_t_id' : coupon_t_id,
		'coupon_t_title' : coupon_t_title,
		'coupon_t_total' : coupon_t_total,
		'coupon_t_price_type' : coupon_t_price_type,
		'coupon_t_price' : coupon_t_price,
		'coupon_t_period_type' : coupon_t_period_type,
		'coupon_t_days' : coupon_t_days,
		'coupon_t_starttime' : coupon_t_starttime,
		'coupon_t_endtime' : coupon_t_endtime,
		'coupon_t_limit' : coupon_t_limit,
		'coupon_t_desc' : coupon_t_desc,
		'coupon_t_goods_type' : coupon_t_goods_type,
		'coupon_rule_starttime' : coupon_rule_starttime,
		'coupon_rule_endtime' : coupon_rule_endtime,
		'coupon_rule_type' : coupon_rule_type,
		'coupon_rule_amount' : coupon_rule_amount,
		'coupon_rule_eachlimit' : coupon_rule_eachlimit,
		'coupon_t_goods_id' : coupon_t_goods_id,
	};
	if(edit_submit_btn) return;
	edit_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_coupon&op=edit',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			edit_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_coupon';
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
		url : 'index.php?act=store_coupon&op=del',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			del_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#coupon_'+del_id).remove();
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

function cancelgoods(goods_id, goods_image, goods_name) {
	$('#goods_'+goods_id).remove();
	$('#goods_op_'+goods_id).html('<span class="btn btn-primary goods-select" onclick="selectgoods(\''+goods_id+'\', \''+goods_image+'\', \''+goods_name+'\')">选取</span>');
}

function selectgoods(goods_id, goods_image, goods_name) {
	if($('#goods_'+goods_id).length == 0) {
		var html = '';
		html += '<tr id="goods_'+goods_id+'">';
		html += '<td>';
		html += '<input type="hidden" name="goods_ids[]" class="goods-item" value="'+goods_id+'">';
		html += '<div class="td-inner clearfix">';
		html += '<div class="item-pic"><a href="javascript:;"><img src="'+goods_image+'"></a></div>';
		html += '<div class="item-info"><a href="javascript:;">'+goods_name+'</a></div>';
		html += '</div>';
		html += '</td>';
		html += '<td><a class="bluelink" href="javascript:;" onclick="cancelgoods(\''+goods_id+'\', \''+goods_image+'\', \''+goods_name+'\')">删除</a></td>';
		html += '</tr>';
		$('.select-box').append(html);
	}
	$('#goods_op_'+goods_id).html('<span class="btn btn-default goods-cancel" onclick="cancelgoods(\''+goods_id+'\', \''+goods_image+'\', \''+goods_name+'\')">取消</span>');
}

$(function() {
	$('.price-box').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		if(field_value == 'discount') {
			$('#price_name').html('折');
		} else {
			$('#price_name').html('元');
		}
	});
	
	$('#coupon_t_starttime').datetimepicker({
		  lang : "ch",
		  format : "Y-m-d",
		  step : 1,
		  timepicker : false,
		  yearStart : 2000,
		  yearEnd : 2050,
		  todayButton : false
	});
	
	$('#coupon_t_endtime').datetimepicker({
		  lang : "ch",
		  format : "Y-m-d",
		  step : 1,
		  timepicker : false,
		  yearStart : 2000,
		  yearEnd : 2050,
		  todayButton : false
	});
	
	$('.goods-box').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		if(field_value == 'goods') {
			$('.goods-list').show();
		} else {
			$('.goods-list').hide();
		}
	});
	
	$('.goods-box').on('click', '.goods-add', function() {
		var goods_ids = '';
		$('.goods-item').each(function() {
			goods_ids += $(this).val() + ',';						   
		});
		goods_ids = goods_ids.substr(0, (goods_ids.length-1));
		Custombox.open({
			target: 'index.php?act=store_goods&op=goods&goods_ids='+goods_ids,
			effect: 'blur',
			overlayClose: true,
			speed:500,
			overlaySpeed: 300,
		});															  			 
	});
	
	$('#coupon_rule_starttime').datetimepicker({
		  lang : "ch",
		  format : "Y-m-d",
		  step : 1,
		  timepicker : false,
		  yearStart : 2000,
		  yearEnd : 2050,
		  todayButton : false
	});
	
	$('#coupon_rule_endtime').datetimepicker({
		  lang : "ch",
		  format : "Y-m-d",
		  step : 1,
		  timepicker : false,
		  yearStart : 2000,
		  yearEnd : 2050,
		  todayButton : false
	});
	
	$('.rule-box').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		if(field_value == 'buy') {
			$('#buy').show();
			$('#free').hide();
		} else if(field_value == 'free') {			
			$('#free').show();
			$('#buy').hide();
		}
	});
	
	$('#coupon_t_title').on('blur', function() {
		var coupon_t_title = $('#coupon_t_title').val();
		if(coupon_t_title == '') {
			showerror('coupon_t_title', '请输入优惠券名称');
			return;
		}
		showsuccess('coupon_t_title');
	});
	
	$('#coupon_t_total').on('blur', function() {
		var coupon_t_total = $('#coupon_t_total').val();
		if(coupon_t_total == '') {
			showerror('coupon_t_total', '请输入发放总量');
			return;
		}
		showsuccess('coupon_t_total');
	});
	
	$('#coupon_t_price').on('blur', function() {
		var coupon_t_price = $('#coupon_t_price').val();
		if(coupon_t_price == '') {
			showerror('coupon_t_price', '请输入面值');
			return;
		}
		showsuccess('coupon_t_price');
	});
		   
	$('#coupon-list').on('click', '.coupon-del', function() {
		var coupon_t_id = $(this).attr('coupon_t_id');
		$('#del_id').val(coupon_t_id);
		Custombox.open({
			target : '#del-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});														  			 
	});
});