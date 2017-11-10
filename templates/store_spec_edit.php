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
						<a href="index.php?act=store_spec"  class="btn btn-primary">返回</a>
					</span>
				</div>
				<div class="orderlist">
					<div class="form-list">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
						<input type="hidden" id="sp_id" name="sp_id" value="<?php echo $sp['sp_id'];?>" />
						<div class="form-item clearfix">
							<label>规格名称：</label>
							<div class="form-item-value">
								<input type="text" id="sp_name" name="sp_name" class="form-input w-400" value="<?php echo $sp['sp_name'];?>">
								<div class="t-tips">请填写常用的商品规格的名称；例如：颜色；尺寸等。</div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>规格排序：</label>
							<div class="form-item-value">
								<input type="text" id="sp_sort" name="sp_sort" class="form-input w-400" value="<?php echo $sp['sp_sort'];?>">
							</div>
						</div>
						<div class="form-item clearfix">
							<label>规格类型：</label>
							<div class="form-item-value radio-box">
								<input type="hidden" id="sp_format" name="sp_format" value="<?php echo $sp['sp_format'];?>" />
								<span class="radio<?php echo $sp['sp_format']=='text' ? ' active' : '';?>" field_value="text" field_key="sp_format"><i class="iconfont icon-type"></i>文字</span>
								<span class="radio<?php echo $sp['sp_format']=='image' ? ' active' : '';?>" field_value="image" field_key="sp_format"><i class="iconfont icon-type"></i>图片</span>
								<p class="t-tips">图片类型的规格值用于直观表现某些特殊规格类型，例如颜色规格，可用对应的色彩图片来表现</p>
							</div>
						</div>
						<div class="sku-group">
							<table class="table-sku-stock">
								<thead>
									<tr>
										<th>排序</th>
										<th>规格值</th>
										<th class="th-price">操作</th>
									</tr>
								</thead>
								<tbody class="sp-value">
									<?php foreach($spec_value_list as $key => $value) { ?>
									<tr>
										<td>
											<input type="hidden" name="sp_value_id[]" value="<?php echo $value['sp_value_id'];?>" />
											<input type="text" name="sp_value_sort[]" class="form-input w-50" value="<?php echo $value['sp_value_sort'];?>">
										</td>
										<td>
											<input type="text" name="sp_value_name[]" class="form-input w-400" value="<?php echo $value['sp_value_name'];?>">
										</td>
										<td>
											<a class="bluelink sp-value-del" href="javascript:;">删除</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>	
								<tr>
									<td colspan="3">
										<span class="btn btn-default sp-value-add">添加规格值</span>
									</td>
								</tr>
							</table>
						</div>
						<div class="form-item clearfix">
							<label>&nbsp;</label>
							<div class="form-item-value">
								<a href="javascript:editsubmit();" class="btn btn-primary">保存</a><span class="return-success"></span>
							</div>
						</div>
					</div>	
				</div>	
			</div>
		</div>
	</div>
	<script type="text/javascript" src="templates/js/profile/store_spec.js"></script>
<?php include(template('common_footer'));?>