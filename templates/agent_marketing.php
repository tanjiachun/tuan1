<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
    <div class="agent_set_center_header">
        <div class="left" style="margin-top: 10px;">
            <a href="index.php">
                <img src="templates/images/logo.png">
            </a>
            <span style="font-size: 18px;font-weight: 500;margin-left: 5px;">机构管理平台</span>
        </div>
        <div class="left agent_message_show">
            <span>被关注 <?php echo $agent['agent_focusnum'] ?></span>
            <span>员工总数 <?php echo $nurse_count ?></span>
            <span>浏览数 <?php echo $agent['agent_viewnum'] ?></span>
            <span>累计交易 <?php echo $book_count ?></span>
        </div>
        <div class="left agent_code_show">
            <span>机构编号 <?php echo $agent['agent_id'] ?></span>
            <span>有<?php echo $question_count ?>个问题待回答 <a href="index.php?act=agent_question" style="background: #ff6905;color:#fff;padding:0 5px;">回答</a></span>
        </div>
    </div>
</div>
<div id="agent_manage">
    <div id="agent_manage_content">
        <div id="agent_manage_sidebar" class="left">
            <div class="agent_manage_logo">
                <img width="100px" height="100px" src="<?php echo $agent['agent_logo'] ?>" alt="">
            </div>
            <ul class="sidebar_list">
                <li><a href="index.php?act=agent_center">首页编辑</a></li>
                <li><a href="index.php?act=agent_question">机构问答</a></li>
                <li><a href="index.php?act=agent_phone_set">手机设置</a></li>
                <li class="staff_set_list">
                    <a class="list_show">员工管理</a>
                    <ul style="display: none;">
                        <li><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
                        <li><a href="index.php?act=agent_nurse_audit" style="font-size: 12px;">· 审核员工</a></li>
                        <li><a href="index.php?act=agent_nurse_add" style="font-size: 12px;">· 新建员工</a></li>
                    </ul>
                </li>
                <li><a href="index.php?act=agent_book">全部订单</a></li>
                <li><a href="index.php?act=agent_evaluate">评价管理</a></li>
                <li><a href="index.php?act=agent_refund">退款查询</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_marketing">营销管理</a></li>
                <li><a href="index.php?act=agent_profit">财务中心</a></li>
                <li><a href="index.php?act=agent_invoice">发票管理</a></li>
            </ul>
            <script>
                $(".list_show").click(function () {
                    if(!$(".staff_set_list ul").is(":hidden")){
                        $(".staff_set_list ul").fadeOut();
                        $(".staff_set_list img").attr('src','templates/images/toBW.png');
                    }else{
                        $(".staff_set_list ul").fadeIn();
                        $(".staff_set_list img").attr('src','templates/images/toTopW.png');
                    }
                })
            </script>
        </div>
        <div id="agent_manage_set" class="left form-list">
            <div class="bail_pay">
                <p><span>服务保证金</span></p>
                <div class="bail_select_box">
                    额度¥
                    <select name="bail_select" id="bail_select">
                        <option value="2000">2000</option>
                        <option value="5000">5000</option>
                        <option value="10000">10000</option>
                        <option value="20000">20000</option>
                    </select>
                    <span class="bail_pay_btn">确认缴纳</span>
                </div>
                <h3>*缴纳保证金可以得到客户更多的信赖，同时根据数额有机会获得一些特色服务。</h3>
                <div class="bail_refund">
                    保证金额 <span class="agent_deposit"><?php echo $agent['agent_deposit'] ?></span> <span class="bail_refund_btn">[退款]</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-wrap w-600" id="book-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-message">保证金可退，如有疑问可跟客服联系</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default bookdel" book_sn="" onclick="Custombox.close();">取消支付</a>
        <a class="btn btn-primary bookpayment" book_sn="">支付保证金</a>
    </div>
</div>

<div class="modal-wrap w-400" id="refund-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="agent_deposit" value="">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-message">您确定要退保证金吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default"  onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="refund();">确定</a>
    </div>
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
<div class="alert-box" style="display:none;">
    <div class="alert alert-danger tip-title"></div>
</div>
<script>
</script>
<script>
    $(".staff_set_list span").click(function () {
        if(!$(".staff_set_list ul").is(":hidden")){
            $(".staff_set_list ul").fadeOut();
            $(".staff_set_list img").attr('src','templates/images/toBW.png');
        }else{
            $(".staff_set_list ul").fadeIn();
            $(".staff_set_list img").attr('src','templates/images/toTopW.png');
        }
    });
    $(".bail_pay_btn").click(function () {
        var deposit_amount=$("#bail_select").val();
        var submitData={
            'deposit_amount':deposit_amount
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_marketing&op=order',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                if(data.done == 'true') {
                    $('.bookpayment').attr('book_sn', data.book_sn);
                    $('.bookdel').attr('book_sn', data.book_sn);
                    Custombox.open({
                        target : '#book-box',
                        effect : 'blur',
                        overlayClose : true,
                        speed : 500,
                        overlaySpeed : 300,
                    });
                } else if(data.done == 'login') {
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
                        },
                    });
                } else {
                    if(data.id == 'system') {
                        showalert(data.msg);
                    }
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
    $(".bookdel").click(function () {
        var book_sn=$(this).attr('book_sn');
        $.getJSON('index.php?act=agent_marketing&op=cancel',function (data) {
            if(data.done=='true'){
                Custombox.close();
            }else{
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
    $('.bookpayment').on('click', function() {
        var book_sn = $(this).attr('book_sn');
        window.location.href = 'index.php?act=agent_marketing&op=payment&book_sn='+book_sn;
    });
</script>
<script>
    $(".bail_refund_btn").click(function () {
        var data=$(".agent_deposit").html();
        $("#agent_deposit").val(data);
        Custombox.open({
            target : '#refund-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function refund() {
        var refund_amount=$("#agent_deposit").val();
        console.log(refund_amount);
        var submitData={
            'refund_amount':refund_amount
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_marketing&op=deposit_refund',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                if(data.done=='true') {
                    Custombox.close(function () {
                        $('.alert-box .tip-title').html('申请成功，请耐心等待审核');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    });
                }else{
                    showwarning('refund-box', data.msg);
                }

            },
            timeout : 15000,
            error : function(xhr, type){
                showwarning('refund-box', '网络不稳定，请稍后重试');
            }
        });
    }
</script>