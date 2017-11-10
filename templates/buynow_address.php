<?php foreach($address_list as $key => $value) { ?>
<li>
	<div class="consignee-item<?php echo !empty($value['address_default']) ? ' active' : '';?>">
		<span><?php echo $value['true_name'];?></span>
		<b></b>
	</div>
	<div class="consignee-detail">
		<span class="addr-name"><?php echo $value['true_name'];?></span>
		<span class="addr-info"><?php echo $value['area_info'].$value['address_info'];?></span>
		<span class="addr-tel"><?php echo $value['mobile_phone'];?></span>
		<?php if(!empty($value['address_default'])) { ?>
		<span class="addr-default">默认地址</span>
		<?php } ?>
	</div>	
	<div class="op-btns">
		<?php if(empty($value['address_default'])) { ?>
		<a class="edit-consignee bluelink address-default" href="javascript:;" address_id="<?php echo $value['address_id'];?>">设为默认地址</a>
		<?php } ?>
		<a class="edit-consignee bluelink address-edit" href="javascript:;" address_id="<?php echo $value['address_id'];?>">编辑</a>
		<?php if(empty($value['address_default'])) { ?>
		<a class="del-consignee bluelink address-del" href="javascript:;" address_id="<?php echo $value['address_id'];?>">删除</a>
		<?php } ?>
	</div>
</li>
<?php } ?>