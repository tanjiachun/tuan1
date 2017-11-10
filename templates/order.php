<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>个人中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
                    	<li><a href="index.php?act=order&op=book">家政人员预约订单</a></li>
						<li><a class="active" href="index.php?act=order">养老商品订单</a></li>						
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
					<strong>养老商品订单</strong>
				</div>
				<div class="orderlist">
					<div class="orderlist-head clearfix">
						<ul>
							<li>
								<a href="index.php?act=order"<?php echo empty($state) ? ' class="active"' : '';?>>全部订单</a>
							</li>
							<li>
								<a href="index.php?act=order&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待付款</a>
							</li>
							<li>
								<a href="index.php?act=order&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>待发货</a>
							</li>
							<li>
								<a href="index.php?act=order&state=deliver"<?php echo $state=='deliver' ? ' class="active"' : '';?>>已发货</a>
							</li>
							<li>
								<a href="index.php?act=order&state=receive"<?php echo $state=='receive' ? ' class="active"' : '';?>>已收货</a>
							</li>
							<li>
								<a href="index.php?act=return">退换货</a>
							</li>
							<li>
								<a href="index.php?act=order&state=finish"<?php echo $state=='finish' ? ' class="active"' : '';?>>已完成</a>
							</li>
							<li>
								<a href="index.php?act=order&state=cancel"<?php echo $state=='cancel' ? ' class="active"' : '';?>>已取消</a>
							</li>
						</ul>
						<div class="pull-right">
							<div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="order">
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
											<span>订单号：<a href="index.php?act=order&op=view&order_id=<?php echo $value['order_id'];?>" target="_blank"><?php echo $value['order_sn'];?></a></span>
											<?php if(!empty($value['transaction_id'])) { ?>
											<span>交易单号：<a href="javascript:;"><?php echo $value['transaction_id'];?></a></span>
											<?php } ?>
											<a class="btn" href="index.php?act=order&op=view&order_id=<?php echo $value['order_id'];?>" target="_blank">查看订单</a>
											<?php if($value['order_state'] >= 30) { ?>
											<a class="btn" href="index.php?act=order&op=physical&order_id=<?php echo $value['order_id'];?>" target="_blank">物流跟踪</a>
											<?php } ?>
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
											<span><?php echo $order_address[$value['order_id']]['true_name'];?></span><br />
                                            <span><?php echo $order_address[$value['order_id']]['mobile_phone'];?></span>
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
													<a href="index.php?act=order&op=payment&order_sn=<?php echo $value['order_sn'];?>" class="btn btn-primary">立即支付</a>
													<p><a href="javascript:;" class="order-cancel" order_id="<?php echo $value['order_id'];?>">取消订单</a></p>
												<?php } elseif($value['order_state'] == 20) { ?>
													<?php if(empty($value['refund_state'])) { ?>
														<p><a href="javascript:;" class="btn btn-default order-refund" order_id="<?php echo $value['order_id'];?>">我要退款</a></p>
													<?php } ?>
												<?php } elseif($value['order_state'] == 30) { ?>
													<a href="javascript:;" class="btn btn-primary order-receive" order_id="<?php echo $value['order_id'];?>">确认收货</a>
													<p><a href="index.php?act=order&op=return&order_id=<?php echo $value['order_id'];?>">我要退换货</a></p>
												<?php } elseif($value['order_state'] == 40) { ?>
													<?php if(empty($value['comment_state'])) { ?>
														<a href="index.php?act=order&op=comment&order_id=<?php echo $value['order_id'];?>" class="btn btn-primary">立即评价</a>
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
			<h4><uik>退货</uik></h4>
			<span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
		</div>
		<div class="modal-bd">
			<div class="cont-modal">
				<div class="cont-item">
					<label>退款原因</label>
					<select id="refund_reason" name="refund_reason">
						<option value="">请选择</option>
						<option value="未收到货">未收到货</option>
						<option value="未按约定时间发货">未按约定时间发货</option>
						<option value="其他">其他</option>
					</select>
				</div>
				<div class="cont-item">
					<label>退款说明</label>
					<textarea id="refund_message" name="refund_message"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-ft">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="refundsubmit();">确定</a>
		</div>
	</div>
	<div class="modal-wrap w-400" id="receive-box" style="display: none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="receive_id" name="receive_id" value="" />
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">您确定已收货吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			<a class="btn btn-default" onclick="Custombox.close();">取消</a>
			<a class="btn btn-primary" onclick="receivesubmit();">确定</a>			
		</div>
	</div>
	<script type="text/javascript" src="templates/js/profile/order.js"></script>
<?php include(template('common_footer'));?>