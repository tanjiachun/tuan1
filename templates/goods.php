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
			<div class="goods-intro clearfix">
				<div class="photoviews">
					<div id="showbox">
                    	<?php foreach($goods['goods_image_more'] as $key => $value) { ?>
						<img src="<?php echo $value;?>" width="300" height="300" />
						<?php } ?>
					</div>
					<div id="showsum"></div>
					<p class="showpage">
					  <a href="javascript:void(0);" id="showlast">&nbsp;</a>
					  <a href="javascript:void(0);" id="shownext">&nbsp;</a>
					</p>
				</div>
				<div class="goods-nature">
					<div class="goods-title">
						<h1>
							<?php echo $goods['goods_name'];?>
							<a class="goods-share"><i class="iconfont icon-linestar"></i>收藏</a>
							<!-- JiaThis Button BEGIN -->
							<a href="http://www.jiathis.com/share" class="jiathis" target="_blank"><i class="iconfont icon-share"></i>分享</a>
							<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
							<!-- JiaThis Button END -->
						</h1>
					</div>
					<div class="goods-form">
						<ul>
							<li>
								<label>商品价格：</label>
								<div class="goods-form-value"><strong class="goods-price">￥<?php echo !empty($goods['goods_price_interval']) ? $goods['goods_price_interval'] : $goods['goods_price'];?></strong></div>
							</li>
                            <?php if($goods['goods_original_price'] > 0) { ?>
							<li>
								<label>参考价：</label>
								<div class="goods-form-value"><del>￥<?php echo $goods['goods_original_price'];?></del></div>
							</li>
                            <?php } ?>
                            <?php if(!empty($coupon_list)) { ?>
                            <li>
                                <label>领券：</label>
                                <div class="goods-form-value">
									<?php foreach($coupon_list as $key => $value) { ?>
                                    <span class="quan-item" coupon_t_id=<?php echo $value['coupon_t_id'];?>>
                                        <s></s>
                                        <b></b>
										<span class="text"><?php echo $value['coupon_t_content'];?></span>
                                    </span>
									<?php } ?>
                                </div>
                            </li>
                            <?php } ?>
							<li>
								<label>商品评分：</label>
								<div class="goods-form-value">
									<div class="goods-score">
										<?php for($i=0; $i<5; $i++) { ?>
										<?php if($i < $goods_score) { ?>
										<i class="iconfont icon-solidstar cur"></i>
										<?php } else { ?>
										<i class="iconfont icon-solidstar"></i>
										<?php } ?>
										<?php } ?>
									</div>
									<span class="score-num">（已有<?php echo $goods['goods_commentnum'];?>人评）</span>
								</div>
							</li>
							<li>
								<label>服务：</label>
								<div class="goods-form-value">由<span class="shop-name">商家</span>发货，并提供售后服务。</div>
							</li>
                            <?php if(is_array($goods['spec_value'])) { ?>
							<?php $i = 0; ?>
                            <?php foreach($goods['spec_name'] as $key => $value) { ?>
                            <?php $i++; ?>
                            <li>
								<label><?php echo $value;?>：</label>
                                <?php if(is_array($goods['spec_value'][$key]) && !empty($goods['spec_value'][$key])) { ?>
								<div class="goods-form-value">
									<div class="spec-box clearfix">
                                    	<?php foreach($goods['spec_value'][$key] as $k => $v) { ?>
										<a class="spec-item" onclick="selectSpec(<?php echo $i;?>, this, <?php echo $k;?>)" spec_image="<?php echo $goods['spec_image'][$k];?>"><?php echo $v;?></a>
                                        <?php } ?>
									</div>
								</div>
                                <?php } ?>
							</li>
                            <?php } ?>
                            <?php } ?>
							<li>
								<label>购买数量：</label>
								<div class="goods-form-value">
									<div class="p-quantity">
										<div class="quantity-form">
											<a href="javascript:void(0);" class="decrement disabled" id="loss">-</a>
											<input type="text" name="quantity" id="quantity" class="itxt" onKeyUp="changeQuantity(this);" value="1">
											<a href="javascript:void(0);" class="increment" id="plus">+</a>
										</div>
									</div>
									库存数量：<span class="goods-storage"><?php echo $goods['goods_storage'];?></span>
								</div>
							</li>
						</ul>
					</div>
					<div class="goods-intro-btn">
						<a href="javascript:;" class="btn btn-primary" id="buynow">立即购买</a>
						<a href="javascript:;" class="btn btn-info" id="addcart">加入购物车</a>
						<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $store['store_qq'];?>&site=qq&menu=yes" class="btn favorite-add">在线咨询</a>
					</div>
				</div>
			</div>
			<div class="layout-main clearfix">
				<div class="main-left">
					<div class="goods-brand">
						<h1 class="goods-brand-title">同类品牌</h1>
						<ul class="clearfix">
                        	<?php foreach($brand_list as $key => $value) { ?>
							<li><a href="index.php?act=index&op=goods&class_id=<?php echo $value['class_id'];?>&brand_id=<?php echo $value['brand_id'];?>"><?php echo $value['brand_name'];?></a></li>
                            <?php } ?>
						</ul>
					</div>
					<div class="goods-left-list">
						<h1 class="goods-left-title">浏览本产品的用户还浏览过</h1>
						<ul>
                        	<?php foreach($goods_list as $key => $value) { ?>
							<li>
                            	<a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>">
                                    <img src="<?php echo $value['goods_image'];?>">
                                    <h1><?php echo $value['goods_name'];?></h1>
									<p>￥<?php echo $value['goods_price'];?></p>
                                </a>
							</li>
                            <?php } ?>
						</ul>
					</div>
				</div>
				<div class="main-right">
					<div class="tabs-box">
						<ul class="tabs-head" id="goods-tabs-head">
							<li class="active" id="goods_body"><a href="javascript:void(0);">商品介绍</a></li>
							<li id="goods_attr"><a href="javascript:void(0);">规格参数</a></li>
							<li id="goods_comment"><a href="javascript:void(0);">商品评价</a></li>
							<li id="sale_support"><a href="javascript:void(0);">售后保障</a></li>
						</ul>
						<div class="tabs-con">
							<!-- 商品介绍 -->
							<div class="goods-detail">
								<?php echo $goods['goods_body'];?>
							</div>
							<!-- 商品参数 -->
							<div class="goods-parameter">
								<?php if(!empty($goods['goods_attr'])) { ?>
								<div class="parameter-table clearfix">
									<ul>
										<?php foreach($goods['goods_attr'] as $key => $value) { ?>
										<li><label><?php echo $attr_list[$key];?>：</label><?php echo $value;?></li>
										<?php } ?>
									</ul>
								</div>
								<?php } ?>
							</div>
							<!-- 用户点评 -->
							<div class="txtimg-module">
								<div class="txtimgcon">
									<div class="commit-box">
										<div class="commit-filter clearfix">
											<div class="commit-all-score">
												<strong><?php echo $goods['goods_commentnum'];?></strong><span>条</span><em>评论</em>
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
							<!-- 售后保障 -->
							<div class="goods-service">
								<h1>服务承诺：</h1>
								<div class="goods-service-con">
									<?php echo $this->setting['sale_support'];?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-wrap w-400" id="login-box" style="display:none;">
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
					<h3 class="tip-title">您还未登录了</h3>
					<div class="tip-hint">3 秒后页面跳转</div>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			 <a class="btn btn-primary" href="index.php?act=login">确定</a>
		</div>
	</div>
	<div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
    <script type="text/javascript">
		var goods_id = '<?php echo $goods['goods_id'];?>';
		
		function spec(id, spec, price, stock) {
			this.id = id;
			this.spec = spec;
			this.price = price;
			this.stock = stock;
		}

		function goodsspec(specs, specQty, defSpec) {
			this.specs = specs;
			this.specQty = specQty;
			this.defSpec = defSpec;
			<?php for($i=1; $i<=$spec_count; $i++){ ?>
				this.spec<?php echo $i?> = null;
			<?php } ?>
			if(this.specQty >= 1) {
				for(var i = 0; i < this.specs.length; i++) {
					if(this.specs[i].id == this.defSpec) {
						<?php for ($i=1; $i<=$spec_count; $i++){?>
							this.spec<?php echo $i?> = this.specs[i].spec[<?php echo (intval($i)-1);?>];
						<?php }?>
						break;
					}
				}
			}
			this.getSpec = function() {
				for(var i = 0; i < this.specs.length; i++) {
					<?php for ($i=1; $i<=$spec_count; $i++){ ?>
						if(this.specs[i].spec[<?php echo (intval($i)-1);?>] != this.spec<?php echo $i?>) continue;
					<?php }?>
					return this.specs[i];
				}
				return null;
			}
		}

		var specs = new Array();
		var source_goods_price = <?php echo $goods['goods_price']; ?>;
		<?php if(is_array($spec_array) && !empty($spec_array)) { ?>
		<?php foreach($spec_array as $val) { ?>
		specs.push(new spec(<?php echo $val['spec_id']; ?>, [<?php echo $val['spec_goods_spec']?>], <?php echo $val['spec_goods_price']; ?>, <?php echo $val['spec_goods_storage']; ?>));
		<?php } ?>
		<?php } ?>
		<?php if($goods['spec_open'] == 1) { ?>
		var specQty = <?php echo $spec_count;?>
		<?php } else { ?>
		var specQty = 0;
		<?php } ?>;
		var defSpec = <?php echo intval($spec_array[0]['spec_id']); ?>;
		var goodsspec = new goodsspec(specs, specQty, defSpec);
	</script>
	<script type="text/javascript" src="templates/js/photoview.js"></script>
    <script type="text/javascript" src="templates/js/home/goods.js"></script>
<?php include(template('common_footer'));?>