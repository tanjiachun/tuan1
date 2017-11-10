var district_id = 0;
var keyword = '';
var service_type='';
var nurse_education = 0;
var nurse_price = 0;
var nurse_age = 0;
var grade_id = 0;
var sort_field = 'time';
var time_sort = 'desc';
var education_sort = 'asc';
var price_sort = 'asc';
var age_sort = 'asc';


function state_select(){
    $(".state_select").each(function(){
        var state_cideci=$(this).attr('data');
        var html='';
        if(state_cideci==1||state_cideci==0){
            html+='<span class="nurse-sta">'+'找工作'+'</span>';
        }
        if(state_cideci==2){
            html+='<span class="nurse-sta staing">'+'服务中'+'</span>';
        }
        if(state_cideci==3){
            html+='<span class="nurse-sta holiday">'+'假期中'+'</span>';
        }
        if(state_cideci==4){
            html+='<span class="nurse-sta trouble">'+'纠纷中'+'</span>';
        }
        if(state_cideci==5){
            html+='<span class="nurse-sta unknow">'+'无状态'+'</span>';
        }
        $(this).html(html);
    });
}
function selectnurse(obj, field, variable) {
	if(field == 'sort_field') {
		$(obj).addClass('curr');
		$(obj).siblings().removeClass('curr');
	} else if(field == 'district_id' || field == 'nurse_type' || field == 'nurse_education' || field == 'nurse_price' || field == 'nurse_age' || field == 'grade_id') {
		$('.search-box').show();
		if($('#'+field).length == 0) {
			$('.search-box ul').append('<li id="'+field+'"><span class="selected">'+$(obj).text()+'<i>x</i></span></li>');
		} else {
			$('#'+field).html('<span class="selected">'+$(obj).text()+'<i>x</i></span>');	
		}
	}
	if(field == 'district_id') {
		district_id = variable;
	} else if(field == 'keyword') {
		keyword = variable;
	} else if(field == 'nurse_type') {
		nurse_type = variable;
	} else if(field == 'service_type') {
        service_type = variable;
    } else if(field == 'nurse_education') {
		nurse_education = variable;
	} else if(field == 'nurse_price') {
		nurse_price = variable;
	} else if(field == 'nurse_age') {
		nurse_age = variable;
	} else if(field == 'grade_id') {
		grade_id = variable;
	} else if(field == 'sort_field') {
		sort_field = variable;
		if(sort_field == 'time') {
			time_sort = time_sort=='desc' ? 'asc' : 'desc';
			education_sort = 'asc';
			price_sort = 'asc';
			age_sort = 'asc';
		} else if(sort_field == 'education') {
			education_sort = education_sort=='desc' ? 'asc' : 'desc';
			time_sort = 'asc';
			price_sort = 'asc';
			age_sort = 'asc';
		} else if(sort_field == 'price') {
			price_sort = price_sort=='desc' ? 'asc' : 'desc';
			time_sort = 'asc';
			education_sort = 'asc';
			age_sort = 'asc';
		} else if(sort_field == 'age') {
			age_sort = age_sort=='desc' ? 'asc' : 'desc';
			time_sort = 'asc';
			education_sort = 'asc';
			price_sort = 'asc';
		} else if(sort_field == 'new') {
			time_sort = 'asc';
			education_sort = 'asc';
			price_sort = 'asc';
			age_sort = 'asc';		
		}
	}
	if(field == 'page') {
		page = variable;	
	} else {
		page = 1;	
	}
	var submitData = {
		'district_id' : district_id,
		'keyword' : keyword,
		'nurse_type' : nurse_type,
        'service_type':service_type,
		'nurse_education' : nurse_education,
		'nurse_price' : nurse_price,
		'nurse_age' : nurse_age,
		'grade_id' : grade_id,
		'sort_field' : sort_field,
		'time_sort' : time_sort,
		'education_sort' : education_sort,
		'price_sort' : price_sort,
		'age_sort' : age_sort,
		'page' : page,
	};
	$.getJSON('index.php?act=index&op=nurse_search', submitData, function(data){
		if(data.done == 'true') {
			$('.page-box').html(data.nurse_page_html);
			$('.count-box').html(data.nurse_count_html);
			$('.nurse-box').html(data.nurse_html);
			$('.multi-box').html(data.nurse_multi_html);
            state_select();
		}
	});
	state_select();
}

function showprompt(msg) {
	$('.alert-box .tip-title').html(msg);
	$('.alert-box').show();
	setTimeout(function() {
		$('.alert-box .tip-title').html('');
		$('.alert-box').hide();
	}, 2000);	
}

$(function() {
	state_select();
	$('.flod-btn').on('click',function(){
        if($('.flod-MoreWrap').hasClass('open-MoreWrap')){
            $('.flod-MoreWrap').removeClass('open-MoreWrap');
            $('.flod-btn em').text('展示');
        }else{
            $('.flod-MoreWrap').addClass('open-MoreWrap');
            $('.flod-btn em').text('收起');
        }
    });
	
	$('.search-box').on('click', 'i', function() {
		var id = $(this).parent().parent().attr('id');
		if(id == 'district_id') {
			district_id = 0;
		} else if(id == 'nurse_type') {
			nurse_type = 0;
		} else if(id == 'nurse_education') {
			nurse_education = 0;
		} else if(id == 'nurse_price') {
			nurse_price = 0;
		} else if(id == 'nurse_age') {
			nurse_age = 0;
		} else if(id == 'grade_id') {
			grade_id = 0;
		}
		page = 1;
		$(this).parent().parent().remove();
		if($('.search-box li').length == 0) {
			$('.search-box').hide();
		}
		var submitData = {
			'district_id' : district_id,
			'nurse_type' : nurse_type,
			'nurse_education' : nurse_education,
			'nurse_price' : nurse_price,
			'nurse_age' : nurse_age,
			'grade_id' : grade_id,
			'sort_field' : sort_field,
			'time_sort' : time_sort,
			'education_sort' : education_sort,
			'price_sort' : price_sort,
			'age_sort' : age_sort,
			'page' : page,
		};
		$.getJSON('index.php?act=index&op=nurse_search', submitData, function(data){
			if(data.done == 'true') {
				$('.page-box').html(data.nurse_page_html);
				$('.count-box').html(data.nurse_count_html);
				$('.nurse-box').html(data.nurse_html);
                $('.multi-box').html(data.nurse_multi_html);
			}
		});
	});
	
	var favorite_btn = false;
	$('.nurse-box').on('click', '.favorite-add', function() {
		var nurse_id = $(this).attr('nurse_id');
		var url = 'index.php?act=nurse&op=favorite&fav_id='+nurse_id;
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
					showalert('收藏成功', 'succ');
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
					showprompt(data.msg);
				}
			},
			timeout : 15000,
			error : function(xhr, type){
				favorite_btn = false;
				showprompt('网路不稳定，请稍候重试');
			}
		});
    });

});