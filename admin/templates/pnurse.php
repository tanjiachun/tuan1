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
            <div class="finance_count">
                <div class="center-title clearfix">
                    <strong>总收入：</strong><strong><span class="red">￥<?php echo priceformat($total_income);?></span></strong>
                    <strong>订单收入：</strong><strong><span class="red">￥<?php echo priceformat($book_income);?></span></strong>
                    <strong>充值收入：</strong><strong><span class="red">￥<?php echo priceformat($recharge_income);?></span></strong>
                    <strong>保证金收入：</strong><strong><span class="red">￥<?php echo priceformat($bail_income);?></span></strong>
                </div>
                <div class="center-title clearfix">
                    <strong>总支出：</strong><strong><span class="green">￥<?php echo priceformat($total_defray);?></span></strong>
                    <strong>提现支出：</strong><strong><span class="green">￥<?php echo priceformat($deposit_defray);?></span></strong>
                    <strong>退款支出：</strong><strong><span class="green">￥<?php echo priceformat($refund_defray);?></span></strong>
                    <strong>团豆豆支出：</strong><strong><span class="green">￥<?php echo priceformat($coin_defray);?></span></strong>
                </div>
                <div class="center-title clearfix">
                    <strong>日收入：</strong><strong><span class="red">￥<?php echo priceformat($day_income);?></span></strong>
                    <strong>月收入：</strong><strong><span class="red">￥<?php echo priceformat($mouth_income);?></span></strong>
                    <strong>年收入：</strong><strong><span class="red">￥<?php echo priceformat($year_income);?></span></strong>
                </div>
                <div class="center-title clearfix">
                    <strong>日支出：</strong><strong><span class="green">￥<?php echo priceformat($day_defray);?></span></strong>
                    <strong>月支出：</strong><strong><span class="green">￥<?php echo priceformat($mouth_defray);?></span></strong>
                    <strong>年支出：</strong><strong><span class="green">￥<?php echo priceformat($year_defray);?></span></strong>
                </div>
            </div>
            <div class="page_filter">
               	<form action="admin.php" method="get" id="search_form">
                	<input type="hidden" name="act" value="pnurse" />
                    <div class="page_filter_right">
                        <ul>
                            <li>
                                <span class="frm_input_box search append">
                                    <a href="javascript:void(0);" class="frm_input_append">
                                        <i class="icon16_common icon_search" onclick="$('#search_form').submit();"></i>
                                    </a>
                                    <input type="text" id="search_name" name="search_name" placeholder="阿姨姓名/电话" class="frm_input" value="<?php echo $search_name;?>">
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
                        <th></th>
                        <th >收入/支出方式</th>
                        <th width="250">订单号</th>
                        <th>会员名称</th>
                        <th>机构名称</th>
                        <th>保姆名称</th>
                        <th>收支状态</th>
                        <th>收支金额</th>
                        <th>记录时间</th>
                    </thead>
                    <tbody>
                    	<?php foreach($finance_list as $key => $value) { ?>
                        <tr>
                            <td></td>
                           	<td><?php echo $type_array[$value['finance_type']];?></td>
                            <td><?php echo $book_list[$value['book_id']]['book_sn'];?></td>
                            <td><?php echo $member_list[$value['member_id']]['member_truename'];?></td>
                            <td><?php echo $agent_list[$value['agent_id']]['agent_name'];?></td>
                            <td><?php echo $nurse_list[$value['nurse_id']]['nurse_name'];?></td>
                            <td>
                                <?php if($value['finance_state']==0) { ?>
                                    收入
                                <?php } else { ?>
                                    支出
                                <?php } ?>
                            </td>
                            <td><span class="<?php echo $value['markclass'];?>"><?php echo $value['mark'];?>￥<?php echo $value['finance_amount'];?></span></td>
                            <td><?php echo empty($value['finance_time']) ? date('Y-m-d') : date('Y-m-d', $value['finance_time']);?></td>
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
    <script type="text/javascript" src="admin/templates/js/pnurse.js"></script>
<?php include(template('common_footer'));?>