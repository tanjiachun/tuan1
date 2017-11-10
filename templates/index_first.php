<?php include(template('common_header'));?>
<style>
    .conwp .top-logo{width:200px;}
    .searching{margin-top: 30px;margin-bottom: 30px;}

</style>
<div class="conwp clearfix searching">
    <h1 class="top-logo left">
        <a href="index.php"><img src="templates/images/logo.png"></a>
    </h1>
    <div class="search left">
        <div class="search-box-top">
            <span class="bg s_ipt_wr iptfocus quickdelete-wrap">
                <input style="width:600px;" type="text" id="keywords" class="itxt" placeholder="搜索您需要的服务">
            </span>
            <span class="s_btn_wr">
                <input type="submit" id="search-btn" value="搜索" class="search-btn bg s_btn">
            </span>
        </div>
        <ul class="search_even">
            <li><a target="_blank" href="index.php?act=index&op=nurse&service_type=衣物洗护">衣物洗护</a></li>
            <li><a target="_blank" href="index.php?act=index&op=nurse&service_type=钟点医护">钟点医护</a></li>
            <li><a target="_blank" href="index.php?act=index&op=nurse&service_type=开锁修锁">开锁修锁</a></li>
            <li><a target="_blank" href="index.php?act=index&op=nurse&service_type=送花服务">送花服务</a></li>
        </ul>
    </div>
    <div class="top-tool left">
        <span class="city-opr" style="color:#000;" onclick="window.location.href='index.php?act=city'"><i class="iconfont icon-city"></i><em><?php echo $this->district['district_name'];?></em>[切换城市]</span>
    </div>
</div>
<div class="sao-app">
    <span>手机APP</span><span class="close-app">✖</span>
    <img src="templates/images/sao-app.jpg" width="80px" height="80px" alt="">
</div>
</div>

<div id="allBody">
    <div class="all_search">
        <div class="all_search_center">
            <div class="left allTypes">
                <span>全部分类</span>
                <img style="padding-top:4px; " class="right" src="templates/images/moreType.png" alt="">
            </div>
            <div id="topBanner left">
                <b class="left"><img src="templates/images/hotSearch.png" alt=""></b>
                <ul class="left hotSearch">
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=单次保洁" >单次保洁</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=住家保姆" >住家保姆</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=家电清洗" >家电清洗</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=月搜护理" >月嫂护理</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=电器维修" >电器维修</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=搬家速运" >搬家速运</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=小学家教" >小学家教</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=全天医护" >全天医护</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=长期保洁" >长期保洁</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=做饭保姆" >做饭保姆</a></li>
                    <li class="left"><a target="_blank" href="index.php?act=index&op=nurse&service_type=管道疏通" >管道疏通</a></li>
                </ul>
            </div>
        </div>
    </div>
