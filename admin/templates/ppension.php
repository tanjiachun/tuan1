<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">财务<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('pnurse', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=pnurse">阿姨收益</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('pstore', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=pstore">店铺收益</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('ppension', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a class="active" href="admin.php?act=ppension">机构收益</a>
                        </dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
        	<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>机构收益</strong></label>
               	<form action="admin.php" method="get" id="search_form">
                	<input type="hidden" name="act" value="ppension" />
                    <div class="page_filter_right">
                        <ul>
                            <li>
                                <span class="frm_input_box search append">
                                    <a href="javascript:void(0);" class="frm_input_append">
                                        <i class="icon16_common icon_search" onclick="$('#search_form').submit();"></i>
                                    </a>
                                    <input type="text" id="search_name" name="search_name" placeholder="机构名称" class="frm_input" value="<?php echo $search_name;?>">
                                </span>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        	<div class="goods_content">
				<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                <table class="goods_table">
                    <thead>
                    	<th style="width:100px;">机构名称</th>
                        <th>机构地址</th>
                        <th>机构房间数</th>
						<th>总金额</th>
                        <th>冻结金额</th>
                        <th>可用金额</th>                        
                        <th class="th_opr">操作</th>
                    </thead>
                    <tbody>
                    	<?php foreach($pension_list as $key => $value) { ?>
                        <tr>
                            <td><?php echo $value['pension_name'];?></td>
                            <td><?php echo $value['pension_areainfo'].$value['pension_address'];?></td>
                            <td><?php echo $room_list[$value['pension_id']];?></td>
							<td><?php echo $value['plat_amount'];?></td>
                            <td><?php echo $value['pool_amount'];?></td>
                            <td><span id="available_<?php echo $value['pension_id'];?>"><?php echo $value['available_amount'];?></span></td>
                            <td class="td_opr" id="pension_<?php echo $value['pension_id'];?>">
                            	<a class="btn btn_primary" onclick="cash_open('<?php echo $value['pension_id'];?>', '<?php echo $value['pension_name'];?>');">汇款</a>
                                <p><a href="admin.php?act=ppension&op=profit&pension_id=<?php echo $value['pension_id'];?>">收入明细</a></p>
                            </td>
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
    <div class="common_tips" style="display:none;" id="tip">
		<div class="tipcontent error_tip" id="tip_content"></div>
	</div>
    <script type="text/javascript" src="admin/templates/js/ppension.js"></script>
<?php include(template('common_footer'));?>