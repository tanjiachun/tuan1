<?php include(template('common_header'));?>
<link rel="stylesheet" href="templates/css/admin.css">
<style>
    .orderlist-head {
        position: relative;
        padding: 0;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }
    .orderlist-head ul li{
        padding:0;
    }
    .orderlist-head ul li a{
        font-size: 18px;
        padding:5px 30px;
        border-bottom: 2px solid transparent;
    }
    .orderlist-head ul li a.active{
        padding:5px 30px;
        color: #ff6905;
        border-bottom: 2px solid #ff6905;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
    }
    .order-tb thead th{
        background: #fff;
    }
</style>
    <div class="nurse_header">
        <a class="left" href="index.php">
            <img src="templates/images/logo.png">
        </a>
        <div class="left" style="min-width:100px;height:74px;line-height:74px;margin-left: 20px;padding-left: 20px;border-left:1px solid #ddd;font-size: 20px;font-weight: 700; ">
            服务车
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
<div id="member_manage" style="width:1180px;margin:0 auto;">
    <div id="member_manage_set" style="width:100%;margin:0;">
        <div class="member_book_set">
        <div class="orderlist">
            <div class="orderlist-head clearfix">
                <ul>
                    <li>
                        <a href="index.php?act=member_collect&state=all"<?php echo $state=='all' ? ' class="active"' : '';?>>全部家政人员 <?php echo $all_count ?><span></span></a>
                    </li>
                    <li>
                        <a href="javascript:;" style="padding:5px 0; font-weight: 300;">|</a>
                    </li>
                    <li>
                        <a href="index.php?act=member_collect&state=discount"<?php echo $state=='discount' ? ' class="active"' : '';?>>降价服务 <span></span></a>
                    </li>
                    <li>
                        <a href="javascript:;" style="padding:5px 0; font-weight: 300;">|</a>
                    </li>
                    <li>
                        <a href="index.php?act=member_collect&state=lose"<?php echo $state=='lose' ? ' class="active"' : '';?>>暂时失效 <?php echo $lose_count ?><span></span></a>
                    </li>
                </ul>

            </div>
            <div class="orderlist-body">
                <table class="order-tb">
                    <thead>
                    <tr>
                        <th width="100">家政人员简历</th>
                        <th width="200">薪资类型</th>
                        <th width="200">服务时长</th>
                        <th width="80">金额</th>
                        <th width="50">操作</th>
                    </tr>
                    </thead>
                    <?php foreach($favourite_list as $key => $value) { ?>
                        <tbody>

                        <tr class="sep-row"><td colspan="5"></td></tr>
                        <tr class="tr-th">
                            <td colspan="5">
                                <span class="check checkitem" collect_id="<?php echo $value['collect_id'];?>"><i class="iconfont icon-type"></i></span>
                                <span style="color: #000;">机构: <?php echo $agent_list[$value['agent_id']]['agent_name'] ?></span>
                                <span style="color: #000;">联系方式：<?php echo $nurse_list[$value['nurse_id']]['member_phone'] ?></span>
                                <span> <a class="lianxi" href="javascript:;" data="<?php echo $nurse_list[$value['nurse_id']]['yx_accid'] ?>"><img style="margin: 0 5px 2px 0" src="templates/images/lianxi.png" alt="">和我联系</a></span>
                            </td>
                        </tr>
                        <tr class="tr-bd">
                            <td>
                                <div class="td-inner clearfix w-300">
                                    <div class="item-pic">
                                        <a href="index.php?act=nurse&nurse_id=<?php echo $nurse_list[$value['nurse_id']]['nurse_id'] ?>" target="_blank"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'] ?>"></a>
                                    </div>
                                    <div class="item-info">
                                        <a style="overflow: inherit;margin-right: 20px;" href="index.php?act=nurse&nurse_id=<?php echo $nurse_list[$value['nurse_id']]['nurse_id'] ?>" target="_blank"><?php echo $nurse_list[$value['nurse_id']]['nurse_nickname'] ?></a>
                                        <span style="color: #000;"><?php echo $nurse_list[$value['nurse_id']]['nurse_special_service'] ?></span>
                                    </div>
                                    <div class="item_image">
                                        <?php if($nurse_list[$value['nurse_id']]['promise_state']==2) { ?>
                                            <img src="templates/images/three_days.jpg" alt="">三小时无理由
                                        <?php } elseif($nurse_list[$value['nurse_id']]['promise_state']==4) { ?>
                                            <img src="templates/images/three_days.jpg" alt="">三天无理由
                                        <?php } ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($nurse_list[$value['nurse_id']]['nurse_type']==3) { ?>
                                    每小时 <s>¥<?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?></s>
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==4) { ?>
                                    每平方 ¥<?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?>
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==5 || $nurse_list[$value['nurse_id']]['nurse_type']==6) { ?>
                                    每月 ¥<s><?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?></s> （26天） <br>
                                    团家政折扣价 <?php echo $nurse_list[$value['nurse_id']]['nurse_price']*$nurse_list[$value['nurse_id']]['nurse_discount'];?> （26天）
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==7 || $nurse_list[$value['nurse_id']]['nurse_type']==8) { ?>
                                    每次 ¥<s><?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?></s><br>
                                    团家政折扣价 <?php echo $nurse_list[$value['nurse_id']]['nurse_price']*$nurse_list[$value['nurse_id']]['nurse_discount'];?>
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==9 || $nurse_list[$value['nurse_id']]['nurse_type']==10) { ?>
                                    每人 ¥<s><?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?></s><br>
                                    团家政折扣价 <?php echo $nurse_list[$value['nurse_id']]['nurse_price']*$nurse_list[$value['nurse_id']]['nurse_discount'];?>
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==11 || $nurse_list[$value['nurse_id']]['nurse_type']==12) { ?>
                                    每个学生 ¥<s><?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?></s><br>
                                    团家政折扣价 <?php echo $nurse_list[$value['nurse_id']]['nurse_price']*$nurse_list[$value['nurse_id']]['nurse_discount'];?>
                                    <br>
                                    <?php if($nurse_list[$value['nurse_id']]['students_sale']==1) { ?>
                                        （多于一个学生半价优惠）
                                    <?php } else { ?>
                                        （多于一个学生无优惠）
                                    <?php } ?>
                                <?php } else { ?>
                                    服务费 ¥<s><?php echo $nurse_list[$value['nurse_id']]['service_price'];?></s> （+首月工资<?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?>）<br>
                                    团家政折扣价 ¥<?php echo $nurse_list[$value['nurse_id']]['service_price']*$nurse_list[$value['nurse_id']]['nurse_discount'];?>（+首月工资<?php echo $nurse_list[$value['nurse_id']]['nurse_price'];?>）
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($nurse_list[$value['nurse_id']]['nurse_type']==3) { ?>
                                    <select name="work_hours_select" class="work_hours_select">
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
                                    </select> 小时 
                                    <select name="work_mins_select" class="work_mins_select">
                                        <option value="0">0</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                        <option value="50">50</option>
                                    </select> <br>
                                    共雇佣 <input type="number" min="1" class="work_days" style="margin-top: 20px;width:100px;height:26px;border-radius: 3px;" value="1"> 天
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==4) { ?>
                                    共 <input type="number" min="1" class="work_area" value="1" style="margin-top: 20px;width:100px;height:26px;border-radius: 3px;"> 平方
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==5 || $nurse_list[$value['nurse_id']]['nurse_type']==6) { ?>
                                    <input type="hidden" class="work_duration" value="1">
                                    <select name="work_days" class="work_days" style="width: 100px;">
                                        <option value="26">26天</option>
                                        <option value="27">27天</option>
                                        <option value="28">28天</option>
                                        <option value="29">29天</option>
                                        <option value="30">30天</option>
                                    </select>
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==7 || $nurse_list[$value['nurse_id']]['nurse_type']==8) { ?>
                                    共 <input type="number" min="1" class="work_machine" value="1" style="margin-top: 20px;width:100px;height:26px;border-radius: 3px;"> 台机器
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==9 || $nurse_list[$value['nurse_id']]['nurse_type']==10) { ?>
                                    共 <input type="number" min="1" class="work_machine" value="1" style="margin-top: 20px;width:100px;height:26px;border-radius: 3px;"> 人
                                    <br>
                                    <?php if(!empty($nurse_list[$value['nurse_id']]['car_weight_list'])) { ?>
                                        <select name="work_car" class="work_car" style="margin-top: 10px;width:100px;">
                                        <?php foreach (unserialize($nurse_list[$value['nurse_id']]['car_weight_list']) as $k => $v) { ?>
                                            <option value="<?php echo $nurse_list[$value['nurse_id']]['car_price_list'][$k] ?>"><?php echo $v ?>吨</option>
                                        <?php } ?>
                                        </select>
                                    <?php } ?>
                                <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==11 || $nurse_list[$value['nurse_id']]['nurse_type']==12) { ?>
                                    <?php if($nurse_list[$value['nurse_id']]['students_state']==1) { ?>
                                        <select name="students_count" class="students_count" data="<?php echo $nurse_list[$value['nurse_id']]['students_sale'] ?>">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                     <?php } else { ?>
                                        1名学生（不支持多于一个学生）
                                      <?php } ?>
                                <?php } else{ ?>
                                    共 <select name="work_duration_select" class="work_duration_select">
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
                                    </select> 个月
                                <?php } ?>
                            </td>
                            <td>
                                <span style="color: #ff6905;">￥<b class="total_price"><?php echo $nurse_list[$value['nurse_id']]['service_price']*$nurse_list[$value['nurse_id']]['nurse_discount']+$nurse_list[$value['nurse_id']]['nurse_price'];?></b></span>
                            </td>
                            <td>
                                <a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'] ?>" class="" data="<?php echo $value['collect_id'] ?>">查看</a>
                            </td>
                        </tr>
                        </tbody>
                    <?php } ?>
                            <tbody style="margin-top: 20px;">
                            <tr class="tool-row">
                                <td colspan="5" style="border:none;"></td>
                            </tr>
                            <tr class="tool-row">
                                <style>
                                    .del_favourite_btn:hover{
                                        cursor: pointer;
                                        color: #ff6905;
                                    }
                                    .del_lose_favourite:hover{
                                        cursor: pointer;
                                        color: #ff6905;
                                    }
                                    .share:hover{
                                        cursor: pointer;
                                        color: #ff6905;
                                    }
                                </style>
                                <td colspan="11" style="border:none;margin-top:20px;background: #eee; line-height: 39px;padding-right: 0;">
                                    <span class="del_favourite_btn" style="margin-left: 30px;">删除</span>
                                    <span style="margin-left: 30px;" class="del_lose_favourite">删除失效收藏</span>
                                    <span style="margin-left: 30px;" class="share"><a href="http://www.jiathis.com/share" class="jiathis" target="_blank"><i class="iconfont icon-share"></i> 分享</a></span>
<!--                                    <span class="right pay_submit_btn" style="display: inline-block;height:39px;line-height: 39px;width:100px;text-align: center;background: #ddd;">结算</span>-->
<!--                                    <span class="right" style="margin-right: 30px;"> 总价：<b class="total_price" style="color:#ff6905;font-size: 16px;">0</b></span>-->
                                </td>
                            </tr>
                            </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
<script>
//    $(".check").click(function () {
//        $(".check").hasClass("active").removeClass("active");
//    });
</script>

<div class="alert-box" style="display:none;">
    <div class="alert alert-danger tip-title"></div>
</div>
<div class="modal-wrap w-400" id="alert-box" style="display:none;">
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

<div class="modal-wrap w-400" id="del-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="del_id" name="del_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定删除吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="delsubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="del-lose-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定删除失效收藏吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="del_losesubmit();">确定</a>
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
<input type="hidden" id="yx_accid" value="">
<script>
    var member_id='<?php echo $this->member_id ?>';
</script>
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
//    $(".checkitem").click(function () {
////        $(".checkitem").hasClass("active").removeClass("active");
////        $(this).addClass("active");
//    });
</script>
<script>
    $(".del_favourite_btn").click(function () {
        if($('.checkitem.active').length == 0) {
            showalert('请至少选择一个家政人员');
            return;
        }
        var items = '';
        $('.checkitem.active').each(function(){
            items += $(this).attr('collect_id') + ',';
        });
        items = items.substr(0, (items.length - 1));
        $("#del_id").val(items);
        Custombox.open({
            target : '#del-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function delsubmit() {
        var del_id=$("#del_id").val();
        var submitData={
            'del_id':del_id,
            'member_id':member_id
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_collect&op=del',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('删除成功');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        },1000)
                    });
                } else {
                    showwarning('del-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showwarning('del-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
    $(".del_lose_favourite").click(function () {
        Custombox.open({
            target : '#del-lose-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function del_losesubmit() {
        var submitData={
            'member_id':member_id
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_collect&op=del_lose',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('删除成功');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        },1000)
                    });
                } else {
                    showwarning('del-lose-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showwarning('del-lose-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script src="templates/3rd/jquery-1.11.3.min.js"></script>
<script src="templates/im/js/config.js"></script>
<script src="templates/im/js/md5.js"></script>
<script src="templates/im/js/util.js"></script>
<script>
    //    var a='<?php //echo $this->member['yx_accid'] ?>//';
    //    var b='<?php //echo $this->member['yx_token'] ?>//';
    var a='x433lYtjBbdDTS5k65Y15a9B1LgJ5KT2';
    var b='jd9FnBzxsnesMSmfqgkXg4yNQ11Dlnml';

    $(".lianxi").click(function () {
        if(member_id==0 || member_id=='' || member_id == undefined){
            Custombox.open({
                target : '#login-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        }
        var data=$(this).attr('data');
        $("#yx_accid").val(data);
    })
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
//            this.$loginBtn.on('click', this.validate.unbind("click").bind(this));
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
            setCookie('toID',$("#yx_accid").val());
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



































