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
            	<a class="btn btn_primary" href="admin.php?act=gclass&op=add">新增分类</a>
            </div>  
            <div class="goods_content">
                <table class="goods_table">
                    <thead>
                    	<th style="width:16px;"></th>
                        <th style="width:80px">显示顺序</th>
                        <th>分类名称</th>
                        <th class="th_opr">操作</th>
                    </thead>
                    <tbody>
                    	<?php foreach($class_list as $key => $value) { ?>
                        <tr>
                        	<td>
                                <label class="frm_checkbox_label checkitem" data="<?php echo $value['class_id'];?>">
                                    <i class="icon16_common icon_checkbox"></i>
                                </label>
                            </td>
                        	<td><?php echo $value['class_sort'];?></td>
                            <td><?php echo $value['class_name'];?></td>
                            <td class="td_opr">
                                <a href="admin.php?act=gclass&op=edit&class_id=<?php echo $value['class_id'];?>">编辑</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
				<div class="goods_tool">
                    <div class="opr_tool">
                        <label class="frm_checkbox_label checkall">
                            <i class="icon16_common icon_checkbox"></i>
                           全选 
                        </label>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="class_ids" uri="admin.php?act=gclass&op=del" confirm="您确实要删除吗?">删除</a>
                    </div>
                </div>
            </div>
		</div>
	</div>
<?php include(template('common_footer'));?>