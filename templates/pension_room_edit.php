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
						<li><a class="active" href="index.php?act=pension_room">房间管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a href="index.php?act=pension_profile">机构信息</a></li>
						<li><a href="index.php?act=pension_profile&op=near">机构周边</a></li>
                        <li><a href="index.php?act=pension_profile&op=equipment">机构设施</a></li>
                        <li><a href="index.php?act=pension_profile&op=service">机构服务</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="user-info">
					<div class="form-list">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
						<input type="hidden" id="room_id" name="room_id" value="<?php echo $room['room_id'];?>" />
                        <div class="form-item clearfix">
							<label>房间名称：</label>
							<div class="form-item-value">
								<input type="text" id="room_name" name="room_name" value="<?php echo $room['room_name'];?>" class="form-input" placeholder="输入你的房间名称">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
						<div class="form-item clearfix">
							<label>房间封面：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php if(!empty($room['room_image'])) { ?>
										<li id="show_image_0" class="cover-item">
											<img src="<?php echo $room['room_image'];?>">
											<span class="close-modal single_close" field_id="0">×</span>
										</li>
										<?php } else { ?>
										<li id="show_image_0" class="cover-item" style="display:none;"></li>
										<?php } ?>
										<li id="upload_image_0">
											<div class="img-update">
												<span class="img-layer">+ 上传</span>
												<span class="img-file">
													<input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
												</span>
											</div>
										</li>
										<input type="hidden" id="room_image" name="room_image" class="image_0" value="<?php echo $room['room_image'];?>"  />
										<div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
									</ul>
								</div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>房间图片：</label>
							<div class="form-item-value">
								<div class="picture-list">
									<ul class="clearfix">
										<?php foreach($room['room_image_more'] as $key => $value) { ?>
										<li class="cover-item">
											<img src="<?php echo $value;?>">
											<span class="close-modal multi_close">×</span>
											<input type="hidden" name="image_1[]" class="image_1" value="<?php echo $value;?>">
										</li>
										<?php } ?>
										<li id="show_image_1">
											<div class="img-update">
												<span class="img-layer">+ 上传</span>
												<span class="img-file">
													<input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
												</span>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>房间价格：</label>
							<div class="form-item-value">
								<input type="text" id="room_price" name="room_price" value="<?php echo $room['room_price'];?>" class="form-input" placeholder="输入你的房间价格"> 元/月
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>床位数：</label>
							<div class="form-item-value">
								<input type="text" id="room_storage" name="room_storage" value="<?php echo $room['room_storage'];?>" class="form-input" placeholder="输入你的房间库存">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>房间排序：</label>
							<div class="form-item-value">
								<input type="text" id="room_sort" name="room_sort" value="<?php echo $room['room_sort'];?>" class="form-input" placeholder="输入你的床位数">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
                            <label>房间设施：</label>
                            <div class="form-item-value">
                            	<span class="check support<?php echo in_array('dn', $room['room_support']) ? ' active' : '';?>" support="dn"><i class="iconfont icon-type"></i>电脑</span>
                                <span class="check support<?php echo in_array('wf', $room['room_support']) ? ' active' : '';?>" support="wf"><i class="iconfont icon-type"></i>WIFI</span>
                                <span class="check support<?php echo in_array('ds', $room['room_support']) ? ' active' : '';?>" support="ds"><i class="iconfont icon-type"></i>电视</span>
                                <span class="check support<?php echo in_array('yx', $room['room_support']) ? ' active' : '';?>" support="yx"><i class="iconfont icon-type"></i>药箱</span>
                                <span class="check support<?php echo in_array('ly', $room['room_support']) ? ' active' : '';?>" support="ly"><i class="iconfont icon-type"></i>轮椅</span>
                                <span class="check support<?php echo in_array('cy', $room['room_support']) ? ' active' : '';?>" support="cy"><i class="iconfont icon-type"></i>餐饮</span>
                            </div>
                        </div>
                        <div class="form-item clearfix">
							<label>房间其他设施：</label>
							<div class="form-item-value">
								<input type="text" id="room_equipment" name="room_equipment" value="<?php echo $room['room_equipment'];?>" class="form-input w-600" placeholder="输入你的房间其他设施">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
							<label>免费客房服务：</label>
							<div class="form-item-value">
								<input type="text" id="room_service" name="room_service" value="<?php echo $room['room_service'];?>" class="form-input w-600" placeholder="输入你的免费客房服务">
                                <div class="Validform-checktip Validform-wrong"></div>
							</div>
						</div>
                        <div class="form-item clearfix">
                            <label>房间简介：</label>
                            <div class="form-item-value">
                                <textarea class="form-textarea w-10-9" id="room_desc" name="room_desc" rows="10"><?php echo $room['room_desc'];?></textarea>
                                <div class="Validform-checktip Validform-wrong"></div>
                            </div>
                        </div>
						<div class="form-item clearfix">
							<label>&nbsp;</label>
							<div class="form-item-value">
								<a href="javascript:editsubmit();" class="btn btn-primary">提交保存</a><span class="return-success"></span>
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
	<script type="text/javascript" src="templates/js/profile/pension_room.js"></script>
<?php include(template('common_footer'));?>