<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">店铺<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('store', $this->admin['admin_permission'])) { ?>
                        <dd>
						    <a href="admin.php?act=store">店铺管理</a>
						</dd>	
						<?php } ?>
						<?php if(in_array('sclass', $this->admin['admin_permission'])) { ?>
						<dd>
							<a class="active" href="admin.php?act=sclass">店铺分类</a>
						</dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>店铺分类</strong></label>
            </div>  
            <form action="admin.php?act=sclass" method="post" class="content-form" id="mall_store_class">
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
												<th style="width:50px">显示顺序</th>
                                            	<th>分类名称</th>
                                                <th class="th_opr" style="width:50px">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="class_value">
                                            <?php foreach($class_list as $key => $value) { ?>
                                            <tr>
												<td><input class="form_input" name="class_sort[]" type="text" value="<?php echo $value['class_sort'];?>" style="width:30px;"></td>
                                            	<td><input type="hidden" name="class_id[]" value="<?php echo $value['class_id'];?>"  /><input class="form_input input_xxlarge" name="class_name[]" type="text" value="<?php echo $value['class_name'];?>"></td>
                                                <td><a href="javascript:;" class="item_del">删除</a></td>
                                            </tr>
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
		var file_name = 'plat';
		function checksubmit() {
			ajaxpost('mall_store_class', '', '', 'onerror');
		}
		$(function() {
			$(".menu_add").click(function() {
				var html = '';
				html += '<tr>';
				html += '<td><input class="form_input" name="class_sort[]" type="text" value="" style="width:30px;"></td>';
                html += '<td><input type="hidden" name="class_id[]" value=""  /><input class="form_input input_xxlarge" name="class_name[]" type="text" value=""></td>';
                html += '<td><a href="javascript:;" class="item_del">删除</a></td>';
                html += '</tr>';
				$("#class_value").append(html);
			});
			
			$("#class_value").on('click', '.item_del', function() {
				$(this).parent().parent().remove();
			});
		});
	</script>
    <script type="text/javascript" src="admin/templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="admin/templates/js/index.js"></script>
<?php include(template('common_footer'));?>