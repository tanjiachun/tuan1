<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="applicable-device"content="pc,mobile"/>
    <meta name="renderer" content="webkit" />
    <link rel="shortcut icon" type="image/x-icon" href="templates/images/favicon.ico"/>
	<title><?php echo $this->setting['site_name'];?></title>
    <link rel="stylesheet" type="text/css" href="templates/css/common.css">
    <link rel="stylesheet" type="text/css" href="templates/css/style.css">
    <link rel="stylesheet" type="text/css" href="templates/css/new.css">
    <link rel="stylesheet" type="text/css" href="templates/css/swapperstyle.css">
    <link rel="stylesheet" type="text/css" href="templates/css/swipeslider.css">
    <link rel="stylesheet" type="text/css" href="templates/css/custombox.min.css">
    <script type="text/javascript" src="templates/js/jquery.js"></script>
	<script type="text/javascript" src="templates/js/custombox.min.js"></script>
    <script type="text/javascript" src="templates/js/legacy.min.js"></script>
	<script type="text/javascript" src="templates/js/common.js"></script>
    <style>
        .right{float:right;}
        .left{float:left;}
        .clear{clear:both;}
    </style>
</head>
<body class="<?php echo $bodyclass;?>">
	<?php if($curmodule == 'home') { ?>
	<div class="header">
		<div class="header-top">
			<div class="conwp clearfix">
                <?php if(empty($this->member['member_id'])) { ?>
                <div class="top-left">
                        <span style="color: #ff6905;">亲！</span>
                        <span ><a style="color: #ff6905;" href="index.php?act=login">请登录</a></span>
                        <span><a href="index.php?act=register">免费注册</a></span>
<!--                        <span><a href="index.php?act=register&next_step=nurse">家政人员注册</a></span>-->
                        <span><a href="javascript:;">手机APP下载</a></span>
                </div>
                    <ul class="top-right">
<!--                        <li><h5 class="top-drop-box"></h5></li>-->
                        <li><h5><a href="index.php?act=agent" style="color: #ff6905;margin-right:10px;">家政机构入驻</a></h5></li>
                        <?php foreach($this->setting['service_qq'] as $key => $value) { ?>
                            <p class="right">
                                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $value;?>&site=qq&menu=yes">
                                    <span class="toolkf">在线客服</span>
                                </a>
                            </p>
                        <?php } ?>
                        <p class="right" style="margin-right:5px;">全国统一客服热线:<b>400-960-9399</b></p>

                    </ul>
                    <?php } else { ?>
                <div class="top-left">
                    <span> <a href="index.php"><img style="margin:0 5px 4px 0;" src="templates/images/return.png" alt="">返回首页</a></span>
                        <span class="persion_detail">您好，<a style="padding:6px 2px;" href="index.php?act=member_center"><?php echo empty($this->member['member_nickname']) ? $this->member['member_phone'] : $this->member['member_nickname'] ?><img style="margin:0 0 3px 5px;"  src="templates/images/arrow.png" alt=""></a>
                                <div class="persion_details">
                                    <div style="overflow: hidden;min-height:80px;">
                                        <div class="persion_image left" style="height:80px;line-height:80px;">
                                            <?php if(empty($this->member['member_avatar'])) { ?>
                                                <span><img width="52px" height="52px" src="templates/images/head.png" alt=""></span>
                                            <?php } else { ?>
                                                <span><img width="52px" height="52px" src="<?php echo $this->member['member_avatar']; ?>" alt=""></span>
                                            <?php } ?>
                                         </div>
                                        <div class="persion_level left">
                                            <p>雇主等级<img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""></p>
                                            <p>家政等级<img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""></p>
                                            <p>机构等级<img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""></p>
                                            <p style="line-height: 30px;"><a href="index.php?act=member_wallet">我的钱包 <img style="margin:0 0 4px 5px;" src="templates/images/money.png" alt=""></a></p>
                                        </div>
                                     </div>
                                    <div style="height:40px;overflow: hidden;line-height:40px;">
                                        <span style="margin-left:20px;"><a href="index.php?act=member_center">账号管理</a></span><span style="float:right;margin-right:20px;"><a href="index.php?act=logout">退出登录</a></a></span>
                                    </div>
                                </div>
                        </span>
                        <span><a href="index.php?act=member_set">设置<img style="margin:0 0 3px 5px;" src="templates/images/set.png" alt=""></a></span>
                        <span style="position: relative;"><a href="index.php?act=message_center">消息<img style="margin:0 0 3px 5px;" src="templates/images/message.png" alt=""><div class="message_count">0</div></a></span>
                </div>
                    <ul class="top-right">
                        <li style="margin-right: 20px;"><a href="javascript:;" class="lianxi"><img style="margin: 0 5px 3px 0" src="templates/images/lianxi.png" alt="">我的聊天</a></li>
                        <li style="margin-right: 10px;"><a href="index.php?act=member_wallet">我的交易记录</a></li>
                        <li class="collect" style="margin-right: 10px;position:relative;">
                            <div class="collect_connect">
                                <p style="font-size: 12px;margin-left: 10px;">最近加入的家政人员：</p>
                                <div class="collect_list">

                                </div>
                                <div class="collect_more">

                                </div>
                                <div class="collect_btn">
                                    <a href="index.php?act=member_collect">查看我的服务车</a>
                                </div>
                            </div>
                            <img style="margin:0 5px 3px 0;"  src="templates/images/service_car_b.png" alt=""><a href="index.php?act=member_collect">服务车<img style="margin:0 0 3px 5px;"  src="templates/images/arrow.png" alt=""></a></li>
                        <li style="margin-right: 10px;"><a href="index.php?act=member_book">我的订单<img style="margin:0 0 3px 5px;"  src="templates/images/arrow.png" alt=""></a></li>
                        <?php if(empty($this->nurse['nurse_id'])) { ?>
<!--                            <li style="margin-right: 10px;"><a href="index.php?act=nurse&op=register">申请家政工作</a></li>-->
                        <?php } else { ?>
                        <?php } ?>
                        <?php foreach($this->setting['service_qq'] as $key => $value) { ?>
                            <p class="right">
                                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $value;?>&site=qq&menu=yes">
                                    <span class="toolkf">咨询客服</span>
                                </a>
                            </p>
                        <?php } ?>

                    </ul>
                    <?php } ?>

			</div>
		</div>
	<?php } elseif($curmodule == 'member') { ?>
	<div class="register">
	<?php } elseif($curmodule == 'profile') { ?>
	<link rel="stylesheet" type="text/css" href="templates/css/admin.css">
	<div class="usercent-head clearfix">
		<div class="conwp">
			<div class="fl">
				<h1>团家政<span>会员中心</span></h1>
			</div>
			<div class="fr">
				<a href="javascript:;"><?php echo $this->member['member_phone'];?></a><em>|</em>
				<a href="index.php?act=logout">退出</a><em>|</em>
				<a href="index.php">返回首页</a>
			</div>
		</div>
	</div>
    <?php } ?>
        <script>
            //页头信息获取
            var member_id = '<?php echo $this->member_id;?>';
            $.getJSON('index.php?act=favorite&op=common_nurse',{'member_id':member_id},function (data) {
                if(data.collect_html==""){
                    html='<p style="text-align: center;padding-top: 40px;">服务车空空如也~</p>'
                    $(".collect_list").html(html);
                }else{
                    $(".collect_list").html(data.collect_html);
                }
                $(".collect_more").html(data.collect_more_html);
                $(".persion_level").html(data.grade_html);
                })
        </script>
        <script>
            $.getJSON('index.php?act=message_center&op=message_count',{'member_id':member_id},function (data) {
                if(data.done=='true'){
                    if(data.count>=100){
                        $(".message_count").html('...');
                    }else{
                        $(".message_count").html(data.count);
                    }
                }
            });
        </script>
        <script src="templates/3rd/jquery-1.11.3.min.js"></script>
        <script src="templates/im/js/config.js"></script>
        <script src="templates/im/js/md5.js"></script>
        <script src="templates/im/js/util.js"></script>
        <script>
            var a='<?php echo $this->member['yx_accid'] ?>';
            var b='<?php echo $this->member['yx_token'] ?>';
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
//                    setCookie('toID',c);
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
