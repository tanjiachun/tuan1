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
						<li><a class="active" href="index.php?act=address">收货地址</a></li>
						<li><a href="index.php?act=password">密码修改</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="user-info">
					<div class="address-tool">
						<a href="javascript:;" class="btn btn-primary cont-edit address-add">新增收货地址</a>
						您已创建<span id="address_count"><?php echo $address_count;?></span>个收货地址，最多可创建<span>20</span>个
					</div>
					<div class="address-list">
						<?php foreach($address_list as $key => $value) { ?>
						<div class="address-item">
							<h1>
								<strong><?php echo $value['true_name'];?></strong>
                                <?php if(!empty($value['address_default'])) { ?>
                                <em class="address-default">默认地址</em>
                                <?php } ?>
								<span class="address-del" address_id="<?php echo $value['address_id'];?>"><i class="iconfont icon-fork"></i></span>
							</h1>
							<div class="address-body">
								<div class="item clearfix">
									<span class="label">联系人：</span>
									<div class="fl"><?php echo $value['true_name'];?></div>
								</div>
								<div class="item clearfix">
									<span class="label">电话：</span>
									<div class="fl"><?php echo $value['mobile_phone'];?></div>
								</div>
								<div class="item clearfix">
									<span class="label">所在地区：</span>
									<div class="fl"><?php echo $value['area_info'];?></div>
								</div>
								<div class="item clearfix">
									<span class="label">地址：</span>
									<div class="fl"><?php echo $value['address_info'];?></div>
								</div>
								<div class="extra">
                                	<?php if(empty($value['address_default'])) { ?>
									<a class="address-default" href="javascript:;" address_id="<?php echo $value['address_id'];?>">设为默认</a>
                                    <?php } ?>
									<a class="cont-edit address-edit" href="javascript:;" address_id="<?php echo $value['address_id'];?>">编辑</a>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>	
                </div>
            </div>
		</div>
	</div>
    <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
    <div class="modal-wrap w-400" id="del-box" style="display:none;">
        <div class="Validform-checktip Validform-wrong m-tip"></div>            
        <input type="hidden" id="del_id" name="del_id" value="" />
        <div class="modal-bd">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <span class="tip-icon">
                        <i class="iconfont icon-info"></i>
                    </span>
                    <h3 class="tip-message">你确定要删除吗？</h3>
                </div>
            </div>
        </div>
        <div class="modal-ft tc">
             <a class="btn btn-default" onclick="Custombox.close();">取消</a>
             <a class="btn btn-primary" onclick="delsubmit();">确定</a>
        </div>
    </div>
    <div class="modal-wrap w-400" id="default-box" style="display:none;">
        <div class="Validform-checktip Validform-wrong m-tip"></div>            
        <input type="hidden" id="default_id" name="default_id" value="" />
        <div class="modal-bd">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <span class="tip-icon">
                        <i class="iconfont icon-info"></i>
                    </span>
                    <h3 class="tip-message">你确定要设置默认吗？</h3>
                </div>
            </div>
        </div>
        <div class="modal-ft tc">
             <a class="btn btn-default" onclick="Custombox.close();">取消</a>
             <a class="btn btn-primary" onclick="defaultsubmit();">确定</a>
        </div>
    </div>
	<div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/profile/address.js"></script>
<?php include(template('common_footer'));?>