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
					 </ul>
				</div>
			</div>
		</div>
	</div>
	<div class="full-banner">
		<!-- Swiper -->
		<div class="swiper-container" id="swiper-banner">
			<div class="swiper-wrapper">
				<?php foreach($this->setting['banner_image'] as $key => $value) { ?>
				<?php if(!empty($value)) { ?>
				<div class="swiper-slide" style="background-image:url(<?php echo $value;?>);">
					<a href="<?php echo $this->setting['banner_url'][$key];?>"></a>
				</div>
				<?php } ?>
				<?php } ?>
			</div>
			<div class="swiper-pagination swiper-banner"></div>
		</div>
	</div>
	<div class="conwp">
		<div class="pension">
			<div class="pension-title">
				<h2>
					<span class="floor">1F</span>
					<a href="index.php?act=index&op=nurse&nurse_type=1">住家保姆</a>
				</h2>
				<div class="pension-subtitle">24小时安全陪护，呵护照您的健康</div>
				<a href="index.php?act=index&op=nurse&nurse_type=1" class="pension-more">更多>></a>
			</div>
			<div class="pension-con">
				<div class="pension-nurse clearfix">
					<a href="index.php?act=nurse&nurse_id=<?php echo $first_nurse['nurse_id'];?>" class="nurse-first">
						<img src="<?php echo $first_nurse['nurse_image'];?>">
						<div class="nurse-first-mask">
							照料护理老人，就找养老到家<p>立即拨打电话，抢免费评估资格</p>
							<p><span class="start-b">立即预约</span></p>
						</div>
					</a>
					<?php foreach($index_inside_list as $key => $value) { ?>
					<div class="nurse-item">
						<div class="nurse-item-pic">
							<img src="<?php echo $value['nurse_image'];?>">
							<div class="nurse-item-on">
								<p><?php echo $value['nurse_age'];?>岁 | <?php echo $value['birth_cityname'];?>人</p>
								<p>服务年限：<?php echo timeformat($value['nurse_education']);?></p>
								<p>客户经验：<?php echo $value['nurse_salenum'];?></p>
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" class="start-a">预约</a>
							</div>
						</div>
						<div class="nurse-item-info">
							<h3 class="txthide">
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['nurse_name'];?></a>
								<strong><i>￥</i><?php echo $value['nurse_price'];?><i>元/月</i></strong>
							</h3>
							<div class="nurse-item-desc clearfix">
								<p><span class="level-box"><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""><?php echo $grade_list[$value['grade_id']]['grade_name'];?></span></p>
								<div class="nurse-tag">
									<?php foreach($value['nurse_tags'] as $subkey => $subvalue) { ?>
									<i class="tag"><?php echo $subvalue;?></i>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="pension">
			<div class="pension-title">
				<h2>
					<span class="floor">2F</span>
					<a href="index.php?act=index&op=nurse&nurse_type=2">不住家保姆</a>
				</h2>
				<div class="pension-subtitle">轻松快乐的享受您的老年生活</div>
				<a href="index.php?act=index&op=nurse&nurse_type=2" class="pension-more">更多>></a>
			</div>
			<div class="pension-con">
				<div class="pension-nurse clearfix">
					<?php foreach($index_outside_list as $key => $value) { ?>
					<div class="nurse-item">
						<div class="nurse-item-pic">
							<img src="<?php echo $value['nurse_image'];?>">
							<div class="nurse-item-on">
								<p><?php echo $value['nurse_age'];?>岁 | <?php echo $value['birth_cityname'];?>人</p>
								<p>服务年限：<?php echo timeformat($value['nurse_education']);?></p>
								<p>客户经验：<?php echo $value['nurse_salenum'];?></p>
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" class="start-a">预约</a>
							</div>
						</div>
						<div class="nurse-item-info">
							<h3 class="txthide">
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['nurse_name'];?></a>
								<strong><i>￥</i><?php echo $value['nurse_price'];?><i>元/月</i></strong>
							</h3>
							<div class="nurse-item-desc clearfix">
								<p><span class="level-box"><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""><?php echo $grade_list[$value['grade_id']]['grade_name'];?></p>
								<div class="nurse-tag">
									<?php foreach($value['nurse_tags'] as $subkey => $subvalue) { ?>
									<i class="tag"><?php echo $subvalue;?></i>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="pension">
			<div class="pension-title">
				<h2>
					<span class="floor">3F</span>
					<a href="index.php?act=index&op=nurse&nurse_type=3">病后照护</a>
				</h2>
				<div class="pension-subtitle">专业的职业素养,呵护您的健康</div>
				<a href="index.php?act=index&op=nurse&nurse_type=3" class="pension-more">更多>></a>
			</div>
			<div class="pension-con">
				<div class="pension-nurse clearfix">
					<?php foreach($index_illness_list as $key => $value) { ?>
					<div class="nurse-item">
						<div class="nurse-item-pic">
							<img src="<?php echo $value['nurse_image'];?>">
							<div class="nurse-item-on">
								<p><?php echo $value['nurse_age'];?>岁 | <?php echo $value['birth_cityname'];?>人</p>
								<p>服务年限：<?php echo timeformat($value['nurse_education']);?></p>
								<p>客户经验：<?php echo $value['nurse_salenum'];?></p>
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" class="start-a">预约</a>
							</div>
						</div>
						<div class="nurse-item-info">
							<h3 class="txthide">
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['nurse_name'];?></a>
								<strong><i>￥</i><?php echo $value['nurse_price'];?><i>元/月</i></strong>
							</h3>
							<div class="nurse-item-desc clearfix">
								<p><span class="level-box"><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""><?php echo $grade_list[$value['grade_id']]['grade_name'];?></p>
								<div class="nurse-tag">
									<?php foreach($value['nurse_tags'] as $subkey => $subvalue) { ?>
									<i class="tag"><?php echo $subvalue;?></i>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="pension">
			<div class="pension-title">
				<h2>
					<span class="floor">4F</span>
					<a href="index.php?act=index&op=nurse&nurse_type=4">钟点工</a>
				</h2>
				<div class="pension-subtitle">临时服务，让您有个惬意的一天</div>
				<a href="index.php?act=index&op=nurse&nurse_type=4" class="pension-more">更多>></a>
			</div>
			<div class="pension-con">
				<div class="pension-nurse clearfix">
					<?php foreach($index_hour_list as $key => $value) { ?>
					<div class="nurse-item">
						<div class="nurse-item-pic">
							<img src="<?php echo $value['nurse_image'];?>">
							<div class="nurse-item-on">
								<p><?php echo $value['nurse_age'];?>岁 | <?php echo $value['birth_cityname'];?>人</p>
								<p>服务年限：<?php echo timeformat($value['nurse_education']);?></p>
								<p>客户经验：<?php echo $value['nurse_salenum'];?></p>
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" class="start-a">预约</a>
							</div>
						</div>
						<div class="nurse-item-info">
							<h3 class="txthide">
								<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['nurse_name'];?></a>
								<strong><i>￥</i><?php echo $value['nurse_price'];?><i>元/时</i></strong>
							</h3>
							<div class="nurse-item-desc clearfix">
								<p><span class="level-box"><img src="<?php echo $grade_list[$value['grade_id']]['grade_icon'];?>" alt=""><?php echo $grade_list[$value['grade_id']]['grade_name'];?></p>
								<div class="nurse-tag">
									<?php foreach($value['nurse_tags'] as $subkey => $subvalue) { ?>
									<i class="tag"><?php echo $subvalue;?></i>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="security">
			<h1>养老到家服务保障</h1>
			<p>真实认证家政人员信息 提供专业技能培训 全程跟踪质量 交易未成功100%退款</p>
			<span><i class="security-01"></i><p>服务多</p></span>
			<span><i class="security-02"></i><p>上门快</p></span>
			<span><i class="security-03"></i><p>有保障</p></span>
		</div>
	</div>
    <div class="toolsidebar" id="service">
        <ul>
            <li>
                <span class="tool-icon"><i class="iconfont icon-qq"></i></span>
                <div class="toolhidebox">
                    <div class="tool-content">
                        <h3>在线咨询</h3>
                        <div class="toolhb_c">
                        	<?php foreach($this->setting['service_qq'] as $key => $value) { ?>
                            <p>
                                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $value;?>&site=qq&menu=yes">
                                    <img border="0" src="http://pub.idqqimg.com/qconn/wpa/button/button_121.gif" alt="" title="点击这里给我发消息">
                                    <span class="toolkf">客服<?php echo $value;?>(点击咨询)</span>
                                </a>
                            </p>
                            <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <span class="tool-icon"><i class="iconfont icon-stel"></i></span>
                <div class="toolhidebox">
                    <div class="tool-content">
                        <h3>联系方式</h3>
                        <div class="toolhb_c">
                            <p>服务时间：<?php echo $this->setting['site_time'];?></p>
                            <p>客服热线：<?php echo $this->setting['site_phone'];?></p>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <span class="tool-icon"><i class="iconfont icon-app"></i></span>
                <div class="toolhidebox">
                    <div class="tool-content">
                        <h3>扫一扫下载App</h3>
                        <div class="toolhb_c">
                            <img width="100%" src="<?php echo $this->setting['app_image'];?>" alt="">
                        </div>
                    </div>
                </div>
            </li>
            <li class="go-top" id="returnTop">
                <span class="tool-icon"><i class="iconfont icon-gotop"></i></span>
            </li>
        </ul>
    </div>
	<?php if(0) { ?>
	<div class="modal-wrap w-400" id="city-box" style="display:none;">
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon"><i class="iconfont icon-info"></i></span>
					<h3 class="tip-title">该城市暂时没有家政人员入驻</h3>
					<p class="tip-hint">你可以到附近城市逛逛</p>
				</div>
            </div>
		</div>
        <div class="modal-ft tc">
             <a class="btn btn-primary" href="index.php?act=city">切换城市</a>
        </div>
	</div>
	<script type="text/javascript">
		$(function() {
			Custombox.open({
				target : '#city-box',
				effect : 'blur',
				overlayClose : true,
				speed : 500,
				overlaySpeed : 300,
			});
		});
	</script>
    <?php } elseif(!empty($message)) { ?>
    <div class="modal-wrap w-500" id="message-box" style="display:none;">
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon"><i class="iconfont icon-info"></i></span>
					<h3 class="tip-title"><?php echo $message['message_title'];?></h3>
					<p class="tip-hint"><?php echo $message['message_content'];?></p>
				</div>
            </div>
		</div>
        <div class="modal-ft tc">
             <a class="btn btn-primary" href="index.php?act=order&op=book">查看</a>
        </div>
	</div>
	<script type="text/javascript">
		$(function() {
			Custombox.open({
				target : '#message-box',
				effect : 'blur',
				overlayClose : true,
				speed : 500,
				overlaySpeed : 300,
			});
		});
	</script>
	<?php } else { ?>
    <div class="modal-wrap w-400" id="red-box" style="display:none;">
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
    <script type="text/javascript">
		$(function() {
			$.getJSON('index.php?act=index&op=red', function(data){
				if(data.done == 'true') {
					$('#red-box .tip-icon').html('<i class="iconfont icon-check"></i>');
					$('#red-box .tip-title').html('恭喜您，获得'+data.red_price+'元红包');
					$('#red-box .tip-hint').html('您可以在个人中心查看');
					Custombox.open({
						target : '#red-box',
						effect : 'blur',
						overlayClose : true,
						speed : 500,
						overlaySpeed : 300,
						open: function () {
							setTimeout(function() {
								Custombox.close();
							}, 3000);
						},
					});
				}
			});
		});
	</script>
    <?php } ?>
	<script type="text/javascript" src="templates/js/swiper.min.js"></script>
	<script type="text/javascript">
		var swiper = new Swiper('#swiper-banner', {
    		pagination: '.swiper-banner',
    		effect : 'fade',
    		paginationClickable: true,
    		paginationBulletRender: function (index, className) {
        		return '<span class="' + className + '">' + (index + 1) + '</span>';
    		}
		});

		var swiper = new Swiper('#swiper-shop', {
			nextButton: '.shop-next',
			prevButton: '.shop-prev',
			pagination: '.swiper-shop',
    	});

		function objscroll(divname) {
			var objs = document.getElementById(divname), divname=$("#"+divname);
			var o = {};
			o.obj = divname;
			o.objH = divname.height();
			o.objs = objs;
			$(window).scroll(function() {
				var bodyH = $(document).scrollTop(), headH = $(".header").height()+$(".full-banner").height();
				if(bodyH >= headH) {
					$('.go-top').show();
				} else {
					$('.go-top').hide();
				}
			});
		}
		objscroll("service");
		$("#returnTop").click(function() {
			var speed=500;
			$('body,html').animate({scrollTop : 0}, speed);
			return false;
		});
	</script>
<?php include(template('common_footer'));?>