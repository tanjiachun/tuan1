var register_submit_btn = false;
function checkregister() {
	var formhash = $('#formhash').val();
	var member_phone = $('#member_phone').val();
	var from_phone = $('#from_phone').val();
	var nurse_name = $('#nurse_name').val();
	var nurse_type = $('#nurse_type').val();
	var nurse_age = $('#nurse_age').val();
	var birth_provinceid = $('#birth_provinceid').val();
	var birth_cityid = $('#birth_cityid').val();
	var birth_areaid = $('#birth_areaid').val();
	var nurse_provinceid = $('#nurse_provinceid').val();
	var nurse_cityid = $('#nurse_cityid').val();
	var nurse_areaid = $('#nurse_areaid').val();
	var nurse_address = $('#nurse_address').val();
	var nurse_education = $('#nurse_education').val();
	var nurse_price = $('#nurse_price').val();
	var nurse_days = $('#nurse_days').val();
	var nurse_image = $('#nurse_image').val();
	var nurse_cardid = $('#nurse_cardid').val();
	var nurse_cardid_image = $('#nurse_cardid_image').val();
	var nurse_demand = $('#nurse_demand').val();
	var nurse_content = $('#nurse_content').val();
	var nurse_desc = $('#nurse_desc').val();
	var i = 0;
	var nurse_qa = {};
	$('.nurse_qa').each(function() {
		if($(this).hasClass('active')) {
			nurse_qa[i] = $(this).attr('nurse_qa');
			i++;
		}
	});
	var i = 0;
	var nurse_qa_image = {};
	$('.image_2').each(function() {
		nurse_qa_image[i] = $(this).val();
		i++;
	});
	if(from_phone != '') {
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(from_phone)) {
			$('#from_phone').focus();
			showerror('from_phone', '推荐人手机号格式不正确');
			return;
		}
		if(from_phone == member_phone) {
			$('#from_phone').focus();
			showerror('from_phone', '推荐人手机号不能是自己的');
			return;
		}
	}
	if(nurse_name == '') {
		$('#nurse_name').focus();
		showerror('nurse_name', '您的姓名必须填写');
		return;	
	}
	if(nurse_type == '') {
		$('#nurse_name').focus();
		showerror('nurse_type', '看护类别必须填写');
		return;		
	}
	if(nurse_age == '') {
		$('#nurse_age').focus();
		showerror('nurse_age', '您的年龄必须填写');
		return;	
	}
	if(birth_cityid == '') {
		$('#nurse_age').focus();
		showerror('birth_provinceid', '出生地址必须填写');
		return;
	}
	if(nurse_cityid == '') {
		$('#nurse_age').focus();
		showerror('nurse_provinceid', '现居地址必须填写');
		return;
	}
	if(nurse_address == '') {
		$('#nurse_address').focus();
		showerror('nurse_address', '详细地址必须填写');
		return;		
	}
	if(nurse_education == '') {
		$('#nurse_education').focus();
		showerror('nurse_education', '工作年限必须填写');
		return;	
	}
	if(nurse_price == '') {
		$('#nurse_price').focus();
		showerror('nurse_price', '月薪必须填写');
		return;
	}
	if(nurse_days == '') {
		$('#nurse_days').focus();
		showerror('nurse_days', '每月工作天数必须填写');
		return;
	}
	if(nurse_image == '') {
		$('#nurse_days').focus();
		showerror('nurse_image', '个人照片必须上传');
		return;		
	}
	if(nurse_cardid == '') {
		$('#nurse_cardid').focus();
		showerror('nurse_cardid', '身份证号码必须填写');
		return;
	}
	if(nurse_cardid_image == '') {
		$('#nurse_cardid').focus();
		showerror('nurse_cardid_image', '手持身份证照必须上传');
		return;		
	}
	if(nurse_content == '') {
		$('#nurse_content').focus();
		showerror('nurse_content', '服务项目必须填写');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'from_phone' : from_phone,
		'nurse_name' : nurse_name,
		'nurse_type' : nurse_type,
		'nurse_age' : nurse_age,
		'birth_provinceid' : birth_provinceid,
		'birth_cityid' : birth_cityid,
		'birth_areaid' : birth_areaid,
		'nurse_provinceid' : nurse_provinceid,
		'nurse_cityid' : nurse_cityid,
		'nurse_areaid' : nurse_areaid,
		'nurse_address' : nurse_address,
		'nurse_education' : nurse_education,
		'nurse_price' : nurse_price,
		'nurse_days' : nurse_days,
		'nurse_image' : nurse_image,
		'nurse_cardid' : nurse_cardid,
		'nurse_cardid_image' : nurse_cardid_image,
		'nurse_qa' : nurse_qa,
		'nurse_qa_image' : nurse_qa_image,
		'nurse_demand' : nurse_demand,
		'nurse_content' : nurse_content,
		'nurse_desc' : nurse_desc,
	};
	if(register_submit_btn) return;
	register_submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=nurse&op=register',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			register_submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=nurse&op=step2';
			} else if(data.done == 'register') {
				window.location.href = 'index.php?act=register&next_step=nurse';
			} else if(data.done == 'nurse') {
				window.location.href = 'index.php?act=nurse_center';
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
			register_submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

$(function() {
	$('.nurse_qa').on('click', function() {
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');	
		}
	});
		   
	$('#from_phone').on('blur', function() {
		var member_phone = $('#member_phone').val();
		var from_phone = $('#from_phone').val();
		if(from_phone == '') {
			showsuccess('from_phone');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(from_phone)) {
			showerror('from_phone', '推荐人手机号格式不正确');
			return;
		}
		if(from_phone == member_phone) {
			showerror('from_phone', '推荐人手机号不能是自己的');
			return;
		}
		$.getJSON('index.php?act=nurse&op=checkname', {'member_phone':from_phone}, function(data){
			if(data.done == 'true') {
				showsuccess('from_phone');
			} else {
				showerror('from_phone', data.msg);
			}
		});
	});
	
	$('#nurse_name').on('blur', function() {
		var nurse_name = $('#nurse_name').val();
		if(nurse_name == '') {
			showerror('nurse_name', '您的姓名必须填写');
			return;
		}
		showsuccess('nurse_name');
	});
	
	$('#nurse_age').on('blur', function() {
		var nurse_age = $('#nurse_age').val();
		if(nurse_age == '') {
			showerror('nurse_age', '您的年龄必须填写');
			return;
		}
		showsuccess('nurse_age');
	});
	
	$('#nurse_address').on('blur', function() {
		var nurse_address = $('#nurse_address').val();
		if(nurse_address == '') {
			showerror('nurse_address', '详细地址必须填写');
			return;
		}
		showsuccess('nurse_address');
	});
	
	$('#nurse_education').on('blur', function() {
		var nurse_education = $('#nurse_education').val();
		if(nurse_education == '') {
			showerror('nurse_education', '工作年限必须填写');
			return;
		}
		showsuccess('nurse_education');
	});
	
	$('#nurse_price').on('blur', function() {
		var nurse_price = $('#nurse_price').val();
		if(nurse_price == '') {
			showerror('nurse_price', '月薪必须填写');
			return;
		}
		showsuccess('nurse_price');
	});
	
	$('#nurse_days').on('blur', function() {
		var nurse_days = $('#nurse_days').val();
		if(nurse_days == '') {
			showerror('nurse_days', '每月工作天数必须填写');
			return;
		}
		showsuccess('nurse_days');
	});
	
	$('#nurse_cardid').on('blur', function() {
		var nurse_cardid = $('#nurse_cardid').val();
		if(nurse_cardid == '') {
			showerror('nurse_cardid', '身份证号码必须填写');
			return;
		}
		$.getJSON('index.php?act=misc&op=checkcard', {'card_id':nurse_cardid}, function(data){
			if(data.done == 'true') {
				showsuccess('nurse_cardid');
			} else {
				showerror('nurse_cardid', data.msg);
			}
		});
	});
	
	$('#nurse_content').on('blur', function() {
		var nurse_content = $('#nurse_content').val();
		if(nurse_content != '') {
			showsuccess('nurse_content');
		}
	});
});