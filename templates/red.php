<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="index.php?act=center"><i class="iconfont icon-user"></i>个人中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
                    	<li><a href="index.php?act=order&op=book">家政人员预约订单</a></li>
						<li><a href="index.php?act=comment">我的评价</a></li>
					</ul>
					<h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=predeposit">钱包</a></li>
						<li><a class="active" href="index.php?act=red">红包</a></li>
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
                    <strong>红包数量：</strong><strong><span class="red"><?php echo $count;?></span></strong>
                </div>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="javascript:;">我的红包</a>
                            </li>
                        </ul>
                    </div>
                    <table class="tb-void">
                        <thead>
                            <tr>
                                <th>红包名称</th>
                                <th>红包面额</th>
                                <th width="150">号码</th>
                                <th width="50">状态</th>
                                <th width="120">到期日期</th>
                                <th width="120">领取日期</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php foreach($red_list as $key => $value) { ?>
                            <tr>
                                <td class="al">
									<?php echo $value['red_title'];?>（<?php echo $red_cate_array[$value['red_cate_id']];?>）
                                    <?php if($value['red_limit'] > 0) { ?>
                                	<p class="red">满<?php echo $value['red_limit'];?>元可用</p>    
                                	<?php } ?>
                                </td>    
                                <td><?php echo $value['red_price'];?>元</td>
                                <td><span class="green"><?php echo $value['red_sn'];?></span></td>
                                <td><span class="red"><?php echo $value['red_state'] == 1 ? '使用' : ($value['red_endtime']<time() ? '过期' : '未用');?></span></td>
                                <td><span class="gray"><?php echo date('Y-m-d', $value['red_endtime']);?></span></td>
                                <td><span class="gray"><?php echo date('Y-m-d', $value['red_addtime']);?></span></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $multi;?>
                </div>
            </div>
        </div>
    </div>        
<?php include(template('common_footer'));?>