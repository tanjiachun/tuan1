<?php include(template('common_header'));?>
        <div class="conwp clearfix">
            <h1 class="top-logo">
                <a href="index.php"><img src="templates/images/logo.png"></a>
                <strong>预约</strong>
            </h1>
            <div class="top-progress zhmm-box">
                <div class="zh-title">
                    <ul>
                        <li class="active"><span>1</span><br>提交预约</li>
                        <li><u></u></li>
                        <li><span>2</span><br>见面并确定</li>
                        <li><u></u></li>
                        <li><span>3</span><br>完成预定</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="conwp">
            <div class="trade-box" style="padding: 10px 20px 10px;">
            	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
				<input type="hidden" id="nurse_id" name="nurse_id" value="<?php echo $nurse_id;?>" />
				<input type="hidden" id="deposit_amount" name="deposit_amount" value="<?php echo $grade['deposit_amount'];?>" />
				<input type="hidden" id="red_id" name="red_id" value=""  />
                <input type="hidden" id="red_price" name="red_price" value="0"  />
                <div class="step-tit">
                    <h3>联系人信息</h3>
                </div>
                <div class="book-form clearfix">
                    <div class="book-form-item full-item">
                        <label>预约人手机号：</label>
                        <div class="book-form-value">
                            <input type="text" id="book_phone" name="book_phone" value="<?php echo $this->member['member_phone'];?>">
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="step-tit">
                    <h3>预定信息</h3>
                </div>
                <div class="book-form clearfix">
                	<div class="book-form-item full-item">
                        <label>家政人员姓名：</label>
                        <div class="book-form-value">
                            <?php echo $nurse['nurse_name'];?>
                        </div>
                    </div>
                    <div class="book-form-item full-item">
                        <label>开始服务时间：</label>
                        <div class="book-form-value">
                            <input type="text" id="work_time" name="work_time" placeholder="选择开始服务时间" value="" readonly="true">
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                    <div class="book-form-item full-item">
                        <label>服务时长：</label>
                        <div class="book-form-value">
                            <input type="text" id="work_duration" name="work_duration" value="1" class="w-50"> 个月
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                    <div class="book-form-item full-item">
						<label>额外服务</label>
						<div class="book-form-value">
                        	<ul class="dis-tab clearfix service-box">
                            	<?php foreach($service_list as $key => $value) { ?>
								<li service_id="<?php echo $value['service_id'];?>">
									<?php echo $value['service_name'];?>
                                	<b></b>
                                </li>
                                <?php } ?>
                            </ul>
						</div>
					</div>
					<div class="book-form-item full-item">
						<label>预定金</label>
						<div class="book-form-value">
							￥<?php echo $grade['deposit_amount'];?>
                            <p class="b-tips">为了确保家政人员的服务质量，我们提前替家政人员向您收取200元预订金。您和家政人员结算工资时扣除200元即可。</p>
						</div>
                    </div>
                    <div class="book-form-item full-item">
                        <label>雇主情况：</label>
                        <div class="book-form-value">
                            <textarea rows="5" id="book_message" name="book_message" placeholder="介绍被服务人员的基本情况，如身体状况，自理能力等等"></textarea>
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                </div>
				<p class="b-tips">如果您对预订金还有疑问，欢迎您随时联系我们的客服人员咨询。客服电话：<?php echo $this->setting['site_phone'];?></p>
                <div class="red-box">
					<?php if(!empty($red_list)) { ?>
					<div class="hr"></div>
					<div class="step-tit">
						<h3>我的红包</h3>
					</div>
					<?php foreach($red_list as $key => $value) { ?>
					<div class="use-coupon-item radio" red_id="<?php echo $value['red_id'];?>" red_price="<?php echo $value['red_price'];?>" red_title="<?php echo $value['red_title'];?>"><i class="iconfont icon-type"></i><?php echo $value['red_title'];?>（<?php echo $value['red_price'];?>元）</div>
					<?php } ?>
					<?php } ?>
				</div>
            </div>
            <div class="order-summary">
                <div class="trade-foot">
                    <div class="fc-price-info">
                        <span class="price-tit">预约金额：</span>
                        <span class="price-num" id="book_amount">￥<?php echo $grade['deposit_amount'];?></span>
                    </div>
                </div>
                <div class="trade-check">
                    <a href="javascript:checksubmit();" class="check-btn">提交预定</a>
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
					<h3 class="tip-message">请等待阿姨联系您，进一步沟通服务细节</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
        	<a class="btn btn-default bookwait" book_sn="">等待沟通</a>
			<a class="btn btn-primary bookpayment" book_sn="">支付定金</a>			
		</div>
	</div>
    <div class="modal-wrap w-600" id="wait-box" style="display:none;">
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
                    	<i class="iconfont icon-info"></i>
                    </span>
					<h3 class="tip-title">当沟通完成，您可以在【个人中心】-【阿姨预约订单】中，完成定金支付。</h3>
				</div>
            </div>
		</div>
        <div class="modal-ft tc">
             <a class="btn btn-primary" href="index.php?act=order&op=book">知道了</a>
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

	<link href="templates/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="templates/js/jquery.datetimepicker.js"></script>
	<script type="text/javascript" src="templates/js/home/book.js"></script>
<?php include(template('common_footer'));?>