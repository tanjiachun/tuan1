<?php include(template('common_header'));?>
<style>
    .btn {
        display: inline-block;
        overflow: visible;
        padding: 0 15px;
        height: 20px;
        line-height: 20px !important;
        vertical-align: middle;
        text-align: center;
        text-decoration: none;
        border-radius: 3px;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        font-size: 14px;
        border-width: 1px;
        border-style: solid;
        cursor: pointer;
    }
</style>
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
            <label class="frm_checkbox_label"><strong>家政人员子分类</strong></label>
            <div class="page_filter_right">
                <a href="admin.php?act=type" class="btn btn_default">返回</a>
            </div>
        </div>
        <div class="goods_content">
            <table class="goods_table">
                <thead>
                <th style="width:16px;"></th>
                <th>类别名称</th>
                <th>薪资单位</th>
                <th>是否有服务费</th>
                <th>被搜索数量</th>
                <th>操作</th>
                </thead>
                <tbody>
                <?php foreach ($type_list as $key => $value) { ?>
                    <tr>
                        <td></td>
                        <td><?php echo $value['type_name'] ?></td>
                        <td><?php echo $value['type_unit'] ?></td>
                        <td><?php echo empty($value['is_service_price']) ? '无' : '有' ?></td>
                        <td><?php echo $value['search_count'] ?></td>
                        <td><span style="cursor: pointer;" class="del_btn" data="<?php echo $value['type_id'] ?>">删除</span></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="control_group">
            <a href="javascript:;" class="btn btn_default" id="addnurse" onclick="add()">添加分类</a>
        </div>
    </div>
</div>
<script>
    var parent_id='<?php echo $parent_id ?>';
    console.log(parent_id);
</script>
<script>
    function add() {
        var html='';
        html+='<tbody>';
        html+='<tr>';
        html+='<td></td>';
        html+='<td><input type="text" id="type_name"></td>';
        html+='<td><input type="text" id="type_unit"></td>';
        html+='<td><input type="radio" class="is_price" name="is_price" value="0">无 <input type="radio" class="is_price" name="is_price" value="1">有</td>';
        html+='<td></td>';
        html+='<td><a href="javascript:;" class="type_add btn btn_primary">增加</a> / <a href="javascript:;" class="type_default btn">取消</a></td>';
        html+='</tr>';
        html+='</tbody>'
        $("tbody").after(html);
        $("#addnurse").hide();
        $(".type_add").click(function () {
            var type_name = $("#type_name").val();
            var type_unit = $("#type_unit").val();
            var is_service_price = $(".is_price:checked").val();
            var submitData={
                'type_name':type_name,
                'parent_id':parent_id,
                'type_unit':type_unit,
                'is_service_price':is_service_price
            };
            console.log(submitData);
            $.ajax({
                type : 'POST',
                url : 'admin.php?act=type&op=child_type_add',
                data : submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType : 'json',
                success:function (data) {
                    if(data.done=='true'){
                        showDialog('添加成功','succ');
                        setTimeout(function () {
                            window.location.reload();
                        },1000);
                    }else{
                        showDialog('添加失败','notice')
                    }
                },
                error:function () {
                    showDialog('添加失败','notice')
                }
            });
        });
        $(".type_default").click(function () {
            $(this).parent().parent().parent().remove();
            $("#addnurse").show();
        });
    }
</script>
<script>
    $(".del_btn").click(function () {
        var data = $(this).attr('data');
        var submitData = {
            'type_id':data
        };
        console.log(submitData);
        $.ajax({
            type : 'POST',
            url : 'admin.php?act=type&op=child_type_del',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    showDialog('删除成功','succ');
                    setTimeout(function () {
                        window.location.reload();
                    },1000);
                }else{

                    showDialog('删除失败','notice')
                }
            },
            error:function () {
                showDialog('删除失败','notice')
            }
        });
    });
</script>