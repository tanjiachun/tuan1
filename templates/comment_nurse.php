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
					<h1><a href="index.php?act=center"><i class="iconfont icon-user"></i>个人中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
                    	<li><a href="index.php?act=order&op=book">家政人员预约订单</a></li>			
						<li><a class="active" href="index.php?act=comment">我的评价</a></li>
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
				<div class="center-title clearfix">
					<strong>我的评价</strong>
				</div>
				<div class="orderlist">
                    <div class="orderlist-body">
                        <table class="order-tb">
							<thead>
								<tr>
									<th width="200">家政人员</th>
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
												<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'];?>"></a>
											</div>
											<div class="item-info">
												<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><?php echo $nurse_list[$value['nurse_id']]['nurse_name'];?></a>
												<p><?php echo $nurse_list[$value['nurse_id']]['member_phone'];?></p>
											</div>
										</div>
									</td>
									<td><?php echo $level_array[$value['comment_level']];?></td>
									<td>
										<div class="commit-item">
											<div class="commit-score">
												<span>诚实守信</span>
												<div class="score-item">
													<?php for($i=0; $i<5; $i++) { ?>
													<?php if($i < $value['comment_honest_star']) { ?>
													<i class="iconfont icon-solidstar cur"></i>
													<?php } else { ?>
													<i class="iconfont icon-solidstar"></i>
													<?php } ?>
													<?php } ?>
												</div>
												<span>爱岗敬业</span>
												<div class="commit-score">
													<div class="score-item">
														<?php for($i=0; $i<5; $i++) { ?>
														<?php if($i < $value['comment_love_star']) { ?>
														<i class="iconfont icon-solidstar cur"></i>
														<?php } else { ?>
														<i class="iconfont icon-solidstar"></i>
														<?php } ?>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>	
									</td>
									<td style="text-align: left;">
										<div class="commit-item">
											<div class="commit-info" style="width:100%">
												<div class="commit-desc"><?php echo $value['comment_content'];?></div>
												<?php if(!empty($value['comment_tags'])) { ?>
												<div class="commit-tag">
													<?php foreach($value['comment_tags'] as $subkey => $subvalue) { ?>
													<i class="tag"><?php echo $subvalue;?></i>
													<?php } ?>
												</div>
												<?php } ?>
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