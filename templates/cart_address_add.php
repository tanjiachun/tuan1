<div class="modal-wrap w-700" id="address-box">
    <div class="modal-hd">
    	<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
    	<h4>新增收货地址</h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="cont-modal">
            <div class="cont-item">
                <label><em>*</em>联系人</label>
                <input id="true_name" name="true_name" type="text">
            </div>
            <div class="cont-item">
                <label><em>*</em>电话</label>
                <input id="mobile_phone" name="mobile_phone" type="text">
            </div>
            <div class="cont-item">
                <label><em>*</em>所在地区</label>
                <select id="province_id" name="province_id" onchange="selectprovince();">
                    <option value="">-省份-</option>
                    <?php foreach($province_list as $key => $value) { ?>
                    <option value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></option>
                    <?php } ?>
                </select>
                <select id="city_id" name="city_id" onchange="selectcity();">
                    <option value="">-城市-</option>
                </select>
                <select id="area_id" name="area_id">
                    <option value="">-州县-</option>
                </select>
            </div>
            <div class="cont-item">
                <label><em>*</em>详细地址</label>
                <textarea id="address_info" name="address_info" rows="5"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-ft">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="addsubmit();">保存收货地址</a>
    </div>
</div>    