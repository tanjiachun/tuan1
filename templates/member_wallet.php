<?php include(template('common_header'));?>
<link rel="stylesheet" href="templates/css/admin.css">
<style>
        .header{
            border:1px solid #ddd;
        }
        .orderlist-body .order-tb .tr-th{
            color: #000;
        }
        .center-title{
            margin: 0;
            padding:10px 20px;
        }
    </style>
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
                <li><a href="index.php?act=member_book">我的订单</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_wallet">我的钱包</a></li>
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
            <div class="member_wallet_message">
                <p><span>我的钱包</span></p>
                <div class="center-title clearfix">
                    <strong>可用金额：</strong><strong><span class="red">￥<?php echo $this->member['available_predeposit'];?></span></strong>
                    <strong>账户状态：</strong><strong><span class="red">有效</span></strong>
                    <span class="pull-right">
                        <a href="index.php?act=predeposit" class="btn btn-line-primary">查看明细</a>
                        <a href="index.php?act=cash" class="btn btn-line-primary">提现</a>
                        <a href="index.php?act=recharge" class="btn btn-primary">充值</a>
                    </span>
                </div>
                <div class="center-title clearfix">
                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;团豆豆：</strong><strong><span class="red"><?php echo $this->member['member_coin'];?></span></strong>
                    <strong>账户状态：</strong><strong><span class="red">有效</span></strong>
                    <span class="pull-right">
                        <a href="index.php?act=member_coin_details" class="btn btn-line-primary">查看明细</a>
                    </span>
                </div>
<!--                <div>-->
<!--                    <span>￥--><?php //echo $this->member['available_predeposit'];?><!--</span>-->
<!--                    <p>账户余额</p>-->
<!--                    <a href="index.php?act=cash" class="btn btn-line-primary">提现</a>-->
<!--                    <a href="index.php?act=recharge" class="btn btn-primary">充值</a>-->
<!--                    <div><a href="index.php?act=predeposit">收支明细</a></div>-->
<!--                </div>-->
<!--                <div>-->
<!--                    <span>1</span>-->
<!--                    <p>抵用券</p>-->
<!--                </div>-->
<!--                <div>-->
<!--                    <span>200</span>-->
<!--                    <p>团豆豆</p>-->
<!--                </div>-->
            </div>
