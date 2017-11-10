<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">运营<span></span></a>
                    <dl style="display:block">
						<?php if(in_array('card', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=card">会员设置</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('red', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=red">红包设置</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('oldage', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a class="active" href="admin.php?act=oldage">养老金设置</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('package', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=package">充值套餐</a>
                        </dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>养老金设置</strong></label>
            </div>    
            <form action="admin.php?act=oldage" method="post" class="content-form" id="mall_oldage">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            	<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                        	<div class="control_group">
                                <div class="control_label">消费奖励：</div>
                                <div class="controls">
                                    每消费1元，可获取 <input type="text" name="first_oldage_rate" class="form_input input_small" value="<?php echo $setting['first_oldage_rate'];?>"> 元养老金
                                </div>
                            </div>
							<div class="control_group">
                                <div class="control_label">评论奖励：</div>
                                <div class="controls">
                                    每发布1条，可获取 <input type="text" name="second_oldage_rate" class="form_input input_small" value="<?php echo $setting['second_oldage_rate'];?>"> 元养老金
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
			ajaxpost('mall_oldage', '', '', 'onerror');
		}
	</script>
<?php include(template('common_footer'));?>