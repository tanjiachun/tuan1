<div class="modal-wrap w-700" id="address-box">
    <div class="modal-hd">
        <div class="Validform-checktip Validform-wrong m-tip"></div>
        <h4>发票详情展示</h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <?php if($invoice['invoice_type']=='个人') { ?>
        <div class="modal-bd">
            <div class="cont-modal">
                <div class="cont-item">
                    <label><em>*</em>订单编号</label>
                    <?php if(empty($book['book_type'])) { ?>
                        <span><?php echo $book['book_sn'] ?></span>
                    <?php } else { ?>
                        <span>
                            <?php for($i=0;$i<count($invoice['book_sn_content']);$i++) { ?>
                                <?php if($i>=1) { ?>
                                    <label for=""></label> <?php echo $invoice['book_sn_content'][$i] ?> <br>
                                <?php } else { ?>
                                    <?php echo $invoice['book_sn_content'][$i] ?> <br>
                                <?php } ?>
                            <?php } ?>
                        </span>
                    <?php } ?>
                </div>
                <div class="cont-item">
                    <label><em>*</em>付款时间</label>
                    <span><?php echo date('Y-m-d H:i', $book['payment_time'])?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>发票抬头</label>
                    <span><?php echo $invoice['invoice_title'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>发票明细</label>
                    <span><?php echo $invoice['invoice_content'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>收件人</label>
                    <span><?php echo $invoice['invoice_membername'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>联系号码</label>
                    <span><?php echo $book['member_phone'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>详细地址</label>
                    <span><?php echo $invoice['invoice_areainfo'] ?> <?php echo $invoice['invoice_address'] ?></span>
                </div>
            </div>
        </div>
    <?php } else if($invoice['invoice_type']=='单位') { ?>
        <div class="modal-bd">
            <div class="cont-modal">
                <div class="cont-item">
                    <label><em>*</em>订单编号</label>
                    <span><?php echo $book['book_sn'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>付款时间</label>
                    <span><?php echo date('Y-m-d H:i', $invoice['add_time'])?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>单位名称</label>
                    <span><?php echo $invoice['unit_name'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>纳税人识别码</label>
                    <span><?php echo $invoice['invoice_code'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>收件人</label>
                    <span><?php echo $invoice['invoice_unit_membername'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>联系号码</label>
                    <span><?php echo $book['member_phone'] ?></span>
                </div>
                <div class="cont-item">
                    <label><em>*</em>详细地址</label>
                    <span><?php echo $invoice['invoice_areainfo'] ?> <?php echo $invoice['invoice_address'] ?></span>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="modal-ft">
        <a class="btn btn-default" onclick="Custombox.close();">关闭</a>
    </div>
</div>