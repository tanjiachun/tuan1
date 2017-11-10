<?php include(template('common_header'));?>
		<div class="conwp clearfix">
            <h1 class="top-logo">
                <a href="index.php"><img src="templates/images/logo.png"></a>
                <strong>结算</strong>
            </h1>
            <div class="top-progress zhmm-box">
                <div class="zh-title">
                    <ul>
                        <li class="active"><span>1</span><br>我的购物车</li>
                        <li class="active"><u></u></li>
                        <li class="active"><span>2</span><br>填写核对订单信息</li>
                        <li><u></u></li>
                        <li><span>3</span><br>成功提交订单</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="conwp">
            <div class="trade-box">
				<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
				<input type="hidden" id="cart_ids" name="cart_ids" value="<?php echo $cart_ids_str;?>" />
                <input type="hidden" id="payment_code" name="payment_code" value="alipay" />
                <input type="hidden" id="order_invoice" name="order_invoice" value="no" />
				<input type="hidden" id="red_id" name="red_id" value=""  />
                <input type="hidden" id="red_price" name="red_price" value="0"  />
				<div class="step-tit">
                    <h3>收货人信息</h3>
                    <a href="javascript:;" class="extra-r pull-right address-add">新增收货地址</a>
                </div>
                <div class="consignee-list">
                    <ul class="address-list">
                        <?php foreach($address_list as $key => $value) { ?>
                        <li>
                            <div class="consignee-item<?php echo !empty($value['address_default']) ? ' active' : '';?>">
                                <span><?php echo $value['true_name'];?></span>
                                <b></b>
                            </div>
                            <div class="consignee-detail">
                                <span class="addr-name"><?php echo $value['true_name'];?></span>
                                <span class="addr-info"><?php echo $value['area_info'].$value['address_info'];?></span>
                                <span class="addr-tel"><?php echo $value['mobile_phone'];?></span>
                                <?php if(!empty($value['address_default'])) { ?>
                                <span class="addr-default">默认地址</span>
                                <?php } ?>
                            </div>	
                            <div class="op-btns">
                            	<?php if(empty($value['address_default'])) { ?>
                                <a class="edit-consignee bluelink address-default" href="javascript:;" address_id="<?php echo $value['address_id'];?>">设为默认地址</a>
                                <?php } ?>
                                <a class="edit-consignee bluelink address-edit" href="javascript:;" address_id="<?php echo $value['address_id'];?>">编辑</a>
                                <?php if(empty($value['address_default'])) { ?>
								<a class="del-consignee bluelink address-del" href="javascript:;" address_id="<?php echo $value['address_id'];?>">删除</a>
								<?php } ?>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="hr"></div>
				<div class="step-tit">
					<h3>购物清单</h3>
				</div>
				<div class="shopping-lists">
                	<?php foreach($cart_list as $store_id => $store_cart_list) { ?>
					<div class="shopping-list clearfix">
						<div class="dis-modes">
							<h1>配送方式</h1>
							<ul class="dis-tab clearfix transport-box" id="transport_<?php echo $store_id;?>">
                            	<?php if(!empty($transport_list[$store_id])) { ?>
                            	<?php foreach($transport_list[$store_id] as $key => $value) { ?>
								<li store_id="<?php echo $store_id;?>" extend_type="<?php echo $key;?>" extend_price="<?php echo $value;?>"><?php echo $extend_name[$store_id][$key];?>(￥<?php echo $value;?>)<b></b></li>
								<?php } ?>
                                <?php } else { ?>
                                <li store_id="<?php echo $store_id;?>" extend_type="free" extend_price="0.00">免运费(￥0.00)<b></b></li>
                                <?php } ?>
                            </ul>
                            <input type="hidden" class="extend_type" id="extend_type_<?php echo $store_id;?>" store_id="<?php echo $store_id;?>" value=""  />
                            <input type="hidden" class="extend_price" id="extend_price_<?php echo $store_id;?>" store_id="<?php echo $store_id;?>" value="0"  />
                            <?php if(!empty($coupon_list[$store_id])) { ?>
							<div class="dis-coupon">
								<h1>优惠券<span><?php echo count($coupon_list[$store_id]);?>张可使用</span></h1>
								<div class="select-class coupon-box">
									<a href="javascript:;" class="select-choice">请选择优惠券<i class="select-arrow"></i></a>
									<div class="select-list" style="display: none">
										<div class="use-coupon">
                                        	<?php foreach($coupon_list[$store_id] as $key => $value) { ?>
											<div class="use-coupon-item radio" store_id="<?php echo $store_id;?>" coupon_id="<?php echo $value['coupon_id'];?>" coupon_price="<?php echo $value['coupon_discount'];?>" coupon_title="<?php echo $value['coupon_title'];?>"><i class="iconfont icon-type"></i><?php echo $value['coupon_title'];?><?php echo !empty($value['discount_goods'])  ? '<strong>只限'.$value['discount_goods'].'</strong>' : '';?></div>
                                            <?php } ?>
										</div>
									</div>
                                    <input type="hidden" class="coupon_id" id="coupon_id_<?php echo $store_id;?>" store_id="<?php echo $store_id;?>" value=""  />
                                	<input type="hidden" class="coupon_price" id="coupon_price_<?php echo $store_id;?>" store_id="<?php echo $store_id;?>" value="0"  />
								</div>                               
							</div>
                            <?php } ?>
						</div>
						<div class="goods-list">
							<h1>商家：<?php echo $store_list[$store_id];?></h1>
                            <?php foreach($store_cart_list as $key => $value) { ?>
							<div class="goods-list-item clearfix">
								<div class="goods-item-img">
									<img src="<?php echo $value['goods_image'];?>">
								</div>
								<div class="goods-item-name">
                                	<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>"><?php echo $value['goods_name'];?></a>
                                	<?php if(!empty($value['spec_info'])) { ?>
									<?php foreach($value['spec_info'] as $subkey => $subvalue) { ?>
                                    <p class="item-props"><?php echo $subvalue;?></p>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="goods-item-price"><strong>￥<?php echo $value['spec_goods_price'];?></strong></div>
								<div class="goods-item-num">x<?php echo $value['goods_num']; ?></div>
								<div class="goods-item-status">有货</div>
							</div>
							<?php } ?>
						</div>
					</div>
                    <?php } ?>
				</div>
				<?php if(!empty($red_list)) { ?>
                <div class="hr"></div>
                <div class="step-tit">
                    <h3>我的红包</h3>
                </div>
                <div class="payment-list red-box">
					<?php foreach($red_list as $key => $value) { ?>
					<div class="use-coupon-item radio" red_id="<?php echo $value['red_id'];?>" red_price="<?php echo $value['red_price'];?>" red_title="<?php echo $value['red_title'];?>"><i class="iconfont icon-type"></i><?php echo $value['red_title'];?>（<?php echo $value['red_price'];?>元）</div>
					<?php } ?>
                </div>
				<?php } ?>
                <div class="hr"></div>
                <div class="step-tit">
                    <h3>发票</h3>
                </div>
                <div class="invoice-box">
                    <div class="select-box invoice-radio-box">
                        <span class="radio active" order_invoice="no"><i class="iconfont icon-type"></i>不开发票</span>
                        <span class="radio" order_invoice="yes"><i class="iconfont icon-type"></i>要开发票</span>
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
                    <h3>支付方式</h3>
                </div>
                <div class="payment-list">
                    <ul class="clearfix payment-box">
                        <li class="active" payment_code="alipay">
                            <div class="payment-item"><span class="pay-icon"><img src="templates/images/alipay.jpg"></span><b></b></div>
                        </li>
                        <li payment_code="weixin">
                            <div class="payment-item"><span class="pay-icon"><img src="templates/images/wxpay.jpg"></span><b></b></div>
                        </li>
                        <li payment_code="predeposit">
                            <div class="payment-item">使用余额支付<b></b></div>
                        </li>
                    </ul>
                </div>
			</div>
            <div class="order-summary">
                <div class="statistic pull-right">
                    <div class="list">
                        <label>商品金额：</label>
                        <em id="goods_amount">￥<?php echo priceformat($goods_amount);?></em>
                    </div>
                    <div class="list">
                        <label>运费金额：</label>
                        <em id="transport_amount">￥0.00</em>
                    </div>
                    <div class="list">
                        <label>优惠金额：</label>
                        <em id="discount_amount">-￥0.00</em>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="trade-foot">
                    <div class="fc-price-info">
                        <span class="price-tit">应付总额：</span>
                        <span class="price-num" id="order_amount" goods_amount="<?php echo priceformat($goods_amount);?>">￥<?php echo priceformat($goods_amount);?></span>
                    </div>
                    <div class="fc-consignee-info address-info">
                        <span>寄送至：<?php echo $address['area_info'].$address['address_info'];?></span>
                        <span>收货人：<?php echo $address['true_name'];?> <?php echo $address['mobile_phone'];?></span>
                    </div>
                </div>
                <div class="trade-check">
                    <a href="javascript:checksubmit();" class="check-btn">提交订单</a>
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
	<script type="text/javascript" src="templates/js/home/cart.js"></script>
<?php include(template('common_footer'));?>