<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">店铺<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('store', $this->admin['admin_permission'])) { ?>
                        <dd>
						    <a class="active" href="admin.php?act=store">店铺管理</a>
						</dd>
						<?php } ?>
						<?php if(in_array('sclass', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=sclass">店铺分类</a>
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
                        <li<?php echo $state=='show' ? ' class="active"' : '';?>><a href="admin.php?act=store&state=show">营业店铺</a></li>
                        <li<?php echo $state=='unshow' ? ' class="active"' : '';?>><a href="admin.php?act=store&state=unshow">违规店铺</a></li>
                    </ul>
                </div>
            </div>
            <div class="page_filter">
               	<form action="admin.php" method="get" id="search_form">
                	<input type="hidden" name="act" value="store" />
                    <input type="hidden" name="state" value="<?php echo $state;?>" />
                    <div class="page_filter_right">
                        <ul>
                            <li>
                                <span class="frm_input_box search append">
                                    <a href="javascript:void(0);" class="frm_input_append">
                                        <i class="icon16_common icon_search" onclick="$('#search_form').submit();"></i>
                                    </a>
                                    <input type="text" id="search_name" name="search_name" placeholder="店铺名称" class="frm_input" value="<?php echo $search_name;?>">
                                </span>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        	<div class="goods_content">
                <table class="goods_table">
                    <thead>
                    	<th style="width:16px;"></th>
                        <th style="width:100px;">店铺名称</th>
                        <th>店铺类型</th>
                        <th>店铺地址</th>
                        <th>店主身份证号码</th>                        
                        <th>店主手持身份证照</th>
						<th>主营业务</th>
                        <th style="width:120px;">时间</th>
                    </thead>
                    <tbody>
                    	<?php foreach($store_list as $key => $value) { ?>
                        <tr>
                            <td>
                                <label class="frm_checkbox_label checkitem" data="<?php echo $value['store_id'];?>">
                                    <i class="icon16_common icon_checkbox"></i>
                                </label>
                            </td>
                            <td><?php echo $value['store_name'];?></td>
                            <td><?php echo $class_list[$value['class_id']];?></td>
                            <td><?php echo $value['store_areainfo'].$value['store_address'];?></td>
                            <td><?php echo $value['store_cardid'];?></td>
                            <td>
                            	<div class="goods_thumb">
                            		<img src="<?php echo $value['store_cardid_image'];?>" class="zoomify">
                                </div>
                            </td>
                            <td><?php echo $value['store_content'];?></td>
                           	<td><?php echo date('Y-m-d H:i', $value['store_time']);?></td>
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
                        <a href="javascript:;" class="btn btn_default batchbutton" name="store_ids" uri="admin.php?act=store&op=unshow" confirm="您确实要关闭吗?">关闭</a>
                        <?php } ?>
                        <?php if($state == 'unshow') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="store_ids" uri="admin.php?act=store&op=show" confirm="您确实要开启吗?">开启</a>
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
    <link href="admin/templates/css/zoomify.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="admin/templates/js/zoomify.js"></script>
    <script type="text/javascript">
		$(function() {
			$('.zoomify').zoomify();
		});
	</script>
<?php include(template('common_footer'));?>