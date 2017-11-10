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
						<li><a class="active" href="index.php?act=store_goods">出售商品</a></li>
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
						<li><a href="index.php?act=store_transport">运费模板</a></li>
                        <li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>发布商品</strong>
				</div>
				<div class="edit-box">
					<div class="edit-body">
						<form method="post" id="goods_form" action="index.php?act=store_goods&op=edit">
							<div class="edit-body-title">基础信信息</div>
							<div class="edit-body-con">
								<div class="form-list">
									<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
									<input type="hidden" id="form_submit" name="form_submit" value="ok" />
									<input type="hidden" id="goods_id" name="goods_id" value="<?php echo $goods['goods_id'];?>" />
									<div class="form-item clearfix">
										<label>商品名称：</label>
										<div class="form-item-value">
											<input type="text" id="goods_name" name="goods_name" class="form-input w-600" value="<?php echo $goods['goods_name'];?>">
											<div class="Validform-checktip Validform-wrong"></div>
											<div class="t-tips">商品标题名称长度至少3个字符，最长50个汉字</div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>商品类别：</label>
										<div class="form-item-value">
											<div class="select-class class-box">
												<a href="javascript:;" class="select-choice"><?php echo empty($class_name) ? '请选择' : $class_name;?><i class="select-arrow"></i></a>
												<div class="select-list" style="display: none">
													<ul>
														<li field_value="" field_key="class_id">请选择</li>
														<?php foreach($class_list as $key => $value) { ?>
														<li field_value="<?php echo $value['class_id'];?>" field_key="class_id"><?php echo $value['class_name'];?></li>
														<?php } ?>
													</ul>
												</div>
											</div>
											<input type="hidden" id="class_id" name="class_id" value="<?php echo $goods['class_id'];?>">
											<div class="Validform-checktip Validform-wrong"></div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>规格值：</label>
										<div class="form-item-value spec-list">
											<?php if(empty($store_spec_list)) { ?>
											<a href="index.php?act=store_spec" class="btn">添加规格项目</a>
											<?php } else { ?>
											<?php foreach($store_spec_list as $key => $value) { ?>
											<span class="check<?php echo in_array($value['sp_id'], $sp_ids) ? ' active' : '';?>" sp_id="<?php echo $value['sp_id'];?>"><i class="iconfont icon-type"></i><?php echo $value['sp_name'];?></span>
											<?php } ?>
											<?php } ?>
										</div>
									</div>
									<div class="spec-box">
										<?php if($goods['spec_open'] == 1) { ?>
										<?php foreach($spec_list as $key => $value) { ?>
										<div class="form-item clearfix">
											<input type="hidden" name="spec_name[<?php echo $value['sp_id'];?>]" value="<?php echo $value['sp_name']?>" />
											<label><?php echo $value['sp_name']?>：</label>
											<div class="form-item-value">
												<div class="sku-group">
													<div class="sku-group-cont" id="spec_group_<?php echo $key;?>">
														<?php foreach($spec_value_list[$value['sp_id']] as $k => $val) { ?>
															<input type="hidden" name="spec_value[<?php echo $value['sp_id'];?>][<?php echo $val['sp_value_id'];?>]" id="sp_value_<?php echo $val['sp_value_id'];?>" value=""/>
															<span class="check" sp_value_id="<?php echo $val['sp_value_id'];?>" sp_value_name="<?php echo $val['sp_value_name'];?>"><i class="iconfont icon-type"></i><?php echo $val['sp_value_name'];?></span>
														<?php } ?>
														<?php if($value['sp_format'] == 'image'){?>
														<div class="sku-image-file" style="display:none;">
															<div class="sku-image-item sku-image-hd">
																<div class="sku-image-name">颜色</div>
																<div class="sku-image-value">图片(无图片可不填)</div>
															</div>
															<?php foreach($spec_value_list[$value['sp_id']] as $k => $val) { ?>
															<div class="sku-image-item spec_image_<?php echo $val['sp_value_id'];?>" style="display:none;">
																<input type="hidden" name="spec_image[<?php echo $val['sp_value_id'];?>]" id="spec_image_<?php echo $val['sp_value_id'];?>" value="<?php echo $goods['spec_image'][$val['sp_value_id']];?>"/>
																<div class="sku-image-name"><?php echo $val['sp_value_name'];?></div>
																<div class="sku-image-value">
																	<div class="sku-atom-list">
																		<ul>
																			<li id="show_image_<?php echo $val['sp_value_id'];?>">
																				<?php if(!empty($goods['spec_image'][$val['sp_value_id']])) { ?>
																				<div class="sku-atom">
																					<span class="sku-img"><img src="<?php echo $goods['spec_image'][$val['sp_value_id']];?>"></span>
																					<span class="close-modal" onclick="image_close('<?php echo $val['sp_value_id'];?>')">×</span>
																				</div>
																				<?php } else { ?>
																				<div class="sku-atom-file">
																					<i class="iconfont icon-camera"></i>
																					<span class="img-upload"><input type="file" id="file_<?php echo $val['sp_value_id'];?>" name="file_<?php echo $val['sp_value_id'];?>" hidefocus="true" maxlength="0" onchange="image_upload('file_<?php echo $val['sp_value_id'];?>', '<?php echo $val['sp_value_id'];?>');"></span>
																				</div>
																				<?php } ?>
																			</li>
																		</ul>
																	</div>
																</div>
															</div>
															<?php } ?>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
										<?php } ?>
										<div class="form-item clearfix" id="spec_setting" style="display:none;">
											<label>库存配置：</label>
											<div class="form-item-value">
												<div class="sku-group">
													<table class="table-sku-stock">
														<thead>
															<tr>
																<?php foreach($spec_list as $key => $value) { ?>
																<th><?php echo $value['sp_name'];?></th>
																<?php } ?>
																<th class="th_price">价格（元）</th>
																<th class="th_stock">库存<em>*</em></th>
																<th>商品货号</th>
															</tr>
														</thead>
														<tbody id="spec_table"></tbody>
													</table>
												</div>
											</div>
										</div>
										<script type="text/javascript">
											var spec_group_checked = [<?php for($i=0; $i<$sign_i; $i++) { echo $i+1 == $sign_i ? "''" : "'',";}?>];
											var str = '';
											var V = new Array();
											<?php for($i=0; $i<$sign_i; $i++) { ?>
											var spec_group_checked_<?php echo $i;?> = new Array();
											<?php } ?>
										
											function into_array(){
												<?php for($i=0; $i<$sign_i; $i++) { ?>
												spec_group_checked_<?php echo $i;?> = new Array();
												$('#spec_group_<?php echo $i;?>').find('.active').each(function(){
													i = $(this).attr('sp_value_id');
													v = $(this).attr('sp_value_name');
													spec_group_checked_<?php echo $i;?>[spec_group_checked_<?php echo $i;?>.length] = [v,i];
												});
												spec_group_checked[<?php echo $i;?>] = spec_group_checked_<?php echo $i;?>;
												<?php } ?>
											}
										
											function spec_show(){
												$('input[name="goods_price"]').attr('readonly','readonly').css('background', '#E7E7E7 none');
												$('input[name="goods_storage"]').attr('readonly','readonly').css('background', '#E7E7E7 none');	
												$('#spec_setting').show();
												str = '<tr>';
												<?php $this->recursionspec(0, $sign_i);?>
												if(str == '<tr>') {
													$('input[name="goods_price"]').removeAttr('readonly').css('background', '');
													$('input[name="goods_storage"]').removeAttr('readonly').css('background', '');
													$('#spec_setting').hide();
												}
												$('#spec_table').empty().html(str).find('input[type="text"]').each(function() {
													var s = $(this).attr('mall_type');
													try {
														$(this).val(V[s])
													} catch(ex) {
														$(this).val('')
													};
													if($(this).attr('data_type') == 'price' && $(this).val() == '') {
														$(this).val($('input[name="goods_price"]').val());
													}
												});
												storage_sum();
												price_interval();
											}
											$(function() {
												var E_SP = new Array();
												var E_SPV = new Array();
												<?php
													$string = '';
													if(is_array($spec_checked) && !empty($spec_checked)){
														foreach($spec_checked as $key => $value) {
															$string .= "E_SP[".$key."] = '".$value."';";
														}
													}
													echo $string;
													echo "\n";
													$string = '';
													if(is_array($spec_value) && !empty($spec_value)){
														foreach($spec_value as $key => $value) {
															$string .= "E_SPV['{$key}'] = '{$value}';";
														}
													}
													echo $string;
												?>
												V = E_SPV;
												$('.spec-box').find('.check').each(function() {
													$('input[name="goods_price"]').attr('readonly','readonly').css('background', '#E7E7E7 none');
													$('input[name="goods_storage"]').attr('readonly','readonly').css('background', '#E7E7E7 none');
													$('#spec_setting').show();
													s = $(this).attr('sp_value_id');
													if(!(typeof(E_SP[s]) == 'undefined')) {
														var n = $(this).attr('sp_value_name');
														$(this).addClass('active');
														$("#sp_value_"+s).val(n);
													}
												});
												into_array();
												str = '<tr>';
												<?php $this->recursionspec(0, $sign_i);?>
												if(str == '<tr>') {
													$('input[name="goods_price"]').removeAttr('readonly').css('background', '');
													$('input[name="goods_storage"]').removeAttr('readonly').css('background', '');
													$('#spec_setting').hide();
												}
												$('#spec_table').empty().html(str).find('input[type="text"]').each(function() {
													var s = $(this).attr('mall_type');
													try {
														$(this).val(V[s])
													} catch(ex) {
														$(this).val('')
													};
												});
												storage_sum();
												$('.spec-box').find('.active').each(function() {
													spec_image_show($(this));
												});
											});	
										</script>
										<?php } ?>
									</div>
									<div class="form-item clearfix">
										<label>商品价格：</label>
										<div class="form-item-value">
											<input type="hidden" id="goods_price_interval" name="goods_price_interval" value="<?php echo $goods['goods_price_interval'];?>">
											<input type="text" id="goods_price" name="goods_price" class="form-input w-100" value="<?php echo $goods['goods_price'];?>"> 元
											<div class="Validform-checktip Validform-wrong"></div>
											<div class="t-tips">价格必须是0.01~9999999之间的数字，且不能高于市场价。此价格为商品实际销售价格，如果商品存在规格，该价格显示最低价格。</div>
										</div>
									</div>
                                    <div class="form-item clearfix">
										<label>商品库存：</label>
										<div class="form-item-value">
											<input type="text" id="goods_storage" name="goods_storage" class="form-input w-100" value="<?php echo $goods['goods_storage'];?>">
											<div class="Validform-checktip Validform-wrong"></div>
											<div class="t-tips">商铺库存数量必须为0~999999999之间的整数。若启用了库存配置，则系统自动计算商品的总数，此处无需卖家填写。</div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>商家货号：</label>
										<div class="form-item-value">
											<input type="text" id="goods_serial" name="goods_serial" class="form-input w-100" value="<?php echo $goods['goods_serial'];?>">
											<div class="t-tips">商家货号是指商家管理商品的编号，买家不可见。最多可输入20个字符，支持输入中文、字母、数字、_、/、-和小数点</div>
										</div>
									</div>
									<div class="form-item clearfix">
										<label>促销类型：</label>
										<div class="form-item-value promotion-box">
											<span class="radio<?php echo $goods['goods_promotion_type'] == 'normal' ? ' active' : '';?>" field_value="normal" field_key="goods_promotion_type"><i class="iconfont icon-type"></i>普通商品</span>
											<span class="radio<?php echo $goods['goods_promotion_type'] == 'cheap' ? ' active' : '';?>" field_value="cheap" field_key="goods_promotion_type"><i class="iconfont icon-type"></i>商品特惠</span>
											<span class="radio<?php echo $goods['goods_promotion_type'] == 'group' ? ' active' : '';?>" field_value="group" field_key="goods_promotion_type"><i class="iconfont icon-type"></i>商品团购</span>
                                        </div>
										<input type="hidden" id="goods_promotion_type" name="goods_promotion_type" value="<?php echo $goods['goods_promotion_type'];?>">
									</div>	
									<div class="form-item clearfix" id="normal"<?php echo $goods['goods_promotion_type'] == 'normal' ? '  style="display: none"' : '';?>>
										<label>&nbsp;</label> 
										<div class="form-item-value">  
											<div id="cheap"<?php echo $goods['goods_promotion_type'] == 'group' ? '  style="display: none"' : '';?>>
												商品原价：<input type="text" id="goods_original_price" name="goods_original_price" class="form-input w-100" value="<?php echo $goods['goods_original_price'] > 0 ? $goods['goods_original_price'] : '';?>"> 元
											</div>
											<div id="group"<?php echo $goods['goods_promotion_type'] == 'cheap' ? '  style="display: none"' : '';?>>
												团购人数：<input type="text" id="goods_group_number" name="goods_group_number" class="form-input w-100" value="<?php echo $goods['goods_group_number'] > 0 ? $goods['goods_group_number'] : '';?>"> 人&nbsp;&nbsp;
												团购价格：<input type="text" id="goods_group_price" name="goods_group_price" class="form-input w-100" value="<?php echo $goods['goods_group_price'] > 0 ? $goods['goods_group_price'] : '';?>"> 元
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="edit-body-title">商品详情描述</div>
							<div class="edit-body-con">
								<div class="form-list">
                                	<div class="form-item clearfix">
                                        <label>商品封面：</label>
                                        <div class="form-item-value">
                                            <div class="picture-list">
                                                <ul class="clearfix">
                                                    <?php if(!empty($goods['goods_image'])) { ?>
                                                    <li id="show_image_0" class="cover-item">
                                                        <img src="<?php echo $goods['goods_image'];?>">
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
                                                    <input type="hidden" id="goods_image" name="goods_image" class="image_0" value="<?php echo $goods['goods_image'];?>"  />
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
									<div class="form-item clearfix">
										<label>商品图片：</label>
                                        <div class="form-item-value">
                                            <div class="picture-list">
                                                <ul class="clearfix">
                                                    <?php foreach($goods['goods_image_more'] as $key => $value) { ?>
                                                    <li class="cover-item">
                                                        <img src="<?php echo $value;?>">
                                                        <span class="close-modal multi_close">×</span>
                                                        <input type="hidden" name="image_1[]" value="<?php echo $value;?>">
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
										<label>商品描述：</label>
										<div class="form-item-value">
											<?php showeditor('goods_body', "$goods[goods_body]", "100%", "400px", $style = "visibility:hidden;", "true", false);?>
										</div>
									</div>
								</div>
							</div>
                            <div class="attr-body attr-box">
                            	<?php if(!empty($brand_list) || !empty($type_list) || !empty($attr_list)) { ?>
								<div class="edit-body-title">商品属性</div>
								<div class="edit-body-con">
                                	<div class="form-list">
										<?php if(!empty($brand_list)) { ?>
										<div class="form-item clearfix">
                                            <label>商品品牌：</label>
                                            <div class="form-item-value">
                                                <div class="select-class">
                                                    <a href="javascript:;" class="select-choice"><?php echo !empty($brand_name) ? $brand_name : '请选择';?><i class="select-arrow"></i></a>
                                                    <div class="select-list" style="display: none">
                                                        <ul>
                                                            <li field_value="" field_key="brand_id">请选择</li>
                                                            <?php foreach($brand_list as $key => $value) { ?>
                                                            <li field_value="<?php echo $value['brand_id'];?>" field_key="brand_id"><?php echo $value['brand_name'];?></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="brand_id" name="brand_id" value="<?php echo $goods['brand_id'];?>">
                                            </div>
                                        </div>
                                		<?php } ?>
                                        <?php if(!empty($type_list)) { ?>
										<div class="form-item clearfix">
                                            <label>商品类型：</label>
                                            <div class="form-item-value">
                                                <div class="select-class">
                                                    <a href="javascript:;" class="select-choice"><?php echo !empty($type_name) ? $type_name : '请选择';?><i class="select-arrow"></i></a>
                                                    <div class="select-list" style="display: none">
                                                        <ul>
                                                            <li field_value="" field_key="type_id">请选择</li>
                                                            <?php foreach($type_list as $key => $value) { ?>
                                                            <li field_value="<?php echo $value['type_id'];?>" field_key="type_id"><?php echo $value['type_name'];?></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="type_id" name="type_id" value="<?php echo $goods['type_id'];?>">
                                            </div>
                                        </div>
                                		<?php } ?>
										<?php if(!empty($attr_list)) { ?>
										<?php foreach($attr_list as $key => $value) { ?>
										<?php if(!empty($value['attr_value'])) { ?>
                                        <div class="form-item clearfix">
                                            <label><?php echo $value['attr_name'];?>：</label>
                                            <div class="form-item-value">
                                                <div class="select-class">
                                                    <a href="javascript:;" class="select-choice"><?php echo !empty($goods['goods_attr'][$value['attr_id']]) ? $goods['goods_attr'][$value['attr_id']] : '请选择';?><i class="select-arrow"></i></a>
                                                    <div class="select-list" style="display: none">
                                                        <ul>
                                                            <li field_value="" field_key="attr_<?php echo $value['attr_id'];?>">请选择</li>
                                                            <?php foreach($value['attr_value'] as $subkey => $subvalue) { ?>
                                                            <li field_value="<?php echo $subvalue;?>" field_key="attr_<?php echo $value['attr_id'];?>"><?php echo $subvalue;?></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="attr_<?php echo $value['attr_id'];?>" name="goods_attr[<?php echo $value['attr_id'];?>]" value="<?php echo $goods['goods_attr'][$value['attr_id']];?>">
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="form-item clearfix">
                                            <label><?php echo $value['attr_name'];?>：</label>
                                            <div class="form-item-value">
                                                <input type="text" id="attr_<?php echo $value['attr_id'];?>" name="goods_attr[<?php echo $value['attr_id'];?>]" class="form-input w-100" value="<?php echo $goods['goods_attr'][$value['attr_id']];?>">
                                            </div>
                                        </div>
                                        <?php } ?>
										<?php } ?>
                                    	<?php } ?>
									</div>
                                </div>    
                                <?php } ?>
							</div>
							<div class="edit-body-title">商品物流信息</div>
							<div class="edit-body-con">
								<div class="form-list">
									<div class="form-item clearfix">
										<label>运费：</label>
										<div class="form-item-value transport-list">
                                            <span class="radio<?php echo $goods['goods_postage']=='freight' ? ' active' : '';?>" field_value="freight" field_key="goods_postage"><i class="iconfont icon-type"></i>固定运费</span>
                                            <span class="radio<?php echo $goods['goods_postage']=='transport' ? ' active' : '';?>" field_value="transport" field_key="goods_postage"><i class="iconfont icon-type"></i>使用运费模板</span>
										</div>
										<input type="hidden" id="goods_postage" name="goods_postage" value="<?php echo $goods['goods_postage'];?>">
									</div>	
									<div class="form-item clearfix">
										<label>&nbsp;</label>
										<div class="form-item-value">
											<div id="freight"<?php echo $goods['goods_postage']=='transport' ? ' style="display: none"' : '';?>>
												<?php echo $this->store['kd_rename'];?>：<input type="text" id="kd_price" name="kd_price" class="form-input w-100" value="<?php echo $goods['kd_price'];?>"> 元&nbsp;&nbsp;
												<?php echo $this->store['es_rename'];?>：<input type="text" id="es_price" name="es_price" class="form-input w-100" value="<?php echo $goods['es_price'];?>"> 元&nbsp;&nbsp;
												<?php echo $this->store['py_rename'];?>：<input type="text" id="py_price" name="py_price" class="form-input w-100" value="<?php echo $goods['py_price'];?>"> 元
											</div>
											<div id="transport"<?php echo $goods['goods_postage']=='freight' ? ' style="display: none"' : '';?>>
												<?php if(empty($transport_list)) { ?>
												<a href="index.php?act=store_transport" class="btn">添加运费模板</a>
												<?php } else { ?>
												<div class="select-class transport-box">
													<a href="javascript:;" class="select-choice"><?php echo empty($transport_name) ? '请选择' : $transport_name;?><i class="select-arrow"></i></a>
													<div class="select-list" style="display: none">
														<ul>
															<li field_value="" field_key="transport_id">请选择</li>
															<?php foreach($transport_list as $key => $value) { ?>
															<li field_value="<?php echo $value['transport_id'];?>" field_key="transport_id"><?php echo $value['transport_name'];?></li>
															<?php } ?>
														</ul>
													</div>
												</div>
												<?php } ?>
												<input type="hidden" id="transport_id" name="transport_id" value="<?php echo $goods['transport_id'];?>">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="edit-body-con">
								<div class="form-list">
									<div class="form-item clearfix">
										<label>&nbsp;</label>
										<div class="form-item-value">
											<a href="javascript:checksubmit();" class="btn btn-primary">发布</a>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var file_name = 'goods';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="templates/js/profile/store_goods.js"></script>   
	<script type="text/javascript" src="templates/js/imageupload.js"></script>
<?php include(template('common_footer'));?>