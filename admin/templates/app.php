<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">全局<span></span></a>
                    <dl style="display:block">
						<?php if(in_array('index', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=index">站点信息</a>
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
							<a class="active" href="admin.php?act=app">APP设置</a>
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
            	<label class="frm_checkbox_label"><strong>APP设置</strong></label> <span>第二行：权重 第三行：图标名称</span>
            </div>    
            <form action="admin.php?act=app" method="post" class="content-form" id="mall_app">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            	<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
							<div class="control_group">
                                <div class="control_label">APP首页描述：</div>
                                <div class="controls">
                                    <input type="text" name="app_desc" class="form_input input_xxlarge" value="<?php echo $setting['app_desc'];?>">
                                </div>
                            </div>
                        	<div class="control_group">
                                <div class="control_label">广告轮播图：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                	<?php if(!empty($setting['app_banner_image'][0])) { ?>
                                                    <img src="<?php echo $setting['app_banner_image'][0];?>" id="show_image_1">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_1">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(1);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_banner_image'][1])) { ?>
                                                    <img src="<?php echo $setting['app_banner_image'][1];?>" id="show_image_2">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_2">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(2);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_banner_image'][2])) { ?>
                                                    <img src="<?php echo $setting['app_banner_image'][2];?>" id="show_image_3">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_3">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(3);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_banner_image'][3])) { ?>
                                                    <img src="<?php echo $setting['app_banner_image'][3];?>" id="show_image_4">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_4">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(4);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_banner_image'][4])) { ?>
                                                    <img src="<?php echo $setting['app_banner_image'][4];?>" id="show_image_5">
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
                                                 <input type="hidden" name="app_banner_image[]" id="image_1" value="<?php echo $setting['app_banner_image'][0];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_2" id="file_2" field_id="2" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="app_banner_image[]" id="image_2" value="<?php echo $setting['app_banner_image'][1];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_3" id="file_3" field_id="3" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="app_banner_image[]" id="image_3" value="<?php echo $setting['app_banner_image'][2];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_4" id="file_4" field_id="4" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="app_banner_image[]" id="image_4" value="<?php echo $setting['app_banner_image'][3];?>"  />
                                            </li>
                                            <li>
                                                 <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_5" id="file_5" field_id="5" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                 </a>
                                                 <input type="hidden" name="app_banner_image[]" id="image_5" value="<?php echo $setting['app_banner_image'][4];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_url[]" value="<?php echo $setting['app_banner_url'][0];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_url[]" value="<?php echo $setting['app_banner_url'][1];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_url[]" value="<?php echo $setting['app_banner_url'][2];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_url[]" value="<?php echo $setting['app_banner_url'][3];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_url[]" value="<?php echo $setting['app_banner_url'][4];?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_weight[]" value="<?php echo $setting['app_banner_weight'][0] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_weight[]" value="<?php echo $setting['app_banner_weight'][1] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_weight[]" value="<?php echo $setting['app_banner_weight'][2] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_weight[]" value="<?php echo $setting['app_banner_weight'][3] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_banner_weight[]" value="<?php echo $setting['app_banner_weight'][4  ] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="help_desc">建议图片大小为：1024*260像素</p>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">分类图标：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][0])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][0];?>" id="show_image_16">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_16">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(16);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][1])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][1];?>" id="show_image_17">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_17">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(17);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][2])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][2];?>" id="show_image_18">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_18">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(18);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][3])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][3];?>" id="show_image_19">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_19">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(19);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][4])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][4];?>" id="show_image_20">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_20">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(20);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][5])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][5];?>" id="show_image_21">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_21">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(21);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][6])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][6];?>" id="show_image_22">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_22">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(22);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][7])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][7];?>" id="show_image_23">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_23">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(23);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_16" id="file_16" field_id="16" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_16" value="<?php echo $setting['app_icon_image'][0];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_17" id="file_17" field_id="17" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_17" value="<?php echo $setting['app_icon_image'][1];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_18" id="file_18" field_id="18" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_18" value="<?php echo $setting['app_icon_image'][2];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_19" id="file_19" field_id="19" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_19" value="<?php echo $setting['app_icon_image'][3];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_20" id="file_20" field_id="20" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_20" value="<?php echo $setting['app_icon_image'][4];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_21" id="file_21" field_id="21" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_21" value="<?php echo $setting['app_icon_image'][5];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_22" id="file_22" field_id="22" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_22" value="<?php echo $setting['app_icon_image'][6];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_23" id="file_23" field_id="23" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_23" value="<?php echo $setting['app_icon_image'][7];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][0];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][1];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][2];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][3];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][4];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][5];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][6];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][7];?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][0] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][1] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][2] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][3] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][4] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][5] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][6] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][7] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][0] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][1] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][2] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][3] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][4] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][5] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][6] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][7] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][8])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][8];?>" id="show_image_24">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_24">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(24);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][9])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][9];?>" id="show_image_25">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_25">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(25);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][10])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][10];?>" id="show_image_26">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_26">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(26);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][11])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][11];?>" id="show_image_27">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_27">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(27);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][12])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][12];?>" id="show_image_28">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_28">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(28);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][13])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][13];?>" id="show_image_29">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_29">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(29);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][14])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][14];?>" id="show_image_30">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_30">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(30);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][15])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][15];?>" id="show_image_31">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_31">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(31);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_24" id="file_24" field_id="24" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_24" value="<?php echo $setting['app_icon_image'][8];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_25" id="file_25" field_id="25" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_25" value="<?php echo $setting['app_icon_image'][9];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_26" id="file_26" field_id="26" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_26" value="<?php echo $setting['app_icon_image'][10];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_27" id="file_27" field_id="27" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_27" value="<?php echo $setting['app_icon_image'][11];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_28" id="file_28" field_id="28" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_28" value="<?php echo $setting['app_icon_image'][12];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_29" id="file_29" field_id="29" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_29" value="<?php echo $setting['app_icon_image'][13];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_30" id="file_30" field_id="30" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_30" value="<?php echo $setting['app_icon_image'][14];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_31" id="file_31" field_id="31" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_31" value="<?php echo $setting['app_icon_image'][15];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][8];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][9];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][10];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][11];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][12];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][13];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][14];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][15];?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][8] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][9] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][10] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][11] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][12] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][13] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][14] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][15] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][8] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][9] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][10] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][11] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][12] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][13] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][14] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][15] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][16])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][16];?>" id="show_image_32">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_32">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(32);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][17])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][17];?>" id="show_image_33">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_33">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(33);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][18])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][18];?>" id="show_image_34">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_34">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(34);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][19])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][19];?>" id="show_image_35">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_35">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(35);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][20])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][20];?>" id="show_image_36">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_36">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(36);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][21])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][21];?>" id="show_image_37">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_37">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(37);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][22])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][22];?>" id="show_image_38">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_38">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(38);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_icon_image'][23])) { ?>
                                                        <img src="<?php echo $setting['app_icon_image'][23];?>" id="show_image_39">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_39">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(39);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_32" id="file_32" field_id="32" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_32" value="<?php echo $setting['app_icon_image'][16];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_33" id="file_33" field_id="33" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_33" value="<?php echo $setting['app_icon_image'][17];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_34" id="file_34" field_id="34" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_34" value="<?php echo $setting['app_icon_image'][18];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_35" id="file_35" field_id="35" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_35" value="<?php echo $setting['app_icon_image'][19];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_36" id="file_36" field_id="36" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_36" value="<?php echo $setting['app_icon_image'][20];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_37" id="file_37" field_id="37" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_37" value="<?php echo $setting['app_icon_image'][21];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_38" id="file_38" field_id="38" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_38" value="<?php echo $setting['app_icon_image'][22];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_39" id="file_39" field_id="39" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_icon_image[]" id="image_39" value="<?php echo $setting['app_icon_image'][23];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][16];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][17];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][18];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][19];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][20];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][21];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][22];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_url[]" value="<?php echo $setting['app_icon_url'][23];?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][16] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][17] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][18] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][19] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][20] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][21] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][22] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_weight[]" value="<?php echo $setting['app_icon_weight'][23] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][16] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][17] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][18] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][19] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][20] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][21] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][22] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_icon_name[]" value="<?php echo $setting['app_icon_name'][23] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">活动专区图片：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_active_image'][0])) { ?>
                                                        <img src="<?php echo $setting['app_active_image'][0];?>" id="show_image_40">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_40">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(40);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_active_image'][1])) { ?>
                                                        <img src="<?php echo $setting['app_active_image'][1];?>" id="show_image_41">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_41">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(41);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_active_image'][2])) { ?>
                                                        <img src="<?php echo $setting['app_active_image'][2];?>" id="show_image_42">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_42">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(42);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_active_image'][3])) { ?>
                                                        <img src="<?php echo $setting['app_active_image'][3];?>" id="show_image_43">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_43">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(43);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_40" id="file_40" field_id="40" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_active_image[]" id="image_40" value="<?php echo $setting['app_active_image'][0];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_41" id="file_41" field_id="41" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_active_image[]" id="image_41" value="<?php echo $setting['app_active_image'][1];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_42" id="file_42" field_id="42" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_active_image[]" id="image_42" value="<?php echo $setting['app_active_image'][2];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_43" id="file_43" field_id="43" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_active_image[]" id="image_43" value="<?php echo $setting['app_active_image'][3];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_url[]" value="<?php echo $setting['app_active_url'][0];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_url[]" value="<?php echo $setting['app_active_url'][1];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_url[]" value="<?php echo $setting['app_active_url'][2];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_url[]" value="<?php echo $setting['app_active_url'][3];?>" />
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_weight[]" value="<?php echo $setting['app_active_weight'][0] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_weight[]" value="<?php echo $setting['app_active_weight'][1] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_weight[]" value="<?php echo $setting['app_active_weight'][2] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_active_weight[]" value="<?php echo $setting['app_active_weight'][3] ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">家政人员入驻：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                            	<a href="javascript:;">
                                                	<?php if(!empty($setting['adv_image'])) { ?>
                                                    <img src="<?php echo $setting['adv_image'];?>" id="show_image_6">
                                                    <?php } else { ?>
                                                    <img src="admin/templates/images/default.jpg" id="show_image_6">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(6);">×</span>
                                            </li>
                                    	</ul>
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
                                            	<input type="hidden" name="adv_image" id="image_6" value="<?php echo $setting['adv_image'];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">热门服务图片：</div>
                                <div class="controls">
                                    <div class="picture_list">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_hot_service_image'][0])) { ?>
                                                        <img src="<?php echo $setting['app_hot_service_image'][0];?>" id="show_image_44">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_44">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(44);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_hot_service_image'][1])) { ?>
                                                        <img src="<?php echo $setting['app_hot_service_image'][1];?>" id="show_image_45">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_45">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(45);">×</span>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <?php if(!empty($setting['app_hot_service_image'][2])) { ?>
                                                        <img src="<?php echo $setting['app_hot_service_image'][2];?>" id="show_image_46">
                                                    <?php } else { ?>
                                                        <img src="admin/templates/images/default.jpg" id="show_image_46">
                                                    <?php } ?>
                                                </a>
                                                <span class="close_modal" href="javascript:;" onclick="image_del(46);">×</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_44" id="file_44" field_id="44" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_hot_service_image[]" id="image_44" value="<?php echo $setting['app_hot_service_image'][0];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_45" id="file_45" field_id="45" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_hot_service_image[]" id="image_45" value="<?php echo $setting['app_hot_service_image'][1];?>"  />
                                            </li>
                                            <li>
                                                <a class="add_goods" href="javascript:;">
                                                    <span class="img_upload">
                                                        <input type="file" name="file_46" id="file_46" field_id="46" size="1" hidefocus="true" maxlength="0" mall_type="image">
                                                    </span>
                                                    <div class="upload-button">图片上传</div>
                                                </a>
                                                <input type="hidden" name="app_hot_service_image[]" id="image_46" value="<?php echo $setting['app_hot_service_image'][2];?>"  />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_hot_service_url[]" value="<?php echo $setting['app_hot_service_url'][0];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_hot_service_url[]" value="<?php echo $setting['app_hot_service_url'][1];?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_hot_service_url[]" value="<?php echo $setting['app_hot_service_url'][2];?>" />
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="picture_list add_list">
                                        <ul>
                                            <li>
                                                <input type="text" class="banner-url" name="app_hot_service_weight[]" value="<?php echo $setting['app_hot_service_weight'][0] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_hot_service_weight[]" value="<?php echo $setting['app_hot_service_weight'][1] ?>" />
                                            </li>
                                            <li>
                                                <input type="text" class="banner-url" name="app_hot_service_weight[]" value="<?php echo $setting['app_hot_service_weight'][2] ?>" />
                                            </li>
                                        </ul>
                                    </div>
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
<!--  44 开始 -->
    <script type="text/javascript">
		var file_name = 'plat';
		function checksubmit() {
			ajaxpost('mall_app', '', '', 'onerror');
		}
	</script>
    <script type="text/javascript" src="admin/templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="admin/templates/js/index.js"></script>
<?php include(template('common_footer'));?>