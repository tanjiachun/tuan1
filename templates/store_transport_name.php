<div class="modal-wrap w-400" id="name-box">
    <div class="modal-hd">
    	<div class="Validform-checktip Validform-wrong m-tip"></div>
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
        <input type="hidden" id="extend_type" name="extend_type" value="<?php echo $extend_type;?>" />
        <h4>修改</h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="cont-modal">
            <div class="cont-item">
                <label><?php echo $name_array[$extend_type];?></label>
                <input type="text" id="extend_name" name="extend_name" style="width: 100px;" value="<?php echo $extend_name;?>">
            </div>
        </div>
    </div>
    <div class="modal-ft">
         <a class="btn btn-default" onclick="Custombox.close();">取消</a>
         <a class="btn btn-primary" onclick="namesubmit();">确定</a>
    </div>
</div>