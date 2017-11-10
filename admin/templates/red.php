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
                <a class="btn btn_primary" href="admin.php?act=red&op=add">发布红包</a>
                <form action="admin.php" method="get" id="search_form">
                	<input type="hidden" name="act" value="red" />
                    <div class="page_filter_right">
                        <ul>
                            <li>
                                <span class="frm_input_box search append">
                                    <a href="javascript:void(0);" class="frm_input_append">
                                        <i class="icon16_common icon_search" onclick="$('#search_form').submit();"></i>
                                    </a>
                                    <input type="text" id="red_t_title" name="article_title" placeholder="红包名称" class="frm_input" value="<?php echo $red_t_title;?>">
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
                        <th>红包名称</th>
                        <th style="width:100px;">红包面额</th>
                        <th style="width:100px;">领取人数</th>
                        <th style="width:100px;">使用人数</th>
                        <th style="width:150px;">红包类型</th>
                        <th class="th_opr">操作</th>
                    </thead>
                    <tbody>
                    	<?php foreach($red_template_list as $key => $value) { ?>
                        <tr>
                            <td>
                                <label class="frm_checkbox_label checkitem" data="<?php echo $value['red_t_id'];?>">
                                    <i class="icon16_common icon_checkbox"></i>
                                </label>
                            </td>
                            <td><?php echo $value['red_t_title'];?>（<?php echo $red_cate_array[$value['red_t_cate_id']];?>）</td>
                            <td><?php echo $value['red_t_price'];?></td>
                            <td><?php echo $value['red_t_giveout'];?></td>
                            <td><?php echo $value['red_t_used'];?></td>
                            <td><?php echo $red_type_array[$value['red_t_type']];?></td>
                            <td class="td_opr">
                                <a href="admin.php?act=red&op=edit&red_t_id=<?php echo $value['red_t_id'];?>">编辑</a> | <a href="admin.php?act=red&op=record&red_t_id=<?php echo $value['red_t_id'];?>&state=giveout">明细</a>
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
                        <a href="javascript:;" class="btn btn_default batchbutton" name="red_t_ids" uri="admin.php?act=red&op=del" confirm="您确实要删除吗?">删除</a>
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