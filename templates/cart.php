<?php include(template('common_header'));?>
		<div class="conwp clearfix">
			<h1 class="top-logo">
				<a href="index.php"><img src="templates/images/logo.png"></a>
				<strong>购物车</strong>
			</h1>
		</div>
    </div>
	<div class="content">
		<div class="conwp">
			<div class="cart-box">
				<?php if(empty($cart_list)) { ?>
					<div class="empty-cart">
						<div class="emptycart-icon"><img src="templates/images/emptycart.png"></div>
						<p>您的购物车为空<a class="btn btn-primary" href="index.php?act=index&op=goods">去逛逛</a></p>
					</div>
				<?php } else { ?>
					<div class="cart-head clearfix">
						<div class="th th-chk">
							<span class="check cartall active"><i class="iconfont icon-type"></i>全选</span>
						</div>
						<div class="th th-item">
							商品
						</div>
						<div class="th th-info">
							&nbsp;
						</div>
						<div class="th th-price">
							单价
						</div>
						<div class="th th-amount">
							数量
						</div>
						<div class="th th-sum">
							小计
						</div>
						<div class="th th-op">
							操作
						</div>
					</div>
					<div class="cart-body">
						<?php foreach($cart_list as $store_id => $store_cart_list) { ?>
						<div class="cart-item-list" id="store_<?php echo $store_id;?>">
							<div class="shop">
								<span class="check storeall active"><i class="iconfont icon-type"></i><em class="self-support"><?php echo $store_list[$store_id];?></em></span>
							</div>
							<?php foreach($store_cart_list as $key => $value) { ?>
								<div class="cart-list clearfix" id="cart_<?php echo $value['cart_id'];?>">
									<div class="td td-chk">
										<span class="check cartitem active" cart_id="<?php echo $value['cart_id'];?>" store_id="<?php echo $store_id;?>" spec_goods_price="<?php echo $value['spec_goods_price'];?>"><i class="iconfont icon-type"></i></span>
									</div>
									<div class="td td-item">
										<div class="td-inner clearfix">
											<div class="item-pic">
												<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>"><img src="<?php echo $value['goods_image'];?>"></a>
											</div>
											<div class="item-info">
												<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>"><?php echo $value['goods_name'];?></a>
											</div>
										</div>
									</div>
									<div class="td td-info">
										<div class="item-props">
											<?php if(!empty($value['spec_info'])) { ?>
											<?php foreach($value['spec_info'] as $subkey => $subvalue) { ?>
											<p><?php echo $subvalue;?></p>
											<?php } ?>
											<?php } else { ?>
											<p>&nbsp;&nbsp;</p>
											<?php } ?>
										</div>
									</div>
									<div class="td td-price">
										<strong class="item-price">￥<?php echo $value['spec_goods_price'];?></strong>
									</div>
									<div class="td td-amount">
										<div class="p-quantity">
											<div class="quantity-form">
												<a href="javascript:void(0);" class="decrement<?php echo $value['goods_num'] <= 1 ? ' disabled' : '';?>" cart_id="<?php echo $value['cart_id'];?>">-</a>
												<input type="text" class="itxt" value="<?php echo $value['goods_num']; ?>" id="cart_item_<?php echo $value['cart_id']; ?>" orig="<?php echo $value['goods_num']; ?>" onKeyUp="changeQuantity('<?php echo $value['cart_id']; ?>');">
												<a href="javascript:void(0);" class="increment<?php echo $value['goods_num'] >= $value['spec_goods_storage'] ? ' disabled' : '';?>" cart_id="<?php echo $value['cart_id'];?>">+</a>
											</div>
										</div>
									</div>
									<div class="td td-sum">
										<strong class="item-price" id="cart_price_<?php echo $value['cart_id'];?>">￥<?php echo priceformat($value['spec_goods_price']*$value['goods_num'], 2);?></strong>
									</div>
									<div class="td td-op">
										<a href="javascript:;" class="cart-item-del" cart_id="<?php echo $value['cart_id'];?>" store_id="<?php echo $store_id;?>" spec_goods_price="<?php echo $value['spec_goods_price'];?>">删除</a>
									</div>
								</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
					<div class="float-bar-wrapper">
						<span class="check cartall active"><i class="iconfont icon-type"></i>全选</span>
						<a href="javascript:;" class="cart-del">删除</a>
						<div class="float-bar-right">
							<div class="amount-sum">
								已选商品<strong id="cart_goods_num"><?php echo $goods_num;?></strong>件
							</div>
							<div class="price-sum">
								合计：<strong id="cart_goods_amount"><?php echo priceformat($goods_amount, 2);?></strong>
							</div>
							<div class="btn-area">
								<a href="javascript:;" class="sumbit-btn">结算</a>
							</div>
						</div>
					</div>
				<?php } ?>
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
	<div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/home/cart.js"></script>
<?php include(template('common_footer'));?>