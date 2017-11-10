<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>商家中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a<?php echo $state != 'payment' ? ' class="active"' : '';?> href="index.php?act=store_order">订单管理</a></li>
						<li><a href="index.php?act=store_return">退换货</a></li>
						<li><a<?php echo $state == 'payment' ? ' class="active"' : '';?> href="index.php?act=store_order&state=payment">待发货</a></li>
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
						<li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>订单中心</strong>
				</div>
				<div class="orderlist">
					<div class="orderlist-head clearfix">
						<ul>
							<li>
								<a href="index.php?act=store_order"<?php echo empty($state) ? ' class="active"' : '';?>>全部订单</a>
							</li>
							<li>
								<a href="index.php?act=store_order&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待付款</a>
							</li>
							<li>
								<a href="index.php?act=store_order&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>待发货</a>
							</li>
							<li>
								<a href="index.php?act=store_order&state=deliver"<?php echo $state=='deliver' ? ' class="active"' : '';?>>已发货</a>
							</li>
							<li>
								<a href="index.php?act=store_order&state=receive"<?php echo $state=='receive' ? ' class="active"' : '';?>>已收货</a>
							</li>
							<li>
								<a href="index.php?act=store_order&state=finish"<?php echo $state=='finish' ? ' class="active"' : '';?>>已完成</a>
							</li>
							<li>
								<a href="index.php?act=store_order&state=cancel"<?php echo $state=='cancel' ? ' class="active"' : '';?>>已取消</a>
							</li>
						</ul>
						<div class="pull-right">
							<div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="store_order">
									<input type="hidden" name="state" value="<?php echo $state;?>">
									<input type="text" id="search_name" name="search_name" class="itxt" placeholder="订单单号" value="<?php echo $search_name;?>">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>
							</div>
						</div>
					</div>
					<div class="orderlist-body">
						<table class="order-tb">
							<thead>
								<tr>
									<th width="500">订单详情</th>
									<th width="120">联系人</th>
									<th width="120">金额</th>
									<th width="150">状态与操作</th>
								</tr>
							</thead>
							<?php foreach($order_list as $key => $value) { ?>
								<tbody>
									<tr class="sep-row"><td colspan="5"></td></tr>
									<tr class="tr-th">
										<td colspan="5">
											<span><?php echo date('Y-m-d H:i', $value['add_time']);?></span>
											<span>订单号：<a href="index.php?act=store_order&op=view&order_id=<?php echo $value['order_id'];?>" target="_blank"><?php echo $value['order_sn'];?></a></span>
											<?php if(!empty($value['transaction_id'])) { ?>
											<span>交易单号：<a href="javascript:;"><?php echo $value['transaction_id'];?></a></span>
											<?php } ?>
                                            <a class="btn" href="index.php?act=store_order&op=view&order_id=<?php echo $value['order_id'];?>" target="_blank">查看订单</a>
										</td>
									</tr>
									<tr class="tr-bd">
										<td>
											<?php foreach($order_goods[$value['order_id']] as $subkey => $subvalue) { ?>
											<div class="td-inner clearfix">
												<div class="item-pic">
													<a href="index.php?act=goods&goods_id=<?php echo $subvalue['goods_id'];?>" target="_blank"><img src="<?php echo $subvalue['goods_image'];?>"></a>
												</div>
												<div class="item-info">
													<a href="index.php?act=goods&goods_id=<?php echo $subvalue['goods_id'];?>" target="_blank">
														<?php echo $subvalue['goods_name'];?>
														<?php if(!empty($subvalue['spec_info'])) { ?>
														（<?php echo $subvalue['spec_info'];?>）
														<?php } ?>
                                                        <?php if($subvalue['goods_return_state'] == 1) { ?>
														<span class="red">部分退货</span>
														<?php } elseif($subvalue['goods_return_state'] == 2) { ?>
														<span class="red">全部退货</span>
														<?php } ?>
													</a>													
													<p><?php echo $subvalue['goods_price'];?></p>
													<p>X<?php echo $subvalue['goods_num'];?></p>
												</div>
											</div>
											<?php } ?>
										</td>
										<td>
                                        	<span id="truename_<?php echo $value['order_id'];?>"><?php echo $order_address[$value['order_id']]['true_name'];?></span><br />
                                            <span id="mobilephone_<?php echo $value['order_id'];?>"><?php echo $order_address[$value['order_id']]['mobile_phone'];?></span>
											<p><a href="javascript:;" class="edit-address" order_id="<?php echo $value['order_id'];?>">修改信息</a></p>
										</td>
										<td>
											<span>总额 ￥<?php echo $value['order_amount'];?></span>
											<p><?php echo $value['payment_name'];?></p>
											<?php if($value['transport_amount'] > 0) { ?>
											<span>（含运费 ￥<?php echo $value['transport_amount'];?>）</span>
											<?php } ?>
										</td>
										<td>
											<p class="state" id="state_<?php echo $value['order_id'];?>">
												<?php if(empty($value['order_state'])) { ?>
													<?php if(empty($value['refund_state'])) { ?>
														已取消
													<?php } else { ?>
														已退款
													<?php } ?>
												<?php } elseif($value['order_state'] == 10) { ?>
													待付款
												<?php } elseif($value['order_state'] == 20) { ?>
													<?php if(empty($value['refund_state'])) { ?>
														待发货
													<?php } elseif($value['refund_state'] == 1) { ?>
														待退款
													<?php } else { ?>
														已拒绝
													<?php } ?>
												<?php } elseif($value['order_state'] == 30) { ?>
													已发货
												<?php } elseif($value['order_state'] == 40) { ?>
													<?php if(empty($value['comment_state'])) { ?>
                                                    	已收货
                                                    <?php } else { ?>
                                                    	已评价
                                                    <?php } ?>
												<?php } elseif($value['order_state'] == 50) { ?>
													已完成
												<?php } ?>
											</p>
											<div id="opr_<?php echo $value['order_id'];?>">
												<?php if($value['order_state'] == 10) { ?>
													<a href="javascript:;" class="btn btn-default order-cancel" order_id="<?php echo $value['order_id'];?>">取消订单</a>
												<?php } elseif($value['order_state'] == 20) { ?>
													<?php if($value['refund_state'] == 1) { ?>
                                           				 <a href="javascript:;" class="btn btn-default order-refund" order_id="<?php echo $value['order_id'];?>" refund_amount="<?php echo $value['refund_amount'];?>" refund_reason="<?php echo $value['refund_reason'];?>" refund_message="<?php echo $value['refund_message'];?>">退款处理</a>
													<?php } else { ?>
														<a href="javascript:;" class="btn btn-primary order-deliver" order_id="<?php echo $value['order_id'];?>">商品发货</a>
													<?php } ?>
												<?php } ?>									
											</div>
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
	<div class="modal-wrap w-400" id="cancel-box" style="display: none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="cancel_id" name="cancel_id" value="" />
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">您确定要取消吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			<a class="btn btn-default" onclick="Custombox.close();">取消</a>
			<a class="btn btn-primary" onclick="cancelsubmit();">确定</a>			
		</div>
	</div>
	<div class="modal-wrap w-700" id="refund-box" style="display:none;">		
		<div class="modal-hd">
        	<div class="Validform-checktip Validform-wrong m-tip"></div>
			<input type="hidden" id="refund_id" name="refund_id" value="" />
			<h4><uik>退款处理</uik></h4>
			<span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
		</div>
    	<div class="modal-bd">
			<div class="cont-modal">
				<div class="cont-item">
					<label>退款金额</label>
					<input type="text" id="refund_amount" name="refund_amount" value="" />
				</div>
				<div class="cont-item">
					<label>退款原因</label>
					<div id="refund_reason" class="cont-value"></div>
				</div>
				<div class="cont-item">
					<label>退款备注</label>
					<div id="refund_message" class="cont-value"></div>
				</div>
                <div class="cont-item">
                    <label>处理方式：</label>
                    <div class="cont-value radio-box">
                        <span class="radio active" field_value="1" field_key="refund_state"><i class="iconfont icon-type"></i>同意</span>
                        <span class="radio" field_value="2" field_key="refund_state"><i class="iconfont icon-type"></i>拒绝</span>
                        <input type="hidden" id="refund_state" name="refund_state" value="1" />
                    </div>
				</div>
			</div>
		</div>
		<div class="modal-ft">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="refundsubmit();">确定</a>
		</div>
	</div>
	<div class="modal-wrap w-700" id="deliver-box" style="display:none;">		
		<div class="modal-hd">
        	<div class="Validform-checktip Validform-wrong m-tip"></div>
			<input type="hidden" id="deliver_id" name="deliver_id" value="" />
			<h4>发货</h4>
			<span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
		</div>
		<div class="modal-bd">
			<div class="cont-modal">
				<div class="cont-item">
					<label>快递公司</label>
					<select id="express_id" name="express_id">
						<option value="">请选择</option>
						<?php foreach($express_list as $key => $value) { ?>
						<option value="<?php echo $value['express_id'];?>"><?php echo $value['express_name'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class="cont-item">
					<label>快递编号</label>
					<input type="text" id="shipping_code" name="shipping_code">
				</div>
			</div>
		</div>
		<div class="modal-ft">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="deliversubmit();">确定</a>
		</div>
	</div>
	<script type="text/javascript" src="templates/js/profile/store_order.js"></script>
<?php include(template('common_footer'));?>