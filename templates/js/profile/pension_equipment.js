var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var equipment_content = $('#equipment_content').val();
	var i = 0;
	var equipment_image = {};
	$('.image_0').each(function() {
		equipment_image[i] = $(this).val();
		i++;
	});
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'equipment_image' : equipment_image,
		'equipment_content' : equipment_content,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=pension_profile&op=equipment',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('修改成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=pension_profile&op=equipment';
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