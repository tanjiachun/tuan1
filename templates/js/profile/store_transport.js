var add_submit_btn = false;
function addsubmit() {
	var formhash = $('#formhash').val();
	var transport_name = $('#transport_name').val();
	if(transport_name == '') {
		showalert('请输入运费模板名称');
		return;
	}
	if($('.logistic-name .active').length == 0) {
		showalert('请选择邮寄方式');
		return;	
	}
	var i = 0;
	var extend_type = {};
	var extend_area = {};
	var extend_snum = {};
	var extend_sprice = {};
	var extend_xnum = {};
	var extend_xprice = {};
	$('.nameitem').each(function() {
		var extend_i = $(this).attr('extend_i');
		if($(this).hasClass('active')) {
			extend_type[i] = extend_i;
			i++;
		}
		extend_area[extend_i] = {};
		extend_snum[extend_i] = {};
		extend_sprice[extend_i] = {};
		extend_xnum[extend_i] = {};
		extend_xprice[extend_i] = {};
	});
	var area_flag = 0
	$('.extend_area').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		if(extend_j != 0 && $(this).val() == '') {
			$(this).parent().parent().find('.area-name').css('color', 'red');
			area_flag = 1;
		}
		extend_area[extend_i][extend_j] = $(this).val();
	});
	if(area_flag == 1) {
		showalert('请选择指定地区');
		return;	
	}
	$('.extend_snum').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_snum[extend_i][extend_j] = $(this).val();
	});	
	$('.extend_sprice').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_sprice[extend_i][extend_j] = $(this).val();
	});	
	$('.extend_xnum').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_xnum[extend_i][extend_j] = $(this).val();
	});	
	$('.extend_xprice').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_xprice[extend_i][extend_j] = $(this).val();
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'transport_name' : transport_name,
		'extend_type' : extend_type,
		'extend_area' : extend_area,
		'extend_snum' : extend_snum,
		'extend_sprice' : extend_sprice,
		'extend_xnum' : extend_xnum,
		'extend_xprice' : extend_xprice
	};
	if(add_submit_btn) return;
	add_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_transport&op=add',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			add_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_transport';
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
	var transport_id = $('#transport_id').val();
	var transport_name = $('#transport_name').val();
	if(transport_name == '') {
		showalert('请输入运费模板名称');
		return;
	}
	if($('.logistic-name .active').length == 0) {
		showalert('请选择邮寄方式');
		return;	
	}
	var i = 0;
	var extend_type = {};
	var extend_area = {};
	var extend_snum = {};
	var extend_sprice = {};
	var extend_xnum = {};
	var extend_xprice = {};
	$('.nameitem').each(function() {
		var extend_i = $(this).attr('extend_i');
		if($(this).hasClass('active')) {
			extend_type[i] = extend_i;
			i++;
		}
		extend_area[extend_i] = {};
		extend_snum[extend_i] = {};
		extend_sprice[extend_i] = {};
		extend_xnum[extend_i] = {};
		extend_xprice[extend_i] = {};
	});
	var area_flag = 0
	$('.extend_area').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		if(extend_j != 0 && $(this).val() == '') {
			$(this).parent().parent().find('.area-name').css('color', 'red');
			area_flag = 1;
		}
		extend_area[extend_i][extend_j] = $(this).val();
	});
	if(area_flag == 1) {
		showalert('请选择指定地区');
		return;	
	}
	$('.extend_snum').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_snum[extend_i][extend_j] = $(this).val();
	});	
	$('.extend_sprice').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_sprice[extend_i][extend_j] = $(this).val();
	});	
	$('.extend_xnum').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_xnum[extend_i][extend_j] = $(this).val();
	});	
	$('.extend_xprice').each(function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_xprice[extend_i][extend_j] = $(this).val();
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'transport_id' : transport_id,
		'transport_name' : transport_name,
		'extend_type' : extend_type,
		'extend_area' : extend_area,
		'extend_snum' : extend_snum,
		'extend_sprice' : extend_sprice,
		'extend_xnum' : extend_xnum,
		'extend_xprice' : extend_xprice
	};
	if(edit_submit_btn) return;
	edit_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_transport&op=edit',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			edit_submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_transport';
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
		url : 'index.php?act=store_transport&op=del',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			del_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#transport_'+del_id).remove();
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

var name_submit_btn = false;
function namesubmit() {
	var formhash = $('#formhash').val();
	var extend_type = $('#extend_type').val();
	var extend_name = $('#extend_name').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'extend_type' : extend_type,
		'extend_name' : extend_name,
	};
	if(name_submit_btn) return;
	name_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_transport&op=name',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			name_submit_btn = false;
			if(data.done == 'true') {
				Custombox.close(function() {
					$('#name_'+extend_type).html('<i class="iconfont icon-type"></i>'+data.extend_name);			 
				});
			} else {
				showwarning('name-box', data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			name_submit_btn = false;
			showwarning('name-box', '网路不稳定，请稍候重试');
		}
	});
}

