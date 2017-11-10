<div class="modal-wrap w-400" id="price-box">
    <div class="modal-hd">
    	<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
        <input type="hidden" id="goods_id" name="goods_id" value="<?php echo $goods_id;?>" />
        <h4>修改</h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="cont-modal">
        	<?php foreach($spec_value as $key => $value) { ?>
            <div class="cont-item">
                <label><?php echo $value['spec_goods_spec'];?></label>
                <input type="text" class="spec_goods_price" spec_id="<?php echo $value['spec_id'];?>" style="width: 100px;" value="<?php echo $value['spec_goods_price'];?>">
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="modal-ft">
         <a class="btn btn-default" onclick="Custombox.close();">取消</a>
         <a class="btn btn-primary" onclick="pricesubmit();">确定</a>
    </div>
</div>