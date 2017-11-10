var map = new BMap.Map('allmap'); 
map.addControl(new BMap.NavigationControl());
map.enableScrollWheelZoom(true);
map.centerAndZoom('北京', 15);

var driving = new BMap.DrivingRoute(map, {
	renderOptions : {map:map, panel:'r-result'}, 
	onResultsHtmlSet : function() {
		$('#r-result').show()
	}	
});		   

var transit = new BMap.TransitRoute(map, {
	renderOptions : {map:map, panel:'r-result'}, 
	onResultsHtmlSet : function() {
		$('#r-result').show()
	}
});	

var myGeo = new BMap.Geocoder();
myGeo.getPoint('北京', function(point) {
	if(point) {
		var address = new BMap.Point(point.lng, point.lat);
		var marker = new BMap.Marker(address);
		map.addOverlay(marker);
		map.panTo(point);
	}
});

function showMap(cityname, end) {
	$('#blank').show();
	$('#map_box').show();
	$('#map_start').val('');
	$('#map_end').val(end);
    $('#r-result').html('');    
	map.reset();
	map.centerAndZoom(cityname, 15);
	var myGeo = new BMap.Geocoder();
	myGeo.getPoint(cityname, function(point) {
		if(point) {
			var address = new BMap.Point(point.lng, point.lat);
			var marker = new BMap.Marker(address);
			map.addOverlay(marker);
			map.panTo(point);
		}
	});
}

$(function() {
	$('#map_change').on('click', function() {
		var map_start = $('#map_start').val();
		var map_end = $('#map_end').val();
		$('#map_start').val(map_end);
		$('#map_end').val(map_start);
	});
	
	$('#map_bus').on('click', function() {
		$('#map_bus').addClass('active');
		$('#map_car').removeClass('active');
		var map_start = $('#map_start').val();
		var map_end = $('#map_end').val();
		map.clearOverlays(); 				
		transit.search(map_start, map_end);
	});
	
	$('#map_car').on('click', function() {
		$('#map_bus').removeClass('active');
		$('#map_car').addClass('active');
		var map_start = $('#map_start').val();
		var map_end = $('#map_end').val();
		map.clearOverlays(); 				
		driving.search(map_start, map_end);
	});
	
	$('#map_search').on('click', function() {
		var map_start = $('#map_start').val();
		var map_end = $('#map_end').val();
		map.clearOverlays();
		if($('#map_bus').hasClass('active')) {
			transit.search(map_start, map_end);
		} else {
			driving.search(map_start, map_end);
		}
	});
	
	$('#delete').on('click', function() {
		$('#blank').hide();
		$('#map_box').hide();							   
	});
	
	var ready = true;
	var map_btn=document.getElementById('map_btn');
	$('#map_btn').on('click', function() {		
		if(ready) {
			$('#map_right').animate({width:0}, 800);
			ready = false;
			map_btn.style.backgroundPosition = '7px -25px';
		} else {
			$('#map_right').animate({width:281}, 800);
			ready = true;
			map_btn.style.backgroundPosition = '7px 18px';
		}
	});
	
	$('#map_btn').on('mouseover', function() {
		if(ready) {
			map_btn.style.backgroundPosition = '7px -68px';
		} else {
			map_btn.style.backgroundPosition = '7px -111px';
		}
	});
	
	$('#map_btn').on('mouseout', function() {
		if(ready) {
			map_btn.style.backgroundPosition = '7px 18px';
		} else {
			map_btn.style.backgroundPosition = '7px -25px';
		}
	});
});