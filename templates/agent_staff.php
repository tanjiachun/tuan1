<?php include(template('common_header'));?>
    <div class="nurse_header">
        <a class="left" href="index.php">
            <img src="templates/images/logo.png">
        </a>
        <div class="left" style="min-width:100px;height:74px;margin-left: 20px;padding-left: 20px;border-left:1px solid #ddd; ">

            <p style="line-height: 37px;font-size: 16px;font-weight: 700;"><?php echo $agent['agent_name'];?></p>

            <p style="line-height: 37px;"><a href="javascript:;" class="lianxi"><img style="margin:0 5px 3px 0; " src="templates/images/lianxi.png" alt="">在线客服</a></p>
        </div>
        <div class="left" style="width:100px;height:74px;margin-left: 20px;">
            <p style="line-height: 37px;">等级<img style="margin:0 0 5px 5px" height="16px" src="<?php echo $agent_grade['grade_icon'] ?>" alt=""></p>
            <p style="line-height: 37px;">关注&nbsp;<?php echo $agent['agent_focusnum'];?>&nbsp;人</p>
        </div>
        <div class="search-box-top right">
            <span class="bg s_ipt_wr iptfocus quickdelete-wrap" style="width:500px;">
                <input style="width:500px;" type="text" id="keywords" class="itxt" placeholder="搜索您需要的服务">
            </span>
            <span class="s_btn_wr">
                <a href="javascript:;" class="search-btn" onclick="selectnurse(this, 'keyword', $('#keywords').val());">
                    <input type="submit" id="search-btn" value="搜索" class="search-btn bg s_btn">
                </a>
            </span>
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
<div class="nurse_header_choose">
    <div class="nurse_header_choose_content">
        <span><a href="index.php?act=agent_show&agent_id=<?php echo $agent['agent_id']; ?>">机构首页</a></span>
        <span style="background: red;"><a href="index.php?act=agent_staff&agent_id=<?php echo $agent['agent_id']; ?>">全部员工(<?php echo $nurse_count ?>)</a></span>
        <span><a href="index.php?act=agent_answers&agent_id=<?php echo $agent['agent_id']; ?>">机构问答</a></span>
        <span>机构号&nbsp;<?php echo $agent['agent_id'];?></span>
    </div>
</div>

