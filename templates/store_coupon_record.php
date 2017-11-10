<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>商家中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=store_order">订单管理</a></li>
						<li><a href="index.php?act=store_return">退换货</a></li>
						<li><a href="index.php?act=store_order&state=payment">待发货</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=store_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">商品中心</h3>
					<ul>
						<li><a href="index.php?act=store_goods&op=add">发布商品</a></li>
						<li><a href="index.php?act=store_goods">出售商品</a></li>
						<li><a href="index.php?act=store_goods&op=goods_unshow">仓库商品</a></li>
						<li><a href="index.php?act=store_spec">规格管理</a></li>
					</ul>
					<h3 class="no4">营销中心</h3>
					<ul>
						<li><a class="active" href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no5">商家设置</h3>
					<ul>
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a href="index.php?act=store_transport">运费模板</a></li>
						<li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
            <div class="center-title clearfix">
                <strong>优惠券领取情况</strong>
                <span class="pull-right">
                    <a href="index.php?act=store_coupon" class="btn btn-default">返回</a>
                </span>
            </div>
            <div class="orderlist">
                <div class="orderlist-head clearfix">
                    <ul>
                    	<li>
                            <a href="index.php?act=store_coupon&op=record&coupon_t_id=<?php echo $coupon_t_id;?>&state=giveout"<?php echo $state=='giveout' ? ' class="active"' : '';?>>已领取</a>
                        </li>
                        <li>
                            <a href="index.php?act=store_coupon&op=record&coupon_t_id=<?php echo $coupon_t_id;?>&state=outused"<?php echo $state=='outused' ? ' class="active"' : '';?>>未使用</a>
                        </li>
                        <li>
                            <a href="index.php?act=store_coupon&op=record&coupon_t_id=<?php echo $coupon_t_id;?>&state=used"<?php echo $state=='used' ? ' class="active"' : '';?>>已使用</a>
                        </li>
                    </ul>
                </div>
                <table class="tb-void">
                    <thead>
                        <tr>
                            <th width="80">用户</th>
                            <th width="200">优惠券名称</th>
                            <th width="80">领取时间</th>
                            <th width="80">面值</th>
                            <th width="80">状态</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php foreach($coupon_list as $key => $value) { ?>
                        <tr>
                            <td><?php echo $member_list[$value['member_id']]['member_phone'];?></td>
                            <td><?php echo $value['coupon_title'];?></td>
                            <td><?php echo date('Y-m-d H:i', $value['coupon_addtime']);?></td>
                            <td><?php echo $value['coupon_price'];?><?php echo $value['coupon_type'] == 1 ? '元' : '折';?></td>
                            <td><?php echo $value['coupon_state'] == '1' ? '已使用' : '未使用';?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php echo $multi;?>
            </div>
		</div>
	</div>	
	<script type="text/javascript" src="templates/js/profile/store_coupon.js"></script>
<?php include(template('common_footer'));?>