$(function () {
	var objCurlArea;
	
	$('.nameitem').on('click', function() {
		var target_box = $(this).attr('target_box');
		if($(this).hasClass('active')) {
			$('#'+target_box).hide();
			$(this).removeClass('active');
		} else {
			$('#'+target_box).show();
			$(this).addClass('active');
		}					
	});
	
	$('.extend-box').on('click', '.extend-add', function() {
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		extend_j++;
		$(this).attr('extend_j', extend_j);
		var html = '';
		html += '<tr class="area-group">';
		html += '<td class="area-name">未添加地区</td>';
        html += '<td><a class="area-add" extend_i="'+extend_i+'" extend_j="'+extend_j+'" href="javascript:;">编辑区域</a><input type="hidden" name="extend_area['+extend_i+']['+extend_j+']" extend_i="'+extend_i+'" extend_j="'+extend_j+'" class="extend_area"></td>';
        html += '<td><input type="text" name="extend_snum['+extend_i+']['+extend_j+']" extend_i="'+extend_i+'" extend_j="'+extend_j+'" class="form-input w-50 extend_snum" value="1"></td>';
        html += '<td><input type="text" name="extend_sprice['+extend_i+']['+extend_j+']" extend_i="'+extend_i+'" extend_j="'+extend_j+'" class="form-input w-50 extend_sprice" value=""></td>';
        html += '<td><input type="text" name="extend_xnum['+extend_i+']['+extend_j+']" extend_i="'+extend_i+'" extend_j="'+extend_j+'" class="form-input w-50 extend_xnum" value="1"></td>';
        html += '<td><input type="text" name="extend_xprice['+extend_i+']['+extend_j+']" extend_i="'+extend_i+'" extend_j="'+extend_j+'" class="form-input w-50 extend_xprice" value=""></td>';
        html += '<td><a class="extend-del">删除</a></td>';
		html += '</tr>';
		$(this).parent().parent().before(html);
	});
	
	$('.extend-box').on('click', '.extend-del', function() {
		$(this).parent().parent().remove();														
	});
	
	$('.extend-box').on('click', '.area-add', function() {
		objCurlArea = $(this);												  
		var SelectArea = new Array();
		var extend_i = $(this).attr('extend_i');
		var extend_j = $(this).attr('extend_j');
		$('.citylist').find('.check').removeClass('active').removeClass('disabled');
		$('.citylist').find('.check-num').html('');
		var expAreas = $('input[name="extend_area['+extend_i+']['+extend_j+']"]').val();
		expAreas = expAreas.split('|||');
		expAreas = expAreas[0].split(',');
		try {
			if(expAreas[0] != '') {
				for(var v in expAreas){
					SelectArea[expAreas[v]] = true;
				}
			}
			$('.citylist').find('.ecity').each(function() {
				var count = 0;
				$(this).find('.check').each(function() {
					if(SelectArea[$(this).attr('district_id')] == true) {
						$(this).addClass('active');
						if(!$(this).hasClass('J_Province')) {
							count++;
						}
					}
				});
				if(count > 0) {
					$(this).find('.check-num').html('('+count+')');
				}
			});
			$('.citylist>li').each(function() {
				$(this).find('.J_Group').addClass('active');
				father = this;
				$(this).find('.J_Province').each(function() {
					if(!$(this).hasClass('active')){
						$(father).find('.J_Group').removeClass('active');
						return;
					}
				});
			});
		} catch(ex){}
		$(this).parent().parent().siblings('.area-group').each(function() {
			expAreas = $(this).find('.extend_area').val().split('|||');
			expAreas = expAreas[0].split(',');
			SelectArea = new Array();
			try {
				if(expAreas[0] != '') {
					for(var v in expAreas) {
						SelectArea[expAreas[v]] = true;
					}
				}
				$('.citylist').find('.check').each(function() {
					if(SelectArea[$(this).attr('district_id')] == true) {
						$(this).addClass('disabled').removeClass('active');
					}
				});
				$('.citylist>li').each(function() {
					$(this).find('.J_Group').addClass('disabled');
					father = this;
					$(this).find('.J_Province').each(function() {
						if(!$(this).hasClass('disabled')){
							$(father).find('.J_Group').addClass('disabled');
							return;
						}
					});
				});				
			} catch(ex){}
		});
		$('.citys').hide();
		Custombox.open({
			target : '#area-box',
			effect : 'blur',
			overlayClose : false,
			speed : 500,
			overlaySpeed : 300,
		});
	});
	
	$('.zt').on('click', function() {
		if($(this).siblings('.citys').css('display') == 'none') {
			$('.citys').hide();
			$(this).siblings('.citys').show();
		} else {
			$(this).siblings('.citys').hide();	
		}						  
	});
	
	$('.close_button').on('click', function() {
		$(this).parent().parent().hide();					  
	});
	
	$('.J_Group').click(function() {
		if($(this).hasClass('disabled')) return;
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$(this).parent().parent().parent().find('.check').removeClass('active');
			$(this).parent().parent().parent().find('.province-list').find('.ecity').each(function() {
				$(this).find('.check-num').html('');
			});
		} else {
			$(this).addClass('active');
			$(this).parent().parent().parent().find('.check').each(function() {
				if($(this).hasClass('disabled')) {
					$(this).removeClass('active');
				} else {
					$(this).addClass('active');
				}			
			});
			$(this).parent().parent().parent().find('.province-list').find('.ecity').each(function() {
				check_num = '('+$(this).find('.citys').find('.active').size()+')';
				if(check_num == '(0)') {
					check_num = '';
				}
				$(this).find('.check-num').html(check_num);
			});
		}

	});
	
	$('.J_Province').on('click', function() {
		if($(this).hasClass('disabled')) return;
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$(this).parent().find('.check-num').eq(0).html('');
			$(this).parent().find('.citys').eq(0).find('.check').removeClass('active');
			$(this).parent().parent().parent().parent().find('.J_Group').removeClass('active');
		} else {
			$(this).addClass('active');
			$(this).parent().find('.citys').eq(0).find('.check').each(function() {
				if($(this).hasClass('disabled')) {
					$(this).removeClass('active');
				} else {
					$(this).addClass('active');
				}
			});
			var check_num = '('+$(this).parent().find('.citys').eq(0).find('.active').size()+')';
			if(check_num == '(0)') {
				check_num = '';
			}
			$(this).parent().find('.check-num').eq(0).html(check_num);
			var input_checked = $(this).parent().parent().parent().find('.active').size();
			var input_all = $(this).parent().parent().parent().find('.check').size();
			if(input_all == input_checked){
				$(this).parent().parent().parent().parent().find('.J_Group').addClass('active');
			}
		}
	});
	
	$('.J_City').on('click', function() {
		if($(this).hasClass('disabled')) return;
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$(this).parent().parent().parent().find('.J_Province').removeClass('active');
			$(this).parent().parent().parent().parent().parent().parent().find('.J_Group').removeClass('active');
		} else {
			$(this).addClass('active');
			var input_checked = $(this).parent().parent().find('.active').size();
			var input_all = $(this).parent().parent().find('.check').size();
			if(input_all == input_checked){
				$(this).parent().parent().parent().find('.J_Province').addClass('active');
			}
			input_checked = $(this).parent().parent().parent().parent().parent().find('.active').size();
			input_all = $(this).parent().parent().parent().parent().parent().find('.check').size();
			if(input_all == input_checked){
				$(this).parent().parent().parent().parent().parent().parent().find('.J_Group').addClass('active');
			}
		}
		var check_num = '('+$(this).parent().parent().find('.active').size()+')';
		if(check_num == '(0)') {
			check_num = '';
		}
		$(this).parent().parent().parent().find('.check-num').eq(0).html(check_num);
	});
	
	$('.J_Submit').on('click', function() {
		var CityText = '', CityText2 = '', CityValue = '';
		$('.citylist').find('.showcitys').each(function() {
			var a = $(this).find('.check').size();
			var b = $(this).find('.active').size();
			if(a == b) {
				CityText += ($(this).find('.J_Province').attr('district_name'))+',';
			} else {
				$(this).find('.J_City').each(function() {	
					if($(this).hasClass('active')) {
						CityText2 += ($(this).attr('district_name'))+',';
					}
				});
			}
		});
		CityText += CityText2;
		$('.citylist').find('.province-list').find('.check').each(function() {
			if($(this).hasClass('active')) {
				CityValue += $(this).attr('district_id')+',';
			}
		});
		CityText = CityText.replace(/(,*$)/g,'');
		CityValue = CityValue.replace(/(,*$)/g,'');
		if(CityText == '') {
			CityText = '未添加地区';
		} else {
			objCurlArea.parent().parent().find('.area-name').css('color', '');
		}
		objCurlArea.parent().parent().find('.area-name').html(CityText);
		objCurlArea.next().val(CityValue+'|||'+CityText);
		$('.check-num').html('');
		$('.citylist').find('.check').removeClass('active');
		Custombox.close();
	});
	
	$('.edit-name').on('click', function() {
		var extend_type = $(this).attr('extend_type');
		Custombox.open({
			target : 'index.php?act=store_transport&op=name&extend_type='+extend_type,
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});															  			 
	});
	
	$('.transport-list').on('click', '.transport-del', function() {
		var transport_id = $(this).attr('transport_id');
		$('#del_id').val(transport_id);
		Custombox.open({
			target : '#del-box',
			effect : 'blur',
			overlayClose : true,
			speed : 500,
			overlaySpeed : 300,
		});														  			 
	});
});