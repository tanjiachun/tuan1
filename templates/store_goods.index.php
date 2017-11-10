<div class="modal-wrap w-700" id="goods-box">
	<div class="modal-hd">
		<h4>选择指定商品</h4>
		<span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
	</div>
	<div class="modal-bd">
		<div class="sku-group">
			<table class="table-sku-stock">
				<thead>
					<tr>
						<th>商品</th>
						<th class="th-price">操作</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($goods_list as $key => $value) { ?>
					<tr>
						<td>
							<div class="td-inner clearfix">
								<div class="item-pic">
									<a href="javascript:;"><img src="<?php echo $value['goods_image'];?>"></a>
								</div>
								<div class="item-info">
									<a href="javascript:;"><?php echo $value['goods_name'];?></a>
								</div>
							</div>
						</td>
						<td id="goods_op_<?php echo $value['goods_id'];?>">
							<?php if(in_array($value['goods_id'], $goods_ids)) { ?>
							<span class="btn btn-default goods-cancel" onclick="cancelgoods('<?php echo $value['goods_id'];?>', '<?php echo $value['goods_image'];?>', '<?php echo $value['goods_name'];?>')">取消</span>
							<?php } else { ?>
							<span class="btn btn-primary goods-select" onclick="selectgoods('<?php echo $value['goods_id'];?>', '<?php echo $value['goods_image'];?>', '<?php echo $value['goods_name'];?>')">选取</span>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal-ft">
		 <a class="btn btn-default" onclick="Custombox.close();">关闭</a>
	</div>
</div>