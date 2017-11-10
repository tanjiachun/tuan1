var b_v = navigator.appVersion;
var IE6 = b_v.search(/MSIE 6/i) != -1;
function objscroll(divname){
	$(window).scroll(function() {
		var bodyH = $(document).scrollTop(),           
		headH = $('.header').height()+$('.institution-hd').height()+$('.institution-preview').height()+60;
		document.getElementById(divname).style.top = 0;
		if(IE6) {
			if(bodyH >= headH) {
				document.getElementById(divname).style.top=bodyH+'px';                
			}
		} else if(bodyH >= headH) {
			$('#'+divname).css('position', 'fixed');
			document.getElementById(divname).style.top = 0;
		} else {
			$('#'+divname).css('position', 'absolute');
		}           
	}); 
}

var map1 = new BMap.Map('rightmap');
map1.addControl(new BMap.NavigationControl());
map1.enableScrollWheelZoom(true);
map1.centerAndZoom('北京', 15);

var myGeo = new BMap.Geocoder();
myGeo.getPoint(pension_address, function(point) {
	if(point) {
		var address = new BMap.Point(point.lng, point.lat);
		var marker = new BMap.Marker(address);
		map1.addOverlay(marker);
		map1.panTo(point);
	}
});

var map2 = new BMap.Map('leftmap');
map2.addControl(new BMap.NavigationControl());
map2.enableScrollWheelZoom(true);
map2.centerAndZoom('北京', 15);

var myGeo = new BMap.Geocoder();
myGeo.getPoint(pension_address, function(point) {
	if(point) {
		var address = new BMap.Point(point.lng, point.lat);
		var marker = new BMap.Marker(address);
		map2.addOverlay(marker);
		map2.panTo(point);
	}
});

$(function() {
	objscroll('tabs-head');
	
	$('#tabs-head').on('click', 'li', function() {
		$(this).addClass('active');
		$(this).siblings('li').removeClass('active');
	});
	
	$('.more-item-btn').on('click',function(){
        if($('.more-item-support').hasClass('active')){
            $('.more-item-support').removeClass('active');
            $('.more-btn').text('展示全部房型');
        }else{
            $('.more-item-support').addClass('active');
            $('.more-btn').text('收起部份房型');
        }
    });
	
	$('.item-title').on('click', function(){
        if($(this).parents('.item-support-list').hasClass('open-support')){
            $(this).parents('.item-support-list').removeClass('open-support');
        }else{
            $(this).parents('.item-support-list').addClass('open-support');
        }
    });
	
	$('.comment-radio-box').on('click', '.radio', function() {
		if($(this).hasClass('active')) return;
		$(this).addClass('active');
		$(this).siblings('.radio').removeClass('active');
		var field_value = $(this).attr('field_value');
		var url = 'index.php?act=pension&op=comment&pension_id='+pension_id+'&field_value='+field_value;
		$('.comment-box').load(url);
	});
});