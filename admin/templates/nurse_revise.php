<?php include(template('common_header'));?>
    <div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <?php if(in_array('nurse', $this->admin['admin_permission'])) { ?>
                        <a class="active" href="javascript:;">阿姨管理<span></span></a>
                        <dl style="display:block">
                            <dd>
                                <a href="admin.php?act=nurse">阿姨列表</a>
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
                                <a class="active" href="admin.php?act=nurse_revise">资料修改审核</a>
                            </dd>
                        <?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
            <div class="page_filter">
                <form action="admin.php" method="get" id="search_form">
                    <input type="hidden" name="act" value="nurse_revise" />
                    <input type="hidden" name="op" value="pending" />
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
            </div>
            <div class="goods_content">
                <table class="goods_table">
                    <thead>
                    <th style="width:16px;"></th>
                    <th>阿姨名称</th>
                    <th>服务类别</th>
                    <th>期望薪资</th>
                    <th>身份证号</th>
                    <th style="width:200px;">特色需求</th>
                    <th>个人照片</th>
                    <th>手持身份证照</th>
                    <th style="width:300px;">工作资质</th>
                    <!--                    <th style="width:300px;">机构服务</th>-->
                    </thead>
                    <tbody>
                    <?php foreach($nurse_list as $key => $value) { ?>
                        <tr>
                            <td>
                                <label class="frm_checkbox_label checkitem" data="<?php echo $value['nurse_id'];?>">
                                    <i class="icon16_common icon_checkbox"></i>
                                </label>
                            </td>
                            <td><?php echo $value['nurse_name'];?></td>
                            <td><?php echo $value['service_type'];?></td>
                            <td><?php echo $value['nurse_price'];?></td>
                            <td><?php echo $value['nurse_cardid'];?></td>
                            <td><?php echo $value['nurse_special_service'];?></td>
                            <td>
                                <div class="goods_thumb">
                                    <img src="<?php echo $value['nurse_image'];?>" class="zoomify">
                                </div>
                            </td>
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
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="goods_tool">
                    <div class="opr_tool">
                        <a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse_revise&op=revise" confirm="您确实要通过审核吗?">通过审核</a>
                        <a href="javascript:;" class="btn btn_default batchbutton" name="nurse_ids" uri="admin.php?act=nurse_revise&op=unrevise" confirm="您确定拒绝审核吗?">审核不通过</a>
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
    <script type="text/javascript">
        $(function() {
            $('.zoomify').zoomify();
        });
    </script>
    <script>
        $("label").click(function () {
            $(".frm_checkbox_label").removeClass("selected");
        });
    </script>
    <link href="admin/templates/css/zoomify.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="admin/templates/js/zoomify.js"></script>
<?php include(template('common_footer'));?>