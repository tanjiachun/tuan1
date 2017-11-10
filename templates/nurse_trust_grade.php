
<?php include(template('common_header'));?>
    <link rel="stylesheet" href="templates/css/admin.css">
    <div id="member_trust_header">
        <div class="member_trust_header_content">
            <a href="index.php">
                <img src="templates/images/logo.png" alt="">
            </a>
            <span>家政人员信用等级</span>
        </div>
    </div>
</div>
<div id="member_trust_section">
    <div id="member_trust_section_content">
        <div class="member_trust_content_left left">
            <ul style="border-bottom: 1px solid #ddd;">
                <li><b>家政人员信息</b></li>
                <li><b><?php echo $nurse['nurse_nickname'] ?></b> <a href="javascript:;" class="lianxi"><img style="margin:0 5px 2px 10px" src="templates/images/lianxi.png" alt="">和我联系</a></li>
                <li><b>所在地区：</b>  <?php echo $nurse_cityname ?></li>
                <li><b>信用等级：</b> <img src="<?php echo $nurse_grade['grade_icon'] ?>" alt=""></li>
                <li><b>信用积分： </b> <?php echo $nurse['nurse_score'] ?></li>
            </ul>
            <div class="nurse_trust_promise">
                <?php if($nurse['promise_state']==2) { ?>
                    <div class="nurse_trust_promise_image left">
                        <img height="80px" src="templates/images/nurse_promise.png" alt="">
                    </div>
                    <div class="nurse_trust_promise_content left">
                        该家政人员已向雇主承诺，三小时无理由退款
                    </div>
                <?php } else if($nurse['promise_state']==4) { ?>
                    <div class="nurse_trust_promise_image left">
                        <img height="80px" src="templates/images/nurse_promise.png" alt="">
                    </div>
                    <div class="nurse_trust_promise_content left">
                        该家政人员已向雇主承诺，三天无理由退款
                    </div>
                <?php } else { ?>
                <?php } ?>
            </div>
            <div class="nurse_trust_bail">
                该家政人员当前保证金余额 <span style="color:#ff6905;">￥<?php echo $nurse['nurse_bail'] ?></span>
            </div>
        </div>
        <div class="member_trust_content_right left">
            <div class="member_good_chance">
                <span><b><?php echo $nurse['nurse_nickname'] ?></b>&nbsp;&nbsp; 信用评价展示</span> <span>好评率<?php echo $good_count_chance ?>%</span>
            </div>
            <div class="orderlist-head clearfix">
                <ul>
                    <li>
                        <a href="index.php?act=nurse_trust_grade&nurse_id=<?php echo $nurse['nurse_id'] ?>&state=show"<?php echo $state=='show' ? ' class="active"' : '';?>>全部</a>
                    </li>
                    <li>
                        <a href="index.php?act=nurse_trust_grade&nurse_id=<?php echo $nurse['nurse_id'] ?>&state=one_mouth"<?php echo $state=='one_mouth' ? ' class="active"' : '';?>>最近1月</a>
                    </li>
                    <li>
                        <a href="index.php?act=nurse_trust_grade&nurse_id=<?php echo $nurse['nurse_id'] ?>&state=six_mouth"<?php echo $state=='six_mouth' ? ' class="active"' : '';?>>最近半年</a>
                    </li>
                    <li>
                        <a href="index.php?act=nurse_trust_grade&nurse_id=<?php echo $nurse['nurse_id'] ?>&state=one_year"<?php echo $state=='one_year' ? ' class="active"' : '';?>>最近1年</a>
                    </li>
                    <li>
                        <a href="index.php?act=nurse_trust_grade&nurse_id=<?php echo $nurse['nurse_id'] ?>&state=one_year_ago"<?php echo $state=='one_year_ago' ? ' class="active"' : '';?>>1年以前</a>
                    </li>
                </ul>
            </div>
            <div class="member_comment_count">
                <div>
                    <span><img src="templates/images/comment_good.png" alt="">好评</span>
                    <span><?php echo $good_count ?></span>
                </div>
                <div>
                    <span><img src="templates/images/comment_middle.png" alt="">中评</span>
                    <span><?php echo $middle_count ?></span>
                </div>
                <div>
                    <span><img src="templates/images/comment_bad.png" alt="">差评</span>
                    <span><?php echo $bad_count ?></span>
                </div>
                <div>
                    <span>退款</span>
                    <span><?php echo $refund_count ?></span>
                </div>
                <div>
                    <span>因违规被处罚</span>
                    <span>0</span>
                </div>
            </div>
        </div>

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
<script src="templates/3rd/jquery-1.11.3.min.js"></script>
<script src="templates/im/js/config.js"></script>
<script src="templates/im/js/md5.js"></script>
<script src="templates/im/js/util.js"></script>
<script>
    var a='<?php echo $this->member['yx_accid'] ?>';
    var b='<?php echo $this->member['yx_token'] ?>';
    var c='<?php echo $nurse_token['yx_accid'] ?>';
//    var a='v7yrfFoCyoCO7qaffZC2SOQo7HZVqQfC';
//    var b='mJlwZwJHk5gw9JTwej9t9ls5t11w6kCW';
//    var c='x433lytjbbddts5k65y15a9b1lgj5kt2';
    var member_id='<?php echo $this->member_id ?>';
    $(".lianxi").click(function () {
        if(member_id==0 || member_id=='' || member_id==undefined){
            Custombox.open({
                target : '#login-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300
            });
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
                toID=c,
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