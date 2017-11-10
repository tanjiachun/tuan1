<?php if(!empty($spec_list)) { ?>
<?php foreach($spec_list as $key => $value) { ?>
<div class="form-item clearfix">
	<input type="hidden" name="spec_name[<?php echo $value['sp_id'];?>]" value="<?php echo $value['sp_name']?>" />
	<label><?php echo $value['sp_name']?>：</label>
	<div class="form-item-value">
		<div class="sku-group">
			<div class="sku-group-cont" id="spec_group_<?php echo $key;?>">
				<?php foreach($spec_value_list[$value['sp_id']] as $k => $val) { ?>
					<input type="hidden" name="spec_value[<?php echo $value['sp_id'];?>][<?php echo $val['sp_value_id'];?>]" id="sp_value_<?php echo $val['sp_value_id'];?>" value=""/>
					<span class="check" sp_value_id="<?php echo $val['sp_value_id'];?>" sp_value_name="<?php echo $val['sp_value_name'];?>"><i class="iconfont icon-type"></i><?php echo $val['sp_value_name'];?></span>
				<?php } ?>
				<?php if($value['sp_format'] == 'image'){?>
				<div class="sku-image-file" style="display:none;">
					<div class="sku-image-item sku-image-hd">
						<div class="sku-image-name">颜色</div>
						<div class="sku-image-value">图片(无图片可不填)</div>
					</div>
					<?php foreach($spec_value_list[$value['sp_id']] as $k => $val) { ?>
					<div class="sku-image-item spec_image_<?php echo $val['sp_value_id'];?>" style="display:none;">
						<input type="hidden" name="spec_image[<?php echo $val['sp_value_id'];?>]" id="spec_image_<?php echo $val['sp_value_id'];?>" value=""/>
						<div class="sku-image-name"><?php echo $val['sp_value_name'];?></div>
						<div class="sku-image-value">
							<div class="sku-atom-list">
								<ul>
									<li id="show_image_<?php echo $val['sp_value_id'];?>">
                                    	<div class="sku-atom-file">
											<i class="iconfont icon-camera"></i>
											<span class="img-upload"><input type="file" id="file_<?php echo $val['sp_value_id'];?>" name="file_<?php echo $val['sp_value_id'];?>" hidefocus="true" maxlength="0" onchange="image_upload('file_<?php echo $val['sp_value_id'];?>', '<?php echo $val['sp_value_id'];?>');"></span>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="form-item clearfix" id="spec_setting" style="display:none;">
	<label>库存配置：</label>
	<div class="form-item-value">
		<div class="sku-group">
			<table class="table-sku-stock">
				<thead>
					<tr>
						<?php foreach($spec_list as $key => $value) { ?>
                        <th><?php echo $value['sp_name'];?></th>
                        <?php } ?>
                        <th class="th_price">价格（元）</th>
                        <th class="th_stock">库存<em>*</em></th>
						<th>商品货号</th>
					</tr>
				</thead>
				<tbody id="spec_table"></tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	var spec_group_checked = [<?php for($i=0; $i<$sign_i; $i++) { echo $i+1 == $sign_i ? "''" : "'',";}?>];
	var str = '';
	var V = new Array();
	<?php for($i=0; $i<$sign_i; $i++) { ?>
	var spec_group_checked_<?php echo $i;?> = new Array();
	<?php } ?>

	function into_array(){
		<?php for($i=0; $i<$sign_i; $i++) { ?>
		spec_group_checked_<?php echo $i;?> = new Array();
		$('#spec_group_<?php echo $i;?>').find('.active').each(function(){
			i = $(this).attr('sp_value_id');
			v = $(this).attr('sp_value_name');
			spec_group_checked_<?php echo $i;?>[spec_group_checked_<?php echo $i;?>.length] = [v,i];
		});
		spec_group_checked[<?php echo $i;?>] = spec_group_checked_<?php echo $i;?>;
		<?php } ?>
	}

	function spec_show(){
		$('input[name="goods_price"]').attr('readonly','readonly').css('background', '#E7E7E7 none');
		$('input[name="goods_storage"]').attr('readonly','readonly').css('background', '#E7E7E7 none');	
		$('#spec_setting').show();
		str = '<tr>';
		<?php $this->recursionspec(0, $sign_i);?>
		if(str == '<tr>') {
			$('input[name="goods_price"]').removeAttr('readonly').css('background', '');
			$('input[name="goods_storage"]').removeAttr('readonly').css('background', '');
			$('#spec_setting').hide();
		}
		$('#spec_table').empty().html(str).find('input[type="text"]').each(function() {
			var s = $(this).attr('mall_type');
			try {
				$(this).val(V[s])
			} catch(ex) {
				$(this).val('')
			};
			if($(this).attr('data_type') == 'price' && $(this).val() == '') {
				$(this).val($('input[name="goods_price"]').val());
			}
		});
		storage_sum();
		price_interval();
	}
</script>
<?php } ?>