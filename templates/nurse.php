<?php include(template('common_header'));?>
    <div class="nurse_header">
        <a class="left" href="index.php">
            <img src="templates/images/logo.png">
        </a>
        <div class="left" style="min-width:100px;height:74px;margin-left: 20px;padding-left: 20px;border-left:1px solid #ddd; ">
            <?php if(empty($nurse['agent_id'])) { ?>
                <p style="line-height: 37px;font-size: 16px;font-weight: 700;">个人保姆</p>
            <?php } else { ?>
                <p style="line-height: 37px;font-size: 16px;font-weight: 700;"><?php echo $agent['agent_name'];?></p>
            <?php } ?>

            <p style="line-height: 37px;">
                <?php if(empty($nurse['agent_id'])) { ?>
                <a href="javascript:;" class="lianxi"><img style="margin:0 5px 3px 0; " src="templates/images/lianxi.png" alt="">和我联系</a>
                <?php } else { ?>
                    <a href="javascript:;" class="lianxi2"><img style="margin:0 5px 3px 0; " src="templates/images/lianxi.png" alt="">和我联系</a>
                <?php } ?>
            </p>
        </div>
        <div class="left" style="width:100px;height:74px;margin-left: 20px;">
            <?php if(empty($nurse['agent_id'])) { ?>
                <p style="line-height: 37px;">等级<img style="height:16px;margin-left: 5px;" src="<?php echo $grade['grade_icon'];?>" alt=""></p>
            <?php } else { ?>
                <p style="line-height: 37px;">等级<img style="height:16px;margin:0 0 3px 5px;" src="<?php echo $agent_grade['grade_icon'];?>" alt=""></p>
            <?php } ?>
            <?php if(empty($nurse['agent_id'])) { ?>
                <p style="line-height: 37px;">关注&nbsp;<?php echo $nurse['nurse_favoritenum'];?>&nbsp;人</p>
            <?php } else { ?>
                <p style="line-height: 37px;">关注&nbsp;<?php echo $agent['agent_focusnum'];?>&nbsp;人</p>
            <?php } ?>
        </div>
        <div class="search-box-top right">
            <span class="bg s_ipt_wr iptfocus quickdelete-wrap" style="width: 500px;">
                <input style="width:500px;" type="text" id="keywords" class="itxt" placeholder="搜索您需要的服务">
            </span>
            <span class="s_btn_wr">
                <input type="submit" id="search-btn" value="搜索" class="search-btn bg s_btn">
            </span>
        </div>
    </div>
