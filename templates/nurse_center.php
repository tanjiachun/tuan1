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
						<li><a href="index.php?act=nurse_book">我的工作</a></li>
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
				<div class="user-head clearfix">
					<div class="user-info-l">
						<div class="imgHeaderBox">
                        	<a href="javascript:;" class="headerImg">
                            	<?php if(empty($this->nurse['nurse_image'])) { ?>
                                <img src="templates/images/peopleicon_01.gif">
                                <?php } else { ?>
                                <img src="<?php echo $this->nurse['nurse_image'];?>">
                                <?php } ?>
                            </a>
							<div class="updateInfo">
							   <div class="opacityBox"></div>
							   <a href="index.php?act=nurse_resume" class="realBox">修改资料</a>
							</div>
						</div>
						<p class="name"><a href="javascript:;"><?php echo $this->nurse['nurse_name'];?></a></p>
						<p class="VIP">
							<a class="txtExplain"><?php echo $nurse['nurse_phone'];?></a>
						</p>
						<p class="cert-status">
							<span><i class="iconfont icon-certphone"></i>手机认证</span>
							<span><i class="iconfont icon-cert"></i><a href="index.php?act=nurse_resume">实名认证</a></span>
						</p>
					</div>
					<div class="user-info-r">
						<div class="order-num-info">
							<h1>订单</h1>
							<ul>
								<li>
									<a href="index.php?act=nurse_book&state=pending">待付款订单<strong><?php echo $pending_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=nurse_book&state=payment">已付款订单<strong><?php echo $payment_count;?></strong></a>
								</li>
                                <li>
									<a href="index.php?act=nurse_book&state=finish">已完成订单<strong><?php echo $finish_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=nurse_book&state=cancel">已取消订单<strong><?php echo $cancel_count;?></strong></a>
								</li>
							</ul>
						</div>
						<div class="amount-sum">
                        	<h1>总收益</h1>
							<p>
								<strong class="amount-num"><?php echo $this->nurse['plat_amount'];?></strong>
								<a href="index.php?act=nurse_profit" class="btn btn-primary">我的收益 </a>
							</p>
							<p class="other-amount">
								<span>可现提金额<a href="index.php?act=nurse_profit"><?php echo $this->nurse['available_amount'];?></a></span>
								<span>冻结金额<a href="index.php?act=nurse_profit"><?php echo $this->nurse['pool_amount'];?></a></span>
							</p>
						</div>
					</div>
				</div>
				<div class="user-module">
					<h1><strong>我的工作</strong><a href="index.php?act=nurse_book">更多>></a></h1>
					<div class="user-module-body">
                    	<?php if(empty($book_list)) { ?>
                        	<div class="empty-box">
                                <i class="iconfont icon-order"></i>
                                你还没有工作单
                        	</div>
                        <?php } else { ?>
                        <table class="simple-order">
                            <tbody>
                            	<?php foreach($book_list as $key => $value) { ?>
                                <tr>
          							<td width="120">
                                        <div class="simple-img only-img" style="line-height: 50px;">
                                            <?php echo $value['book_phone'];?>
                                        </div>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', $value['work_time']);?></td>
									<td><?php echo $value['work_duration'];?> 个月</td>
                                    <td>￥<?php echo $value['book_amount'];?></td>
									<td>
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
                                    </td>
                                    <td><a href="index.php?act=nurse_book">查看</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
				</div>
			</div>
		</div>
	</div>
<?php include(template('common_footer'));?>