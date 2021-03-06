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
                        <li><a class="active" href="index.php?act=order&op=bespoke">房间预定订单</a></li>
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
					<strong>房间预定订单</strong>
				</div>
				<div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=order&op=bespoke"<?php echo empty($state) ? ' class="active"' : '';?>>全部订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=order&op=bespoke&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待付款</a>
                            </li>
                            <li>
                                <a href="index.php?act=order&op=bespoke&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>已付款</a>
                            </li>
							<li>
                                <a href="index.php?act=order&op=bespoke&state=finish"<?php echo $state=='finish' ? ' class="active"' : '';?>>已完成</a>
                            </li>
							<li>
                                <a href="index.php?act=order&op=bespoke&state=cancel"<?php echo $state=='cancel' ? ' class="active"' : '';?>>已取消</a>
                            </li>							
                        </ul>
                        <div class="pull-right">
                            <div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="order">
									<input type="hidden" name="op" value="bespoke">
									<input type="hidden" name="state" value="<?php echo $state;?>">
									<input type="text" id="search_name" name="search_name" class="itxt" placeholder="机构名称/手机号/预约单号" value="<?php echo $search_name;?>">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>
                            </div>
                        </div>
                    </div>
                    <div class="orderlist-body">
                        <table class="order-tb">
							<thead>
								<tr>
									<th width="200">机构详情</th>
									<th width="100">入驻时间</th>
									<th width="80">入驻时长</th>
									<th width="80">金额</th>
									<th width="100">状态与操作</th>
								</tr>
							</thead>
							<?php foreach($bespoke_list as $key => $value) { ?>
                            <tbody>
                            	<tr class="sep-row"><td colspan="8"></td></tr>
                            	<tr class="tr-th">
                                	<td colspan="5">
                                    	<span><?php echo date('Y-m-d H:i', $value['add_time']);?></span>
                                        <span>预定单号：<?php echo $value['bespoke_sn'];?></span>
                                	</td>
                            	</tr>
								<tr class="tr-bd">
									<td>
										<div class="td-inner clearfix w-200">
											<div class="item-pic">
												<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>" target="_blank"><img src="<?php echo $pension_list[$value['pension_id']]['pension_image'];?>"></a>
											</div>
											<div class="item-info">
												<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>" target="_blank"><?php echo $room_list[$value['room_id']]['room_name'];?></a>
												<p><?php echo $pension_list[$value['pension_id']]['pension_name'];?></p>
                                                <p><?php echo $person_array[$value['pension_id']];?></p>
											</div>
										</div>
									</td>
									<td><?php echo date('Y-m-d', $value['live_time']);?><br /><?php echo date('H:i', $value['live_time']);?></td>
									<td><?php echo $value['live_duration'];?> 个月</td>
									<td>
										￥<?php echo $value['bespoke_amount'];?>
										<?php if(!empty($value['invoice_content'])) { ?>
										<p><a href="javascript:;" class="tooltips" bespoke_id="<?php echo $value['bespoke_id'];?>">发票详情</a></p>
										<div id="invoice_content_<?php echo $value['bespoke_id'];?>" style="display:none;">
											发票抬头：<?php echo $value['invoice_content']['invoice_title'];?><br />
											发票明细：<?php echo $value['invoice_content']['invoice_content'];?><br />
											收件人：<?php echo $value['invoice_content']['invoice_membername'];?><br />
											邮寄地址：<?php echo $value['invoice_content']['invoice_areainfo'].$value['invoice_content']['invoice_address'];?>
										</div>
										<?php } ?>
										<?php if($value['red_amount'] > 0) { ?>
										<p style="color:#999;">（红包优惠 ￥<?php echo $value['red_amount'];?>）</p>
										<?php } ?>
									</td>
									<td>
										<p class="state" id="state_<?php echo $value['bespoke_id'];?>">
											<?php if(empty($value['bespoke_state'])) { ?>
												<?php if(empty($value['refund_state'])) { ?>
                                                    已取消
                                                <?php } else { ?>
                                                    已退款
                                                <?php } ?>
											<?php } elseif($value['bespoke_state'] == 10) { ?>
												待付款
											<?php } elseif($value['bespoke_state'] == 20) { ?>
												<?php if(empty($value['refund_state'])) { ?>
                                                    已付款
                                                <?php } elseif($value['refund_state'] == 1) { ?>
                                                    待退款
                                                <?php } else { ?>
                                                    已拒绝
                                                <?php } ?>
											<?php } elseif($value['bespoke_state'] == 30) { ?>
												<?php if(empty($value['comment_state'])) { ?>
													待评价
												<?php } else { ?>
													已评价
												<?php } ?>
											<?php } ?>
										</p>
										<div id="opr_<?php echo $value['bespoke_id'];?>">
										<?php if($value['bespoke_state'] == 10) { ?>
											<a href="index.php?act=bespoke&op=payment&bespoke_sn=<?php echo $value['bespoke_sn'];?>" class="btn btn-primary">立即付款</a>
											<p><a href="javascript:;" class="bespoke-cancel" bespoke_id="<?php echo $value['bespoke_id'];?>">取消预定</a></p>
										<?php } elseif($value['bespoke_state'] == 20) { ?>
											<?php if(time() < $value['validity_period']) { ?>
                                            	<?php if($value['refund_state'] != 1) { ?>
                                                <a href="javascript:;" class="btn btn-primary bespoke-refund" bespoke_id="<?php echo $value['bespoke_id'];?>">我要退款</a>
                                                <?php } ?>
                                            <?php } else { ?>
                                            <a href="javascript:;" class="btn btn-primary bespoke-finish" bespoke_id="<?php echo $value['bespoke_id'];?>">确定入驻</a>
                                            <?php } ?>
										<?php } elseif($value['bespoke_state'] == 30) { ?>
                                        	<?php if(empty($value['comment_state'])) { ?>
											<a href="index.php?act=order&op=bespoke_comment&bespoke_id=<?php echo $value['bespoke_id'];?>" class="btn btn-primary">立即评价</a>
                                            <?php } ?>
										<?php } ?>
										</div>
									</td>	
								</tr>
								<tr class="tr-ft">
									<td colspan="5">
										<div class="reture-exp">
											<label>床位数：<?php echo $value['bed_number'];?></label>
										</div>
										<div class="reture-exp">
											<label>
                                            	客户信息：<?php echo $value['bespoke_message'];?>
                                            </label>
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
			<h4><uik>我要退款</uik></h4>
			<span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
		</div>
    	<div class="modal-bd">
			<div class="cont-modal">
				<div class="cont-item">
					<label>退款原因</label>
					<textarea id="refund_reason" name="refund_reason"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-ft">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="refundsubmit();">确定</a>
		</div>
	</div>
	<div class="modal-wrap w-400" id="finish-box" style="display: none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="finish_id" name="finish_id" value="" />
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">您确定要入驻吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			<a class="btn btn-default" onclick="Custombox.close();">取消</a>
			<a class="btn btn-primary" onclick="finishsubmit();">确定</a>			
		</div>
	</div>
	<link href="templates/css/jquery.tooltips.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="templates/js/jquery.tooltips.js"></script>
	<script type="text/javascript" src="templates/js/profile/order_bespoke.js"></script>
<?php include(template('common_footer'));?>