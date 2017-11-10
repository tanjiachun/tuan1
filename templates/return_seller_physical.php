<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="order-state">
				<div class="state-lcol">
					<div class="state-top">退货单号：<?php echo $order_return['return_sn'];?></div>
					<h1 class="state-strong">
						<?php if($order_return['return_type'] == 'return') { ?>
							退货
						<?php } else { ?>
							换货
						<?php } ?>
					</h1>
				</div>
				<div class="state-rcol">
					<?php if(!empty($order_return['return_image'])) { ?>
					<div class="picture-list">
						<ul class="clearfix">
							<?php foreach($order_return['return_image'] as $key => $value) { ?>
							<li><img src="<?php echo $value;?>"></li>
							<?php } ?>
						</ul>
					</div>
					<?php } ?>
					<?php if(!empty($order_return['return_content'])) { ?>
					<div class="reture-exp">
						<label>退换说明：</label><?php echo $order_return['return_content'];?>
					</div>
					<?php } ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="order-track">
				<div class="track-lcol">
					<div class="p-item clearfix">
						<div class="p-img">
							<a href="index.php?act=goods&goods_id=<?php echo $order_return_goods['goods_id'];?>">
								<img src="<?php echo $order_return_goods['goods_image'];?>" alt="">
							</a>
						</div>
						<div class="p-info">
							<ul>
								<li>承运方：<?php echo $order_return['seller_express_name'];?></li>
								<li>货运单号：<?php echo $order_return['seller_shipping_code'];?></li>
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
		</div>
	</div>		
<?php include(template('common_footer'));?>