<!--    <div class="full-banner">-->
<!--        <!-- Swiper -->-->
<!--        <div class="swiper-container" id="swiper-banner">-->
<!--            <div class="swiper-wrapper">-->
<!--                --><?php //foreach($this->setting['banner_image'] as $key => $value) { ?>
<!--                --><?php //if(!empty($value)) { ?>
<!--                <div class="swiper-slide" style="background-image:url(--><?php //echo $value;?>/*);">*/
/*                    <a href="*/<?php //echo $this->setting['banner_url'][$key];?><!--"></a>-->
<!--                </div>-->
<!--                --><?php //} ?>
<!--                --><?php //} ?>
<!--            </div>-->
<!--            <div class="swiper-pagination swiper-banner"></div>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="jq22-container">-->
<!--        <article class="container">-->
<!--            <section>-->
<!--                <figure id="responsiveness" class="swipslider" style="padding-top: 420px;">-->
<!--                    <ul class="sw-slides">-->
<!--                        --><?php //foreach ($this->setting['banner_image'] as $key => $value) { ?>
<!--                            <li class="sw-slide">-->
<!--                                <a href="--><?php //echo $this->setting['banner_url'][$key] ?><!--"><img src="--><?php //echo $value ?><!--" alt=""></a>-->
<!--                            </li>-->
<!--                        --><?php //} ?>
<!---->
<!--                    </ul>-->
<!--                </figure>-->
<!--            </section>-->
<!--        </article>-->
<!--    </div>-->

    <script type="text/javascript" src="templates/js/swipeslider.min.js"></script>
    <script type="text/javascript">
        $(window).load(function() {
            $('#responsiveness').swipeslider();

        });
    </script>

    <div id="centerContainer">
        <div id="leftAsider" class="left">
            <div class="assortment">

                <ul class="nav_banner">
                    <li>
                        <img src="templates/images/i1.png" alt="">
                        <span><a target="_blank target=_blank" href="index.php?act=index&op=nurse&nurse_type=1">职业保姆</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=2">涉外保姆</a></span>
                        <i></i>
                    </li>
                    <li>
                        <img src="templates/images/i2.png" alt="">
                        <span><a target="_blank" href="index.php?act=index&op=nurse&nurse_type=3">钟点服务</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=4">保洁服务</a></span>
                        <i></i>
                    </li>
                    <li>
                        <img src="templates/images/i3.png" alt="">
                        <span><a target="_blank" href="index.php?act=index&op=nurse&nurse_type=5">月嫂保育</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=6">育婴早教</a></span>
                        <i></i>
                    </li>
                    <li>
                        <img src="templates/images/i4.png" alt="">
                        <span><a target="_blank" href="index.php?act=index&op=nurse&nurse_type=7">维修安装</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=8">清洗疏通</a></span>
                        <i></i>
                    </li>
                    <li>
                        <img src="templates/images/i5.png" alt="">
                        <span><a target="_blank" href="index.php?act=index&op=nurse&nurse_type=9">搬家服务</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=10">设备搬运</a></span>
                        <i></i>
                    </li>
                    <li>
                        <img src="templates/images/i6.png" alt="">
                        <span><a target="_blank" href="index.php?act=index&op=nurse&nurse_type=11">家庭外教</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=12">家庭辅导</a></span>
                        <i></i>
                    </li>
                    <li>
                        <img src="templates/images/i7.png" alt="">
                        <span><a target="_blank" href="index.php?act=index&op=nurse&nurse_type=13">陪护医护</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=14">老年照顾</a></span>
                        <i></i>
                    </li>
                    <li>
                        <img src="templates/images/i8.png" alt="">
                        <span><a target="_blank" href="index.php?act=index&op=nurse&nurse_type=15">管家服务</a>/<a target="_blank" href="index.php?act=index&op=nurse&nurse_type=16">高级家教</a></span>
                        <i></i>
                    </li>
                </ul>
                <div class="area_banner">
                </div>
            </div>
            <div style="margin-top:29px;">
                <a href="<?php echo $this->setting['banner_left_url'];?>"><img class="left" src="<?php echo $this->setting['banner_left_image'];?>" alt="" width="200" height="240px;"></a>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                $(".hotScroll li a").each(function(){
                    var maxwidth=11;
                    if($(this).text().length>maxwidth){
                        $(this).text($(this).text().substring(0,maxwidth));
                        $(this).html($(this).html()+'--');
                    }
                });
            });
        </script>
        <div id="rightAsider" class="left">

            <div id="bottomBanner">
                <div id="picture" class="left">
                    <div>
                        <div id="wrapper" class="left" style="margin-top: 10px">
                            <div id="banner">
                                <ul class="imgList">
                                    <?php foreach($this->setting['banner_image'] as $key => $value) { ?>
                                        <?php if(!empty($value)) { ?>
                                            <li><a href="<?php echo $this->setting['banner_url'][$key];?>"><img src="<?php echo $value;?>" width="710px" height="320px"></a></li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                                <ul class="indexList">
                                    <li class="indexOn"></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="litter">
                        <div style="overflow: hidden">
                            <p class="left">热门家政机构(本地)</p>
                            <a class="right" href="index.php?act=agent">机构入驻</a>
                            <i></i>
                        </div>
                        <div class="clear"></div>
                        <div class="litter_picture">
                            <?php foreach($this->setting['nav_image'] as $key => $value) { ?>
                                <?php if(!empty($value)) { ?>
                                    <div class="left" >
                                        <a href="<?php echo $this->setting['nav_url'][$key];?>">
                                            <img src="<?php echo $value;?>" width="350px" height="240px" alt="">
<!--                                            <span>--><?php //echo $this->setting['nav_name'][$key];?><!--</span>-->
                                        </a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div id="trade" class="left">
                    <div class="login_module">
                        <?php if(empty($this->member['member_id'])) { ?>
                            <div class="login_circle">
                                <span><img src="templates/images/head.png" alt=""></span>
                            </div>
                            <div class="login_btn">
                                <p>欢迎您来到<?php echo $this->setting['site_name'];?></p>
                            </div>
                            <div class="login_btn">
                                <span class="btn1"><a href="index.php?act=login" style="color:#fff;padding:0 10px;">登录</a></span>
                                <span class="btn1"><a href="index.php?act=register" style="color:#fff;padding:0 10px;">注册</a></span>
                            </div>
                            <div class="shop_enter">
                                <span></span>
                                <a href="index.php?act=agent">机&nbsp;&nbsp;构&nbsp;&nbsp;入&nbsp;&nbsp;驻</a>
                                <img src="templates/images/enter.png" alt="">
                            </div>
                        <?php } else { ?>
                            <div class="login_circle">
                                <?php if(empty($this->member['member_avatar'])) { ?>
                                    <span><img src="templates/images/head.png" alt=""></span>
                                <?php } else { ?>
                                    <span><img width="52px" height="52px" src="<?php echo $this->member['member_avatar']; ?>" alt=""></span>
                                <?php } ?>
                            </div>
                            <div class="login_btn">
                                <b><em><a href="index.php?act=member_center"><?php echo empty($this->member['member_nickname']) ? $this->member['member_phone'] : $this->member['member_nickname'] ?></a></em></b><img style="height:16px;margin:0 0 2px 5px" class="member_card" src="" alt="">
                                <p><a href="index.php?act=member_wallet"><img style="margin:0 5px 3px 0;" src="templates/images/money.png" alt="">我的钱包</a></p>
                                <div class="login_module_state">

                                </div>
                            </div>
                            <div class="login_module_states"></div>
                            <div class="login_module_control">
                                <a class="left" href="index.php?act=member_center">账号管理</a><a class="right" href="index.php?act=logout">退出登录</a>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="transaction wrap">
<!--                        <p>24小时交易动态&nbsp;&nbsp;&nbsp;已预约<b>--><?php //echo $book_count+10000 ?><!--</b>单</p>-->
                        <p>24小时交易动态</p>
                        <div class="roll-wrap" id="roll-wrap">
                            <ul class="tranScroll">
                                <?php foreach($book_list as $key => $value) { ?>
                                    <li><span style="color:#5abef0;">○</span>&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $value['member_phone'];?>]<?php echo date('m月d H:i', $value['comment_time']);?>已下单</li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                    <div class="hotReview wrap">
                        <p>雇主热评</p>
                        <div class="roll-wrap" id="roll-wrap">
                            <ul class="hotScroll">
                                <?php foreach($comment_list as $key => $value) { ?>
                                    <?php if(empty($member_list[$value['nurse_id']]['nurse_areaname'])) { ?>
                                        <li><span style="color:#5abef0;">○</span>&nbsp;&nbsp;&nbsp;&nbsp;[******]<a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['comment_content'];?></a></li>
                                    <?php } else { ?>
                                        <li><span style="color:#5abef0;">○</span>&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $member_list[$value['nurse_id']]['nurse_areaname'];?>]<a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['comment_content'];?></a></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="bottomContainer">
        <h2>机构热卖</h2>
        <div class="hotSeller">
            <?php foreach($this->setting['hot_image'] as $key => $value) { ?>
                <?php if(!empty($value)) { ?>
                    <a href="<?php echo $this->setting['hot_url'][$key];?>">
                        <img  class="left" src="<?php echo $value;?>"  alt="">
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
        <h2>条件筛选</h2>
        <div class="conwp">
            <div class="selector">
                <div class="selectorline selector-open">
                    <label>所在区域：</label>
                    <span class="city-opr" onclick="window.location.href='index.php?act=city'"><i class="iconfont icon-city"></i><em><?php echo $this->district['district_name'];?></em>[切换城市]</span>
                    <div class="selector-value clearfix">
                        <ul>
                            <?php foreach($district_list as $key => $value) { ?>
                                <li><a href="javascript:;" onclick="selectnurse(this, 'district_id', '<?php echo $value['district_id'];?>');"><?php echo $value['district_name'];?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="selectorline">
                    <label>职业类型：</label>
                    <div class="selector-value clearfix">
                        <ul>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 1);">住家保姆</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 2);">涉外保姆</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 6);">育婴早教</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 3);">钟点服务</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 5);">幼教保育</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 11);">家庭外教</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 7);">水电维修</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 8);">管道疏通</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 4);">清洁清洗</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_type', 9);">搬家服务</a></li>
                        </ul>
                    </div>
                </div>
                <div class="selectorline">
                    <label>星级：</label>
                    <div class="selector-value clearfix">
                        <ul>
                            <li style="position: relative">
                                心级 <img src="templates/images/toBottom.png" alt="">
                                <ul class="level_ul" style="position: absolute;width:40px;left:15px;z-index:99999;border: 1px solid #ddd;background: #fff;border-top:none;display: none;">
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 1);">一心</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 2);">二心</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 3);">三心</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 4);">四心</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 5);">五心</a></li>
                                </ul>
                            </li>
                            <li style="position: relative">
                                钻级<img style="margin-left: 5px;" src="templates/images/toBottom.png" alt="">
                                <ul class="level_ul" style="position: absolute;width:40px;left:15px;z-index:99999;border: 1px solid #ddd;background: #fff;border-top:none;display: none;">
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 6);">一钻</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 7);">二钻</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 8);">三钻</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 9);">四钻</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 10);">五钻</a></li>
                                </ul>
                            </li>
                            <li style="position: relative">
                                皇冠<img style="margin-left:10px;" src="templates/images/toBottom.png" alt="">
                                <ul class="level_ul" style="position: absolute;width:60px;left:15px;z-index:99999;border: 1px solid #ddd;background: #fff;border-top:none;display: none;">
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 11);">一皇冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 12);">二皇冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 13);">三皇冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 14);">四皇冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 15);">五皇冠</a></li>
                                </ul>
                            </li>
                            <li style="position: relative">
                                金冠<img style="margin-left:10px;" src="templates/images/toBottom.png" alt="">
                                <ul class="level_ul" style="position: absolute;width:60px;left:15px;z-index:99999;border: 1px solid #ddd;background: #fff;border-top:none;display: none;">
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 16);">一金冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 17);">二金冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 18);">三金冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 19);">四金冠</a></li>
                                    <li class="clear"><a href="javascript:;" onclick="selectnurse(this, 'grade_id', 10);">五金冠</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="selectorline">
                    <label>收费标准：</label>
                    <div class="selector-value clearfix">
                        <ul>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_price', '0-500');">500以下</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_price', '500-1000');">500-1000</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_price', '1000-2000');">1000-2000</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_price', '2000-3000');">2000-3000</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_price', '3000-5000');">3000-5000</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_price', '5000');">5000以上</a></li>
                        </ul>
                    </div>
                </div>
                <div class="selectorline">
                    <label>年龄：</label>
                    <div class="selector-value clearfix">
                        <ul>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_age', '0-35');">35岁以下</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_age', '35-40');">35-40岁</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_age', '41-45');">41-45岁</a></li>
                            <li><a href="javascript:;" onclick="selectnurse(this, 'nurse_age', '45');">45岁以上</a></li>
                        </ul>
                    </div>
                </div>
                <div class="selectorline search-box" style="display:none;">
                    <label>你的选择：</label>
                    <div class="selector-value clearfix">
                        <ul></ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="nurse-list nurse-box" id="nurse_list">
            <?php if(empty($nurse_list)) { ?>
                <div class="no-shop">
                    <dl>
                        <dt></dt>
                        <dd>
                            <p>抱歉，没有找到符合条件的看护人员</p>
                            <p>您可以适当减少筛选条件</p>
                        </dd>
                    </dl>
                </div>
            <?php } else { ?>
                <ul >
                    <?php foreach($nurse_list as $key => $value) { ?>
                        <li>
                            <div class="nurse-img">
                                <?php if($value['nurse_image'] == '') { ?>
                                    <a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></a>
                                <?php } else { ?>
                                    <a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="<?php echo $value['nurse_image'];?>"></a>
                                <?php } ?>
                                <div class="nurse-salary">
                                    <?php if($value['nurse_type'] == 3) { ?>
                                        <span class="nurse-price">¥<strong><?php echo $value['nurse_price'];?></strong>/时</span>
                                    <?php }else if($value['nurse_type'] == 4) { ?>
                                        <span class="nurse-price">¥<strong><?php echo $value['nurse_price'];?></strong>/平方</span>
                                    <?php } else { ?>
                                        <span class="nurse-price">¥<strong><?php echo $value['nurse_price'];?></strong>/月</span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="nurse-type">
                                <a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" class="nurse-name"><?php echo $value['nurse_nickname'];?></a><span><?php echo $value['service_type'];?></span>
                                <span class="nurse_content"><?php echo $value['nurse_content'];?></span>
<!--                                <span class="nurse_content">--><?php //echo $value['nurse_special_service'];?><!--</span>-->
                            </div>
                            <div class="nurse-evaluate">
                                <span class="level-box"><a target="_blank" href="index.php?act=nurse_trust_grade&nurse_id=<?php echo $value['nurse_id'];?>"><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""></a></span>
                                <span class="nurse-score">
                                评价 (<?php echo $value['nurse_commentnum'];?>)
                                </span>
                                <span class="book_count right">
                                    <?php echo $value['nurse_salenum'];?>人已付款
                                </span>
                            </div>
                            <div class="nurse-certified">
                                <?php if($value['agent_id'] == 0) { ?>
                                    <span style="text-decoration: underline;">个人</span>
                                <?php } else { ?>
                                    <span><a style="text-decoration: underline;" href="index.php?act=agent_show&agent_id=<?php echo $value['agent_id'];?>"><?php echo $agent_list[$value['agent_id']];?></a></span>
                                <?php } ?>

                            </div>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="multi-box"><?php echo $multi;?></div>
</div>
<div class="toolsidebar" id="service">
    <ul>
        <li>
            <span class="tool-icon"><i class="iconfont icon-qq"></i></span>
            <div class="toolhidebox">
                <div class="tool-content">
                    <h3>在线咨询</h3>
                    <div class="toolhb_c">
                        <?php foreach($this->setting['service_qq'] as $key => $value) { ?>
                            <p>
                                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $value;?>&site=qq&menu=yes">
                                    <img border="0" src="http://pub.idqqimg.com/qconn/wpa/button/button_121.gif" alt="" title="点击这里给我发消息">
                                    <span class="toolkf">客服<?php echo $value;?>(点击咨询)</span>
                                </a>
                            </p>
                        <?php } ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <span class="tool-icon"><i class="iconfont icon-stel"></i></span>
            <div class="toolhidebox">
                <div class="tool-content">
                    <h3>联系方式</h3>
                    <div class="toolhb_c">
                        <p>服务时间：08:00 - 11:00</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;14:00 - 18:00</p>
                        <p>客服热线：<?php echo $this->setting['site_phone'];?></p>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <span class="tool-icon"><i class="iconfont icon-app"></i></span>
            <div class="toolhidebox">
                <div class="tool-content">
                    <h3>扫一扫下载App</h3>
                    <div class="toolhb_c">
                        <img width="100%" src="<?php echo $this->setting['app_image'];?>" alt="">
                    </div>
                </div>
            </div>
        </li>
        <style>
            .mail{padding:10px 0;}
            .mail i{display: block;width:26px;height:28px;background: url(../templates/images/suggestB.png) no-repeat center;margin:0 auto;}
            .mail:hover i{background: url(../templates/images/suggestW.png) no-repeat center;}
        </style>
        <li>
            <span class="tool-icon mail"><i class=""></i></span>
            <div class="toolhidebox">
                <div class="tool-content">
                    <h3>用户反馈调查</h3>
                    <div class="toolhb_c">
                        <a href="index.php?act=user_feed_back">我要反馈</a>
                    </div>
                </div>
            </div>
        </li>
        <li class="go-top" id="returnTop">
            <span class="tool-icon"><i class="iconfont icon-gotop"></i></span>
        </li>
    </ul>
</div>
<!--<script>-->
<!--    $(".toolhidebox").mouseover(function () {-->
<!--        $(".mail i").css('background','url(../templates/images/suggestW.png) no-repeat center')-->
<!--    });-->
<!--    $(".toolhidebox").mouseout(function () {-->
<!--        $(".mail i").css('background','url(../templates/images/suggestB.png) no-repeat center')-->
<!--    });-->
<!--</script>-->
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
<div class="alert-box" style="display:none;">
    <div class="alert alert-danger tip-title"></div>
</div>
<?php if(0) { ?>
    <div class="modal-wrap w-400" id="city-box" style="display:none;">
        <div class="modal-bd">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <span class="tip-icon"><i class="iconfont icon-info"></i></span>
                    <h3 class="tip-title">该城市暂时没有家政人员入驻</h3>
                    <p class="tip-hint">你可以到附近城市逛逛</p>
                </div>
            </div>
        </div>
        <div class="modal-ft tc">
            <a class="btn btn-primary" href="index.php?act=city">切换城市</a>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            Custombox.open({
                target : '#city-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        });
        $("li").click(function () {
            console.log(2);
        });
    </script>
<?php } elseif(!empty($message)) { ?>
    <div class="modal-wrap w-500" id="message-box" style="display:none;">
        <div class="modal-bd">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <span class="tip-icon"><i class="iconfont icon-info"></i></span>
                    <h3 class="tip-title"><?php echo $message['message_title'];?></h3>
                    <p class="tip-hint"><?php echo $message['message_content'];?></p>
                </div>
            </div>
        </div>
        <div class="modal-ft tc">
            <a class="btn btn-primary" href="index.php?act=order&op=book">查看</a>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            Custombox.open({
                target : '#message-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        });
    </script>
<?php } else { ?>
    <div class="modal-wrap w-400" id="red-box" style="display:none;">
        <div class="modal-bd">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <span class="tip-icon"></span>
                    <h3 class="tip-title"></h3>
                    <div class="tip-hint"></div>
                </div>
            </div>
        </div>
        <div class="modal-ft tc">
            <a class="btn btn-primary" onclick="Custombox.close();">关闭</a>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $.getJSON('index.php?act=index&op=red', function(data){
                if(data.done == 'true') {
                    $('#red-box .tip-icon').html('<i class="iconfont icon-check"></i>');
                    $('#red-box .tip-title').html('恭喜您，获得'+data.red_price+'元红包');
                    $('#red-box .tip-hint').html('您可以在个人中心查看');
                    Custombox.open({
                        target : '#red-box',
                        effect : 'blur',
                        overlayClose : true,
                        speed : 500,
                        overlaySpeed : 300,
                        open: function () {
                            setTimeout(function() {
                                Custombox.close();
                            }, 3000);
                        },
                    });
                }
            });
        });
    </script>
<?php } ?>
<script type="text/javascript" src="templates/js/swiper.min.js"></script>
<script type="text/javascript" src="templates/js/img2blob.js"></script>
<script type="text/javascript">

    var swiper = new Swiper('#swiper-banner', {
        pagination: '.swiper-banner',
        effect : 'fade',
        paginationClickable: true,
        paginationBulletRender: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + '</span>';
        }
    });

    function objscroll(divname) {
        var objs = document.getElementById(divname), divname=$("#"+divname);
        var o = {};
        o.obj = divname;
        o.objH = divname.height();
        o.objs = objs;
        $(window).scroll(function() {
            var bodyH = $(document).scrollTop(), headH = $(".header").height()+$(".full-banner").height();
            if(bodyH >= headH) {
                $('.go-top').show();
            } else {
                $('.go-top').hide();
            }
        });
    }
    objscroll("service");

    $("#returnTop").click(function() {
        var speed=500;
        $('body,html').animate({scrollTop : 0}, speed);
        return false;
    });
</script>
<script>
    $(".close-app").click(function () {
        $(this).parent().remove();
    });
//    二维码定位函数
    function fix(){
        var max=window.screen.width;
        var sum=(max-1180)/2;
//        var max=1920;
        num=sum-(max-$(window).width())/2;
        $(".sao-app").css('right',num+'px');
        var w=max/3.2;
        $("#keywords").css('width',w+'px');
    }
    fix();
    $(window).resize(function () {
        fix();
    });
</script>
<script type="text/javascript">
    var nurse_type = '<?php echo $nurse_type;?>';
    var page = '<?php echo $page;?>';
</script>
<script type="text/javascript" src="templates/js/home/index_first.js"></script>
<script type="text/javascript" src="templates/js/home/index_new.js"></script>
<script>
    $(function(){
        var mainOffsetTop = $(".header").offset().top;
        var mainHeight = $(".header").height();
        var winHeight = $(window).height();
        $(window).scroll(function(){
            var winScrollTop = $(window).scrollTop();
            if(winScrollTop > mainOffsetTop + mainHeight || winScrollTop <　mainOffsetTop - winHeight){
                $(".header").addClass("fix");
            }else{
                $(".header").removeClass("fix");
            }
        })
    });
    var member_id = '<?php echo $this->member_id;?>';
    $.getJSON('index.php?act=index&op=login_module',{'member_id':member_id},function (data) {
        $(".login_module_state").html(data.login_module_state_html);
        $(".login_module_states").html(data.login_module_states_html);
        $(".member_card").attr('src',data.card['card_icon']);
        $(".agent_enter").click(function () {
            window.location.href='index.php?act=agent_center';
        });
        $(".shop_enter").click(function () {
            window.location.href='index.php?act=agent';
        });
    });
    function state_setClick() {
        $(".login_nurseState_set span").click(function () {
            $(this).addClass("stateOn").parent().siblings().children().removeClass();
            var state_cideci=$(this).attr('data');
            $.getJSON('index.php?act=index&op=login_setState',{'member_id':member_id,'state_cideci':state_cideci},function (data) {
            })
        })
    }
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
<!--<script type="text/javascript">-->
<!--    var swiper = new Swiper('#swiper-banner', {-->
<!--        pagination: '.swiper-banner',-->
<!--        effect : 'fade',-->
<!--        paginationClickable: true,-->
<!--        paginationBulletRender: function (index, className) {-->
<!--            return '<span class="' + className + '">' + (index + 1) + '</span>';-->
<!--        }-->
<!--    });-->
<!---->
<!--    function objscroll(divname) {-->
<!--        var objs = document.getElementById(divname), divname=$("#"+divname);-->
<!--        var o = {};-->
<!--        o.obj = divname;-->
<!--        o.objH = divname.height();-->
<!--        o.objs = objs;-->
<!--        $(window).scroll(function() {-->
<!--            var bodyH = $(document).scrollTop(), headH = $(".header").height()+$(".full-banner").height();-->
<!--            if(bodyH >= headH) {-->
<!--                $('.go-top').show();-->
<!--            } else {-->
<!--                $('.go-top').hide();-->
<!--            }-->
<!--        });-->
<!--    }-->
<!--    objscroll("service");-->
<!---->
<!--    $("#returnTop").click(function() {-->
<!--        var speed=5000;-->
<!--        $('body,html').animate({scrollTop : 0}, speed);-->
<!--        return false;-->
<!--    });-->
<!--    setInterval(function () {-->
<!--        objscroll(3)-->
<!--    },500);-->
<!--</script>-->

<?php include(template('common_footer'));?>
