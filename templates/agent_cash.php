<?php include(template('common_header'));?>
<style>
    .header{
        border:1px solid #ddd;
    }
</style>
<link rel="stylesheet" href="templates/css/admin.css">
<div class="member_center_header">
    <p>我的团家政 —— 我的钱包</p>
</div>
</div>
<div class="conwp">
    <div class="user-main">
        <div id="member_manage_sidebar" class="left">
            <div class="member_manage_image">
                <?php if(empty($this->member['member_avatar'])) { ?>
                    <img width="100px" height="100px" src="templates/images/peopleicon_01.gif">
                <?php } else { ?>
                    <img width="100px" height="100px" src="<?php echo $this->member['member_avatar'];?>">
                <?php } ?>
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
                <li><a href="index.php?act=agent_refund">退款查询</a></li>
                <li><a href="index.php?act=agent_marketing">营销管理</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_profit">财务中心</a></li>
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
        <div class="user-right">
            <div class="center-title clearfix">
                <strong>钱包提现</strong>
                <span class="pull-right">
						<a href="index.php?act=agent_profit" class="btn btn-default">返回</a>
					</span>
            </div>
            <div class="form-list">
                <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                <div class="form-item clearfix">
                    <label>充值账户：</label>
                    <div class="form-item-value"><?php echo $agent['member_phone'];?></div>
                </div>
                <div class="form-item clearfix">
                    <label>提现金额：</label>
                    <div class="form-item-value">
                        <input type="text" id="pdc_amount" name="pdc_amount" class="form-input w-100">&nbsp;元 <span class="t-tips">可提现金额￥<?php echo $available_amount ;?></span>
                    </div>
                </div>
                <div class="form-item clearfix">
                    <label>&nbsp;</label>
                    <div class="form-item-value">
                        <a class="btn btn-primary" href="javascript:checksubmit();">下一步</a>
                    </div>
                </div>
                <div class="b-tips">
                    <h3>温馨提示：</h3>
                    <p>
                        1. 提现成功后，可能存在延迟现象，一般2小时之内到账，如有问题，请咨询客服；<br>
                        2. 提现金额输入值必须是不小于10且不大于50000的正整数；<br>
                        3. 提现完成后，您可以进入相关账户查看到账状态。
                    </p>
                </div>
            </div>
        </div>
    </div>
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
    var agent_id='<?php echo $agent['agent_id'] ?>';
    console.log(agent_id);
</script>
<script>
    var submit_btn = false;
function checksubmit() {
    var formhash = $('#formhash').val();
    var pdc_amount = $('#pdc_amount').val();
    if(pdc_amount == '') {
        showalert('请输入提现金额');
        return;
    }
    var regu = /^\d+$/;
    if(!regu.test(pdc_amount)) {
        showalert('提现金额必须是正整数');
        return;
    }
    if(pdc_amount < 10) {
        showalert('提现金额不能小于10');
        return;
    }
    if(pdc_amount > 50000) {
        showalert('提现金额不能大于50000');
        return;
    }
    var submitData = {
        'form_submit' : 'ok',
        'formhash' : formhash,
        'agent_id':agent_id,
        'pdc_amount' : pdc_amount,
    };
    if(submit_btn) return;
    submit_btn = true;
    $.ajax({
        type : 'POST',
        url : 'index.php?act=agent_cash',
        data : submitData,
        contentType: 'application/x-www-form-urlencoded; charset=utf-8',
        dataType : 'json',
        success : function(data) {
            submit_btn = false;
            if(data.done == 'true') {
                window.location.href = 'index.php?act=agent_cash&op=step2&agent_id='+agent_id;
            } else {
                showalert(data.msg);
            }
        },
        timeout : 15000,
        error : function(xhr, type){
            submit_btn = false;
            showalert('网路不稳定，请稍候重试');
        }
    });
}
</script>