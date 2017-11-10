<?php include(template('common_header'));?>
	<div class="conwp">
        <div class="voucher-wrap">
            <div class="voucher-head">
                <h1>机构评价</h1>
                <p>预定单号：<a href="javascript:;"><?php echo $bespoke['bespoke_sn'];?></a><?php echo date('Y-m-d H:i', $bespoke['add_time']);?></p>
            </div>
        	<div class="voucher-body clearfix">
				<div class="voucher-goods">
                    <div class="comment-goods">
                        <div class="p-img">
                            <a href="index.php?act=pension&pension_id=<?php echo $pension['pension_id'];?>" target="_blank"><img src="<?php echo $pension['pension_image'];?>"></a>
                        </div>
                        <div class="p-name">
                            <a href="index.php?act=pension&pension_id=<?php echo $pension['pension_id'];?>" target="_blank"><?php echo $room['room_name'];?></a>
                        </div>
                        <div class="p-attr"><?php echo $pension['pension_name'];?></div>
                        <div class="p-price"><strong>￥<?php echo $bespoke['bespoke_amount'];?></strong></div>
                    </div>
                </div>
                <div class="voucher-info">
                    <div class="voucher-form">
						<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            			<input type="hidden" id="bespoke_id" name="bespoke_id" value="<?php echo $bespoke_id;?>" />
						<div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">满意度评分</div>
                            <div class="voucher-form-value radio-box">
                            	<label class="radio" field_value="good" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    好评
                                </label>
                                <label class="radio" field_value="middle" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    中评
                                </label>
                                <label class="radio" field_value="bad" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    差评
                                </label>
                            </div>
                            <input type="hidden" id="comment_level" name="comment_level" value="" />
                        </div>
                    	<div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">评价分数</div>
                            <div class="voucher-form-value radio-box">
                            	<fieldset class="rating">
									<input type="radio" id="hstar5" name="comment_score" value="5" />
                                    <label class="full" for="hstar5"></label>
                                    <input type="radio" id="hstar4" name="comment_score" value="4" />
                                    <label class="full" for="hstar4"></label>
                                    <input type="radio" id="hstar3" name="comment_score" value="3" />
                                    <label class="full" for="hstar3"></label>
                                    <input type="radio" id="hstar2" name="comment_score" value="2" />
                                    <label class="full" for="hstar2"></label>
                                    <input type="radio" id="hstar1" name="comment_score" value="1" checked="checked" />
                                    <label class="full" for="hstar1"></label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">评价描述</div>
                            <div class="voucher-form-value">
                                <div class="f-textarea">
                                    <textarea id="comment_content" name="comment_content" placeholder="快分享你的感受吧~"></textarea>
                                    <div class="textarea-ext">还可输入500字</div>
                                </div>
                                <div class="picture-list voucher-list">
                                    <ul class="clearfix">
                                        <li id="show_image_0">
                                            <a class="add-goods" href="javascript:;">
                                                <i class="iconfont icon-camera"></i>
                                                <span class="img-upload"><input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="multi"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
           	<div class="f-btnbox">
                <a href="javascript:commentsubmit();" class="btn-submit">提交</a><span class="return-success"></span>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		var file_name = 'agent';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="templates/js/imageupload.js"></script>
    <script type="text/javascript" src="templates/js/profile/order_bespoke.js"></script>
<?php include(template('common_footer'));?>