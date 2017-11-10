<?php include(template('common_header'));?>
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
						<li><a href="index.php?act=comment">我的评价</a></li>
						<li><a href="index.php?act=cart">购物车</a></li>
					</ul>
					<h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=predeposit">钱包</a></li>
						<li><a href="index.php?act=red">红包</a></li>
						<li><a class="active" href="index.php?act=oldage">养老金</a></li>
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
                    <strong>养老金：</strong><strong><span class="red">￥<?php echo $this->member['oldage_amount'];?></span></strong>
                </div>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="javascript:;">养老金收支明细</a>
                            </li>
                        </ul>
                    </div>
                    <table class="tb-void">
                        <thead>
                            <tr>
                                <th width="200">时间</th>
                                <th width="100">收支</th>
                                <th width="100">余额</th>
                                <th>备注</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php foreach($oldage_list as $key => $value) { ?>
                            <tr>
                                <td><span class="gray"><?php echo date('Y-m-d H:i', $value['oldage_addtime']);?></span></td>
                                <td><span class="<?php echo $value['markclass'];?>"><?php echo $value['mark'];?>￥<?php echo $value['oldage_price'];?></span></td>
                                <td>￥<?php echo $value['oldage_balance'];?></td>
                                <td><div class="al"><?php echo $value['oldage_desc'];?></div></td>
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