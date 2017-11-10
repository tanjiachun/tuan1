var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var nurse_name = $('#nurse_name').val();
	var member_phone = $('#member_phone').val();
    var nurse_type,service_type;
    $(".selectBox span").each(function(){
        if($(this).hasClass("on")){
            nurse_type=$(this).attr('filed_value');
        }
    });
    $(".keywordBox span").each(function(){
        if($(this).hasClass("on")){
            service_type=$(this).text();
        }
    });
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
	var nurse_image = $('#nurse_image').val();
	var nurse_cardid = $('#nurse_cardid').val();
	var nurse_cardid_image = $('#nurse_cardid_image').val();
	var nurse_content = $('#nurse_content').val();
	var i = 0;
	var nurse_qa_image = {};
	$('.image_2').each(function() {
		nurse_qa_image[i] = $(this).val();
		i++;
	});
	if(nurse_name == '') {
		$('#nurse_name').focus();
		showerror('nurse_name', '请输入您的姓名');
		return;	
	}
	if(member_phone == '') {
		$('#member_phone').focus();
		showerror('member_phone', '请输入您的手机号');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(member_phone)) {
		$('#member_phone').focus();
		showerror('member_phone', '您的手机号格式不正确');
		return;
	}
	if(nurse_type == undefined) {
		$('#member_phone').focus();
		showerror('nurse_type', '请选择看护类别');
		return;		
	}else{
        $("#nurse_type").siblings(".Validform-checktip").hide();
	}
    if(service_type == undefined) {
        $('#member_phone').focus();
        showerror('service_type', '请选择可提供服务');
        return;
    }else{
        $("#service_type").siblings(".Validform-checktip").hide();
    }
	if(nurse_age == '') {
		$('#nurse_age').focus();
		showerror('nurse_age', '请输入您的年龄');
		return;	
	}
	if(birth_cityid == '') {
		$('#nurse_age').focus();
		showerror('birth_provinceid', '请选择出生地址');
		return;
	}
	if(nurse_cityid == '') {
		$('#nurse_age').focus();
		showerror('nurse_provinceid', '请选择现居地址');
		return;
	}
	if(nurse_address == '') {
		$('#nurse_address').focus();
		showerror('nurse_address', '请输入详细地址');
		return;
	}
	if(nurse_education == '') {
		$('#nurse_education').focus();
		showerror('nurse_education', '请输入工作年限');
		return;	
	}
	if(nurse_price == '') {
		$('#nurse_price').focus();
		showerror('nurse_price', '请输入期望薪资');
		return;
	}
	if(nurse_image == '') {
		$('#nurse_price').focus();
		showerror('nurse_image', '请上传个人照片');
		return;		
	}
	if(nurse_cardid == '') {
		$('#nurse_cardid').focus();
		showerror('nurse_cardid', '请输入身份证号码');
		return;
	}
	if(nurse_cardid_image == '') {
		showerror('nurse_cardid_image', '请上传手持身份证照');
		return;		
	}
	if(nurse_content == '') {
		$('#nurse_content').focus();
		showerror('nurse_content', '请输入服务项目');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'nurse_name' : nurse_name,
		'member_phone' : member_phone,
		'nurse_type' : nurse_type,
		'service_type':service_type,
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
		'nurse_image' : nurse_image,
		'nurse_cardid' : nurse_cardid,
		'nurse_cardid_image' : nurse_cardid_image,
		'nurse_qa_image' : nurse_qa_image,
		'nurse_content' : nurse_content,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=nurse_resume',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('提交成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=nurse_resume';
				}, 1000);
			} else if(data.done == 'nurse') {
				window.location.href = 'index.php?act=register&next_step=nurse';
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
			submit_btn = false;
			showalert('网路不稳定，请稍候重试');
		}
	});
}

$(function() {	
	$('.type-box').on('click', '.select-choice', function() {
		$(this).siblings('.select-list').toggle();
	});
	
	$('.type-box').on('click', 'li', function() {
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');
		showsuccess('nurse_type');
		if(field_value == 4) {
			$('#price_field').html('期望时薪');
		} else {
			$('#price_field').html('期望月薪');
		}
	});
    $('.selectBox').on('click','span',function () {
        var data=$(this).attr('data');
        if(data== 1) {
            $('#price_field').html('期望时薪');
        } else {
            $('#price_field').html('期望月薪');
        }
    })
	$('#nurse_name').on('blur', function() {
		var nurse_name = $('#nurse_name').val();
		if(nurse_name == '') {
			showerror('nurse_name', '请输入您的姓名');
			return;
		}
		showsuccess('nurse_name');
	});
	
	$('#member_phone').on('blur', function() {
		var member_phone = $('#member_phone').val();
		if(member_phone == '') {
			showerror('member_phone', '请输入您的手机号');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(member_phone)) {
			showerror('member_phone', '您的手机号格式不正确');
			return;
		}
		showsuccess('member_phone');
	});
	
	$('#nurse_age').on('blur', function() {
		var nurse_age = $('#nurse_age').val();
		if(nurse_age == '') {
			showerror('nurse_age', '请输入您的年龄');
			return;
		}
		showsuccess('nurse_age');
	});
	
	$('#nurse_address').on('blur', function() {
		var nurse_address = $('#nurse_address').val();
		if(nurse_address == '') {
			showerror('nurse_address', '请输入详细地址');
			return;
		}
		showsuccess('nurse_address');
	});
	
	$('#nurse_education').on('blur', function() {
		var nurse_education = $('#nurse_education').val();
		if(nurse_education == '') {
			showerror('nurse_education', '请输入工作年限');
			return;
		}
		showsuccess('nurse_education');
	});
	
	$('#nurse_price').on('blur', function() {
		var nurse_price = $('#nurse_price').val();
		if(nurse_price == '') {
			showerror('nurse_price', '请输入期望薪资');
			return;
		}
		showsuccess('nurse_price');
	});
	
	$('#nurse_cardid').on('blur', function() {
		var nurse_cardid = $('#nurse_cardid').val();
		if(nurse_cardid == '') {
			showerror('nurse_cardid', '请输入身份证号码');
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