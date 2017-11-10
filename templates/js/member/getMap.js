/**
 * Created by Administrator on 2017/7/14 0014.
 */

    // 百度地图API功能
var map = new BMap.Map("allmap");
var longtitude;
var latitude;
var point = new BMap.Point(116.331398,39.897445);
var map = new BMap.Map("allmap",{mapType:BMAP_NORMAL_MAP,minZoom:1,maxZoom:18});
map.centerAndZoom(point,12);
map.enableScrollWheelZoom();
var geolocation = new BMap.Geolocation();
var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮
map.addControl(top_left_control);
map.addControl(top_left_navigation);
map.addControl(top_right_navigation);
geolocation.getCurrentPosition(function(r){
    if(this.getStatus() == BMAP_STATUS_SUCCESS){
        longtitude=r.point.lng;
        latitude=r.point.lat;
        var mk = new BMap.Marker(r.point);
        map.addOverlay(mk);//标出所在地
        map.panTo(r.point);//地图中心移动
        //alert('您的位置：'+r.point.lng+','+r.point.lat);
        var x=r.point.lng;
        var y=r.point.lat;
        console.log(x+":"+y)
        var point = new BMap.Point(r.point.lng,r.point.lat);//用所定位的经纬度查找所在地省市街道等信息
        var gc = new BMap.Geocoder();
        gc.getLocation(point, function(rs){
            var addComp = rs.addressComponents;
            //alert(rs.address);//弹出所在地址
            $("#nurse_address").val(rs.address);
            $("#agent_address").val(rs.address);
        });
    }else {
        alert('failed'+this.getStatus());
    }
},{enableHighAccuracy: true})
map.addEventListener("click",function(e){
    // prompt("鼠标单击地方的经纬度为：",e.point.lng + "," + e.point.lat);
    var point = new BMap.Point(e.point.lng,e.point.lat);
    var gc = new BMap.Geocoder();
    gc.getLocation(point, function(rs){
        var addComp = rs.addressComponents;
        //alert(rs.address);//弹出所在地址
        $("#nurse_address").val(rs.address);
        $("#agent_address").val(rs.address);
    });
});
//        // 百度地图API功能
//        //GPS坐标
//        var x = 118.29689338;
//        var y =33.95204973;
//        var ggPoint = new BMap.Point(x,y);
//
//        //地图初始化
//        var bm = new BMap.Map("allmap");
//        bm.centerAndZoom(ggPoint, 15);
//        bm.addControl(new BMap.NavigationControl());
//
//        //添加gps marker和label
//        var markergg = new BMap.Marker(ggPoint);
//        bm.addOverlay(markergg); //添加GPS marker
//        var labelgg = new BMap.Label("未转换的GPS坐标（错误）",{offset:new BMap.Size(20,-10)});
//        markergg.setLabel(labelgg); //添加GPS label
//
//        //坐标转换完之后的回调函数
//        translateCallback = function (data){
//            if(data.status === 0) {
//                var marker = new BMap.Marker(data.points[0]);
//                bm.addOverlay(marker);
//                var label = new BMap.Label("转换后的百度坐标（正确）",{offset:new BMap.Size(20,-10)});
//                marker.setLabel(label); //添加百度label
//                bm.setCenter(data.points[0]);
//            }
//        }
//
//        setTimeout(function(){
//            var convertor = new BMap.Convertor();
//            var pointArr = [];
//            pointArr.push(ggPoint);
//            convertor.translate(pointArr, 1, 5, translateCallback)
//        }, 1000);
