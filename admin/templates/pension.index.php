<div class="sku_group">
    <div class="page_filter">
        <ul>
            <li>
                <span class="frm_input_box search append">
                    <a href="admin.php?act=misc&op=pension" class="frm_input_append" id="search">
                        <i class="icon16_common icon_search"></i>
                    </a>
                    <input type="text" placeholder="输入机构名称" class="frm_input" id="search_name" value="<?php echo $search_name;?>">
                </span>
            </li>
            <li><a href="javascript:hideWindow('addpension');" class="btn btn_default">关闭</a></li>
        </ul>
    </div>
    <table class="goods_table">
        <thead>
            <th>图片</th>
            <th>名称</th>
            <th>规模</th>
            <th>价格</th>
            <th>地址</th>
            <th class="th_opr">操作</th>
        </thead>
        <tbody type="pension_body">
            <?php foreach($pension_list as $key => $value) { ?>
            <tr pension_id="<?php echo $value['pension_id'];?>">
                <td><img src="<?php echo $value['pension_image'];?>" width="80px;" height="80px;"></td>
                <td type="name"><?php echo $value['pension_name'];?></td>
                <td type="scale"><?php echo $pension_scale[$value['pension_scale']];?></td>
                <td type="price"><?php echo $value['pension_price'];?></td>
                <td type="address"><?php echo $value['pension_areainfo'];?></td>
                <td pension_id="<?php echo $value['pension_id'];?>" class="td_opr">
                    <a href="javascript:;" class="btn btn_primary" onclick="addpension($(this));">添加商品</a>
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
			target : '#fwin_content_addpension'
		});

		$('#search').on('click', function(){
			$(this).attr('href', $(this).attr('href')+'&search_name='+$('#search_name').val());
			$('#search').ajaxContent({
				event : 'dblclick',
				loaderType : 'img',
				loadingMsg : SITEURL+"/admin/templates/images/dialog/loading.gif",
				target : '#fwin_content_addpension'
			});
			$(this).dblclick();
			return false;
		});
	});
</script>     
<script type="text/javascript" reload="1">
$(function() {
	O = $('input[mall_type="pension_id"]');
	A = new Array();
	if(typeof(O) != 'undefined'){
		O.each(function(){
			A[$(this).val()] = $(this).val();
		});
	}
	T = $('tbody[type="pension_body"] tr');
	if(typeof(T) != 'undefined'){
		T.each(function(){
			if(typeof(A[$(this).attr('pension_id')]) != 'undefined'){
				$(this).children(':last').html('<a href="javascript:;" onclick="delpension($(\'#pension_'+$(this).attr('pension_id')+'\'), '+$(this).attr('pension_id')+')" class="btn btn_default">移除机构</a>');
			}
		});
	}
});
</script>