<?php include(template('common_header'));?>

<style>
    .left{float: left}
    .comment_and_book{
        font-size: 8px;
        margin:7px;
        overflow: hidden;
    }
    .hotScroll li, .tranScroll li {
        height: 20px;
        line-height: 20px;
        border-bottom: 1px dashed rgb(197,0,0);
        padding-left: 10px;
        width:400px;
    }
    .hot_comment{
        overflow: hidden;
    }
    .hot_book{
        overflow: hidden;
    }
    .hot_comment span,.hot_book span{
        display: inline-block;
        height:20px;
        line-height: 20px;
    }
    /*#roll-wrap{*/
        /*overflow: hidden;*/
    /*}*/
    .hotScroll,.tranScroll{
        height:20px;
    }
    #roll-wrap{
        width:400px;
    }
    ..hotScroll{
        width:400px;!important;
    }
    .tranScroll li,.hotScroll li{
        color:#000;
    }
    .hot_title{color:#ff6905;font-weight: normal;display: inline-block;border:1px solid #ff6905;height:20px;line-height: 20px;padding:0 10px; border-radius: 5px;margin-right: 5px;box-sizing: border-box;}
</style>
<div class="comment_and_book">
    <div class="hot_comment" style="margin-bottom: 2px;">
        <span class="left"><b class="hot_title">雇主热评</b></span>
        <div class="roll-wrap left" id="roll-wrap">
            <ul class="hotScroll" style="width:100%;padding-right: 20px;">
                <li><a href="">雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评 </a></li>
                <li><a href="">雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评 </a></li>
                <li><a href="">雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评 </a></li>
                <li><a href="">雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评 </a></li>
                <li><a href="">雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评雇主热评 </a></li>
                <?php foreach($comment_list as $key => $value) { ?>
                    <?php if(empty($member_list[$value['nurse_id']]['nurse_areaname'])) { ?>
                        <li>[******]<a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['comment_content'];?></a></li>
                    <?php } else { ?>
                        <li>[<?php echo $member_list[$value['nurse_id']]['nurse_areaname'];?>]<a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['comment_content'];?></a></li>
                    <?php } ?>
                <?php } ?>

            </ul>
        </div>
    </div>
    <div class="hot_book">
        <span class="left"><b class="hot_title">交易动态</b></span>
        <div class="roll-wrap left" id="roll-wrap">
            <ul class="tranScroll">
                <?php foreach($book_list as $key => $value) { ?>
                    <li>[<?php echo $value['member_phone'];?>]<?php echo date('m月d H:i', $value['comment_time']);?>已下单</li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<script>

//    $(document).ready(function () {
//        wrapText();
//    });
//    function wrapText() {
//        $("a").each(function (i) {
//            var divH = $(this).width();
//            var $p = $("p", $(this)).eq(0);
//            while ($p.outerWidth() > divH) {
//                $p.text($p.text().replace(/(\s)*([a-zA-Z0-9]+|\W)(\.\.\.)?$/, "..."));
//            }
//        });
//    }

    function a(){
        console.log($(window).width());
        console.log($('.hot_title').width());
        var w=$(window).width();
        var titleW=$('.hot_title').width();
        $(".roll-wrap").css('width',w-titleW-53+'px')
    }
    a();
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
</script>