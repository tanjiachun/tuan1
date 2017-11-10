function showbirthday(){
	$('#member_birthday').html('');
	$('#member_birthday').append('<option value="">请选择</option>');
	for(var i=1; i<=28; i++){
		$('#member_birthday').append('<option value="'+i+'">'+i+'</option>');
	}
	if($('#member_birthmonth').val() != '2'){
		$('#member_birthday').append('<option value="29">29</option>');
		$('#member_birthday').append('<option value="30">30</option>');
		switch($('#member_birthmonth').val()){
			case '1':
			case '3':
			case '5':
			case '7':
			case '8':
			case '10':
			case '12':{
				$('#member_birthday').append('<option value="31">31</option>');
			}
		}
	} else if($('#member_birthyear').val() != '') {
		var nbirthyear = $('#member_birthyear').val();
		if(nbirthyear%400 == 0 || (nbirthyear%4 == 0 && nbirthyear%100 != 0)) {
			$('#member_birthday').append('<option value="29">29</option>');
		}
	}
}

var submit_btn = false;
function checksubmit() {
	var formhash = $('#formhash').val();
	var member_nickname = $('#member_nickname').val();
	var member_avatar = $('#member_avatar').val();
	var member_sex = $('#member_sex').val();
	var member_birthyear = $('#member_birthyear').val();
	var member_birthmonth = $('#member_birthmonth').val();
	var member_birthday = $('#member_birthday').val();
	var member_truename = $('#member_truename').val();
	var member_cardid = $('#member_cardid').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'member_nickname' : member_nickname,
		'member_avatar' : member_avatar,
		'member_sex' : member_sex,
		'member_birthyear' : member_birthyear,
		'member_birthmonth' : member_birthmonth,
		'member_birthday' : member_birthday,
		'member_truename' : member_truename,
		'member_cardid' : member_cardid,
	};
	if(submit_btn) return;
	submit_btn = true;
	$.ajax({
		type : 'POST',
		url : 'index.php?act=profile',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data) {
			submit_btn = false;
			if(data.done == 'true') {
				$('.return-success').html('修改成功');
				$('.return-success').show();
				setTimeout(function(){
					window.location.href = 'index.php?act=profile';
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
	$('#member_cardid').on('blur', function() {
		var member_cardid = $('#member_cardid').val();
		if(member_cardid == '') {
			showsuccess('member_cardid');
			return;
		}
		$.getJSON('index.php?act=misc&op=checkcard', {'card_id' : member_cardid}, function(data){
			if(data.done == 'true') {
				showsuccess('member_cardid');
			} else {
				showerror('member_cardid', data.msg);
			}
		});
	});
});