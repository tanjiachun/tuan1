<?php include(template('common_header'));?>
	<div class="register-hd">
		<h2>
			<a href="index.php">
				<img src="templates/images/logo.png">
			</a>
		</h2>
		<div class="top-progress zhmm-box">
			<div class="zh-title">
				<ul>
					<li class="active"><span>1</span><br>注册</li>
					<li class="active"><u></u></li>
					<li class="active"><span>2</span><br>实名认证</li>
					<li><u></u></li>
					<li><span>3</span><br>完成注册</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="register-c">
		<h1>实名认证</h1>
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
		<table class="register-form">
			<tr>
				<th>店铺名称</th>
				<td>
					<input id="store_name" name="store_name" class="lr-input" type="text" placeholder="请输入店铺名称">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>产品类别</th>
				<td>
					<div class="select-class select-box">
						<a href="javascript:;" class="select-choice">-请选择-<i class="select-arrow"></i></a>
						<div class="select-list" style="display: none">
							<ul>
								<li field_value="" field_key="class_id">-请选择-</li>
								<?php foreach($class_list as $key => $value) { ?>
								<li field_value="<?php echo $value['class_id'];?>" field_key="class_id"><?php echo $value['class_name'];?></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<input type="hidden" id="class_id" name="class_id" value=""  />
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>所在地区</th>
				<td>
					<div class="first-province-box" prefix="store" style="display:inline-block">
						<div class="select-class">
							<a href="javascript:;" class="select-choice">-省份-<i class="select-arrow"></i></a>
							<div class="select-list" style="display: none">
								<ul>
									<li field_value="">-省份-</li>
									<?php foreach($province_list as $key => $value) { ?>
									<li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
                    <div class="first-city-box" style="display:inline-block"></div>
                    <div class="first-area-box" style="display:inline-block"></div>
					<input type="hidden" id="store_provinceid" name="store_provinceid" value=""  />
					<input type="hidden" id="store_cityid" name="store_cityid" value=""  />
					<input type="hidden" id="store_areaid" name="store_areaid" value=""  />
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>详细地址</th>
				<td>
					<input id="store_address" name="store_address" class="lr-input" type="text" placeholder="请输入详细地址">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
            <tr>
				<th>主营业务</th>
				<td>
					<textarea id="store_content" name="store_content" class="lr-textarea"></textarea>
                    <div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>身份证号码</th>
				<td>
					<input id="store_cardid" name="store_cardid" class="lr-input" type="text" placeholder="请输入身份证">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th>手持身份证照</th>
				<td>
                	<div class="picture-list">
                        <ul class="clearfix">
                        	<li id="show_image_0" class="cover-item" style="display:none;"></li>
                        	<li id="upload_image_0">
                                <div class="img-update">
                                    <span class="img-layer">+ 上传</span>
                                    <span class="img-file">
                                        <input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                    </span>
                                </div>
                            </li>
							<input type="hidden" id="store_cardid_image" name="store_cardid_image" class="image_0" value=""  />
							<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                        </ul>
                        <p class="help-desc">本人拿身份证正面拍上半身照，须确保身份证内容清晰，笔迷世证件与头部重叠</p>
                    </div>
				</td>
			</tr>
			<tr>
				<th>工作资质</th>
				<td>
                	<div class="picture-list">
                        <ul class="clearfix">
                        	<li id="show_image_1">
                                <div class="img-update">
                                    <span class="img-layer">+ 上传</span>
                                    <span class="img-file">
                                        <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <p class="help-desc">请上传您的相关工作资质</p>
                    </div>
                </td>
			</tr>
			<tr>
				<th></th>
				<td>
					<a class="btn btn-primary" href="javascript:checksubmit();">提交注册</a>
				</td>
			</tr>
		</table>
		<div class="register-right">
			<div class="qrcode-cont">
				<img src="<?php echo $this->setting['app_image'];?>">
				<p>下载手机客户端</p>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var file_name = 'store';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>   	
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
	<script type="text/javascript" src="templates/js/member/store.js"></script>
<?php include(template('common_footer'));?>