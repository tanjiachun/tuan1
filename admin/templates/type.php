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
                            <a href="admin.php?act=recommend">网站推荐</a>
                        </dd>
                    <?php } ?>
                    <?php if(in_array('type', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a class="active" href="admin.php?act=type">服务类别</a>
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
            <label class="frm_checkbox_label"><strong>家政人员大分类</strong></label>
        </div>
        <div class="goods_content">
            <table class="goods_table">
                <thead>
                <th style="width:16px;"></th>
                <th>类别名称</th>
                <th class="th_opr">操作</th>
                </thead>
                <tbody>
                    <?php foreach ($type_list as $key => $value) { ?>
                        <tr>
                            <td></td>
                            <td><?php echo $value['type_name'] ?></td>
                            <td class="td_opr"><a href="admin.php?act=type&op=child_type&parent_id=<?php echo $value['type_id'] ?>">添加子分类</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
