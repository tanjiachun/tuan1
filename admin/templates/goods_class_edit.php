<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">商品<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('goods', $this->admin['admin_permission'])) { ?>
                        <dd>
						    <a href="admin.php?act=goods">商品管理</a>
						</dd>
						<?php } ?>
						<?php if(in_array('gclass', $this->admin['admin_permission'])) { ?>
						<dd>
							<a class="active" href="admin.php?act=gclass">商品分类</a>
						</dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>编辑分类</strong></label>
				<div class="page_filter_right">
            		<a href="admin.php?act=gclass" class="btn btn_default">返回</a>
            	</div>
            </div>    
            <form action="admin.php?act=gclass&op=edit" method="post" class="content-form" id="mall_class">
            	<input type="hidden" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
				<input type="hidden" id="class_id" name="class_id" value="<?php echo $class['class_id'];?>" />
            	<div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                            <div class="control_group">
                                <div class="control_label">分类名称：</div>
                                <div class="controls">
                                    <input type="text" name="class_name" class="form_input input_xxlarge" value="<?php echo $class['class_name'];?>">
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">排序：</div>
                                <div class="controls">
                                    <input type="text" name="class_sort" class="form_input input_xxlarge" value="<?php echo $class['class_sort'];?>">
                                    <p class="help_desc">请填写自然数。规格列表将会根据排序进行由小到大排列显示。</p>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">关联品牌：</div>
                                <div class="controls">
                                    <div class="sku_group">
                                        <table class="table_sku_stock">
                                            <thead>
                                                <tr>
                                                    <th class="th_stock">排序</th>
                                                    <th>品牌名称</th>
                                                    <th class="th_opr">操作</th>
                                                </tr>
                                            </thead>
                                            <tbody id="brand_value">
												<?php foreach($brand_list as $key => $value) { ?>
                                                <tr>													
                                                    <td><input type="hidden" name="brand_id[]" value="<?php echo $value['brand_id'];?>" /><input class="form_input input_xlarge" name="brand_sort[]" type="text" value="<?php echo $value['brand_sort'];?>"></td>
                                                    <td><input class="form_input input_xlarge" style="width:200px !important;" name="brand_name[]" type="text" value="<?php echo $value['brand_name'];?>"></td>
                                                    <td><a href="javascript:;" class="item_del">删除</a></td>													
                                                </tr>
												<?php } ?>
                                            </tbody>
                                            <tr>
                                                <td colspan="4"><a href="javascript:;" class="btn btn_default" id="brand_add">+ 增加品牌</a></td>
                                            </tr>                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">商品类型：</div>
                                <div class="controls">
                                    <div class="sku_group">
                                        <table class="table_sku_stock">
                                            <thead>
                                                <tr>
                                                    <th class="th_stock">排序</th>
                                                    <th>类型名称</th>
                                                    <th class="th_opr">操作</th>
                                                </tr>
                                            </thead>
                                            <tbody id="type_value">
												<?php foreach($type_list as $key => $value) { ?>
                                                <tr>													
                                                    <td><input type="hidden" name="type_id[]" value="<?php echo $value['type_id'];?>" /><input class="form_input input_xlarge" name="type_sort[]" type="text" value="<?php echo $value['type_sort'];?>"></td>
                                                    <td><input class="form_input input_xlarge" style="width:200px !important;" name="type_name[]" type="text" value="<?php echo $value['type_name'];?>"></td>
                                                    <td><a href="javascript:;" class="item_del">删除</a></td>													
                                                </tr>
												<?php } ?>
                                            </tbody>
                                            <tr>
                                                <td colspan="4"><a href="javascript:;" class="btn btn_default" id="type_add">+ 增加类型</a></td>
                                            </tr>                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="control_group">
                                <div class="control_label">属性值：</div>
                                <div class="controls">
                                    <div class="sku_group">
                                        <table class="table_sku_stock">
                                            <thead>
                                                <tr>
                                                    <th class="th_stock">排序</th>
                                                    <th>属性名称</th>
                                                    <th style="width:450px;">属性可选值</th>
                                                    <th class="th_opr">操作</th>
                                                </tr>
                                            </thead>
                                            <tbody id="attr_value">
												<?php $i = 0;?>
												<?php foreach($attr_list as $key => $value) { ?>
                                                <tr>
                                                    <td><input type="hidden" name="attr_id[<?php echo $i;?>]" value="<?php echo $value['attr_id'];?>" /><input class="form_input input_xlarge" name="attr_sort[<?php echo $i;?>]" type="text" value="<?php echo $value['attr_sort'];?>"></td>
                                                    <td><input class="form_input input_xlarge" style="width:200px !important;" name="attr_name[<?php echo $i;?>]" type="text" value="<?php echo $value['attr_name'];?>"></td>
                                                    <td>
                                                    	<div class="sku_atom_list">
                                                            <ul>
                                                                <?php foreach($value['attr_value'] as $subkey => $subvalue) { ?><li><input type="text" name="attr_value_name[<?php echo $i;?>][]" class="form_input input_xlarge" value="<?php echo $subvalue;?>"></li><?php } ?><li><a href="javascript:;" class="btn btn_default attr_value_add" index="<?php echo $i;?>">添加</a></li>
                                                            </ul>
                                                        </div>    
                                                    </td>
                                                    <td><a href="javascript:;" class="item_del">删除</a></td>
                                                </tr>
												<?php $i++;?>
												<?php } ?>
                                            </tbody>
                                            <tr>
                                                <td colspan="4"><a href="javascript:;" class="btn btn_default" id="attr_add">+ 增加属性值</a></td>
                                            </tr>                                      
                                        </table>
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
    <script type="text/javascript">
		function checksubmit() {
			ajaxpost('mall_class', '', '', 'onerror');
		}
		$(function() {
			$('#brand_add').on('click', function() {
				var html = '<tr>';
					html += '<td><input type="hidden" name="brand_id[]" value="0" /><input class="form_input input_xlarge" name="brand_sort[]" type="text"></td>';
					html += '<td><input class="form_input input_xlarge" style="width:200px !important;" name="brand_name[]" type="text"></td>';
					html += '<td><a href="javascript:;" class="item_del">删除</a></td>';
					html += '</tr>';
				$('#brand_value').append(html);
			});
			
			$('#brand_value').on('click', '.item_del', function() {
				$(this).parent().parent().remove();	
			});
			
			$('#type_add').on('click', function() {
				var html = '<tr>';
					html += '<td><input type="hidden" name="type_id[]" value="0" /><input class="form_input input_xlarge" name="type_sort[]" type="text"></td>';
					html += '<td><input class="form_input input_xlarge" style="width:200px !important;" name="type_name[]" type="text"></td>';
					html += '<td><a href="javascript:;" class="item_del">删除</a></td>';
					html += '</tr>';
				$('#type_value').append(html);
			});
			
			$('#type_value').on('click', '.item_del', function() {
				$(this).parent().parent().remove();	
			});
			
			$('#attr_value').on('click', '.attr_value_add', function() {
				var index = $(this).attr('index');
				$(this).parent().before('<li><input type="text" name="attr_value_name['+index+'][]" class="form_input input_xlarge" value=""></li>');	
			});
			
			var i = 1;
			$('#attr_add').on('click', function() {
				var html = '<tr>';
					html += '<td><input type="hidden" name="attr_id['+i+']" value="0" /><input class="form_input input_xlarge" name="attr_sort['+i+']" type="text"></td>';
					html += '<td><input class="form_input input_xlarge" style="width:200px !important;" name="attr_name['+i+']" type="text"></td>';
					html += '<td><div class="sku_atom_list"><ul><li><input type="text" name="attr_value_name['+i+'][]" class="form_input input_xlarge" value=""></li><li><a href="javascript:;" class="btn btn_default attr_value_add" index="'+i+'">添加</a></li></ul></div></td>';
					html += '<td><a href="javascript:;" class="item_del">删除</a></td>';
					html += '</tr>';
				$('#attr_value').append(html);
				i++;
			});
			
			$('#attr_value').on('click', '.item_del', function() {
				$(this).parent().parent().remove();	
			});
		});
	</script>
<?php include(template('common_footer'));?>