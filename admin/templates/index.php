<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">全局<span></span></a>
                    <dl style="display:block">
						<?php if(in_array('index', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a class="active" href="admin.php?act=index">站点信息</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('recommend', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=recommend">网站推荐</a>
                        </dd>
						<?php } ?>
                        <?php if(in_array('type', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a href="admin.php?act=type">服务类别</a>
                            </dd>
                        <?php } ?>
						<?php if(in_array('article', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=article">文章公告</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('link', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=link">友情链接</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('related', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=related">相关认证</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('app', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=app">APP设置</a>
						</dd>
						<?php } ?>
						<?php if(in_array('admin', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=admin">管理员</a>
						</dd>
						<?php } ?>
						<?php if(in_array('log', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=log">操作记录</a>
						</dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>站点信息</strong></label>
            </div>    
            <form action="admin.php?act=index" method="post" class="content-form" id="mall_setting">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            	<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                        	<div class="control_group">
                                <div class="control_label">首页轮播图：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                	<?php if(!empty($setting['banner_image'][0])) { ?>
                                                    <img src="<?php echo $setting['banner_image'][0];?>" id="show_image_1">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_1">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(1);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['banner_image'][1])) { ?>
                                                    <img src="<?php echo $setting['banner_image'][1];?>" id="show_image_2">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_2">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(2);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['banner_image'][2])) { ?>
                                                    <img src="<?php echo $setting['banner_image'][2];?>" id="show_image_3">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_3">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(3);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['banner_image'][3])) { ?>
                                                    <img src="<?php echo $setting['banner_image'][3];?>" id="show_image_4">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_4">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(4);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['banner_image'][4])) { ?>
                                                    <img src="<?php echo $setting['banner_image'][4];?>" id="show_image_5">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_5">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(5);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_1" id="file_1" field_id="1" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="banner_image[]" id="image_1" value="<?php echo $setting['banner_image'][0];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_2" id="file_2" field_id="2" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="banner_image[]" id="image_2" value="<?php echo $setting['banner_image'][1];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_3" id="file_3" field_id="3" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="banner_image[]" id="image_3" value="<?php echo $setting['banner_image'][2];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_4" id="file_4" field_id="4" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="banner_image[]" id="image_4" value="<?php echo $setting['banner_image'][3];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_5" id="file_5" field_id="5" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="banner_image[]" id="image_5" value="<?php echo $setting['banner_image'][4];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                            	<input type="text" class="banner-url" name="banner_url[]" value="<?php echo $setting['banner_url'][0];?>" />
                                            </li>
                                            <li>
                                            	<input type="text" class="banner-url" name="banner_url[]" value="<?php echo $setting['banner_url'][1];?>" />
                                            </li>
                                            <li>
                                            	<input type="text" class="banner-url" name="banner_url[]" value="<?php echo $setting['banner_url'][2];?>" />
                                            </li>
                                            <li>
                                            	<input type="text" class="banner-url" name="banner_url[]" value="<?php echo $setting['banner_url'][3];?>" />
                                            </li>
                                            <li>
                                            	<input type="text" class="banner-url" name="banner_url[]" value="<?php echo $setting['banner_url'][4];?>" />
                                            </li>
                                        </ul>
                                    </div>    
                                    <p class="help_desc">建议图片大小为：710*320像素</p>
                                </div>
                            </div>

                            <div class="control_group">
                                <div class="control_label">首页轮播左侧图：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['banner_left_image'])) { ?>
                                                        <img src="<?php echo $setting['banner_left_image'];?>" id="show_image_7"">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_7"">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(7);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_7" id="file_7" field_id="7" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="banner_left_image" id="image_7"" value="<?php echo $setting['banner_left_image'];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="banner_left_url" value="<?php echo $setting['banner_left_url'];?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="help_desc">建议图片大小为：200*240像素</p>
                                </div>
                            </div>

                            <div class="control_group">
                                <div class="control_label">热门家政机构图：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['nav_image'][0])) { ?>
                                                        <img src="<?php echo $setting['nav_image'][0];?>" id="show_image_8">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_8">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(8);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['nav_image'][1])) { ?>
                                                        <img src="<?php echo $setting['nav_image'][1];?>" id="show_image_9">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_9">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(9);">×</span>
                                            </li>
<!--                                            <li>-->
<!--                                                <a href="javascript:;">-->
<!--                                                    --><?php //if(!empty($setting['nav_image'][2])) { ?>
<!--                                                        <img src="--><?php //echo $setting['nav_image'][2];?><!--" id="show_image_10">-->
<!--                                                    --><?php //} else { ?>
<!--                                                        <img src="admin/templates/images/default.jpg" id="show_image_10">-->
<!--                                                    --><?php //} ?>
<!--                                                </a>-->
<!--                                                <span class="close_modal" href="javascript:;" onclick="image_del(10);">×</span>-->
<!--                                            </li>-->
<!--                                            <li>-->
<!--                                                <a href="javascript:;">-->
<!--                                                    --><?php //if(!empty($setting['nav_image'][3])) { ?>
<!--                                                        <img src="--><?php //echo $setting['nav_image'][3];?><!--" id="show_image_11">-->
<!--                                                    --><?php //} else { ?>
<!--                                                        <img src="admin/templates/images/default.jpg" id="show_image_11">-->
<!--                                                    --><?php //} ?>
<!--                                                </a>-->
<!--                                                <span class="close_modal" href="javascript:;" onclick="image_del(11);">×</span>-->
<!--                                            </li>-->

                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_8" id="file_8" field_id="8" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="nav_image[]" id="image_8" value="<?php echo $setting['nav_image'][0];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_9" id="file_9" field_id="9" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="nav_image[]" id="image_9" value="<?php echo $setting['nav_image'][1];?>"  />
                                            </li>
<!--                                            <li>-->
<!--                                                <a class="add_goods" href="javascript:;">-->
<!--                                                    <span class="img_upload">-->
<!--                                                        <input type="file" name="file_10" id="file_10" field_id="10" size="1" hidefocus="true" maxlength="0" mall_type="image">-->
<!--                                                    </span>-->
<!--                                                    <div class="upload-button">图片上传</div>-->
<!--                                                </a>-->
<!--                                                <input type="hidden" name="nav_image[]" id="image_10" value="--><?php //echo $setting['nav_image'][2];?><!--"  />-->
<!--                                            </li>-->
<!--                                            <li>-->
<!--                                                <a class="add_goods" href="javascript:;">-->
<!--                                                    <span class="img_upload">-->
<!--                                                        <input type="file" name="file_11" id="file_11" field_id="11" size="1" hidefocus="true" maxlength="0" mall_type="image">-->
<!--                                                    </span>-->
<!--                                                    <div class="upload-button">图片上传</div>-->
<!--                                                </a>-->
<!--                                                <input type="hidden" name="nav_image[]" id="image_11" value="--><?php //echo $setting['nav_image'][3];?><!--"  />-->
<!--                                            </li>-->

                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="nav_url[]" value="<?php echo $setting['nav_url'][0];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="nav_url[]" value="<?php echo $setting['nav_url'][1];?>" />
                                            </li>
<!--                                            <li>-->
<!--                                                <input type="text" class="banner-url" name="nav_url[]" value="--><?php //echo $setting['nav_url'][2];?><!--" />-->
<!--                                            </li>-->
<!--                                            <li>-->
<!--                                                <input type="text" class="banner-url" name="nav_url[]" value="--><?php //echo $setting['nav_url'][3];?><!--" />-->
<!--                                            </li>-->

                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="nav_name[]" value="<?php echo $setting['nav_name'][0];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="nav_name[]" value="<?php echo $setting['nav_name'][1];?>" />
                                            </li>
<!--                                            <li>-->
<!--                                                <input type="text" class="banner-url" name="nav_name[]" value="--><?php //echo $setting['nav_name'][2];?><!--" />-->
<!--                                            </li>-->
<!--                                            <li>-->
<!--                                                <input type="text" class="banner-url" name="nav_name[]" value="--><?php //echo $setting['nav_name'][3];?><!--" />-->
<!--                                            </li>-->

                                        </ul>
                                    </div>
                                    <p class="help_desc">建议图片大小为：350*220像素</p>
                                </div>
                            </div>

                            <div class="control_group">
                                <div class="control_label">机构热卖图：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['hot_image'][0])) { ?>
                                                        <img src="<?php echo $setting['hot_image'][0];?>" id="show_image_12">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_12">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(12);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['hot_image'][1])) { ?>
                                                        <img src="<?php echo $setting['hot_image'][1];?>" id="show_image_13">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_13">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(13);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['hot_image'][2])) { ?>
                                                        <img src="<?php echo $setting['hot_image'][2];?>" id="show_image_14">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_14">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(14);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['hot_image'][3])) { ?>
                                                        <img src="<?php echo $setting['hot_image'][3];?>" id="show_image_15">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_15">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(15);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_12" id="file_12" field_id="12" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="hot_image[]" id="image_12" value="<?php echo $setting['hot_image'][0];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_13" id="file_13" field_id="13" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="hot_image[]" id="image_13" value="<?php echo $setting['hot_image'][1];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_14" id="file_14" field_id="14" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="hot_image[]" id="image_14" value="<?php echo $setting['hot_image'][2];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_15" id="file_15" field_id="15" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="hot_image[]" id="image_15" value="<?php echo $setting['hot_image'][3];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="hot_url[]" value="<?php echo $setting['hot_url'][0];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="hot_url[]" value="<?php echo $setting['hot_url'][1];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="hot_url[]" value="<?php echo $setting['hot_url'][2];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="hot_url[]" value="<?php echo $setting['hot_url'][3];?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="help_desc">建议图片大小为：第二张<b>400*200</b>像素&nbsp;&nbsp;其余250*200像素</p>
                                </div>
                            </div>


                            <div class="control_group">
                                <div class="control_label">站点名称：</div>
                                <div class="controls">
                                    <input type="text" name="site_name" class="form_input input_xxlarge" value="<?php echo $setting['site_name'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">站点SEO关键字：</div>
                                <div class="controls">
                                    <input type="text" name="seo_keyword" class="form_input input_xxlarge" value="<?php echo $setting['seo_keyword'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">站点SEO描述：</div>
                                <div class="controls">
									<textarea class="frm_textarea input_xxlarge" id="seo_desc" name="seo_desc"><?php echo $setting['seo_desc'];?></textarea>
                                </div>
                            </div>
                            <div class="control_group sku_atom_list">
                                <div class="control_label">客服QQ：</div>
                                <div class="controls">
                                	<ul>
                                    	<?php foreach($setting['service_qq'] as $key => $value) { ?><li><input type="text" name="service_qq[]" class="form_input input_xlarge" value="<?php echo $value;?>"></li><?php } ?><li><a href="javascript:;" class="btn btn_default service_add">添加</a></li>
                                	</ul>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">服务时间：</div>
                                <div class="controls">
                                    <input type="text" name="site_time" class="form_input input_xxlarge" value="<?php echo $setting['site_time'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">客服热线：</div>
                                <div class="controls">
                                    <input type="text" name="site_phone" class="form_input input_xxlarge" value="<?php echo $setting['site_phone'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">底部省市第一排：</div>
                                <div class="controls">
                                    <input type="text" name="first_province" class="form_input input_xxlarge" value="<?php echo $setting['first_province'];?>">
                                    <p class="help_desc">不同城市用|隔开</p>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">底部省市第二排：</div>
                                <div class="controls">
                                    <input type="text" name="second_province" class="form_input input_xxlarge" value="<?php echo $setting['second_province'];?>">
                                    <p class="help_desc">不同城市用|隔开</p>
                                </div>
                            </div>
							<div class="control_group">
                                <div class="control_label">APP二维码：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                            	<a href="javascript:;">
                                                	<?php if(!empty($setting['app_image'])) { ?>
                                                    <img src="<?php echo $setting['app_image'];?>" id="show_image_6">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_6">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(6);">×</span>
                                            </li>
                                    	</ul>
                                        <p class="help_desc">建议图片大小为：258*258像素</p>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_6" id="file_6" field_id="6" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                            	<input type="hidden" name="app_image" id="image_6" value="<?php echo $setting['app_image'];?>"  /> 
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">版权信息：</div>
                                <div class="controls">
                                	<textarea class="frm_textarea input_xxlarge" id="copy_right" name="copy_right"><?php echo $setting['copy_right'];?></textarea>
                                </div>
                            </div>
							<div class="control_group">
                                <div class="control_label">售后保障：</div>
                                <div class="controls">
                                	<textarea class="frm_textarea input_xxlarge" id="sale_support" name="sale_support"><?php echo $setting['sale_support'];?></textarea>
                                </div>
                            </div>
							<div class="control_group">
                                <div class="control_label">阿姨年费：</div>
                                <div class="controls">
									<input type="text" name="nurse_fee" class="form_input input_xxlarge" value="<?php echo $setting['nurse_fee'];?>">
                                </div>
                            </div>
                        </div>
                	</div>
            	</div>
            </form>    
            <div class="page_bottom tc">
                <a href="javascript:checksubmit();" class="btn btn_primary">保存</a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		var file_name = 'plat';
		function checksubmit() {
			ajaxpost('mall_setting', '', '', 'onerror');
		}
		$(function() {
			$('.service_add').on('click', function() {
				$(this).parent().before('<li><input type="text" name="service_qq[]" class="form_input input_xlarge" value=""></li>');	
			});
		});
	</script>
    <script type="text/javascript" src="admin/templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="admin/templates/js/index.js"></script>
<?php include(template('common_footer'));?>