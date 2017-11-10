<?php include(template('common_header'));?>
<style>
    .header{
        border:1px solid #ddd;
    }
</style>
<link rel="stylesheet" href="templates/css/admin.css">
<div class="member_center_header">
    <img src="templates/images/my_tjz.png" alt="">
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
        <div class="user-right">
            <div class="center-title clearfix">
                <strong>可用团豆豆：</strong><strong><span class="red"><?php echo $this->member['member_coin'];?></span></strong>
                <strong>账户状态：</strong><strong><span class="red">有效</span></strong>
            </div>
            <div class="orderlist">
                <div class="orderlist-head clearfix">
                    <ul>
                        <li>
                            <a href="javascript:;">钱包收支明细</a>
                        </li>
                    </ul>
                </div>
                <table class="tb-void">
                    <thead>
                    <tr>
                        <th width="200">时间</th>
                        <th width="100">收支</th>
                        <th width="200">获取/支付方式</th>
                        <th>备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($coin_list as $key => $value) { ?>
                        <tr>
                            <td><span class="gray"><?php echo date('Y-m-d H:i', $value['true_time']);?></span></td>
                            <td><span class="<?php echo $value['markclass'];?>"><?php echo $value['coin_count'];?></span></td>
                            <td>
                                <?php if($value['get_type']=='register') { ?>
                                    注册赠送
                                <?php } else if($value['get_type']=='level_up') { ?>
                                    升级
                                <?php } else if($value['get_type']=='member_comment') {  ?>
                                    评价
                                <?php } else if($value['get_type']=='nurse_comment') {  ?>
                                    被评价
                                <?php } else if($value['get_type']=='sure_work') {  ?>
                                    确认到岗
                                <?php } else if($value['get_type']=='payment') {  ?>
                                    <?php if($value['get_state']==1){ ?>
                                        退款
                                     <?php } else { ?>
                                        付款
                                     <?php } ?>
                                <?php } else if($value['get_type']=='discount') {  ?>
                                    <?php if($value['get_state']==1){ ?>
                                        取消订单/退款
                                    <?php } else { ?>
                                        抵用折扣
                                    <?php } ?>
                                <?php } if($value['get_type']=='sign') {  ?>
                                    签到
                                <?php } else if($value['get_type']=='share') {  ?>
                                    分享
                                <?php } ?>
                            </td>
                            <td><div class="al"></div></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php echo $multi;?>
            </div>
        </div>
    </div>
</div>