<!--            <div class="member_card_add">-->
<!--                <p><span>添加银行卡</span></p>-->
<!--                <div class="user-right">-->
<!--                    <div class="user-info">-->
<!--                        <div class="form-list">-->
<!--                            <input type="hidden" id="formhash" name="formhash" value="--><?php //echo formhash();?><!--" />-->
<!--                            <div class="form-item clearfix">-->
<!--                                <label><span class="red">*</span>所属银行：</label>-->
<!--                                <div class="form-item-value">-->
<!--                                    <input type="text" id="card_bank" name="card_bank" value="" class="form-input w-300">-->
<!--                                    <div class="Validform-checktip Validform-wrong"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-item clearfix">-->
<!--                                <label><span class="red">*</span>银行卡号：</label>-->
<!--                                <div class="form-item-value">-->
<!--                                    <input type="text" id="card_id" name="card_id" value="" class="form-input w-300" placeholder="请输入银行卡号">-->
<!--                                    <div class="Validform-checktip Validform-wrong"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-item clearfix">-->
<!--                                <label><span class="red">*</span>开户人：</label>-->
<!--                                <div class="form-item-value">-->
<!--                                    <input type="text" id="card_name" name="card_name" value="" class="form-input w-300" placeholder="请输入开户人姓名">-->
<!--                                    <div class="Validform-checktip Validform-wrong"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-item clearfix">-->
<!--                                <label><span class="red">*</span>手机号码：</label>-->
<!--                                <div class="form-item-value">-->
<!--                                    <input type="text" id="card_phone" name="card_phone" value="" class="form-input w-300" placeholder="请输入预留号码">-->
<!--                                    <div class="Validform-checktip Validform-wrong"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-item clearfix" style="position: relative">-->
<!--                                <label><span class="red">*</span>短信验证码：</label>-->
<!--                                <div class="form-item-value">-->
<!--                                    <input type="text" id="card_phone_code" name="card_phone_code" value="" class="form-input w-300" placeholder="请输入获取的验证码">-->
<!--                                    <span class="take-card-code">获取验证码</span>-->
<!--                                    <div class="Validform-checktip Validform-wrong"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-item clearfix">-->
<!--                                <label></label>-->
<!--                                <div class="form-item-value">-->
<!--                                    <input type="checkbox" id="member_agree" name="member_agree" value="" checked>同意&lt;&lt;<a href="javascript:;">团家政钱包协议</a>&gt;&gt;以及&lt;&lt;<a href="javascript:;">银联在线支付开通协议</a>&gt;&gt;-->
<!--                                    <div class="Validform-checktip Validform-wrong"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="form-item clearfix">-->
<!--                                <label></label>-->
<!--                                <div class="form-item-value">-->
<!--                                    <span class="member_submit_btn" id="member_submit_btn">保存</span>-->
<!--                                    <span class="return_success" style="color: #ff6905;"></span>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="member_card_set">-->
<!--                <p><span>管理银行卡</span></p>-->
<!--                <div class="orderlist">-->
<!--                    <div class="orderlist-body">-->
<!--                        <table class="order-tb">-->
<!--                            <thead>-->
<!--                            <tr>-->
<!--                                <th width="150">开户行</th>-->
<!--                                <th width="220">卡号</th>-->
<!--                                <th width="150">支付方式</th>-->
<!--                                <th width="150">操作</th>-->
<!--                                <th width="150">状态</th>-->
<!--                            </tr>-->
<!--                            </thead>-->
<!--                            <tbody id="address_--><?php //echo $value['member_address_id'];?><!--">-->
<!--                                <tr class="tr-bd">-->
<!--                                    <td>邮政储蓄银行</td>-->
<!--                                    <td>6217993000306460182</td>-->
<!--                                    <td>快捷支付</td>-->
<!--                                    <td>解绑</td>-->
<!--                                    <td>设为默认</td>-->
<!--                                </tr>-->
<!--                                <tr class="tr-bd">-->
<!--                                    <td>邮政储蓄银行</td>-->
<!--                                    <td>6217993000306460182</td>-->
<!--                                    <td>快捷支付</td>-->
<!--                                    <td>解绑</td>-->
<!--                                    <td>设为默认</td>-->
<!--                                </tr>-->
<!--                            </tbody>-->
<!--                            --><?php //foreach($address_list as $key => $value) { ?>
<!--                                <tbody id="address_--><?php //echo $value['member_address_id'];?><!--">-->
<!--                                <tr class="tr-bd">-->
<!--                                    <td>--><?php //echo $value['address_member_name'] ?><!--</td>-->
<!--                                    <td>--><?php //echo $value['member_areainfo'] ?><!--</td>-->
<!--                                    <td>--><?php //echo $value['address_content'] ?><!--</td>-->
<!--                                    <td>--><?php //echo $value['address_phone'] ?><!--</td>-->
<!--                                    <td><a href="index.php?act=member_address_set&op=address_resume&member_address_id=--><?php //echo $value['member_address_id'] ?><!--">修改</a>/-->
<!--                                        <a href="javascript:;" class="del_address_btn" data="--><?php //echo $value['member_address_id'] ?><!--">删除</a></td>-->
<!--                                    --><?php //if($value['member_address_id']==$this->member['show_address_id']) { ?>
<!--                                        <td>默认选中</td>-->
<!--                                    --><?php //} else { ?>
<!--                                        <td></td>-->
<!--                                    --><?php //} ?>
<!--                                </tr>-->
<!--                                </tbody>-->
<!--                            --><?php //} ?>
<!--                        </table>-->
<!--                    </div>-->
<!--                    --><?php //echo $multi;?>
<!--                </div>-->
<!---->
<!--            </div>-->
        </div>
    </div>
</div>