var register_submit_btn = false;
function checkregister() {
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
	var nurse_longtitude=longtitude;
	var nurse_latitude=latitude;
	var nurse_education = $('#nurse_education').val();
	var nurse_price = $('#nurse_price').val();
	var nurse_image = $('#nurse_image').val();
	var nurse_cardid = $('#nurse_cardid').val();
	var nurse_cardid_image = $('#nurse_cardid_image').val();
	var nurse_content = $('#nurse_content').val();
	var from_phone = $('#from_phone').val();
	var i = 0;
	var nurse_qa_image = {};
	$('.image_2').each(function() {
		nurse_qa_image[i] = $(this).val();
		i++;
	});
	if(nurse_name == '') {
		$('#nurse_name').focus();
		showerror('nurse_name', '您的姓名必须填写');
		return;	
	}
	if(member_phone == '') {
		$('#member_phone').focus();
		showerror('member_phone', '您的手机号必须填写');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(member_phone)) {
		$('#member_phone').focus();
		showerror('member_phone', '您的手机号格式不正确');
		return;
	}
    if(nurse_type==undefined||nurse_type==''){
        $('#member_phone').focus();
        $('#nurse_name').focus();
        showerror('nurse_type', '看护类别必须选择');
        return;
    }else{
		$("#nurse_type").siblings(".Validform-checktip").hide();
	}
    if(service_type==undefined||nurse_type==''){
        $('#member_phone').focus();
        $('#nurse_name').focus();
        showerror('service_type', '可提供服务必选选择');
        return;
    }else{
        $("#service_type").siblings(".Validform-checktip").hide();
    }
	if(nurse_age == '') {
		$('#nurse_age').focus();
		showerror('nurse_age', '您的年龄必须填写');
		return;	
	}
	// if(birth_cityid == '') {
		// $('#nurse_age').focus();
		// showerror('birth_provinceid', '出生地址必须填写');
		// return;
	// }
	if(nurse_cityid == '') {
		$('#nurse_age').focus();
		showerror('nurse_provinceid', '现居地址必须填写');
		return;
	}
	// if(nurse_address == '') {
		// $('#nurse_address').focus();
		// showerror('nurse_address', '详细地址必须填写');
		// return;		
	// }
	// if(nurse_education == '') {
		// $('#nurse_education').focus();
		// showerror('nurse_education', '工作年限必须填写');
		// return;	
	// }
	if(nurse_price == '') {
		$('#nurse_price').focus();
		showerror('nurse_price', '期望薪资必须填写');
		return;
	}
	// if(nurse_image == '') {
		// $('#nurse_price').focus();
		// showerror('nurse_image', '个人照片必须上传');
		// return;		
	// }
	// if(nurse_cardid == '') {
		// $('#nurse_cardid').focus();
		// showerror('nurse_cardid', '身份证号码必须填写');
		// return;
	// }
	// if(nurse_cardid_image == '') {
		// showerror('nurse_cardid_image', '手持身份证照必须上传');
		// return;		
	// }
	 if(nurse_content == '') {
		 $('#nurse_content').focus();
		 showerror('nurse_content', '服务项目必须填写');
		 return;
	 }
	 // if(from_phone != '') {
		 // var regu = /^[1][0-9]{10}$/;
		 // if(!regu.test(from_phone)) {
			 // $('#from_phone').focus();
			 // showerror('from_phone', '推荐人手机号格式不正确');
			 // return;
		 // }
	 // }
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
		'nurse_longtitude':nurse_longtitude,
		'nurse_latitude':nurse_latitude,
		'nurse_education' : nurse_education,
		'nurse_price' : nurse_price,
		'nurse_image' : nurse_image,
		'nurse_cardid' : nurse_cardid,
		'nurse_cardid_image' : nurse_cardid_image,
		'nurse_qa_image' : nurse_qa_image,
		'nurse_content' : nurse_content,
		'from_phone' : from_phone,
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
			// showalert('网路不稳定，请稍候重试');
		}
	});
}

$(function() {
    $("#nurse_address").focus(function () {
        $("#allmap").show();
    })
    $("#nurse_address").blur(function () {
        $("#allmap").hide();
    })
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
			showerror('nurse_name', '您的姓名必须填写');
			return;
		}
		showsuccess('nurse_name');
	});
			   
	$('#member_phone').on('blur', function() {
		var member_phone = $('#member_phone').val();
		if(member_phone == '') {
			showerror('member_phone', '您的手机号必须填写');
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
			showerror('nurse_age', '您的年龄必须填写');
			return;
		}
		showsuccess('nurse_age');
	});
	
	// $('#nurse_address').on('blur', function() {
		// var nurse_address = $('#nurse_address').val();
		// if(nurse_address == '') {
			// showerror('nurse_address', '详细地址必须填写');
			// return;
		// }
		// showsuccess('nurse_address');
	// });
	
	// $('#nurse_education').on('blur', function() {
		// var nurse_education = $('#nurse_education').val();
		// if(nurse_education == '') {
			// showerror('nurse_education', '工作年限必须填写');
			// return;
		// }
		// showsuccess('nurse_education');
	// });
	
	$('#nurse_price').on('blur', function() {
		var nurse_price = $('#nurse_price').val();
		if(nurse_price == '') {
			showerror('nurse_price', '期望薪资必须填写');
			return;
		}
		showsuccess('nurse_price');
	});
	
	// $('#nurse_cardid').on('blur', function() {
		// var nurse_cardid = $('#nurse_cardid').val();
		// if(nurse_cardid == '') {
			// showerror('nurse_cardid', '身份证号码必须填写');
			// return;
		// }
		// $.getJSON('index.php?act=misc&op=checkcard', {'card_id':nurse_cardid}, function(data){
			// if(data.done == 'true') {
				// showsuccess('nurse_cardid');
			// } else {
				// showerror('nurse_cardid', data.msg);
			// }
		// });
	// });
	
	 $('#nurse_content').on('blur', function() {
		 var nurse_content = $('#nurse_content').val();
		 if(nurse_content != '') {
			 showsuccess('nurse_content');
		 }
	});
	
	// $('#from_phone').on('blur', function() {
		// var from_phone = $('#from_phone').val();
		// if(from_phone == '') {
			// showsuccess('from_phone');
			// return;
		// }
		// var regu = /^[1][0-9]{10}$/;
		// if(!regu.test(from_phone)) {
			// showerror('from_phone', '推荐人手机号格式不正确');
			// return;
		// }
		// showsuccess('from_phone');
	// });
});