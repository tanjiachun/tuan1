<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">商品<span></span></a>
                    <dl style="display:block">
						<?php if(in_array('goods', $this->admin['admin_permission'])) { ?>
                        <dd>
						    <a class="active" href="admin.php?act=goods">商品管理</a>
						</dd>
						<?php } ?>
						<?php if(in_array('gclass', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=gclass">商品分类</a>
						</dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main have-tab">
        	<div class="fixed_content">
                <div class="tab">
                    <ul>
                        <li<?php echo $state=='show' ? ' class="active"' : '';?>><a href="admin.php?act=goods&state=show">出售商品</a></li>
                        <li<?php echo $state=='pending' ? ' class="active"' : '';?>><a href="admin.php?act=goods&state=pending">等待审核</a></li>
                        <li<?php echo $state=='unshow' ? ' class="active"' : '';?>><a href="admin.php?act=goods&state=unshow">违规商品</a></li>
                    </ul>
                </div>
            </div>
            <div class="page_filter">
               	<form action="admin.php" method="get" id="search_form">
                	<input type="hidden" name="act" value="goods" />
                    <input type="hidden" name="state" value="<?php echo $state;?>" />
                    <div class="page_filter_right">
                        <ul>
                            <li>
                                <span class="frm_input_box search append">
                                    <a href="javascript:void(0);" class="frm_input_append">
                                        <i class="icon16_common icon_search" onclick="$('#search_form').submit();"></i>
                                    </a>
                                    <input type="text" id="search_name" name="search_name" placeholder="店铺名称/商品名称" class="frm_input" value="<?php echo $search_name;?>">
                                </span>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        	<div class="goods_content">
                <table class="goods_table">
                    <thead>
                        <th>商品图片</th>
                        <th>商品名称</th>
                        <th>店铺名称</th>
                        <th>商品价格</th>
                        <th>商品库存</th>
                        <th style="width:150px;">上架时间</th>
                        <th class="th_opr">操作</th>
                    </thead>
                    <tbody>
                    	<?php foreach($goods_list as $key => $value) { ?>
                        <tr>
                        	<td>
                            	<div class="goods_thumb">
                            		<img src="<?php echo $value['goods_image'];?>">
                                    <label class="frm_checkbox_label checkitem" data="<?php echo $value['goods_id'];?>">
                                        <i class="icon16_common icon_checkbox"></i>
                                    </label>
                                </div>
                            </td>
                            <td><?php echo $value['goods_name'];?></td>
                            <td><?php echo $store_list[$value['store_id']];?></td>
                            <td><?php echo $value['goods_price'];?></td>
                            <td><?php echo $value['goods_storage'];?></td>
                            <td><?php echo date('Y-m-d H:i', $value['goods_add_time']);?></td>
                            <td class="td_opr">
                                <a href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>" target="_blank">查看</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
				<div class="goods_tool">
                	<div class="opr_tool">
                        <label class="frm_checkbox_label checkall">
                            <i class="icon16_common icon_checkbox"></i>
                           全选 
                        </label>
                        <?php if($state == 'show') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="goods_ids" uri="admin.php?act=goods&op=unshow" confirm="您确实要下架吗?">下架</a>
                        <?php } ?>
                        <?php if($state == 'unshow') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="goods_ids" uri="admin.php?act=goods&op=show" confirm="您确实要上架吗?">上架</a>
                        <?php } ?>
                        <?php if($state == 'pending') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="goods_ids" uri="admin.php?act=goods&op=show" confirm="您确实要上架吗?">上架</a>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="goods_ids" uri="admin.php?act=goods&op=unshow" confirm="您确实要下架吗?">下架</a>
                        <?php } ?>
                    </div>
                    <?php if(!empty($multi)) { ?>
                    <div class="pagination_wrp">
                        <div class="pagination">
                            共有<?php echo $count;?>条记录&nbsp;每页<?php echo $perpage;?>条
                            <?php echo $multi;?>
                        </div>
                    </div>
					<?php } ?>                
                </div>				
            </div>
        </div>
    </div>
    <div class="common_tips" style="display:none;" id="tip">
        <div class="tipcontent error_tip" id="tip_content"></div>
    </div>
    <div class="common_tips" style="display:none;" id="tip_success">
        <div class="tipcontent" id="tip_success_content"></div>
    </div>
    <script src="admin/templates/js/goods.js" type="text/javascript"></script>
<?php include(template('common_footer'));?>