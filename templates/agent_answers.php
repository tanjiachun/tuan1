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
                <input type="submit" id="search-btn" value="搜索" class="search-btn bg s_btn">
            </span>
        </div>
    </div>
</div>
<div class="nurse_header_choose">
    <div class="nurse_header_choose_content">
        <span><a href="index.php?act=agent_show&agent_id=<?php echo $agent['agent_id']; ?>">机构首页</a></span>
        <span><a href="index.php?act=agent_staff&agent_id=<?php echo $agent['agent_id']; ?>">全部员工(<?php echo $nurse_count ?>)</a></span>
        <span style="background: red;"><a href="index.php?act=agent_answers&agent_id=<?php echo $agent['agent_id']; ?>">机构问答</a></span>
        <span>机构号&nbsp;<?php echo $agent['agent_id'];?></span>
    </div>
</div>
<div id="agent_answers_content">
    <div id="answers_box" class="left">
       <div class="answers_box_header">
           <span>机构问答</span>
           <select name="sort" id="answers_sort">
               <option value="time">默认排序</option>
               <option value="focus">热门问答</option>
           </select>
           <a href="#put_question" class="right">我要提问</a>
       </div>
        <div class="answers_list">
            <?php foreach ($question_list as $key => $value)  { ?>
                <div class="answer_details">
                    <div class="question_message">
                        <?php if($member_list[$value['member_id']]['member_image']== '') { ?>
                            <img src="templates/images/head.png">
                        <?php } else { ?>
                            <img src="<?php echo $member_list[$value['member_id']]['member_image'];?>">
                        <?php } ?>
                        <b><?php echo $member_list[$value['member_id']]['member_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;提了一个问题</b> <span><?php echo date('Y-m-d H:i', $value['question_time']);?><img src="templates/images/browse.png" alt=""> <?php echo $value['focus_count'] ?></span>
                    </div>
                    <h3><img src="templates/images/question.png" alt=""><a href="javascript:;"><?php echo $value['question_content'] ?></a></h3>
                    <div class="answer_content">
                        <img src="templates/images/answer.png" alt=""><?php echo $value['answer_content'] ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="multi-box"><?php echo $multi;?></div>
        <div id="put_question">
            <textarea name="question" id="get_questions" cols="30" rows="10" placeholder="请输入您要提问的问题"></textarea>
            <p><span style="color:#ff6905;display: none" class="error"></span><a class="publish_question" href="javascript:;">发表问题</a></p>
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
    <script>
        var agent_id='<?php echo $agent['agent_id'] ?>';
        var page='<?php echo $page ?>';
        function selectquestion(obj, field, variable) {
            var sort=$("#answers_sort").val();
            if(field == 'page') {
                page = variable;
            } else {
                page = 1;
            }
            var data={
                'agent_id':agent_id,
                'page':page,
                'sort':sort
            }
            $.getJSON('index.php?act=agent_answers&op=search_question',data,function (data) {
                if(data.done=='true'){
                    $('.answers_list').html(data.question_html);
                    $(".multi-box").html(data.question_multi_html);
                }
            })
        }
        $("#answers_sort").change(function () {
            selectquestion(this,'page',page);
        })
        $(".publish_question").click(function(){
            var agent_id='<?php echo $agent['agent_id'] ?>';
            var question_content=$("#get_questions").val();
            var data={
                'agent_id':agent_id,
                'question_content':question_content
            }
            $.getJSON('index.php?act=agent_answers&op=get_question',data,function(data){
                if(data.done=='empty'){
                    Custombox.open({
                        target : '#login-box',
                        effect : 'blur',
                        overlayClose : true,
                        speed : 500,
                        overlaySpeed : 300,
                        open: function () {
                            setTimeout(function(){
                                window.location.href = 'index.php?act=login';
                            }, 3000);
                        }
                    });
                }else if(data.done=='false'){
                    $(".error").show();
                    $('.error').text(data.msg);
                }else if(data.done=='true'){
                    $("#get_questions").val('');
                    $(".error").show();
                    $('.error').text(data.msg);
                    setTimeout(function(){
                        $(".error").hide();
                    },5000)
                }
            })
        });
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
<?php include(template('common_footer'));?>
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
