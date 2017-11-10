var keywordsJSON=[
    {
        code1:'职业保姆',
        code2:'涉外保姆',
        length:0,
        type1:1,
        type2:2,
        children:['非住家保姆','住家保姆','带孩子保姆','做饭保姆','涉外保姆']
    },{
        code1:'钟点服务',
        code2:'保洁服务',
        length:1,
        type1:3,
        type2:4,
        children:['接送服务','买菜做饭','钟点保洁','临时帮工','钟点看护','综合服务','散传单','钟点家教','单次保洁']
    },{
        code1:'月嫂保育',
        code2:'育婴早教',
        length:2,
        type1:5,
        type2:6,
        children:['月子护理师','育婴师','保育员','产妇照护','婴儿照护','催乳师']
    },{
        code1:'维修安装',
        code2:'清洗疏通',
        length:3,
        type1:7,
        type2:8,
        children:['电器维修','水电安装','综合保养','管道疏通','设备拆装','电脑维修']
    },{
        code1:'搬家服务',
        code2:'设备搬运',
        length:4,
        type1:9,
        type2:10,
        children:['家庭搬运','企业搬运','设备搬运','综合运输']
    },{
        code1:'家庭外教',
        code2:'家庭辅导',
        length:5,
        type1:11,
        type2:12,
        children:['小学家教','初中家教','高中家教','数学家教','英语家教','钢琴家教','绘画家教','综合家教','自考辅导','其他家教']
    },{
        code1:'陪护医护',
        code2:'老年照顾',
        length:6,
        type1:13,
        type2:14,
        children:['康复理疗','全天医护','白天医护','老幼照顾','病患照顾']
    },{
        code1:'管家服务',
        code2:'高级家教',
        length:7,
        type1:15,
        type2:16,
        children:['高级管家','空房管家','别墅管家','外籍管家','成人家教']
    }
];
function showKey(){
    var keywordBox=$(".keywordBox");
    keywordBox.empty();
    for(var i=0;i<keywordsJSON.length;i++){
        var keywordBox1=keywordsJSON[i].children;
        for(var j=0;j<keywordBox1.length;j++){
            var keywordBox_span=$("<span></span>").appendTo(keywordBox).html(keywordBox1[j]);
        }
    }
}
function showCode(){
    var selectBox=$(".selectBox");
    selectBox.empty();
    for(var i=0;i<keywordsJSON.length;i++){
        var selectBox_span1=$("<span></span>").appendTo(selectBox).html(keywordsJSON[i].code1).attr('filed_value',keywordsJSON[i].type1).attr('filed_key','nurse_type').attr('data',keywordsJSON[i].length);
        var selectBox_span2=$("<span></span>").appendTo(selectBox).html(keywordsJSON[i].code2).attr('filed_value',keywordsJSON[i].type2).attr('filed_key','nurse_type').attr('data',keywordsJSON[i].length);
    }
    change();
}
showKey();
showCode();
function  change() {
    $(".selectBox span").each(function () {
        if($(this).attr('filed_value')==nurse_type){
            $(this).addClass("on");
            showKeyNum($(this).attr('data'));
        }
    });
    $(".keywordBox span").each(function () {
        if($(this).text()==service_type){
            $(this).addClass("on");
        }
    });
}
function showKeyNum(num){
    var keywordBox=$(".keywordBox");
    keywordBox.empty();
    var keywordBox1=keywordsJSON[num].children;
    for(var j=0;j<keywordBox1.length;j++){
        var keywordBox_span=$("<span></span>").appendTo(keywordBox).html(keywordBox1[j]);
    }
}
function choose() {
    $(".keywordBox span").click(function () {
        if(!$(this).hasClass("on")){
            $(this).addClass("on").siblings().removeClass("on");
        }else{
            $(this).removeClass("on");
        }
    });
}
choose();
$(".selectBox span").click(function(){
    if($(this).hasClass("on")){
        showKey();
        $(this).removeClass("on");

    }else{
        showKeyNum($(this).attr('data'));
        $(this).addClass("on").siblings().removeClass("on");
    }
    choose();
});

//特色需求选项框  ， 包括添加自定义  点击  确认  取消等函数
var special_service_json=[
    {
        children:['会做卤菜','会做川菜','会做粤菜','会做潮州菜','会做  苏菜','会做淮扬菜','会做闽菜','会做浙菜','会做湘菜','会做徽菜','会做东北菜','会做上海菜','会做新疆菜','会做面食','会做西餐','会走甜点']
    },{
        children:['有医学常识','有营养师资质','有护理师资质','有厨师证','有驾照的','有健康证','有月嫂证','有育婴师证','家政员资格证']
    },{
        children:['能照顾老人','能照顾孩子','能照顾2个孩子','能照顾3个孩子','能照顾多个孩子','能照顾宠物']
    },{
        children:['护理糖尿病人','护理痛风病人','护理轻伤病人','护理发烧病人','护理慢性病人','护理高血脂病人','护理高血压病人','护理心脑血管疾病病人','护理癌症病人','护理冠心病人']
    },{
        children:['能照顾脾气大的人','能照顾生活不能自理的人','能照顾大小便不能自理的人','能照顾盲人','能照顾残疾人','能照顾精神异常的人','能照顾瘫痪的人','能照顾聋哑人']
    },{
        children:['会英文的','会推拿','会园艺','会电脑']
    }
];
function show_special_service() {
    var box=$("#special_service_box");
    box.empty();
    var box_input=$("<input type='text' maxlength='10' class='add_span' placeholder='最多可输入10个字'>").appendTo(box);
    var add_button=$("<button class='add_btn'>添加自定义需求</button>").appendTo(box);
    var span_box=$("<div class='span_box'></div>").appendTo(box);
    for(var i=0;i<special_service_json.length;i++){
        var span=special_service_json[i].children;
        var special_br=$("<br>").appendTo(span_box);
        for(var j=0;j<span.length;j++){
            var special_span=$("<span></span>").appendTo(span_box).html(span[j]);
        }
    }
    var special_menu=$("<div class='special_service_menu'></div>").appendTo(box);
    var quxiao_btn=$("<button class='quxiao_btn'>取消</button>").appendTo(special_menu);
    quxiao_btn.click(function () {
        box.hide();
    })
    var queding_btn=$("<button class='queding_btn'>确定</button>").appendTo(special_menu);
    queding_btn.click(function () {
        var item='';
        $(".span_box span.active").each(function () {
            item+=$(this).text();
            item+=',';
        })
        $("#nurse_special_service").val(item);
        $("#nurse_special_service").val(item);
        box.hide();
    });
    add_button.click(function () {
        if($(".add_span").val()!==''){
            var add_span=$("<span></span>").appendTo(span_box).html(box_input.val());
            box_input.val("");
            add_span.click(function () {
                if($(this).hasClass("active")){
                    $(this).removeClass("active");
                }else {
                    if ($(".span_box span.active").length < 4) {
                        $(this).addClass("active");
                    }
                }
                })
        }
    });
}
show_special_service();

$(".special_service_show").click(function () {
    $("#special_service_box").show();
});
function special_span_chooose() {
    $(".span_box span").click(function () {
        if($(this).hasClass("active")){
            $(this).removeClass("active");
        }else {
            if ($(".span_box span.active").length < 4) {
                $(this).addClass("active");
            }
        }
    });
}
special_span_chooose();