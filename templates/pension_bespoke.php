<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>养老机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a class="active" href="index.php?act=pension_bespoke">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=pension_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">机构配套</h3>
					<ul>
						<li><a href="index.php?act=pension_room">房间管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a href="index.php?act=pension_profile">机构信息</a></li>
						<li><a href="index.php?act=pension_profile&op=near">机构周边</a></li>
                        <li><a href="index.php?act=pension_profile&op=equipment">机构设施</a></li>
                        <li><a href="index.php?act=pension_profile&op=service">机构服务</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>订单管理</strong>
				</div>
				<div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=pension_bespoke"<?php echo empty($state) ? ' class="active"' : '';?>>全部订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=pension_bespoke&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待付款</a>
                            </li>
                            <li>
                                <a href="index.php?act=pension_bespoke&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>已付款</a>
                            </li>
							<li>
                                <a href="index.php?act=pension_bespoke&state=finish"<?php echo $state=='finish' ? ' class="active"' : '';?>>已完成</a>
                            </li>
							<li>
                                <a href="index.php?act=pension_bespoke&state=cancel"<?php echo $state=='cancel' ? ' class="active"' : '';?>>已取消</a>
                            </li>							
                        </ul>
                        <div class="pull-right">
                            <div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="pension_bespoke">
									<input type="hidden" name="state" value="<?php echo $state;?>">
									<input type="text" id="search_name" name="search_name" class="itxt" placeholder="客户名称/手机号/预定单号" value="<?php echo $search_name;?>">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>
                            </div>
                        </div>
                    </div>
                    <div class="orderlist-body">
                        <table class="order-tb">
							<thead>
								<tr>
									<th width="200">客户详情</th>
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
												<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>" target="_blank"><img src="<?php echo $room_list[$value['room_id']]['room_image'];?>"></a>
											</div>
											<div class="item-info">
												<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>" target="_blank"><?php echo $room_list[$value['room_id']]['room_name'];?></a>
												<p><?php echo $value['bespoke_name'];?></p>
                                                <p><?php echo $value['bespoke_phone'];?></p>
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
										<?php if($value['bespoke_state'] == 20 && $value['refund_state'] == 1) { ?>
                                            <a href="javascript:;" class="btn btn-default bespoke-refund" bespoke_id="<?php echo $value['bespoke_id'];?>" refund_amount="<?php echo $value['refund_amount'];?>" refund_reason="<?php echo $value['refund_reason'];?>">退款处理</a>
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
	<link href="templates/css/jquery.tooltips.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="templates/js/jquery.tooltips.js"></script>
    <script type="text/javascript" src="templates/js/profile/pension_bespoke.js"></script>
<?php include(template('common_footer'));?>