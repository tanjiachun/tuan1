<?php include(template('common_header'));?>
        <div class="conwp clearfix">
            <h1 class="top-logo">
                <a href="index.php"><img src="templates/images/logo.png"></a>
                <span class="city-opr" onclick="window.location.href='index.php?act=city'"><i class="iconfont icon-city"></i><em><?php echo $this->district['district_name'];?></em>[切换城市]</span>
            </h1>
            <div class="nav-box">
                <div class="nav-list">
                    <ul class="clearfix">
                        <li class="active"><a href="index.php">首页</a></li>
                        <li><a href="javascript:;">下载APP</a></li>
                        <li><a href="index.php?act=register&next_step=nurse" class="nav-btn">成为照护人员</a></li>
                        <li><a href="index.php?act=article&article_id=2">加盟合作</a></li>
                        <li><a href="index.php?act=article&article_id=1">关于我们</a></li>
                        <li><a href="index.php?act=login">家政人员登录</a></li>
                     </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
		<div class="conwp">
			<div class="layout-main clearfix">
				<div class="institution-hd clearfix">
					<div class="institution-hd-left">
						<h1><?php echo $pension['pension_name'];?></h1>
						<p>地址：<?php echo $pension['pension_areainfo'].$pension['pension_address'];?><a href="javascrupt:;" onclick="showMap('<?php echo $pension['pension_areainfo'].$pension['pension_address'];?>', '<?php echo $pension['pension_address'];?>');"><i class="iconfont icon-city"></i>查看地图</a></p>
					</div>
					<div class="institution-hd-right">
						<span><strong><?php echo $pension_score;?></strong>/5分</span>
						<span><strong><?php echo $pension['pension_commentnum'];?></strong>评论</span>
						<span>￥<strong><?php echo $pension['pension_price'];?></strong>元起</span>
					</div>
				</div>
				<div class="left-big">
					<div class="institution-preview clearfix">
						<?php foreach($pension['pension_image_more'] as $key => $value) { ?>
						<div class="preview-pic<?php echo $key+1;?>"><img src="<?php echo $value;?>"></div>
						<?php } ?>
					</div>
					<div class="tabs-box">
						<ul class="tabs-head" id="tabs-head">
							<li class="active"><a href="#support_mark">配套及户型</a></li>
							<li><a href="#desc_mark">机构概述</a></li>
							<li><a href="#near_mark">周边</a></li>
							<li><a href="#equipment_mark">机构设施</a></li>
							<li><a href="#service_mark">机构服务</a></li>
							<li><a href="#map_mark">交通地图</a></li>
							<li><a href="#comment_mark">评论</a></li>
						</ul>
						<div class="tabs-con">
							<!-- 配套及户型 -->
							<div class="supportBox" id="support_mark">
                            	<?php foreach($room_list as $key => $value) { ?>
								<div class="item-support-list">
									<ul class="clearfix">
                                    	<li class="item-pic"><img src="<?php echo $value['room_image'];?>"></li>
										<li class="item-title"><?php echo $value['room_name'];?><a href="javascript:void(0);">详情<i></i></a></li>
										<li class="item-device">
											<dl>
												<?php if(in_array('dn', $value['room_support'])) { ?>
												<dt><span><i class="iconfont icon-computer"></i></span><p>电脑</p></dt>
												<?php } ?>
												<?php if(in_array('wf', $value['room_support'])) { ?>
												<dt><span><i class="iconfont icon-wifi"></i></span><p>WIFI</p></dt>
												<?php } ?>
												<?php if(in_array('ds', $value['room_support'])) { ?>
												<dt><span><i class="iconfont icon-tv"></i></span><p>电视</p></dt>
												<?php } ?>
												<?php if(in_array('yx', $value['room_support'])) { ?>
												<dt><span><i class="iconfont icon-chest"></i></span><p>药箱</p></dt>
												<?php } ?>
												<?php if(in_array('ly', $value['room_support'])) { ?>
												<dt><span><i class="iconfont icon-chair"></i></span><p>轮椅</p></dt>
												<?php } ?>
												<?php if(in_array('cy', $value['room_support'])) { ?>
												<dt><span><i class="iconfont icon-meet"></i></span><p>餐饮</p></dt>
												<?php } ?>
											</dl>
										</li>
										<li class="item-price"><strong>￥<?php echo $value['room_price'];?></strong>元/月 <a class="btn btn-primary book-btn" href="index.php?act=bespoke&room_id=<?php echo $value['room_id'];?>">点击预定</a></li>
									</ul>
									<div class="support-desc">
										<div class="support-img clearfix">
											<dl>
                                            	<?php foreach($value['room_image_more'] as $k => $val) { ?>
												<dt><img src="<?php echo $val;?>"></dt>
												<?php } ?>
											</dl>
										</div>
										<div class="support-info">
											<p><label>客房介绍：</label><?php echo empty($value['room_desc']) ? '无' : $value['room_desc'];?></p>
											<p><label>房间其他设施：</label><?php echo empty($value['room_equipment']) ? '无' : $value['room_equipment'];?></p>
											<p><label>免费客房服务：</label><?php echo empty($value['room_service']) ? '无' : $value['room_service'];?></p>
										</div>
									</div>
								</div>
                                <?php } ?>
                                <?php if(!empty($room_more_list)) { ?>
								<div class="more-item-support">
                                	<?php foreach($room_more_list as $key => $value) { ?>
                                        <div class="item-support-list">
                                            <ul class="clearfix">
                                                <li class="item-pic"><img src="<?php echo $value['room_image'];?>"></li>
                                                <li class="item-title"><?php echo $value['room_name'];?><a href="javascript:void(0);">详情<i></i></a></li>
                                                <li class="item-device">
                                                    <dl>
                                                        <?php if(in_array('dn', $value['room_support'])) { ?>
                                                        <dt><span><i class="iconfont icon-computer"></i></span><p>电脑</p></dt>
                                                        <?php } ?>
                                                        <?php if(in_array('wf', $value['room_support'])) { ?>
                                                        <dt><span><i class="iconfont icon-wifi"></i></span><p>WIFI</p></dt>
                                                        <?php } ?>
                                                        <?php if(in_array('ds', $value['room_support'])) { ?>
                                                        <dt><span><i class="iconfont icon-tv"></i></span><p>电视</p></dt>
                                                        <?php } ?>
                                                        <?php if(in_array('yx', $value['room_support'])) { ?>
                                                        <dt><span><i class="iconfont icon-chest"></i></span><p>药箱</p></dt>
                                                        <?php } ?>
                                                        <?php if(in_array('ly', $value['room_support'])) { ?>
                                                        <dt><span><i class="iconfont icon-chair"></i></span><p>轮椅</p></dt>
                                                        <?php } ?>
                                                        <?php if(in_array('cy', $value['room_support'])) { ?>
                                                        <dt><span><i class="iconfont icon-meet"></i></span><p>餐饮</p></dt>
                                                        <?php } ?>
                                                    </dl>
                                                </li>
                                                <li class="item-price"><strong>￥<?php echo $value['room_price'];?></strong>元/月 <a class="btn btn-primary book-btn" href="index.php?act=bespoke&room_id=<?php echo $value['room_id'];?>">点击预定</a></li>
                                            </ul>
                                            <div class="support-desc">
                                                <div class="support-img clearfix">
                                                    <dl>
                                                        <?php foreach($value['room_image_more'] as $k => $val) { ?>
                                                        <dt><img src="<?php echo $val;?>"></dt>
                                                        <?php } ?>
                                                    </dl>
                                                </div>
                                                <div class="support-info">
                                                    <p><label>客房介绍：</label><?php echo empty($value['room_desc']) ? '无' : $value['room_desc'];?></p>
                                                    <p><label>房间其他设施：</label><?php echo empty($value['room_equipment']) ? '无' : $value['room_equipment'];?></p>
                                                    <p><label>免费客房服务：</label><?php echo empty($value['room_service']) ? '无' : $value['room_service'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
								</div>
								<div class="more-item-btn"><span class="more-btn">展示全部房型</span></div>
								<?php } ?>
							</div>
							<!-- 机构概述 -->
							<div class="txtimg-module" id="desc_mark">
								<div class="txtimgtitle">
									<h1>机构概述</h1>
								</div>
								<div class="txtimgcon">
									<?php echo $pension['pension_summary'];?>
								</div>
							</div>
							<!-- 周边 -->
							<div class="txtimg-module" id="near_mark">
								<div class="txtimgtitle">
									<h1>周边</h1>
								</div>
								<div class="txtimgcon">
									<div class="txtimg-layout1 clearfix">
										<div class="layout-left">
											<?php echo $pension_field['near_content'];?>
										</div>
										<div class="layout-right">
											<ul class="layout-img1 clearfix">
                                            	<?php foreach($pension_field['near_image'] as $key => $value) { ?>
												<li><img src="<?php echo $value;?>"></li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- 机构设施 -->
							<div class="txtimg-module" id="equipment_mark">
								<div class="txtimgtitle">
									<h1>机构设施</h1>
								</div>
								<div class="txtimgcon">
									<div class="txtimg-layout2 clearfix">
										<div class="layout-left">
											<ul class="layout-img2 clearfix">
												<?php foreach($pension_field['equipment_image'] as $key => $value) { ?>
												<li><img src="<?php echo $value;?>"></li>
												<?php } ?>
											</ul>
										</div>
										<div class="layout-right">
											<?php echo $pension_field['equipment_content'];?>
										</div>
									</div>
								</div>
							</div>
							<!-- 机构服务 -->
							<div class="txtimg-module" id="service_mark">
								<div class="txtimgtitle">
									<h1>机构服务</h1>
								</div>
								<div class="txtimgcon">
									<div class="txtimg-layout1 clearfix">
										<div class="layout-left">
											<?php echo $pension_field['service_content'];?>
										</div>
										<div class="layout-right">
											<ul class="layout-img1 clearfix">
												<?php foreach($pension_field['service_image'] as $key => $value) { ?>
												<li><img src="<?php echo $value;?>"></li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- 交通地图 -->
							<div class="txtimg-module" id="map_mark">
								<div class="txtimgtitle">
									<h1>交通地图</h1>
								</div>
								<div class="txtimgcon">
									<div class="map-box" id="leftmap" style="width:910px; height:350px;"></div>
                                    <div class="map_bus_btn" onclick="showMap('<?php echo $pension['pension_areainfo'].$pension['pension_address'];?>', '<?php echo $pension['pension_address'];?>');">线路规划</div>
								</div>
                            </div>
							<!-- 用户点评 -->
							<div class="txtimg-module" id="comment_mark">
								<div class="txtimgtitle">
									<h1>用户点评</h1>
								</div>
								<div class="txtimgcon">
									<div class="commit-box">
										<div class="commit-filter clearfix">
											<div class="commit-all-score">
												<strong><?php echo $pension['pension_commentnum'];?></strong><span>条</span><em>评论</em>
											</div>
											<div class="commit-filter-item comment-radio-box">
                                                <label class="radio active" field_value='all'>
                                                    <i class="iconfont icon-type"></i>
                                                    全部
                                                </label>
                                                <label class="radio" field_value="good">
                                                    <i class="iconfont icon-type"></i>
                                                    好评
                                                </label>
                                                <label class="radio" field_value="middle">
                                                    <i class="iconfont icon-type"></i>
                                                    中评
                                                </label>
                                                <label class="radio" field_value="bad">
                                                    <i class="iconfont icon-type"></i>
                                                    差评
                                                </label>
                                            </div>
										</div>
                                        <div class="comment-box">
                                			<?php foreach($comment_list as $key => $value) { ?>
											<div class="commit-item clearfix">
												<div class="commit-score">
                                                    <span><strong><?php echo $value['comment_score'];?></strong>分</span>
													<div class="score-item">
														<?php for($i=0; $i<5; $i++) { ?>
														<?php if($i < $value['comment_score']) { ?>
														<i class="iconfont icon-solidstar cur"></i>
														<?php } else { ?>
														<i class="iconfont icon-solidstar"></i>
														<?php } ?>
														<?php } ?>
													</div>
                                                </div>
                                                <div class="commit-info">
													<div class="commit-desc"><?php echo $value['comment_content'];?></div>
													<?php if(!empty($value['comment_image'])) { ?>
													<div class="commit-img">
														<ul>
															<?php foreach($value['comment_image'] as $subkey => $subvalue) { ?>
															<li><img src="<?php echo $subvalue;?>"></li>
															<?php } ?>
														</ul>
													</div>
													<?php } ?>
												</div>
                                                <div class="commit-user">
													<?php if(empty($member_list[$value['member_id']]['member_avatar'])) { ?>
													<img src="templates/images/peopleicon_01.gif">
													<?php } else { ?>
													<img src="<?php echo $member_list[$value['member_id']]['member_avatar'];?>">
													<?php } ?>
													<p><?php echo $member_list[$value['member_id']]['member_phone'];?></p>
                                                    <p><?php echo date('Y-m-d H:i', $value['comment_time']);?></p>
												</div>
											</div>
                                            <?php } ?>
                                        </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="right-small">
					<div class="right-map">
						<div id="rightmap" style="width:250px; height:240px;"></div>
						<a href="javascript:;" onclick="showMap('<?php echo $pension['pension_areainfo'].$pension['pension_address'];?>', '<?php echo $pension['pension_address'];?>');">查看地图</a>
					</div>
					<div class="list-module">
						<h1 class="module-title">
							养老机构推荐
						</h1>
						<div class="module-con">
							<ul>
                            	<?php foreach($recommend_pension as $key => $value) { ?>
                                <li>
                                    <a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>">
                                        <img src="<?php echo $value['pension_image'];?>">
                                        <h1><?php echo $value['pension_name'];?></h1>
                                        <p><strong>￥<?php echo $value['pension_price'];?></strong></p>
                                    </a>
                                </li>
                                <?php } ?>
							</ul>
						</div>
					</div>
					<div class="list-module">
						<h1 class="module-title">
							老年用品推荐
							<a href="index.php?act=index&op=goods">更多>></a>
						</h1>
						<div class="module-con">
							<ul>
								<?php foreach($recommend_goods as $key => $value) { ?>
								<li>
									<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>">
										<img src="<?php echo $value['goods_image'];?>">
										<h1><?php echo $value['goods_name'];?></h1>
										<p>
											<strong>￥<?php echo $value['goods_price'];?></strong>
											<?php if($value['goods_original_price'] > 0) { ?>
											<del>￥<?php echo $value['goods_original_price'];?></del>
											<?php } ?>
										</p>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<div class="list-module">
						<h1 class="module-title">
							我浏览过的
						</h1>
						<div class="module-con">
							<ul>
								<?php foreach($browse_pension_list as $key => $value) { ?>
								<li>
									<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>">
										<img src="<?php echo $value['pension_image'];?>">
										<h1><?php echo $value['pension_name'];?></h1>
										<p><strong>￥<?php echo $value['pension_price'];?></strong></p>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div id="blank"></div>
	<div id="map_box">
		<div class="map">
			<a href="javascript:;" id="delete">×</a>
			<div id="allmap">正在加载地图，请稍后...</div>
			<div id="map_right">
				<div class="right_con">
					<div class="lx_box">
						<div class="lux">
							<h6>线路查询</h6>
							<div class="btn">
								<input type="button" id="map_bus" class="active" value="公交"/>
								<input type="button" id="map_car" value="驾车"/>
							</div>
							<div class="lx">
								<div class="change"><a href="javascript:;" id="map_change">换</a></div>
								<input name="map_start" id="map_start" value="">
								<input name="map_end" id="map_end" value="">
								<input type="button" value="查询" id="map_search" class="btn"/>
							</div>
						</div>
						<div class="result_box">
							<div id="r-result"></div>
						</div>
					</div>
				</div>
				<em id="map_btn"></em>
			</div>
		</div>
	</div>
    <link rel="stylesheet" type="text/css" href="templates/css/map.css">
	<script src="http://api.map.baidu.com/api?key=a258befb5804cb80bed5338c74dd1fd1&v=1.1&services=true" type="text/javascript"></script>
	<script type="text/javascript" src="templates/js/home/map.js"></script>
	<script type="text/javascript">
		var pension_id = '<?php echo $pension['pension_id'];?>';
		var pension_address = '<?php echo $pension['pension_areainfo'].$pension['pension_address'];?>';
	</script>
	<script type="text/javascript" src="templates/js/home/pension.js"></script>	
<?php include(template('common_footer'));?>