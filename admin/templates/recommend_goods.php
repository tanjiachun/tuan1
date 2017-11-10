<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">全局<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('index', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=index">站点信息</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('recommend', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a class="active" href="admin.php?act=recommend">网站推荐</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('article', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=article">文章公告</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('link', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=link">友情链接</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('related', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=related">相关认证</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('app', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=app">APP设置</a>
						</dd>
						<?php } ?>
						<?php if(in_array('admin', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=admin">管理员</a>
						</dd>
						<?php } ?>
						<?php if(in_array('log', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=log">操作记录</a>
						</dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
            <div class="page_filter">
            	<label class="frm_checkbox_label"><strong>商品推荐</strong></label>
				<div class="page_filter_right">
            		<a href="admin.php?act=recommend" class="btn btn_default">返回</a>
            	</div>
            </div>
            <form action="admin.php?act=recommend&op=goods" method="post" class="content-form" id="mall_recommend">
            	<input type="hidden" id="form_submit" name="form_submit" value="ok"  />
    			<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                <div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                            <div class="control_group">
                                <table class="goods_table">
                                    <thead>
                                        <th>图片</th>
                                        <th>商品</th>
                                        <th>店铺</th>
                                        <th>价格</th>
                                        <th>库存</th>
                                        <th class="th_opr">操作</th>
                                    </thead>
                                    <tbody id="goods_list">
                                        <?php foreach($recommend_goods as $key => $value) { ?>
                                        <tr id="goods_<?php echo $value['goods_id']?>">
                                            <td><input type="hidden" mall_type="goods_id" value="<?php echo $value['goods_id'];?>" name="goods_ids[]" /><img src="<?php echo $value['goods_image'];?>" width="80px;" height="80px;"></td>
                                            <td><?php echo $value['goods_name'];?></td>
                                            <td><?php echo $store_list[$value['store_id']];?></td>
                                            <td><?php echo $value['goods_price'];?></td>
                                            <td><?php echo $value['goods_storage'];?></td>
                                            <td class="td_opr" id="goods_<?php echo $value['goods_id'];?>">
                                            	<a href="javascript:;" class="btn btn_default" onclick="delgoods($('#goods_<?php echo $value['goods_id']?>'), <?php echo $value['goods_id']?>)">移除商品</a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="control_group">
                            	<a href="admin.php?act=misc&op=goods" class="btn btn_default" id="addgoods" onclick="showWindow(this.id, this.href);">添加推荐</a>
                            </div>
                        </div>
            		</div>
                </div>    
            </form>    
            <div class="page_bottom tc">
                <a href="javascript:checksubmit();" class="btn btn_primary">保存</a>
            </div>
    	</div>
    </div>
    <script type="text/javascript" src="admin/templates/js/jquery.ajaxContent.pack.js"></script>
    <script type="text/javascript">
		function checksubmit() {
			ajaxpost('mall_recommend', '', '', 'onerror');
		}
		
		function delgoods(o, id){
			o.remove();
			$('tr[goods_id="'+id+'"]').children(':last').html('<a href="JavaScript:void(0);" onclick="addgoods($(this))" class="btn btn_primary">添加商品</a>')
		}

		function addgoods(o) {
			parents = o.parents('tr:first');
			strs = new Array();
			strs[0] = o.parent().attr('goods_id');
			strs[1] = parents.find('img').attr('src');
			strs[2] = parents.find('td[type="name"]').html();
			strs[3] = parents.find('td[type="store"]').html();
			strs[4] = parents.find('td[type="price"]').html();
			strs[5] = parents.find('td[type="storage"]').html();
			$('<tr id="goods_'+strs[0]+'">'
				+'<td><input type="hidden" mall_type="goods_id" value="'+strs[0]+'" name="goods_ids[]" /><img src="'+strs[1]+'" width="80px;" height="80px;"></td>'
				+'<td>'+strs[2]+'</td>'
				+'<td>'+strs[3]+'</td>'
				+'<td>'+strs[4]+'</td>'
				+'<td>'+strs[5]+'</td>'
				+'<td class="td_opr" id="goods_'+strs[0]+'"><a href="javascript:;" class="btn btn_default" onclick="delgoods($(\'#goods_'+strs[0]+'\'), '+strs[0]+')">移除商品</a></td></tr>')
				.appendTo('#goods_list');
			$('tr[goods_id="'+strs[0]+'"]').children(':last').html('<a href="javascript:;" class="btn btn_default" onclick="delgoods($(\'#goods_'+strs[0]+'\'), '+strs[0]+')">移除商品</a>');
		}
	</script>
<?php include(template('common_footer'));?>