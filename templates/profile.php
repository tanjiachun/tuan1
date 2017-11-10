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
						<li><a class="active" href="index.php?act=profile">个人信息</a></li>
						<li><a href="index.php?act=password">密码修改</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>个人信息</strong>
				</div>
				<div class="user-info">
					<div class="form-list">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
						<div class="form-item clearfix">
							<label>手机号：</label>
							<div class="form-item-value"><?php echo $this->member['member_phone'];?></div>
						</div>
						<?php if(!empty($card)) { ?>
						<div class="form-item clearfix">
							<label>等级：</label>
							<div class="form-item-value">
								<span class="VIP">
									<a class="imgVip"><image src="<?php echo $card['card_icon'];?>"></image></a>
                                	<a class="txtExplain"><?php echo $card['card_name'];?></a>
								</span>
							</div>
						</div>
						<?php } ?>
						<div class="form-item clearfix">
							<label>昵称：</label>
							<div class="form-item-value">
								<input type="text" id="member_nickname" name="member_nickname" value="<?php echo $this->member['member_nickname'];?>" class="form-input" placeholder="输入你的昵称">
							</div>
						</div>
						<div class="form-item clearfix">
							<label>头像：</label>
							<div class="form-item-value">
								<div class="imgHeaderBox">
									<a href="javascript:;" class="headerImg" id="show_image_0">
										<?php if(empty($this->member['member_avatar'])) { ?>
										<img src="templates/images/peopleicon_01.gif">
										<?php } else { ?>
										<img src="<?php echo $this->member['member_avatar'];?>">
										<?php } ?>
									</a>
									<div class="updateInfo">
									   <div class="opacityBox"></div>
									   <a href="javascript:;" class="realBox">修改头像</a>
									   <span class="img-file">
											<input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="other">
										</span>
									</div>
								</div>
								<input type="hidden" id="member_avatar" name="member_avatar" class="image_0" value="<?php echo $this->member['member_avatar'];?>" />
							</div>
						</div>
						<div class="form-item clearfix">
							<label>性别：</label>
							<div class="form-item-value radio-box">
								<span class="radio<?php echo $this->member['member_sex'] == 1 ? ' active' : '';?>" field_value="1" field_key="member_sex"><i class="iconfont icon-type"></i>男</span>
								<span class="radio<?php echo $this->member['member_sex'] == 2 ? ' active' : '';?>" field_value="2" field_key="member_sex"><i class="iconfont icon-type"></i>女</span>
								<span class="radio<?php echo $this->member['member_sex'] == 0 ? ' active' : '';?>" field_value="0" field_key="member_sex"><i class="iconfont icon-type"></i>保密</span>
								<input type="hidden" id="member_sex" name="member_sex" value="<?php echo $this->member['member_sex'];?>" />
							</div>
						</div>
						<div class="form-item clearfix">
							<label>生日：</label>
							<select id="member_birthyear" name="member_birthyear" onchange="showbirthday()">
								<option value="">请选择</option>
								<?php for($i=0; $i<100; $i++) { ?>
								<option value="<?php echo $current_year-$i;?>"<?php echo $this->member['member_birthyear'] == $current_year-$i ? ' selected="selected"' : '';?>><?php echo $current_year-$i;?></option>
								<?php } ?>
							</select>
							<em class="spacing">年</em>
							<select id="member_birthmonth" name="member_birthmonth" onchange="showbirthday()">
								<option value="">请选择</option>
								<?php for($i=1; $i<=12; $i++) { ?>
								<option value="<?php echo $i;?>"<?php echo $this->member['member_birthmonth'] == $i ? ' selected="selected"' : '';?>><?php echo $i;?></option>
								<?php } ?>
							</select>
							<em class="spacing">月</em>
							<select id="member_birthday" name="member_birthday">
								<option value="">请选择</option>
								<?php for($i=1; $i<=$days; $i++) { ?>
								<option value="<?php echo $i;?>"<?php echo $this->member['member_birthday'] == $i ? ' selected="selected"' : '';?>><?php echo $i;?></option>
								<?php } ?>
							</select>
							<em class="spacing">日</em>
						</div>
						<div class="form-item clearfix">
							<label>姓名：</label>
							<div class="form-item-value">
								<input type="text" id="member_truename" name="member_truename" value="<?php echo $this->member['member_truename'];?>" class="form-input" placeholder="输入你的真实姓名">
							</div>
						</div>
						<div class="form-item clearfix">
							<label id="tooltips">身份证号：</label>
							<div class="form-item-value">
								<input type="text" id="member_cardid" name="member_cardid" value="<?php echo $this->member['member_cardid'];?>" class="form-input w-400" placeholder="输入你的身份证号码">
								<div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>&nbsp;</label>
							<div class="form-item-value">
								<a href="javascript:checksubmit();" class="btn btn-primary">提交保存</a><span class="return-success"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var file_name = 'plat';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>	
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/profile.js"></script>
<?php include(template('common_footer'));?>