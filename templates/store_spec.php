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
						<li><a class="active" href="index.php?act=store_spec">规格管理</a></li>
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
					<strong>规格管理</strong>
					<span class="pull-right">
						<a href="index.php?act=store_spec&op=add"  class="btn btn-primary">添加规格</a>
					</span>
				</div>
				<div class="orderlist">
					<div class="orderlist-head clearfix">
						<ul>
							<li>
								<a>商品规格</a>
							</li>
						</ul>
					</div>
					<table class="tb-void">
						<thead>
							<tr>
								<th width="200">规格名称</th>
								<th>规格值</th>
								<th width="100">排序</th>
								<th width="100">规格类型</th>
								<th width="100">操作</th>
							</tr>
						</thead>
						<tbody class="spec-list">
							<?php foreach($spec_list as $key => $value) { ?>
							<tr id="spec_<?php echo $value['sp_id'];?>">
								<td><?php echo $value['sp_name'];?></td>
								<td><?php echo $value['sp_value'];?></td>
								<td><?php echo $value['sp_sort'];?></td>
								<td><?php echo $value['sp_format'] == 'text' ? '文本' : '图片';?></td>
								<td>
									<a class="bluelink" href="index.php?act=store_spec&op=edit&sp_id=<?php echo $value['sp_id'];?>">编辑</a>&nbsp;&nbsp;<a class="spec-del" href="javascript:;" sp_id="<?php echo $value['sp_id'];?>">删除</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php echo $multi;?>
			</div>
		</div>
	</div>
	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
	<div class="modal-wrap w-400" id="del-box" style="display:none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="del_id" name="del_id" value="" />
        <div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">你确定要删除吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="delsubmit();">确定</a>
		</div>
	</div>
	<div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/profile/store_spec.js"></script>
<?php include(template('common_footer'));?>