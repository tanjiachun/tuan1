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
                    <li><a href="index.php?act=member_wallet">我的钱包</a></li>
                    <li><a href="index.php?act=member_comment">我的评价</a></li>
                    <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=favorite&op=nurse">我的关注</a></li>
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
                    <strong>关注的家政人员</strong>
                </div>
                <div class="orderlist">
                    <div class="collection-box">
                        <ul class="clearfix">
                            <?php foreach($nurse_ids as $key => $value) { ?>
                                <?php if(!empty($nurse_list[$value])) { ?>
                                    <li id="fav_<?php echo $nurse_list[$value]['nurse_id'];?>">
                                        <div class="collection-item">
                                            <div class="collection-img">
                                                <img style="height:100%" src="<?php echo $nurse_list[$value]['nurse_image'];?>">
                                            </div>
                                            <p class="collection-price"><strong><?php echo $nurse_list[$value]['nurse_name'];?></strong></p>
                                            <h1 class="collection-name">￥
                                                <?php if($nurse_list[$value]['nurse_type']==3) { ?>
                                                    <?php echo $nurse_list[$value]['nurse_price'];?>/时
                                                <?php } else if($nurse_list[$value]['nurse_type']==4) { ?>
                                                    <?php echo $nurse_list[$value]['nurse_price'];?>/平方
                                                <?php } else if($nurse_list[$value]['nurse_type']==7 || $nurse_list[$value]['nurse_type']==8 || $nurse_list[$value]['nurse_type']== 9 || $nurse_list[$value]['nurse_type']==10) { ?>
                                                    <?php echo $nurse_list[$value]['nurse_price'];?>/次
                                                <?php } else { ?>
                                                    <?php echo $nurse_list[$value]['nurse_price'];?>/月
                                                <?php } ?>
                                            </h1>
                                            <div class="collection-stats">
                                                <span><i class="iconfont icon-talk"></i><?php echo $nurse_list[$value]['nurse_commentnum'];?></span>
                                                <span><i class="iconfont icon-zan"></i><?php echo $nurse_list[$value]['nurse_viewnum'];?></span>
                                            </div>
                                            <div class="collection-operate">
                                                <a class="operate-btn cancel-btn favorite-del" href="javascript:;" fav_id="<?php echo $nurse_list[$value]['nurse_id'];?>" fav_type="nurse">取消关注</a>
                                                <a class="operate-btn" href="index.php?act=nurse&nurse_id=<?php echo $nurse_list[$value]['nurse_id'];?>">家政人员详情</a>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php echo $multi;?>
                </div>
            </div>
		</div>
	</div>
    <div class="alert-box" style="display:none;">
		<div class="alert alert-danger tip-title"></div>
	</div>
	<script type="text/javascript" src="templates/js/profile/favorite.js"></script>