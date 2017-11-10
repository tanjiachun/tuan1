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
						<li><a class="active" href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no5">商家设置</h3>
					<ul>
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a href="index.php?act=store_transport">运费模板</a></li>
						<li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>新建优惠券</strong>
					<span class="pull-right">
						<a href="index.php?act=store_coupon" class="btn btn-default add-spec">返回</a>
					</span>
				</div>
            	<div class="edit-box">
                	<div class="edit-body">
                    	<div class="edit-body-title">基础信信息</div>
                    	<div class="edit-body-con">
                        	<div class="form-list">
                            	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                            	<div class="form-item clearfix">
                                	<label>优惠券名称：</label>
                                	<div class="form-item-value">
                                    	<input type="text" id="coupon_t_title" name="coupon_t_title" class="form-input w-600">
										<div class="Validform-checktip Validform-wrong"></div>
                                	</div>
                            	</div>
								<div class="form-item clearfix">
									<label>发放总量：</label>
									<div class="form-item-value">
										<input type="text" id="coupon_t_total" name="coupon_t_total" class="form-input w-100"> 张
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label>面值：</label>
									<div class="form-item-value price-box">
										<span class="radio active" field_value="cash" field_key="coupon_t_price_type"> 
											<i class="iconfont icon-type"></i>现金券
										</span>
										<span class="radio" field_value="discount" field_key="coupon_t_price_type"> 
											<i class="iconfont icon-type"></i>折扣券
										</span>
                                        <input type="text" id="coupon_t_price" name="coupon_t_price" class="form-input w-100"> <span id="price_name">元</span>
										<input type="hidden" id="coupon_t_price_type" name="coupon_t_price_type" value="1">
										<div class="Validform-checktip Validform-wrong"></div>
									</div>
								</div>
								<div class="form-item clearfix">
									<label>有效期：</label>
									<div class="form-item-value radio-box">
										<span class="radio active" field_value="duration" field_key="coupon_t_period_type"> 
											<i class="iconfont icon-type"></i>按时长 <input type="text" id="coupon_t_days" name="coupon_t_days" class="form-input w-100"> 天
										</span>
										<span class="radio" field_value="timezone" field_key="coupon_t_period_type"> 
											<i class="iconfont icon-type"></i>按区间 <input type="text" id="coupon_t_starttime" name="coupon_t_starttime" class="form-input w-100" value="<?php echo date('Y-m-d');?>"> - <input type="text" id="coupon_t_endtime" name="coupon_t_endtime" class="form-input w-100" value="<?php echo date('Y-m-d', time()+604800);?>">
										</span>
                                        <div>
                                        </div>
                                        <input type="hidden" id="coupon_t_period_type" name="coupon_t_period_type" value="duration">
									</div>
								</div>
								<div class="form-item clearfix">
									<label>使用门槛：</label>
									<div class="form-item-value radio-box">
										<span class="radio active" field_value="" field_key="coupon_t_limit"> 
											<i class="iconfont icon-type"></i>不限制
										</span>
										<span class="radio" field_value="" field_key="coupon_t_limit"> 
											<i class="iconfont icon-type"></i>满 <input type="text" id="coupon_t_limit" name="coupon_t_limit" class="form-input w-100"> 元可使用
										</span>
									</div>
								</div>
								<div class="form-item clearfix">
									<label>使用说明：</label>
									<div class="form-item-value">
										<textarea class="form-textarea w-10-9" id="coupon_t_desc" name="coupon_t_desc" rows="5"></textarea>
									</div>
								</div>
								<div class="form-item clearfix">
									<label>可使用商品：</label>
									<div class="form-item-value goods-box">
										<span class="radio active" field_value="store" field_key="coupon_t_goods_type"> 
											<i class="iconfont icon-type"></i>全店通用
										</span>
										<span class="radio" field_value="goods" field_key="coupon_t_goods_type"> 
											<i class="iconfont icon-type"></i>指定商品
										</span>
										<div class="sku-group goods-list" style="display:none;">
											<table class="table-sku-stock">
												<thead>
													<tr>
														<th>商品</th>
														<th class="th-price">操作</th>
													</tr>
												</thead>
												<tbody class="select-box"></tbody>
												<tbody>
													<tr>
														<td colspan="2">
															<span class="btn btn-default add-goods goods-add">添加产品</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<input type="hidden" id="coupon_t_goods_type" name="coupon_t_goods_type" value="store">
									</div>
								</div>
                        	</div>
                   		</div>
                    	<div class="edit-body-title">基本规则</div>
                    	<div class="edit-body-con">
                        	<div class="form-list">
                                <div class="form-item clearfix">
                                    <label>领取时间：</label>
                                    <div class="form-item-value">
                                        <input type="text" id="coupon_rule_starttime" name="coupon_rule_starttime" class="form-input w-100" value="<?php echo date('Y-m-d');?>"> - <input type="text" id="coupon_rule_endtime" name="coupon_rule_endtime" class="form-input w-100" value="<?php echo date('Y-m-d', time()+604800);?>">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        	<div class="form-list">
								<div class="form-item clearfix">
									<label>领取方式：</label>
									<div class="form-item-value rule-box">
										<span class="radio active" field_value="buy" field_key="coupon_rule_type"> 
											<i class="iconfont icon-type"></i>消费送
										</span>
										<span class="radio" field_value="free" field_key="coupon_rule_type">
											<i class="iconfont icon-type"></i>免费送
										</span>
									</div>
                                    <input type="hidden" id="coupon_rule_type" name="coupon_rule_type" value="1">
								</div>
                                <div id="buy">
                                	<div class="form-item clearfix">
                                        <label>条件：</label>
                                        <div class="form-item-value">
                                           	消费满 <input type="text" id="coupon_rule_amount" name="coupon_rule_amount" class="form-input w-100"> 元赠送
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="free" style="display:none">
                                	<div class="form-item clearfix">
                                        <label>每人限领：</label>
                                        <div class="form-item-value">
                                        	<input type="text" id="coupon_rule_eachlimit" name="coupon_rule_eachlimit" class="form-input w-100"> 张
                                            </span>
                                        </div>
                                    </div>
                                </div>
								<div class="form-item clearfix">
									<label>&nbsp;</label>
									<div class="form-item-value">
										<a href="javascript:addsubmit();" class="btn btn-primary">保存</a><span class="return-success"></span>
									</div>
								</div>
                        	</div>
                    	</div>
                	</div>
            	</div>
			</div>
		</div>
	</div>
	<link href="templates/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="templates/js/jquery.datetimepicker.js"></script>
	<script type="text/javascript" src="templates/js/profile/store_coupon.js"></script>
<?php include(template('common_footer'));?>