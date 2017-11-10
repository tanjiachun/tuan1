function upload(id, field_id) {
	$.ajaxFileUpload({
		url : 'admin.php?act=misc&op=upload',
		secureuri : false,
		fileElementId : id,
		dataType : 'JSON',
		data:{id : id, field_id : field_id, file_name : file_name},
		success : function(data, status) {
			var data = $.parseJSON(data);
			if(data.done == 'true') {
				$('#show_image_'+data.field_id).attr('src', data.file_path);
				$('#image_'+data.field_id).val(data.file_path);
			} else {
				showDialog(data.msg, 'notice');	
			}
		},
		error : function(data, status, e) {
			showDialog(e, 'notice');
		}
	});
	return false;
}

$(function() {
	$('[mall_type="image"]').unbind().change(function(){
		var id = $(this).attr('id');
		var field_id = $(this).attr('field_id');
		$.ajaxFileUpload({
			url : 'admin.php?act=misc&op=upload',
			secureuri : false,
			fileElementId : id,
			dataType : 'JSON',
			data:{id : id, field_id : field_id, file_name : file_name},
			success : function(data, status) {
				var data = $.parseJSON(data);
				if(data.done == 'true') {
					$('#show_image_'+data.field_id).attr('src', data.file_path);
					$('#image_'+data.field_id).val(data.file_path);
					$.getScript("admin/templates/js/index.js");
				} else {
					showDialog(data.msg, 'notice');	
				}
			},
			error : function(data, status, e) {
				showDialog(e, 'notice');
			}
		});
		return false;
	});
});