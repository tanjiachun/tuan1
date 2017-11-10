/**
 * Created by Administrator on 2017/7/19 0019.
 */
//--------------------------------------------------图片轮播函数
var curIndex = 0, //当前index
    imgLen = $(".imgList li").length; //图片总数
// 定时器自动变换2.5秒每次
var autoChange = setInterval(function(){
    if(curIndex < imgLen-1){
        curIndex ++;
    }else{
        curIndex = 0;
    }
    //调用变换处理函数
    changeTo(curIndex);
},4000);

//对右下角按钮index进行事件绑定处理等
$(".indexList").find("li").each(function(item){
    $(this).click(function(){
        clearInterval(autoChange);
        changeTo(item);
        curIndex = item;
        autoChangeAgain();
    });
});
//清除定时器时候的重置定时器--封装
function autoChangeAgain(){
    autoChange = setInterval(function(){
        if(curIndex < imgLen-1){
            curIndex ++;
        }else{
            curIndex = 0;
        }
        //调用变换处理函数
        changeTo(curIndex);
    },2500);
}
function changeTo(num){
    var goLeft = num * 710;
    $(".imgList").animate({left: "-" + goLeft + "px"},500);
    $(".infoList").find("li").removeClass("infoOn").eq(num).addClass("infoOn");
    $(".indexList").find("li").removeClass("indexOn").eq(num).addClass("indexOn");
}
//--------------------------------------------左右滚动函数
function scrollTxt(){
    var controls={},
        values={},
        t1=500, /*播放动画的时间*/
        t2=4000, /*播放时间间隔*/
        si;
    controls.rollWrap=$(".roll-wrap");
    controls.rollWrapUl=controls.rollWrap.children();
    controls.rollWrapLIs=controls.rollWrapUl.children();
    values.liNums=controls.rollWrapLIs.length;
    values.liHeight=controls.rollWrapLIs.eq(0).height();
    values.ulHeight=controls.rollWrap.height();
    this.init=function(){
        autoPlay();
        pausePlay();
    }
    /*滚动*/
    function play(){
        controls.rollWrapUl.animate({"margin-top" : "-"+values.liHeight}, t1, function(){
            $(this).css("margin-top" , "0").children().eq(0).appendTo($(this));
        });
    }
    /*自动滚动*/
    function autoPlay(){
        /*如果所有li标签的高度和大于.roll-wrap的高度则滚动*/
        if(values.liHeight*values.liNums > values.ulHeight){
            si=setInterval(function(){
                play();
            },t2);
        }
    }
    /*鼠标经过ul时暂停滚动*/
    function pausePlay(){
        controls.rollWrapUl.on({
            "mouseenter":function(){
                clearInterval(si);
            },
            "mouseleave":function(){
                autoPlay();
            }
        });
    }
}
new scrollTxt().init();



function setKeywords() {
    var message=['找做潮州菜的保姆搜   潮州菜','找通风病人医护搜   通风','找清洗吸油烟机保洁搜   油烟机','找修微波炉的维修工搜  微波炉','找修锁的开锁工搜  锁','找管道疏通的师傅搜  管道疏通','找教钢琴的家教搜  钢琴']
    var length=0;
    setInterval(function () {
        $("#keywords").prop('placeholder',message[length]);
        length++;
        if(length==3){
            length=0;
        }
    },6000)
}
setKeywords();
var keywords=[
    {
        code1:'职业保姆',
        code2:'涉外保姆',
        type1:1,
        type2:2,
        children:['非住家保姆','住家保姆','带孩子保姆','做饭保姆','涉外保姆']
    },{
        code1:'钟点服务',
        code2:'清洁清扫',
        type1:3,
        type2:4,
        children:['接送服务','买菜做饭','清洁清洗','临时帮工','钟点看护','综合服务','散传单','钟点式家教']
    },{
        code1:'月嫂保育',
        code2:'育婴早教',
        type1:5,
        type2:6,
        children:['月嫂护理师','育婴师','保育员','产妇照护','婴儿照护','催乳师']
    },{
        code1:'水电维修',
        code2:'管道疏通',
        type1:7,
        type2:8,
        children:['电器维修','水电安装','综合保养','管道疏通','设备安装']
    },{
        code1:'搬家服务',
        code2:'设备搬运',
        type1:9,
        type2:10,
        children:['家庭搬运','企业搬运','设备搬运','综合运输']
    },{
        code1:'家庭外教',
        code2:'家庭辅导',
        type1:11,
        type2:12,
        children:['小学家教','初中家教','高中家教','数学家教','英语家教','钢琴家教','绘画家教','综合家教','其他兴趣家教','其他家教']
    },{
        code1:'陪护医护',
        code2:'老年照顾',
        type1:13,
        type2:14,
        children:['康复理疗','病患全天医护','病患白天医护','老年全天照顾','老年白天照顾']
    },{
        code1:'管家服务',
        code2:'高级家教',
        type1:15,
        type2:16,
        children:['高级管家','成人自考家教']
    }
];
function showKey(){
    var area_banner=$(".area_banner");
    area_banner.empty();
    for(var i=0;i<keywords.length;i++){
        var keywords1=keywords[i].children;
        var area_banner_ul=$("<ul></ul>").appendTo(area_banner);
        for(var j=0;j<keywords1.length;j++){
            var area_banner_ul_li=$("<li></li>").appendTo(area_banner_ul);
            var area_banner_ul_li_a=$("<a></a>").attr('target','_blank').attr('href','index.php?act=index&op=nurse&service_type='+keywords1[j]).appendTo(area_banner_ul_li).html(keywords1[j]);
        }
    }
}
showKey();
$(function () {
    $(".selector .selectorline:nth-child(3) .selector-value>ul>li").mouseover(function () {
        $(this).children(".level_ul").show();
    });
    $(".selector .selectorline:nth-child(3) .selector-value>ul>li").mouseout(function () {
        $(this).children(".level_ul").hide();
    });
    $(".area_banner ul li a").click(function () {
        selectnurse(this, 'keyword', $(this).text());
    })
    $(".nav_banner li").mouseover(function () {
        $(".area_banner ul").eq($(this).index()).addClass("onKey");
        $(".area_banner").show();
    });
    $(".nav_banner li").mouseout(function () {
        $(".area_banner ul").eq($(this).index()).removeClass("onKey");
    });
    $(".nav_banner").mouseover(function () {
        $(".area_banner").show();
    });
    $(".nav_banner").mouseout(function () {
        $(".area_banner").hide();
    });
    $(".area_banner").mouseover(function () {
        $(this).show();
    });
    $(".area_banner").mouseout(function () {
        $(this).hide();
    });
});