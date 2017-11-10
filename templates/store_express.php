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
					<h3 class="no5">营销中心</h3>
					<ul>
						<li><a href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no4">商家设置</h3>
					<ul>
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a href="index.php?act=store_transport">运费模板</a></li>
						<li><a class="active" href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>物流公司</strong>
				</div>
				<div class="orderlist">
					<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
					<table class="express-table">
						<?php foreach($express_list as $key => $value) { ?>
						<tr>
							<?php foreach($value as $subkey => $subvalue) { ?>
							<td><span class="check<?php echo in_array($subvalue['express_id'], $this->store['store_express']) ? ' active' : '';?>" express_id="<?php echo $subvalue['express_id'];?>"><i class="iconfont icon-type"></i><?php echo $subvalue['express_name'];?></span></td>
							<?php } ?>
						</tr>
						<?php } ?>
					</table>
				</div>
				<div class="logistic-btn">
					<a href="javascript:checksubmit();" class="btn btn-primary">保存</a><span class="return-success"></span>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="templates/js/profile/store_express.js"></script>
<?php include(template('common_footer'));?>