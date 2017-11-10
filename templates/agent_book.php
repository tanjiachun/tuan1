<?php include(template('common_header'));?>
<link rel="stylesheet" href="templates/css/admin.css">
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
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
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_book">全部订单</a></li>
                <li><a href="index.php?act=agent_evaluate">评价管理</a></li>
                <li><a href="index.php?act=agent_refund">退款查询</a></li>
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
            <div class="agent_orders_list">
                <p><span>全部订单</span></p>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=agent_book&state=all<?php echo $state ?>"<?php echo $state=='all' ? ' class="active"' : '';?>>全部订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=in_three_month"<?php echo $state=='in_three_month' ? ' class="active"' : '';?>>近三个月订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>雇主已付款</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=onwork"<?php echo $state=='onwork' ? ' class="active"' : '';?>>已在岗</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=evaluated"<?php echo $state=='evaluated' ? ' class="active"' : '';?>>雇主已评价</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=close"<?php echo $state=='close' ? ' class="active"' : '';?>>关闭的订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_book&state=before_three_month"<?php echo $state=='before_three_month' ? ' class="active"' : '';?>>三个月前的订单</a>
                            </li>

                        </ul>
                        <div class="pull-right">
                            <div class="search">
                                <form action="index.php" method="get" id="search_form">
                                    <input type="hidden" name="act" value="agent_book">
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
                                <th width="170">订单编号</th>
                                <th width="100">预约时间</th>
                                <th width="80">员工</th>
                                <th width="100">雇主</th>
                                <th width="100">交易金额</th>
                                <th width="220">交易详情</th>
                                <th width="80">交易状态</th>
                                <th width="130">操作</th>
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
                                    <td><a target="_blank" href="index.php?act=member_trust_grade&member_id=<?php echo $value['member_id'] ?>"><?php echo $value['member_phone'];?></a></td>
                                    <td><?php echo $value['book_amount'] ?></td>
                                    <td><?php echo $value['book_details'] ?></td>
                                    <td>
                                        <?php if($value['comment_state']==1) { ?>
                                            雇主已评
                                        <?php } else { ?>
                                            <?php if($value['book_state']==0){ ?>
                                                交易关闭
                                            <?php } else if($value['book_state']==10) { ?>
                                                未付款
                                            <?php } else if($value['book_state']==20) { ?>
                                                <?php if($nurse_list[$value['nurse_id']]['state_cideci']==2) {?>
                                                    已在岗
                                                <?php } else if($nurse_list[$value['nurse_id']]['state_cideci']==4) { ?>
                                                    已到岗
                                                <?php } else { ?>
                                                    已付款
                                                <?php } ?>
                                            <?php } else if($value['book_state']==30) { ?>
                                                交易完成
                                            <?php } ?>
                                        <?php } ?>

                                    </td>
                                    <td>
                                        <?php if($value['comment_state']==1) { ?>
                                            <a href="index.php?act=agent_evaluate">进入评价管理</a>
                                        <?php } else { ?>
                                            <?php if($value['book_state']==0){ ?>
                                                <span style="color: #ff6905;text-decoration: underline;cursor: pointer;" class="agent_remove_book" data="<?php echo $value['book_id'] ?>">删除订单</span>
                                            <?php } else if($value['book_state']==10) { ?>
                                                <select style="border: none;" name="agent_member_message_show" class="agent_member_message_show" data="<?php echo $value['book_id'] ?>">
                                                    <option value="0" class="agent_member_message_show_chhoose">获取雇主信息</option>
                                                    <option value="1"><?php echo $member_list[$value['member_id']]['member_phone'];?></option>
                                                    <option value="2">发消息给雇主</option>
                                                    <option value="3">取消订单</option>
                                                </select>
                                            <?php } else if($value['book_state']==20) { ?>
                                                <?php if($nurse_list[$value['nurse_id']]['state_cideci']==2) { ?>
                                                    已在岗
                                                <?php } else { ?>
                                                    <select style="border: none;" name="agent_book_confirm" class="agent_book_confirm" data="<?php echo $value['book_id'] ?>" book_amount="<?php echo $value['book_amount'] ?>">
                                                        <option value="0" class="agent_book_confirm_choose">请谨慎选择您的操作</option>
                                                        <option value="1">切换至已在岗模式</option>
                                                        <option value="2">拒绝上岗，自动退款</option>
                                                        <option value="3">需要面谈，自动退款</option>
                                                        <option value="4">其他原因，自动退款</option>
                                                    </select>
                                                <?php } ?>
                                            <?php } else if($value['book_state']==30) { ?>
                                                交易完成
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                </tbody>
                            <?php } ?>
                            <?php if(!empty($book_list)) { ?>
                                <tbody>
                                <tr class="tool-row">
                                    <td colspan="11">
                                        <span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
                                        <?php if($state=='close') { ?>
                                            <span class="btn btn-default batch_del">批量删除</span>
                                        <?php } else { ?>

                                        <?php } ?>

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
<div class="modal-wrap w-400" id="del-book-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="del_book_id" name="del_book_id" value="" />
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
        <a class="btn btn-primary" onclick="delssubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="onwork-box" style="display: none;">
    <div class="modal-hd">
        <div class="Validform-checktip Validform-wrong m-tip"></div>
        <input type="hidden" id="onwork_id" name="onwork_id" value="" />
        <h4><uik>请输入在岗码（请在雇主处获取）</uik></h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <input type="text" id="book_code" name="book_code" class="form-input w-200 tip-message" placeholder="请输入6位在岗码">
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default onwork-quxiao" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="onworksubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="onworks-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="onworks_ids" name="onworks_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定已上岗吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="onworkssubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="cancel-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="cancel_id" name="cancel_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定取消吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default cancel-quxiao" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="cancelsubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-700" id="refund-box" style="display:none;">
    <div class="modal-hd">
        <div class="Validform-checktip Validform-wrong m-tip"></div>
        <input type="hidden" id="refund_id" name="refund_id" value="" />
        <h4><uik>退款处理</uik></h4>
        <span class="closed" onclick="Custombox.close();"><i class="iconfont icon-fork"></i></span>
    </div>
    <div class="modal-bd">
        <div class="cont-modal">
            <div class="cont-item">
                <label>退款金额</label>
                <input type="text" id="refund_amount" name="refund_amount" value="" />
            </div>
            <div class="cont-item">
                <label>退款原因</label>
                <div id="refund_reason" class="cont-value"></div>
            </div>
            <div class="cont-item">
                <label>处理方式：</label>
                <div class="cont-value radio-box">
                    <span class="radio active" field_value="1" field_key="refund_state"><i class="iconfont icon-type"></i>同意</span>
<!--                    <span class="radio" field_value="2" field_key="refund_state"><i class="iconfont icon-type"></i>拒绝</span>-->
                    <input type="hidden" id="refund_state" name="refund_state" value="1" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-ft">
        <a class="btn btn-default onwork-quxiao" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="refundsubmit();">确定</a>
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
<link href="templates/css/zoomify.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="templates/js/zoomify.js"></script>
<script>
//    单个删除函数
    $(".agent_remove_book").click(function () {
        var book_id=$(this).attr('data');
        $("#del_book_id").val(book_id);
        Custombox.open({
            target : '#del-book-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300
        });
    });
    function delsubmit() {
        var formhash = $('#formhash').val();
        var book_id=$("#del_book_id").val();
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'book_id' : book_id
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_book&op=del_book',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('#book_'+data.book_id).remove();
                        $('.alert-box .tip-title').html('删除成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                }else{
                    showwarning('del-book-box', data.msg);
                }
            },
            timeout:1500,
            error:function (xhr, type) {
                showwarning('del-book-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
//群体删除函数
    $(".batch_del").click(function () {
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
function delssubmit() {
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
        url : 'index.php?act=agent_book&op=del_books',
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
</script>
<script>
    $(".agent_book_confirm").change(function () {
       if($(this).val()=='1') {
          var book_id=$(this).attr('data');
          $("#onwork_id").val(book_id);
           Custombox.open({
               target : '#onwork-box',
               effect : 'blur',
               overlayClose : true,
               speed : 500,
               overlaySpeed : 300
           });
       }else if($(this).val()!=='0'){
           var refund_id = $(this).attr('data');
           var refund_amount = $(this).attr('book_amount');
           var refund_reason;
           if($(this).val()=='2'){
               refund_reason='拒绝上岗';
           }else if($(this).val()=='3'){
               refund_reason='需要面谈';
           }else if($(this).val()=='4'){
               refund_reason='其他原因';
           }
           $('#refund_id').val(refund_id);
           $('#refund_amount').val(refund_amount);
           $('#refund_reason').html(refund_reason);
           Custombox.open({
               target : '#refund-box',
               effect : 'blur',
               overlayClose : true,
               speed : 500,
               overlaySpeed : 300,
           });
       }
    });
    $(".onwork-quxiao").click(function () {
        Custombox.close();
        $(".agent_book_confirm_choose").prop('selected','selected');
    });
    function onworksubmit() {
        var formhash = $('#formhash').val();
        var book_id=$("#onwork_id").val();
        var book_code=$("#book_code").val();
        if(book_code==''){
            showwarning('onwork-box','请输入在岗码');
            return;
        }
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'book_id' : book_id,
            'book_code':book_code
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_book&op=onwork',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('设置成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 1000);
                        window.location.reload();
                    });
                }else{
                    showwarning('onwork-box', data.msg);
                }
            },
            timeout:1500,
            error:function (xhr, type) {
                showwarning('onwork-box', '网路不稳定，请稍候重试');
            }
        });
    }
    var refund_submit_btn = false;
    function refundsubmit() {
        var formhash = $('#formhash').val();
        var refund_id = $('#refund_id').val();
        var refund_amount = $('#refund_amount').val();
        var refund_reason=$('#refund_reason').text();
        var refund_state = $('#refund_state').val();
        if(refund_state != 1 && refund_state != 2) {
            showwarning('refund-box', '请选择处理方式');
            return;
        }
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'refund_id' : refund_id,
            'refund_amount' : refund_amount,
            'refund_state' : refund_state,
            'refund_reason':refund_reason
        };
        if(refund_submit_btn) return;
        refund_submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_book&op=refund',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                refund_submit_btn = false;
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('退款成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                            window.location.reload();
                        }, 2000);
                    });
                } else {
                    showwarning('refund-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                refund_submit_btn = false;
                showwarning('refund-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
//    $(".batch_onwork").click(function () {
//        if($('.checkitem.active').length == 0) {
//            showalert('请至少选择一个家政人员');
//            return;
//        }
//        var items = '';
//        $('.checkitem.active').each(function(){
//            items += $(this).attr('book_id') + ',';
//        });
//        items = items.substr(0, (items.length - 1));
//        $('#onworks_ids').val(items);
//        Custombox.open({
//            target : '#onworks-box',
//            effect : 'blur',
//            overlayClose : true,
//            speed : 500,
//            overlaySpeed : 300,
//        });
//    });
//    var onworks_submit_btn=false;
//    function onworkssubmit() {
//        var formhash = $('#formhash').val();
//        var book_ids=$("#onworks_ids").val();
//        var submitData={
//            'form_submit' : 'ok',
//            'formhash' : formhash,
//            'book_ids':book_ids
//        };
//        if(onworks_submit_btn) return;
//        onworks_submit_btn=true;
//        $.ajax({
//            type : 'POST',
//            url : 'index.php?act=agent_book&op=onworks',
//            data : submitData,
//            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
//            dataType : 'json',
//            success:function (data) {
//                console.log(data);
//                onworks_submit_btn=false;
//                if(data.done=='true'){
//                    Custombox.close(function() {
//                        $('.alert-box .tip-title').html('上岗成功，等待雇主确认');
//                        $('.alert-box').show();
//                        setTimeout(function() {
//                            $('.alert-box .tip-title').html('');
//                            $('.alert-box').hide();
//                            window.location.reload();
//                        }, 2000);
//                    });
//                }else{
//                    showwarning('onworks-box', data.msg);
//                }
//            },
//            timeout:15000,
//            error:function (xhr, type) {
//                onworks_submit_btn=false;
//                showwarning('onworks-box', '网路不稳定，请稍候重试');
//            }
//        });
//    }
//    $(".staff_set_list span").click(function () {
//        if(!$(".staff_set_list ul").is(":hidden")){
//            $(".staff_set_list ul").fadeOut();
//            $(".staff_set_list img").attr('src','templates/images/toBW.png');
//        }else{
//            $(".staff_set_list ul").fadeIn();
//            $(".staff_set_list img").attr('src','templates/images/toTopW.png');
//        }
//    })
</script>
<script>
    $(".agent_member_message_show").change(function () {
        if($(this).val()==3){
            console.log(1);
            var cancel_id=$(this).attr('data');
            $("#cancel_id").val(cancel_id);
            Custombox.open({
                target : '#cancel-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300
            });
        }
    });
    $(".cancel-quxiao").click(function () {
        $(".agent_member_message_show_chhoose").prop('selected','selected');
    });
    function cancelsubmit() {
        var cancel_id=$("#cancel_id").val();
        var submitData={
          'cancel_id':cancel_id
        };
        console.log(submitData);
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_book&op=cancel',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('取消成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 1000);
                        window.location.reload();
                    });
                }else{
                    showwarning('cancel-box', data.msg);
                }
            },
            timeout:1500,
            error:function (xhr, type) {
                showwarning('cancel-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>














