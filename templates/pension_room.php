<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>养老机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=pension_bespoke">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=pension_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">机构配套</h3>
					<ul>
						<li><a class="active" href="index.php?act=pension_room">房间管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a href="index.php?act=pension_profile">机构信息</a></li>
						<li><a href="index.php?act=pension_profile&op=near">机构周边</a></li>
                        <li><a href="index.php?act=pension_profile&op=equipment">机构设施</a></li>
                        <li><a href="index.php?act=pension_profile&op=service">机构服务</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>房间管理</strong>
					<span class="pull-right">
						<a href="index.php?act=pension_room&op=add"  class="btn btn-primary">添加房间</a>
					</span>
				</div>
				<div class="orderlist">
					<div class="orderlist-head clearfix">
						<ul>
							<li>
								<a>机构房间</a>
							</li>
						</ul>
					</div>
					<table class="tb-void">
						<thead>
							<tr>
								<th width="80">房间图片</th>
								<th>房间名称</th>
								<th width="100">房间价格</th>
								<th width="100">排序</th>
								<th width="100">操作</th>
							</tr>
						</thead>
						<tbody class="room-list">
							<?php foreach($room_list as $key => $value) { ?>
							<tr id="room_<?php echo $value['room_id'];?>">
								<td>
									<div class="td-inner clearfix">
											<div class="item-pic">
												<img src="<?php echo $value['room_image'];?>">
											</div>
										</div>
								</td>
								<td><?php echo $value['room_name'];?></td>
								<td><?php echo $value['room_price'];?></td>
								<td><?php echo $value['room_sort'];?></td>
								<td>
									<a class="bluelink" href="index.php?act=pension_room&op=edit&room_id=<?php echo $value['room_id'];?>">编辑</a>&nbsp;&nbsp;<a class="room-del" href="javascript:;" room_id="<?php echo $value['room_id'];?>">删除</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php echo $multi;?>
			</div>
		</div>
	</div>
	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
	<div class="modal-wrap w-400" id="del-box" style="display:none;">
    	<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="del_id" name="del_id" value="" />
		<div class="modal-bd">			
			<div class="m-success-tip">
				<div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
					<h3 class="tip-message">你确定要删除吗？</h3>
				</div>
			</div>
		</div>
		<div class="modal-ft tc">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="delsubmit();">确定</a>
		</div>
	</div>
	<div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/profile/pension_room.js"></script>
<?php include(template('common_footer'));?>