</div>
<?php if(empty($nurse['agent_id'])) { ?>
    <div class="nurse_header_choose">
        <div class="nurse_header_choose_content">

        </div>
    </div>
<?php } else {  ?>
    <div class="nurse_header_choose">
        <div class="nurse_header_choose_content">
            <span><a href="index.php?act=agent_show&agent_id=<?php echo $nurse['agent_id'] ?>">机构首页</a></span>
            <span><a href="index.php?act=agent_staff&agent_id=<?php echo $nurse['agent_id'] ?>">全部员工</a></span>
            <span><a href="index.php?act=agent_answers&agent_id=<?php echo $nurse['agent_id'] ?>">机构问答</a></span>
            <span>机构号&nbsp;<?php echo $agent['agent_id'];?></span>
        </div>
    </div>
<?php } ?>
<div id="nurse_message">
    <div class="nurse_message_left left">
        <div class="nurse_level left">
            <div class="nurse_image">
                <?php if($nurse['nurse_image']== '') { ?>
                    <div class="nurse-previews pull-left"><img width="300px;" height="350px;" src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></div>
                <?php } else { ?>
                    <div class="nurse-previews pull-left"><img width="300px;" height="350px;" src="<?php echo $nurse['nurse_image'];?>"></div>
                <?php } ?>
                <?php if($nurse['nurse_type'] == 3) { ?>
                    <span>¥<strong><?php echo $nurse['nurse_price'];?></strong>元/时</span>
                <?php }else if($nurse['nurse_type'] == 4) { ?>
                    <span>¥<strong><?php echo $nurse['nurse_price'];?></strong>元/平方</span>
                <?php }else if($nurse['nurse_type'] == 7 || $nurse['nurse_type']==8) { ?>
                    <span>¥<strong><?php echo $nurse['nurse_price'];?></strong>元/次</span>
                <?php }else if($nurse['nurse_type'] == 9 || $nurse['nurse_type']==10) { ?>
                    <span>¥<strong><?php echo $nurse['nurse_price'];?></strong>元/次</span>
                <?php } else { ?>
                    <span>¥<strong><?php echo $nurse['nurse_price'];?></strong>元/月</span>
                <?php } ?>
            </div>
            <div class="nurse_name">
                <span>称呼&nbsp;&nbsp;<?php echo $nurse['nurse_nickname'];?></span>
                <span>年龄&nbsp;&nbsp;<?php echo $nurse['nurse_age'];?></span>
                <span>籍贯&nbsp;&nbsp;<?php echo $nurse['birth_cityname'];?></span>
                <span>等级&nbsp;&nbsp;<img src="<?php echo $grade['grade_icon'];?>" alt=""></span>
            </div>
        </div>
        <?php if($nurse['nurse_type']==1 || $nurse['nurse_type']==2) { ?>
            <div class="nurse_type left">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['service_price']*$nurse['nurse_discount']+$nurse['nurse_price'] ?>">
                    <input type="hidden" id="service_price" name="service_price" value="<?php echo $nurse['service_price']*$nurse['nurse_discount'] ?>">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price'] ?>">
                    <div class="price-type-list">
                        <div class="price-type-item">服务费<span>¥ <s><small><?php echo $nurse['service_price'];?></small></s></span></div>
                        <div class="service_price_discount price-type-item">
                            折扣价<span> <small style="color: #ff6905;">¥ </small> <strong class="service_price_change" style="color: #ff6905;"><?php echo $nurse['service_price']*$nurse['nurse_discount'];?></strong> + 本月工资 <small style="color: #ff6905;">¥ <?php echo $nurse['nurse_price'] ?></small></span>
                            <b style="margin-left:10px;font-weight: normal;background: #ff6905;color:#fff;padding:0 5px;border-radius: 2px;">团家政折扣价</b>
                        </div>
                        <div class="jiathis_style pull-right" style="margin-top:55px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                        <div class="top_right_message">
                            <div class="transaction_success left">
                                <span><?php echo $success_count;?></span>
                                <span>交易成功</span>
                            </div>
                            <div class="top_comment left">
                                <span><?php echo $count;?></span>
                                <span>累计评论</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="service_time">
                    <p>服务时长</p>
                    <input type="hidden" id="service_time" name="service_time" value="1">
                    <div class="service-time-list">
                        <ul class="clearfix time-box">
                            <li class="active" service_time="一个月" data="1">
                                <div class="service-time-item"><span>一个月</span><b></b></div>
                            </li>
                            <li service_time="二个月" data="2">
                                <div class="service-time-item"><span>二个月</span><b></b></div>
                            </li>
                            <li service_time="三个月" data="3">
                                <div class="service-time-item"><span>三个月</span><b></b></div>
                            </li>
                            <li service_time="四个月" data="4">
                                <div class="service-time-item"><span>四个月</span><b></b></div>
                            </li>
                            <li service_time="五个月" data="5">
                                <div class="service-time-item"><span>五个月</span><b></b></div>
                            </li>
                            <li service_time="六个月" data="6">
                                <div class="service-time-item"><span>六个月</span><b></b></div>
                            </li>
                            <li service_time="七个月" data="7">
                                <div class="service-time-item"><span>七个月</span><b></b></div>
                            </li>
                            <li service_time="八个月" data="8">
                                <div class="service-time-item"><span>八个月</span><b></b></div>
                            </li>
                            <li service_time="九个月" data="9">
                                <div class="service-time-item"><span>九个月</span><b></b></div>
                            </li>
                            <li service_time="十个月" data="10">
                                <div class="service-time-item"><span>十个月</span><b></b></div>
                            </li>
                            <li service_time="十一个月" data="11">
                                <div class="service-time-item"><span>十一个月</span><b></b></div>
                            </li>
                            <li service_time="十二个月" data="12">
                                <div class="service-time-item"><span>十二个月</span><b></b></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['service_price']*$nurse['nurse_discount'] ?>';
                    var nurse_price='<?php echo $nurse['nurse_price'] ?>';
                </script>
                <script>
                    $(".time-box li").click(function () {
                             var service_time = $(this).attr('service_time');
                             var data=parseInt($(this).attr('data'));
                            var service_price_change=data*parseInt(service_price)-5*(data-1);
                            if(service_price_change<0){
                                service_price_change=0;
                            }
                            $("#service_price").val(service_price_change);
                            var total_price=parseInt(nurse_price)+service_price_change;
                            $("#total_price").val(total_price);
                            $(".service_price_change").html(service_price_change);
                             $('#service_time').val(data);
                             $(this).addClass('active');
                             $(this).siblings('li').removeClass('active');
                    });
                </script>
                <div class="message_btn">
<!--                    <span class="nurse_order"><a href="index.php?act=book&nurse_id=--><?php //echo $nurse['nurse_id'];?><!--">立即预约</a></span>-->
                    <span class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name" style="">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } else if($nurse['nurse_type']== 3) { ?>
            <div class="nurse_type left">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="service_days" name="service_days" value="1">
                    <input type="hidden" id="service_hours" name="service_hours" value="1">
                    <input type="hidden" id="service_mins" name="service_mins" value="0">
                    <div class="price-type-list">
                        <div class="nurse_clean">每小时<span>¥<s><?php echo $nurse['nurse_price'];?></s></span></div>
                        <div class="nurse_clean">折扣价<span>¥<strong><?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?></strong></span></div>
                        <div class="jiathis_style pull-right" style="margin-top:10px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                    </div>
                    <div class="top_right_message">
                        <div class="transaction_success left">
                            <span><?php echo $success_count;?></span>
                            <span>交易成功</span>
                        </div>
                        <div class="top_comment left">
                            <span><?php echo $count;?></span>
                            <span>累计评论</span>
                        </div>
                    </div>
                </div>
                <div class="service_time">
                    <p>服务时长</p>
                    <div class="time_slelct">
                        <div class="left" style="height:30px;line-height: 30px;">
                            一天
                        </div>
                        <div class="left">
                            <select class="hours"  name="hours" id="hours">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="left" style="height:30px;line-height: 30px;">
                            小时
                        </div>
                        <div class="left">
                            <select class="mins" name="mins" id="mins">
                                <option value="0">00</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="left" style="height:30px;line-height: 30px;">
                            分钟
                        </div>
                    </div>
                    <div class="service_days">
                        <div class="left" style="height:30px;line-height: 34px;">共雇佣</div>
                        <div class="left">
                            <input style="text-align: center;width:120px;" type="number" min="1" max="28" class="days" id="days" placeholder="请填入雇佣天数" value="1">
                        </div>
                        <div class="left" style="height:30px;line-height: 34px;">天</div>
                    </div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['nurse_price']*$nurse['nurse_discount'] ?>';
                </script>
                <script>
                    $("#hours").change(function () {
                        var hours=parseInt($(this).val());
                        $("#service_hours").val(hours);
                        var days=parseInt($("#service_days").val());
                        var mins=parseInt($("#service_mins").val());
                        var total_price=(parseInt(service_price)*hours+Math.floor(parseInt(service_price)*mins/60))*days;
                        $("#total_price").val(total_price);
                    });
                    $("#mins").change(function () {
                        var mins=parseInt($(this).val());
                        $("#service_mins").val(mins);
                        var hours=parseInt($("#service_hours").val());
                        var days=parseInt($("#service_days").val());
                        var total_price=(parseInt(service_price)*hours+Math.floor(parseInt(service_price)*mins/60))*days;
                        $("#total_price").val(total_price);
                    });
                    $("#days").bind('input propertychange',function () {
                        var days=$("#days").val();
                        if(days<=0){
                            days=1;
                            $(this).val(days);
                        }
                        if(days>=28){
                            days=28;
                            $(this).val(days);
                        }
                        $("#service_days").val(days);
                        var hours=parseInt($("#service_hours").val());
                        var mins=parseInt($("#service_mins").val());
                        var total_price=(parseInt(service_price)*hours+Math.floor(parseInt(service_price)*mins/60))*days;
                        $("#total_price").val(total_price);
                    });
                </script>
                <div class="message_btn">
                    <span class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name" style="">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } else if($nurse['nurse_type']== 4){ ?>
            <div class="nurse_type left">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="service_area" name="service_area" value="1">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <div class="price-type-list">
                        <div class="nurse_clean">每平方<span>¥<s><?php echo $nurse['nurse_price'];?></s></span></div>
                        <div class="nurse_clean">折扣价<span>¥<strong><?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?></strong></span></div>
                        <div class="jiathis_style pull-right" style="margin-top:10px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                    </div>
                    <div class="top_right_message">
                        <div class="transaction_success left">
                            <span><?php echo $success_count;?></span>
                            <span>交易成功</span>
                        </div>
                        <div class="top_comment left">
                            <span><?php echo $count;?></span>
                            <span>累计评论</span>
                        </div>
                    </div>
                </div>
                <div class="service_time">
                    <p>打扫面积</p>
                    <div class="nurse_clean_area"><input style="text-align: center;" type="number" min="1" id="clean_area" name="clean_area" placeholder="请填入需要打扫的准确面积" value="1">平方</div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['nurse_price']*$nurse['nurse_discount'] ?>';
                </script>
                <script>
                    $("#clean_area").bind('input propertychange',function () {
                        var clean_area=parseInt($(this).val());
                        if(clean_area<=0){
                            clean_area=1;
                            $(this).val(clean_area);
                        }
                        $("#service_area").val(clean_area);
                        var total_price=clean_area*parseInt(service_price);
                        $("#total_price").val(total_price);
                    });
                </script>
                <div class="message_btn">
                    <span class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name" style="">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } else if($nurse['nurse_type']==5 || $nurse['nurse_type'==6]) { ?>
            <div class="nurse_type left">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="service_days" name="service_days" value="26">
                    <div class="price-type-list">
                        <div class="nurse_clean">每月<span>¥<s><?php echo $nurse['nurse_price'];?></s></span></div>
                        <div class="nurse_clean">折扣价<span>¥<strong><?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?></strong></span></div>
                        <div class="jiathis_style pull-right" style="margin-top:10px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                    </div>
                    <div class="top_right_message">
                        <div class="transaction_success left">
                            <span><?php echo $success_count;?></span>
                            <span>交易成功</span>
                        </div>
                        <div class="top_comment left">
                            <span><?php echo $count;?></span>
                            <span>累计评论</span>
                        </div>
                    </div>
                </div>
                <div class="service_time">
                    <p>服务时长</p>
                    <input type="hidden" id="service_time" name="service_time" value="26">
                    <div class="service-time-list">
                        <ul class="clearfix time-box">
                            <li class="active" service_time="26">
                                <div class="service-time-item"><span>26天</span><b></b></div>
                            </li>
                            <li service_time="27">
                                <div class="service-time-item"><span>27天</span><b></b></div>
                            </li>
                            <li service_time="28">
                                <div class="service-time-item"><span>28天</span><b></b></div>
                            </li>
                            <li service_time="29">
                                <div class="service-time-item"><span>29天</span><b></b></div>
                            </li>
                            <li service_time="30">
                                <div class="service-time-item"><span>30天</span><b></b></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['nurse_price']*$nurse['nurse_discount'] ?>';
                </script>
                <script>
                    $(".time-box li").click(function () {
                        $(this).addClass("active").siblings().removeClass("active");
                        var service_time=parseInt($(this).attr('service_time'));
                        $("#service_days").val(service_time);
                        var item=Math.floor(parseInt(service_price)*service_time/26);
                        $(".nurse_clean span strong").html(item);
                        $("#total_price").val(item);
                    });
                </script>
                <div class="message_btn">
                    <span class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name" style="">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } else if($nurse['nurse_type']==7 || $nurse['nurse_type']==8) { ?>
            <div class="nurse_type left">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="machine_count" name="machine_count" value="1">
                    <div class="price-type-list">
                        <div class="nurse_clean">每次<span>¥<s><?php echo $nurse['nurse_price'];?></s></span></div>
                        <div class="nurse_clean">折扣价<span>¥<strong><?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?></strong></span></div>
                        <div class="jiathis_style pull-right" style="margin-top:10px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                    </div>
                    <div class="top_right_message">
                        <div class="transaction_success left">
                            <span><?php echo $success_count;?></span>
                            <span>交易成功</span>
                        </div>
                        <div class="top_comment left">
                            <span><?php echo $count;?></span>
                            <span>累计评论</span>
                        </div>
                    </div>
                </div>
                <div class="service_time">
                    <p>需要维修、拆装、疏通的设备数量</p>
                    <input type="hidden" id="service_time" name="service_time" value="1">
                    <div class="service-time-list">
                        <ul class="clearfix time-box">
                            <li class="active" service_time="1">
                                <div class="service-time-item"><span>1</span><b></b></div>
                            </li>
                            <li service_time="2">
                                <div class="service-time-item"><span>2</span><b></b></div>
                            </li>
                            <li service_time="3">
                                <div class="service-time-item"><span>3</span><b></b></div>
                            </li>
                            <li service_time="4">
                                <div class="service-time-item"><span>4</span><b></b></div>
                            </li>
                            <li service_time="5">
                                <div class="service-time-item"><span>5</span><b></b></div>
                            </li>
                            <li service_time="6">
                                <div class="service-time-item"><span>6</span><b></b></div>
                            </li>
                            <li service_time="7">
                                <div class="service-time-item"><span>7</span><b></b></div>
                            </li>
                            <li service_time="8">
                                <div class="service-time-item"><span>8</span><b></b></div>
                            </li>
                            <li service_time="9">
                                <div class="service-time-item"><span>9</span><b></b></div>
                            </li>
                            <li service_time="10">
                                <div class="service-time-item"><span>10</span><b></b></div>
                            </li>
                            <li service_time="11">
                                <div class="service-time-item"><span>11</span><b></b></div>
                            </li>
                            <li service_time="12">
                                <div class="service-time-item"><span>12</span><b></b></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['nurse_price']*$nurse['nurse_discount'] ?>';
                </script>
                <script>
                    $(".time-box li").click(function () {
                        $(this).addClass("active").siblings().removeClass("active");
                        var service_time=parseInt($(this).attr('service_time'));
                        $("#machine_count").val(service_time);
                        var total_price=service_time*parseInt(service_price);
                        $("#total_price").val(total_price);
                    });
                </script>
                <div class="message_btn">
                    <span class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name" style="">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } else if($nurse['nurse_type']==9 || $nurse['nurse_type']==10) { ?>
            <div class="nurse_type left">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="persons" name="persons" value="1">
                    <div class="price-type-list">
                        <div class="nurse_clean">每人<span>¥<s><?php echo $nurse['nurse_price'];?></s></span></div>
                        <div class="nurse_clean">折扣价<span>¥<strong><?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?></strong></span></div>
                        <div class="jiathis_style pull-right" style="margin-top:10px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                    </div>
                    <div class="top_right_message">
                        <div class="transaction_success left">
                            <span><?php echo $success_count;?></span>
                            <span>交易成功</span>
                        </div>
                        <div class="top_comment left">
                            <span><?php echo $count;?></span>
                            <span>累计评论</span>
                        </div>
                    </div>
                </div>
                <div class="service_time">
                    <p>人数</p>
                    <div style="margin:5px 0 5px 50px;">
                        共雇佣 <input type="number" min="1" id="person_count" name="person_count" value="1">人
                    </div>
                    <p>用车吨位</p>
                    <input type="hidden" id="service_time" name="service_time" value="0">
                    <input type="hidden" id="car_price" name="car_price" value="0">
                    <div class="service-time-list">
                        <ul class="clearfix time-box">
                            <li class="active" service_time="0" data="0">
                                <div class="service-time-item" style="padding: 2px 10px"><span>不用车</span><b></b></div>
                            </li>
                            <?php foreach ($nurse['car_weight_list'] as $k => $v) { ?>
                                <li service_time="<?php echo $v ?>" data="<?php echo $nurse['car_price_list'][$k] ?>">
                                    <div class="service-time-item" style="padding: 2px 10px"><span><?php echo $v ?>吨</span><b></b></div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['nurse_price']*$nurse['nurse_discount'] ?>';
                </script>
                <script>
                    $("#person_count").bind('input propertychange',function () {
                        var person_count=parseInt($(this).val());
                        if(person_count<=0){
                            person_count=1;
                        }
                        $("#persons").val(person_count);
                        var total_price=person_count*parseInt(service_price)+parseInt($("#car_price").val());
                        $("#total_price").val(total_price);
                    });
                    $(".time-box li").click(function () {
                        $(this).addClass("active").siblings().removeClass("active");
                        var service_time=$(this).attr('service_time');
                        $("#service_time").val(service_time);
                        var car_price=parseInt($(this).attr('data'));
                        $("#car_price").val(car_price);
                        var total_price=parseInt(service_price)*parseInt($("#persons").val())+car_price;
                        $("#total_price").val(total_price);
                    });
                </script>
                <div class="message_btn">
                    <span class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name" style="">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } else if($nurse['nurse_type']==11 || $nurse['nurse_type']==12) { ?>
            <div class="nurse_type left" style="height:470px;">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?>">
                    <input type="hidden" id="students_count" name="students_count" value="1">
                    <input type="hidden" id="service_time" name="service_time" value="1">
                    <div class="price-type-list">
                        <div class="nurse_clean">每人<span>¥<s><?php echo $nurse['nurse_price'];?></s></span></div>
                        <div class="nurse_clean">折扣价<span>¥<strong><?php echo $nurse['nurse_price']*$nurse['nurse_discount'];?></strong></span></div>
                        <div class="jiathis_style pull-right" style="margin-top:10px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                    </div>
                    <div class="top_right_message">
                        <div class="transaction_success left">
                            <span><?php echo $success_count;?></span>
                            <span>交易成功</span>
                        </div>
                        <div class="top_comment left">
                            <span><?php echo $count;?></span>
                            <span>累计评论</span>
                        </div>
                    </div>
                </div>
                <style>
                    .service-time-list .service-time-item{
                        padding:2px 10px;
                    }
                    .service-time-list li.active .service-time-item {
                        border: 1px solid #ff6905;
                        padding: 2px 10px;
                    }
                    .service-time-list li:hover .service-time-item{border: 1px solid #ff6905;padding: 2px 10px}
                </style>
                <div class="service_time" style="height:167px;">
                    <p style="color: #000;font-weight: 500;">服务时长</p>
                    <input type="hidden" id="service_time" name="service_time" value="一个月">
                    <div class="service-time-list" style="margin-bottom: 0;">
                        <ul class="clearfix time-box">
                            <li class="active" service_time="1" >
                                <div class="service-time-item"><span>一个月</span><b></b></div>
                            </li>
                            <li service_time="2">
                                <div class="service-time-item"><span>二个月</span><b></b></div>
                            </li>
                            <li service_time="3">
                                <div class="service-time-item"><span>三个月</span><b></b></div>
                            </li>
                            <li service_time="4">
                                <div class="service-time-item"><span>四个月</span><b></b></div>
                            </li>
                            <li service_time="5">
                                <div class="service-time-item"><span>五个月</span><b></b></div>
                            </li>
                            <li service_time="6">
                                <div class="service-time-item"><span>六个月</span><b></b></div>
                            </li>
                            <li service_time="7">
                                <div class="service-time-item"><span>七个月</span><b></b></div>
                            </li>
                            <li service_time="8">
                                <div class="service-time-item"><span>八个月</span><b></b></div>
                            </li>
                            <li service_time="9">
                                <div class="service-time-item"><span>九个月</span><b></b></div>
                            </li>
                            <li service_time="10">
                                <div class="service-time-item"><span>十个月</span><b></b></div>
                            </li>
                            <li service_time="11">
                                <div class="service-time-item"><span>十一个月</span><b></b></div>
                            </li>
                            <li service_time="12">
                                <div class="service-time-item"><span>十二个月</span><b></b></div>
                            </li>
                        </ul>
                    </div>
                    <div class="students_count_list">
                        <input type="hidden" id="students_count" name="students_count" value="1">
                        <?php if(empty($nurse['students_state'])) { ?>
                            <p style="margin-left: -15px;color: #000;font-weight: 500;">学生数量（该家政人员只辅导一个学生）</p>
                            <ul class="clearfix students-box">
                                <li class="active" students_count="1">
                                    <div class="students-item"><span>1</span><b></b></div>
                                </li>
                            </ul>
                        <?php } else { ?>
                            <?php if(empty($nurse['students_sale'])) { ?>
                                <p style="margin-left: -15px;color: #000;font-weight: 500;">学生数量</p>
                             <?php } else { ?>
                                <p style="margin-left: -15px;color: #000;font-weight: 500;">学生数量（该家政人员多于一个学生半价优惠）</p>
                             <?php } ?>
                            <ul class="clearfix students-box">
                                <li class="active" students_count="1">
                                    <div class="students-item"><span>1</span><b></b></div>
                                </li>
                                <li students_count="2">
                                    <div class="students-item"><span>2</span><b></b></div>
                                </li>
                                <li students_count="3">
                                    <div class="students-item"><span>3</span><b></b></div>
                                </li>
                                <li students_count="4">
                                    <div class="students-item"><span>4</span><b></b></div>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['nurse_price']*$nurse['nurse_discount'] ?>';
                    var students_sale='<?php echo $nurse['students_sale'] ?>';
                </script>
                <script>
                    $(".time-box li").click(function () {
                       $(this).addClass("active").siblings().removeClass("active");
                       var service_time=parseInt($(this).attr('service_time'));
                       $("#service_time").val(service_time);
                       var students_count=parseInt($("#students_count").val()-1);
                        if(students_sale==1){
                            var total_price=(parseInt(service_price)+parseInt(service_price)*0.5*students_count)*service_time;
                        }else{
                            var total_price=(parseInt(service_price)+parseInt(service_price)*students_count)*service_time;
                        }
                       $("#total_price").val(total_price);
                    });
                    $(".students-box li").click(function () {
                        $(this).addClass("active").siblings().removeClass("active");
                        var students_count=parseInt($(this).attr('students_count'));
                        $("#students_count").val(students_count);
                        var service_time=parseInt($("#service_time").val());
                        var count=students_count-1;
                        if(students_sale==1){
                            var total_price=(parseInt(service_price)+parseInt(service_price)*0.5*count)*service_time;
                        }else{
                            var total_price=(parseInt(service_price)+parseInt(service_price)*count)*service_time;
                        }
                        $("#total_price").val(total_price);
                    });
                </script>
                <div class="message_btn" style="height:40px;">
                    <span style="margin-top: 10px;" class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span style="margin-top: 10px;" class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="nurse_type left">
                <p><span><?php echo $nurse['service_type'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'];?><span></span></p>
                <div class="price_type">
                    <p>工资</p>
                    <input type="hidden" id="total_price" name="total_price" value="<?php echo $nurse['service_price']*$nurse['nurse_discount']+$nurse['nurse_price'] ?>">
                    <input type="hidden" id="nurse_price" name="nurse_price" value="<?php echo $nurse['nurse_price'] ?>">
                    <input type="hidden" id="service_price" name="service_price" value="<?php echo $nurse['service_price']*$nurse['nurse_discount'] ?>">
                    <div class="price-type-list">
                        <div class="price-type-item">服务费<span>¥ <s><small><?php echo $nurse['service_price'];?></small></s></span></div>
                        <div class="service_price_discount price-type-item">
                            折扣价<span> <small style="color: #ff6905;">¥ </small> <strong class="service_price_change" style="color: #ff6905;"><?php echo $nurse['service_price']*$nurse['nurse_discount'];?></strong> + 本月工资 <small style="color: #ff6905;">¥ <?php echo $nurse['nurse_price'] ?></small></span>
                            <b style="margin-left:10px;font-weight: normal;background: #ff6905;color:#fff;padding:0 5px;border-radius: 2px;">团家政折扣价</b>
                        </div>
                        <div class="jiathis_style pull-right" style="margin-top:55px;">
                            <a href="javascript:;" class="nurse_focus"><img style="margin:0 5px 2px 0" src="templates/images/nurse_focus.png" alt="">关注</a>
                        </div>
                        <div class="top_right_message">
                            <div class="transaction_success left">
                                <span><?php echo $success_count;?></span>
                                <span>交易成功</span>
                            </div>
                            <div class="top_comment left">
                                <span><?php echo $count;?></span>
                                <span>累计评论</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="service_time">
                    <p>服务时长</p>
                    <input type="hidden" id="service_time" name="service_time" value="1">
                    <div class="service-time-list">
                        <ul class="clearfix time-box">
                            <li class="active" service_time="一个月" data="1">
                                <div class="service-time-item"><span>一个月</span><b></b></div>
                            </li>
                            <li service_time="二个月" data="2">
                                <div class="service-time-item"><span>二个月</span><b></b></div>
                            </li>
                            <li service_time="三个月" data="3">
                                <div class="service-time-item"><span>三个月</span><b></b></div>
                            </li>
                            <li service_time="四个月" data="4">
                                <div class="service-time-item"><span>四个月</span><b></b></div>
                            </li>
                            <li service_time="五个月" data="5">
                                <div class="service-time-item"><span>五个月</span><b></b></div>
                            </li>
                            <li service_time="六个月" data="6">
                                <div class="service-time-item"><span>六个月</span><b></b></div>
                            </li>
                            <li service_time="七个月" data="7">
                                <div class="service-time-item"><span>七个月</span><b></b></div>
                            </li>
                            <li service_time="八个月" data="8">
                                <div class="service-time-item"><span>八个月</span><b></b></div>
                            </li>
                            <li service_time="九个月" data="9">
                                <div class="service-time-item"><span>九个月</span><b></b></div>
                            </li>
                            <li service_time="十个月" data="10">
                                <div class="service-time-item"><span>十个月</span><b></b></div>
                            </li>
                            <li service_time="十一个月" data="11">
                                <div class="service-time-item"><span>十一个月</span><b></b></div>
                            </li>
                            <li service_time="十二个月" data="12">
                                <div class="service-time-item"><span>十二个月</span><b></b></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <script>
                    var service_price='<?php echo $nurse['service_price']*$nurse['nurse_discount'] ?>';
                    var nurse_price='<?php echo $nurse['nurse_price'] ?>';
                </script>
                <script>
                    $(".time-box li").click(function () {
                        var service_time = $(this).attr('service_time');
                        var data=parseInt($(this).attr('data'));
                        var service_price_change=data*parseInt(service_price)-5*(data-1);
                        if(service_price_change<0){
                            service_price_change=0;
                        }
                        var total_price=parseInt(nurse_price)+service_price_change;
                        $("#total_price").val(total_price);
                        $(".service_price_change").html(service_price_change);
                        $('#service_time').val(data);
                        $(this).addClass('active');
                        $(this).siblings('li').removeClass('active');
                    });
                </script>
                <div class="message_btn">
                    <span class="nurse_order"><a href="javascript:;">立即预约</a></span>
                    <span class="tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
                </div>
                <div class="real_name" style="">
                    <span>资质</span>
                    <span><img src="templates/images/smrz.png" alt="">实名认证</span >
                    <span><a class="toimg" href="#img_show"><img src="templates/images/zzzs.png" alt="">资质证书</a></span>
                    <?php if($nurse['promise_state']==2) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三小时无理由退款</span>
                    <?php } else if($nurse['promise_state']==4) { ?>
                        <span><img height="16px" src="templates/images/three_days.jpg" alt="">三天无理由退款</span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="nurse_message_right left">
        <?php if(empty($nurse['agent_id'])) { ?>
            <div class="agent_box">

            </div>
        <?php } else { ?>
            <div class="agent_box">
                <?php if($agent['agent_score']<=2400) { ?>
                    <h4>——星星家政——</h4>
                <?php } else if($agent['agent_score']>2400 && $agent['agent_score']<=30000) { ?>
                    <h4>——月亮家政——</h4>
                <?php } else if($agent['agent_score']>30000 && $agent['agent_score']<=80000) { ?>
                    <h4>——太阳家政——</h4>
                <?php } else if($agent['agent_score']>80000) { ?>
                    <h4>——皇冠家政——</h4>
                <?php } ?>
                <h3><?php echo $agent['agent_name'];?></h3>
                <p>等&nbsp;&nbsp;&nbsp;级：&nbsp;&nbsp;<img style="margin: 0 0 3px 0" height="16px" src="<?php echo $agent_grade['grade_icon'] ?>"></p>
                <p>联&nbsp;&nbsp;&nbsp;系：&nbsp;&nbsp;<a href="javascript:;" class="lianxi2"><img style="margin:0 5px 3px 0; " src="templates/images/lianxi.png" alt=""><span style="background:#feeba0;color:red;border-radius: 3px;">和我联系</span></a></p>
                <p>资&nbsp;&nbsp;&nbsp;质：&nbsp;&nbsp;<a href="javascript:;"><img style="margin:0 5px 4px 0; " src="templates/images/zizhi.png" alt="">点击查看</a></p>
                <p>机构号：&nbsp;&nbsp;<?php echo $agent['agent_id'];?></p>
                <div class="agent_box_btn">
                    <span><a href="index.php?act=agent_show&agent_id=<?php echo $nurse['agent_id'];?>">进入机构</a></span>
                    <span class="agent_focus"><a href="javascript:;">关注机构</a></span>
                </div>
            </div>
        <?php } ?>
        <?php if($nurse['nurse_type']== 3 || $nurse['nurse_type']== 4 || $nurse['nurse_type']== 5 || $nurse['nurse_type']== 6 || $nurse['nurse_type']== 7 || $nurse['nurse_type']== 8 || $nurse['nurse_type']== 9 || $nurse['nurse_type']== 10) { ?>
            <div style="width:200px;height:242px;background: url(templates/images/point_bg.jpg) no-repeat center;overflow: hidden;">
                <div class="agent_point">
                    <h5>温馨提示</h5>
                    <div style="overflow: hidden;position: relative;">
                        <img style="margin:3px 3px 0 5px;position: absolute;"  src="templates/images/starO.png" alt="">
                        <p>请您在付款前与家政机构或家政人员详细沟通，请确保在您付款后家政人员可以立即为您服务</p>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div style="width:200px;height:242px;background: url(templates/images/point_bg.jpg) no-repeat center;overflow: hidden;">
                <div class="agent_point">
                    <h5>温馨提示</h5>
                    <div style="overflow: hidden;position: relative;">
                        <img style="margin:3px 3px 0 5px;position: absolute;"  src="templates/images/starO.png" alt="">
                        <p>选择的月份越长越优惠</p>
                    </div>
                    <div style="overflow: hidden;position: relative;">
                        <img style="margin:3px 3px 0 5px;position: absolute;"  src="templates/images/starO.png" alt="">
                        <p>请在付款前和家政机构或家政人员进行详细的沟通，确保服务到位</p>
                    </div>
                    <div style="overflow: hidden;position: relative;">
                        <img style="margin:3px 3px 0 5px;position: absolute;"  src="templates/images/starO.png" alt="">
                        <p>选择一个月以上家政服务，请确保您的账户余额充足，我们将在交易满一个月后自动扣除下个月的工资</p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div id="nurse_bottom">
    <div class="nurse_bottom_banner left">
        <p><?php echo $agent['agent_name'];?>欢迎您</p>
    </div>
    <div class="nurse_bottom_center left">
        <ul id="choose_top">
            <li class="active myself_resume">个人简介</li>
            <li class="total_evaluate">累计评价&nbsp;&nbsp;<span><?php echo $count;?></span></li>
            <li></li>
            <span style="color:#fff;margin:4px 10px 0 0;display: none;" class="choose_add right tool-favorite favorite-add"><img style="margin: 0 15px 2px 0;" src="templates/images/service_car.png" alt="">加入服务车</span>
        </ul>
        <div class="resume_box">
            <div class="basics">
                <div class="resume_box-title">
                    <span>基本资料</span>
                </div>
                <div>
                    <p class="left">称呼：<?php echo $nurse['nurse_nickname'] ?></p> <h4 class="left">年龄：<?php echo $nurse['nurse_age'] ?></h4>
                </div>
                <div>
                    <?php if($nurse['nurse_sex']==1) { ?>
                        <p class="left">性别：男</p>
                    <?php } else if($nurse['nurse_sex']==2) { ?>
                        <p class="left">性别：女</p>
                    <?php } else { ?>
                        <p class="left">性别：保密</p>
                    <?php } ?>
                    <h4 class="left">籍贯：<?php echo $nurse['birth_cityname'] ?></h4>
                </div>
<!--                <div>-->
<!--                    <p class="left">学历：高中</p>-->
<!--                </div>-->
            </div>
            <div class="work">
                <div class="resume_box-title">
                    <span>工作经历</span>
                </div>
                <?php if(empty($nurse['nurse_work_exe'])) { ?>
                    <div>
                        <p>该家政人员未添加工作经历</p>
                    </div>
                <?php } else { ?>
                    <?php foreach ($nurse['nurse_work_exe'] as $k => $v) { ?>
                        <div>
                            <p><?php echo $v ?></p>
                        </div>
                     <?php } ?>
                <?php } ?>
            </div>
            <div class="myself">
                <div class="resume_box-title">
                    <span>自我评价</span>
                </div>
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nurse['nurse_content'] ?>
                </div>
            </div>
            <style>
                .base-pic li {
                     float: left;
                     width: 150px;
                     height: 150px;
                     border: 1px solid #ccc;
                     margin: 0 10px 20px 10px;
                }
                .base-pic li img{width:100%;height:100%;}
            </style>
            <div class="img_show" id="img_show">
                <div class="resume_box-title">
                    <span>图片展示</span>
                </div>
                <div class="con-bd">
                    <ul class="base-pic clearfix">
                        <?php foreach($nurse['nurse_qa_image'] as $key => $value) { ?>
                            <li>
                                <a href="javascript:;"><img class="zoomify" src="<?php echo $value;?>"></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="evaluate_box" style="display: none;" >
            <div class="comment_choose">
                <span>信用评价</span>
                <span><input type="radio" name="comment_choose" value="all" checked>全部评价(<?php echo $count;?>)</span>
               <span> <input type="radio" name="comment_choose" value="good">好评(<?php echo $good_count;?>)</span>
                <span><input type="radio" name="comment_choose" value="middle">中评(<?php echo $middle_count;?>)</span>
                <span><input type="radio" name="comment_choose" value="bad">差评(<?php echo $bad_count;?>)</span>
                <span><input type="radio" name="comment_choose" value="hasimg">有图(<?php echo $hasImg_count;?>)</span>
                <span class="content-choose"><input style="display: none;" class="hasContent" id="hasContent" type="checkbox" value="hasContent" checked>
                <label class="hasContentLabel" for="hasContent"></label>有内容</span>
                <select name="" id="sort">
                    <option value="">推荐排序</option>
                    <option value="score">好评优先</option>
                    <option value="time">时间优先</option>
                </select>
            </div>
           <div class="comment_details">
               <?php foreach($comment_list as $key => $value) { ?>
                   <div class="comment_list">
                        <div class="comment_list_content">
                            <p><?php echo $value['comment_content'];?></p>
                            <div class="order_message">
                                <?php if(!empty($value['comment_image'])) { ?>
                                    <div class="commit-img comment_img left">
                                        <ul>
                                            <?php foreach($value['comment_image'] as $subkey => $subvalue) { ?>
                                                <li class="left"><img class="zoomify" width="60px" height="60px" src="<?php echo $subvalue;?>"></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <div class="comment_user right">
                                    <p><?php echo $member_list[$value['member_id']]['member_phone'];?>(匿名)</p>
                                    <span><img src="templates/images/star.png" alt=""></span>
                                </div>
                                <div class="right" style="margin-right:30px;">
                                    服务时长：
                                    <?php if(!empty($book_list[$value['book_id']]['work_duration'])) { ?>
                                        <?php echo $book_list[$value['book_id']]['work_duration'] ?>个月
                                    <?php } else if(!empty($book_list[$value['book_id']]['work_duration_days'])) { ?>
                                        <?php echo $book_list[$value['book_id']]['work_duration_days'] ?>天
                                    <?php } else if(!empty($book_list[$value['book_id']]['work_duration_hours'])) { ?>
                                        <?php echo $book_list[$value['book_id']]['work_duration_hours'] ?>小时
                                    <?php } else if(!empty($book_list[$value['book_id']]['work_duration_mins'])) { ?>
                                        <?php echo $book_list[$value['book_id']]['work_duration_mins'] ?>分钟
                                    <?php } ?>
                                </div>
                            </div>
                            <h5><?php echo date('Y年m月d日 H:i', $value['comment_time']);?></h5>
                        </div>
                   </div>
               <?php } ?>
               <div class="multi-box"><?php echo $multi;?></div>
           </div>
        </div>
    </div>
    <style>
        .multi-box{
            position: absolute;
            right:20px;
            bottom:10px;
        }
    </style>
    <div class="nurse_bottom_right left">
        <p></p>
    </div>
</div>



<div class="modal-wrap w-400" id="phone-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-title"></h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default nurse_state">无法接通</a>
        <a class="btn btn-success nurse_state">已经工作</a>
        <a class="btn btn-primary nurse_state">通话成功</a>
        <!-- <a class="btn btn-primary" href="javascript:Custombox.close();">确定</a>-->
    </div>
</div>
<div class="modal-wrap w-400" id="login-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-title">您还未登录了</h3>
                <div class="tip-hint">3 秒后页面跳转</div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="index.php?act=login">确定</a>
    </div>
</div>
<div class="modal-wrap w-400" id="alert-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-title"></h3>
                <div class="tip-hint">3 秒后页面关闭</div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="javascript:Custombox.close();">确定</a>
    </div>
</div>
<div class="modal-wrap w-400" id="focus-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-title">关注成功</h3>
                <div class="tip-hint">3 秒后页面关闭</div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="javascript:Custombox.close();">确定</a>
    </div>
</div>
<div class="alert-box" style="display:none;">
    <div class="alert alert-danger tip-title"></div>
</div>
<div class="modal-wrap w-400" id="login-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-title">您还未登录了</h3>
                <div class="tip-hint">3 秒后页面跳转</div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="index.php?act=login">确定</a>
    </div>
</div>
<script type="text/javascript">
    var nurse_id = '<?php echo $nurse['nurse_id'];?>';
    var agent_id='<?php echo $agent['agent_id'];?>';
    var nurse_type='<?php echo $nurse['nurse_type'] ?>';
    var nurse_discount='<?php echo $nurse['nurse_discount'] ?>';
</script>
    <script>
        $(".nurse_order").click(function () {
            var work_duration,work_duration_days,work_duration_hours,work_duration_mins,work_area,work_person,work_machine,work_cars,car_price,work_students,service_price,nurse_price,total_price=0;
            var collect_details='';
            if(nurse_type==1 || nurse_type==2){
                service_price=$("#service_price").val();
                nurse_price=$("#nurse_price").val();
                work_duration=$("#service_time").val();
                total_price=$("#total_price").val();
                collect_details+=work_duration+'月服务费'+'+本月工资';
            }else if(nurse_type==3){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_duration_days=$("#service_days").val();
                work_duration_hours=$("#service_hours").val();
                work_duration_mins=$("#service_mins").val();
                collect_details+='每天'+work_duration_hours+'小时'+work_duration_mins+'分'+',共'+work_duration_days+'天';
            }else if(nurse_type==4){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_area=$("#service_area").val();
                collect_details+='共'+work_area+'平方';
            }else if(nurse_type==5 || nurse_type==6){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_duration_days=$("#service_days").val();
                collect_details+='共'+work_duration_days+'天';
            }else if(nurse_type==7 || nurse_type==8){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_machine=$("#machine_count").val();
                collect_details+='共'+work_machine+'台设备';
            }else if(nurse_type==9 || nurse_type==10){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_person=$("#persons").val();
                work_cars=$("#service_time").val();
                car_price=$("#car_price").val();
                collect_details+='共'+work_person+'人';
                if(work_cars!=0 && work_cars!=undefined && work_cars!=''){
                    collect_details+="+"+work_cars+'吨车一辆';
                }
            }else if(nurse_type==11 || nurse_type==12){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_students=$("#students_count").val();
                work_duration=$("#service_time").val();
                collect_details+='共'+work_students+'个学生'+'+'+work_duration+'个月';
            }else{
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                service_price=$("#service_price").val();
                work_duration=$("#service_time").val();
                collect_details+=work_duration+'月服务费'+'+本月工资';
            }
            var submitData={
                'nurse_id':nurse_id,
                'agent_id':agent_id,
                'nurse_type':nurse_type,
                'work_duration':work_duration,
                'work_duration_days':work_duration_days,
                'work_duration_hours':work_duration_hours,
                'work_duration_mins':work_duration_mins,
                'work_area':work_area,
                'work_person':work_person,
                'work_machine':work_machine,
                'work_cars':work_cars,
                'car_price':car_price,
                'work_students':work_students,
                'service_price':service_price,
                'nurse_price':nurse_price,
                'nurse_discount':nurse_discount,
                'total_price':total_price,
                'collect_details':collect_details
            };
            console.log(submitData);
            $.ajax({
                type : 'POST',
                url : 'index.php?act=nurse&op=collect_add',
                data : submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType : 'json',
                success : function(data){
                    console.log(data);
                    if(data.done == 'true') {
                        window.location.href='index.php?act=place_order&collect_id='+data.collect_id;
                    } else {
                        showalert(data.msg);
                    }
                },
                timeout : 15000,
                error : function(xhr, type){
                    showalert( '网路不稳定，请稍候重试');
                }
             });
        });
    </script>
    <script>
        $(".favorite-add").click(function () {
            var work_duration,work_duration_days,work_duration_hours,work_duration_mins,work_area,work_person,work_machine,work_cars,car_price,work_students,service_price,nurse_price,total_price=0;
            var collect_details='';
            if(nurse_type==1 || nurse_type==2){
                service_price=$("#service_price").val();
                nurse_price=$("#nurse_price").val();
                work_duration=$("#service_time").val();
                total_price=$("#total_price").val();
                collect_details+=work_duration+'月服务费'+'+本月工资';
            }else if(nurse_type==3){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_duration_days=$("#service_days").val();
                work_duration_hours=$("#service_hours").val();
                work_duration_mins=$("#service_mins").val();
                collect_details+='每天'+work_duration_hours+'小时'+work_duration_mins+'分'+',共'+work_duration_days+'天';
            }else if(nurse_type==4){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_area=$("#service_area").val();
                collect_details+='共'+work_area+'平方';
            }else if(nurse_type==5 || nurse_type==6){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_duration_days=$("#service_days").val();
                collect_details+='共'+work_duration_days+'天';
            }else if(nurse_type==7 || nurse_type==8){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_machine=$("#machine_count").val();
                collect_details+='共'+work_machine+'台设备';
            }else if(nurse_type==9 || nurse_type==10){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_person=$("#persons").val();
                work_cars=$("#service_time").val();
                car_price=$("#car_price").val();
                collect_details+='共'+work_person+'人';
                if(work_cars!=0 && work_cars!=undefined && work_cars!=''){
                    collect_details+="+"+work_cars+'吨车一辆';
                }
            }else if(nurse_type==11 || nurse_type==12){
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                work_students=$("#students_count").val();
                work_duration=$("#service_time").val();
                collect_details+='共'+work_students+'个学生'+'+'+work_duration+'个月';
            }else{
                total_price=$("#total_price").val();
                nurse_price=$("#nurse_price").val();
                service_price=$("#service_price").val();
                work_duration=$("#service_time").val();
                collect_details+=work_duration+'月服务费'+'+本月工资';
            }
            var submitData={
                'nurse_id':nurse_id,
                'agent_id':agent_id,
                'nurse_type':nurse_type,
                'work_duration':work_duration,
                'work_duration_days':work_duration_days,
                'work_duration_hours':work_duration_hours,
                'work_duration_mins':work_duration_mins,
                'work_area':work_area,
                'work_person':work_person,
                'work_machine':work_machine,
                'work_cars':work_cars,
                'car_price':car_price,
                'work_students':work_students,
                'service_price':service_price,
                'nurse_price':nurse_price,
                'nurse_discount':nurse_discount,
                'total_price':total_price,
                'collect_details':collect_details
            };
            console.log(submitData);
            $.ajax({
                type : 'POST',
                url : 'index.php?act=nurse&op=favourite_add',
                data : submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType : 'json',
                success : function(data){
                    console.log(data);
                    if(data.done == 'true') {
                        showprompt('收藏成功');
                    } else {
                        showalert(data.msg);
                    }
                },
                timeout : 15000,
                error : function(xhr, type){
                    showalert( '网路不稳定，请稍候重试');
                }
            });
        });
    </script>
    <script>
        $(function(){
            var mainOffsetTop = $("#choose_top").offset().top;
            var mainHeight = $("#choose_top").height();
            var winHeight = $(window).height();
            $(window).scroll(function(){
                var winScrollTop = $(window).scrollTop();
                if(winScrollTop > mainOffsetTop + mainHeight){
                    $("#choose_top").addClass("fix");
                    $(".choose_add").show();
                }else{
                    console.log(2);
                    $("#choose_top").removeClass("fix");
                    $(".choose_add").hide();
                }
            })
        });
    </script>
<link href="templates/css/zoomify.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="templates/js/zoomify.js"></script>
<script type="text/javascript" src="templates/js/home/nurse.js"></script>

<script>
    var page = '<?php echo $page;?>';
</script>
<?php include(template('common_footer'));?>
<script>
    $('.search-btn').click(function(){
        var keyword=$('#keywords').val();
        window.open('index.php?act=index&op=nurse&keyword='+keyword);
    })
    $(function(){
        document.onkeydown = function(e){
            var ev = document.all ? window.event : e;
            if(ev.keyCode==13) {
                var keyword=$("#keywords").val();
                if(keyword!=''){
                    window.open('index.php?act=index&op=nurse&keyword='+keyword);
                }
            }
        }
    });
</script>
<!--<script src="templates/3rd/jquery-1.11.3.min.js"></script>-->
<script src="templates/im/js/config.js"></script>
<script src="templates/im/js/md5.js"></script>
<script src="templates/im/js/util.js"></script>
<script>
    var member_id='<?php echo $this->member_id ?>';
    var a='<?php echo $this->member['yx_accid'] ?>';
    var b='<?php echo $this->member['yx_token'] ?>';
    var c='<?php echo $nurse_accid['yx_accid'] ?>';
    var d='<?php echo $agent_accid['yx_accid'] ?>';
    console.log(c);
    console.log(d);
    $(".lianxi").click(function () {
        if(member_id==0 || member_id=='' || member_id==undefined){
            Custombox.open({
                target : '#login-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300
            });
            setTimeout(function () {
                window.location.href='index.php?act=login';
            },3000)
        }
    });
    $(".lianxi2").click(function () {
        if(member_id==0 || member_id=='' || member_id==undefined){
            Custombox.open({
                target : '#login-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300
            });
            setTimeout(function () {
                window.location.href='index.php?act=login';
            },3000)
        }
    });
</script>
<script>
    var Login = {
        init: function() {
            this.initNode();
            this.showNotice();
            this.initAnimation();
            this.addEvent();
        },

        initNode: function() {	// 初始化节点
            this.$account = $('#j-account');
            this.$pwd = $('#j-secret');
            this.$errorMsg = $('#j-errorMsg');
            this.$loginBtn = $('.lianxi');
            this.$footer = $('#footer');
        },

        initAnimation: function() {	// 添加动画
            var $wrapper = $('#j-wrapper'),
                wrapperClass = $wrapper.attr('class');
            $wrapper.addClass('fadeInDown animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).removeClass().addClass(wrapperClass);
            });
        },

        /**
         * 如果浏览器非IE10,Chrome, FireFox, Safari, Opera的话，显示提示
         */
        showNotice: function() {
            var browser = this.getBrowser(),
                temp = browser.split(' '),
                appname = temp[0],
                version = temp[1];
            if (['msie', 'firefox', 'opera', 'safari', 'chrome'].contains(appname)) {
                if (appname == 'msie' && version < 10) {
                    this.$footer.find('p').removeClass('hide');
                }
            } else {
                this.$footer.find('p').removeClass('hide');
            }
        },

        addEvent: function() {	// 绑定事件
            var that = this;
//            this.$loginBtn.on('click', this.validate.bind(this));
            this.$loginBtn.on('click', function() {
                that.validate();
            });
//            $(document).on('click', function() {
//                console.log(1);
//                var ev = e || window.event;
//                if (ev.keyCode === 13) {
//                    that.validate();
//                }
//            });
        },

        validate: function() {	// 登录验证
            var that = this,
                account = a,
                pwd = b,
                errorMsg = '';
            if (account.length === 0) {
                errorMsg = '帐号不能为空';
            } else if (!pwd || pwd.length < 6) {
                errorMsg = '密码长度至少6位';
            } else {
//                that.$loginBtn.html('登录中...').attr('disabled', 'disabled');
                that.requestLogin.call(that, account, pwd);
//                that.$loginBtn.html('登录').removeAttr('disabled');
            }
            that.$errorMsg.html(errorMsg).removeClass('hide');  // 显示错误信息
            return false;
        },
        //这里做了个伪登录方式（实际上是把accid，token带到下个页面连SDK在做鉴权）
        //一般应用服务器的应用会有自己的登录接口
        requestLogin: function(account, pwd) {
            setCookie('uid',account.toLocaleLowerCase());
            //自己的appkey就不用加密了
            // setCookie('sdktoken',pwd);
            setCookie('sdktoken',pwd);
            setCookie('toID',c);
//            window.location.href = 'templates/im/main.html';
//            window.location.href = './main.html';
            window.open("templates/im/main.html");

        },
        /**
         * 获取浏览器的名称和版本号信息
         */
        getBrowser: function() {
            var browser = {
                msie: false,
                firefox: false,
                opera: false,
                safari: false,
                chrome: false,
                netscape: false,
                appname: 'unknown',
                version: 0
            }, ua = window.navigator.userAgent.toLowerCase();
            if (/(msie|firefox|opera|chrome|netscape)\D+(\d[\d.]*)/.test(ua)) {
                browser[RegExp.$1] = true;
                browser.appname = RegExp.$1;
                browser.version = RegExp.$2;
            } else if (/version\D+(\d[\d.]*).*safari/.test(ua)){ // safari
                browser.safari = true;
                browser.appname = 'safari';
                browser.version = RegExp.$2;
            }
            return browser.appname + ' ' + browser.version;
        }
    };
    Login.init();
</script>
<script>
    var Login = {
        init: function() {
            this.initNode();
            this.showNotice();
            this.initAnimation();
            this.addEvent();
        },

        initNode: function() {	// 初始化节点
            this.$account = $('#j-account');
            this.$pwd = $('#j-secret');
            this.$errorMsg = $('#j-errorMsg');
            this.$loginBtn = $('.lianxi2');
            this.$footer = $('#footer');
        },

        initAnimation: function() {	// 添加动画
            var $wrapper = $('#j-wrapper'),
                wrapperClass = $wrapper.attr('class');
            $wrapper.addClass('fadeInDown animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).removeClass().addClass(wrapperClass);
            });
        },

        /**
         * 如果浏览器非IE10,Chrome, FireFox, Safari, Opera的话，显示提示
         */
        showNotice: function() {
            var browser = this.getBrowser(),
                temp = browser.split(' '),
                appname = temp[0],
                version = temp[1];
            if (['msie', 'firefox', 'opera', 'safari', 'chrome'].contains(appname)) {
                if (appname == 'msie' && version < 10) {
                    this.$footer.find('p').removeClass('hide');
                }
            } else {
                this.$footer.find('p').removeClass('hide');
            }
        },

        addEvent: function() {	// 绑定事件
            var that = this;
//            this.$loginBtn.on('click', this.validate.bind(this));
            this.$loginBtn.on('click', function() {
                that.validate();
            });
//            $(document).on('click', function() {
//                console.log(1);
//                var ev = e || window.event;
//                if (ev.keyCode === 13) {
//                    that.validate();
//                }
//            });
        },

        validate: function() {	// 登录验证
            var that = this,
                account = a,
                pwd = b,
                errorMsg = '';
            if (account.length === 0) {
                errorMsg = '帐号不能为空';
            } else if (!pwd || pwd.length < 6) {
                errorMsg = '密码长度至少6位';
            } else {
//                that.$loginBtn.html('登录中...').attr('disabled', 'disabled');
                that.requestLogin.call(that, account, pwd);
//                that.$loginBtn.html('登录').removeAttr('disabled');
            }
            that.$errorMsg.html(errorMsg).removeClass('hide');  // 显示错误信息
            return false;
        },
        //这里做了个伪登录方式（实际上是把accid，token带到下个页面连SDK在做鉴权）
        //一般应用服务器的应用会有自己的登录接口
        requestLogin: function(account, pwd) {
            setCookie('uid',account.toLocaleLowerCase());
            //自己的appkey就不用加密了
            // setCookie('sdktoken',pwd);
            setCookie('sdktoken',pwd);
            setCookie('toID',d);
//            window.location.href = 'templates/im/main.html';
//            window.location.href = './main.html';
            window.open("templates/im/main.html");

        },
        /**
         * 获取浏览器的名称和版本号信息
         */
        getBrowser: function() {
            var browser = {
                msie: false,
                firefox: false,
                opera: false,
                safari: false,
                chrome: false,
                netscape: false,
                appname: 'unknown',
                version: 0
            }, ua = window.navigator.userAgent.toLowerCase();
            if (/(msie|firefox|opera|chrome|netscape)\D+(\d[\d.]*)/.test(ua)) {
                browser[RegExp.$1] = true;
                browser.appname = RegExp.$1;
                browser.version = RegExp.$2;
            } else if (/version\D+(\d[\d.]*).*safari/.test(ua)){ // safari
                browser.safari = true;
                browser.appname = 'safari';
                browser.version = RegExp.$2;
            }
            return browser.appname + ' ' + browser.version;
        }
    };
    Login.init();
</script>