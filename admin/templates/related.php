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
                            <a class="active" href="admin.php?act=related">相关认证</a>
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
            	<label class="frm_checkbox_label"><strong>相关认证</strong></label>
            </div>  
            <form action="admin.php?act=related" method="post" class="content-form" id="mall_link">
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
                                                <th>链接图片</th>
                                                <th>链接URL</th>
                                                <th class="th_opr" style="width:50px">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="link_value">
                                        	<?php $i = 0;?>
                                            <?php foreach($link_list as $key => $value) { ?>
                                            <tr id="menu_<?php echo $i;?>">
                                            	<td><input type="hidden" name="link_id[<?php echo $i;?>]" value="<?php echo $value['link_id'];?>"  /><input class="form_input" name="link_sort[<?php echo $i;?>]" type="text" value="<?php echo $value['link_sort'];?>" style="width:30px;"></td>
                                                <td>
                                                    <div class="picture_list">
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <?php if(!empty($value['link_image'])) { ?>
                                                                    <img src="<?php echo $value['link_image'];?>" id="show_image_<?php echo $i;?>">
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
                                                                <input type="hidden" name="link_image[<?php echo $i;?>]" id="image_<?php echo $i;?>" value="<?php echo $value['link_image'];?>"  /> 
                                                            </li>
                                        				</ul>
                                    				</div>
                                                </td>
                                                <td>
                                                    <input class="form_input input_xxlarge" name="link_url[<?php echo $i;?>]" type="text" value="<?php echo $value['link_url'];?>">
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
		var file_name = 'plat';
		function checksubmit() {
			ajaxpost('mall_link', '', '', 'onerror');
		}
		$(function() {
			var i = '<?php echo $i;?>';
			$('.menu_add').click(function() {
				var html = '';
				html += '<tr id="menu_'+i+'">';
                html += '<td><input type="hidden" name="link_id['+i+']" value=""  /><input class="form_input" name="link_sort['+i+']" type="text" value="" style="width:30px;"></td>';
				html += '<td><div class="picture_list"><ul><li><a href="javascript:;"><img src="admin/templates/images/default.jpg" id="show_image_'+i+'"></a><span class="close_modal" href="javascript:;" onclick="image_del('+i+');">×</span></li></ul></div><div class="picture_list add_list"><ul><li><a class="add_goods" href="javascript:;"><span class="img_upload"><input type="file" name="file_'+i+'" id="file_'+i+'" size="1" hidefocus="true" maxlength="0"  onchange="upload(\'file_'+i+'\', \''+i+'\')"></span><div class="upload-button">图片上传</div></a><input type="hidden" name=link_image['+i+']" id="image_'+i+'" value=""  /></li></ul></div></td>';
				html += '<td><input class="form_input input_xxlarge" name="link_url['+i+']" type="text" value=""></td>';
                html += '<td><a href="javascript:;" class="item_del" key="'+i+'">删除</a></td>';
                html += '</tr>';
				$('#link_value').append(html);
				i++;
			});
			
			$('#link_value').on('click', '.item_del', function() {
				var key = $(this).attr("key");
				$(this).parent().parent().remove();
				$('.menu_'+key).remove();
			});
		});
	</script>
    <script type="text/javascript" src="admin/templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="admin/templates/js/index.js"></script>
<?php include(template('common_footer'));?>