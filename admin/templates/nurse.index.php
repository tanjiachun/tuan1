<div class="sku_group">
    <div class="page_filter">
        <ul>
            <li>
                <span class="frm_input_box search append">
                    <a href="admin.php?act=misc&op=nurse&type=<?php echo $type;?>" class="frm_input_append" id="search">
                        <i class="icon16_common icon_search"></i>
                    </a>
                    <input type="text" placeholder="输入机构名称" class="frm_input" id="search_name" value="<?php echo $search_name;?>">
                </span>
            </li>
            <li><a href="javascript:hideWindow('addnurse');" class="btn btn_default">关闭</a></li>
        </ul>
    </div>
    <table class="goods_table">
        <thead>
            <th>图片</th>
            <th>名字</th>
            <th>机构</th>
            <th>价格</th>
            <th>城市</th>
            <th class="th_opr">操作</th>
        </thead>
        <tbody type="nurse_body">
            <?php foreach($nurse_list as $key => $value) { ?>
            <tr nurse_id="<?php echo $value['nurse_id'];?>">
                <td><img src="<?php echo $value['nurse_image'];?>" width="80px;" height="80px;"></td>
                <td type="name"><?php echo $value['nurse_name'];?></td>
                <td type="agent"><?php echo $agent_list[$value['agent_id']];?></td>
                <td type="price"><?php echo $value['nurse_price'];?></td>
                <td type="address"><?php echo $value['nurse_cityname'];?></td>
                <td nurse_id="<?php echo $value['nurse_id'];?>" class="td_opr">
                    <a href="javascript:;" class="btn btn_primary" onclick="addnurse($(this));">添加阿姨</a>
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
			target : '#fwin_content_addnurse'
		});

		$('#search').on('click', function(){
			$(this).attr('href', $(this).attr('href')+'&search_name='+$('#search_name').val());
			$('#search').ajaxContent({
				event : 'dblclick',
				loaderType : 'img',
				loadingMsg : SITEURL+"/admin/templates/images/dialog/loading.gif",
				target : '#fwin_content_addnurse'
			});
			$(this).dblclick();
			return false;
		});
	});
</script>
<script type="text/javascript" reload="1">
$(function() {
	O = $('input[mall_type="nurse_id"]');
	A = new Array();
	if(typeof(O) != 'undefined'){
		O.each(function(){
			A[$(this).val()] = $(this).val();
		});
	}
	T = $('tbody[type="nurse_body"] tr');
	if(typeof(T) != 'undefined'){
		T.each(function(){
			if(typeof(A[$(this).attr('nurse_id')]) != 'undefined'){
				$(this).children(':last').html('<a href="javascript:;" onclick="delnurse($(\'#nurse_'+$(this).attr('nurse_id')+'\'), '+$(this).attr('nurse_id')+')" class="btn btn_default">移除阿姨</a>');
			}
		});
	}
});
</script>