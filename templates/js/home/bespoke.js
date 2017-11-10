var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var room_id = $('#room_id').val();
	var bespoke_name = $('#bespoke_name').val();
	var bespoke_phone = $('#bespoke_phone').val();
	var live_time = $('#live_time').val();
	var live_duration = $('#live_duration').val();
	var bed_number = $('#bed_number').val();
	var red_id = $('#red_id').val();
	var bespoke_invoice = $('#bespoke_invoice').val();
	var invoice_title = $('#invoice_title').val();
	var invoice_content = $('#invoice_content').val();
	var invoice_membername = $('#invoice_membername').val();
	var invoice_provinceid = $('#invoice_provinceid').val();
	var invoice_cityid = $('#invoice_cityid').val();
	var invoice_areaid = $('#invoice_areaid').val();
	var invoice_address = $('#invoice_address').val();
	var bespoke_contact = 0;
	var i = 0;
	var contact_name = {};
	$('.contact_name').each(function() {
		if($(this).val() == '') {
			bespoke_contact = 1;
			$(this).next().html('请输入姓名');	
		}
		contact_name[i] = $(this).val();
		i++;
	});
	var i = 0;
	var contact_cardid = {};
	$('.contact_cardid').each(function() {
		if($(this).val() == '') {
			bespoke_contact = 1;
			$(this).next().html('请输入身份证号码');	
		}
		contact_cardid[i] = $(this).val();
		i++;
	});
	if(bespoke_name == '') {
		$('#bespoke_name').focus();
		showerror('bespoke_name', '请输入预定人姓名');
		return;	
	}
	if(bespoke_phone == '') {
		$('#bespoke_phone').focus();
		showerror('bespoke_phone', '请输入预定人手机号');
		return;	
	}
	var regu = /^[1][0-9]{10}$/;
	if(!regu.test(bespoke_phone)) {
		$('#bespoke_phone').focus();
		showerror('bespoke_phone', '预定人手机号格式不正确');
		return;
	}
	if(live_time == '') {
		$('#live_time').focus();
		showerror('live_time', '请输入入驻时间');
		return;	
	}
	if(live_duration == '') {
		$('#live_duration').focus();
		showerror('live_duration', '请输入入驻时长');
		return;	
	}
	var regu = /^\d+$/;
	if(!regu.test(live_duration)) {
		$('#live_duration').focus();
		showerror('live_duration', '入驻时长必须为整数');
		return;
	}
	if(bed_number == '') {
		$('#bed_number').focus();
		showerror('bed_number', '请输入需要床位');
		return;	
	}
	var regu = /^\d+$/;
	if(!regu.test(bed_number)) {
		$('#bed_number').focus();
		showerror('bed_number', '需要床位必须为整数');
		return;
	}
	if(bespoke_contact == 1) {
		$('.contact_name')[0].focus();
		retrun;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'room_id' : room_id,
		'bespoke_name' : bespoke_name,
		'bespoke_phone' : bespoke_phone,		
		'live_time' : live_time,
		'live_duration' : live_duration,		
		'bed_number' : bed_number,
		'red_id' : red_id,
		'contact_name' : contact_name,
		'contact_cardid' : contact_cardid,
		'bespoke_invoice' : bespoke_invoice,
		'invoice_title' : invoice_title,
		'invoice_content' : invoice_content,
		'invoice_membername' : invoice_membername,
		'invoice_provinceid' : invoice_provinceid,
		'invoice_cityid' : invoice_cityid,
		'invoice_areaid' : invoice_areaid,
		'invoice_address' : invoice_address,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=bespoke&op=order',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				window.location.href = 'index.php?act=bespoke&op=payment&bespoke_sn='+data.bespoke_sn;
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

function changeQuantity(id) {
	var red_id = $('#red_id').val();
	var red_price = $('#red_price').val();
	var red_amount = red_price;
	var room_price = $('#room_price').val();
	var live_duration = $('#live_duration').val();
	var bed_number = $('#bed_number').val();
	if(id == 'live_duration') {		
		var regu = /^\d+$/;
		if(!regu.test(live_duration)) {
			var orig = $('#live_duration').attr('orig');
			live_duration = orig;
			$('#live_duration').val(live_duration);			
		} else {
			$('#live_duration').attr('orig', live_duration);
		}
		showsuccess('live_duration');
	} else if(id == 'bed_number') {
		var regu = /^\d+$/;
		if(!regu.test(bed_number)) {
			var orig = $('#bed_number').attr('orig');
			bed_number = orig;
			$('#bed_number').val(bed_number);
		} else {
			var room_storage = parseInt($('#bed_number').attr('room_storage'));
			if(parseInt(bed_number) > room_storage) {
				bed_number = room_storage;	
			}
			$('#bed_number').val(bed_number);
			$('#bed_number').attr('orig', bed_number);
		}
		var bed_number = parseInt(bed_number);
		var item_number = $('.bed-box').find('.book-form-item').length;		
		if(bed_number > item_number) {
			var html = '';
			for(var i=item_number; i<bed_number; i++) {
				html += '<div class="book-form-item full-item">';
            	html += '<label>姓名：</label>';
            	html += '<div class="book-form-value">';
            	html += '<input class="error-input contact_name" type="text"> ';
            	html += '<span class="red"></span> ';
            	html += '<span style="padding-left: 20px;">身份证号码：</span> ';
            	html += '<input class="error-input contact_cardid" type="text"> ';
            	html += '<span class="red"></span>';
            	html += '</div>';
            	html += '</div>';
			}
			$('.bed-box').append(html);
		} else if(bed_number < item_number) {
			$('.bed-box').find('.book-form-item:gt('+(bed_number-1)+')').remove();
		}
		showsuccess('bed_number');
	}
	$.getJSON('index.php?act=bespoke&op=red&room_price='+room_price+'&live_duration='+live_duration+'&bed_number='+bed_number+'&red_id='+red_id, function(result) {
		$('.red-box').html(result.html);
		if(result.select_red == 'false') {
			$('#red_id').val('');				
			$('#red_price').val('0');
			red_amount = 0;
		}
	});
	var bespoke_amount = parseInt(room_price)*parseInt(live_duration)*parseInt(bed_number)-parseInt(red_amount);
	$('#room_number').html(bed_number);
	$('#room_duration').html(live_duration);
	$('#room_amount').html('￥'+bespoke_amount.toFixed(2));
	$('#bespoke_amount').html('￥'+bespoke_amount.toFixed(2));
}

$(function() {
	$('#live_time').datetimepicker({
		  lang : "ch",
		  format : "Y-m-d H:i",
		  step : 1,
		  timepicker : true,
		  yearStart : 2000,
		  yearEnd : 2050,
		  todayButton : false
	});
	
	$('.red-box').on('click', '.radio', function() {
		var red_id = $(this).attr('red_id');
		var red_price = $(this).attr('red_price');
		var room_price = $('#room_price').val();
		var live_duration = $('#live_duration').val();
		var bed_number = $('#bed_number').val();		
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
		var bespoke_amount = parseInt(room_price)*parseInt(live_duration)*parseInt(bed_number)-parseInt(red_amount);
		$('#bespoke_amount').html('￥'+bespoke_amount.toFixed(2));
	});
	
	$('.invoice-radio-box').on('click', '.radio', function() {
		var bespoke_invoice = $(this).attr('bespoke_invoice');
		$('#bespoke_invoice').val(bespoke_invoice);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		if(bespoke_invoice == 'yes') {
			$('.invoice-form').show();
		} else {
			$('.invoice-form').hide();
		}
	});
	
	$('#bespoke_name').on('blur', function() {
		var bespoke_name = $('#bespoke_name').val();
		if(bespoke_name == '') {
			showerror('bespoke_name', '请输入预定人姓名');
			return;
		}
		showsuccess('bespoke_name');
	});
	
	$('#bespoke_phone').on('blur', function() {
		var bespoke_phone = $('#bespoke_phone').val();
		if(bespoke_phone == '') {
			showerror('bespoke_phone', '请输入预定人手机号');
			return;
		}
		var regu = /^[1][0-9]{10}$/;
		if(!regu.test(bespoke_phone)) {
			showerror('bespoke_phone', '预定人手机号格式不正确');
			return;
		}
		showsuccess('bespoke_phone');
	});
	
	$('#live_time').on('blur', function() {
		var live_time = $('#live_time').val();
		if(live_time == '') {
			showerror('live_time', '请输入入驻时间');
			return;
		}
		showsuccess('live_time');
	});
	
	$('#invoice_title').on('blur', function() {
		var invoice_title = $('#invoice_title').val();
		if(invoice_title == '') {
			showerror('invoice_title', '请输入发票抬头');
			return;
		}
		showsuccess('invoice_title');
	});
	
	$('#invoice_content').on('blur', function() {
		var invoice_content = $('#invoice_content').val();
		if(invoice_content == '') {
			showerror('invoice_content', '请输入发票明细');
			return;
		}
		showsuccess('invoice_content');
	});
	
	$('#invoice_membername').on('blur', function() {
		var invoice_membername = $('#invoice_membername').val();
		if(invoice_membername == '') {
			showerror('invoice_membername', '请输入收件人');
			return;
		}
		showsuccess('invoice_membername');
	});
	
	$('#invoice_address').on('blur', function() {
		var invoice_address = $('#invoice_address').val();
		if(invoice_address == '') {
			showerror('invoice_address', '请输入邮寄地址');
			return;
		}
		showsuccess('invoice_address');
	});
	
	$('.bed-box').on('blur', '.contact_name', function() {
		var contact_name = $(this).val();
		if(contact_name != '') {
			$(this).next().html('');
		}
	});
	
	$('.bed-box').on('blur', '.contact_cardid', function() {
		var contact_cardid = $(this).val();
		if(contact_cardid != '') {
			var obj = this;
			$.getJSON('index.php?act=misc&op=checkcard', {'card_id':contact_cardid}, function(data){
				if(data.done == 'true') {
					$(obj).next().html('');
				} else {
					$(obj).next().html(data.msg);
				}
			});
		}
	});
});