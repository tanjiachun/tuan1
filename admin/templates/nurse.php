<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <?php if(in_array('nurse', $this->admin['admin_permission'])) { ?>
                    <a class="active" href="javascript:;">阿姨管理<span></span></a>
                    <dl style="display:block">
                        <dd>
                            <a class="active" href="admin.php?act=nurse">阿姨列表</a>
                        </dd>
                        <dd>
                            <a href="admin.php?act=nurse&op=pending">等待审核</a>
                        </dd>
                    </dl>
                    <?php } ?>
                    <a class="active" href="javascript:;">阿姨设置<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('grade', $this->admin['admin_permission'])) { ?>
                        <dd>
						    <a href="admin.php?act=grade">阿姨等级</a>
						</dd>
						<?php } ?>
						<?php if(in_array('tag', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=tag">评论标签</a>
						</dd>
						<?php } ?>
                        <?php if(in_array('service', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=service">阿姨服务</a>
						</dd>
						<?php } ?>
                        <?php if(in_array('nurse_revise', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a href="admin.php?act=nurse_revise">资料修改审核</a>
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
                        <li<?php echo $state=='status' ? ' class="active"' : '';?>><a href="admin.php?act=nurse&state=status">阿姨状态</a></li>
                        <li<?php echo $state=='pending' ? ' class="active"' : '';?>><a href="admin.php?act=nurse&state=pending">等待雇佣</a></li>
                        <li<?php echo $state=='working' ? ' class="active"' : '';?>><a href="admin.php?act=nurse&state=working">正在服务</a></li>
                        <li<?php echo $state=='customer' ? ' class="active"' : '';?>><a href="admin.php?act=nurse&state=customer">售后处理</a></li>
                        <li<?php echo $state=='unshow' ? ' class="active"' : '';?>><a href="admin.php?act=nurse&state=unshow">冻结</a></li>
                    </ul>
                </div>
            </div>
            <div class="page_filter">
                 <?php if($state == 'pending' || $state == 'working' || $state == 'customer' || $state == 'unshow') { ?>
                    <form action="admin.php" method="get" id="search_form">
                        <input type="hidden" name="act" value="nurse" />
                        <input type="hidden" name="state" value="<?php echo $state;?>" />
                        <div class="page_filter_right">
                            <ul>
                                <li>
                                    <span class="frm_input_box search append">
                                        <a href="javascript:void(0);" class="frm_input_append">
                                            <i class="icon16_common icon_search" onclick="$('#search_form').submit();"></i>
                                        </a>
                                        <input type="text" id="search_name" name="search_name" placeholder="阿姨名称" class="frm_input" value="<?php echo $search_name;?>">
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </form>
                 <?php } ?>
            </div>
        	<div class="goods_content">
                <table class="goods_table">
                    <?php if($state == 'status') { ?>
                           <thead>
                              <th style="width:16px;"></th>
                              <th style="width:100px;">阿姨名称</th>
                              <th>联系号码</th>
                              <th>状态管理</th>
                              <th class="sort">无法接通↑</th>
                              <th class="sort1">已经工作↑</th>
                              <th>通话完成</th>
                              <th>最后评价时间</th>
                               <th class="th_opr">最后评价状态</th>
                           </thead>
                     <?php } ?>
                    <?php if($state == 'pending' || $state == 'working' || $state == 'customer' || $state == 'unshow') { ?>
                    <thead>
                    	<th style="width:16px;"></th>
                        <th style="width:100px;">阿姨名称</th>
						<th>所属机构</th>
                        <th>服务类型</th>
                        <th>工作年限</th>
                        <th>身份证号码</th>                        
                        <th>手持身份证照</th>
                        <th style="width:400px;">工作资质</th>
                        <th class="th_opr">联系号码</th>
                    </thead>
                     <?php } ?>
                     <?php if($state=='status') { ?>
                            <tbody class="goods_tbody">
                                <?php foreach($nurse_list as $key => $value) { ?>
                                <tr>
                                    <td>
                                        <label class="frm_checkbox_label checkitem" data="<?php echo $value['nurse_id'];?>">
                                            <i class="icon16_common icon_checkbox"></i>
                                        </label>
                                    </td>
                                    <td><a style="color:red" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><?php echo $value['nurse_name'];?></a></td>
                                    <td><?php echo $value['member_phone'];?></td>
                                    <td class="stateOrder" data="<?php echo $value['nurse_id'];?>">查看/修改</td>
                                    <td class="notAvailable"><?php echo $value['status_available'];?></td>
                                    <td class="onWorking"><?php echo $value['status_working'];?></td>
                                    <td><?php echo $value['status_called'];?></td>
                                    <td class="getTime"></td>
                                    <td class="td_opr lastStatus"></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                     <?php } ?>
                     <?php if($state == 'pending' || $state == 'working' || $state == 'customer' || $state == 'unshow') { ?>
                    <tbody>
                    	<?php foreach($nurse_list as $key => $value) { ?>
                        <tr>
                            <td>
                                <label class="frm_checkbox_label checkitem" data="<?php echo $value['nurse_id'];?>">
                                    <i class="icon16_common icon_checkbox"></i>
                                </label>
                            </td>
                            <td><a style="color:red" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><?php echo $value['nurse_name'];?></a></td>
                            <td><?php echo $agent_list[$value['agent_id']];?></td>
                            <td><?php echo $type_array[$value['nurse_type']];?></td>
                            <td><?php echo $value['nurse_education'];?>个月</td>
                            <td><?php echo $value['nurse_cardid'];?></td>
                            <td>
                            	<div class="goods_thumb">
                            		<img src="<?php echo $value['nurse_cardid_image'];?>" class="zoomify">
                                </div>
                            </td>
                            <td>
                            	<div class="media_list">
									<ul>
										<?php foreach($value['nurse_qa_image'] as $subkey => $subvalue) { ?>
                                            <li>
                                                <div class="goods_thumb">
                                                    <img src="<?php echo $subvalue;?>" class="zoomify">
                                                </div>
                                            </li>  
                                        <?php } ?>
                                	</ul>
                                </div>
                            </td>
                                <td><?php echo $value['member_phone'];?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                     <?php } ?>
                </table>
				<div class="goods_tool">
                	<div class="opr_tool">
                        <label class="frm_checkbox_label checkall">
                            <i class="icon16_common icon_checkbox"></i>
                           全选 
                        </label>
                        <?php if($state == 'status') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse&op=unshow&state=pending" confirm="您确实要冻结吗?">冻结</a>
                        <?php } ?>
                        <?php if($state == 'pending') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse&op=unshow&state=pending" confirm="您确实要冻结吗?">冻结</a>
                        <?php } ?>
						<?php if($state == 'working') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse&op=unshow&state=working" confirm="您确实要冻结吗?">冻结</a>
                        <?php } ?>
						<?php if($state == 'customer') { ?>
						<a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse&op=resolve" confirm="您确实已解决吗?">解决</a>
						<a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse&op=unshow&state=customer" confirm="您确实要冻结吗?">冻结</a>
						<?php } ?>
                        <?php if($state == 'unshow') { ?>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse&op=show" confirm="您确实要开启吗?">开启</a>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse&op=del" confirm="阿姨的数据将会清除掉，您确实要删除吗?">删除</a>
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
    <div class="modal-wrap w-400" id="phone-box">
        <span class="nurse_name">111</span>
        <input type="radio" value="1" id="hunting" name="state"/><span>待雇佣</span>
        <input type="radio" value="2" id="working" name="state"/><span>工作中</span>
        <input type="radio" value="3" id="holiday" name="state"/><span>请假中</span>
        <input type="radio" value="4" id="trouble" name="state"/><span>纠纷中</span>
        <input type="radio" value="5" id="unknow" name="state"/><span>无状态</span>
        <a class="btn-quxiao">取消</a>
        <a class="reviseState">确定</a>
    </div>

    <script type="text/javascript">
		$(function() {
			$('.zoomify').zoomify();
		});
	</script>
    <link href="admin/templates/css/zoomify.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="admin/templates/js/zoomify.js"></script>
    <script type="text/javascript" src="admin/templates/js/get_status.js"></script>
<?php include(template('common_footer'));?>