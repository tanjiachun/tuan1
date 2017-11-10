<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="order-state">
				<div class="state-lcol">
					<div class="state-top">订单号：<?php echo $order['order_sn'];?></div>
					<h1 class="state-strong" id="state_<?php echo $order['order_id'];?>">
						<?php if(empty($order['order_state'])) { ?>
							<?php if(empty($order['refund_state'])) { ?>
								已取消
							<?php } else { ?>
								已退款
							<?php } ?>
						<?php } elseif($order['order_state'] == 10) { ?>
							待付款
						<?php } elseif($order['order_state'] == 20) { ?>
							<?php if(empty($order['refund_state'])) { ?>
								待发货
							<?php } elseif($order['refund_state'] == 1) { ?>
								待退款
							<?php } else { ?>
								已拒绝
							<?php } ?>
						<?php } elseif($order['order_state'] == 30) { ?>
							已发货
						<?php } elseif($order['order_state'] == 40) { ?>
							<?php if(empty($order['comment_state'])) { ?>
								已收货
							<?php } else { ?>
								已评价
							<?php } ?>
						<?php } elseif($order['order_state'] == 50) { ?>
							已完成
						<?php } ?>
					</h1>
					<div class="state-opr" id="opr_<?php echo $order['order_id'];?>">
						<?php if($order['order_state'] == 10) { ?>
							<a href="javascript:;" class="btn btn-default order-cancel" order_id="<?php echo $order['order_id'];?>">取消订单</a>
						<?php } elseif($order['order_state'] == 20) { ?>
							<?php if($order['refund_state'] == 1) { ?>
								 <a href="javascript:;" class="btn btn-default order-refund" order_id="<?php echo $order['order_id'];?>" refund_amount="<?php echo $order['refund_amount'];?>" refund_reason="<?php echo $order['refund_reason'];?>" refund_message="<?php echo $order['refund_message'];?>">退款处理</a>
							<?php } else { ?>
								<a href="javascript:;" class="btn btn-primary order-deliver" order_id="<?php echo $order['order_id'];?>">商品发货</a>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<div class="state-rcol">
					<div class="state-rtop gray"></div>
					<div class="order-process">
						<div class="node<?php echo empty($order['add_time']) ? ' ready' : '';?>">
							<i class="node-icon icon-start"></i>
							<ul>
								<li class="txt1">&nbsp;</li>
								<li class="txt2">提交订单</li>
								<?php if(empty($order['add_time'])) { ?>
								<li class="txt3"><?php echo date('Y-m-d', $order['add_time']);?> <br> <?php echo date('H:i:s', $order['add_time']);?></li>
								<?php } ?>
							</ul>
							</div>
						<div class="proce<?php echo empty($order['payment_time']) ? ' done' : ' wait';?>"><ul><li class="txt1">&nbsp;</li></ul></div>
						<div class="node<?php echo empty($order['payment_time']) ? ' ready' : '';?>">
							<i class="node-icon icon-pay"></i>
							<ul>
								<li class="txt1">&nbsp;</li>
								<li class="txt2">付款成功</li>
								<?php if(empty($order['payment_time'])) { ?>
								<li class="txt3"><?php echo date('Y-m-d', $order['payment_time']);?> <br> <?php echo date('H:i:s', $order['payment_time']);?></li>
								<?php } ?>
							</ul>
						</div>
						<div class="proce<?php echo empty($order['shipping_time']) ? ' done' : ' wait';?>"><ul><li class="txt1">&nbsp;</li></ul></div>
						<div class="node<?php echo empty($order['shipping_time']) ? ' ready' : '';?>">
							<i class="node-icon icon-store"></i>
							<ul>
								<li class="txt1">&nbsp;</li>
								<li class="txt2">商品发货</li>
								<?php if(empty($order['shipping_time'])) { ?>
								<li class="txt3"><?php echo date('Y-m-d', $order['shipping_time']);?> <br> <?php echo date('H:i:s', $order['shipping_time']);?></li>
								<?php } ?>
							</ul>
						</div>
						<div class="proce<?php echo empty($order['receive_time']) ? ' done' : ' wait';?>"><ul><li class="txt1">&nbsp;</li></ul></div>
						<div class="node<?php echo empty($order['receive_time']) ? ' ready' : '';?>">
							<i class="node-icon icon-express"></i>
							<ul>
								<li class="txt1">&nbsp;</li>
								<li class="txt2">商品收货</li>
								<?php if(empty($order['receive_time'])) { ?>
								<li class="txt3"><?php echo date('Y-m-d', $order['receive_time']);?> <br> <?php echo date('H:i:s', $order['receive_time']);?></li>
								<?php } ?>
							</ul>
						</div>
						<div class="proce<?php echo empty($order['finish_time']) ? ' done' : ' wait';?>"><ul><li class="txt1">&nbsp;</li></ul></div>
						<div class="node<?php echo empty($order['finish_time']) ? ' ready' : '';?>">
							<i class="node-icon icon-finish"></i>
							<ul>
								<li class="txt1">&nbsp;</li>
								<li class="txt2">订单完成</li>
								<?php if(empty($order['finish_time'])) { ?>
								<li class="txt3"><?php echo date('Y-m-d', $order['finish_time']);?> <br> <?php echo date('H:i:s', $order['finish_time']);?></li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php if(!empty($order['express_code']) && !empty($order['shipping_code'])) { ?>
			<div class="order-track">
				<div class="track-lcol">
					<div class="p-item clearfix">
						<div class="p-img">
							<a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>">
								<img src="<?php echo $goods['goods_image'];?>" alt="">
							</a>
						</div>
						<div class="p-info">
							<ul>
								<li>承运方：<?php echo $order['express_name'];?></li>
								<li>货运单号：<?php echo $order['shipping_code'];?></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="track-rcol">
					<div class="track-list">
						<ul>
							<?php foreach($delivery_data as  $key => $value) { ?>
							<li>
								<i class="node-icon"></i>
								<span class="time"><?php echo $value['time'];?></span>
								<span class="txt"><?php echo $value['context'];?></span>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php } ?>
			<div class="order-info">
				<dl>
					<dt>收货人信息</dt>
					<dd>
						<div class="item clearfix">
							<label class="label">收货人：</label>
							<div class="fl"><span id="truename_<?php echo $order['order_id'];?>"><?php echo $order_address['true_name'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" class="redlink edit-address" order_id="<?php echo $order['order_id'];?>">修改信息</a></div>
						</div>
						<div class="item clearfix">
							<label class="label">地址：</label>
							<div class="fl"><span id="address_<?php echo $order['order_id'];?>"><?php echo $order_address['area_info'].$order_address['address_info'];?></span></div>
						</div>
						<div class="item clearfix">
							<label class="label">手机号码：</label>
							<div class="fl"><span id="mobilephone_<?php echo $order['order_id'];?>"><?php echo $order_address['mobile_phone'];?></span></div>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>订单信息</dt>
					<dd>
						<?php if(!empty($order['payment_name'])) { ?>
						<div class="item clearfix">
							<label class="label">付款方式：</label>
							<div class="fl"><?php echo $order['payment_name'];?></div>
						</div>
						<?php } ?>
						<?php if(!empty($used_coupon['coupon_title'])) { ?>
						<div class="item clearfix">
							<label class="label">优惠券：</label>
							<div class="fl"><?php echo $used_coupon['coupon_title'];?></div>
						</div>
						<?php } ?>
						<?php if(!empty($used_red['coupon_title'])) { ?>
						<div class="item clearfix">
							<label class="label">红包：</label>
							<div class="fl"><?php echo $used_red['red_title'];?></div>
						</div>
						<?php } ?>
						<div class="item clearfix">
							<label class="label">运费金额：</label>
							<div class="fl"><?php echo $order['transport_amount'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">订单金额：</label>
							<div class="fl"><?php echo $order['order_amount'];?></div>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>发票信息</dt>
					<?php if(!empty($order['invoice_content'])) { ?>
					<dd>						
						<div class="item clearfix">
							<label class="label">发票抬头：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_title'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">发票内容：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_content'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">收件人：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_membername'];?></div>
						</div>
						<div class="item clearfix">
							<label class="label">邮寄地址：</label>
							<div class="fl"><?php echo $order['invoice_content']['invoice_area'].$order['invoice_content']['invoice_address'];?></div>
						</div>
					</dd>
					<?php } else { ?>
					<dd>
						<div class="item clearfix">
							不开发票
						</div>
					</dd>
					<?php } ?>
				</dl>
			</div>
			<div class="trade-goods">
				<h3 class="goods-title">购物清单</h3>
				<div class="cart-head clearfix">
					<div class="th th-item">
						商品
					</div>
					<div class="th th-info">
						&nbsp;
					</div>
					<div class="th th-price">
						单价
					</div>
					<div class="th th-amount">
						数量
					</div>
					<div class="th th-sum">
						小计
					</div>
				</div>
				<div class="cart-item-list">
					<?php foreach($order_goods as $key => $value) { ?>
					<div class="cart-list clearfix">
						<div class="td td-item">
							<div class="td-inner clearfix">
								<div class="item-pic">
									<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>" target="_blank"><img src="<?php echo $value['goods_image'];?>"></a>
								</div>
								<div class="item-info">
									<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>" target="_blank"><?php echo $value['goods_name'];?></a>
									<?php if($value['goods_return_state'] == 1) { ?>
									<span class="red">部分退货</span>
									<?php } elseif($value['goods_return_state'] == 2) { ?>
									<span class="red">全部退货</span>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="td td-info">
							<div class="item-props">
								<?php if(!empty($value['spec_info'])) { ?>
								<?php foreach($value['spec_info'] as $subkey => $subvalue) { ?>
								<p><?php echo $subvalue;?></p>
								<?php } ?>
								<?php } else { ?>
								<p>&nbsp;&nbsp;</p>
								<?php } ?>
							</div>
						</div>
						<div class="td td-price">
							<strong class="item-price">￥<?php echo $value['goods_price'];?></strong>
						</div>
						<div class="td td-amount">
							<?php echo $value['goods_num'];?>
						</div>
						<div class="td td-sum">
							<strong class="item-price">￥<?php echo priceformat($value['goods_price']*$value['goods_num'], 2);?></strong>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="order-summary">
				<div class="statistic pull-right">
					<div class="list">
						<label>商品金额：</label>
						<em>￥<?php echo $order['goods_amount'];?></em>
					</div>
					<div class="list">
						<label>运费金额：</label>
						<em>￥<?php echo $order['transport_amount'];?></em>
					</div>
					<div class="list">
						<label>折扣金额：</label>
						<em>￥<?php echo priceformat($order['coupon_amount']+$order['red_amount']);?></em>
					</div>
					<div class="list">
						<label>应付总额：</label>
						<em>￥<?php echo $order['order_amount'];?></em>
					</div>
				</div>
				<div class="clearfix"></div>
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