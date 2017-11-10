<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>家政人员中心</a></h1>
                    <h3 class="no1">简历管理</h3>
					<ul>
						<li><a href="index.php?act=nurse_resume">我的简历</a></li>
					</ul>
					<h3 class="no2">工作管理</h3>
					<ul>
						<li><a class="active" href="index.php?act=nurse_book">我的工作</a></li>
					</ul>
					<h3 class="no3">资产中心</h3>
					<ul>
						<li><a href="index.php?act=nurse_profit">我的收益</a></li>
					</ul>
					<h3 class="no4">账户设置</h3>
					<ul>
						<li><a href="index.php?act=nurse_password">密码修改</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>我的预约</strong>
				</div>
				<div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=nurse_book"<?php echo empty($state) ? ' class="active"' : '';?>>全部订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=nurse_book&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待付款</a>
                            </li>
                            <li>
                                <a href="index.php?act=nurse_book&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>已付款</a>
                            </li>
							<li>
                                <a href="index.php?act=nurse_book&state=finish"<?php echo $state=='finish' ? ' class="active"' : '';?>>已完成</a>
                            </li>
							<li>
                                <a href="index.php?act=nurse_book&state=cancel"<?php echo $state=='cancel' ? ' class="active"' : '';?>>已取消</a>
                            </li>							
                        </ul>
                        <div class="pull-right">
                            <div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="nurse_book">
									<input type="hidden" name="state" value="<?php echo $state;?>">
									<input type="text" id="search_name" name="search_name" class="itxt" placeholder="手机号/预约单号" value="<?php echo $search_name;?>">
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
									<th width="100">开始服务时间</th>
									<th width="80">服务时长</th>
									<th width="80">预付金</th>
									<th width="100">状态与操作</th>
                                </tr>
                            </thead>
							<?php foreach($book_list as $key => $value) { ?>
                            <tbody id="book_<?php echo $value['book_id'];?>">
                                <tr class="sep-row"><td colspan="5"></td></tr>
                                <tr class="tr-th">
                                    <td colspan="5">
                                        <span><?php echo date('Y-m-d H:i', $value['add_time']);?></span>
                                        <span>预约单号：<?php echo $value['book_sn'];?></span>
                                    </td>
                                </tr>
                                <tr class="tr-bd">
									<td>
										<div class="td-inner clearfix w-200">
											<div class="item-info" style="margin: 3px 0 3px 0px;">
												<a href="javascript:;"><?php echo $value['book_phone'];?></a>
											</div>
										</div>
									</td>									
                                    <td><?php echo date('Y-m-d', $value['work_time']);?><br /><?php echo date('H:i', $value['work_time']);?></td>
									<td><?php echo $value['work_duration'];?> 个月</td>
                                    <td>
										￥<?php echo $value['book_amount'];?>
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
									</td>								
                                </tr>
								<tr class="tr-ft">
									<td colspan="5">
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
<?php include(template('common_footer'));?>