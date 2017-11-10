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
			<div class="selector">
				<div class="selectorline search-box"<?php echo empty($type_array[$pension_type]) ? ' style="display:none;"' : '';?>>
					<label>你的选择：</label>
					<div class="selector-value clearfix">
						<ul>
							<?php if(!empty($type_array[$pension_type])) { ?>
							<li id="pension_type"><span class="selected"><?php echo $type_array[$pension_type];?><i>x</i></span></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="selectorline selector-open">
					<label>所在区域：</label>
					<div class="selector-value clearfix">
						<ul class="district-box">
							<?php foreach($district_list as $key => $value) { ?>
							<li><a href="javascript:;" onclick="selectpension(this, 'district_id', '<?php echo $value['district_id'];?>');"><?php echo $value['district_name'];?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="selectorline">
					<label>机构类型：</label>
					<div class="selector-value clearfix">
						<ul>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_type', '1');">养老产业园</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_type', '2');">老年公寓</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_type', '3');">护理院</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_type', '4');">托老所</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_type', '5');">养老院</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_type', '6');">敬老院</a></li>
						</ul>
					</div>
				</div>
				<div class="selectorline">
					<label>机构性质：</label>
					<div class="selector-value clearfix">
						<ul>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_nature', '1');">民办</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_nature', '2');">公办</a></li>
						</ul>
					</div>
				</div>
				<div class="selectorline">
					<label>床位数量：</label>
					<div class="selector-value clearfix">
						<ul>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_bed', '0-50');">50以下</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_bed', '50-100');">50-100</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_bed', '100-200');">100-200</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_bed', '200-300');">200-300</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_bed', '300-500');">300-500</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_bed', '500');">500以上</a></li>
						</ul>
					</div>
				</div>
				<div class="selectorline">
					<label>收费标准：</label>
					<div class="selector-value clearfix">
						<ul>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_price', '0-500');">500以下</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_price', '500-1000');">500-1000</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_price', '1000-2000');">1000-2000</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_price', '2000-3000');">2000-3000</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_price', '3000-5000');">3000-5000</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_price', '5000');">5000以上</a></li>
						</ul>
					</div>
				</div>
				<div class="selectorline">
					<label>收住对象：</label>
					<div class="selector-value clearfix">
						<ul>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_person', '1');">自理</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_person', '2');">半自理</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_person', '3');">全自理</a></li>
							<li><a href="javascript:;" onclick="selectpension(this, 'pension_person', '4');">特护</a></li>
						</ul>
					</div>
				</div>
            </div>
			<div class="layout-main clearfix">
				<div class="left-big">
					<div class="filter-line">
						<div class="f-sort">
							<a href="javascript:;" class="curr" onclick="selectpension(this, 'sort_field', 'time');">默认<i></i></a>
							<a href="javascript:;" class="" onclick="selectpension(this, 'sort_field', 'view');">人气<i></i></a>
							<a href="javascript:;" class="" onclick="selectpension(this, 'sort_field', 'price');">价格<i></i></a>
						</div>
						<div class="f-pager">
							<span class="fp-text"><b><?php echo $page;?></b><em>/</em><i><?php echo $maxpage;?></i></span>
							<?php if($page == 1) { ?>
							<a class="fp-prev disabled" href="javascript:;">&lt;</a>
							<?php } else { ?>
							<a class="fp-prev" href="javascript:;" onclick="selectpension(this, 'page', '<?php echo $page-1;?>');">&lt;</a>
							<?php } ?>
							<?php if($page == $maxpage) { ?>
							<a class="fp-next disabled" href="javascript:;">&gt;</a>
							<?php } else { ?>
							<a class="fp-next" href="javascript:;" onclick="selectpension(this, 'page', '<?php echo $page+1;?>');">&gt;</a>
							<?php } ?>
						</div>
                		<div class="f-result-sum count-box">共<span id="J_resCount" class="num"><?php echo $count;?></span>家养老机构</div>
            		</div>
					<div class="agency-box pension-box">
						<?php if(empty($pension_list)) { ?>
						<div class="no-shop">
							<dl>
								<dt></dt>
								<dd>
									<p>抱歉，没有找到符合条件的养老机构</p>
									<p>您可以适当减少筛选条件</p>
								</dd>
							</dl>
						</div>
						<?php } else { ?>
						<?php foreach($pension_list as $key => $value) { ?>
                		<div class="agency-item">
							<div class="agency-item-top clearfix">
								<div class="item-top-left">
									<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>">
										<img src="<?php echo $value['pension_image'];?>" width="350px" height="200px">
									</a>
								</div>
								<div class="item-top-right">
									<h1><a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>"><?php echo $value['pension_name'];?></a></h1>
									<div class="item-desc"><?php echo $value['pension_summary'];?></div>
									<div class="item-info">
										区域：<u><?php echo $value['pension_areainfo'];?></u><i><u class="zdmj">占地面积：</u><?php echo $value['pension_cover'];?>万平方米</i>
									</div>
									<div class="item-info">
										地址：<?php echo $value['pension_address'];?><i><u class="zdmj">床位：</u><?php echo $value['pension_bed'];?></i>
									</div>
									<div class="map-commit">
										<a href="javascrupt:;" onclick="showMap('<?php echo $value['pension_areainfo'].$value['pension_address'];?>', '<?php echo $value['pension_address'];?>');"><i class="iconfont icon-city"></i>查看地图</a>
										<a><i class="iconfont icon-talk"></i><?php echo $value['pension_commentnum'];?>评价</a>
										<p><a><i class="iconfont icon-tel"></i><?php echo $value['pension_phone'];?></a></p>
									</div>
								</div>
							</div>
							<div class="agency-item-foot">
								<?php foreach($room_list[$value['pension_id']] as $subkey => $subvalue) { ?>
								<div class="item-support-list">
									<ul class="clearfix">
										<li class="item-pic"><img src="<?php echo $subvalue['room_image'];?>"></li>
										<li class="item-title"><a href="javascript:;"><?php echo $subvalue['room_name'];?></a></li>
										<li class="item-device">
											<dl>
												<?php if(in_array('dn', $subvalue['room_support'])) { ?>
												<dt><span><i class="iconfont icon-computer"></i></span><p>电脑</p></dt>
												<?php } ?>
												<?php if(in_array('wf', $subvalue['room_support'])) { ?>
												<dt><span><i class="iconfont icon-wifi"></i></span><p>WIFI</p></dt>
												<?php } ?>
												<?php if(in_array('ds', $subvalue['room_support'])) { ?>
												<dt><span><i class="iconfont icon-tv"></i></span><p>电视</p></dt>
												<?php } ?>
												<?php if(in_array('yx', $subvalue['room_support'])) { ?>
												<dt><span><i class="iconfont icon-chest"></i></span><p>药箱</p></dt>
												<?php } ?>
												<?php if(in_array('ly', $subvalue['room_support'])) { ?>
												<dt><span><i class="iconfont icon-chair"></i></span><p>轮椅</p></dt>
												<?php } ?>
												<?php if(in_array('cy', $subvalue['room_support'])) { ?>
												<dt><span><i class="iconfont icon-meet"></i></span><p>餐饮</p></dt>
												<?php } ?>
											</dl>
										</li>
										<li class="item-price"><strong>￥<?php echo $subvalue['room_price'];?></strong>元/月</li>
									</ul>
									<div class="support-desc">
										<div class="support-img clearfix">
											<dl>
												<?php foreach($subvalue['room_image_more'] as $k => $val) { ?>
												<dt><img src="<?php echo $val;?>"></dt>
												<?php } ?>
											</dl>
										</div>
										<div class="support-info">
											<p><label>客房介绍：</label><?php echo empty($subvalue['room_desc']) ? '无' : $subvalue['room_desc'];?></p>
											<p><label>房间其他设施：</label><?php echo empty($subvalue['room_equipment']) ? '无' : $subvalue['room_equipment'];?></p>
											<p><label>免费客房服务：</label><?php echo empty($subvalue['room_service']) ? '无' : $subvalue['room_service'];?></p>
										</div>
									</div>
								</div>
								<?php } ?>
								<div class="item-btn">
									<a class="btn btn-primary" href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>">点击预定</a>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php } ?>
						<?php echo $multi;?>
                	</div>					
				</div>
				<div class="right-small">
					<div class="agency-top-list">
						<ul class="clearfix">
							<?php foreach($recommend_pension as $key => $value) { ?>
							<li>
								<a href="index.php?act=pension&pension_id=<?php echo $value['pension_id'];?>">
									<img src="<?php echo $value['pension_image'];?>">
									<h3 class="txthide"><?php echo $value['pension_name'];?></h3>
									<p>￥<?php echo $value['pension_price'];?></p>
								</a>
							</li>
							<?php } ?>
						</ul>
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
		var temp_districtid = '<?php echo $district_id;?>';
		var pension_type = '<?php echo $pension_type;?>';
		var page = '<?php echo $page;?>';
	</script>
    <script type="text/javascript" src="templates/js/home/index_pension.js"></script>
<?php include(template('common_footer'));?>