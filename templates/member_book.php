<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
        .orderlist-body .order-tb .tr-th{
            color: #000;
        }
    </style>
    <link rel="stylesheet" href="templates/css/admin.css">
    <div class="member_center_header">
        <img src="templates/images/my_tjz.png" alt="">
    </div>
</div>
<div id="member_manage">
    <div id="member_manage_content">
        <div id="member_manage_sidebar" class="left">
            <div class="member_manage_image">
                <?php if(empty($this->member['member_avatar'])) { ?>
                    <img width="100px" height="100px" src="templates/images/peopleicon_01.gif">
                <?php } else { ?>
                    <img width="100px" height="100px" src="<?php echo $this->member['member_avatar'];?>">
                <?php } ?>
            </div>
            <ul class="member_sidebar_list">
                <li class="staff_set_list">
                    <a class="list_show">账户与安全</a>
                    <ul style="display: none;">
                        <li><a href="index.php?act=member_center" style="font-size: 12px;">· 个人资料</a></li>
                        <li><a href="index.php?act=member_real_name" style="font-size: 12px;">· 实名认证</a></li>
                        <li><a href="index.php?act=member_address_set" style="font-size: 12px;">· 地址管理</a></li>
                    </ul>
                </li>
                <li><a href="index.php?act=member_password_set" >密码管理</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_book">我的订单</a></li>
                <li><a href="index.php?act=member_wallet">我的钱包</a></li>
                <li><a href="index.php?act=member_comment">我的评价</a></li>
                <li><a href="index.php?act=favorite&op=nurse">我的关注</a></li>
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
        <div id="member_manage_set" class="left">
            <div class="member_book_set">
                <p><span>我的订单</span></p>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=member_book"<?php echo empty($state) ? ' class="active"' : '';?>>全部订单</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_book&state=pending"<?php echo $state=='pending' ? ' class="active"' : '';?>>待付款</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_book&state=payment"<?php echo $state=='payment' ? ' class="active"' : '';?>>已付款</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_book&state=duty"<?php echo $state=='duty' ? ' class="active"' : '';?>>待上岗</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_book&state=evaluation"<?php echo $state=='evaluation' ? ' class="active"' : '';?>>待评价</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_book&state=finish"<?php echo $state=='finish' ? ' class="active"' : '';?>>已完成</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_book&state=cancel"<?php echo $state=='cancel' ? ' class="active"' : '';?>>已取消</a>
                            </li>
                        </ul>
                        <div class="pull-right">
                            <div class="search">
                                <form action="index.php" method="get" id="search_form">
                                    <input type="hidden" name="act" value="member_book">
                                    <input type="hidden" name="state" value="<?php echo $state;?>">
                                    <input type="text" id="search_name" name="search_name" class="itxt" placeholder="家政人员姓名/手机号/预约单号" value="<?php echo $search_name;?>">
                                    <a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="orderlist-body">
                        <table class="order-tb">
                            <thead>
                            <tr>
                                <th width="200">家政人员详情</th>
                                <th width="80">价格</th>
                                <th width="100">交易详情</th>
                                <th width="80">实付款</th>
                                <th width="100">状态与操作</th>
                            </tr>
                            </thead>
                            <?php foreach($book_list as $key => $value) { ?>
                                <tbody>
                                <tr class="sep-row"><td colspan="5"></td></tr>
                                <tr class="tr-th">
                                    <td colspan="5">
                                        <span class="check checkitem" book_id="<?php echo $value['book_id'];?>" agent_id="<?php echo $value['agent_id'] ?>" nurse_state="<?php echo $value['nurse_state'] ?>"><i class="iconfont icon-type"></i></span>
                                        <span><?php echo $agent_list[$value['agent_id']]['agent_name'];?></span>
                                        <span><?php echo date('Y-m-d H:i', $value['add_time']);?></span>
                                        <span>预约单号：<?php echo $value['book_sn'];?></span>
                                        <span> <a href="javascript:;" class="lianxi" data="<?php echo $nurse_list[$value['nurse_id']]['yx_accid'] ?>"><img style="margin: 0 5px 2px 0" src="templates/images/lianxi.png" alt="">和我联系</a></span>
                                        <?php if($state=='pending') { ?>
                                            <?php if($value['nurse_state']==2 || $value['nurse_state']==4) { ?>
                                                <span style="color: #ff6905;">该家政人员已在岗，无法付款</span>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr class="tr-bd">
                                    <td>
                                        <div class="td-inner clearfix w-200">
                                            <div class="item-pic">
                                                <a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'];?>"></a>
                                            </div>
                                            <div class="item-info">
                                                <a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><?php echo $nurse_list[$value['nurse_id']]['nurse_nickname'];?></a>
                                                <p><?php echo $nurse_list[$value['nurse_id']]['member_phone'];?></p>
                                            </div>
                                            <div class="item_image">
                                                <?php if($nurse_list[$value['nurse_id']]['promise_state']==2) { ?>
                                                    <img src="templates/images/three_days.jpg" alt="">三小时无理由
                                                <?php } elseif($nurse_list[$value['nurse_id']]['promise_state']==4) { ?>
                                                    <img src="templates/images/three_days.jpg" alt="">三天无理由
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($nurse_list[$value['nurse_id']]['nurse_type']==1 || $nurse_list[$value['nurse_id']]['nurse_type']==2) { ?>
                                            服务费 ¥<?php echo $value['service_price'] ?>
                                        <?php } else if($nurse_list[$value['nurse_id']]['nurse_type']==3) { ?>
                                            每小时 ¥<?php echo $value['nurse_price'];?>
                                        <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==4) { ?>
                                            每平方 ¥<?php echo $value['nurse_price'];?>
                                        <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==5 || $nurse_list[$value['nurse_id']]['nurse_type']==6) { ?>
                                            每月 ¥<?php echo $value['nurse_price'];?>
                                        <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==7 || $nurse_list[$value['nurse_id']]['nurse_type']==8) { ?>
                                            每次 ¥<?php echo $value['nurse_price'];?>
                                        <?php } elseif($nurse_list[$value['nurse_id']]['nurse_type']==9 || $nurse_list[$value['nurse_id']]['nurse_type']==10) { ?>
                                            每次 ¥<?php echo $value['nurse_price'];?>
                                        <?php } else { ?>
                                            服务费 ¥<?php echo $value['nurse_price'];?>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $value['book_details'] ?></td>
                                    <td>
                                        ￥<?php echo $value['book_amount'];?>
                                        <?php if($value['member_coin_amount'] > 0) { ?>
                                            <p style="color:#999;">（使用团豆豆<?php echo $value['member_coin_amount'];?>个 优惠 ￥<?php echo $value['member_coin_amount']/100;?>）</p>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <p class="state" id="state_<?php echo $value['book_id'];?>">
                                            <?php if(empty($value['book_state'])) { ?>
                                                <?php if(empty($value['refund_state'])) { ?>
                                                    已取消
                                                    <span data="<?php echo $value['book_id'] ?>" class="member_del_book_btn">删除</span>
                                                <?php } else { ?>
                                                    已退款
                                                    <span data="<?php echo $value['book_id'] ?>" class="member_del_book_btn">删除</span>
                                                <?php } ?>
                                            <?php } elseif($value['book_state'] == 10) { ?>
                                                待付款
                                            <?php } elseif($value['book_state'] == 20) { ?>
                                                <?php if(empty($value['refund_state'])) { ?>
                                                    <?php if($value['nurse_state'] ==2) { ?>
                                                        已在岗 <br>
                                                        <?php if($value['work_duration'] >1 && time()+604800>$value['finish_time'] && $value['finish_time']<$value['book_finish_time'] && $value['pay_count']<$value['work_duration']-1) { ?>
                                                            <span data="<?php echo $value['book_id'] ?>" class="renew_btn btn btn-gray">我要续费</span>
                                                        <?php } else { ?>
                                                            已续费（<?php echo $value['pay_count']+1 ?>/<?php echo $value['work_duration'] ?>）
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        已付款
                                                        <span data="<?php echo $value['book_id'] ?>" class="confirm_work_btn">确认已到岗</span>
                                                    <?php } ?>
                                                <?php } elseif($value['refund_state'] == 1) { ?>
                                                    待退款
                                                <?php } else { ?>
                                                    已拒绝
                                                <?php } ?>
                                            <?php } elseif($value['book_state'] == 30) { ?>
                                                <?php if(empty($value['comment_state'])) { ?>
                                                    待评价
                                                <?php } else { ?>
                                                    已评价
                                                <?php } ?>
                                            <?php } ?>
                                        </p>
                                        <div id="opr_<?php echo $value['book_id'];?>">
                                            <?php if($value['book_state'] == 10) { ?>
                                                <a href="index.php?act=book&op=payment&book_sn=<?php echo $value['book_sn'];?>" class="btn btn-primary">立即付款</a>
                                                <p><a href="javascript:;" class="book-cancel" book_id="<?php echo $value['book_id'];?>">取消预约</a></p>
                                            <?php } elseif($value['book_state'] == 20) { ?>
                                                <?php if($nurse_list[$value['nurse_id']]['promise_state']==2) { ?>
                                                    <?php if(time() < $value['work_time']+10800) { ?>
                                                        <?php if($value['refund_state'] != 1) { ?>
                                                            <a href="javascript:;" class="btn btn-primary book-refund" book_id="<?php echo $value['book_id'];?>" book_amount="<?php echo $value['book_amount'] ?>">我要退款</a>
                                                        <?php } ?>
                                                    <?php } else if(time()>intval($value['work_duration_days'])*86400+$value['work_time']) { ?>
                                                        <a href="javascript:;" class="btn btn-primary book-finish" book_id="<?php echo $value['book_id'];?>">完成服务</a>
                                                    <?php } ?>
                                                <?php } elseif($nurse_list[$value['nurse_id']]['promise_state']==4) { ?>
                                                    <?php if(time() < $value['work_time']+259200) { ?>
                                                        <?php if($value['refund_state'] != 1) { ?>
                                                            <a href="javascript:;" class="btn btn-primary book-refund" book_id="<?php echo $value['book_id'];?>" book_amount="<?php echo $value['book_amount'] ?>">我要退款</a>
                                                        <?php } ?>
                                                    <?php } else if(time()>intval($value['work_duration_days'])*86400+$value['work_time']) { ?>
                                                        <a href="javascript:;" class="btn btn-primary book-finish" book_id="<?php echo $value['book_id'];?>">完成服务</a>
                                                    <?php } ?>
                                                <?php } else if(time()>intval($value['work_duration_days'])*86400+$value['work_time']) { ?>
                                                    <a href="javascript:;" class="btn btn-primary book-finish" book_id="<?php echo $value['book_id'];?>">完成服务</a>
                                                <?php } ?>
                                            <?php } elseif($value['book_state'] == 30) { ?>
                                                <?php if(empty($value['comment_state'])) { ?>
                                                    <a href="index.php?act=member_book&op=book_comment&book_id=<?php echo $value['book_id'];?>" class="btn btn-primary">立即评价</a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="tr-ft">
                                    <td colspan="5">
                                        <div class="reture-exp">
                                            <label>雇主情况：<?php echo $value['book_message'];?></label>
                                        </div>
                                        <?php if(!empty($value['book_service'])) { ?>
                                            <div class="reture-exp">
                                                <label>额外服务：<?php echo $value['book_service'];?></label>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                                </tbody>
                            <?php } ?>
                            <?php if(!empty($book_list)) { ?>
                            <?php if($state=='pending') { ?>
                                    <tbody>
                                        <tr class="tool-row">
                                            <td colspan="11">
                                                <span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
                                                <span class="btn btn-default dismiss_nurse">合并付款</span>
                                                <span style="font-size: 12px;margin-left: 50px;"><span style="color: #ff6905;">*</span>同一家政机构可合并付款</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php } ?>
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
<div class="modal-wrap w-400" id="cancel-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="cancel_id" name="cancel_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">您确定要取消吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="cancelsubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="del-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="del_id" name="del_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">您确定要删除吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="delsubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="confirm-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="confirm_id" name="confirm_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">您确定家政人员到岗了吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="confirmsubmit();">确定</a>
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
                <input type="text" id="refund_reason" name="refund_reason" value="">
            </div>
        </div>
    </div>
    <div class="modal-ft">
        <a class="btn btn-default onwork-quxiao" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="refundsubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="finish-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="finish_id" name="finish_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">您确定完成服务了吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="finishsubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="dismiss-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="dismiss_ids" name="dismiss_ids" value="" />
    <input type="hidden" id="agent_id" name="agent_id" value="">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">您确定合并付款吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="dismisssubmit();">确定</a>
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
<div class="modal-wrap w-400" id="login-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-title">您还未登录了</h3>
                <div class="tip-hint">3 秒后页面跳转</div>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="index.php?act=login">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="renew-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="renew_id" name="renew_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">您确定要续费吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="renewsubmit();">确定</a>
    </div>
</div>
<input type="hidden" id="yx_accid" value="">
<script>
    $(".renew_btn").click(function () {
        var data=$(this).attr('data');
        $("#renew_id").val(data);
        Custombox.open({
            target : '#renew-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300
        });
    });
    function renewsubmit() {
        var book_id=$("#renew_id").val();
        var submitData={
            'book_id':book_id
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_book&op=book_renew',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('续费成功');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        },2000);
                    });
                } else {
                    showwarning('renew-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showwarning('renew-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
    $(".book-cancel").click(function () {
        var item=$(this).attr('book_id');
        $("#cancel_id").val(item);
        Custombox.open({
            target : '#cancel-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300
        });
    });
    var cancel_submit_btn = false;
    function cancelsubmit() {
        var formhash = $('#formhash').val();
        var cancel_id = $('#cancel_id').val();
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'cancel_id' : cancel_id,
        };
        if(cancel_submit_btn) return;
        cancel_submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_book&op=book_cancel',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                cancel_submit_btn = false;
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('#state_'+cancel_id).html('已取消');
                        $('.alert-box .tip-title').html('取消成功');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        },2000);
                    });
                } else {
                    showwarning('cancel-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                cancel_submit_btn = false;
                showwarning('cancel-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
$(".member_del_book_btn").click(function () {
    var item=$(this).attr('data');
    $("#del_id").val(item);
    Custombox.open({
        target : '#del-box',
        effect : 'blur',
        overlayClose : true,
        speed : 500,
        overlaySpeed : 300
    });
});
var del_submit_btn = false;
function delsubmit() {
    var formhash = $('#formhash').val();
    var del_id = $('#del_id').val();
    var submitData = {
        'form_submit' : 'ok',
        'formhash' : formhash,
        'del_id' : del_id,
    };
    if(del_submit_btn) return;
    del_submit_btn = true;
    $.ajax({
        type : 'POST',
        url : 'index.php?act=member_book&op=book_del',
        data : submitData,
        contentType: 'application/x-www-form-urlencoded; charset=utf-8',
        dataType : 'json',
        success : function(data){
            del_submit_btn = false;
            if(data.done == 'true') {
                Custombox.close(function() {
                    $('.alert-box .tip-title').html('删除成功');
                    $('.alert-box').show();
                    setTimeout(function () {
                        window.location.reload();
                    },2000)
                });
            } else {
                showwarning('del-box', data.msg);
            }
        },
        timeout : 15000,
        error : function(xhr, type){
            del_submit_btn = false;
            showwarning('del-box', '网路不稳定，请稍候重试');
        }
    });
}
</script>
<script>
    $(".confirm_work_btn").click(function () {
        var item=$(this).attr('data');
        $("#confirm_id").val(item);
        Custombox.open({
            target : '#confirm-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300
        });
    });
    var confirm_submit_btn = false;
    function confirmsubmit() {
        var formhash = $('#formhash').val();
        var confirm_id = $('#confirm_id').val();
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'confirm_id' : confirm_id,
        };
        if(confirm_submit_btn) return;
        confirm_submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_book&op=book_confirm',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                confirm_submit_btn = false;
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('确认成功');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        },2000)
                    });
                } else {
                    showwarning('confirm-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                confirm_submit_btn = false;
                showwarning('confirm-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
    $(".book-refund").click(function () {
        var item=$(this).attr('book_id');
        var price=$(this).attr('book_amount');
        $("#refund_id").val(item);
        $("#refund_amount").val(price);
        Custombox.open({
            target : '#refund-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300
        });
    });
    var refund_submit_btn = false;
    function refundsubmit() {
        var formhash = $('#formhash').val();
        var refund_id = $('#refund_id').val();
        var refund_amount=$("#refund_amount").val();
        var refund_reason=$("#refund_reason").val();
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'refund_id' : refund_id,
            'refund_amount':refund_amount,
            'refund_reason':refund_reason
        };
        if(refund_submit_btn) return;
        refund_submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=member_book&op=book_refund',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                refund_submit_btn = false;
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('退款成功');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        },2000)
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
    $('.book-finish').on('click', function() {
        var book_id = $(this).attr('book_id');
        $('#finish_id').val(book_id);
        Custombox.open({
            target : '#finish-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    var finish_submit_btn = false;
    function finishsubmit() {
        var formhash = $('#formhash').val();
        var finish_id = $('#finish_id').val();
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'finish_id' : finish_id,
        };
        if(finish_submit_btn) return;
        finish_submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=order&op=book_finish',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                finish_submit_btn = false;
                if(data.done == 'true') {
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('确认成功');
                        $('.alert-box').show();
                        setTimeout(function () {
                            window.location.reload();
                        },2000)
                    });
                } else {
                    showwarning('finish-box', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                finish_submit_btn = false;
                showwarning('finish-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
    $(".dismiss_nurse").click(function () {
        if($('.checkitem.active').length == 0) {
            showalert('请至少选择一个订单');
            return;
        }
        var item = [];
        var items = '';
        var item2=[];
        $('.checkitem.active').each(function(){
            items += $(this).attr('book_id') + ',';
            item.push(parseInt($(this).attr('agent_id')));
            item2.push(parseInt($(this).attr('nurse_state')));
        });
        items = items.substr(0, (items.length - 1));
        console.log(items);
        console.log(item);
        for(var i=0;i<item.length-1;i++){
            if(item[i]!=item[i+1]){
                showalert('只有属于同一家政机构的订单才可合并付款');
                return;
            }
        }
        for(var j=0;j<item2.length;j++){
            if(item2[j]==2 || item2[j]==4){
                showalert('无法对已在岗的家政人员付款');
                return;
            }
        }
        $("#dismiss_ids").val(items);
        $("#agent_id").val(item[0]);
        Custombox.open({
            target : '#dismiss-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function dismisssubmit(){
        var dismiss_ids=$("#dismiss_ids").val();
        var agent_id=$("#agent_id").val();
        console.log(dismiss_ids);
        window.location.href='index.php?act=member_book&op=dismiss&dismiss_ids='+dismiss_ids+'&agent_id='+agent_id;
//        var submitData={
//            'dismiss_ids':dismiss_ids
//        };
//        $.ajax({
//            type : 'POST',
//            url : 'index.php?act=member_book&op=dismiss',
//            data : submitData,
//            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
//            dataType : 'json',
//            success : function(data){
//                if(data.done == 'true') {
//                    Custombox.close(function() {
//                        $('.alert-box .tip-title').html('确认成功');
//                        $('.alert-box').show();
//                        setTimeout(function () {
//                            window.location.reload();
//                        },2000)
//                    });
//                } else {
//                    showwarning('dismiss-box', data.msg);
//                }
//            },
//            timeout : 15000,
//            error : function(xhr, type){
//                showwarning('dismiss-box', '网路不稳定，请稍候重试');
//            }
//        });
    }
</script>
<script src="templates/3rd/jquery-1.11.3.min.js"></script>
<script src="templates/im/js/config.js"></script>
<script src="templates/im/js/md5.js"></script>
<script src="templates/im/js/util.js"></script>
<script>
        var a='<?php echo $this->member['yx_accid'] ?>';
        var b='<?php echo $this->member['yx_token'] ?>';
    var member_id='<?php echo $this->member_id ?>';
    console.log(member_id)
//    var a='x433lYtjBbdDTS5k65Y15a9B1LgJ5KT2';
//    var b='jd9FnBzxsnesMSmfqgkXg4yNQ11Dlnml';
    $(".lianxi").click(function () {
        if(member_id==0 || member_id=='' || member_id==undefined){
            Custombox.open({
                target : '#login-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        }
        var data=$(this).attr('data');
        $("#yx_accid").val(data);
    })
</script>
<script>
    var Login = {
        init: function() {
            this.initNode();
            this.showNotice();
            this.initAnimation();
            this.addEvent();
        },

        initNode: function() {	// 初始化节点
            this.$account = $('#j-account');
            this.$pwd = $('#j-secret');
            this.$errorMsg = $('#j-errorMsg');
            this.$loginBtn = $('.lianxi');
            this.$footer = $('#footer');
        },

        initAnimation: function() {	// 添加动画
            var $wrapper = $('#j-wrapper'),
                wrapperClass = $wrapper.attr('class');
            $wrapper.addClass('fadeInDown animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).removeClass().addClass(wrapperClass);
            });
        },

        /**
         * 如果浏览器非IE10,Chrome, FireFox, Safari, Opera的话，显示提示
         */
        showNotice: function() {
            var browser = this.getBrowser(),
                temp = browser.split(' '),
                appname = temp[0],
                version = temp[1];
            if (['msie', 'firefox', 'opera', 'safari', 'chrome'].contains(appname)) {
                if (appname == 'msie' && version < 10) {
                    this.$footer.find('p').removeClass('hide');
                }
            } else {
                this.$footer.find('p').removeClass('hide');
            }
        },

        addEvent: function() {	// 绑定事件
            var that = this;
//            this.$loginBtn.on('click', this.validate.unbind("click").bind(this));
            this.$loginBtn.on('click', function() {
                that.validate();
            });
//            $(document).on('click', function() {
//                console.log(1);
//                var ev = e || window.event;
//                if (ev.keyCode === 13) {
//                    that.validate();
//                }
//            });
        },

        validate: function() {	// 登录验证
            var that = this,
                account = a,
                pwd = b,
                errorMsg = '';
            if (account.length === 0) {
                errorMsg = '帐号不能为空';
            } else if (!pwd || pwd.length < 6) {
                errorMsg = '密码长度至少6位';
            } else {
//                that.$loginBtn.html('登录中...').attr('disabled', 'disabled');
                that.requestLogin.call(that, account, pwd);
//                that.$loginBtn.html('登录').removeAttr('disabled');
            }
            that.$errorMsg.html(errorMsg).removeClass('hide');  // 显示错误信息
            return false;
        },
        //这里做了个伪登录方式（实际上是把accid，token带到下个页面连SDK在做鉴权）
        //一般应用服务器的应用会有自己的登录接口
        requestLogin: function(account, pwd) {
            setCookie('uid',account.toLocaleLowerCase());
            //自己的appkey就不用加密了
            // setCookie('sdktoken',pwd);
            setCookie('sdktoken',pwd);
            setCookie('toID',$("#yx_accid").val());
//            window.location.href = 'templates/im/main.html';
//            window.location.href = './main.html';
            window.open("templates/im/main.html");

        },
        /**
         * 获取浏览器的名称和版本号信息
         */
        getBrowser: function() {
            var browser = {
                msie: false,
                firefox: false,
                opera: false,
                safari: false,
                chrome: false,
                netscape: false,
                appname: 'unknown',
                version: 0
            }, ua = window.navigator.userAgent.toLowerCase();
            if (/(msie|firefox|opera|chrome|netscape)\D+(\d[\d.]*)/.test(ua)) {
                browser[RegExp.$1] = true;
                browser.appname = RegExp.$1;
                browser.version = RegExp.$2;
            } else if (/version\D+(\d[\d.]*).*safari/.test(ua)){ // safari
                browser.safari = true;
                browser.appname = 'safari';
                browser.version = RegExp.$2;
            }
            return browser.appname + ' ' + browser.version;
        }
    };
    Login.init();
</script>