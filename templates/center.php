    <?php include(template('common_header'));?>
	<div class="conwp">
        <div class="user-main">
            <div class="user-left">
                <div class="user-nav">
                    <h1><a href="javascript:;"><i class="iconfont icon-user"></i>个人中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
                    	<li><a href="index.php?act=order&op=book">家政人员预约订单</a></li>
						<li><a href="index.php?act=comment">我的评价</a></li>
					</ul>
					<h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=predeposit">钱包</a></li>
						<li><a href="index.php?act=red">红包</a></li>
					</ul>
					<h3 class="no3">收藏中心</h3>
					<ul>
						<li><a href="index.php?act=favorite&op=nurse">收藏的家政人员</a></li>
					</ul>
					<h3 class="no4">账户设置</h3>
					<ul>
						<li><a href="index.php?act=profile">个人信息</a></li>
						<li><a href="index.php?act=password">密码修改</a></li>
					</ul>
                </div>
            </div>
            <div class="user-right">
                <div class="user-head clearfix">
                    <div class="user-info-l">
                        <div class="imgHeaderBox">
                            <a href="javascript:;" class="headerImg">
                            	<?php if(empty($this->member['member_avatar'])) { ?>
                                <img src="templates/images/peopleicon_01.gif">
                                <?php } else { ?>
                                <img src="<?php echo $this->member['member_avatar'];?>">
                                <?php } ?>
                            </a>
                            <div class="updateInfo">
                               <div class="opacityBox"></div>
                               <a href="index.php?act=profile" class="realBox">修改资料</a>
                            </div>
                        </div>
                        <p class="name"><a href="javascript:;"><?php echo $this->member['member_phone'];?></a></p>                        
                        <p class="VIP">
							<?php if(!empty($card)) { ?>
                            <a class="imgVip"><image src="<?php echo $card['card_icon'];?>"></image></a>
                            <a class="txtExplain"><?php echo $card['card_name'];?></a>
                        	<?php } ?>
                        </p>                        
                        <p class="cert-status">
                            <span><i class="iconfont icon-certphone"></i>手机认证</span>
                            <span><i class="iconfont icon-cert"></i><a href="index.php?act=profile">实名认证</a></span>
                        </p>
                    </div>
                    <div class="user-info-r">
                        <div class="order-num-info">
                            <h1>订单</h1>
                            <ul>
                            	<li>
                                    <a href="index.php?act=order&op=book">家政人员预约订单<strong><?php echo $book_count;?></strong></a>
                                </li>
                                <li>
                                    <a href="index.php?act=order">养老商品订单<strong><?php echo $order_count;?></strong></a>
                                </li>
                                <li>
                                    <a href="index.php?act=order&op=bespoke">房间预定订单<strong><?php echo $bespoke_count;?></strong></a>
                                </li>
                            </ul>
                        </div>
                        <div class="amount-sum">
                            <h1>余额</h1>
                            <p>
                                <strong class="amount-num"><?php echo $this->member['available_predeposit'];?></strong>
                                <a href="index.php?act=recharge" class="btn btn-primary">充值</a>
                                <a href="index.php?act=cash" class="btn btn-default">提现</a>
                            </p>
                            <p class="other-amount">
                                <span>红包<a href="index.php?act=red"><?php echo $red_count;?></a>个</span>
                                <span>养老金<a href="index.php?act=oldage"><?php echo $this->member['oldage_amount'];?></a></span>
                                <span>优惠券<a href="index.php?act=coupon"><?php echo $coupon_count;?></a>张</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="user-module">
                    <h1><strong>家政人员预约订单</strong><a href="index.php?act=order&op=book">更多>></a></h1>
                    <div class="user-module-body">
                    	<?php if(empty($book_list)) { ?>
                        	<div class="empty-box">
                                <i class="iconfont icon-order"></i>
                                你还没有预约订单，<a href="index.php?act=index&op=nurse">去逛逛吧</a>
                        	</div>
                        <?php } else { ?>
                        <table class="simple-order">
                            <tbody>
                            	<?php foreach($book_list as $key => $value) { ?>
                                <tr>
                                    <td width="120">
                                        <div class="simple-img only-img">
                                            <a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'];?>"></a>
                                        </div>
                                    </td>
                                    <td><?php echo $nurse_list[$value['nurse_id']]['nurse_name'];?></td>
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
                                    <td><a href="index.php?act=order&op=book">查看</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
                <div class="user-module">
                    <h1><strong>养老商品订单</strong><a href="index.php?act=order">更多>></a></h1>
                    <div class="user-module-body">
                    	<?php if(empty($order_list)) { ?>
                        <div class="empty-box">
                            <i class="iconfont icon-order"></i>
                            你还没有商品订单，<a href="index.php?act=index&op=goods">去逛逛吧</a>
                        </div>
                        <?php } else { ?>
                        <table class="simple-order">
                            <tbody>
                            	<?php foreach($order_list as $key => $value) { ?>
                                <tr>
                                    <td width="270">
                                        <div class="simple-img">
                                        	<?php foreach($order_goods[$value['order_id']] as $subkey => $subvalue) { ?>
                                            <a href="index.php?act=goods&goods_id=<?php echo $subvalue['goods_id'];?>" target="_blank"><img src="<?php echo $subvalue['goods_image'];?>"></a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td><?php echo $order_address[$value['order_id']]['true_name'];?></td>
                                    <td><?php echo date('Y-m-d H:i', $value['add_time']);?></td>
									<td>￥<?php echo $value['order_amount'];?></td>
                                    <td>
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
                                    </td>
                                    <td><a href="index.php?act=order&op=view&order_id=<?php echo $value['order_id'];?>" target="_blank">查看</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
				</div>	
				<div class="user-module">
					<h1><strong>房间预定订单</strong><a href="index.php?act=order&op=bespoke">更多>></a></h1>
					<div class="user-module-body">
						<?php if(empty($bespoke_list)) { ?>
							<div class="empty-box">
								<i class="iconfont icon-order"></i>
								你还没有预定订单，<a href="index.php?act=index&op=pension">去逛逛吧</a>
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
										<td><a href="index.php?act=order&op=bespoke">查看</a></td>
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