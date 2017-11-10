<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>商家中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=store_order">订单管理</a></li>
						<li><a href="index.php?act=store_return">退换货</a></li>
						<li><a href="index.php?act=store_order&state=payment">待发货</a></li>
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
					<h3 class="no4">营销中心</h3>
					<ul>
						<li><a href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no5">商家设置</h3>
					<ul>
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a href="index.php?act=store_transport">运费模板</a></li>
                        <li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="user-head clearfix">
					<div class="user-info-l">
						<div class="imgHeaderBox">
                        	<a href="javascript:;" class="headerImg">
                            	<?php if(empty($this->store['store_logo'])) { ?>
                                <img src="templates/images/peopleicon_01.gif">
                                <?php } else { ?>
                                <img src="<?php echo $this->store['store_logo'];?>">
                                <?php } ?>
                            </a>
							<div class="updateInfo">
							   <div class="opacityBox"></div>
							   <a href="index.php?act=store_profile" class="realBox">修改资料</a>
							</div>
						</div>
						<p class="name"><a href="javascript:;"><?php echo $this->store['store_name'];?></a></p>
						<p class="VIP">
							<a class="txtExplain"><?php echo $store['store_phone'];?></a>
						</p>
						<p class="cert-status">
							<span><i class="iconfont icon-certphone"></i>手机认证</span>
							<span><i class="iconfont icon-cert"></i><a href="index.php?act=store_profile">实名认证</a></span>
						</p>
					</div>
					<div class="user-info-r">
						<div class="order-num-info">
							<h1>订单</h1>
							<ul>
								<li>
									<a href="index.php?act=store_order&state=pending">待付款订单<strong><?php echo $pending_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=store_order&state=payment">待发货订单<strong><?php echo $payment_count;?></strong></a>
								</li>
                                <li>
									<a href="index.php?act=store_order&state=deliver">已发货订单<strong><?php echo $deliver_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=store_return">退换货订单<strong><?php echo $return_count;?></strong></a>
								</li>
							</ul>
						</div>
						<div class="amount-sum">
                        	<h1>总收益</h1>
							<p>
								<strong class="amount-num"><?php echo $this->store['plat_amount'];?></strong>
								<a href="index.php?act=store_profit" class="btn btn-primary">我的收益</a>
							</p>
							<p class="other-amount">
								<span>可现提金额<a href="index.php?act=store_profit"><?php echo $this->store['available_amount'];?></a></span>
								<span>冻结金额<a href="index.php?act=store_profit"><?php echo $this->store['pool_amount'];?></a></span>
							</p>
						</div>
					</div>
				</div>
				<div class="user-module">
					<h1><strong>待发货订单</strong><a href="index.php?act=store_order&state=payment">更多>></a></h1>
					<div class="user-module-body">
						<?php if(empty($order_list)) { ?>
                        <div class="empty-box">
                            <i class="iconfont icon-order"></i>
                            你还没有商品订单
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
					<h1><strong>退换货订单</strong><a href="index.php?act=store_return">更多>></a></h1>
					<div class="user-module-body">
						<?php if(empty($return_list)) { ?>
                        <div class="empty-box">
                            <i class="iconfont icon-order"></i>
                            你还没有退换货订单
                        </div>
                        <?php } else { ?>
                        <table class="simple-order">
                            <tbody>
                            	<?php foreach($return_list as $key => $value) { ?>
                                <tr>
                                    <td width="270">
                                        <div class="simple-img">
                                            <a href="index.php?act=goods&goods_id=<?php echo $return_goods[$value['return_id']]['goods_id'];?>" target="_blank"><img src="<?php echo $return_goods[$value['return_id']]['goods_image'];?>"></a>
                                        </div>
                                    </td>
                                    <td><?php echo $order_address[$value['order_id']]['true_name'];?></td>
                                    <td><?php echo $return_goods[$value['return_id']]['goods_returnnum'];?>件</td>
                                    <td><?php echo date('Y-m-d H:i', $value['return_addtime']);?></td>
                                    <td>
                                    	<?php if($value['return_state'] == 1) { ?>
                                            已通过
                                        <?php } elseif($value['return_state'] == 2) { ?>
                                            不通过
                                        <?php } elseif($value['return_state'] == 3) { ?>
                                            待审核
                                        <?php } ?>
                                    </td>
                                    <td><a href="index.php?act=return" target="_blank">查看</a></td>
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