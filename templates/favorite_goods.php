<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>个人中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
                    	<li><a href="index.php?act=order&op=book">家政人员预约订单</a></li>
						<li><a href="index.php?act=order">养老商品订单</a></li>						
                        <li><a href="index.php?act=order&op=bespoke">房间预定订单</a></li>
						<li><a href="index.php?act=comment">我的评价</a></li>
						<li><a href="index.php?act=cart">购物车</a></li>
					</ul>
					<h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=predeposit">钱包</a></li>
						<li><a href="index.php?act=red">红包</a></li>
						<li><a href="index.php?act=oldage">养老金</a></li>
						<li><a href="index.php?act=coupon">优惠券</a></li>
					</ul>
					<h3 class="no3">收藏中心</h3>
					<ul>
						<li><a class="active" href="index.php?act=favorite">收藏的商品</a></li>
						<li><a href="index.php?act=favorite&op=nurse">收藏的家政人员</a></li>
					</ul>
					<h3 class="no4">账户设置</h3>
					<ul>
						<li><a href="index.php?act=profile">个人信息</a></li>
						<li><a href="index.php?act=address">收货地址</a></li>
						<li><a href="index.php?act=password">密码修改</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
                <div class="center-title clearfix">
                    <strong>收藏的商品</strong>
                </div>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a class="active" href="index.php?act=favorite">收藏的商品</a>
                            </li>
                            <li>
                                <a href="index.php?act=favorite&op=nurse">收藏的家政人员</a>
                            </li>
                        </ul>
                    </div>
                    <div class="collection-box">
                        <ul class="clearfix">
                            <?php foreach($goods_ids as $key => $value) { ?>
                            <?php if(!empty($goods_list[$value])) { ?>
                            <li id="fav_<?php echo $goods_list[$value]['goods_id'];?>">
                                <div class="collection-item">
                                    <div class="collection-img">
                                        <img src="<?php echo $goods_list[$value]['goods_image'];?>">
                                    </div>
                                    <h1 class="collection-name"><?php echo $goods_list[$value]['goods_name'];?></h1>
                                    <p class="collection-price">￥<strong><?php echo $goods_list[$value]['goods_price'];?></strong></p>
                                    <div class="collection-stats">
                                        <span><i class="iconfont icon-talk"></i><?php echo $goods_list[$value]['goods_commentnum'];?></span>
                                        <span><i class="iconfont icon-zan"></i><?php echo $goods_list[$value]['goods_viewnum'];?></span>
                                    </div>
                                    <div class="collection-operate">
                                        <a class="operate-btn cancel-btn favorite-del" href="javascript:;" fav_id="<?php echo $goods_list[$value]['goods_id'];?>" fav_type="goods">取消收藏</a>
                                        <a class="operate-btn" href="index.php?act=goods&goods_id=<?php echo $goods_list[$value]['goods_id'];?>">商品详情</a>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php echo $multi;?>
                </div>
            </div>
		</div>
	</div>
    <div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/profile/favorite.js"></script>
<?php include(template('common_footer'));?>