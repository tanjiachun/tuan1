<?php include(template('common_header'));?>
	<style>
		.commit-item{
			border-bottom: 0px;
			padding: 0px;
		}
	</style>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>个人中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
                    	<li><a href="index.php?act=order&op=book">家政人员预约订单</a></li>
						<li><a href="index.php?act=order">养老商品订单</a></li>						
                        <li><a href="index.php?act=order&op=bespoke">房间预定订单</a></li>
						<li><a class="active" href="index.php?act=comment">我的评价</a></li>
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
					<strong>我的评价</strong>
				</div>
				<div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=comment">家政人员评价</a>
                            </li>
                            <li>
                                <a href="index.php?act=comment&op=goods">商品评价</a>
                            </li>
                            <li>
                                <a href="index.php?act=comment&op=pension" class="active">机构评价</a>
                            </li>						
                        </ul>
                    </div>
                    <div class="orderlist-body">
                        <table class="order-tb">
							<thead>
								<tr>
									<th width="200">机构</th>
									<th width="80">满意度</th>
									<th width="150">评价分数</th>
									<th>评价内容</th>
								</tr>
							</thead>
							<?php foreach($comment_list as $key => $value) { ?>
                            <tbody>
								<tr class="tr-bd">
									<td>
										<div class="td-inner clearfix w-200">
											<div class="item-pic">
												<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>" target="_blank"><img src="<?php echo $pension_list[$value['pension_id']]['pension_image'];?>"></a>
											</div>
											<div class="item-info">
												<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>" target="_blank"><?php echo $pension_list[$value['pension_id']]['pension_name'];?></a>
												<p><?php echo $person_array[$pension_list[$value['pension_id']]['pension_type']];?></p>
											</div>
										</div>
									</td>
									<td><?php echo $level_array[$value['comment_level']];?></td>
									<td>
										<div class="commit-item">
											<div class="commit-score">
												<div class="score-item">
													<?php for($i=0; $i<5; $i++) { ?>
													<?php if($i < $value['comment_score']) { ?>
													<i class="iconfont icon-solidstar cur"></i>
													<?php } else { ?>
													<i class="iconfont icon-solidstar"></i>
													<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div>	
									</td>
									<td style="text-align: left;">
										<div class="commit-item">
											<div class="commit-info" style="width:100%">
												<div class="commit-desc"><?php echo $value['comment_content'];?></div>
												<?php if(!empty($value['comment_image'])) { ?>
												<div class="commit-img">
													<ul>
														<?php foreach($value['comment_image'] as $subkey => $subvalue) { ?>
														<li><img src="<?php echo $subvalue;?>"></li>
														<?php } ?>
													</ul>
												</div>
												<?php } ?>
											</div>
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
<?php include(template('common_footer'));?>