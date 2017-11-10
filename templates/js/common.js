function isundefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

function showalert(title, mode, closetime) {
	mode = isundefined(mode) ? 'error' : mode;
	closetime = isundefined(closetime) ? 2 : closetime;
	if(mode == 'error') {
		$('#alert-box .tip-icon').html('<i class="iconfont icon-error"></i>');
	} else if(mode == 'succ') {
		$('#alert-box .tip-icon').html('<i class="iconfont icon-check"></i>');
	} else if(mode == 'info') {
		$('#alert-box .tip-icon').html('<i class="iconfont icon-info"></i>');
	}
	$('#alert-box .tip-title').html(title);
	var hint = closetime+' 秒后窗口关闭';
	$('#alert-box .tip-hint').html(hint);
	Custombox.open({
        target : '#alert-box',
        effect : 'blur',
        overlayClose : true,
        speed : 500,
        overlaySpeed : 300,
		open: function () {
			setTimeout(function() {
				Custombox.close();
			}, closetime*1000);
		},
    });
}

function showerror(id, msg) {
	var obj = $(document.getElementById(id));
	obj.siblings('.Validform-wrong').html('<i class="iconfont icon-type"></i>'+msg);
	if(!obj.hasClass('Validform-error')) {
		obj.addClass('Validform-error');
	}
	if(!obj.siblings('.Validform-wrong').hasClass('tip-display')) {
		obj.siblings('.Validform-wrong').addClass('tip-display');		
	}
}
function shuiyin(){
    $("img").each(function() {
        // 特殊情况--过滤掉无关的图片
        if ($(this).attr("id") == "img1") {
            return;
        }
        $(this).addClass('sample2');
        $(this).attr('data-img2blob',$(this).attr('src'));

        $(".sample1").img2blob();
        // with watermark
        $(".sample2").img2blob({
            watermark: '团家政',
            fontStyle: 'Microsoft YaHei,Arial',
            fontSize: '20', // px
            fontColor: '#fff2e8', // default 'black'
            fontX: 0, // The x coordinate where to start painting the text
            fontY: 20 // The y coordinate where to start painting the text
        });
    })
}

function showsuccess(id) {
	var obj = $(document.getElementById(id));
	obj.siblings('.Validform-wrong').html('');
	if(obj.hasClass('Validform-error')) {
		obj.removeClass('Validform-error');
	}
	if(obj.siblings('.Validform-wrong').hasClass('tip-display')) {		
		obj.siblings('.Validform-wrong').removeClass('tip-display');			
	}	
}

function showwarning(id, msg) {
	var obj = $(document.getElementById(id)).find('.m-tip'); 
	obj.html('<i class="iconfont icon-type"></i>'+msg);
	if(!obj.hasClass('tip-display')) {
		obj.addClass('tip-display');
	}
	setTimeout(function() {
		obj.html('');
		obj.removeClass('tip-display');
	}, 3000);
}

function selectprovince() {
	var province_id = $('#province_id').val();
	$.getJSON('index.php?act=misc&op=second_province', {'province_id' : province_id}, function(data){
		if(data.done == 'true') {
			if(data.html == '') {
				$('#city_id').hide();
				$('#area_id').hide();	
			} else {
				$('#city_id').show();
				$('#area_id').show();
				$('#city_id').html(data.html);
				$('#area_id').html('<option value="">-州县-</option>');
			}
		}
	});
}

function selectcity() {
	var city_id = $('#city_id').val();
	$.getJSON('index.php?act=misc&op=second_city', {'city_id' : city_id}, function(data){
		if(data.done == 'true') {
			if(data.html == '') {
				$('#area_id').hide();	
			} else {
				$('#area_id').show();
				$('#area_id').html(data.html);
			}
		}
	});
}

function upload_success(field_id, file_path, mode) {
	if(mode == 'single') {
		single_upload(field_id, file_path);
	} else if(mode == 'multi') {
		multi_upload(field_id, file_path);
	} else if(mode == 'other') {
		other_upload(field_id, file_path)
	}
}

function single_upload(field_id, file_path) {
	var html = '<img src="'+file_path+'"><span class="close-modal single_close" field_id="'+field_id+'">×</span>';
	$('#show_image_'+field_id).html(html);
	$('#show_image_'+field_id).show();
	$('.image_'+field_id).val(file_path);
	$('#upload_image_'+field_id).hide();
	if($('.image_'+field_id).siblings('.Validform-wrong').hasClass('tip-display')) {	
		$('.image_'+field_id).siblings('.Validform-wrong').html('');
		$('.image_'+field_id).siblings('.Validform-wrong').removeClass('tip-display');			
	}
}

function multi_upload(field_id, file_path) {
	var html = '<li class="cover-item"><img src="'+file_path+'"><span class="close-modal multi_close">×</span><input type="hidden" name="image_'+field_id+'[]" class="image_'+field_id+'" value="'+file_path+'"></li>';
	$('#show_image_'+field_id).before(html);
}

function other_upload(field_id, file_path) {
	$('#show_image_'+field_id).html('<img src="'+file_path+'">');
	$('.image_'+field_id).val(file_path);
}

