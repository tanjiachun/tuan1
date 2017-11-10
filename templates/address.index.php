<?php foreach($address_list as $key => $value) { ?>
<div class="address-item">
	<h1>
		<strong><?php echo $value['true_name'];?></strong>
		<?php if(!empty($value['address_default'])) { ?>
		<em class="address-default">默认地址</em>
		<?php } ?>
		<span class="address-del" address_id="<?php echo $value['address_id'];?>"><i class="iconfont icon-fork"></i></span>
	</h1>
	<div class="address-body">
		<div class="item clearfix">
			<span class="label">联系人：</span>
			<div class="fl"><?php echo $value['true_name'];?></div>
		</div>
		<div class="item clearfix">
			<span class="label">电话：</span>
			<div class="fl"><?php echo $value['mobile_phone'];?></div>
		</div>
		<div class="item clearfix">
			<span class="label">所在地区：</span>
			<div class="fl"><?php echo $value['area_info'];?></div>
		</div>
		<div class="item clearfix">
			<span class="label">地址：</span>
			<div class="fl"><?php echo $value['address_info'];?></div>
		</div>
		<div class="extra">
			<?php if(empty($value['address_default'])) { ?>
			<a class="address-default" href="javascript:;" address_id="<?php echo $value['address_id'];?>">设为默认</a>
			<?php } ?>
			<a class="cont-edit address-edit" href="javascript:;" address_id="<?php echo $value['address_id'];?>">编辑</a>
		</div>
	</div>
</div>
<?php } ?>