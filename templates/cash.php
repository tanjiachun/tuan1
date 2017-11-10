<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
    <link rel="stylesheet" href="templates/css/admin.css">
    <div class="member_center_header">
        <p>我的团家政 —— 我的钱包</p>
    </div>
    </div>
	<div class="conwp">
		<div class="user-main">
            <div id="member_manage_sidebar" class="left">
                <div class="member_manage_image">
                    <?php if(empty($this->member['member_avatar'])) { ?>
                        <img width="100px" height="100px" src="templates/images/peopleicon_01.gif">
                    <?php } else { ?>
                        <img width="100px" height="100px" src="<?php echo $this->member['member_avatar'];?>">
                    <?php } ?>
                </div>
                <ul class="member_sidebar_list">
                    <li class="staff_set_list">
                        <a class="list_show">账户与安全</a>
                        <ul style="display: none;">
                            <li><a href="index.php?act=member_center" style="font-size: 12px;">· 个人资料</a></li>
                            <li><a href="index.php?act=member_real_name" style="font-size: 12px;">· 实名认证</a></li>
                            <li><a href="index.php?act=member_address_set" style="font-size: 12px;">· 地址管理</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?act=member_password_set" >密码管理</a></li>
                    <li><a href="index.php?act=member_book">我的订单</a></li>
                    <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_wallet">我的钱包</a></li>
                    <li><a href="index.php?act=member_comment">我的评价</a></li>
                    <li><a href="index.php?act=favorite&op=nurse">我的关注</a></li>
                </ul>
                <script>
                    $(".list_show").click(function () {
                        if(!$(".staff_set_list ul").is(":hidden")){
                            $(".staff_set_list ul").fadeOut();
                            $(".staff_set_list img").attr('src','templates/images/toBW.png');
                        }else{
                            $(".staff_set_list ul").fadeIn();
                            $(".staff_set_list img").attr('src','templates/images/toTopW.png');
                        }
                    })
                </script>
            </div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>钱包提现</strong>
					<span class="pull-right">
						<a href="index.php?act=predeposit" class="btn btn-default">返回</a>
					</span>
				</div>
				<div class="form-list">
					<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
					<div class="form-item clearfix">
						<label>充值账户：</label>
						<div class="form-item-value"><?php echo $this->member['member_phone'];?></div>
					</div>
					<div class="form-item clearfix">
						<label>提现金额：</label>
						<div class="form-item-value">
							<input type="text" id="pdc_amount" name="pdc_amount" class="form-input w-100">&nbsp;元 <span class="t-tips">可提现金额￥<?php echo $this->member['available_predeposit'];?></span>
						</div>
					</div>
					<div class="form-item clearfix">
						<label>&nbsp;</label>
						<div class="form-item-value">
							<a class="btn btn-primary" href="javascript:checksubmit();">下一步</a>
						</div>
					</div>
					<div class="b-tips">
						<h3>温馨提示：</h3>
						<p>
							1. 提现成功后，可能存在延迟现象，一般1到5分钟内到账，如有问题，请咨询客服；<br>
							2. 提现金额输入值必须是不小于10且不大于50000的正整数；<br>
							3. 提现完成后，您可以进入相关账户查看到账状态。
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
<div class="modal-wrap w-400" id="alert-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
                <span class="tip-icon"></span>
                <h3 class="tip-title"></h3>
                <div class="tip-hint"></div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" onclick="Custombox.close();">关闭</a>
    </div>
</div>
    <script type="text/javascript" src="templates/js/profile/cash.js"></script>