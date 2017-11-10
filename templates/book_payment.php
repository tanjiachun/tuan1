<?php include(template('common_header'));?>
		<div class="conwp clearfix">
			<h1 class="top-logo">
				<a href="index.php"><img src="templates/images/logo.png"></a>
				<strong>支付</strong>
			</h1>
			<div class="top-progress zhmm-box" style="margin-top: 12px;height:75px;line-height: 75px;padding:0;">
                <img src="templates/images/lc2.png" alt="">
<!--                <div class="zh-title">-->
<!--                    <ul>-->
<!--                        <li class="active"><span>1</span><br>提交预约</li>-->
<!--                        <li class="active"><u></u></li>-->
<!--                        <li class="active"><span>2</span><br>见面并确定</li>-->
<!--                        <li><u></u></li>-->
<!--                        <li><span>3</span><br>完成预定</li>-->
<!--                    </ul>-->
<!--                </div>-->
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
                                <p>开始服务时间：<?php echo date('Y-m-d H:i', $book['add_time']);?>  服务时长：<?php echo $book['work_duration'];?>个月</p>
                            </dd>
                        </dl>
                    </div>
                </div>
				<p class="b-tips">为了保证服务的质量，请先确保和家政人员充分的电话沟通或面试过后，再选择支付哦</p>
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
                    <a href="javascript:booksubmit();" class="check-btn">支付</a>
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
    <script type="text/javascript" src="templates/js/home/payment.js"></script>
<?php include(template('common_footer'));?>