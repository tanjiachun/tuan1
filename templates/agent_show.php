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
                <p style="line-height: 37px;">等级<img height="16px" style="margin:0 0 5px 5px" src="<?php echo $agent_grade['grade_icon'] ?>" alt=""></p>
                <p style="line-height: 37px;">关注&nbsp;<?php echo $agent['agent_focusnum'];?>&nbsp;人</p>
        </div>
        <div class="search-box-top right">
            <span class="bg s_ipt_wr iptfocus quickdelete-wrap" style="width:500px;">
                <input style="width:500px;" type="text" id="keywords" class="itxt" placeholder="搜索您需要的服务">
            </span>
            <span class="s_btn_wr">
                <input type="submit" id="search-btn" value="搜索" class="search-btn bg s_btn">
            </span>
        </div>
    </div>
</div>
<div class="nurse_header_choose">
    <div class="nurse_header_choose_content">
        <span style="background: red;"><a href="index.php?act=agent_show&agent_id=<?php echo $agent['agent_id']; ?>">机构首页</a></span>
        <span><a href="index.php?act=agent_staff&agent_id=<?php echo $agent['agent_id']; ?>">全部员工(<?php echo $nurse_count ?>)</a></span>
        <span><a href="index.php?act=agent_answers&agent_id=<?php echo $agent['agent_id']; ?>">机构问答</a></span>
        <span>机构号&nbsp;<?php echo $agent['agent_id'];?></span>
    </div>
</div>
<div class="agent_banner_show">
<!--    <img src="templates/images/444525949559538099.jpg" alt="">-->
    <img src="<?php echo $agent['agent_banner'] ?>" alt="">
</div>
<div id="target_menus_box">
    <div class="target_menus">
        <a href="#agent_message" class="active">基本信息</a>
        <a href="#agent_overview">机构概述</a>
        <a href="#agent_service">机构服务</a>
        <a href="#agent_aptitude">机构资质</a>
        <a href="#agent_map">交通地图</a>
    </div>
</div>
<div id="agent_message">
    <div>
        <p>机构名：&nbsp;&nbsp;&nbsp;<?php echo $agent['agent_name'] ?></p>
        <p>机构号：&nbsp;&nbsp;&nbsp;<?php echo $agent['agent_id'] ?></p>
        <P>机构等级：&nbsp;&nbsp;&nbsp;<img height="16px" style="margin:0 0 5px 5px" src="<?php echo $agent_grade['grade_icon'] ?>" alt=""></P>
    </div>
    <div>
        <P>负责人：&nbsp;&nbsp;&nbsp;<?php echo $agent['owner_name'] ?></P>
        <P>客服电话：&nbsp;&nbsp;&nbsp;<?php echo $agent['agent_phone'] ?></P>
        <P>地址：&nbsp;&nbsp;&nbsp;<?php echo $agent['agent_address'] ?></P>
    </div>
