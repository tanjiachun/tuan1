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
							<a class="active" href="admin.php?act=admin">管理员</a>
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
            	<label class="frm_checkbox_label"><strong>编辑管理员</strong></label>
				<div class="page_filter_right">
            		<a href="admin.php?act=admin" class="btn btn_default">返回</a>
            	</div>
            </div>
            <form action="admin.php?act=admin&op=edit" method="post" class="content-form" id="mall_admin">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
				<input type="hidden" id="admin_id" name="admin_id" value="<?php echo $admin_id;?>" />
            	<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
							<div class="control_group">
                                <div class="control_label">用户名：</div>
                                <div class="controls">
                                    <?php echo $admin['admin_name'];?>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">新密码：</div>
                                <div class="controls">
                                    <input type="password" name="admin_password" class="form_input input_xxlarge" value="">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">确认密码：</div>
                                <div class="controls">
                                    <input type="password" name="admin_password2" class="form_input input_xxlarge" value="">
                                </div>
                            </div>
                            <div class="control_group">
								<div class="control_label">全局：</div>
								<div class="controls">
									<label class="frm_checkbox_label<?php echo in_array('index', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_1" data="index">
										<i class="icon16_common icon_checkbox"></i>
                                        站点信息
									</label>
									<input type="hidden" id="permission_1" name="admin_permission[]" value="<?php echo in_array('index', $admin['admin_permission']) ? 'index' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('recommend', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_2" data="recommend">
										<i class="icon16_common icon_checkbox"></i>
                                        网站推荐
									</label>
									<input type="hidden" id="permission_2" name="admin_permission[]" value="<?php echo in_array('recommend', $admin['admin_permission']) ? 'recommend' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('article', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_3" data="article">
										<i class="icon16_common icon_checkbox"></i>
                                        文章公告
									</label>
									<input type="hidden" id="permission_3" name="admin_permission[]" value="<?php echo in_array('article', $admin['admin_permission']) ? 'article' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('link', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_4" data="link">
										<i class="icon16_common icon_checkbox"></i>
                                        友情链接
									</label>
									<input type="hidden" id="permission_4" name="admin_permission[]" value="<?php echo in_array('link', $admin['admin_permission']) ? 'link' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('related', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_5" data="related">
										<i class="icon16_common icon_checkbox"></i>
                                        相关认证
									</label>
									<input type="hidden" id="permission_5" name="admin_permission[]" value="<?php echo in_array('related', $admin['admin_permission']) ? 'related' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('app', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_25" data="app">
										<i class="icon16_common icon_checkbox"></i>
                                        APP设置
									</label>
									<input type="hidden" id="permission_25" name="admin_permission[]" value="<?php echo in_array('app', $admin['admin_permission']) ? 'app' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('admin', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_6" data="admin">
										<i class="icon16_common icon_checkbox"></i>
                                        管理员
									</label>
									<input type="hidden" id="permission_6" name="admin_permission[]" value="<?php echo in_array('admin', $admin['admin_permission']) ? 'admin' : '';?>" />
									<label class="frm_checkbox_label<?php echo in_array('log', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_24" data="log">
										<i class="icon16_common icon_checkbox"></i>
                                        操作记录
									</label>
									<input type="hidden" id="permission_24" name="admin_permission[]" value="<?php echo in_array('log', $admin['admin_permission']) ? 'log' : '';?>" />

                                    <label class="frm_checkbox_label<?php echo in_array('type', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_28" data="type">
                                        <i class="icon16_common icon_checkbox"></i>
                                        服务类别
                                    </label>
                                    <input type="hidden" id="permission_28" name="admin_permission[]" value="<?php echo in_array('type', $admin['admin_permission']) ? 'type' : '';?>" />
                                </div>
							</div>
                            <div class="control_group">
								<div class="control_label">阿姨：</div>
								<div class="controls">
									<label class="frm_checkbox_label<?php echo in_array('nurse', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_7" data="nurse">
										<i class="icon16_common icon_checkbox"></i>
                                        阿姨管理
									</label>
									<input type="hidden" id="permission_7" name="admin_permission[]" value="<?php echo in_array('nurse', $admin['admin_permission']) ? 'nurse' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('grade', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_8" data="grade">
										<i class="icon16_common icon_checkbox"></i>
                                        阿姨等级
									</label>
									<input type="hidden" id="permission_8" name="admin_permission[]" value="<?php echo in_array('grade', $admin['admin_permission']) ? 'grade' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('tag', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_9" data="tag">
										<i class="icon16_common icon_checkbox"></i>
                                        阿姨评论
									</label>
									<input type="hidden" id="permission_9" name="admin_permission[]" value="<?php echo in_array('tag', $admin['admin_permission']) ? 'tag' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('service', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_23" data="service">
										<i class="icon16_common icon_checkbox"></i>
                                        阿姨服务
									</label> 
									<input type="hidden" id="permission_23" name="admin_permission[]" value="<?php echo in_array('service', $admin['admin_permission']) ? 'service' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('nurse_revise', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_28" data="nurse_revise">
                                        <i class="icon16_common icon_checkbox"></i>
                                        阿姨资料修改审核
                                    </label>
                                    <input type="hidden" id="permission_28" name="admin_permission[]" value="<?php echo in_array('nurse_revise', $admin['admin_permission']) ? 'nurse_revise' : '';?>" />
                                </div>
							</div>
                            <div class="control_group">
								<div class="control_label">商品：</div>
								<div class="controls">
									<label class="frm_checkbox_label<?php echo in_array('goods', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_10" data="goods">
										<i class="icon16_common icon_checkbox"></i>
                                        商品管理
									</label>
									<input type="hidden" id="permission_10" name="admin_permission[]" value="<?php echo in_array('goods', $admin['admin_permission']) ? 'goods' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('gclass', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_11" data="gclass">
										<i class="icon16_common icon_checkbox"></i>
                                        商品分类
									</label>
									<input type="hidden" id="permission_11" name="admin_permission[]" value="<?php echo in_array('gclass', $admin['admin_permission']) ? 'gclass' : '';?>" />
                                </div>
							</div>
                            <div class="control_group">
								<div class="control_label">店铺：</div>
								<div class="controls">
									<label class="frm_checkbox_label<?php echo in_array('store', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_12" data="store">
										<i class="icon16_common icon_checkbox"></i>
                                        店铺管理
									</label>
									<input type="hidden" id="permission_12" name="admin_permission[]" value="<?php echo in_array('store', $admin['admin_permission']) ? 'store' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('sclass', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_13" data="sclass">
										<i class="icon16_common icon_checkbox"></i>
                                        店铺分类
									</label>
									<input type="hidden" id="permission_13" name="admin_permission[]" value="<?php echo in_array('sclass', $admin['admin_permission']) ? 'sclass' : '';?>" />
                                </div>
							</div>
                            <div class="control_group">
								<div class="control_label">机构：</div>
								<div class="controls">
									<label class="frm_checkbox_label<?php echo in_array('agent', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_14" data="agent">
										<i class="icon16_common icon_checkbox"></i>
                                        家政机构
									</label>
									<input type="hidden" id="permission_14" name="admin_permission[]" value="<?php echo in_array('agent', $admin['admin_permission']) ? 'agent' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('pension', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_15" data="pension">
										<i class="icon16_common icon_checkbox"></i>
                                        养老机构
									</label>
									<input type="hidden" id="permission_15" name="admin_permission[]" value="<?php echo in_array('pension', $admin['admin_permission']) ? 'pension' : '';?>" />

                                    <label class="frm_checkbox_label<?php echo in_array('agent_grade', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_26" data="agent_grade">
                                        <i class="icon16_common icon_checkbox"></i>
                                        机构等级
                                    </label>
                                    <input type="hidden" id="permission_26" name="admin_permission[]" value="<?php echo in_array('agent_grade', $admin['admin_permission']) ? 'agent_grade' : '';?>" />

                                    <label class="frm_checkbox_label<?php echo in_array('agent_revise', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_27" data="agent_revise">
                                        <i class="icon16_common icon_checkbox"></i>
                                        机构资料修改审核
                                    </label>
                                    <input type="hidden" id="permission_27" name="admin_permission[]" value="<?php echo in_array('agent_revise', $admin['admin_permission']) ? 'agent_revise' : '';?>" />

                                </div>
							</div>
                            <div class="control_group">
								<div class="control_label">运营：</div>
								<div class="controls">
									<label class="frm_checkbox_label<?php echo in_array('card', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_16" data="card">
										<i class="icon16_common icon_checkbox"></i>
                                        会员设置
									</label>
									<input type="hidden" id="permission_16" name="admin_permission[]" value="<?php echo in_array('card', $admin['admin_permission']) ? 'card' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('red', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_17" data="red">
										<i class="icon16_common icon_checkbox"></i>
                                        红包设置
									</label>
									<input type="hidden" id="permission_17" name="admin_permission[]" value="<?php echo in_array('red', $admin['admin_permission']) ? 'red' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('oldage', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_18" data="oldage">
										<i class="icon16_common icon_checkbox"></i>
                                        养老金设置
									</label> 
									<input type="hidden" id="permission_18" name="admin_permission[]" value="<?php echo in_array('oldage', $admin['admin_permission']) ? 'oldage' : '';?>" />
									<label class="frm_checkbox_label<?php echo in_array('package', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_22" data="package">
										<i class="icon16_common icon_checkbox"></i>
                                        充值套餐
									</label> 
									<input type="hidden" id="permission_22" name="admin_permission[]" value="<?php echo in_array('package', $admin['admin_permission']) ? 'package' : '';?>" />
                                </div>
							</div>
                            <div class="control_group">
								<div class="control_label">财务：</div>
								<div class="controls">
									<label class="frm_checkbox_label<?php echo in_array('pnurse', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_19" data="pnurse">
										<i class="icon16_common icon_checkbox"></i>
                                        阿姨收益
									</label>
									<input type="hidden" id="permission_19" name="admin_permission[]" value="<?php echo in_array('pnurse', $admin['admin_permission']) ? 'pnurse' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('pstore', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_20" data="pstore">
										<i class="icon16_common icon_checkbox"></i>
                                        店铺收益
									</label>
									<input type="hidden" id="permission_20" name="admin_permission[]" value="<?php echo in_array('pstore', $admin['admin_permission']) ? 'pstore' : '';?>" />
                                    <label class="frm_checkbox_label<?php echo in_array('ppension', $admin['admin_permission']) ? ' selected' : '';?>" field="permission_21" data="ppension">
										<i class="icon16_common icon_checkbox"></i>
                                        养老机构收益
									</label>
									<input type="hidden" id="permission_21" name="admin_permission[]" value="<?php echo in_array('ppension', $admin['admin_permission']) ? 'ppension' : '';?>" />
                                </div>
                                <!--往下从29开始   28在上面-->
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
			ajaxpost('mall_admin', '', '', 'onerror');
		}
		
		$(function() {
			$(".frm_checkbox_label").on('click', function() {
				var field = $(this).attr('field');
				var data = $(this).attr('data');										  
				if($(this).hasClass("selected")) {
					$("#"+field).val('');
					$(this).removeClass("selected");
				} else {
					$("#"+field).val(data);
					$(this).addClass("selected");
				}
			});
		});
	</script>
<?php include(template('common_footer'));?>