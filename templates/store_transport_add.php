<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>商家中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=store_order">订单管理</a></li>
						<li><a href="index.php?act=store_return">退换货</a></li>
						<li><a href="index.php?act=store_order&state=payment">待发货</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=store_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">商品中心</h3>
					<ul>
						<li><a href="index.php?act=store_goods&op=add">发布商品</a></li>
						<li><a href="index.php?act=store_goods">出售商品</a></li>
						<li><a href="index.php?act=store_goods&op=goods_unshow">仓库商品</a></li>
						<li><a href="index.php?act=store_spec">规格管理</a></li>
					</ul>
					<h3 class="no4">营销中心</h3>
					<ul>
						<li><a href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no5">商家设置</h3>
					<ul>
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a class="active" href="index.php?act=store_transport">运费模板</a></li>
						<li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>运费模板</strong>
					<span class="pull-right">
						<a class="btn btn-primary" href="index.php?act=store_transport">返回</a>
					</span>
				</div>
				<div class="orderlist">
					<div class="orderlist-body transport-box">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
						<div class="logistic-name">
							运费模板名称：<input type="text" id="transport_name" name="transport_name" class="form-input w-300" value="">
						</div>
						<div class="logistic-name">
							<span class="check nameitem" id="name_kd" extend_i="kd" target_box="kd-box"><i class="iconfont icon-type"></i><?php echo $this->store['kd_rename'];?></span> <a class="edit-name order-tb" href="javascript:;" extend_type="kd"><i class="iconfont icon-edit"></i></a>
						</div>
						<div class="logistic-item extend-box" id="kd-box" style="display:none;">
							<div class="logistic-default">
								<div class="logistic-price">
									默认运费：<input type="text" name="extend_snum[kd][0]" extend_i="kd" extend_j="0" class="form-input w-50 extend_snum" value="1">件内，<input type="text" name="extend_sprice[kd][0]" extend_i="kd" extend_j="0" class="form-input w-50 extend_sprice" value="">元，每增加<input type="text" name="extend_xnum[kd][0]" extend_i="kd" extend_j="0" class="form-input w-50 extend_xnum" value="1">件，增加运费<input type="text" name="extend_xprice[kd][0]" extend_i="kd" extend_j="0" class="form-input w-50 extend_xprice" value="">元<input type="hidden" name="extend_area[kd][0]" extend_i="kd" extend_j="0" class="extend_area" value="">
								</div>
							</div>
							<div class="logistic-set">
								<div class="logistic-bd">
									<table class="logistic-table">
										<thead>
											<tr>
												<th>运送到</th>
												<th class="logistic-title">&nbsp;</th>
												<th class="logistic-title">首件（件）</th>
												<th class="logistic-title">首费（元）</th>
												<th class="logistic-title">续件（件）</th>
												<th class="logistic-title">续费（元）</th>
												<th class="logistic-title">操作</th>
											</tr>
										</thead>
										<tbody>
											<tr><td colspan="8"><a href="javascript:;" class="extend-add" extend_i="kd" extend_j="0">为指定地区城市设置运费</a></td></tr>										
										</tbody>
									</table>	
								</div>
							</div>
						</div>
						<div class="logistic-name">
							<span class="check nameitem" id="name_es" extend_i="es" target_box="es-box"><i class="iconfont icon-type"></i><?php echo $this->store['es_rename'];?></span> <a class="edit-name order-tb" href="javascript:;" extend_type="es"><i class="iconfont icon-edit"></i></a>
						</div>
						<div class="logistic-item extend-box" id="es-box" style="display:none;">
							<div class="logistic-default">
								<div class="logistic-price">
									默认运费：<input type="text" name="extend_snum[es][0]" extend_i="es" extend_j="0" class="form-input w-50 extend_snum" value="1">件内，<input type="text" name="extend_sprice[es][0]" extend_i="es" extend_j="0" class="form-input w-50 extend_sprice" value="">元，每增加<input type="text" name="extend_xnum[es][0]" extend_i="es" extend_j="0" class="form-input w-50 extend_xnum" value="1">件，增加运费<input type="text" name="extend_xprice[es][0]" extend_i="es" extend_j="0" class="form-input w-50 extend_xprice" value="">元<input type="hidden" name="extend_area[es][0]" extend_i="es" extend_j="0" class="extend_area" value="">
								</div>
							</div>
							<div class="logistic-set">
								<div class="logistic-bd">
									<table class="logistic-table">
										<thead>
											<tr>
												<th>运送到</th>
												<th class="logistic-title">&nbsp;</th>
												<th class="logistic-title">首件（件）</th>
												<th class="logistic-title">首费（元）</th>
												<th class="logistic-title">续件（件）</th>
												<th class="logistic-title">续费（元）</th>
												<th class="logistic-title">操作</th>
											</tr>
										</thead>
										<tbody>
											<tr><td colspan="8"><a href="javascript:;" class="extend-add" extend_i="es" extend_j="0">为指定地区城市设置运费</a></td></tr>										
										</tbody>
									</table>	
								</div>
							</div>
						</div>
						<div class="logistic-name">
							<span class="check nameitem" id="name_py" extend_i="py" target_box="py-box"><i class="iconfont icon-type"></i><?php echo $this->store['py_rename'];?></span> <a class="edit-name order-tb" href="javascript:;" extend_type="py"><i class="iconfont icon-edit"></i></a>
						</div>
						<div class="logistic-item extend-box" id="py-box" style="display:none;">
							<div class="logistic-default">
								<div class="logistic-price">
									默认运费：<input type="text" name="extend_snum[py][0]" extend_i="py" extend_j="0" class="form-input w-50 extend_snum" value="1">件内，<input type="text" name="extend_sprice[py][0]" extend_i="py" extend_j="0" class="form-input w-50 extend_sprice" value="">元，每增加<input type="text" name="extend_xnum[py][0]" extend_i="py" extend_j="0" class="form-input w-50 extend_xnum" value="1">件，增加运费<input type="text" name="extend_xprice[py][0]" extend_i="py" extend_j="0" class="form-input w-50 extend_xprice" value="">元<input type="hidden" name="extend_area[py][0]" extend_i="py" extend_j="0" class="extend_area" value="">
								</div>
							</div>
							<div class="logistic-set">
								<div class="logistic-bd">
									<table class="logistic-table">
										<thead>
											<tr>
												<th>运送到</th>
												<th class="logistic-title">&nbsp;</th>
												<th class="logistic-title">首件（件）</th>
												<th class="logistic-title">首费（元）</th>
												<th class="logistic-title">续件（件）</th>
												<th class="logistic-title">续费（元）</th>
												<th class="logistic-title">操作</th>
											</tr>
										</thead>
										<tbody>
											<tr><td colspan="8"><a href="javascript:;" class="extend-add" extend_i="py" extend_j="0">为指定地区城市设置运费</a></td></tr>										
										</tbody>
									</table>	
								</div>
							</div>
						</div>
					</div>
					<div class="logistic-btn">
                    	<a href="javascript:addsubmit();" class="btn btn-primary">保存</a><span class="return-success"></span>
                    </div>
				</div>	
			</div>
		</div>
	</div>
    <div class="modal-wrap w-700" id="area-box" style="display:none;">
        <div class="modal-hd">
            <h4>选择地区</h4>
            <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
        </div>
    	<div class="modal-bd">
        	<div class="citylist">
            	<ul>
                	<li>
                    	<div class="dcity">
                            <div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										华东
									</label>
								</span>
                            </div>
                        	<div class="province-list">
                            	<?php foreach($area_list[0] as $province_id) { ?>
                            	<div class="ecity">
                                	<span class="gareas showcitys">
                                    	<label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                        	<i class="iconfont icon-type"></i>
                                        	<?php echo $province_list[$province_id]['district_ipname'];?>
                                    	</label>
                                    	<span class="check-num"></span>
                                    	<em class="zt"></em>
                                    	<div class="citys">
                                        	<?php foreach($city_list[$province_id] as $key => $value) { ?>
                                        	<span class="areas">
                                            	<label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                	<i class="iconfont icon-type"></i>
                                                	<?php echo $value['district_ipname'];?>
                                            	</label>
                                        	</span>
                                            <?php } ?>
                                        	<p><input type="button" class="close_button" value="关闭"></p>
                                    	</div>
                                	</span>
                            	</div>
                                <?php } ?>
                        	</div>
                    	</div>
                	</li>
                	<li>
                        <div class="dcity">
                            <div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										华北
									</label>
								</span>
                            </div>
                        	<div class="province-list">
                            	<?php foreach($area_list[1] as $province_id) { ?>
                            	<div class="ecity">
                                	<span class="gareas showcitys">
                                    	<label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                        	<i class="iconfont icon-type"></i>
                                        	<?php echo $province_list[$province_id]['district_ipname'];?>
                                    	</label>
                                    	<span class="check-num"></span>
                                    	<em class="zt"></em>
                                    	<div class="citys">
                                        	<?php foreach($city_list[$province_id] as $key => $value) { ?>
                                        	<span class="areas">
                                            	<label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                	<i class="iconfont icon-type"></i>
                                                	<?php echo $value['district_ipname'];?>
                                            	</label>
                                        	</span>
                                            <?php } ?>
                                        	<p><input type="button" class="close_button" value="关闭"></p>
                                    	</div>
                                	</span>
                            	</div>
                                <?php } ?>
                        	</div>
                    	</div>
               	 	</li>
                	<li>
                        <div class="dcity">
                            <div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										华中
									</label>
								</span>
                            </div>
                            <div class="province-list">
                                <?php foreach($area_list[2] as $province_id) { ?>
                                <div class="ecity">
                                    <span class="gareas showcitys">
                                        <label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                            <i class="iconfont icon-type"></i>
                                            <?php echo $province_list[$province_id]['district_ipname'];?>
                                        </label>
                                        <span class="check-num"></span>
                                        <em class="zt"></em>
                                        <div class="citys">
                                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                            <span class="areas">
                                                <label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                    <i class="iconfont icon-type"></i>
                                                    <?php echo $value['district_ipname'];?>
                                                </label>
                                            </span>
                                            <?php } ?>
                                            <p><input type="button" class="close_button" value="关闭"></p>
                                        </div>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                	<li>
                        <div class="dcity">
							<div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										华南
									</label>
								</span>
                            </div>
                        	<div class="province-list">
                            	<?php foreach($area_list[3] as $province_id) { ?>
                                <div class="ecity">
                                    <span class="gareas showcitys">
                                        <label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                            <i class="iconfont icon-type"></i>
                                            <?php echo $province_list[$province_id]['district_ipname'];?>
                                        </label>
                                        <span class="check-num"></span>
                                        <em class="zt"></em>
                                        <div class="citys">
                                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                            <span class="areas">
                                                <label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                    <i class="iconfont icon-type"></i>
                                                    <?php echo $value['district_ipname'];?>
                                                </label>
                                            </span>
                                            <?php } ?>
                                            <p><input type="button" class="close_button" value="关闭"></p>
                                        </div>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                    	</div>
                	</li>
                    <li>
                        <div class="dcity">
							<div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										东北
									</label>
								</span>
                            </div>
                        	<div class="province-list">
                            	<?php foreach($area_list[4] as $province_id) { ?>
                                <div class="ecity">
                                    <span class="gareas showcitys">
                                        <label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                            <i class="iconfont icon-type"></i>
                                            <?php echo $province_list[$province_id]['district_ipname'];?>
                                        </label>
                                        <span class="check-num"></span>
                                        <em class="zt"></em>
                                        <div class="citys">
                                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                            <span class="areas">
                                                <label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                    <i class="iconfont icon-type"></i>
                                                    <?php echo $value['district_ipname'];?>
                                                </label>
                                            </span>
                                            <?php } ?>
                                            <p><input type="button" class="close_button" value="关闭"></p>
                                        </div>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                    	</div>
                	</li>
                    <li>
                        <div class="dcity">
                            <div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										西北
									</label>
								</span>
                            </div>
                        	<div class="province-list">
                            	<?php foreach($area_list[5] as $province_id) { ?>
                                <div class="ecity">
                                    <span class="gareas showcitys">
                                        <label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                            <i class="iconfont icon-type"></i>
                                            <?php echo $province_list[$province_id]['district_ipname'];?>
                                        </label>
                                        <span class="check-num"></span>
                                        <em class="zt"></em>
                                        <div class="citys">
                                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                            <span class="areas">
                                                <label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                    <i class="iconfont icon-type"></i>
                                                    <?php echo $value['district_ipname'];?>
                                                </label>
                                            </span>
                                            <?php } ?>
                                            <p><input type="button" class="close_button" value="关闭"></p>
                                        </div>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                    	</div>
                	</li>
                    <li>
                        <div class="dcity">
                            <div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										西南
									</label>
								</span>
                            </div>
                        	<div class="province-list">
                            	<?php foreach($area_list[6] as $province_id) { ?>
                                <div class="ecity">
                                    <span class="gareas showcitys">
                                        <label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                            <i class="iconfont icon-type"></i>
                                            <?php echo $province_list[$province_id]['district_ipname'];?>
                                        </label>
                                        <span class="check-num"></span>
                                        <em class="zt"></em>
                                        <div class="citys">
                                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                            <span class="areas">
                                                <label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                    <i class="iconfont icon-type"></i>
                                                    <?php echo $value['district_ipname'];?>
                                                </label>
                                            </span>
                                            <?php } ?>
                                            <p><input type="button" class="close_button" value="关闭"></p>
                                        </div>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                    	</div>
                	</li>
                    <li>
                        <div class="dcity">
                            <div class="ecity">
								<span class="gareas">
									<label class="check J_Group">
										<i class="iconfont icon-type"></i>
										港澳台
									</label>
								</span>
                            </div>
                        	<div class="province-list">
                            	<?php foreach($area_list[7] as $province_id) { ?>
                                <div class="ecity">
                                    <span class="gareas showcitys">
                                        <label class="check J_Province" district_id="<?php echo $province_id;?>" district_name="<?php echo $province_list[$province_id]['district_ipname'];?>">
                                            <i class="iconfont icon-type"></i>
                                            <?php echo $province_list[$province_id]['district_ipname'];?>
                                        </label>
                                        <span class="check-num"></span>
                                        <em class="zt"></em>
                                        <div class="citys">
                                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                            <span class="areas">
                                                <label class="check J_City" district_id="<?php echo $value['district_id'];?>" district_name="<?php echo $value['district_ipname'];?>">
                                                    <i class="iconfont icon-type"></i>
                                                    <?php echo $value['district_ipname'];?>
                                                </label>
                                            </span>
                                            <?php } ?>
                                            <p><input type="button" class="close_button" value="关闭"></p>
                                        </div>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                    	</div>
                	</li>
            	</ul>
        	</div>
    	</div>
        <div class="modal-ft">
             <a class="btn btn-default" onclick="Custombox.close();">取消</a>
             <a class="btn btn-primary J_Submit">确定</a>
        </div>
    </div>
	<script type="text/javascript">
		var i = 0;
	</script>
    <script type="text/javascript" src="templates/js/profile/store_transport.js"></script>
<?php include(template('common_footer'));?>