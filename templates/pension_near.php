<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>养老机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=pension_bespoke">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=pension_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">机构配套</h3>
					<ul>
						<li><a href="index.php?act=pension_room">房间管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a href="index.php?act=pension_profile">机构信息</a></li>
						<li><a class="active" href="index.php?act=pension_profile&op=near">机构周边</a></li>
                        <li><a href="index.php?act=pension_profile&op=equipment">机构设施</a></li>
                        <li><a href="index.php?act=pension_profile&op=service">机构服务</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="user-info">
                	<div class="edit-box">
						<div class="edit-body">
                            <div class="edit-body-title">机构周边</div>
                            <div class="edit-body-con">
                                <div class="form-list">
                                    <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                                    <div class="form-item clearfix">
                                        <label>相关图片：</label>
                                        <div class="form-item-value">
                                            <div class="picture-list">
                                                <ul class="clearfix">
                                                    <?php foreach($pension_field['near_image'] as $key => $value) { ?>
                                                    <li class="cover-item">
                                                        <img src="<?php echo $value;?>">
                                                        <span class="close-modal multi_close">×</span>
                                                        <input type="hidden" name="image_0[]" class="image_0" value="<?php echo $value;?>">
                                                    </li>
                                                    <?php } ?>
                                                    <li id="show_image_0">
                                                        <div class="img-update">
                                                            <span class="img-layer">+ 上传</span>
                                                            <span class="img-file">
                                                                <input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
                                                            </span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-item clearfix">
                                        <label>周边描述：</label>
                                        <div class="form-item-value">
                                            <textarea class="form-textarea w-10-9" id="near_content" name="near_content" rows="10"><?php echo $pension_field['near_content'];?></textarea>
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
			</div>
		</div>
	</div>
    <script type="text/javascript">
		var file_name = 'agent';
	</script>
    <script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>	
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/pension_near.js"></script>	
<?php include(template('common_footer'));?>