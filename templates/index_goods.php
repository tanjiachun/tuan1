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
                <div class="main-left">
                    <div class="level-list" id="level-wrap">
                        <ul>
							<li>
								<a href="javascript:;"><i class="iconfont icon-menu"></i>产品分类<span></span></a>
								<dl style="display: block;">
									<?php foreach($class_list as $key => $value) { ?>
									<dd>
										<a<?php echo $value['class_id']==$class_id ? ' class="active"' : '';?> href="index.php?act=index&op=goods&class_id=<?php echo $value['class_id'];?>"><?php echo $value['class_name'];?></a>
									</dd>
									<?php } ?>
								</dl>
							</li>
                        </ul>
                    </div>
                </div>
                <div class="main-right">
                    <div class="selector">
                        <div class="selectorline search-box"<?php echo empty($brand_name) ? ' style="display:none;"' : '';?>>
                            <label>你的选择：</label>
                            <div class="selector-value clearfix">
                                <ul>
                                	<?php if(!empty($brand_name)) { ?>
                                	<li id="brand_id"><span class="selected"><?php echo $brand_name;?><i>x</i></span></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php if(!empty($type_list)) { ?>
                        <div class="selectorline">
                            <label>类型：</label>
                            <div class="selector-value clearfix">
                                <ul>
                                	<?php foreach($type_list as $key => $value) { ?>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'type_id', '<?php echo $value['type_id'];?>');"><?php echo $value['type_name'];?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if(!empty($brand_list)) { ?>
                        <div class="selectorline selector-open">
                            <label>品牌：</label>
                            <div class="selector-value clearfix">
                                <ul>
                                	<?php foreach($brand_list as $key => $value) { ?>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'brand_id', '<?php echo $value['brand_id'];?>');"><?php echo $value['brand_name'];?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="selectorline">
                            <label>价格：</label>
                            <div class="selector-value clearfix">
                                <ul>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'goods_price', '0-99');">0-99</a></li>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'goods_price', '100-199');">100-199</a></li>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'goods_price', '200-299');">200-299</a></li>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'goods_price', '300-399');">300-399</a></li>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'goods_price', '400-499');">400-499</a></li>
                                    <li><a href="javascript:;" onclick="selectgoods(this, 'goods_price', '500');">500以上</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="filter-line">
                        <div class="f-sort">
                            <a href="javascript:;" class="curr" onclick="selectgoods(this, 'sort_field', 'time');">默认<i></i></a>
                            <a href="javascript:;" onclick="selectgoods(this, 'sort_field', 'view');">人气<i></i></a>
                            <a href="javascript:;" onclick="selectgoods(this, 'sort_field', 'price');">价格<i></i></a>
                        </div>
                        <div class="f-pager page-box">
                            <span class="fp-text"><b><?php echo $page;?></b><em>/</em><i><?php echo $maxpage;?></i></span>
                            <?php if($page == 1) { ?>
							<a class="fp-prev disabled" href="javascript:;">&lt;</a>
                            <?php } else { ?>
							<a class="fp-prev" href="javascript:;" onclick="selectgoods(this, 'page', '<?php echo $page-1;?>');">&lt;</a>
							<?php } ?>
							<?php if($page == $maxpage) { ?>
							<a class="fp-next disabled" href="javascript:;">&gt;</a>
							<?php } else { ?>
							<a class="fp-next" href="javascript:;" onclick="selectgoods(this, 'page', '<?php echo $page+1;?>');">&gt;</a>
							<?php } ?>
                        </div>
                        <div class="f-result-sum count-box">共<span id="J_resCount" class="num"><?php echo $count;?></span>件商品</div>
                    </div>
                    <div class="goods-list goods-box">
                    	<?php if(empty($goods_list)) { ?>
                        <div class="no-shop">
                            <dl>
                                <dt></dt>
                                <dd>
                                    <p>抱歉，没有找到符合条件的商品</p>
                                    <p>您可以适当减少筛选条件</p>
                                </dd>
                            </dl>
                        </div>
                        <?php } else { ?>
                        <ul class="clearfix">
                        	<?php foreach($goods_list as $key => $value) { ?>
                            <li class="gl-item">
                                <div class="gl-item-img">
                                    <a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>">
                                        <img src="<?php echo $value['goods_image'];?>">
                                    </a>
                                </div>
                                <div class="p-price">
                                    <strong><em>￥</em><i><?php echo $value['goods_price'];?></i></strong>
                                    <?php if($value['goods_original_price'] > 0) { ?>
                                    <del><em>￥</em><i><?php echo $value['goods_original_price'];?></i></del>
                                    <?php } ?>
                                </div>
                                <div class="p-name">
                                    <a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>"><?php echo $value['goods_name'];?></a>
                                </div>
                                <div class="p-commit">已有<?php echo $value['goods_salenum'];?>人购买</div>
                                <div class="p-shop"><em class="self-support"><?php echo $store_list[$value['store_id']];?></em></div>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
						<?php echo $multi;?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		var class_id = '<?php echo $class_id;?>';
		var brand_id = '<?php echo $brand_id;?>';
		var page = '<?php echo $page;?>';
	</script>
    <script type="text/javascript" src="templates/js/home/index_goods.js"></script>
<?php include(template('common_footer'));?>