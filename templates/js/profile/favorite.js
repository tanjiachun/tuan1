$(function() {	
	var favorite_btn = false;
	$('.favorite-del').on('click', function() {
		var fav_id = $(this).attr('fav_id');
		var fav_type = $(this).attr('fav_type');
		var url = 'index.php?act=favorite&op=del&fav_id='+fav_id+'&fav_type='+fav_type;
		if(favorite_btn) return;
		favorite_btn = true;
		$.ajax({
			type : "POST",
			url : url,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			dataType : "json",
			success : function(data){
				favorite_btn = false;
				if(data.done == 'true') {
					$('#fav_'+fav_id).remove();
					showalert('取消收藏成功', 'succ');	
				} else {
					showalert(data.msg);
				}
			},
			timeout : 15000,
			error : function(xhr, type){
				favorite_btn = false;
				showalert('网路不稳定，请稍候重试');
			}
		});
    });
});