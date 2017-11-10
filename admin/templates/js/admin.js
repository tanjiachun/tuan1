$(document).ready(function(){
    var h = $(window).height() - $('.header').height();
    var pagestyle = function() {
        $('.wrap').css('min-height',h);
    };
    pagestyle();
    $(window).resize(pagestyle);

	$('#menu_wrap').on('click','li>a',function(e){
		var submenu = $(this).next();
		if(submenu.length > 0) {
			var dl = $('#menu_wrap dl:visible').not(submenu[0]);
			if(dl.length > 0) {
				dl.slideUp('fast',function() {
					submenu.slideToggle('fast');
					$(this).prev().toggleClass('active');
				});
			} else {
				submenu.slideToggle('fast');
			}
			$(this).toggleClass('active');
		}
	});
	
	$('.checkitem').on('click', function() {
		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		} else {
			$(this).addClass('selected');
		}
	});
	
	$('.checkall').on('click', function() {
		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
			$(".checkitem").removeClass('selected');
		} else {
			$(this).addClass('selected');
			$(".checkitem").addClass('selected');
		}
	});

    $('.batchbutton').on('click', function() {
        if($('.checkitem.selected').length == 0) {
            showDialog('请至少选择一项', 'notice');
        	return false;
        }
        var _uri = $(this).attr('uri');
        var _name = $(this).attr('name');
        var handleResult = function(uri, name) {
	        var items = '';
	        $('.checkitem.selected').each(function(){
	            items += $(this).attr('data') + ',';
	        });
	        items = items.substr(0, (items.length - 1));
	        ajaxget(uri + '&' + name + '=' + items);
	        return false;
        }
        if($(this).attr('confirm')){
        	showDialog($(this).attr('confirm'), 'confirm', '', function() {handleResult(_uri, _name)});
        	return false;
        }
		handleResult(_uri, _name);
    });
	
	$(".icon_radio").on('click', function() {
		var field = $(this).attr('field');
		var data = $(this).attr('data');
		$("#"+field).val(data);
		$(this).parent().attr("class", "selected");	
		$(this).parent().siblings(".selected").removeClass("selected");
	});
});

function image_del(index) {
	$('#show_image_'+index).attr('src', 'admin/templates/images/default.jpg');
	$('#image_'+index).val('');
}

function number_format(num, ext){
	return num.toFixed(ext);
}