var district_id = temp_districtid;
var pension_nature = 0;
var pension_person = 0;
var pension_bed = 0;
var pension_price = 0;
var sort_field = 'time';
var time_sort = 'desc';
var view_sort = 'asc';
var price_sort = 'asc';

function selectpension(obj, field, variable) {
	if(field == 'sort_field') {
		$(obj).addClass('curr');
		$(obj).siblings().removeClass('curr');
	} else if(field == 'pension_type' || field == 'pension_nature' || field == 'pension_bed' || field == 'pension_price' || field == 'pension_person') {
		$('.search-box').show();
		if($('#'+field).length == 0) {
			$('.search-box ul').append('<li id="'+field+'"><span class="selected">'+$(obj).text()+'<i>x</i></span></li>');
		} else {
			$('#'+field).html('<span class="selected">'+$(obj).text()+'<i>x</i></span>');	
		}
	}
	if(field == 'district_id') {
		district_id = variable;
	} else if(field == 'pension_type') {
		pension_type = variable;
	} else if(field == 'pension_nature') {
		pension_nature = variable;
	} else if(field == 'pension_person') {
		pension_person = variable;
	} else if(field == 'pension_bed') {
		pension_bed = variable;
	} else if(field == 'pension_price') {
		pension_price = variable;	
	} else if(field == 'sort_field') {
		sort_field = variable;
		if(sort_field == 'time') {
			time_sort = time_sort=='desc' ? 'asc' : 'desc';
			view_sort = 'asc';
			price_sort = 'asc';
		} else if(sort_field == 'view') {
			view_sort = view_sort=='desc' ? 'asc' : 'desc';
			time_sort = 'asc';
			price_sort = 'asc';
		} else if(sort_field == 'price') {
			price_sort = price_sort=='desc' ? 'asc' : 'desc';
			time_sort = 'asc';
			view_sort = 'asc';
		}
	}
	if(field == 'page') {
		page = variable;	
	} else {
		page = 1;	
	}
	var submitData = {
		'district_id' : district_id,
		'pension_type' : pension_type,
		'pension_nature' : pension_nature,
		'pension_person' : pension_person,
		'pension_bed' : pension_bed,
		'pension_price' : pension_price,		
		'sort_field' : sort_field,
		'time_sort' : time_sort,
		'view_sort' : view_sort,
		'price_sort' : price_sort,
		'page' : page,
	};
	$.getJSON('index.php?act=index&op=pension_search', submitData, function(data){
		if(data.done == 'true') {
			$('.page-box').html(data.pension_page_html);
			$('.count-box').html(data.pension_count_html);
			$('.pension-box').html(data.pension_html);
			if(data.district_html == '') {
				$('.search-box').show();
				if($('#'+field).length == 0) {
					$('.search-box ul').append('<li id="'+field+'"><span class="selected">'+$(obj).text()+'<i>x</i></span></li>');
				} else {
					$('#'+field).html('<span class="selected">'+$(obj).text()+'<i>x</i></span>');	
				}
			} else {
				$('.district-box').html(data.district_html);
				temp_districtid = district_id;
			}
		}
	});
}

$(function() {
	$('.search-box').on('click', 'i', function() {
		var id = $(this).parent().parent().attr('id');
		if(id == 'district_id') {
			district_id = temp_districtid;
		} else if(id == 'pension_type') {
			pension_type = 0;
		} else if(id == 'pension_nature') {
			pension_nature = 0;
		} else if(id == 'pension_person') {
			pension_person = 0;
		} else if(id == 'pension_bed') {
			pension_bed = 0;
		} else if(id == 'pension_price') {
			pension_price = 0;	
		}
		page = 1;
		$(this).parent().parent().remove();
		if($('.search-box li').length == 0) {
			$('.search-box').hide();		
		}
		var submitData = {
			'district_id' : district_id,
			'pension_type' : pension_type,
			'pension_nature' : pension_nature,
			'pension_person' : pension_person,
			'pension_bed' : pension_bed,
			'pension_price' : pension_price,			
			'sort_field' : sort_field,
			'time_sort' : time_sort,
			'view_sort' : view_sort,
			'price_sort' : price_sort,
			'page' : page,
		};
		$.getJSON('index.php?act=index&op=pension_search', submitData, function(data){
			if(data.done == 'true') {
				$('.page-box').html(data.pension_page_html);
				$('.count-box').html(data.pension_count_html);
				$('.pension-box').html(data.pension_html);
			}
		});
	});

    $('.pension-box').on('click', '.item-title', function(){
        if($(this).parents('.item-support-list').hasClass('open-support')){
            $(this).parents('.item-support-list').removeClass('open-support');
        }else{
            $(this).parents('.item-support-list').addClass('open-support');
        }
    });
});