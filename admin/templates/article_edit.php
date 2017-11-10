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
						<?php if(in_array('article', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a class="active" href="admin.php?act=article">文章公告</a>
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
            	<label class="frm_checkbox_label"><strong>编辑文章</strong></label>
				<div class="page_filter_right">
            		<a href="admin.php?act=article" class="btn btn_default">返回</a>
            	</div>
            </div>
            <form action="admin.php?act=article&op=edit" method="post" class="content-form" id="mall_article">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                <input type="hidden" name="article_id" value="<?php echo $article['article_id'];?>"  />
            	<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                        	<div class="control_group">
                                <div class="control_label">文章标题：</div>
                                <div class="controls">
                                    <input type="text" name="article_title" class="form_input input_xxlarge" value="<?php echo $article['article_title'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">文章排序：</div>
                                <div class="controls">
                                    <input type="text" name="article_sort" class="form_input input_xlarge" value="<?php echo $article['article_sort'];?>">
                                </div>
                            </div>
                            <div class="control_group">
								<div class="control_label">推荐到首页：</div>
								<div class="controls">
									<input type="hidden" id="article_recommend" name="article_recommend" value="<?php echo $article['article_recommend'];?>">
									<label<?php echo empty($article['article_recommend']) ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="article_recommend" data="0"></i>否
									</label>
									<label<?php echo !empty($article['article_recommend']) ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="article_recommend" data="1"></i>是
									</label>
                                </div>
							</div>
                            <div class="control_group">
                                <div class="control_label">文章内容：</div>
                                <div class="controls">
                                	<?php showeditor('article_content', "$article[article_content]", "100%", "600px", $style = "visibility:hidden;", "true", false);?>
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
		function checksubmit() {
			ajaxpost('mall_article', '', '', 'onerror');
		}
	</script>
<?php include(template('common_footer'));?>