<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
<link rel="stylesheet" href="templates/css/admin.css">
    <div class="agent_set_center_header">
        <div class="left" style="margin-top: 10px;">
            <a href="index.php">
                <img src="templates/images/logo.png">
            </a>
            <span style="font-size: 18px;font-weight: 500;margin-left: 5px;">机构管理平台</span>
        </div>
        <div class="left agent_message_show">
            <span>被关注 <?php echo $agent['agent_focusnum'] ?></span>
            <span>员工总数 <?php echo $nurse_count ?></span>
            <span>浏览数 <?php echo $agent['agent_viewnum'] ?></span>
            <span>累计交易 <?php echo $book_count ?></span>
        </div>
        <div class="left agent_code_show">
            <span>机构编号 <?php echo $agent['agent_id'] ?></span>
            <span>有<?php echo $question_count ?>个问题待回答 <a href="index.php?act=agent_question" style="background: #ff6905;color:#fff;padding:0 5px;">回答</a></span>
        </div>
    </div>
</div>
<div id="agent_manage">
    <div id="agent_manage_content">
        <div id="agent_manage_sidebar" class="left">
            <div class="agent_manage_logo">
                <img width="100px" height="100px" src="<?php echo $agent['agent_logo'] ?>" alt="">
            </div>
            <ul class="sidebar_list">
                <li><a href="index.php?act=agent_center">首页编辑</a></li>
                <li><a href="index.php?act=agent_question">机构问答</a></li>
                <li><a href="index.php?act=agent_phone_set">手机设置</a></li>
                <li class="staff_set_list">
                    <a class="list_show">员工管理</a>
                    <ul style="display: none;">
                        <li><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
                        <li><a href="index.php?act=agent_nurse_audit" style="font-size: 12px;">· 审核员工</a></li>
                        <li><a href="index.php?act=agent_nurse_add" style="font-size: 12px;">· 新建员工</a></li>
                    </ul>
                </li>
                <li><a href="index.php?act=agent_book">全部订单</a></li>
                <li><a href="index.php?act=agent_evaluate">评价管理</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_refund">退款查询</a></li>
                <li><a href="index.php?act=agent_marketing">营销管理</a></li>
                <li><a href="index.php?act=agent_profit">财务中心</a></li>
                <li><a href="index.php?act=agent_invoice">发票管理</a></li>
            </ul>
            <script>
                $(".list_show").click(function () {
                    if(!$(".staff_set_list ul").is(":hidden")){
                        $(".staff_set_list ul").fadeOut();
                        $(".staff_set_list img").attr('src','templates/images/toBW.png');
                    }else{
                        $(".staff_set_list ul").fadeIn();
                        $(".staff_set_list img").attr('src','templates/images/toTopW.png');
                    }
                })
            </script>
        </div>
        <div id="agent_manage_set" class="left form-list" style="width:970px;margin:0 0 0 10px;">
            <div class="agent_refund_list">
                <p><span>退款查询</span></p>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=agent_refund&state=all<?php echo $state ?>"<?php echo $state=='all' ? ' class="active"' : '';?>>全部订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_refund&state=time"<?php echo $state=='time' ? ' class="active"' : '';?>>按时间排序</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_refund&state=nurse_refund"<?php echo $state=='nurse_refund' ? ' class="active"' : '';?>>员工收到的退款</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_refund&state=member_refund"<?php echo $state=='member_refund' ? ' class="active"' : '';?>>员工申请的退款</a>
                            </li>
                        </ul>
                        <div class="pull-right">
                            <div class="search">
                                <form action="index.php" method="get" id="search_form">
                                    <input type="hidden" name="act" value="agent_refund">
                                    <input type="hidden" name="state" value="<?php echo $state;?>">
                                    <input type="text" id="search_name" name="search_name" class="itxt" placeholder="家政人员姓名/订单编号" value="<?php echo $search_name;?>">
                                    <a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="orderlist-body">
                        <table class="order-tb">
                            <thead>
                            <tr>
                                <th width="30"></th>
                                <th width="200">订单编号</th>
                                <th width="160">预约时间</th>
                                <th width="80">员工</th>
                                <th width="100">雇主</th>
                                <th width="120">交易金额</th>
                                <th width="120">退款金额</th>
                                <th width="200">退款原因</th>
                            </tr>
                            </thead>
                            <?php foreach($book_list as $key => $value) { ?>
                                <tbody id="book_<?php echo $value['book_id'];?>" ">
                                <tr class="tr-bd">
                                    <td>
                                        <span class="check checkitem" book_id="<?php echo $value['book_id'];?>"><i class="iconfont icon-type"></i></span>
                                    </td>
                                    <td><?php echo $value['book_sn'];?></td>
                                    <td><?php echo date('Y-m-d H:i', $value['add_time']);?></td>
                                    <td><?php echo $nurse_list[$value['nurse_id']]['nurse_name'];?></td>
                                    <td><?php echo $nurse_list[$value['nurse_id']]['member_phone'];?></td>
                                    <td><?php echo $value['book_amount'] ?></td>
                                    <td><?php echo $value['refund_amount'] ?></td>
                                    <td><?php echo $value['refund_reason'] ?></td>
                                </tr>
                                </tbody>
                            <?php } ?>
                            <?php if(!empty($book_list)) { ?>
                                <tbody>
                                <tr class="tool-row">
                                    <td colspan="11">
                                        <span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
                                        <span class="btn btn-default del-books">删除</span>
                                    </td>
                                </tr>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                    <?php echo $multi;?>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
<div class="modal-wrap w-400" id="del-books-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="del_book_ids" name="del_book_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定删除吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="delsubmit();">确定</a>
    </div>
</div>

<div class="alert-box" style="display:none;">
    <div class="alert alert-danger tip-title"></div>
</div>
<div class="modal-wrap w-400" id="alert-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
                <span class="tip-icon"></span>
                <h3 class="tip-title"></h3>
                <div class="tip-hint"></div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" onclick="Custombox.close();">关闭</a>
    </div>
</div>

<script>
    $(".del-books").click(function () {
        if($('.checkitem.active').length == 0) {
            showalert('请至少选择一个家政人员');
            return;
        }
        var items = '';
        $('.checkitem.active').each(function(){
            items += $(this).attr('book_id') + ',';
        });
        items = items.substr(0, (items.length - 1));
        $('#del_book_ids').val(items);
        Custombox.open({
            target : '#del-books-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    var del_submit_btn=false;
    function delsubmit() {
        var formhash = $('#formhash').val();
        var del_book_ids=$("#del_book_ids").val();
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'del_book_ids':del_book_ids
        };
        console.log(submitData);
        if(del_submit_btn) return;
        del_submit_btn=true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_refund&op=del',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                console.log(data);
                del_submit_btn=false;
                if(data.done=='true'){
                    Custombox.close(function() {
                        for(var i=0; i<data.del_ids.length; i++) {
                            $('#book_'+data.del_ids[i]).remove();
                        }
                        $('.alert-box .tip-title').html('删除成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                }else{
                    showwarning('del-books-box', data.msg);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                del_submit_btn=false;
                showwarning('del-books-box', '网路不稳定，请稍候重试');
            }
        });
    }
    $(".staff_set_list span").click(function () {
        if(!$(".staff_set_list ul").is(":hidden")){
            $(".staff_set_list ul").fadeOut();
            $(".staff_set_list img").attr('src','templates/images/toBW.png');
        }else{
            $(".staff_set_list ul").fadeIn();
            $(".staff_set_list img").attr('src','templates/images/toTopW.png');
        }
    })
</script>