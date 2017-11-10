<?php include(template('common_header'));?>
    <div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">机构<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('agent', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a href="admin.php?act=agent">家政机构</a>
                            </dd>
                        <?php } ?>
                        <?php if(in_array('pension', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a href="admin.php?act=pension">养老机构</a>
                            </dd>
                        <?php } ?>
                    </dl>
                    <a class="active" href="javascript:;">机构设置<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('agent_grade', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a class="active" href="admin.php?act=agent_grade">机构等级</a>
                            </dd>
                        <?php } ?>
                        <?php if(in_array('agent_revise', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a href="admin.php?act=agent_revise">资料修改审核</a>
                            </dd>
                        <?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
            <div class="page_filter">
                <label class="frm_checkbox_label"><strong>机构等级</strong></label>
            </div>
            <form action="admin.php?act=agent_grade" method="post" class="content-form" id="mall_agent_grade">
                <input type="hidden" id="form_submit" name="form_submit" value="ok"  />
                <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                <div class="goods_info_group">
                    <div class="info_group_cont">
                        <div class="group_inner">
                            <div class="control_group">
                                <div class="sku_group">
                                    <table class="table_sku_stock">
                                        <thead>
                                        <tr>
                                            <th>等级名称</th>
                                            <th>等级图标</th>
                                            <th>所需分值</th>
                                            <th>预约金</th>
                                            <th class="th_opr" style="width:50px">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody id="grade_value">
                                        <?php $i = 0;?>
                                        <?php foreach($grade_list as $key => $value) { ?>
                                            <tr id="menu_<?php echo $i;?>">
                                                <td><input type="hidden" name="grade_id[<?php echo $i;?>]" value="<?php echo $value['grade_id'];?>"  /><input class="form_input input_xxlarge" name="grade_name[<?php echo $i;?>]" type="text" value="<?php echo $value['grade_name'];?>"></td>
                                                <td>
                                                    <div class="picture_list">
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:;">
                                                                    <?php if(!empty($value['grade_icon'])) { ?>
                                                                        <img src="<?php echo $value['grade_icon'];?>" id="show_image_<?php echo $i;?>">
                                                                    <?php } else { ?>
                                                                        <img src="admin/templates/images/default.jpg" id="show_image_<?php echo $i;?>">
                                                                    <?php } ?>
                                                                </a>
                                                                <span class="close_modal" href="javascript:;" onclick="image_del(<?php echo $i;?>);">×</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="picture_list add_list">
                                                        <ul>
                                                            <li>
                                                                <a class="add_goods" href="javascript:;">
                                                                    <span class="img_upload">
	                                                                    <input type="file" name="file_<?php echo $i;?>" id="file_<?php echo $i;?>" size="1" hidefocus="true" maxlength="0" onchange="upload('file_<?php echo $i;?>', '<?php echo $i;?>')">
                                                                    </span>
                                                                    <div class="upload-button">图片上传</div>
                                                                </a>
                                                                <input type="hidden" name="grade_icon[<?php echo $i;?>]" id="image_<?php echo $i;?>" value="<?php echo $value['grade_icon'];?>"  />
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td><input class="form_input input_xlarge" name="agent_score[<?php echo $i;?>]" type="text" value="<?php echo $value['agent_score'];?>"></td>
                                                <td><input class="form_input input_xlarge" name="deposit_amount[<?php echo $i;?>]" type="text" value="<?php echo $value['deposit_amount'];?>"></td>
                                                <td><a href="javascript:;" class="item_del" key="<?php echo $i;?>">删除</a></td>
                                            </tr>
                                            <?php $i++;?>
                                        <?php } ?>
                                        </tbody>
                                        <tr>
                                            <td colspan="5"><a href="javascript:;" class="btn btn_default menu_add">+ 增加</a></td>
                                        </tr>
                                    </table>
                                </div>
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
    <script type="text/javascript">
        var file_name = 'plat';
        function checksubmit() {
            ajaxpost('mall_agent_grade', '', '', 'onerror');
        }
        $(function() {
            var i = '<?php echo $i;?>';
            $(".menu_add").click(function() {
                var html = '';
                html += '<tr id="menu_'+i+'">';
                html += '<td><input type="hidden" name="grade_id['+i+']" value=""  /><input class="form_input input_xxlarge" name="grade_name['+i+']" type="text" value=""></td>';
                html += '<td><div class="picture_list"><ul><li><a href="javascript:;"><img src="admin/templates/images/default.jpg" id="show_image_'+i+'"></a><span class="close_modal" href="javascript:;" onclick="image_del('+i+');">×</span></li></ul></div><div class="picture_list add_list"><ul><li><a class="add_goods" href="javascript:;"><span class="img_upload"><input type="file" name="file_'+i+'" id="file_'+i+'" size="1" hidefocus="true" maxlength="0"  onchange="upload(\'file_'+i+'\', \''+i+'\')"></span><div class="upload-button">图片上传</div></a><input type="hidden" name=grade_icon['+i+']" id="image_'+i+'" value=""  /></li></ul></div></td>';
                html += '<td><input class="form_input input_xlarge" name="agent_score['+i+']" type="text" value=""></td>';
                html += '<td><input class="form_input input_xlarge" name="deposit_amount['+i+']" type="text" value=""></td>';
                html += '<td><a href="javascript:;" class="item_del" key="'+i+'">删除</a></td>';
                html += '</tr>';
                $("#grade_value").append(html);
                i++;
            });

            $("#grade_value").on('click', '.item_del', function() {
                var key = $(this).attr("key");
                $(this).parent().parent().remove();
                $(".menu_"+key).remove();
            });
        });
    </script>
    <script type="text/javascript" src="admin/templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="admin/templates/js/index.js"></script>
<?php include(template('common_footer'));?>