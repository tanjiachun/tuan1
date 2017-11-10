<div class="modal-wrap w-700" id="address-box">
    <div class="modal-hd">
    	<div class="Validform-checktip Validform-wrong m-tip"></div>
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
        <input type="hidden" id="address_id" name="address_id" value="<?php echo $address['address_id'];?>" />
        <h4>编辑收货地址</h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="cont-modal">
            <div class="cont-item">
                <label><em>*</em>联系人</label>
                <input id="true_name" name="true_name" type="text" value="<?php echo $address['true_name'];?>">
            </div>
            <div class="cont-item">
                <label><em>*</em>电话</label>
                <input id="mobile_phone" name="mobile_phone" type="text" value="<?php echo $address['mobile_phone'];?>">
            </div>
            <div class="cont-item">
                <label><em>*</em>所在地区</label>
                <select id="province_id" name="province_id" onchange="selectprovince();">
                    <option value="">-省份-</option>
                    <?php foreach($province_list as $key => $value) { ?>
                    <option value="<?php echo $value['district_id'];?>"<?php echo $address['province_id'] == $value['district_id'] ? ' selected="selected"' : '';?>><?php echo $value['district_name'];?></option>
                    <?php } ?>
                </select>
                <select id="city_id" name="city_id" onchange="selectcity();">
                    <option value="">-城市-</option>
                    <?php foreach($city_list as $key => $value) { ?>
                    <option value="<?php echo $value['district_id'];?>"<?php echo $address['city_id'] == $value['district_id'] ? ' selected="selected"' : '';?>><?php echo $value['district_name'];?></option>
                    <?php } ?>
                </select>
                <select id="area_id" name="area_id"<?php echo empty($address['area_id']) ? ' style="display:none;"' : '';?>>
                    <option value="">-州县-</option>
                    <?php foreach($area_list as $key => $value) { ?>
                    <option value="<?php echo $value['district_id'];?>"<?php echo $address['area_id'] == $value['district_id'] ? ' selected="selected"' : '';?>><?php echo $value['district_name'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="cont-item">
                <label><em>*</em>详细地址</label>
                <textarea id="address_info" name="address_info" rows="5"><?php echo $address['address_info'];?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-ft">
         <a class="btn btn-default" onclick="Custombox.close();">取消</a>
         <a class="btn btn-primary" onclick="editsubmit();">保存收货地址</a>
    </div>
</div>    