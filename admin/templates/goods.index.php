<div class="sku_group">
    <div class="page_filter">
        <ul>
            <li>
                <span class="frm_input_box search append">
                    <a href="admin.php?act=misc&op=goods" class="frm_input_append" id="search">
                        <i class="icon16_common icon_search"></i>
                    </a>
                    <input type="text" placeholder="输入商品名称或店铺名称" class="frm_input" id="search_name" value="<?php echo $search_name;?>">
                </span>
            </li>
            <li><a href="javascript:hideWindow('addgoods');" class="btn btn_default">关闭</a></li>
        </ul>
    </div>
    <table class="goods_table">
        <thead>
            <th>图片</th>
            <th>商品</th>
            <th>店铺</th>
            <th>价格</th>
            <th>库存</th>
            <th class="th_opr">操作</th>
        </thead>
        <tbody type="goods_body">
            <?php foreach($goods_list as $key => $value) { ?>
            <tr goods_id="<?php echo $value['goods_id'];?>">
                <td><img src="<?php echo $value['goods_image'];?>" width="80px;" height="80px;"></td>
                <td type="name"><?php echo $value['goods_name'];?></td>
                <td type="store"><?php echo $store_list[$value['store_id']];?></td>
                <td type="price"><?php echo $value['goods_price'];?></td>
                <td type="storage"><?php echo $value['goods_storage'];?></td>
                <td goods_id="<?php echo $value['goods_id'];?>" class="td_opr">
                    <a href="javascript:;" class="btn btn_primary" onclick="addgoods($(this));">添加商品</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
	<?php if(!empty($multi)) { ?>
    <div class="goods_tool">
		<div class="pagination_wrp">
            <div class="pagination">
                <?php echo $multi;?>
            </div>
        </div>
	</div>
	<?php } ?>  
</div>
<script type="text/javascript">
	$(function() {
		$('.pagination').find('a').unbind().ajaxContent({
			event : 'click',
			loaderType : "img",
			loadingMsg : SITEURL+"/admin/templates/images/dialog/loading.gif",
			target : '#fwin_content_addgoods'
		});

		$('#search').on('click', function(){
			$(this).attr('href', $(this).attr('href')+'&search_name='+$('#search_name').val());
			$('#search').ajaxContent({
				event : 'dblclick',
				loaderType : 'img',
				loadingMsg : SITEURL+"/admin/templates/images/dialog/loading.gif",
				target : '#fwin_content_addgoods'
			});
			$(this).dblclick();
			return false;
		});
	});
</script>
<script type="text/javascript" reload="1">
$(function() {	
	O = $('input[mall_type="goods_id"]');
	A = new Array();
	if(typeof(O) != 'undefined'){
		O.each(function(){
			A[$(this).val()] = $(this).val();
		});
	}
	T = $('tbody[type="goods_body"] tr');
	if(typeof(T) != 'undefined'){
		T.each(function(){
			if(typeof(A[$(this).attr('goods_id')]) != 'undefined'){
				$(this).children(':last').html('<a href="javascript:;" onclick="delgoods($(\'#goods_'+$(this).attr('goods_id')+'\'), '+$(this).attr('goods_id')+')" class="btn btn_default">移除商品</a>');
			}
		});
	}
});
</script>