<div class="content">
    <div class="conwp">
        <div class="selector">
            <div class="selectorline search-box" style="display:none;">
                <label>你的选择：</label>
                <div class="selector-value clearfix">
                    <ul></ul>
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

        </div>
        <div class="filter-line">
            <div class="f-sort">
                <a href="javascript:;" class="curr" onclick="selectnurse(this, 'sort_field', 'new');">综合<i></i></a>
                <a href="javascript:;" class="" onclick="selectnurse(this, 'sort_field', 'salenum');">交易量<i></i></a>
                <a href="javascript:;" class="" onclick="selectnurse(this, 'sort_field', 'commentnum');">好评度<i></i></a>
                <a href="javascript:;" class="" onclick="selectnurse(this, 'sort_field', 'price');">价格<i></i></a>
                <a href="javascript:;" class="" onclick="selectnurse(this, 'sort_field', 'favoritenum');">收藏<i></i></a>
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
                                    <a target="_Blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></a>
                                <?php } else { ?>
                                    <a target="_Blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="<?php echo $value['nurse_image'];?>"></a>
                                <?php } ?>
                                <div class="nurse-salary">
                                    <?php if($value['nurse_type'] == 3) { ?>
                                        <span class="nurse-price">¥<strong><?php echo $value['nurse_price'];?></strong>/时</span>
                                    <?php }else if($value['nurse_type'] == 4) { ?>
                                        <span class="nurse-price">¥<strong><?php echo $value['nurse_price'];?></strong>/平方</span>
                                    <?php }else if($value['nurse_type'] == 7 || $value['nurse_type'] == 8 || $value['nurse_type'] == 9 ||$value['nurse_type'] == 10) { ?>
                                        <span class="nurse-price">¥<strong><?php echo $value['nurse_price'];?></strong>/次</span>
                                    <?php } else { ?>
                                        <span class="nurse-price">¥<strong><?php echo $value['nurse_price'];?></strong>/月</span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="nurse-type">
                                <a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" class="nurse-name"><?php echo $value['nurse_nickname'];?></a><span><?php echo $value['service_type'];?></span>
                                <span class="nurse_content"><?php echo $value['nurse_special_service'];?></span>
                            </div>
                            <div class="nurse-evaluate">
                                <span class="level-box"><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""></span>
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
        <div class="multi-box"><?php echo $multi;?></div>
    </div>
</div>


<script>
    var keyword = '';
    var service_type='';
    var nurse_type='';
    var nurse_education = 0;
    var nurse_price = 0;
    var nurse_age = 0;
    var grade_id = 0;
    var sort_field = 'time';
    var time_sort = 'desc';
    var price_sort = 'asc';
    var salenum_sort='asc';
    var commentnum_sort='asc';
    var favoritenum_sort='asc';
    var agent_id='<?php echo $agent['agent_id'] ?>';
    $(".selector .selectorline:nth-child(3) .selector-value>ul>li").mouseover(function () {
        $(this).children(".level_ul").show();
    });
    $(".selector .selectorline:nth-child(3) .selector-value>ul>li").mouseout(function () {
        $(this).children(".level_ul").hide();
    });

    function selectnurse(obj, field, variable) {
        if(field == 'sort_field') {
            $(obj).addClass('curr');
            $(obj).siblings().removeClass('curr');
        } else if(field == 'nurse_type' || field == 'nurse_education' || field == 'nurse_price' || field == 'nurse_age' || field == 'grade_id') {
            $('.search-box').show();
            if($('#'+field).length == 0) {
                $('.search-box ul').append('<li id="'+field+'"><span class="selected">'+$(obj).text()+'<i>x</i></span></li>');
            } else {
                $('#'+field).html('<span class="selected">'+$(obj).text()+'<i>x</i></span>');
            }
        }
        if(field == 'keyword') {
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
                price_sort = 'asc';
                salenum_sort='asc';
                commentnum_sort='asc';
                favoritenum_sort='asc;'
            }else if(sort_field == 'price') {
                price_sort = price_sort=='desc' ? 'asc' : 'desc';
                time_sort = 'asc';
                salenum_sort='asc';
                commentnum_sort='asc';
                favoritenum_sort='asc;'
            } else if(sort_field == 'salenum'){
                salenum_sort = salenum_sort=='desc' ? 'asc' : 'desc';
                time_sort = 'asc';
                price_sort = 'asc';
                commentnum_sort='asc';
                favoritenum_sort='asc;'
            } else if(sort_field == 'commentnum'){
                commentnum_sort = commentnum_sort=='desc' ? 'asc' : 'desc';
                time_sort = 'asc';
                price_sort = 'asc';
                salenum_sort='asc';
                favoritenum_sort='asc;'
            }else if(sort_field == 'favoritenum'){
                favoritenum_sort = favoritenum_sort=='desc' ? 'asc' : 'desc';
                time_sort = 'asc';
                price_sort = 'asc';
                salenum_sort='asc';
                commentnum_sort='asc';
            }else if(sort_field == 'new') {
                time_sort = 'asc';
                price_sort = 'asc';
                salenum_sort='asc';
                commentnum_sort='asc';
                favoritenum_sort='asc;'
            }
        }
        if(field == 'page') {
            page = variable;
        } else {
            page = 1;
        }
        var submitData = {
            'agent_id':agent_id,
            'keyword' : keyword,
            'nurse_type' : nurse_type,
            'service_type':service_type,
            'nurse_education' : nurse_education,
            'nurse_price' : nurse_price,
            'nurse_age' : nurse_age,
            'grade_id' : grade_id,
            'sort_field' : sort_field,
            'time_sort' : time_sort,
            'price_sort' : price_sort,
            'salenum':salenum_sort,
            'commentnum':commentnum_sort,
            'favoritenum':favoritenum_sort,
            'page' : page
        };
        $.getJSON('index.php?act=agent_staff&op=nurse_search', submitData, function(data){
            console.log(data);
            if(data.done == 'true') {
                $('.page-box').html(data.nurse_page_html);
                $('.count-box').html(data.nurse_count_html);
                $('.nurse-box').html(data.nurse_html);
                $('.multi-box').html(data.nurse_multi_html);
                $(".f-pager").html(data.multiTop_html);
            }
        });
    }
    $('.search-box').on('click', 'i', function() {
        var id = $(this).parent().parent().attr('id');
        if(id == 'nurse_type') {
            nurse_type = 0;
        } else if(id == 'nurse_education') {
            nurse_education = 0;
        } else if(id == 'nurse_price') {
            nurse_price = 0;
        } else if(id == 'nurse_age') {
            nurse_age = 0;
        } else if(id == 'grade_id') {
            grade_id = 0;
        }
        page = 1;
        $(this).parent().parent().remove();
        if($('.search-box li').length == 0) {
            $('.search-box').hide();
        }
        var submitData = {
            'agent_id':agent_id,
            'nurse_type' : nurse_type,
            'nurse_education' : nurse_education,
            'nurse_price' : nurse_price,
            'nurse_age' : nurse_age,
            'grade_id' : grade_id,
            'sort_field' : sort_field,
            'time_sort' : time_sort,
            'price_sort' : price_sort,
            'salenum':salenum_sort,
            'commentnum':commentnum_sort,
            'favoritenum':favoritenum_sort,
            'page' : page,
        };
        $.getJSON('index.php?act=agent_staff&op=nurse_search', submitData, function(data){
            if(data.done == 'true') {
                $('.page-box').html(data.nurse_page_html);
                $('.count-box').html(data.nurse_count_html);
                $('.nurse-box').html(data.nurse_html);
                $('.multi-box').html(data.nurse_multi_html);
                $(".f-pager").html(data.multiTop_html);
            }
        });
    });

</script>
<script>
    var a='<?php echo $this->member['yx_accid'] ?>';
    var b='<?php echo $this->member['yx_token'] ?>';
    var c='<?php echo $member['yx_accid'] ?>';
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