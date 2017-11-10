<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="index.php?act=agent&op=login"><i class="iconfont icon-user"></i>家政机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a class="active" href="index.php?act=agent_book">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=agent_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">家政人员中心</h3>
					<ul>
						<li><a href="index.php?act=agent_nurse">家政人员管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a href="index.php?act=agent_profile">机构信息</a></li>
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
                                <a href="index.php?act=agent_book"<?php echo empty($state) ? ' class="active"' : '';?>>全部订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待付款</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>已付款</a>
                            </li>
							<li>
                                <a href="index.php?act=agent_book&state=finish"<?php echo $state=='finish' ? ' class="active"' : '';?>>已完成</a>
                            </li>
							<li>
                                <a href="index.php?act=agent_book&state=cancel"<?php echo $state=='cancel' ? ' class="active"' : '';?>>已取消</a>
                            </li>							
                        </ul>
                        <div class="pull-right">
                            <div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="agent_book">
									<input type="hidden" name="state" value="<?php echo $state;?>">
									<input type="text" id="search_name" name="search_name" class="itxt" placeholder="家政人员姓名/手机号/预约单号">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>
                            </div>
                        </div>
                    </div>
                    <div class="orderlist-body">
                        <table class="order-tb">
                            <thead>
                                <tr>
                                    <th width="200">家政人员详情</th>
									<th width="100">客户详情</th>
									<th width="100">开始服务时间</th>
									<th width="80">服务时长</th>
									<th width="80">预付金</th>
									<th width="100">状态与操作</th>
                                </tr>
                            </thead>
							<?php foreach($book_list as $key => $value) { ?>
                            <tbody id="book_<?php echo $value['book_id'];?>">
                                <tr class="sep-row"><td colspan="6"></td></tr>
                                <tr class="tr-th">
                                    <td colspan="6">
                                        <span><?php echo date('Y-m-d H:i', $value['add_time']);?></span>
                                        <span>预约单号：<?php echo $value['book_sn'];?></span>
                                    </td>
                                </tr>
                                <tr class="tr-bd">
									<td>
										<div class="td-inner clearfix w-200">
											<div class="item-pic">
												<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'];?>"></a>
											</div>
											<div class="item-info">
												<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><?php echo $nurse_list[$value['nurse_id']]['nurse_name'];?></a>
												<p><?php echo $nurse_list[$value['nurse_id']]['member_phone'];?></p>
											</div>
										</div>
									</td>
									<td>
										<?php echo $value['book_phone'];?>
									</td>							
                                    <td><?php echo date('Y-m-d', $value['work_time']);?><br /><?php echo date('H:i', $value['work_time']);?></td>
									<td><?php echo $value['work_duration'];?> 个月</td>
                                    <td>
										￥<?php echo $value['book_amount']; ?>
										<?php if($value['red_amount'] > 0) { ?>
										<p style="color:#999;">（红包优惠 ￥<?php echo $value['red_amount'];?>）</p>
										<?php } ?>
									</td>
									<td>
										<p class="state" id="state_<?php echo $value['book_id'];?>">
											<?php if(empty($value['book_state'])) { ?>
												<?php if(empty($value['refund_state'])) { ?>
													已取消
												<?php } else { ?>
													已退款
												<?php } ?>
											<?php } elseif($value['book_state'] == 10) { ?>
												待付款
											<?php } elseif($value['book_state'] == 20) { ?>
												<?php if(empty($value['refund_state'])) { ?>
                                                    已付款
                                                <?php } elseif($value['refund_state'] == 1) { ?>
                                                    待退款
                                                <?php } else { ?>
                                                    已拒绝
                                                <?php } ?>
											<?php } elseif($value['book_state'] == 30) { ?>
												<?php if(empty($value['comment_state'])) { ?>
													待评价
												<?php } else { ?>
													已评价
												<?php } ?>
											<?php } ?>
										</p>
                                        <div id="opr_<?php echo $value['book_id'];?>">
										<?php if($value['book_state'] == 20 && $value['refund_state'] == 1) { ?>
                                            <a href="javascript:;" class="btn btn-default book-refund" book_id="<?php echo $value['book_id'];?>" refund_amount="<?php echo $value['refund_amount'];?>" refund_reason="<?php echo $value['refund_reason'];?>">退款处理</a>
										<?php } ?>
										</div>
									</td>								
                                </tr>
								<tr class="tr-ft">
									<td colspan="6">
										<div class="reture-exp">
											<label>雇主情况：<?php echo $value['book_message'];?></label>
										</div>
										<?php if(!empty($value['book_service'])) { ?>
										<div class="reture-exp">
											<label>额外服务：<?php echo $value['book_service'];?></label>
										</div>
										<?php } ?>
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
    <script type="text/javascript" src="templates/js/profile/agent_book.js"></script>
<?php include(template('common_footer'));?>