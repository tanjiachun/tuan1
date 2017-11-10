<?php include(template('common_header'));?>
	<div class="conwp">
        <div class="voucher-wrap">
            <div class="voucher-head">
                <h1>订单评价</h1>
                <p>订单单号：<a href="javascript:;"><?php echo $order['order_sn'];?></a><?php echo date('Y-m-d H:i', $order['add_time']);?></p>
            </div>
            <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            <input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id;?>" />
            <?php foreach($order_goods as $key => $value) { ?>
            <div class="voucher-body clearfix">
				<input type="hidden" id="rec_id_<?php echo $value['rec_id'];?>" name="rec_id[]" value="<?php echo $value['rec_id'];?>" />
				<div class="voucher-goods">
                    <div class="comment-goods">
                        <div class="p-img">
                            <a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>" target="_blank"><img src="<?php echo $value['goods_image'];?>"></a>
                        </div>
                        <div class="p-name">
                            <a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>" target="_blank"><?php echo $value['goods_name'];?></a>
                        </div>
						<?php if(!empty($value['spec_info'])) { ?>
                        <div class="p-attr"><?php echo $value['spec_info'];?></div>
						<?php } ?>
                        <div class="p-price"><strong>￥<?php echo $value['goods_price'];?></strong></div>
                    </div>
                </div>
                <div class="voucher-info">
                    <div class="voucher-form">
						<div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">满意度评分</div>
                            <div class="voucher-form-value radio-box">
                            	<label class="radio" field_value="good" field_key="comment_level_<?php echo $value['rec_id'];?>">
                                    <i class="iconfont icon-type"></i>
                                    好评
                                </label>
                                <label class="radio" field_value="middle" field_key="comment_level_<?php echo $value['rec_id'];?>">
                                    <i class="iconfont icon-type"></i>
                                    中评
                                </label>
                                <label class="radio" field_value="bad" field_key="comment_level_<?php echo $value['rec_id'];?>">
                                    <i class="iconfont icon-type"></i>
                                    差评
                                </label>
                            </div>
                            <input type="hidden" id="comment_level_<?php echo $value['rec_id'];?>" name="comment_level[<?php echo $value['rec_id'];?>]" value="" />
                        </div>
                    	<div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">评价分数</div>
                            <div class="voucher-form-value radio-box">
                            	<fieldset class="rating">
                                    <input type="radio" id="g<?php echo $value['rec_id'];?>_hstar5" name="comment_score[<?php echo $value['rec_id'];?>]" value="5" />
                                    <label class="full" for="g<?php echo $value['rec_id'];?>_hstar5"></label>
                                    <input type="radio" id="g<?php echo $value['rec_id'];?>_hstar4" name="comment_score[<?php echo $value['rec_id'];?>]" value="4" />
                                    <label class="full" for="g<?php echo $value['rec_id'];?>_hstar4"></label>
                                    <input type="radio" id="g<?php echo $value['rec_id'];?>_hstar3" name="comment_score[<?php echo $value['rec_id'];?>]" value="3" />
                                    <label class="full" for="g<?php echo $value['rec_id'];?>_hstar3"></label>
                                    <input type="radio" id="g<?php echo $value['rec_id'];?>_hstar2" name="comment_score[<?php echo $value['rec_id'];?>]" value="2" />
                                    <label class="full" for="g<?php echo $value['rec_id'];?>_hstar2"></label>
                                    <input type="radio" id="g<?php echo $value['rec_id'];?>_hstar1" name="comment_score[<?php echo $value['rec_id'];?>]" value="1" checked="checked" />
                                    <label class="full" for="g<?php echo $value['rec_id'];?>_hstar1"></label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">评价描述</div>
                            <div class="voucher-form-value">
                                <div class="f-textarea">
                                    <textarea id="comment_content_<?php echo $value['rec_id'];?>" name="comment_content[<?php echo $value['rec_id'];?>]" placeholder="快分享你的感受吧~"></textarea>
                                    <div class="textarea-ext">还可输入500字</div>
                                </div>
                                <div class="picture-list voucher-list">
                                    <ul class="clearfix">
                                        <li id="show_image_<?php echo $value['rec_id'];?>">
                                            <a class="add-goods" href="javascript:;">
                                                <i class="iconfont icon-camera"></i>
                                                <span class="img-upload"><input type="file" id="file_<?php echo $value['rec_id'];?>" name="file_<?php echo $value['rec_id'];?>" field_id="<?php echo $value['rec_id'];?>" hidefocus="true" maxlength="0" mall_type="image" mode="multi"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            <?php } ?>
            <div class="f-btnbox">
                <a href="javascript:commentsubmit();" class="btn-submit">提交</a><span class="return-success"></span>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		var file_name = 'store';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="templates/js/imageupload.js"></script>
    <script type="text/javascript" src="templates/js/profile/order.js"></script>
<?php include(template('common_footer'));?>