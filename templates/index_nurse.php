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
                <a href="javascript:;" class="search-btn" onclick="selectnurse(this, 'keyword', $('#keywords').val());">
                    <input type="submit" id="search-btn" value="搜索" class="search-btn bg s_btn">
                </a>
            </span>
            </div>
        </div>
        <div class="top-tool left">
            <span class="city-opr" style="color:#000;" onclick="window.location.href='index.php?act=nurse_city'"><i class="iconfont icon-city"></i><em><?php echo $this->district['district_name'];?></em>[切换城市]</span>
        </div>
    </div>
</div>
<script>
    $(function(){
        document.onkeydown = function(e){
            var ev = document.all ? window.event : e;
            if(ev.keyCode==13) {
                var keyword=$("#keywords").val();
                if(keyword!=''){
                    selectnurse(this, 'keyword', $('#keywords').val());
                }
            }
        }
    });
</script>
<div id="bottomContainer">

    <h2>条件筛选> <span class="nurse_count" style="font-weight: normal;font-size: 16px;">共<b><?php echo $count;?></b>位家政人员</span></h2>
    <div class="conwp">
        <span class="pick_up">收起筛选</span>
        <div class="selector">
            <div class="selectorline selector-open">
                <label>所在区域：</label>
                <span class="city-opr" onclick="window.location.href='index.php?act=nurse_city'"><i class="iconfont icon-city"></i><em><?php echo $this->district['district_name'];?></em>[切换城市]</span>
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
    <script>
        $(".pick_up").click(function () {
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $(".selector").fadeOut();
                $(this).text('展开筛选');
            }else if($(this).hasClass("active")){
                $(this).removeClass("active");
                $(this).text("收起筛选");
                $(".selector").fadeIn();
            }
        })
    </script>
    <div class="filter-line">
        <div class="f-sort">
            <a href="javascript:;" class="curr" onclick="selectnurse(this, 'sort_field', 'new');">默认排序<i></i></a>
            <a href="javascript:;" class="" onclick="selectnurse(this, 'sort_field', 'education');">经验<i></i></a>
            <a href="javascript:;" class="" onclick="selectnurse(this, 'sort_field', 'price');">价格<i></i></a>
            <a href="javascript:;" class="" onclick="selectnurse(this, 'sort_field', 'age');">年龄<i></i></a>
        </div>
        <div class="f-pager">
            <?php echo $multiTop ?>
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
<script>
    var district_id = 0;
    var keyword = '';
    var service_type='';
    var nurse_type='';
    var nurse_education = 0;
    var nurse_price = 0;
    var nurse_age = 0;
    var grade_id = 0;
    var sort_field = 'time';
    var time_sort = 'desc';
    var education_sort = 'asc';
    var price_sort = 'asc';
    var age_sort = 'asc';
    function selectnurse(obj, field, variable) {
        if(field == 'sort_field') {
            $(obj).addClass('curr');
            $(obj).siblings().removeClass('curr');
        } else if(field == 'district_id' || field == 'nurse_type' || field == 'nurse_education' || field == 'nurse_price' || field == 'nurse_age' || field == 'grade_id') {
            $('.search-box').show();
            if($('#'+field).length == 0) {
                $('.search-box ul').append('<li id="'+field+'"><span class="selected">'+$(obj).text()+'<i>x</i></span></li>');
            } else {
                $('#'+field).html('<span class="selected">'+$(obj).text()+'<i>x</i></span>');
            }
        }
        if(field == 'district_id') {
            district_id = variable;
        } else if(field == 'keyword') {
            keyword = variable;
        } else if(field == 'nurse_type') {
            nurse_type = variable;
        } else if(field == 'service_type') {
            service_type = variable;
        } else if(field == 'nurse_education') {
            nurse_education = variable;
        } else if(field == 'nurse_price') {
            nurse_price = variable;
        } else if(field == 'nurse_age') {
            nurse_age = variable;
        } else if(field == 'grade_id') {
            grade_id = variable;
        } else if(field == 'sort_field') {
            sort_field = variable;
            if(sort_field == 'time') {
                time_sort = time_sort=='desc' ? 'asc' : 'desc';
                education_sort = 'asc';
                price_sort = 'asc';
                age_sort = 'asc';
            } else if(sort_field == 'education') {
                education_sort = education_sort=='desc' ? 'asc' : 'desc';
                time_sort = 'asc';
                price_sort = 'asc';
                age_sort = 'asc';
            } else if(sort_field == 'price') {
                price_sort = price_sort=='desc' ? 'asc' : 'desc';
                time_sort = 'asc';
                education_sort = 'asc';
                age_sort = 'asc';
            } else if(sort_field == 'age') {
                age_sort = age_sort=='desc' ? 'asc' : 'desc';
                time_sort = 'asc';
                education_sort = 'asc';
                price_sort = 'asc';
            } else if(sort_field == 'new') {
                time_sort = 'asc';
                education_sort = 'asc';
                price_sort = 'asc';
                age_sort = 'asc';
            }
        }
        if(field == 'page') {
            page = variable;
        } else {
            page = 1;
        }
        var submitData = {
            'district_id' : district_id,
            'keyword' : keyword,
            'nurse_type' : nurse_type,
            'service_type':service_type,
            'nurse_education' : nurse_education,
            'nurse_price' : nurse_price,
            'nurse_age' : nurse_age,
            'grade_id' : grade_id,
            'sort_field' : sort_field,
            'time_sort' : time_sort,
            'education_sort' : education_sort,
            'price_sort' : price_sort,
            'age_sort' : age_sort,
            'page' : page,
        };
        $.getJSON('index.php?act=index&op=nurse_search', submitData, function(data){
            if(data.done == 'true') {
                $('.page-box').html(data.nurse_page_html);
                $('.count-box').html(data.nurse_count_html);
                $('.nurse-box').html(data.nurse_html);
                $('.multi-box').html(data.nurse_multi_html);
                $(".f-pager").html(data.multiTop_html);
                $('.nurse_count b').text(data.count);
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
    $(function() {
        $(".selector .selectorline:nth-child(3) .selector-value>ul>li").mouseover(function () {
            $(this).children(".level_ul").show();
        });
        $(".selector .selectorline:nth-child(3) .selector-value>ul>li").mouseout(function () {
            $(this).children(".level_ul").hide();
        });
        $('.search-box').on('click', 'i', function () {
            var id = $(this).parent().parent().attr('id');
            if (id == 'district_id') {
                district_id = 0;
            } else if (id == 'nurse_type') {
                nurse_type = 0;
            } else if (id == 'nurse_education') {
                nurse_education = 0;
            } else if (id == 'nurse_price') {
                nurse_price = 0;
            } else if (id == 'nurse_age') {
                nurse_age = 0;
            } else if (id == 'grade_id') {
                grade_id = 0;
            }
            page = 1;
            $(this).parent().parent().remove();
            if ($('.search-box li').length == 0) {
                $('.search-box').hide();
            }
            var submitData = {
                'district_id': district_id,
                'nurse_type': nurse_type,
                'nurse_education': nurse_education,
                'nurse_price': nurse_price,
                'nurse_age': nurse_age,
                'grade_id': grade_id,
                'sort_field': sort_field,
                'time_sort': time_sort,
                'education_sort': education_sort,
                'price_sort': price_sort,
                'age_sort': age_sort,
                'page': page,
            };
            $.getJSON('index.php?act=index&op=nurse_search', submitData, function (data) {
                if (data.done == 'true') {
                    $('.page-box').html(data.nurse_page_html);
                    $('.count-box').html(data.nurse_count_html);
                    $('.nurse-box').html(data.nurse_html);
                    $('.multi-box').html(data.nurse_multi_html);
                    $(".f-pager").html(data.multiTop_html);
                    $('.nurse_count b').text(data.count);

                }
            });
        });
    });
</script>
<?php include(template('common_footer'));?>
