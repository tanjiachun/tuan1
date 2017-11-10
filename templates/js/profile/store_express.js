var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var i = 0;
	var express_id = {};
	$('.check').each(function() {
		if($(this).hasClass('active')) {
			express_id[i] = $(this).attr('express_id');
			i++;
		}
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'express_id' : express_id,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=store_express',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('保存成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=store_express';
				}, 1000);
			} else {
				showalert(data.msg);
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
	$('.check').on('click', function() {
		if(!$(this).hasClass('active')) {
			$(this).addClass('active');	
		} else {
			$(this).removeClass('active');
		}
	});	   
});