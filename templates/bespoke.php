<?php include(template('common_header'));?>
        <div class="conwp clearfix">
            <h1 class="top-logo">
                <a href="index.php"><img src="templates/images/logo.png"></a>
                <strong>预约</strong>
            </h1>
            <div class="top-progress zhmm-box">
                <div class="zh-title">
                    <ul>
                        <li class="active"><span>1</span><br>提交预定</li>
                        <li><u></u></li>
                        <li><span>2</span><br>支付确认</li>
                        <li><u></u></li>
                        <li><span>3</span><br>完成预定</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="conwp">
            <div class="trade-box">
            	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
				<input type="hidden" id="room_id" name="room_id" value="<?php echo $room_id;?>" />
				<input type="hidden" id="room_price" name="room_price" value="<?php echo $room['room_price'];?>" />
				<input type="hidden" id="red_id" name="red_id" value=""  />
                <input type="hidden" id="red_price" name="red_price" value="0"  />
                <input type="hidden" id="bespoke_invoice" name="bespoke_invoice" value="no" />
                <div class="step-tit">
                    <h3>联系人信息</h3>
                </div>
                <div class="book-form clearfix">
                    <div class="book-form-item full-item">
                        <label>预定人姓名：</label>
                        <div class="book-form-value">
                            <input type="text" id="bespoke_name" name="bespoke_name" value="">
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                    <div class="book-form-item full-item">
                        <label>预定人手机号：</label>
                        <div class="book-form-value">
                            <input type="text" id="bespoke_phone" name="bespoke_phone" value="">
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
                        <label>入驻时间：</label>
                        <div class="book-form-value">
                            <input type="text" id="live_time" name="live_time" placeholder="选择入驻时间" value="" readonly="true">
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                    <div class="book-form-item full-item">
                        <label>入驻时长：</label>
                        <div class="book-form-value">
                            <input type="text" id="live_duration" name="live_duration" value="1" class="w-50" onKeyUp="changeQuantity('live_duration');" orig="1"> 个月
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                    <div class="book-form-item full-item">
                        <label>需要床位：</label>
                        <div class="book-form-value">
                            <input type="text" id="bed_number" name="bed_number" value="1" class="w-50" onKeyUp="changeQuantity('bed_number');" orig="1" room_storage="<?php echo $room['room_storage'];?>">
                            <strong class="red">共有<?php echo $room['room_storage'];?>个床位</strong>
                            <div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                    <div class="bed-box">
                        <div class="book-form-item full-item">
                            <label>姓名：</label>
                            <div class="book-form-value">
                                <input class="error-input contact_name" type="text">
                                <span class="red"></span>
                                <span style="padding-left: 20px;">身份证号码：</span>
                                <input class="error-input contact_cardid" type="text">
                                <span class="red"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="b-tips"><strong>取消修改说明：</strong>订单提交后我们立即操作扣款，如订单不确认将全额退款至付款账户，如需修改取消，<span class="red">请在入住前一天18：00以前通知我们更改</span>，我们将收取您第一天的房费。</p>
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
                <div class="hr"></div>
                <div class="step-tit">
                    <h3>发票</h3>
                </div>
                <div class="invoice-box">
                    <div class="select-box invoice-radio-box">
                        <span class="radio active" bespoke_invoice="no"><i class="iconfont icon-type"></i>不开发票</span>
                        <span class="radio" bespoke_invoice="yes"><i class="iconfont icon-type"></i>要开发票</span>
                        <em class="t-tips">如需修改，请先选择不开发票(如商品由第三方卖家销售，发票内容由其卖家决定，发票由卖家开具并寄出)。</em>
                    </div>
                    <div class="invoice-form" style="display:none;">
                        <div class="invoice-form-item">
                            <span>发票抬头</span>
                            <input id="invoice_title" name="invoice_title" type="text">                            
							<div class="Validform-checktip Validform-wrong"></div>
							<em class="t-tips">遵循税务局相关规定，发票抬头必须为个人姓名或公司名称</em>
                        </div>
                        <div class="invoice-form-item">
                            <span>发票明细</span>
                            <input id="invoice_content" name="invoice_content" type="text">
							<div class="Validform-checktip Validform-wrong"></div>
                        </div>
                        <div class="invoice-form-item">
                            <span>收件人</span>
                            <input id="invoice_membername" name="invoice_membername" type="text">
							<div class="Validform-checktip Validform-wrong"></div>
                        </div>
                        <div class="invoice-form-item">
                            <span>邮寄地址</span>
                            <div class="first-province-box" prefix="invoice" style="display:inline-block">
                            	<div class="select-class">
                                    <a href="javascript:;" class="select-choice">-省份-<i class="select-arrow"></i></a>
                                    <div class="select-list" style="display: none">
                                        <ul>
                                            <li field_value="">-省份-</li>
                                            <?php foreach($province_list as $key => $value) { ?>
                                            <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>    
                            <div class="first-city-box" style="display:inline-block"></div>
                    		<div class="first-area-box" style="display:inline-block"></div>
                            <input type="hidden" id="invoice_provinceid" name="invoice_provinceid" value="" />
							<input type="hidden" id="invoice_cityid" name="invoice_cityid" value="" />
							<input type="hidden" id="invoice_areaid" name="invoice_areaid" value="" />
							<div class="Validform-checktip Validform-wrong"></div>
                        </div>
                        <div class="invoice-form-item">
                            <span>&nbsp;</span>
                            <input id="invoice_address" name="invoice_address" type="text">
							<div class="Validform-checktip Validform-wrong"></div>
                        </div>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="step-tit">
                    <h3>确认信息</h3>
                </div>
                <div class="trade-goods">
                    <div class="cart-head clearfix">
                        <div class="th th-item">
                            客房信息
                        </div>
                        <div class="th th-info">
                            单价
                        </div>
                        <div class="th th-price">
                            数量
                        </div>
                        <div class="th th-amount">
                            时长
                        </div>
                        <div class="th th-sum">
                            小计
                        </div>
                    </div>
                    <div class="cart-item-list">
                        <div class="cart-list clearfix">
                            <div class="td td-item">
                                <div class="td-inner clearfix">
                                    <div class="item-pic">
                                        <img src="<?php echo $room['room_image'];?>">
                                    </div>
                                    <div class="item-info">
                                        <?php echo $room['room_name'];?>
                                    </div>
                                    <div class="item-info">
                                        机构：<?php echo $pension['pension_name'];?>
                                    </div>
                                    <div class="item-info">
                                       	房型：<?php echo $person_array[$pension['pension_person']];?>
                                    </div>
                                </div>
                            </div>
                            <div class="td td-info">
                                <div class="item-props">
                                   <strong class="item-price">￥<?php echo $room['room_price'];?></strong> 
                                </div>
                            </div>
                            <div class="td td-price">
                                <span id="room_number">1</span>
                            </div>
                            <div class="td td-amount">
                                <span id="room_duration">1</span>
                            </div>
                            <div class="td td-sum">
                                <strong class="item-price" id="room_amount">￥<?php echo priceformat($room['room_price'], 2);?></strong>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="order-summary">
                <div class="trade-foot">
                    <div class="fc-price-info">
                        <span class="price-tit">预约金额：</span>
                        <span class="price-num" id="bespoke_amount">￥<?php echo priceformat($room['room_price'], 2);?></span>
                    </div>
                </div>
                <div class="trade-check">
                    <a href="javascript:checksubmit();" class="check-btn">提交预定</a>
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
	<link href="templates/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="templates/js/jquery.datetimepicker.js"></script>
	<script type="text/javascript" src="templates/js/home/bespoke.js"></script>
<?php include(template('common_footer'));?>