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
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_evaluate">评价管理</a></li>
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
            <div class="agent_evaluate_show">
                <p><span>评价管理</span></p>
                <div class="orderlist" style="overflow: hidden;">
                    <div class="orderlist-head clearfix">
                    <ul>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=all"<?php echo $state=='all' ? ' class="active"' : '';?>>全部评价</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=nurse_reply"<?php echo $state=='nurse_reply' ? ' class="active"' : '';?>>未回复雇主</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=nurse_comment"<?php echo $state=='nurse_comment' ? ' class="active"' : '';?>>未评价雇主</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=comment_middle"<?php echo $state=='comment_middle' ? ' class="active"' : '';?>>雇主中评</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=comment_bad"<?php echo $state=='comment_bad' ? ' class="active"' : '';?>>雇主差评</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=nurse_good"<?php echo $state=='nurse_good' ? ' class="active"' : '';?>>我的好评</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=nurse_middle"<?php echo $state=='nurse_middle' ? ' class="active"' : '';?>>我的中评</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_evaluate&state=nurse_bad"<?php echo $state=='nurse_bad' ? ' class="active"' : '';?>>我的差评</a>
                        </li>
                    </ul>
                </div>
                    <ul class="agent_comment_show_title">
                        <li>雇主评价</li>
                        <li>我的回复</li>
                        <li>我的评价</li>
                    </ul>
                    <div class="agent_evaluate_details">
                        <?php foreach ($comment_list as $key => $value) { ?>
                            <div class="agent_evaluate_list">
                                <ul class="evaluate_list_message">
                                    <li>
                                        <span class="check checkitem" comment_id="<?php echo $value['comment_id'];?>"><i class="iconfont icon-type"></i></span>
                                    </li>
                                    <li>
                                        订单编号：<?php echo $book_list[$value['book_id']]['book_sn'] ?>
                                    </li>
                                    <li>
                                        雇主：<?php echo $book_list[$value['book_id']]['member_phone'] ?>
                                    </li>
                                    <li>
                                        员工：<?php echo $nurse_list[$value['nurse_id']]['nurse_name'] ?>
                                    </li>
                                    <li>
                                        成交时间：<?php echo date('Y-m-d H:i', $book_list[$value['book_id']]['finish_time']);?>
                                    </li>
                                    <li>
                                        交易天数：<?php echo $book_list[$value['book_id']]['work_duration_days'] ?>天
                                    </li>
                                    <li style="margin-right: 0;">
                                        交易金额：¥ <?php echo $book_list[$value['book_id']]['book_amount'] ?>
                                    </li>
                                </ul>
                                <div class="evaluate_list_content">
                                    <div class="member_comment left">
                                        <div class="member_comment_level">
                                            <div class="comment_level">
                                                <?php if($value['comment_level']=='good') { ?>
                                                    <span>得到好评 <img src="templates/images/comment_good.png" alt=""></span>
                                                <?php } else if($value['comment_level']=='middle') { ?>
                                                    <span>得到中评 <img src="templates/images/comment_middle.png" alt=""></span>
                                                <?php } else if($value['comment_level']=='bad') { ?>
                                                    <span>得到差评 <img src="templates/images/comment_bad.png" alt=""></span>
                                                <?php } ?>

                                            </div>
                                            <div class="comment_level_star">
                                                <p> 诚实守信
                                                    <?php for($i=0; $i<5; $i++) { ?>
                                                        <?php if($i < $value['comment_honest_star']) { ?>
                                                            <i class="iconfont icon-solidstar cur"></i>
                                                        <?php } else { ?>
                                                            <i class="iconfont icon-solidstar"></i>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </p>
                                                <p>爱岗敬业
                                                        <?php for($i=0; $i<5; $i++) { ?>
                                                            <?php if($i < $value['comment_love_star']) { ?>
                                                                <i class="iconfont icon-solidstar cur"></i>
                                                            <?php } else { ?>
                                                                <i class="iconfont icon-solidstar"></i>
                                                            <?php } ?>
                                                        <?php } ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="member_comment_content">
                                            <div class="left">评价内容</div>
                                            <div class="left"><?php echo $value['comment_content'] ?></div>
                                        </div>
                                        <div class="member_comment_image">
                                            <span>配图</span>
                                            <?php foreach ($value['comment_image'] as $k => $v) { ?>
                                                <img class="zoomify" src="<?php echo $v ?>" alt="">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="nurse_reply left">
                                        <?php if($value['agent_reply_content']=='') { ?>
                                            <textarea name="nurse_reply_content" maxlength="300" class="nurse_reply_content" cols="30" rows="10" placeholder="输出最多不超过300个字符"></textarea>
                                            <span class="nurse_reply_content_submit_btn" data="<?php echo $value['comment_id'] ?>">确认回复</span>
                                            <span class="return_success" style="display: block;"></span>
                                        <?php } else {?>

                                        <?php } ?>
                                        <div class="agent_reply_content">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value['agent_reply_content'] ?>
                                        </div>
                                    </div>
                                    <div class="nurse_comment left">
                                        <?php if($value['nurse_comment_state']==0) { ?>
                                            <div class="nurse_comment_level">
                                                <span>评价雇主</span>
                                                <input type="radio" value="good" name="member_level<?php echo $value['comment_id'] ?>" checked><img src="templates/images/comment_good.png" alt="">
                                                <input type="radio" value="middle" name="member_level<?php echo $value['comment_id'] ?>"><img src="templates/images/comment_middle.png" alt="">
                                                <input type="radio" value="bad" name="member_level<?php echo $value['comment_id'] ?>"><img src="templates/images/comment_bad.png" alt="">
                                            </div>
                                            <div class="nurse_comment_content">
                                                <div class="left">评价内容</div>
                                                <div class="left">
                                                    <textarea name="nurse_comment_content_textarea" maxlength="300" placeholder="输入最多不超过300个字符" class="nurse_comment_content_textarea" cols="30" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <div class="nurse_comment_image">
                                                <div class="picture-list voucher-list" style="margin-left: 20px;">
                                                    <ul class="clearfix">
                                                        <li id="show_image_<?php echo $value['comment_id'] ?>">
                                                            <a class="add-goods" href="javascript:;">
                                                                <i class="iconfont icon-camera"></i>
                                                                <span class="img-upload"><input type="file" id="file_<?php echo $value['comment_id'] ?>" name="file_<?php echo $value['comment_id'] ?>" field_id="<?php echo $value['comment_id'] ?>" hidefocus="true" maxlength="0" mall_type="image" mode="multi"></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="nurse_comment_submit">
                                                <span class="nurse_comment_submit_btn" data="<?php echo $value['comment_id'] ?>">确认评价</span>
                                                <span class="return_success" style="color: #ff6905;"></span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="nurse_comment_level">
                                                <span>评价雇主</span>
                                                <?php if($value['nurse_comment_level']=='good') { ?>
                                                    <img src="templates/images/comment_good.png" alt="">好评
                                                <?php } else if($value['nurse_comment_level']=='middle') { ?>
                                                    <img src="templates/images/comment_middle.png" alt="">中评
                                                <?php } else if ($value['nurse_comment_level']=='bad') { ?>
                                                    <img src="templates/images/comment_bad.png" alt="">差评
                                                <?php } ?>
                                                <?php if(time() < $value['nurse_comment_time'] +2592000 && $value['nurse_revise_state'] !=1 && $value['nurse_comment_level']!='good') { ?>
                                                <span class="btn btn-default"> <a href="index.php?act=agent_evaluate&op=nurse_comment_resume&comment_id=<?php echo $value['comment_id'] ?>">修改评价</a></span>
                                                <?php } else { ?>
                                                    （无法修改）
                                                <?php } ?>
                                            </div>
                                            <div class="nurse_comment_content">
                                                <div class="left">评价内容</div>
                                                <div class="left">
                                                    <?php echo $value['nurse_comment_content'] ?>
                                                </div>
                                            </div>
                                        <div class="nurse_comment_image_content">
                                            <?php foreach ($value['nurse_comment_image'] as $k => $v) { ?>
                                                <img class="zoomify" src="<?php echo $v ?>" alt="">
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        <?php } ?>
                        <?php if(!empty($comment_list)) { ?>
                            <tr class="tool-row">
                                <td colspan="11">
                                    <span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
                                    <?php if($state=='nurse_reply') { ?>
                                        <span style="margin-top: 10px;" class="btn btn-default nurse_unfiy_reply">统一回复</span>
                                    <?php } else if($state=='nurse_comment') { ?>
                                        <span style="margin-top: 10px;" class="btn btn-default nurse_unfiy_comment">统一评价</span>
                                    <?php } else { ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php echo $multi;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-wrap w-400" id="reply-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="reply_ids" name="reply_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <textarea name="reply_contents" id="reply_contents" cols="30" rows="10" maxlength="300" placeholder="请填写回复内容，最多不超过300个字符"></textarea>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="replysubmit();">确定</a>
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
<script type="text/javascript">
    var file_name = 'store';
</script>
<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="templates/js/imageupload.js"></script>
<script>
    $(function() {
        $('.zoomify').zoomify();
    });
</script>
<script>
    $(".nurse_reply_content_submit_btn").click(function () {
        var that=$(this);
        if(that.siblings(".nurse_reply_content").val()==''){
            that.siblings(".return_success").html('回复内容不能为空');
            setTimeout(function () {
                that.siblings(".return_success").html('');
            },3000);
            return;
        }
        var comment_id=that.attr('data');
        var agent_reply_content=that.siblings(".nurse_reply_content").val();
        var submitData={
            'comment_id':comment_id,
            'agent_reply_content':agent_reply_content
        };
        console.log(submitData);
        $.ajax({
            type:'POST',
            url:'index.php?act=agent_evaluate&op=agent_reply',
            data:submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                console.log(data);
                if(data.done=='true'){
                    that.siblings(".return_success").html('回复成功');
                    setTimeout(function () {
                        window.location.reload();
                    },3000);
                }else {
                    that.siblings(".return_success").html(data.msg);
                    setTimeout(function () {
                        that.siblings(".return_success").html('');
                    },3000);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                that.siblings(".return_success").html('网络不稳定，请稍后重试');
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000);
            }
        });
    });
</script>
<script>
    //评价雇主函数
    var nurse_comment_submit_btn = false;
$(".nurse_comment_submit_btn").click(function () {
    var that=$(this);
    var comment_id=that.attr('data');
    var nurse_comment_level=that.parent().siblings(".nurse_comment_level").children("input[type='radio']:checked").val();
    var nurse_comment_content=that.parent().siblings(".nurse_comment_content").children("div").children(".nurse_comment_content_textarea").val();
    var nurse_comment_image = {};
    var i=0;
    $(".image_"+comment_id).each(function() {
        nurse_comment_image[i] = $(this).val();
        i++;
    });
    if(nurse_comment_content==''){
        that.siblings(".return_success").html("评价内容不能为空");
        setTimeout(function () {
            that.siblings(".return_success").html("");
        },3000);
        return;
    }
    var submitData={
        'comment_id':comment_id,
        'nurse_comment_level':nurse_comment_level,
        'nurse_comment_content':nurse_comment_content,
        'nurse_comment_image':nurse_comment_image
    };
    if(nurse_comment_submit_btn) return;
    nurse_comment_submit_btn = true;
    $.ajax({
        type : 'POST',
        url : 'index.php?act=agent_evaluate&op=nurse_comment',
        data : submitData,
        contentType: 'application/x-www-form-urlencoded; charset=utf-8',
        dataType : 'json',
        success:function (data) {
            nurse_comment_submit_btn = false;
            if(data.done=='true'){
                that.siblings(".return_success").html("评价成功");
                setTimeout(function () {
                    window.location.reload();
                },2000);
            }else{
                that.siblings(".return_success").html(data.msg);
                setTimeout(function () {
                    that.siblings(".return_success").html("");
                },3000);
            }
        },
        timeout:15000,
        error:function (xhr, type) {
            nurse_comment_submit_btn = false;
            that.siblings(".return_success").html("网络不稳定，请稍后重试");
            setTimeout(function () {
                that.siblings(".return_success").html("");
            },3000);
        }
    });
});


</script>
<script>
    //统一回复
    $(".nurse_unfiy_reply").click(function () {
        if($('.checkitem.active').length == 0) {
            showalert('请至少选择一个订单');
            return;
        }
        var items = '';
        $('.checkitem.active').each(function(){
            items += $(this).attr('comment_id') + ',';
        });
        items = items.substr(0, (items.length - 1));
        console.log(items);
        $('#reply_ids').val(items);
        Custombox.open({
            target : '#reply-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function replysubmit() {
        var comment_ids=$("#reply_ids").val();
        var agent_reply_content=$("#reply_contents").val();
        if(agent_reply_content==''){
            showwarning('reply-box', '回复内容不能为空');
            return;
        }
        var submitData={
            'comment_ids':comment_ids,
            'agent_reply_content':agent_reply_content
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_evaluate&op=agent_replys',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('回复成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                            window.location.reload();
                        }, 2000);
                    });
                }else{
                    showwarning('reply-box', data.msg);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                showwarning('reply-box', '网路不稳定，请稍候重试');
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