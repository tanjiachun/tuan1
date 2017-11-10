<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>养老机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=pension_bespoke">订单管理</a></li>
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
				<div class="user-head clearfix">
					<div class="user-info-l">
						<div class="imgHeaderBox">
                        	<a href="javascript:;" class="headerImg">
                            	<?php if(empty($this->pension['pension_logo'])) { ?>
                                <img src="templates/images/peopleicon_01.gif">
                                <?php } else { ?>
                                <img src="<?php echo $this->pension['pension_logo'];?>">
                                <?php } ?>
                            </a>
							<div class="updateInfo">
							   <div class="opacityBox"></div>
							   <a href="index.php?act=pension_profile" class="realBox">修改资料</a>
							</div>
						</div>
						<p class="name"><a href="javascript:;"><?php echo $this->pension['pension_name'];?></a></p>
						<p class="VIP">
							<a class="txtExplain"><?php echo $pension['pension_phone'];?></a>
						</p>
						<p class="cert-status">
							<span><i class="iconfont icon-certphone"></i>手机认证</span>
							<span><i class="iconfont icon-cert"></i><a href="index.php?act=pension_profile">实名认证</a></span>
						</p>
					</div>
					<div class="user-info-r">
						<div class="order-num-info">
							<h1>订单</h1>
							<ul>
								<li>
									<a href="index.php?act=pension_bespoke&state=pending">待付款订单<strong><?php echo $pending_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=pension_bespoke&state=payment">已付款订单<strong><?php echo $payment_count;?></strong></a>
								</li>
                                <li>
									<a href="index.php?act=pension_bespoke&state=finish">已完成订单<strong><?php echo $finish_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=pension_bespoke&state=cancel">已取消订单<strong><?php echo $cancel_count;?></strong></a>
								</li>
							</ul>
						</div>
						<div class="amount-sum">
                        	<h1>总收益</h1>
							<p>
								<strong class="amount-num"><?php echo $this->pension['plat_amount'];?></strong>
								<a href="index.php?act=pension_profit" class="btn btn-primary">我的收益</a>
							</p>
							<p class="other-amount">
								<span>可现提金额<a href="index.php?act=pension_profit"><?php echo $this->pension['available_amount'];?></a></span>
								<span>冻结金额<a href="index.php?act=pension_profit"><?php echo $this->pension['pool_amount'];?></a></span>
							</p>
						</div>
					</div>
				</div>
				<div class="user-module">
					<h1><strong>房间预定订单</strong><a href="index.php?act=pension_bespoke">更多>></a></h1>
					<div class="user-module-body">
						<?php if(empty($bespoke_list)) { ?>
							<div class="empty-box">
								<i class="iconfont icon-order"></i>
								你还没有预定订单
							</div>
						<?php } else { ?>
						<table class="simple-order">
							<tbody>
								<?php foreach($bespoke_list as $key => $value) { ?>
								<tr>
									<td width="120">
										<div class="simple-img only-img">
											<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>" target="_blank"><img src="<?php echo $room_list[$value['room_id']]['room_image'];?>"></a>
										</div>
									</td>
									<td><?php echo $room_list[$value['room_id']]['room_name'];?></td>
									<td><?php echo $value['bespoke_name'];?></td>
									<td><?php echo $value['bespoke_phone'];?></td>
									<td><?php echo date('Y-m-d H:i', $value['live_time']);?></td>
									<td><?php echo $value['live_duration'];?> 个月</td>
									<td><?php echo $value['bed_number'];?> 个床位</td>
									<td>￥<?php echo $value['bespoke_amount'];?></td>
									<td>
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
									</td>
									<td>
										<td><a href="index.php?act=pension_bespoke">查看</a></td>
									</td>
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