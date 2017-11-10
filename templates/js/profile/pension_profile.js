var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var pension_name = $('#pension_name').val();
	var pension_logo = $('#pension_logo').val();
	var pension_image = $('#pension_image').val();
	var pension_type = $('#pension_type').val();
	var pension_nature = $('#pension_nature').val();
	var pension_person = $('#pension_person').val();
	var pension_bed = $('#pension_bed').val();
	var pension_cover = $('#pension_cover').val();
	var pension_price = $('#pension_price').val();
	var pension_phone = $('#pension_phone').val();
	var pension_provinceid = $('#pension_provinceid').val();
	var pension_cityid = $('#pension_cityid').val();
	var pension_areaid = $('#pension_areaid').val();
	var pension_address = $('#pension_address').val();
	var pension_summary = $('#pension_summary').val();
	var i = 0;
	var pension_image_more = {};
	$('.image_1').each(function() {
		pension_image_more[i] = $(this).val();
		i++;
	});
	if(pension_name == '') {
		$('#pension_name').focus();
		showerror('pension_name', '请输入你的机构名称');
		return;	
	}
	if(pension_image == '') {
		showerror('pension_image', '请上传机构封面');
		return;		
	}
	if(pension_type == '') {
		showerror('pension_type', '请选择机构类型');
		return;		
	}
	if(pension_nature == '') {
		showerror('pension_nature', '请选择机构性质');
		return;		
	}
	if(pension_person == '') {
		showerror('pension_person', '请选择收住对象');
		return;		
	}
	if(pension_bed == '') {
		$('#pension_bed').focus();
		showerror('pension_bed', '请输入床位数量');
		return;	
	}
	if(pension_cover == '') {
		$('#pension_cover').focus();
		showerror('pension_cover', '请输入占地面积');
		return;	
	}
	if(pension_price == '') {
		$('#pension_price').focus();
		showerror('pension_price', '请输入收费标准');
		return;	
	}
	if(pension_phone == '') {
		$('#pension_phone').focus();
		showerror('pension_phone', '请输入联系电话');
		return;	
	}
	if(pension_cityid == '') {
		showerror('pension_provinceid', '请选择所在地区');
		return;
	}
	if(pension_address == '') {
		$('#pension_address').focus();
		showerror('pension_address', '请输入详细地址');
		return;
	}
	if(pension_summary == '') {
		$('#pension_summary').focus();
		showerror('pension_summary', '请输入机构概述');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'pension_name' : pension_name,
		'pension_logo' : pension_logo,
		'pension_image' : pension_image,
		'pension_image_more' : pension_image_more,
		'pension_type' : pension_type,
		'pension_nature' : pension_nature,
		'pension_person' : pension_person,
		'pension_bed' : pension_bed,
		'pension_cover' : pension_cover,
		'pension_price' : pension_price,
		'pension_phone' : pension_phone,
		'pension_provinceid' : pension_provinceid,
		'pension_cityid' : pension_cityid,
		'pension_areaid' : pension_areaid,
		'pension_address' : pension_address,
		'pension_summary' : pension_summary,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=pension_profile',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('修改成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=pension_profile';
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
	$('#pension_name').on('blur', function() {
		var pension_name = $('#pension_name').val();
		if(pension_name == '') {
			showerror('pension_name', '请输入你的机构名称');
			return;
		}
		showsuccess('pension_name');
	});
	
	$('#pension_bed').on('blur', function() {
		var pension_bed = $('#pension_bed').val();
		if(pension_bed == '') {
			showerror('pension_bed', '请输入床位数量');
			return;
		}
		showsuccess('pension_bed');
	});
	
	$('#pension_cover').on('blur', function() {
		var pension_cover = $('#pension_cover').val();
		if(pension_cover == '') {
			showerror('pension_cover', '请输入占地面积');
			return;
		}
		showsuccess('pension_cover');
	});
	
	$('#pension_price').on('blur', function() {
		var pension_price = $('#pension_price').val();
		if(pension_price == '') {
			showerror('pension_price', '请输入收费标准');
			return;
		}
		showsuccess('pension_price');
	});
	
	$('#pension_phone').on('blur', function() {
		var pension_phone = $('#pension_phone').val();
		if(pension_phone == '') {
			showerror('pension_phone', '请输入联系电话');
			return;
		}
		showsuccess('pension_phone');
	});
	
	$('#pension_address').on('blur', function() {
		var pension_address = $('#pension_address').val();
		if(pension_address == '') {
			showerror('pension_address', '请输入详细地址');
			return;
		}
		showsuccess('pension_address');
	});
	
	$('#pension_summary').on('blur', function() {
		var pension_summary = $('#pension_summary').val();
		if(pension_summary != '') {
			showsuccess('pension_summary');
		}
	});
});