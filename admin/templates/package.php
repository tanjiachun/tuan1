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
                            <a href="admin.php?act=oldage">养老金设置</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('package', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a class="active" href="admin.php?act=package">充值套餐</a>
                        </dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>充值套餐</strong></label>
            </div>  
            <form action="admin.php?act=package" method="post" class="content-form" id="mall_package">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            	<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                        	<div class="control_group">
                                <div class="sku_group">
                                    <table class="table_sku_stock">
                                        <thead>
                                            <tr>
                                            	<th>充值金额</th>
                                                <th>奖励金额</th>
                                                <th class="th_opr" style="width:50px">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="package_value">
                                        	<?php $i = 0;?>
                                            <?php foreach($package_list as $key => $value) { ?>
                                            <tr id="menu_<?php echo $i;?>">
                                            	<td><input type="hidden" name="package_id[<?php echo $i;?>]" value="<?php echo $value['package_id'];?>"  /><input class="form_input input_xxlarge" name="package_amount[<?php echo $i;?>]" type="text" value="<?php echo $value['package_amount'];?>"></td>
                                                <td>
                                                    <input class="form_input" name="discount_amount[<?php echo $i;?>]" type="text" value="<?php echo $value['discount_amount'];?>">
                                                </td>
                                               	<td><a href="javascript:;" class="item_del" key="<?php echo $i;?>">删除</a></td>
                                            </tr>
                                            <?php $i++;?>
                                            <?php } ?>
                                        </tbody>
                                        <tr>
                                            <td colspan="5"><a href="javascript:;" class="btn btn_default menu_add">+ 增加</a></td>
                                        </tr>                                       
                                    </table>
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
			ajaxpost('mall_package', '', '', 'onerror');
		}
		$(function() {
			var i = '<?php echo $i;?>';
			$('.menu_add').click(function() {
				var html = '';
				html += '<tr id="menu_'+i+'">';
                html += '<td><input type="hidden" name="package_id['+i+']" value=""  /><input class="form_input input_xxlarge" name="package_amount['+i+']" type="text" value=""></td>';
				html += '<td><input class="form_input" name="discount_amount['+i+']" type="text" value=""></td>';
                html += '<td><a href="javascript:;" class="item_del" key="'+i+'">删除</a></td>';
                html += '</tr>';
				$('#package_value').append(html);
				i++;
			});
			
			$('#package_value').on('click', '.item_del', function() {
				var key = $(this).attr("key");
				$(this).parent().parent().remove();
				$('.menu_'+key).remove();
			});
		});
	</script>
<?php include(template('common_footer'));?>