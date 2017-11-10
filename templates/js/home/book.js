var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var nurse_id = $('#nurse_id').val();
	var book_phone = $('#book_phone').val();
	var work_time = $('#work_time').val();
	var work_duration = $('#work_duration').val();
	var book_message = $('#book_message').val();
	var red_id = $('#red_id').val();
	var i = 0;
	var book_service = {};
	$('.service-box li').each(function() {
		if($(this).hasClass('active')) {
			var service_id = $(this).attr('service_id');
			book_service[i] = service_id;
			i++;
		}
	});
	if(book_phone == '') {
		$('#book_phone').focus();
		showerror('book_phone', '请输入预约人手机号');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(book_phone)) {
		$('#book_phone').focus();
		showerror('book_phone', '预约人手机号格式不正确');
		return;
	}
	if(work_time == '') {
		$('#work_time').focus();
		showerror('work_time', '请输入开始服务时间');
		return;	
	}
	if(work_duration == '') {
		$('#work_duration').focus();
		showerror('work_duration', '请输入服务时长');
		return;	
	}
	var regu = /^\d+$/;
	if(!regu.test(work_duration)) {
		$('#work_duration').focus();
		showerror('work_duration', '服务时长必须为整数');
		return;
	}
	if(book_message == '') {
		$('#book_message').focus();
		showerror('book_message', '请介绍下老人的基本情况');
		return;
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'nurse_id' : nurse_id,
		'book_phone' : book_phone,
		'work_time' : work_time,
		'work_duration' : work_duration,
		'book_service' : book_service,
		'book_message' : book_message,
		'red_id' : red_id,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=book&op=order',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.bookpayment').attr('book_sn', data.book_sn);
				$('.bookwait').attr('book_sn', data.book_sn);
				Custombox.open({
					target : '#book-box',
					effect : 'blur',
					overlayClose : true,
					speed : 500,
					overlaySpeed : 300,
				});
			} else if(data.done == 'login') {
				Custombox.open({
					target : '#login-box',
					effect : 'blur',
					overlayClose : true,
					speed : 500,
					overlaySpeed : 300,
					open: function () {
						setTimeout(function(){
							window.location.href = 'index.php?act=login';
						}, 3000);
					},
				});
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
	$('#work_time').datetimepicker({
		  lang : "ch",
		  format : "Y-m-d H:i",
		  step : 1,
		  timepicker : true,
		  yearStart : 2000,
		  yearEnd : 2050,
		  todayButton : false
	});
	
	$('.service-box').on('click', 'li', function() {
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');
		}
	});
	
	$('.red-box').on('click', '.radio', function() {
		var red_id = $(this).attr('red_id');
		var red_price = $(this).attr('red_price');
		var deposit_amount = $('#deposit_amount').val();
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('#red_id').val('');				
			$('#red_price').val('0');
			var red_amount = 0;
		} else {
			$(this).addClass('active');
			$(this).siblings('.radio').removeClass('active');
			$('#red_id').val(red_id);				
			$('#red_price').val(red_price);
			var red_amount = parseInt(red_price);
		}
		var book_amount = parseInt(deposit_amount)-parseInt(red_amount);
		$('#book_amount').html('￥'+book_amount);
	});
	
	$('.bookwait').on('click', function() {
		Custombox.close();
		var book_sn = $(this).attr('book_sn');
		$.getJSON('index.php?act=book&op=message&book_sn='+book_sn, function(data){
			Custombox.open({
				target : '#wait-box',
				effect : 'blur',
				overlayClose : true,
				speed : 500,
				overlaySpeed : 300,
			});
		});	
	})
	
	$('.bookpayment').on('click', function() {
		var book_sn = $(this).attr('book_sn');
		window.location.href = 'index.php?act=book&op=payment&book_sn='+book_sn;
	});
	
	$('#book_phone').on('blur', function() {
		var book_phone = $('#book_phone').val();
		if(book_phone == '') {
			showerror('book_phone', '请输入预约人手机号');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(book_phone)) {
			showerror('book_phone', '预约人手机号格式不正确');
			return;
		}
		showsuccess('book_phone');
	});
	
	$('#work_time').on('blur', function() {
		var work_time = $('#work_time').val();
		if(work_time == '') {
			showerror('work_time', '请输入开始服务时间');
			return;
		}
		showsuccess('work_time');
	});
	
	$('#work_duration').on('blur', function() {
		var work_duration = $('#work_duration').val();
		if(work_duration == '') {
			showerror('work_duration', '请输入服务时长');
			return;	
		}
		var regu = /^\d+$/;
		if(!regu.test(work_duration)) {
			showerror('work_duration', '服务时长必须为整数');
			return;
		}
		showsuccess('work_duration');
	});
	
	$('#book_message').on('blur', function() {
		var book_message = $('#book_message').val();
		if(book_message != '') {
			showsuccess('book_message');
		}
	});
});