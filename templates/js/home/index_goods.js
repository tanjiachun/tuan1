var type_id = 0;
var goods_price = 0;
var sort_field = 'time';
var time_sort = 'desc';
var view_sort = 'asc';
var price_sort = 'asc';

function selectgoods(obj, field, variable) {
	if(field == 'sort_field') {
		$(obj).addClass('curr');
		$(obj).siblings().removeClass('curr');
	} else if(field == 'type_id' || field == 'brand_id' || field == 'goods_price') {
		$('.search-box').show();
		if($('#'+field).length == 0) {
			$('.search-box ul').append('<li id="'+field+'"><span class="selected">'+$(obj).text()+'<i>x</i></span></li>');
		} else {
			$('#'+field).html('<span class="selected">'+$(obj).text()+'<i>x</i></span>');	
		}
	}
	if(field == 'type_id') {
		type_id = variable;
	} else if(field == 'brand_id') {
		brand_id = variable;
	} else if(field == 'goods_price') {
		goods_price = variable;
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
		'class_id' : class_id,
		'type_id' : type_id,
		'brand_id' : brand_id,
		'goods_price' : goods_price,
		'sort_field' : sort_field,
		'time_sort' : time_sort,
		'view_sort' : view_sort,
		'price_sort' : price_sort,
		'page' : page,
	};
	$.getJSON('index.php?act=index&op=goods_search', submitData, function(data){
		if(data.done == 'true') {
			$('.page-box').html(data.goods_page_html);
			$('.count-box').html(data.goods_count_html);
			$('.goods-box').html(data.goods_html);
		}
	});
}

$(function() {
	$('.search-box').on('click', 'i', function() {
		var id = $(this).parent().parent().attr('id');
		if(id == 'type_id') {
			type_id = 0;
		} else if(id == 'brand_id') {
			brand_id = 0;
		} else if(id == 'goods_price') {
			goods_price = 0;
		}
		page = 1;
		$(this).parent().parent().remove();
		if($('.search-box li').length == 0) {
			$('.search-box').hide();		
		}
		var submitData = {
			'class_id' : class_id,
			'type_id' : type_id,
			'brand_id' : brand_id,
			'goods_price' : goods_price,
			'sort_field' : sort_field,
			'time_sort' : time_sort,
			'view_sort' : view_sort,
			'price_sort' : price_sort,
			'page' : page,
		};
		$.getJSON('index.php?act=index&op=goods_search', submitData, function(data){
			if(data.done == 'true') {
				$('.page-box').html(data.goods_page_html);
				$('.count-box').html(data.goods_count_html);
				$('.goods-box').html(data.goods_html);
			}
		});
	});	   
});