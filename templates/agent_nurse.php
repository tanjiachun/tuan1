<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="index.php?act=agent&op=login"><i class="iconfont icon-user"></i>家政机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=agent_book">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=agent_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">家政人员中心</h3>
					<ul>
						<li><a class="active" href="index.php?act=agent_nurse">家政人员管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a href="index.php?act=agent_profile">机构信息</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>家政人员管理</strong>
					<span class="pull-right">
						<a class="btn btn-primary" href="index.php?act=agent_nurse&op=add" style="padding:5px 20px;">添加简历</a>
					</span>
				</div>
				<div class="orderlist">
					<div class="orderlist-head clearfix">
						<ul>
							<li>
								<a href="index.php?act=agent_nurse&state=show"<?php echo $state=='show' ? ' class="active"' : '';?>>已运营</a>
							</li>
							<li>
								<a href="index.php?act=agent_nurse&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待审核</a>
							</li>
							<li>
								<a href="index.php?act=agent_nurse&state=unshow"<?php echo $state=='unshow' ? ' class="active"' : '';?>>已违规</a>
							</li>
                            <li>
                                状态筛选：
                                <select name="" id="stateSelect">
                                    <option value="0">全部</option>
                                    <option value="1">待雇佣</option>
                                    <option value="2">工作中</option>
                                    <option value="3">请假中</option>
                                    <option value="4">纠纷中</option>
                                    <option value="5">无状态</option>
                                </select>
							</li>
						</ul>
						<div class="pull-right">
							<div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="agent_nurse">
									<input type="hidden" name="state" value="<?php echo $state;?>">
									<input type="text" id="search_name" name="search_name" class="itxt" placeholder="家政人员姓名/电话" value="<?php echo $search_name;?>">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>	
							</div>
						</div>
					</div>					
					<div class="orderlist-body">
						<table class="order-tb">
							<thead>
								<tr>
									<th width="30"></th>
									<th width="80">姓名</th>
									<th width="80">类型</th>
									<th width="80">价格</th>
									<th width="150">工作地区</th>
									<th width="100">身份证号码</th>
									<th width="90">人员状态</th>
									<th width="50">操作</th>
								</tr>
							</thead>
                            <?php foreach($nurse_list as $key => $value) { ?>
							<tbody id="nurse_<?php echo $value['nurse_id'];?>" data="<?php echo $value['state_cideci'];?>">
								<tr class="tr-bd">
									<td>
										<span class="check checkitem" nurse_id="<?php echo $value['nurse_id'];?>"><i class="iconfont icon-type"></i></span>
									</td>
									<td><?php echo $value['nurse_name'];?></td>
									<td><?php echo $type_array[$value['nurse_type']];?></td>
                                    <?php if($value['nurse_type'] == 4) { ?>
                                        <td><?php echo $value['nurse_price'];?>/时</td>
                                    <?php } else { ?>
                                        <td><?php echo $value['nurse_price'];?>/月</td>
                                    <?php } ?>
									<td><?php echo $value['nurse_cityname'];?></td>
									<td><?php echo $value['nurse_cardid'];?></td>
                                    <td class="stateOrder" data="<?php echo $value['nurse_id'];?>">查看/修改</td>
									<td><a class="bluelink" href="index.php?act=agent_nurse&op=edit&nurse_id=<?php echo $value['nurse_id'];?>">编辑</a>&nbsp;&nbsp;<a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank">查看</a></td>
								</tr>
							</tbody>
                            <?php } ?>
                            <?php if(!empty($nurse_list)) { ?>
							<tbody>
								<tr class="tool-row">
									<td colspan="10">
										<span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
										<?php if($state == 'show') { ?>
										<a class="btn btn-default use-express nurse-unshow">违规下架</a>
										<?php } ?>
										<?php if($state == 'unshow') { ?>
										<a class="btn btn-default use-express nurse-show">审核通过</a>
										<?php } ?>
										<?php if($state == 'pending') { ?>
										<a class="btn btn-default use-express nurse-unshow">违规下架</a>
										<a class="btn btn-default use-express nurse-show">审核通过</a>
										<?php } ?>
									</td>
								</tr>
							</tbody>
                            <?php } ?>
						</table>
					</div>
					<?php echo $multi;?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
	<div class="modal-wrap w-400" id="unshow-box" style="display: none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="unshow_ids" name="unshow_ids" value="" />
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">您确定要下架吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			<a class="btn btn-default" onclick="Custombox.close();">取消</a>
			<a class="btn btn-primary" onclick="unshowsubmit();">确定</a>			
		</div>
	</div>
	<div class="modal-wrap w-400" id="show-box" style="display: none;">
		<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="show_ids" name="show_ids" value="" />
		<div class="modal-bd">
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">您确定要通过吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			<a class="btn btn-default" onclick="Custombox.close();">取消</a>
			<a class="btn btn-primary" onclick="showsubmit();">确定</a>			
		</div>
	</div>
    <div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
    <div id="state-box">
        <span class="nurse_name">111</span>
        <input type="radio" value="1" id="hunting" name="state"/><span>待雇佣</span>
        <input type="radio" value="2" id="working" name="state"/><span>工作中</span>
        <input type="radio" value="3" id="holiday" name="state"/><span>请假中</span>
        <input type="radio" value="4" id="trouble" name="state"/><span>纠纷中</span>
        <input type="radio" value="5" id="unknow" name="state"/><span>无状态</span>
        <a class="btn-quxiao">取消</a>
        <a class="reviseState">确定</a>
    </div>
	<link href="templates/css/zoomify.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="templates/js/zoomify.js"></script>
	<script type="text/javascript" src="templates/js/profile/agent_nurse.js"></script>
    <script type="text/javascript" src="admin/templates/js/get_status.js"></script>
<?php include(template('common_footer'));?>