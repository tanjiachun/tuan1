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
                        <li><a class="active" href="index.php?act=store_goods">出售商品</a></li>
						<li><a href="index.php?act=store_goods&op=goods_unshow">仓库商品</a></li>
						<li><a href="index.php?act=store_spec">规格管理</a></li>
					</ul>
					<h3 class="no4">营销中心</h3>
					<ul>
						<li><a href="index.php?act=store_coupon">优惠券管理</a></li>
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
					<strong>商品中心</strong>
				</div>
				<div class="orderlist">
					<div class="orderlist-head clearfix">
						<div class="pull-right">
							<div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="store_goods">
									<input type="text" id="goods_name" name="goods_name" class="itxt" placeholder="商品名称" value="<?php echo $goods_name;?>">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>	
							</div>
						</div>
					</div>					
					<div class="orderlist-body">
						<table class="order-tb">
							<thead>
								<tr>
									<th width="300">商品详情</th>
									<th width="100">价格</th>
									<th width="100">库存</th>
									<th width="98">发布时间</th>
									<th width="100">操作</th>
								</tr>
							</thead>
                            <?php if(!empty($goods_list)) { ?>
							<tbody>
								<tr class="tool-row">
									<td colspan="5">
										<span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
										<a class="btn btn-default use-express goods-del">删除</a>
										<a class="btn btn-default use-express goods-unshow">下架</a>
									</td>
								</tr>
							</tbody>
                            <?php } ?>
                            <?php foreach($goods_list as $key => $value) { ?>
							<tbody id="goods_<?php echo $value['goods_id'];?>">
								<tr class="sep-row"><td colspan="5"></td></tr>
								<tr class="tr-th">
									<td colspan="5">
										<span class="check checkitem" style="padding-left: 10px;" goods_id="<?php echo $value['goods_id'];?>"><i class="iconfont icon-type"></i></span>
										<span style="padding-left: 0;">商品货号：<a><?php echo $value['goods_serial'];?></a></span>
									</td>
								</tr>
								<tr class="tr-bd">
									<td>
										<div class="td-inner goods-inner clearfix">
											<div class="item-pic">
												<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>" target="_blank"><img src="<?php echo $value['goods_image'];?>"></a>
											</div>
											<div class="item-info">
												<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>"><?php echo $value['goods_name'];?></a>
											</div>
										</div>
									</td>
									<td>¥<span id="price_<?php echo $value['goods_id'];?>"><?php echo $value['goods_price'];?></span> <a class="edit-price" href="javascript:;" goods_id="<?php echo $value['goods_id'];?>"><i class="iconfont icon-edit"></i></a></td>
									<td><span id="storage_<?php echo $value['goods_id'];?>"><?php echo $value['goods_storage'];?></span> <a class="edit-storage" href="javascript:;" goods_id="<?php echo $value['goods_id'];?>"><i class="iconfont icon-edit"></i></a></td>
									<td><?php echo date('Y-m-d H:i', $value['goods_add_time']);?></td>
									<td><a class="btn btn-default use-express" href="index.php?act=store_goods&op=edit&goods_id=<?php echo $value['goods_id'];?>">编辑商品</a><p></td>
								</tr>
							</tbody>
                            <?php } ?>
                            <?php if(!empty($goods_list)) { ?>
							<tbody>
								<tr class="tool-row">
									<td colspan="5">
										<span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
										<a class="btn btn-default use-express goods-del">删除</a>
										<a class="btn btn-default use-express goods-unshow">下架</a>
									</td>
								</tr>
							</tbody>
                            <?php } ?>
						</table>
					</div>
					<?php echo $multi;?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
	<div class="modal-wrap w-400" id="del-box" style="display: none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="del_ids" name="del_ids" value="" />
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">您确定要删除吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			<a class="btn btn-default" onclick="Custombox.close();">取消</a>
			<a class="btn btn-primary" onclick="delsubmit();">确定</a>			
		</div>
	</div>
	<div class="modal-wrap w-400" id="unshow-box" style="display: none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="unshow_ids" name="unshow_ids" value="" />
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">您确定要下架吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			<a class="btn btn-default" onclick="Custombox.close();">取消</a>
			<a class="btn btn-primary" onclick="unshowsubmit();">确定</a>			
		</div>
	</div>
    <div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/profile/store_goods.js"></script>
<?php include(template('common_footer'));?>