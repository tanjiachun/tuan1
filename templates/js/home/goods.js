var showproduct = {
	'boxid' : 'showbox',
	'sumid' : 'showsum',
	'boxw' : 348,
	'boxh' : 348,
	'sumw' : 50,
	'sumh' : 50,
	'sumi' : 5,
	'sums' : 5,
	'sumsel' : 'sel',
	'sumborder' : 2,
	'lastid' : 'showlast',
	'nextid' : 'shownext'
};
var showbox = $('#showbox').html();
	
var n_v = navigator.appVersion;
var IE6 = n_v.search(/MSIE 6/i) != -1;
function objscroll(divname) {
	$(window).scroll(function(){        
		var bodyH = $(document).scrollTop(),
		headH = $('.header').height()+$('.goods-intro').height()+40;
		document.getElementById(divname).style.top = 0;
		if(IE6) {
			if(bodyH >= headH) {
				document.getElementById(divname).style.top = bodyH+'px';                
			}
		} else if(bodyH >= headH) {
			$('#'+divname).css('position', 'fixed');
			document.getElementById(divname).style.top = 0;
		} else {
			$('#'+divname).css('position', 'absolute');
		}           
	}); 
}

function showprompt(msg) {
	$('.alert-box .tip-title').html(msg);
	$('.alert-box').show();
	setTimeout(function() {
		$('.alert-box .tip-title').html('');
		$('.alert-box').hide();
	}, 2000);	
}

function selectSpec(num, liObj, SID) {
	var spec_image = $(liObj).attr('spec_image');
	if(spec_image != '') {
		var html = '<div id="showbox"><img src="'+spec_image+'" width="300" height="300">'+showbox+'</div><div id="showsum"></div><p class="showpage"><a href="javascript:void(0);" id="showlast">&nbsp;</a><a href="javascript:void(0);" id="shownext">&nbsp;</a></p>';
		$('.photoviews').html(html);
		$.ljsGlasses.pcGlasses(showproduct);
	}
	goodsspec['spec' + num] = SID;
	$(liObj).addClass('active');
	$(liObj).siblings().removeClass('active');
	var spec = goodsspec.getSpec();
	var sign = 't';
	$('.spec-box').each(function(){
		if($(this).find('.active').html() == null ){
			sign = 'f';
		}
	});
	if(spec != null && sign == 't') {
		$('.goods-price').html('￥'+spec.price.toFixed(2));
		$('.goods-storage').html(spec.stock);
		var quantity = parseInt($('#quantity').val());
		if(isNaN(quantity) || quantity < 1) {
			$('#quantity').val(1);
		} else if(quantity > parseInt(spec.stock)){
			$('#quantity').val(spec.stock);	
		}
	}
}

function changeQuantity(obj) {
	var quantity = parseInt($(obj).val());
	var max_stock = parseInt($('.goods-storage').text());
	if(isNaN(quantity)) return;
	if(quantity < 1) {
		$(obj).val(1);
	} else {
		if(quantity > max_stock) {
			$(obj).val(max_stock);
		}
	}
	$('#loss').removeClass('disabled');
	$('#plus').removeClass('disabled');
	if(quantity <= 1) {
		$('#loss').addClass('disabled');
	}
	if(quantity >= max_stock) {
		$('#plus').addClass('disabled');
	}
}

