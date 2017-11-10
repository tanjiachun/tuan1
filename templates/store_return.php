<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>商家中心</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=store_order">订单管理</a></li>
						<li><a class="active" href="index.php?act=store_return">退换货</a></li>
						<li><a href="index.php?act=store_order&state=payment">待发货</a></li>
					</ul>
					<h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=store_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">商品中心</h3>
					<ul>
						<li><a href="index.php?act=store_goods&op=add">发布商品</a></li>
						<li><a href="index.php?act=store_goods">出售商品</a></li>
						<li><a href="index.php?act=store_goods&op=goods_unshow">仓库商品</a></li>
						<li><a href="index.php?act=store_spec">规格管理</a></li>
					</ul>
					<h3 class="no5">营销中心</h3>
					<ul>
						<li><a href="index.php?act=store_coupon">优惠券管理</a></li>
					</ul>
					<h3 class="no4">商家设置</h3>
					<ul>
						<li><a href="index.php?act=store_profile">商家信息</a></li>
						<li><a href="index.php?act=store_transport">运费模板</a></li>
						<li><a href="index.php?act=store_express">物流公司</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="center-title clearfix">
					<strong>退换货</strong>
				</div>
				<div class="orderlist">
					<div class="orderlist-head clearfix">
                    	<ul>
							<li>
								<a href="index.php?act=store_return"<?php echo empty($state) ? ' class="active"' : '';?>>全部</a>
							</li>
							<li>
								<a href="index.php?act=store_return&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待审核</a>
							</li>
							<li>
								<a href="index.php?act=store_return&state=show"<?php echo $state=='show' ? ' class="active"' : '';?>>已同意</a>
							</li>
							<li>
								<a href="index.php?act=store_return&state=unshow"<?php echo $state=='unshow' ? ' class="active"' : '';?>>不同意</a>
							</li>
						</ul>
						<div class="pull-right">
							<div class="search">
								<form action="index.php" method="get" id="search_form">
									<input type="hidden" name="act" value="store_return">
									<input type="text" id="search_name" name="search_name" class="itxt" placeholder="退货单号" value="<?php echo $search_name;?>">
									<a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
								</form>
							</div>
						</div>
					</div>
                    <div class="orderlist-body">
                        <table class="order-tb">
                            <thead>
                                <tr>
                                    <th width="500">订单详情</th>
                                    <th width="120">联系人</th>
                                    <th width="120">类型</th>
                                    <th width="198">状态与操作</th>
                                </tr>
                            </thead>
                            <?php foreach($return_list as $key => $value) { ?>
                                <tbody>
                                    <tr class="sep-row"><td colspan="5"></td></tr>
                                    <tr class="tr-th">
                                        <td colspan="5">
                                            <span><?php echo date('Y-m-d H:i', $value['return_addtime']);?></span>
                                            <span>退货单号：<?php echo $value['return_sn'];?></span>
                                        </td>
                                    </tr>
                                    <tr class="tr-bd">
                                        <td>
                                            <div class="td-inner clearfix">
                                                <div class="item-pic">
                                                    <a href="index.php?act=goods&goods_id=<?php echo $return_goods[$value['return_id']]['goods_id'];?>" target="_blank"><img src="<?php echo $return_goods[$value['return_id']]['goods_image'];?>"></a>
                                                </div>
                                                <div class="item-info">
                                                    <a href="index.php?act=goods&goods_id=<?php echo $return_goods[$value['return_id']]['goods_id'];?>" target="_blank">
                                                        <?php echo $return_goods[$value['return_id']]['goods_name'];?>
                                                        <?php if(!empty($return_goods[$value['return_id']]['spec_info'])) { ?>
                                                        （<?php echo $return_goods[$value['return_id']]['spec_info'];?>）
                                                        <?php } ?>
                                                    </a>													
                                                    <p><?php echo $return_goods[$value['return_id']]['goods_price'];?></p>
                                                    <p>X<?php echo $return_goods[$value['return_id']]['goods_returnnum'];?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                        	<span><?php echo $order_address[$value['order_id']]['true_name'];?></span><br />
                                            <span><?php echo $order_address[$value['order_id']]['mobile_phone'];?></span>
                                        </td>
                                        <td>
                                        	<?php if($value['return_type'] == 'return') { ?>
												<p>退货</p>
                                            <?php } else { ?>
                                            	<p>换货</p>
                                            <?php } ?>
                                        </td>
                                        <td>
											<p class="state" id="state_<?php echo $value['return_id'];?>">
												<?php if($value['return_state'] == 3) { ?>
													待商家审核
												<?php } elseif($value['return_state'] == 1) { ?>
													<?php if($value['physical_state'] == 0) { ?>
														商家已同意
													<?php } elseif($value['physical_state'] == 1) { ?>
														买家已发货
													<?php } elseif($value['physical_state'] == 2) { ?>
														商家已收货
													<?php } elseif($value['physical_state'] == 3) { ?>
														商家已发货
													<?php } elseif($value['physical_state'] == 4) { ?>
														退换货完成
													<?php } ?>
												<?php } elseif($value['return_state'] == 2) { ?>
													商家不同意
												<?php } ?>
                                            </p>
                                            <div id="opr_<?php echo $value['return_id'];?>">
												<?php if($value['return_state'] == 3) { ?>
                                               		<a class="bluelink return-agree" href="javascript:;" return_id="<?php echo $value['return_id'];?>">同意</a>
                                                	<p><a href="javascript:;" class="return-refuse" return_id="<?php echo $value['return_id'];?>">拒绝</a></p>
                                            	<?php } elseif($value['return_state'] == 1) { ?>
													<?php if($value['physical_state'] == '1') { ?>
														<a href="javascript:;" class="btn btn-primary return-receive" return_id="<?php echo $value['return_id'];?>">确认收货</a>
														<p><a href="index.php?act=store_return&op=physical&return_id=<?php echo $value['return_id'];?>" target="_blank">物流跟踪</a></p>
													<?php } elseif($value['physical_state'] == '2') { ?>
														<?php if($value['return_type'] == 'exchange') { ?>
															<a href="javascript:;" class="btn btn-primary return-deliver" return_id="<?php echo $value['return_id'];?>">确认发货</a>
														<?php } else { ?>
															<a href="javascript:;" class="btn btn-primary return-finish" return_id="<?php echo $value['return_id'];?>">完成退货</a>
														<?php } ?>
													<?php } elseif($value['physical_state'] == '3') { ?>	
														<a href="index.php?act=store_return&op=seller_physical&return_id=<?php echo $value['return_id'];?>" class="btn btn-primary" target="_blank">物流跟踪</a>
													<?php } ?>	
												<?php } ?>
											</div>
                                        </td>
                                    </tr>
                                    <tr class="tr-ft">
                                        <td colspan="5">
                                        	<?php if(!empty($value['return_image'])) { ?>
                                            <div class="picture-list voucher-list">
                                                <ul class="clearfix">
                                                    <?php foreach($value['return_image'] as $subkey => $subvalue) { ?>
                                                    <li><img src="<?php echo $subvalue;?>"></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                            <?php } ?>
                                            <?php if(!empty($value['return_content'])) { ?>
                                            <div class="reture-exp">
                                                <label>退换说明：<?php echo $value['return_content'];?></label>
                                            </div>
                                            <?php } ?>
                                            <?php if(!empty($value['seller_message'])) { ?>
                                            <div class="reture-exp">
                                                <label>拒绝理由：<?php echo $value['seller_message'];?></label>
                                            </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            <?php } ?>
                        </table>  
					</div>
					<?php echo $multi;?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
	<div class="modal-wrap w-400" id="agree-box" style="display:none;">
    	<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="agree_id" name="agree_id" value="" />
        <div class="modal-bd">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <span class="tip-icon">
                        <i class="iconfont icon-info"></i>
                    </span>
                    <h3 class="tip-message">你确认同意退换货？</h3>
                </div>
            </div>
        </div>
        <div class="modal-ft tc">
            <a class="btn btn-primary" onclick="agreesubmit();">同意</a>
            <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        </div>
    </div>
    <div class="modal-wrap w-700" id="refuse-box" style="display:none;">
        <div class="modal-hd">
        	<div class="Validform-checktip Validform-wrong m-tip"></div>
			<input type="hidden" id="refuse_id" name="refuse_id" value="" />
            <h4>拒绝理由</h4>
            <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
        </div>
        <div class="modal-bd">
            <div class="cont-modal">
                <div class="cont-item">
                    <label>拒绝理由</label>
                    <textarea id="seller_message" name="seller_message"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-ft">
             <a class="btn btn-default" onclick="Custombox.close();">取消</a>
             <a class="btn btn-primary" onclick="refusesubmit();">确定</a>
        </div>
    </div>
	<div class="modal-wrap w-400" id="receive-box" style="display:none;">
    	<div class="Validform-checktip Validform-wrong m-tip"></div>
		<input type="hidden" id="receive_id" name="receive_id" value="" />
        <div class="modal-bd">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <span class="tip-icon">
                        <i class="iconfont icon-info"></i>
                    </span>
                    <h3 class="tip-message">你确认已经收到货物？</h3>
                </div>
            </div>
        </div>
        <div class="modal-ft tc">
            <a class="btn btn-primary" onclick="receivesubmit();">确定</a>
            <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        </div>
    </div>
	<div class="modal-wrap w-700" id="deliver-box" style="display:none;">
		<div class="modal-hd">
			<div class="Validform-checktip Validform-wrong m-tip"></div>
			<input type="hidden" id="deliver_id" name="deliver_id" value="" />
            <h4>发货</h4>
			<span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
		</div>
		<div class="modal-bd">
			<div class="cont-modal">
				<div class="cont-item">
					<label>快递公司</label>
					<select id="express_id" name="express_id">
						<option value="">请选择</option>
						<?php foreach($express_list as $key => $value) { ?>
						<option value="<?php echo $value['express_id'];?>"><?php echo $value['express_name'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class="cont-item">
					<label>快递编号</label>
					<input type="text" id="shipping_code" name="shipping_code">
				</div>
			</div>
		</div>
		<div class="modal-ft">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="deliversubmit();">确定</a>
		</div>
	</div>
	<div class="modal-wrap w-400" id="finish-box" style="display:none;">
    	<div class="modal-hd">
			<div class="Validform-checktip Validform-wrong m-tip"></div>
			<input type="hidden" id="finish_id" name="finish_id" value="" />
            <h4>完成退货</h4>
			<span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
		</div>
		<div class="modal-bd">
			<div class="cont-modal">
				<div class="cont-item">
					<label>退货金额</label>
					<input type="text" id="return_amount" name="return_amount">
				</div>
			</div>
		</div>
		<div class="modal-ft">
			 <a class="btn btn-default" onclick="Custombox.close();">取消</a>
			 <a class="btn btn-primary" onclick="finishsubmit();">确定</a>
		</div>
    </div>
	<script type="text/javascript" src="templates/js/profile/store_return.js"></script>
<?php include(template('common_footer'));?>