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
        <div id="main" class="main have-tab">
            <div class="fixed_content">
                <div class="tab">
                    <ul>
                        <li<?php echo $state=='giveout' ? ' class="active"' : '';?>><a href="admin.php?act=red&op=record&red_t_id=<?php echo $red_t_id;?>&state=giveout">已领取</a></li>
                        <li<?php echo $state=='outused' ? ' class="active"' : '';?>><a href="admin.php?act=red&op=record&red_t_id=<?php echo $red_t_id;?>&state=outused">未使用</a></li>
						<li<?php echo $state=='used' ? ' class="active"' : '';?>><a href="admin.php?act=red&op=record&red_t_id=<?php echo $red_t_id;?>&state=used">已使用</a></li>
                    </ul>
                </div>
            </div>
        	<div class="goods_content">
                <table class="goods_table">
                    <thead>
                    	<th width="80">用户</th>
						<th width="200">优惠券名称</th>
						<th width="80">领取时间</th>
						<th width="80">面值</th>
						<th width="80">状态</th>
                    </thead>
                    <tbody>
                    	<?php foreach($red_list as $key => $value) { ?>
                        <tr>
                            <td><?php echo $member_list[$value['member_id']]['member_phone'];?></td>
                            <td><?php echo $value['red_title'];?></td>
                            <td><?php echo date('Y-m-d H:i', $value['red_addtime']);?></td>
                            <td><?php echo $value['red_price'];?></td>
                            <td><?php echo $value['red_state'] == '1' ? '已使用' : '未使用';?></td>
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