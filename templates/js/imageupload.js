$(function() {
	$('[mall_type="image"]').unbind().change(function(){
		if($(this).val()) {
            var id = $(this).attr('id');
            var field_id = $(this).attr('field_id');
            var mode = $(this).attr('mode');
            $.ajaxFileUpload({
                url: 'index.php?act=misc&op=upload&thumb=0',
                secureuri: false,
                fileElementId: id,
                dataType: 'JSON',
                data: {id: id, field_id: field_id, file_name: file_name},
                success: function (data, status) {
                    var data = $.parseJSON(data);
                    if (data.done == 'true') {
                        upload_success(data.field_id, data.file_path, mode);
                        $.getScript("templates/js/imageupload.js");
                    } else {
                        showalert(data.msg);
                    }
                },
                error: function (data, status, e) {
                    showalert(e);
                }
            });
            return false;
        }
	});
});