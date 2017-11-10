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
				<th><span class="red">*</span>您的姓名</th>
				<td>
					<input id="nurse_name" name="nurse_name" class="lr-input" type="text" placeholder="请输入真实姓名">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
            <tr>
				<th><span class="red">*</span>您的手机号</th>
				<td>
					<input id="member_phone" name="member_phone" class="lr-input" type="text" placeholder="请输入您的手机号" value="<?php echo $this->member['member_phone'];?>">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
            <tr>
				<th><span class="red">*</span>看护类别</th>
                <td>
                    <div class="selectBox" style="width:550px;min-height:100px;border:1px solid #ddd;border-radius: 10px;">
                        <span></span>
                    </div>
                    <input type="hidden" id="nurse_type" name="nurse_type" value="">
                    <div class="Validform-checktip Validform-wrong"></div>
                </td>
			</tr>
            <style>
                .keywordBox span,.selectBox span{display: inline-block;padding:0 5px;border:1px solid ;border-radius:5px;line-height: 30px;text-align: center;margin:10px 10px;}
                .keywordBox span:hover,.selectBox span:hover{border-color:#2a90e2;color: #ff6905;}
                .on{background:#2a90e2;color:#fff; }
            </style>
            <tr>
                <th><span class="red">*</span>可提供服务</th>
                <td>
                    <div class="keywordBox" style="width:550px;min-height:100px;border:1px solid #ddd;border-radius: 10px;">
                    </div>
                    <input type="hidden" id="service_type" name="service_type" value="">
                    <div class="Validform-checktip Validform-wrong"></div>
                </td>
            </tr>
			<tr>
				<th><span class="red">*</span>您的年龄</th>
				<td class="code-input">
					<input type="text" id="nurse_age" name="nurse_age" class="form-input w-100" value="">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th><span class="red">*</span>出生地址</th>
				<td>
					<div class="first-province-box" prefix="birth" style="display:inline-block">
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
					<input type="hidden" id="birth_provinceid" name="birth_provinceid" value="" />
					<input type="hidden" id="birth_cityid" name="birth_cityid" value="" />
					<input type="hidden" id="birth_areaid" name="birth_areaid" value="" />
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th><span class="red">*</span>现居地址</th>
				<td>
					<div class="second-province-box" prefix="nurse" style="display:inline-block">
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
                    <div class="second-city-box" style="display:inline-block"></div>
                    <div class="second-area-box" style="display:inline-block"></div>
					<input type="hidden" id="nurse_provinceid" name="nurse_provinceid" value=""  />
					<input type="hidden" id="nurse_cityid" name="nurse_cityid" value=""  />
					<input type="hidden" id="nurse_areaid" name="nurse_areaid" value=""  />
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
            <tr>
				<th><span class="red">*</span>详细地址</th>
				<td>
					<input id="nurse_address" name="nurse_address" class="lr-input" type="text" placeholder="点击输入详细地址">
					<div class="Validform-checktip Validform-wrong"></div>
                    <div id="allmap"></div>
				</td>
			</tr>
            <tr>
				<th><span class="red">*</span>工作年限</th>
				<td class="code-input">
					<input id="nurse_education" name="nurse_education" class="lr-input code-input" type="text"> 年
                    <div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th><span class="red">*</span><span id="price_field">期望月薪</span></th>
				<td class="code-input">
					<input type="text" id="nurse_price" name="nurse_price" class="form-input w-100" value=""> 元
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th><span class="red">*</span>您的照片</th>
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
							<input type="hidden" id="nurse_image" name="nurse_image" class="image_0" value=""  />
                            <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                        </ul>
                    </div>
				</td>
			</tr>
			<tr>
				<th><span class="red">*</span>身份证号码</th>
				<td>
					<input id="nurse_cardid" name="nurse_cardid" class="lr-input" type="text" placeholder="请输入身份证">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th><span class="red">*</span>手持身份证照</th>
				<td>
                	<div class="picture-list">
                        <ul class="clearfix">
							<li id="show_image_1" class="cover-item" style="display:none;"></li>
                        	<li id="upload_image_1">
                                <div class="img-update">
                                    <span class="img-layer">+ 上传</span>
                                    <span class="img-file">
                                        <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                    </span>
                                </div>
                            </li>
							<input type="hidden" id="nurse_cardid_image" name="nurse_cardid_image" class="image_1" value=""  />
                            <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                        </ul>
                        <p class="help-desc">本人拿身份证正面拍上半身照，须确保身份证内容清晰，避免证件与头部重叠</p>
                    </div>
				</td>
			</tr>
			<tr>
				<th>工作资质</th>
				<td>
                	<div class="picture-list">
                        <ul class="clearfix">
                        	<li id="show_image_2">
                                <div class="img-update">
                                    <span class="img-layer">+ 上传</span>
                                    <span class="img-file">
                                        <input type="file" id="file_2" name="file_2" field_id="2" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <p class="help-desc">请上传您的相关工作资质，必须和证件信息相符</p>
                    </div>
                </td>
			</tr>
			<tr>
				<th><span class="red">*</span>服务项目</th>
				<td>
                	<textarea class="form-textarea w-10-9" id="nurse_content" name="nurse_content" rows="10"></textarea>
                    <div class="Validform-checktip Validform-wrong"></div>
                </td>
			</tr>
			<tr>
				<th>推荐人手机号</th>
				<td>
					<input id="from_phone" name="from_phone" class="lr-input" type="text" placeholder="请输入推荐人手机号">
					<div class="Validform-checktip Validform-wrong"></div>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<a class="btn btn-primary" href="javascript:checkregister();">提交注册</a>
					<span class="return-success"></span>
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
		var file_name = 'nurse';
	</script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=XGoO2K5UyxmU57p1ZoW7SUcjmuy4n9ha"></script>
    <script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
    <script type="text/javascript" src="templates/js/member/getMap.js"></script>
    <script type="text/javascript" src="templates/js/member/nurse.js"></script>
    <script type="text/javascript" src="templates/js/member/type.js"></script>
    <script src="http://api.map.baidu.com/api?v=1.4" type="text/javascript"></script>
<?php include(template('common_footer'));?>