<?php include(template('common_header'));?>
	<div class="conwp">
        <div class="voucher-wrap">
            <div class="voucher-head">
                <h1>退换货</h1>
                <p>订单单号：<a href="javascript:;"><?php echo $order['order_sn'];?></a><?php echo date('Y-m-d H:i', $order['add_time']);?></p>
            </div>
            <?php if(empty($order_goods)) { ?>
                <div class="m-success-tip">
                    <div class="tip-inner">
                        <span class="tip-icon">
                            <i class="iconfont icon-info"></i>
                        </span>
                        <h3 class="tip-title">没有需要退换货的商品~</h3>
                        <div class="tip-hint">感谢你对养老到家的支持，我们将为您提供更好的服务<a href="index.php?act=order">返回订单列表 &gt;</a></div>
                    </div>
                </div>
            <?php } else { ?>
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
                                <div class="voucher-form-label">选择服务</div>
                                <div class="voucher-form-value">
                                    <div class="m-tagbox tag-box">
                                        <a href="javascript:void(0)" field_value="" class="tag-item tag-checked">正常<i class="t-check"></i></a>
                                        <a href="javascript:void(0)" field_value="return" class="tag-item">退货<i class="t-check"></i></a>
                                        <a href="javascript:void(0)" field_value="exchange" class="tag-item">换货<i class="t-check"></i></a>
                                        <input type="hidden" id="return_type_<?php echo $value['rec_id'];?>" name="return_type[<?php echo $value['rec_id'];?>]" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="voucher-form-item clearfix">
                                <div class="voucher-form-label">提交数量</div>
                                <div class="voucher-form-value">
                                    <div class="p-quantity">
                                        <div class="quantity-form">
                                            <input type="text" id="return_goodsnum_<?php echo $value['rec_id'];?>" name="return_goodsnum[<?php echo $value['rec_id'];?>]" value="1" onKeyUp="changeQuantity(this);" max_stock="<?php echo $value['goods_num']-$value['goods_returnnum'];?>" style="text-align: center; width: 60px;">
                                        </div>
                                    </div>
                                    <p class="t-tips">您最多可提交数量为<?php echo $value['goods_num']-$value['goods_returnnum'];?>个</p>
                                </div>
                            </div>
                            <div class="voucher-form-item clearfix">
                                <div class="voucher-form-label">问题描述</div>
                                <div class="voucher-form-value">
                                    <div class="f-textarea">
                                        <textarea id="return_content_<?php echo $value['rec_id'];?>" name="return_content[<?php echo $value['rec_id'];?>]" placeholder="填写退换原因，不超过500字"></textarea>
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
                    <a href="javascript:returnsubmit();" class="btn-submit">提交</a><span class="return-success"></span>
                </div>
            <?php } ?>
        </div>
    </div>
    <script type="text/javascript">
		var file_name = 'store';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="templates/js/imageupload.js"></script>
    <script type="text/javascript" src="templates/js/profile/order.js"></script>
<?php include(template('common_footer'));?>