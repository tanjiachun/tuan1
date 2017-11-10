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
            	<a class="btn btn_primary" href="admin.php?act=admin&op=add">新增管理员</a>
               	<form action="admin.php" method="get" id="search_form">
                	<input type="hidden" name="act" value="admin" />
                    <div class="page_filter_right">
                        <ul>
                            <li>
                                <span class="frm_input_box search append">
                                    <a href="javascript:void(0);" class="frm_input_append">
                                        <i class="icon16_common icon_search" onclick="$('#search_form').submit();"></i>
                                    </a>
                                    <input type="text" id="search_name" name="search_name" placeholder="用户名" class="frm_input" value="<?php echo $search_name;?>">
                                </span>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        	<div class="goods_content">
                <table class="goods_table">
                    <thead>
                    	<th style="width:16px;"></th>
                        <th style="width:200px;">用户名</th>
                        <th>创建时间</th>
                        <th class="th_opr">操作</th>
                    </thead>
                    <tbody>
                    	<?php foreach($admin_list as $key => $value) { ?>
                        <tr>
                            <td>
                            	<?php if(empty($value['admin_system'])) { ?>
                                <label class="frm_checkbox_label checkitem" data="<?php echo $value['admin_id'];?>">
                                    <i class="icon16_common icon_checkbox"></i>
                                </label>
                                <?php } ?>
                            </td>
                            <td><?php echo $value['admin_name'];?></td>
                           	<td><?php echo date('Y-m-d H:i', $value['add_time']);?></td>
                            <td class="td_opr">
                                <a href="admin.php?act=admin&op=edit&admin_id=<?php echo $value['admin_id'];?>">编辑</a>
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
                        <a href="javascript:;" class="btn btn_default batchbutton" name="admin_ids" uri="admin.php?act=admin&op=del" confirm="您确实要删除吗?">删除</a>
                    </div>
                    <?php if(!empty($multi)) { ?>
                    <div class="pagination_wrp">
                        <div class="pagination">
                            共有<?php echo $count;?>条记录&nbsp;每页<?php echo $perpage;?>条
                            <?php echo $multi;?>
                        </div>
                    </div>
					<?php } ?>                
                </div>				
            </div>
        </div>
    </div>
<?php include(template('common_footer'));?>