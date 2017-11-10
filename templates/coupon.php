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
						<li><a class="active" href="index.php?act=coupon">优惠券</a></li>
					</ul>
					<h3 class="no3">收藏中心</h3>
					<ul>
						<li><a href="index.php?act=favorite">收藏的商品</a></li>
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
                    <strong>优惠券数量：</strong><strong><span class="red"><?php echo $count;?></span></strong>
                </div>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="javascript:;">我的优惠券</a>
                            </li>
                        </ul>
                    </div>
                    <table class="tb-void">
                        <thead>
                            <tr>
                                <th>优惠券名称</th>
                                <th>优惠券面额</th>
                                <th width="150">号码</th>
                                <th width="100">店铺</th>
                                <th width="50">状态</th>
                                <th width="120">到期日期</th>
                                <th width="120">领取日期</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php foreach($coupon_list as $key => $value) { ?>
                            <tr>
                                <td class="al">
									<?php echo $value['coupon_title'];?>
									<?php $discount_goods = array(); ?>
									<?php if(!empty($value['coupon_goods_id'])) { ?>
									<?php foreach($value['coupon_goods_id'] as $goods_id) { ?>
                                    <?php $discount_goods[] = $goods_list[$goods_id];?>
                                    <?php } ?>
									<?php } ?>
									<?php if(!empty($discount_goods)) { ?>
                                    （限<?php echo implode(',', $discount_goods);?>使用）
                                    <?php } ?>
                                    <?php if($value['coupon_limit'] > 0) { ?>
                                	<p class="red">满<?php echo $value['coupon_limit'];?>元可用</p>    
                                	<?php } ?>
                                </td>    
                                <td><?php echo ($value['coupon_price']*1).($value['coupon_price_type'] == 'cash' ? '元' : '折');?></td>
                                <td><span class="green"><?php echo $value['coupon_sn'];?></span></td>
                                <td><?php echo $store_list[$value['store_id']];?></td>
                                <td><span class="red"><?php echo $value['coupon_state'] == 1 ? '使用' : ($value['coupon_endtime']<time() ? '过期' : '未用');?></span></td>
                                <td><span class="gray"><?php echo date('Y-m-d', $value['coupon_endtime']);?></span></td>
                                <td><span class="gray"><?php echo date('Y-m-d', $value['coupon_addtime']);?></span></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $multi;?>
                </div>
            </div>
        </div>
    </div>        
<?php include(template('common_footer'));?>