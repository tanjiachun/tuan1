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
                <div class="cart-head clearfix">
					<div class="th th-chk"></div>
                    <div class="th th-item">
                        商品
                    </div>
                    <div class="th th-info">
                        &nbsp;
                    </div>
                    <div class="th th-price">
                        单价
                    </div>
                    <div class="th th-buy-amount">
                        数量
                    </div>
                    <div class="th th-sum">
                        小计
                    </div>
                </div>
                <div class="cart-body">
                    <div class="cart-item-list">
                        <div class="shop">
                            <em class="self-support"><?php echo $store['store_name'];?></em>
                        </div>
                        <div class="cart-list clearfix">
                        	<input type="hidden" id="spec_id" name="spec_id" value="<?php echo $goods['spec_id'];?>"  />
                            <input type="hidden" id="spec_goods_price" name="spec_goods_price" value="<?php echo $goods['spec_goods_price'];?>"  />
                            <input type="hidden" id="spec_goods_storage" name="spec_goods_storage" value="<?php echo $goods['spec_goods_storage'];?>"  />
                            <div class="td td-chk"></div>
							<div class="td td-item">
                                <div class="td-inner clearfix">
                                    <div class="item-pic">
                                        <a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>"><img src="<?php echo $goods['goods_image'];?>"></a>
                                    </div>
                                    <div class="item-info">
                                        <a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>"><?php echo $goods['goods_name'];?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="td td-info">
                                <div class="item-props">
                                    <?php if(!empty($goods['spec_info'])) { ?>
                                    <?php foreach($goods['spec_info'] as $key => $value) { ?>
                                    <p><?php echo $value;?></p>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <p>&nbsp;&nbsp;</p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="td td-price">
                                <strong class="item-price">￥<?php echo $goods['spec_goods_price'];?></strong>
                            </div>
                            <div class="td td-buy-amount">
                                <div class="p-quantity">
                                    <div class="quantity-form">
                                        <a href="javascript:void(0);" class="decrement<?php echo $quantity <= 1 ? ' disabled' : '';?>">-</a>
                                        <input type="text" class="itxt" value="<?php echo $quantity; ?>" id="cart_item" onKeyUp="changeQuantity();">
                                        <a href="javascript:void(0);" class="increment<?php echo $quantity >= $goods['spec_goods_storage'] ? ' disabled' : '';?>">+</a>
                                    </div>
                                </div>
                            </div>
                            <div class="td td-sum">
                                <strong class="item-price" id="cart_price">￥<?php echo priceformat($goods['spec_goods_price']*$quantity, 2);?></strong>
                            </div>
						</div>
					</div>
                </div>    
                <div class="float-bar-wrapper">
                    <div class="float-bar-right">
                        <div class="price-sum">
                            合计：<strong id="cart_goods_amount"><?php echo priceformat($goods_amount, 2);?></strong>
                        </div>
                        <div class="btn-area">
                            <a href="javascript:;" class="sumbit-btn">结算</a>
                        </div>
                    </div>
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
	<div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/home/buynow.js"></script>
<?php include(template('common_footer'));?>