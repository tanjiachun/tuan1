<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="order-track">
				<div class="track-lcol">
					<div class="p-item clearfix">
						<div class="p-img">
							<a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>">
								<img src="<?php echo $goods['goods_image'];?>" alt="">
							</a>
						</div>
						<div class="p-info">
							<ul>
								<li>承运方：<?php echo $order['express_name'];?></li>
								<li>货运单号：<?php echo $order['shipping_code'];?></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="track-rcol">
					<div class="track-list">
						<ul>
							<?php foreach($delivery_data as  $key => $value) { ?>
							<li>
								<i class="node-icon"></i>
								<span class="time"><?php echo $value['time'];?></span>
								<span class="txt"><?php echo $value['context'];?></span>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="order-info">
				<dl>
					<dt>收货人信息</dt>
					<dd>
						<div class="item clearfix">
							<label class="label">收货人：</label>
							<div class="fl"><?php echo $order_address['true_name'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">地址：</label>
							<div class="fl"><?php echo $order_address['area_info'].$order_address['address_info'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">手机号码：</label>
							<div class="fl"><?php echo $order_address['mobile_phone'];?></div>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>订单信息</dt>
					<dd>
						<?php if(!empty($order['payment_name'])) { ?>
						<div class="item clearfix">
							<label class="label">付款方式：</label>
							<div class="fl"><?php echo $order['payment_name'];?></div>
						</div>
						<?php } ?>
						<?php if(!empty($used_coupon['coupon_title'])) { ?>
						<div class="item clearfix">
							<label class="label">优惠券：</label>
							<div class="fl"><?php echo $used_coupon['coupon_title'];?></div>
						</div>
						<?php } ?>
						<?php if(!empty($used_red['coupon_title'])) { ?>
						<div class="item clearfix">
							<label class="label">红包：</label>
							<div class="fl"><?php echo $used_red['red_title'];?></div>
						</div>
						<?php } ?>
						<div class="item clearfix">
							<label class="label">运费金额：</label>
							<div class="fl"><?php echo $order['transport_amount'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">订单金额：</label>
							<div class="fl"><?php echo $order['order_amount'];?></div>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>发票信息</dt>
					<?php if(!empty($order['invoice_content'])) { ?>
					<dd>						
						<div class="item clearfix">
							<label class="label">发票抬头：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_title'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">发票内容：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_content'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">收件人：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_membername'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">邮寄地址：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_areainfo'].$order['invoice_content']['invoice_address'];?></div>
						</div>
					</dd>
					<?php } else { ?>
					<dd>
						<div class="item clearfix">
							不开发票
						</div>
					</dd>
					<?php } ?>
				</dl>
			</div>
			
	<script type="text/javascript" src="templates/js/profile/order_view.js"></script>
<?php include(template('common_footer'));?>