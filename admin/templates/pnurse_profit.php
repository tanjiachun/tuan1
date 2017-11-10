<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">财务<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('pnurse', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a class="active" href="admin.php?act=pnurse">阿姨收益</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('pstore', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=pstore">店铺收益</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('ppension', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=ppension">机构收益</a>
                        </dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
            <div class="page_filter">
            	<label class="frm_checkbox_label"><strong><?php echo $nurse['nurse_name'];?>的收益</strong></label>
            </div>
        	<div class="goods_content">
                <table class="goods_table">
                    <thead>
                        <th width="200">时间</th>
						<th width="100">收支</th>
						<th width="100">状态</th>
						<th>备注</th>
                    </thead>
                    <tbody>
                    	<?php foreach($profit_list as $key => $value) { ?>
						<tr>
							<td><span class="gray"><?php echo date('Y-m-d H:i', $value['add_time']);?></span></td>
							<td><?php echo $value['mark'];?>￥<?php echo $value['profit_amount'];?></td>
							<td><?php echo !empty($value['is_freeze']) ? '冻结' : '可用';?></td>
							<td><?php echo $value['profit_desc'];?></td>
						</tr>
						<?php } ?>
                    </tbody>
                </table>
				<div class="goods_tool">
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