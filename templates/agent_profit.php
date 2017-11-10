<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
    <link rel="stylesheet" href="templates/css/admin.css">
    <div class="agent_set_center_header">
        <div class="left" style="margin-top: 10px;">
            <a href="index.php">
                <img src="templates/images/logo.png">
            </a>
            <span style="font-size: 18px;font-weight: 500;margin-left: 5px;">机构管理平台</span>
        </div>
        <div class="left agent_message_show">
            <span>被关注 <?php echo $agent['agent_focusnum'] ?></span>
            <span>员工总数 <?php echo $nurse_count ?></span>
            <span>浏览数 <?php echo $agent['agent_viewnum'] ?></span>
            <span>累计交易 <?php echo $book_count ?></span>
        </div>
        <div class="left agent_code_show">
            <span>机构编号 <?php echo $agent['agent_id'] ?></span>
            <span>有<?php echo $question_count ?>个问题待回答 <a href="index.php?act=agent_question" style="background: #ff6905;color:#fff;padding:0 5px;">回答</a></span>
        </div>
    </div>
</div>
	<div class="conwp">
		<div class="user-main">
            <div id="agent_manage_sidebar" class="left">
                <div class="agent_manage_logo">
                    <img width="100px" height="100px" src="<?php echo $agent['agent_logo'] ?>" alt="">
                </div>
                <ul class="sidebar_list">
                    <li><a href="index.php?act=agent_center">首页编辑</a></li>
                    <li><a href="index.php?act=agent_question">机构问答</a></li>
                    <li><a href="index.php?act=agent_phone_set">手机设置</a></li>
                    <li class="staff_set_list">
                        <a class="list_show">员工管理</a>
                        <ul style="display: none;">
                            <li><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
                            <li><a href="index.php?act=agent_nurse_audit" style="font-size: 12px;">· 审核员工</a></li>
                            <li><a href="index.php?act=agent_nurse_add" style="font-size: 12px;">· 新建员工</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?act=agent_book">全部订单</a></li>
                    <li><a href="index.php?act=agent_evaluate">评价管理</a></li>
                    <li><a href="index.php?act=agent_refund">退款查询</a></li>
                    <li><a href="index.php?act=agent_marketing">营销管理</a></li>
                    <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_profit">财务中心</a></li>
                    <li><a href="index.php?act=agent_invoice">发票管理</a></li>
                </ul>
                <script>
                    $(".list_show").click(function () {
                        if(!$(".staff_set_list ul").is(":hidden")){
                            $(".staff_set_list ul").fadeOut();
                            $(".staff_set_list img").attr('src','templates/images/toBW.png');
                        }else{
                            $(".staff_set_list ul").fadeIn();
                            $(".staff_set_list img").attr('src','templates/images/toTopW.png');
                        }
                    })
                </script>
            </div>
			<div class="user-right">
                <div class="center-title clearfix">
                    <strong>总收益：</strong><strong><span class="red">￥<?php echo priceformat($this->agent['plat_amount']);?></span></strong>
                    <strong>退款：</strong><strong><span class="red">￥<?php echo priceformat($this->agent['plat_refund']);?></span></strong>
                    <strong>可用金额：</strong><strong><span class="red">￥<?php echo priceformat($this->agent['available_amount']);?></span></strong>
                    <strong>冻结金额：</strong><strong><span class="red">￥<?php echo priceformat($this->agent['pool_amount']);?></span></strong>
                    <span class="pull-right">
                        <a href="index.php?act=agent_cash&agent_id=<?php echo $agent['agent_id'] ?>" class="btn btn-line-primary">提现</a>
<!--                        <a href="index.php?act=recharge" class="btn btn-primary">充值</a>-->
                    </span>
                </div>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=agent_profit"<?php echo empty($state) ? ' class="active"' : '';?>>全部</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_profit&state=income"<?php echo $state=='income' ? ' class="active"' : '';?>>收入</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_profit&state=expend"<?php echo $state=='expend' ? ' class="active"' : '';?>>支出</a>
                            </li>
							<li>
                                <a href="index.php?act=agent_profit&state=freeze"<?php echo $state=='freeze' ? ' class="active"' : '';?>>冻结</a>
                            </li>
                        </ul>
                        <div class="pull-right">
							<div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="agent_profit">
									<input type="hidden" name="state" value="<?php echo $state;?>">
									<input type="text" id="nurse_name" name="nurse_name" class="itxt" placeholder="家政人员名称" value="<?php echo $nurse_name;?>" style="margin-right:5px;">
									<input type="text" id="start_time" name="start_time" class="itxt" placeholder="开始时间" value="<?php echo empty($start_time) ? '' : date('Y-m-d', $start_time);?>" style="margin-right:5px;">
                                    <input type="text" id="end_time" name="end_time" class="itxt" placeholder="结束时间" value="<?php echo empty($end_time) ? '' : date('Y-m-d', $end_time);?>">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>
							</div>
						</div>
                    </div>
                    <table class="tb-void">
                        <thead>
                            <tr>
                                <th width="200">时间</th>
								<th width="100">家政人员</th>
                                <th width="100">收支</th>
                                <th width="100">状态</th>
                                <th>备注</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php foreach($profit_list as $key => $value) { ?>
                            <tr>
                                <td><span class="gray"><?php echo date('Y-m-d H:i', $value['add_time']);?></span></td>
								<td><?php echo $nurse_list[$value['nurse_id']]['nurse_name'];?></td>
								<td><span class="<?php echo $value['markclass'];?>"><?php echo $value['mark'];?>￥<?php echo $value['profit_amount'];?></span></td>
                                <td><?php echo !empty($value['is_freeze']) ? '冻结' : '可用';?></td>
                                <td><?php echo $value['profit_desc'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $multi;?>
                </div>
            </div>    
		</div>
	</div>

    <script type="text/javascript">
		$(function() {
			$('#start_time').datetimepicker({
				  lang : "ch",
				  format : "Y-m-d",
				  step : 1,
				  timepicker : false,
				  yearStart : 2000,
				  yearEnd : 2050,
				  todayButton : false
			});
			
			$('#end_time').datetimepicker({
				  lang : "ch",
				  format : "Y-m-d",
				  step : 1,
				  timepicker : false,
				  yearStart : 2000,
				  yearEnd : 2050,
				  todayButton : false
			});
		});
        $(".staff_set_list span").click(function () {
            if(!$(".staff_set_list ul").is(":hidden")){
                $(".staff_set_list ul").fadeOut();
                $(".staff_set_list img").attr('src','templates/images/toBW.png');
            }else{
                $(".staff_set_list ul").fadeIn();
                $(".staff_set_list img").attr('src','templates/images/toTopW.png');
            }
        })
	</script>
    <link href="templates/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="templates/js/jquery.datetimepicker.js"></script>