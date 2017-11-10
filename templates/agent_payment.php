<?php include(template('common_header'));?>
    <div class="conwp clearfix">
        <h1 class="top-logo">
            <a href="index.php"><img src="templates/images/logo.png"></a>
            <strong>机构保证金缴纳</strong>
        </h1>
        <div class="top-progress zhmm-box" style="margin-top: 12px;height:75px;line-height: 75px;padding:0;">
        </div>
    </div>
    </div>
    <div class="content">
        <div class="conwp">
            <div class="pay-box">
                <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                <input type="hidden" id="book_sn" name="book_sn" value="<?php echo $book_sn;?>" />
                <input type="hidden" id="payment_code" name="payment_code" value="alipay" />
                <div class="pay-head">
                    <div class="money">
                        <dl>
                            <dt>
                                <span>预约单号：<?php echo $book['book_sn'];?></span>
                                <em>
                                    <strong>￥<?php echo $book['book_amount'];?></strong>
                                </em>
                            </dt>
                            <dd class="wf show">
                                <h6><?php echo $nurse['nurse_name'];?> <?php echo $nurse['member_phone'];?></h6>
                                <p>缴纳时间：<?php echo date('Y-m-d H:i', $book['add_time']);?> </p>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="trade-box" style="padding:40px 20px;">
                    <div class="step-tit">
                        <h3>支付方式</h3>
                    </div>
                    <div class="payment-list">
                        <ul class="clearfix payment-box">
                            <li class="active" payment_code="alipay">
                                <div class="payment-item"><span class="pay-icon"><img src="templates/images/alipay.jpg"></span><b></b></div>
                            </li>
<!--                            <li payment_code="weixin">-->
<!--                                <div class="payment-item"><span class="pay-icon"><img src="templates/images/wxpay.jpg"></span><b></b></div>-->
<!--                            </li>-->
                            <li payment_code="predeposit">
                                <div class="payment-item">使用余额支付<b></b></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="trade-check" style="padding:20px 0; text-align: center;">
                    <a href="javascript:agentsubmit();" class="check-btn">支付</a>
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
    <script>
        var book_submit_btn = false;
        function agentsubmit() {
            var formhash = $('#formhash').val();
            var book_sn = $('#book_sn').val();
            var payment_code = $('#payment_code').val();
            var submitData = {
                'form_submit' : 'ok',
                'formhash' : formhash,
                'book_sn' : book_sn,
                'payment_code' : payment_code,
            };
            if(book_submit_btn) return;
            book_submit_btn = true;
            $.ajax({
                type : 'POST',
                url : 'index.php?act=agent_marketing&op=payment',
                data : submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType : 'json',
                success : function(data) {
                    book_submit_btn = false;
                    if(data.done == 'true') {
                        window.location.href = 'index.php?act=payment&op=agent&book_sn='+data.book_sn;
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
                        showalert(data.msg);
                    }
                },
                timeout : 15000,
                error : function(xhr, type){
                    book_submit_btn = false;
                    showalert('网路不稳定，请稍候重试');
                }
            });
        }
    </script>
    <script>
        $(function() {
            $('.payment-box').on('click', 'li', function() {
                var payment_code = $(this).attr('payment_code');
                $('#payment_code').val(payment_code);
                $(this).addClass('active');
                $(this).siblings('li').removeClass('active');
            });
        });
    </script>
<?php include(template('common_footer'));?>