$(function() {
	$('.checkitem').on('click', function() {
		if($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');
		}
	});
	
	$('.checkall').on('click', function() {
		if($(this).hasClass('active')) {
			$('.checkall').removeClass('active');
			$('.checkitem').removeClass('active');
		} else {
			$('.checkall').addClass('active');
			$('.checkitem').addClass('active');
		}
	});
	
	$('.radio-box').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
	});
	
	$('.select-box').on('click', '.select-choice', function() {
		$(this).siblings('.select-list').toggle();
	});
	
	$('.select-box').on('click', 'li', function() {
		var field_key = $(this).attr('field_key');
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');
		if($('.select-box').siblings('.Validform-wrong').hasClass('tip-display')) {	
			$('.select-box').siblings('.Validform-wrong').html('');
			$('.select-box').siblings('.Validform-wrong').removeClass('tip-display');			
		}
	});
	
	$('.first-province-box, .first-city-box, .first-area-box').on('click', '.select-choice', function() {
		$(this).siblings('.select-list').toggle();																  
	});
	
	$('.first-province-box').on('click', 'li', function() {		
		var prefix = $('.first-province-box').attr('prefix');
		var field_key = prefix+'_provinceid';
		var field_value = $(this).attr('field_value');
		var province_id = $('#'+field_key).val();		
		$('#'+field_key).val(field_value);
		if(field_value != province_id) {
			$.getJSON('index.php?act=misc&op=first_province', {'province_id':field_value}, function(data){
				if(data.done == 'true') {
					$('.first-city-box').html(data.html);
					$('.first-area-box').html('');
				}
			});	
		}
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');	
	});
	
	$('.first-city-box').on('click', 'li', function() {		
		var prefix = $('.first-province-box').attr('prefix');
		var field_key = prefix+'_cityid';
		var field_value = $(this).attr('field_value');
		var city_id = $('#'+field_key).val();
		$('#'+field_key).val(field_value);
		if(field_value != city_id) {
			$.getJSON('index.php?act=misc&op=first_city', {'city_id':field_value}, function(data){
				if(data.done == 'true') {
					$('.first-area-box').html(data.html);
					if(data.html == '') {
						if($('.first-province-box').siblings('.Validform-wrong').hasClass('tip-display')) {	
							$('.first-province-box').siblings('.Validform-wrong').html('');
							$('.first-province-box').siblings('.Validform-wrong').removeClass('tip-display');			
						}
					}
				}
			});	
		}
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');		
	});
	
	$('.first-area-box').on('click', 'li', function() {
		var prefix = $('.first-province-box').attr('prefix');
		var field_key = prefix+'_areaid';
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');		
		if($('.first-province-box').siblings('.Validform-wrong').hasClass('tip-display')) {	
			$('.first-province-box').siblings('.Validform-wrong').html('');
			$('.first-province-box').siblings('.Validform-wrong').removeClass('tip-display');			
		}
	});
	
	$('.second-province-box, .second-city-box, .second-area-box').on('click', '.select-choice', function() {
		$(this).siblings('.select-list').toggle();																  
	});
	
	$('.second-province-box').on('click', 'li', function() {		
		var prefix = $('.second-province-box').attr('prefix');
		var field_key = prefix+'_provinceid';
		var field_value = $(this).attr('field_value');
		var province_id = $('#'+field_key).val();		
		$('#'+field_key).val(field_value);
		if(field_value != province_id) {
			$.getJSON('index.php?act=misc&op=first_province', {'province_id':field_value}, function(data){
				if(data.done == 'true') {
					$('.second-city-box').html(data.html);
					$('.second-area-box').html('');
				}
			});	
		}
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');	
	});
	
	$('.second-city-box').on('click', 'li', function() {		
		var prefix = $('.second-province-box').attr('prefix');
		var field_key = prefix+'_cityid';
		var field_value = $(this).attr('field_value');
		var city_id = $('#'+field_key).val();
		$('#'+field_key).val(field_value);
		if(field_value != city_id) {
			$.getJSON('index.php?act=misc&op=first_city', {'city_id':field_value}, function(data){
				if(data.done == 'true') {
					$('.second-area-box').html(data.html);
					if(data.html == '') {
						if($('.second-province-box').siblings('.Validform-wrong').hasClass('tip-display')) {	
							$('.second-province-box').siblings('.Validform-wrong').html('');
							$('.second-province-box').siblings('.Validform-wrong').removeClass('tip-display');			
						}
					}
				}
			});	
		}
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');		
	});
	
	$('.second-area-box').on('click', 'li', function() {
		var prefix = $('.second-province-box').attr('prefix');
		var field_key = prefix+'_areaid';
		var field_value = $(this).attr('field_value');
		$('#'+field_key).val(field_value);
		$(this).parent().parent().hide();
		$(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');		
		if($('.second-province-box').siblings('.Validform-wrong').hasClass('tip-display')) {	
			$('.second-province-box').siblings('.Validform-wrong').html('');
			$('.second-province-box').siblings('.Validform-wrong').removeClass('tip-display');			
		}
	});
	
	$('.picture-list').on('click', '.single_close', function() {
		var field_id = $(this).attr('field_id');
		$('#show_image_'+field_id).html('');
		$('#show_image_'+field_id).hide();
		$('.image_'+field_id).val('');
		$('#upload_image_'+field_id).show();
		$('#file_'+field_id).val('');
	});
	
	$('.picture-list').on('click', '.multi_close', function() {
		$(this).parent().remove();
	});
});