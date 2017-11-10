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
						<li><a href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no5">商家设置</h3>
					<ul>
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a class="active" href="index.php?act=store_transport">运费模板</a></li>
						<li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>运费模板</strong>
					<span class="pull-right">
						<a class="btn btn-primary" href="index.php?act=store_transport&op=add">添加运费模板</a>
					</span>
				</div>
				<div class="orderlist">
					<div class="orderlist-body transport-list">
						<?php foreach($transport_list as $key => $value) { ?>
						<div class="logistic-item" id="transport_<?php echo $value['transport_id'];?>">
							<div class="logistic-hd clearfix">
								<div class="info pull-left"><?php echo $value['transport_name'];?></div>
								<div class="extra-opr pull-right">
									<span class="t-tips">最后编辑时间：<?php echo date('Y-m-d H:i', $value['upgrade_time']);?></span>
									<a class="warn-opr" href="index.php?act=store_transport&op=edit&transport_id=<?php echo $value['transport_id'];?>">编辑</a>
									<a class="warn-opr transport-del" href="javascript:;" transport_id="<?php echo $value['transport_id'];?>">删除</a>
								</div>
							</div>
							<div class="logistic-set">
								<div class="logistic-bd">
									<table class="logistic-table">
										<thead>
											<tr>
												<th>邮寄名称</th>
												<th>运送到</th>
												<th class="logistic-title">首件（件）</th>
												<th class="logistic-title">运费（元）</th>
												<th class="logistic-title">续件（件）</th>
												<th class="logistic-title">运费（元）</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($extend_list[$value['transport_id']] as $extend_type => $extend_value) { ?>
                                            <?php $i = 0;?>
                                            <?php foreach($extend_value as $subkey => $subvalue) { ?>
											<tr>
                                            	<?php if($i == 0) { ?>
												<td class="select-logistic" rowspan="<?php echo count($extend_value);?>">
													<?php echo $extend_name[$extend_type];?>
												</td>
                                                <?php } ?>
												<td><?php echo $subvalue['area_name'];?></td>
												<td><?php echo $subvalue['extend_snum'];?></td>
												<td><?php echo $subvalue['extend_sprice'];?></td>
												<td><?php echo $subvalue['extend_xnum'];?></td>
												<td><?php echo $subvalue['extend_xprice'];?></td>
											</tr>
                                            <?php $i++;?>
											<?php } ?>
                                            <?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php echo $multi;?>
				</div>
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
	<script type="text/javascript" src="templates/js/profile/store_transport.js"></script>
<?php include(template('common_footer'));?>