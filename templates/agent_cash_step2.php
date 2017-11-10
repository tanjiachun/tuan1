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
						<a href="index.php?act=agent_cash&agent_id=<?php echo $agent['agent_id'] ?>" class="btn btn-default">返回</a>
					</span>
            </div>
            <div class="form-list">
                <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                <div class="form-item clearfix">
                    <label>提现金额：</label>
                    <div class="form-item-value"><?php echo $pdc_amount;?></div>
                </div>
                <div class="form-item clearfix">
                    <input type="hidden" id="pdc_code" name="pdc_code" value="<?php echo $cash['pdc_code'];?>" />
                    <label>提现至：</label>
                    <div class="form-item-value">
                        <div class="select-class w-400 cash-box">
                            <a href="javascirpt:;" class="select-choice"><?php echo $cash_type[$cash['pdc_code']];?><i class="select-arrow"></i></a>
                            <div class="select-list" style="display: none">
                                <ul>
                                    <li field_value="alipay" field_key="pdc_code">支付宝</li>
<!--                                    <li field_value="weixin" field_key="pdc_code">微信</li>-->
<!--                                    <li field_value="bank" field_key="pdc_code">银行卡</li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-item clearfix alipay"<?php if($cash['pdc_code'] != 'alipay') {?> style="display:none;"<?php } ?>>
                    <label>支付宝帐号：</label>
                    <div class="form-item-value"><input type="text" id="alipay_card" name="alipay_card" class="form-input w-400" value="<?php echo $cash['alipay_card'];?>"></div>
                </div>
                <div class="form-item clearfix weixin"<?php if($cash['pdc_code'] != 'weixin') {?> style="display:none;"<?php } ?>>
                    <label>微信号：</label>
                    <div class="form-item-value"><input type="text" id="weixin_card" name="weixin_card" class="form-input w-400" value="<?php echo $cash['weixin_card'];?>"></div>
                </div>
                <div class="form-item clearfix bank"<?php if($cash['pdc_code'] != 'bank') {?> style="display:none;"<?php } ?>>
                    <label>收款人：</label>
                    <div class="form-item-value"><input type="text" id="bank_membername" name="bank_membername" class="form-input w-400" value="<?php echo $cash['bank_membername'];?>"></div>
                </div>
                <div class="form-item clearfix bank"<?php if($cash['pdc_code'] != 'bank') {?> style="display:none;"<?php } ?>>
                    <label>开户行：</label>
                    <div class="form-item-value"><input type="text" id="bank_deposit" name="bank_deposit" class="form-input w-400" value="<?php echo $cash['bank_deposit'];?>"></div>
                </div>
                <div class="form-item clearfix bank"<?php if($cash['pdc_code'] != 'bank') {?> style="display:none;"<?php } ?>>
                    <label>银行卡号：</label>
                    <div class="form-item-value"><input type="text" id="bank_card" name="bank_card" class="form-input w-400" value="<?php echo $cash['bank_card'];?>"></div>
                </div>
                <div class="form-item clearfix">
                    <label>&nbsp;</label>
                    <div class="form-item-value">
                        <a class="btn btn-primary" href="javascript:checkcash();">确定</a><span class="return-success"></span>
                    </div>
                </div>
                <div class="b-tips">
                    <h3>温馨提示：</h3>
                    <p>
                        1. 提现成功后，可能存在延迟现象，一般2小时内到账，如有问题，请咨询客服；<br>
                        2. 提现金额输入值必须是不小于10且不大于50000的正整数；<br>
                        3. 提现完成后，您可以进入相关账户查看到账状态。
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-wrap w-400" id="cash-box" style="display:none;">
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-check"></i>
					</span>
                <h3 class="tip-message">我们已经接收到您的提现申请</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-primary" href="index.php?act=predeposit">确定</a>
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
    var cash_submit_btn = false;
    function checkcash() {
        var formhash = $('#formhash').val();
        var pdc_code = $('#pdc_code').val();
        var alipay_card = $('#alipay_card').val();
        var weixin_card = $('#weixin_card').val();
        var bank_membername = $('#bank_membername').val();
        var bank_deposit = $('#bank_deposit').val();
        var bank_card = $('#bank_card').val();
        if(pdc_code == 'alipay') {
            if(alipay_card == '') {
                showalert('请输入支付宝帐号');
                return;
            }
        } else if(pdc_code == 'weixin') {
            if(weixin_card == '') {
                showalert('请输入微信号');
                return;
            }
        } else if(pdc_code == 'bank') {
            if(bank_membername == '') {
                showalert('请输入收款人');
                return;
            }
            if(bank_deposit == '') {
                showalert('请输入开户行');
                return;
            }
            if(bank_card == '') {
                showalert('请输入银行卡号');
                return;
            }
        }
        var submitData = {
            'form_submit' : 'ok',
            'formhash' : formhash,
            'agent_id':agent_id,
            'pdc_code' : pdc_code,
            'alipay_card' : alipay_card,
            'weixin_card' : weixin_card,
            'bank_membername' : bank_membername,
            'bank_deposit' : bank_deposit,
            'bank_card' : bank_card,
        };
        if(cash_submit_btn) return;
        cash_submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_cash&op=step2',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                cash_submit_btn = false;
                if(data.done == 'true') {
                    $('.return-success').html('我们已经接收到您的提现申请');
                    $('.return-success').show();
                    setTimeout(function(){
                        window.location.href = 'index.php?act=agent_profit';
                    }, 1000);
                } else {
                    showalert(data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                cash_submit_btn = false;
                showalert('网路不稳定，请稍候重试');
            }
        });
    }


    $(function() {
        $('.cash-box').on('click', '.select-choice', function() {
            $(this).siblings('.select-list').toggle();
        });

        $('.cash-box').on('click', 'li', function() {
            var field_key = $(this).attr('field_key');
            var field_value = $(this).attr('field_value');
            $('#'+field_key).val(field_value);
            $(this).parent().parent().hide();
            $(this).parent().parent().siblings('.select-choice').html($(this).text()+'<i class="select-arrow"></i>');
            if(field_value == 'alipay') {
                $('.alipay').show();
                $('.weixin').hide();
                $('.bank').hide();
            } else if(field_value == 'weixin') {
                $('.alipay').hide();
                $('.weixin').show();
                $('.bank').hide();
            } else if(field_value == 'bank') {
                $('.alipay').hide();
                $('.weixin').hide();
                $('.bank').show();
            }
        });
    });
</script>