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
                            <a class="active" href="admin.php?act=red">红包设置</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('oldage', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=oldage">养老金设置</a>
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
            	<label class="frm_checkbox_label"><strong>编辑红包</strong></label>
				<div class="page_filter_right">
            		<a href="admin.php?act=red" class="btn btn_default">返回</a>
            	</div>
            </div>
            <form action="admin.php?act=red&op=edit" method="post" class="content-form" id="mall_red">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            	<input type="hidden" id="red_t_id" name="red_t_id" value="<?php echo $red_t_id;?>"  />
				<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                            <div class="control_group">
                                <div class="control_label">红包名称：</div>
                                <div class="controls">
                                    <input type="text" name="red_t_title" class="form_input input_xxlarge" value="<?php echo $red_template['red_t_title'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">红包面额：</div>
                                <div class="controls">
                                    <input type="text" name="red_t_price" class="form_input input_xxlarge" value="<?php echo $red_template['red_t_price'];?>">
                                </div>
                            </div>
							<div class="control_group">
                                <div class="control_label">红包有效期：</div>
                                <div class="controls">
                                	<label<?php echo $red_template['red_t_period_type'] == 'duration' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_period_type" data="duration"></i>
										按时长
									</label>
									<label<?php echo $red_template['red_t_period_type'] == 'timezone' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_period_type" data="timezone"></i>
										按区间
									</label>
									<div class="sku_group" id="duration"<?php echo $red_template['red_t_period_type'] == 'timezone' ? ' style="display:none;"' : '';?>>
										<input type="text" id="red_t_days" name="red_t_days" class="form_input" value="<?php echo $red_template['red_t_days'];?>">天
									</div>
									<div class="sku_group" id="timezone"<?php echo $red_template['red_t_period_type'] == 'duration' ? ' style="display:none;"' : '';?>>
										<input type="text" id="red_t_starttime" name="red_t_starttime" class="form_input" value="<?php echo date('Y-m-d', $red_template['red_t_starttime']);?>"> - <input type="text" id="red_t_endtime" name="red_t_endtime" class="form_input" value="<?php echo date('Y-m-d', $red_template['red_t_endtime']);?>">
									</div>
								</div>
								<input type="hidden" id="red_t_period_type" name="red_t_period_type" value="<?php echo $red_template['red_t_period_type'];?>">
                            </div>
							<div class="control_group">
                                <div class="control_label">适用对象：</div>
                                <div class="controls">
                                    <label<?php echo $red_template['red_t_cate_id'] == '0' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_cate_id" data="0"></i>
										全场通用
									</label>
									<label<?php echo $red_template['red_t_cate_id'] == '1' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_cate_id" data="1"></i>
										阿姨看护专区
									</label>
									<label<?php echo $red_template['red_t_cate_id'] == '2' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_cate_id" data="2"></i>
										养老商品专区
									</label>
									<label<?php echo $red_template['red_t_cate_id'] == '3' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_cate_id" data="3"></i>
										养老机构专区
									</label>
                                </div>
								<input type="hidden" id="red_t_cate_id" name="red_t_cate_id" value="<?php echo $red_template['red_t_cate_id'];?>">
                            </div>
							<div class="control_group">
                                <div class="control_label">使用门槛：</div>
                                <div class="controls">
									满 <input type="text" name="red_t_limit" class="form_input input_small" value="<?php echo $red_template['red_t_limit'];?>"> 可以使用
                                </div>
                            </div>
							<div class="control_group">
                                <div class="control_label">发放总数：</div>
                                <div class="controls">
                                    <input type="text" name="red_t_total" class="form_input input_xlarge" value="<?php echo $red_template['red_t_total'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">红包类型：</div>
                                <div class="controls">
                                    <label<?php echo $red_template['red_t_type'] == 'new' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_type" data="new"></i>
										新用户红包
									</label>
									<label<?php echo $red_template['red_t_type'] == 'activity' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_type" data="activity"></i>
										活动红包
									</label>
									<label<?php echo $red_template['red_t_type'] == 'reward' ? ' class="selected"' : '';?>>
										<i class="icon16_common icon_radio" field="red_t_type" data="reward"></i>
										奖励红包
									</label>
									<div class="sku_group" id="activity"<?php echo in_array($red_template['red_t_type'], array('new', 'reward')) ? ' style="display:none;"' : '';?>>
										活动时间： <input type="text" id="red_rule_starttime" name="red_rule_starttime" class="form_input" value="<?php echo date('Y-m-d', $red_template['red_rule_starttime']);?>"> - <input type="text" id="red_rule_endtime" name="red_rule_endtime" class="form_input" value="<?php echo date('Y-m-d', $red_template['red_rule_endtime']);?>">
									</div>
									<div class="sku_group" id="reward"<?php echo in_array($red_template['red_t_type'], array('new', 'activity')) ? ' style="display:none;"' : '';?>>
										消费满 <input type="text" name="red_t_amount" class="form_input input_small" value="<?php echo $red_template['red_t_amount'];?>"> 赠送
									</div>
									<input type="hidden" id="red_t_type" name="red_t_type" value="<?php echo $red_template['red_t_type'];?>">
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
			ajaxpost('mall_red', '', '', 'onerror');
		}
		
		$(function() {
			$('.icon_radio').on('click', function() {
				var data = $(this).attr('data');
				if(data == 'duration') {			
					$('#duration').show();
					$('#timezone').hide();
				} else if(data == 'timezone') {
					$('#duration').hide();
					$('#timezone').show();
				} else if(data == 'new') {
					$('#activity').hide();
					$('#reward').hide();
				} else if(data == 'activity') {
					$('#activity').show();
					$('#reward').hide();
				} else if(data == 'reward') {
					$('#activity').hide();
					$('#reward').show();
				}
			});
			
			$('#red_t_starttime').datetimepicker({
				  lang : "ch",
				  format : "Y-m-d",
				  step : 1,
				  timepicker : false,
				  yearStart : 2000,
				  yearEnd : 2050,
				  todayButton : false
			});
			
			$('#red_t_endtime').datetimepicker({
				  lang : "ch",
				  format : "Y-m-d",
				  step : 1,
				  timepicker : false,
				  yearStart : 2000,
				  yearEnd : 2050,
				  todayButton : false
			});
			
			$('#red_rule_starttime').datetimepicker({
				  lang : "ch",
				  format : "Y-m-d",
				  step : 1,
				  timepicker : false,
				  yearStart : 2000,
				  yearEnd : 2050,
				  todayButton : false
			});
			
			$('#red_rule_endtime').datetimepicker({
				  lang : "ch",
				  format : "Y-m-d",
				  step : 1,
				  timepicker : false,
				  yearStart : 2000,
				  yearEnd : 2050,
				  todayButton : false
			});
		});	
	</script>
    <link href="admin/templates/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="admin/templates/js/jquery.datetimepicker.js"></script>
<?php include(template('common_footer'));?>