</div>
<div id="agent_message_box">
    <div id="agent_message_box_left" class="left">
        <div class="fortarget" id="agent_overview">
            <p> <span></span>机构概述</p>
            <div class="agent_overview_content">
               <?php echo $agent['agent_summary'] ?>
            </div>
        </div>
        <div class="fortarget" id="agent_service">
            <p> <span></span>机构服务</p>
            <div class="agent_service_content">
                <div class="agent_service_text left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $agent['agent_content'] ?>
                </div>
                <div class="agent_service_image left">
                    <?php foreach($agent['agent_service_image'] as $subkey => $subvalue) { ?>
                        <img src="<?php echo $subvalue;?>">
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="fortarget" id="agent_aptitude">
            <p> <span></span>机构资质</p>
            <div class="agent_aptitude_images">
                <img src="<?php echo $agent['agent_code_image'] ?>" alt="">
                <img src="<?php echo $agent['agent_person_image'] ?>" alt="">
                <img src="<?php echo $agent['agent_person_code_image'] ?>" alt="">

            </div>
        </div>
        <div class="fortarget" id="agent_map">
            <p> <span></span>交通地图</p>
            <div id="container" style="width:900px; height:500px;margin:0 auto;"></div>
        </div>
    </div>
    <div id="agent_message_box_right" class="left">
        <div class="agent_recommend">
            <p>机构推荐</p>
            <?php foreach($nurse_list as $key => $value) { ?>
                <div class="agent_recommend_count">
                    <div class="agent_recommend_count_img left">
                        <?php if($value['nurse_image'] == '') { ?>
                            <a target="_Blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></a>
                        <?php } else { ?>
                            <a target="_Blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="<?php echo $value['nurse_image'];?>"></a>
                        <?php } ?>
                    </div>
                    <div class="agent_recommend_count_message left">
                        <div class="agent_recommend_count_nurse_name">
                            <p><?php echo $value['nurse_nickname'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value['service_type'] ?></p>
                            <p><?php echo $value['nurse_special_service'] ?></p>
                        </div>
                        <div class="agent_recommend_count_nurse_price">
                            <?php if($value['nurse_type'] == 3) { ?>
                                <span>¥<strong><?php echo $value['nurse_price'] ?></strong>/时</span>
                            <?php } elseif($value['nurse_type'] == 4) { ?>
                                <span>¥<strong><?php echo $value['nurse_price'] ?></strong>/平方</span>
                            <?php } elseif($value['nurse_type'] == 7 || $value['nurse_type'] == 8 || $value['nurse_type'] ==9 || $value['nurse_type'] ==10) { ?>
                                <span>¥<strong><?php echo $value['nurse_price'] ?></strong>/次</span>
                            <?php } else { ?>
                                <span>¥<strong><?php echo $value['nurse_price'] ?></strong>/月</span>
                            <?php } ?>
                        </div>
                        <div class="agent_recommend_count_nurse_level">
                            <span><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""></span>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
        <div class="agent_praise">
            <p>好评榜</p>
            <?php foreach($nurse_good_list as $key => $value) { ?>
                <div class="agent_recommend_count">
                    <div class="agent_recommend_count_img left">
                        <?php if($value['nurse_image'] == '') { ?>
                            <a target="_Blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></a>
                        <?php } else { ?>
                            <a target="_Blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><img src="<?php echo $value['nurse_image'];?>"></a>
                        <?php } ?>
                    </div>
                    <div class="agent_recommend_count_message left">
                        <div class="agent_recommend_count_nurse_name">
                            <p><?php echo $value['nurse_nickname'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $type_array[$value['nurse_type']] ?></p>
                            <p><?php echo $value['nurse_content'] ?></p>
                        </div>
                        <div class="agent_recommend_count_nurse_price">
                            <?php if($value['nurse_type'] == 3) { ?>
                                <span>¥<strong><?php echo $value['nurse_price'] ?></strong>/时</span>
                            <?php } elseif($value['nurse_type'] == 4) { ?>
                                <span>¥<strong><?php echo $value['nurse_price'] ?></strong>/平方</span>
                            <?php } else { ?>
                                <span>¥<strong><?php echo $value['nurse_price'] ?></strong>/月</span>
                            <?php } ?>
                        </div>
                        <div class="agent_recommend_count_nurse_level">
                            <span><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""></span>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>

</div>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=6ff3f708e6e3a3f8dd760f8eba06fd8e"></script>
    <script src="http://webapi.amap.com/maps?v=1.3&amp;key=6ff3f708e6e3a3f8dd760f8eba06fd8e&callback=init"></script>
    <script>
        var agent_location='<?php echo $agent['agent_location'] ?>'.split(",");
        function init(){
            if(agent_location.length==1){
                return;
            }
            var map = new AMap.Map('container', {
                resizeEnable: true,
                center: [parseFloat(agent_location[0]),parseFloat(agent_location[1])],
                zoom: 15,
                mapStyle: 'amap://styles/normal'//样式URL
            });
            map.plugin(["AMap.ToolBar"], function() {
                map.addControl(new AMap.ToolBar());
            });
            var marker2 = new AMap.Marker({ //添加自定义点标记
                map: map,
                position: [parseFloat(agent_location[0]),parseFloat(agent_location[1])], //基点位置
                offset: new AMap.Pixel(-64, -128), //相对于基点的偏移位置
                draggable: true,  //是否可拖动
                content: '<i style="color:#ff6905;font-size:50px;" class="iconfont icon-city"></i>'   //自定义点标记覆盖物内容
            });

        }
    </script>
    <script>
        $(function(){
            var mainOffsetTop = $(".target_menus").offset().top;
            var mainHeight = $(".target_menus").height();
            var winHeight = $(window).height();
            $(window).scroll(function(){
                var winScrollTop = $(window).scrollTop();
                if(winScrollTop > mainOffsetTop + mainHeight || winScrollTop <　mainOffsetTop - winHeight){
                    $(".target_menus").addClass("fix");
                }else{
                    $(".target_menus").removeClass("fix");
                }
            })
        });
        $(".target_menus").on('click','a',function () {
            $(this).addClass('active');
            $(this).siblings().removeClass("active");
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var $target = $(this.hash);
                $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
                if ($target.length) {
                    var targetOffset = $target.offset().top;
                    $('html,body').animate({scrollTop: targetOffset},800);
                    return false;
                }
            }
        })
        var oNav = $('.target_menus'); //导航壳
        var aNav = oNav.find('a'); //导航
        var aDiv = $('#agent_message_box_left .fortarget'); //楼层
        $(window).scroll(function() {
            //可视窗口高度
            var winH = $(window).height();
            //鼠标滚动的距离
            var iTop = $(window).scrollTop();
            if(iTop >= $("#agent_message_box_left").offset().top) {
                //鼠标滑动样式改变
                aDiv.each(function() {
                    if(winH + iTop - $(this).offset().top > winH / 2) {
                        aNav.removeClass('active');
                        aNav.eq($(this).index()).addClass('active');
                    }
                })
            } else {
                aNav.removeClass('active');
                aNav.eq(0).addClass('active');
            }
        })

        $('.search-btn').click(function(){
            var agent_id='<?php echo $agent['agent_id'] ?>';
            var keyword=$('#keywords').val();
            window.open('index.php?act=agent_staff&agent_id='+agent_id+'&keyword='+keyword);
        })
        $(function(){
            document.onkeydown = function(e){
                var ev = document.all ? window.event : e;
                if(ev.keyCode==13) {
                    var keyword=$("#keywords").val();
                    if(keyword!=''){
                        var agent_id='<?php echo $agent['agent_id'] ?>';
                        var keyword=$('#keywords').val();
                        window.open('index.php?act=agent_staff&agent_id='+agent_id+'&keyword='+keyword);
                    }
                }
            }
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
<?php include(template('common_footer'));?>