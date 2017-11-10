<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">运营<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('card', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a class="active" href="admin.php?act=card">会员设置</a>
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
                            <a href="admin.php?act=package">充值套餐</a>
                        </dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>会员设置</strong></label>
            </div>  
            <form action="admin.php?act=card" method="post" class="content-form" id="mall_card">
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
                                            	<th>会员名称</th>
                                                <th>会员图标</th>
                                                <th>积分</th>
												<th>折扣</th>
                                                <th class="th_opr" style="width:50px">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="card_value">
                                        	<?php $i = 0;?>
                                            <?php foreach($card_list as $key => $value) { ?>
                                            <tr id="menu_<?php echo $i;?>">
                                            	<td><input type="hidden" name="card_id[<?php echo $i;?>]" value="<?php echo $value['card_id'];?>"  /><input class="form_input input_xxlarge" name="card_name[<?php echo $i;?>]" type="text" value="<?php echo $value['card_name'];?>"></td>
                                                <td>
                                                    <div class="picture_list">
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <?php if(!empty($value['card_icon'])) { ?>
                                                                    <img src="<?php echo $value['card_icon'];?>" id="show_image_<?php echo $i;?>">
                                                                    <?php } else { ?>
                                                                    <img src="admin/templates/images/default.jpg" id="show_image_<?php echo $i;?>">
                                                                    <?php } ?>
                                                                </a>
                                                                <span class="close_modal" href="javascript:;" onclick="image_del(<?php echo $i;?>);">×</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                    				<div class="picture_list add_list">
                                        				<ul>            
                                            				<li>
                                                                <a class="add_goods" href="javascript:;">
                                                                    <span class="img_upload">
	                                                                    <input type="file" name="file_<?php echo $i;?>" id="file_<?php echo $i;?>" size="1" hidefocus="true" maxlength="0" onchange="upload('file_<?php echo $i;?>', '<?php echo $i;?>')">
                                                                    </span>
                                                                    <div class="upload-button">图片上传</div>
                                                                 </a>
                                                                <input type="hidden" name="card_icon[<?php echo $i;?>]" id="image_<?php echo $i;?>" value="<?php echo $value['card_icon'];?>"  /> 
                                                            </li>
                                        				</ul>
                                    				</div>
                                                </td>
                                                <td><input class="form_input input_xlarge" name="card_predeposit[<?php echo $i;?>]" type="text" value="<?php echo $value['card_predeposit'];?>"></td>
												<td><input class="form_input input_xlarge" name="discount_rate[<?php echo $i;?>]" type="text" value="<?php echo $value['discount_rate'];?>"></td>
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
		var file_name = 'plat';
		function checksubmit() {
			ajaxpost('mall_card', '', '', 'onerror');
		}
		$(function() {
			var i = '<?php echo $i;?>';
			$(".menu_add").click(function() {
				var html = '';
				html += '<tr id="menu_'+i+'">';
                html += '<td><input type="hidden" name="card_id['+i+']" value=""  /><input class="form_input input_xxlarge" name="card_name['+i+']" type="text" value=""></td>';
				html += '<td><div class="picture_list"><ul><li><a href="javascript:;"><img src="admin/templates/images/default.jpg" id="show_image_'+i+'"></a><span class="close_modal" href="javascript:;" onclick="image_del('+i+');">×</span></li></ul></div><div class="picture_list add_list"><ul><li><a class="add_goods" href="javascript:;"><span class="img_upload"><input type="file" name="file_'+i+'" id="file_'+i+'" size="1" hidefocus="true" maxlength="0"  onchange="upload(\'file_'+i+'\', \''+i+'\')"></span><div class="upload-button">图片上传</div></a><input type="hidden" name=card_icon['+i+']" id="image_'+i+'" value=""  /></li></ul></div></td>';
				html += '<td><input class="form_input input_xlarge" name="card_predeposit['+i+']" type="text" value=""></td>';
				html += '<td><input class="form_input input_xlarge" name="discount_rate['+i+']" type="text" value=""></td>';
                html += '<td><a href="javascript:;" class="item_del" key="'+i+'">删除</a></td>';
                html += '</tr>';
				$("#card_value").append(html);
				i++;
			});
			
			$("#card_value").on('click', '.item_del', function() {
				var key = $(this).attr("key");
				$(this).parent().parent().remove();
				$(".menu_"+key).remove();
			});
		});
	</script>
    <script type="text/javascript" src="admin/templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="admin/templates/js/index.js"></script>
<?php include(template('common_footer'));?>