<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <?php if(in_array('nurse', $this->admin['admin_permission'])) { ?>
                    <a class="active" href="javascript:;">阿姨管理<span></span></a>
                    <dl style="display:block">
                        <dd>
                            <a href="admin.php?act=nurse">阿姨列表</a>
                        </dd>
                        <dd>
                            <a href="admin.php?act=nurse&op=pending">等待审核</a>
                        </dd>
                    </dl>
                    <?php } ?>
                    <a class="active" href="javascript:;">阿姨设置<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('grade', $this->admin['admin_permission'])) { ?>
                        <dd>
						    <a href="admin.php?act=grade">阿姨等级</a>
						</dd>
						<?php } ?>
						<?php if(in_array('tag', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=tag">评论标签</a>
						</dd>
						<?php } ?>
                        <?php if(in_array('service', $this->admin['admin_permission'])) { ?>
						<dd>
							<a class="active" href="admin.php?act=service">阿姨服务</a>
						</dd>
						<?php } ?>
                        <?php if(in_array('nurse_revise', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a href="admin.php?act=nurse_revise">资料修改审核</a>
                            </dd>
                        <?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>阿姨服务</strong></label>
            </div>  
            <form action="admin.php?act=service" method="post" class="content-form" id="mall_service">
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
                                                <th>服务名称</th>
                                                <th>服务金额</th>
                                                <th class="th_opr" style="width:50px">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="service_value">
                                        	<?php $i = 0;?>
                                            <?php foreach($service_list as $key => $value) { ?>
                                            <tr id="menu_<?php echo $i;?>">
                                            	<td><input type="hidden" name="service_id[<?php echo $i;?>]" value="<?php echo $value['service_id'];?>"  /><input class="form_input" name="service_sort[<?php echo $i;?>]" type="text" value="<?php echo $value['service_sort'];?>" style="width:30px;"></td>
                                                <td>
                                                    <input class="form_input" name="service_name[<?php echo $i;?>]" type="text" value="<?php echo $value['service_name'];?>">
                                                </td>
                                                <td>
                                                    <input class="form_input input_xxlarge" name="service_price[<?php echo $i;?>]" type="text" value="<?php echo $value['service_price'];?>">
                                                </td>
                                                <td><a href="javascript:;" class="item_del" key="<?php echo $i;?>">删除</a></td>
                                            </tr>
                                            <?php $i++;?>
                                            <?php } ?>
                                        </tbody>
                                        <tr>
                                            <td colspan="4"><a href="javascript:;" class="btn btn_default menu_add">+ 增加</a></td>
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
			ajaxpost('mall_service', '', '', 'onerror');
		}
		$(function() {
			var i = '<?php echo $i;?>';
			$('.menu_add').click(function() {
				var html = '';
				html += '<tr id="menu_'+i+'">';
                html += '<td><input type="hidden" name="service_id['+i+']" value=""  /><input class="form_input" name="service_sort['+i+']" type="text" value="" style="width:30px;"></td>';
				html += '<td><input class="form_input" name="service_name['+i+']" type="text" value=""></td>';
				html += '<td><input class="form_input input_xxlarge" name="service_price['+i+']" type="text" value=""></td>';
                html += '<td><a href="javascript:;" class="item_del" key="'+i+'">删除</a></td>';
                html += '</tr>';
				$('#service_value').append(html);
				i++;
			});
			
			$('#service_value').on('click', '.item_del', function() {
				var key = $(this).attr("key");
				$(this).parent().parent().remove();
				$('.menu_'+key).remove();
			});
		});
	</script>
<?php include(template('common_footer'));?>