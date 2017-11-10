<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>家政人员中心</a></h1>
                    <h3 class="no1">简历管理</h3>
					<ul>
						<li><a href="index.php?act=nurse_resume">我的简历</a></li>
					</ul>
					<h3 class="no2">工作管理</h3>
					<ul>
						<li><a href="index.php?act=nurse_book">我的工作</a></li>
					</ul>
					<h3 class="no3">资产中心</h3>
					<ul>
						<li><a class="active" href="index.php?act=nurse_profit">我的收益</a></li>
					</ul>
					<h3 class="no4">账户设置</h3>
					<ul>
						<li><a href="index.php?act=nurse_password">密码修改</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
                <div class="center-title clearfix">
                    <strong>总收益：</strong><strong><span class="red">￥<?php echo $this->nurse['plat_amount'];?></span></strong>
                    <strong>可用金额：</strong><strong><span class="red">￥<?php echo $this->nurse['available_amount'];?></span></strong>
                    <strong>冻结金额：</strong><strong><span class="red">￥<?php echo $this->nurse['pool_amount'];?></span></strong>
                </div>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=nurse_profit"<?php echo empty($state) ? ' class="active"' : '';?>>全部</a>
                            </li>
                            <li>
                                <a href="index.php?act=nurse_profit&state=income"<?php echo $state=='income' ? ' class="active"' : '';?>>收入</a>
                            </li>
                            <li>
                                <a href="index.php?act=nurse_profit&state=expend"<?php echo $state=='expend' ? ' class="active"' : '';?>>支出</a>
                            </li>
							<li>
                                <a href="index.php?act=nurse_profit&state=freeze"<?php echo $state=='freeze' ? ' class="active"' : '';?>>冻结</a>
                            </li>
                        </ul>
                        <div class="pull-right">
							<div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="nurse_profit">
									<input type="hidden" name="state" value="<?php echo $state;?>">
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
                                <th width="100">收支</th>
                                <th width="100">状态</th>
                                <th>备注</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php foreach($profit_list as $key => $value) { ?>
                            <tr>
                                <td><span class="gray"><?php echo date('Y-m-d H:i', $value['add_time']);?></span></td>
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
	</script>
    <link href="templates/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="templates/js/jquery.datetimepicker.js"></script>
<?php include(template('common_footer'));?>