$(function() {
	if($('#showbox img').length != 0) {
		$.ljsGlasses.pcGlasses(showproduct);
	}
	objscroll('goods-tabs-head');
	
	$('#goods-tabs-head').on('click', 'li', function() {
		$(this).addClass('active');
		$(this).siblings('li').removeClass('active');
		var id = $(this).attr('id');
		if(id == 'goods_body') {
			$('.goods-detail').show();
			$('.goods-parameter').show();
			$('.txtimg-module').show();
			$('.goods-service').show();
		} else if(id == 'goods_attr') {
			$('.goods-detail').hide();
			$('.goods-parameter').show();
			$('.txtimg-module').hide();
			$('.goods-service').hide();
		} else if(id == 'goods_comment') {
			$('.goods-detail').hide();
			$('.goods-parameter').hide();
			$('.txtimg-module').show();
			$('.goods-service').hide();
		} else if(id == 'sale_support') {
			$('.goods-detail').hide();
			$('.goods-parameter').hide();
			$('.txtimg-module').hide();
			$('.goods-service').show();
		}
	});
	
	$('.comment-radio-box').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		var field_value = $(this).attr('field_value');
		var url = 'index.php?act=goods&op=comment&goods_id='+goods_id+'&field_value='+field_value;
		$('.comment-box').load(url);
	});
	
	$('.quantity-form').on('click', '#loss', function(){
		if(!$(this).hasClass('disabled')) {
			var item = $(this).siblings('input');
			var quantity = parseInt(item.val());
			var max_stock = parseInt($('.goods-storage').text());			
			if(isNaN(quantity) || quantity < 2) {
				quantity = 2;
			}
			if(quantity == 2) {
				$(this).addClass('disabled');
			}
			if(quantity <= max_stock) {
				$('#plus').removeClass('disabled');
			}
			if(quantity > max_stock) {
				item.val(max_stock);					
			} else {
				item.val(quantity-1);
			}
		}
	});
	
	$('.quantity-form').on('click', '#plus', function(){
		if(!$(this).hasClass('disabled')) {
			var item = $(this).siblings('input');
			var quantity = parseInt(item.val());
			var max_stock = parseInt($('.goods-storage').text());							
			if(isNaN(quantity) || quantity < 1) {
				quantity = 0;	
			}
			if(quantity >= 1) {
				$('#loss').removeClass('disabled');
			}
			if(quantity+1 >= max_stock) {
				$(this).addClass('disabled');
			}
			if(quantity >= max_stock) {	
				item.val(max_stock);
			} else {
				item.val(quantity+1);
			}
		}
	});
	
	$('#buynow').on('click', function() {
		var B = false;
		$('.spec-box').each(function() {
			if(!$(this).find('.spec-item').hasClass('active')) {
				B = true;
			}
		});
		if(goodsspec.getSpec() == null || B) {
			showprompt('请选择相关规格');
			return;
		}
		var spec_id = goodsspec.getSpec().id;
		var quantity = parseInt($('#quantity').val());
		var max_stock = parseInt($('.goods-storage').text());
		if(isNaN(max_stock) || max_stock < 1){
			showprompt('商品已经售完');
			return;
		}
		if(isNaN(quantity) || quantity < 1){
			showprompt('请填写购买数量');
			return;
		}
		if(quantity > max_stock){
			showprompt('您购买的商品数量，超出了该商品库存，请您重新选择商品数量');
			return;
		}
		window.location.href = 'index.php?act=buynow&spec_id='+spec_id+'&quantity='+quantity;
    });

    $('#addcart').on('click', function() {
		var B = false;
		$('.spec-box').each(function() {
			if(!$(this).find('.spec-item').hasClass('active')) {
				B = true;
			}
		});
		if(goodsspec.getSpec() == null || B) {
			showprompt('请选择相关规格');
			return;
		}
		var spec_id = goodsspec.getSpec().id;
		var quantity = parseInt($('#quantity').val());
		var max_stock = parseInt($('.goods-storage').text());
		if(isNaN(max_stock) || max_stock < 1){
			showprompt('商品已经售完');
			return;
		}
		if(isNaN(quantity) || quantity < 1){
			showprompt('请填写购买数量');
			return;
		}
		if(quantity > max_stock){
			showprompt('您购买的商品数量，超出了该商品库存，请您重新选择商品数量');
			return;
		}
		var url = 'index.php?act=cart&op=add';
		$.getJSON(url, {'spec_id':spec_id, 'quantity':quantity}, function(data){
			if(data.done == 'true'){
				showalert('成功加入购物车', 'succ');
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
		});
    });
	
	var coupon_btn = false;
	$('.quan-item').on('click',function() {
		var coupon_t_id = $(this).attr('coupon_t_id');
		var url = 'index.php?act=goods&op=coupon&coupon_t_id='+coupon_t_id;
		if(coupon_btn) return;
		coupon_btn = true;
		$.ajax({
			type : "POST",
			url : url,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			dataType : "json",
			success : function(data){
				coupon_btn = false;
				if(data.done == 'true') {
					showalert('领取成功，您可以在个人中心查看', 'succ');
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
				coupon_btn = false;
				showprompt('网路不稳定，请稍候重试');
			}
		});
    });
	
	var favorite_btn = false;
	$('.favorite-add').on('click',function() {
		var url = 'index.php?act=goods&op=favorite&fav_id='+goods_id;
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