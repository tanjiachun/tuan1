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
						<li><a href="index.php?act=red">红包</a></li>
					</ul>
					<h3 class="no3">收藏中心</h3>
					<ul>
						<li><a href="index.php?act=favorite&op=nurse">收藏的家政人员</a></li>
					</ul>
					<h3 class="no4">账户设置</h3>
					<ul>
						<li><a href="index.php?act=profile">个人信息</a></li>
						<li><a class="active" href="index.php?act=password">密码修改</a></li>
					</ul>
                </div>
            </div>
            <div class="user-right">
				<div class="center-title clearfix">
					<strong>密码修改</strong>
				</div>
                <div class="user-info">
                    <div class="form-list">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                        <div class="form-item clearfix">
                            <label>原密码</label>
                            <div class="form-item-value">
                                <input type="password" id="old_password" name="old_password" class="form-input w-400" placeholder="输入你的原密码">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label>新密码</label>
                            <div class="form-item-value">
                                <input type="password" id="member_password" name="member_password" class="form-input w-400" placeholder="输入你的新密码">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label>确认密码</label>
                            <div class="form-item-value">
                                <input type="password" id="member_password2" name="member_password2" class="form-input w-400" placeholder="确认你的新密码">
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
                        <div class="form-item clearfix">
                            <label>&nbsp;</label>
                            <div class="form-item-value">
                                <a href="javascript:checksubmit();" class="btn btn-primary">提交</a><span class="return-success"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script type="text/javascript" src="templates/js/profile/password.js"></script>
<?php include(template('common_footer'));?>