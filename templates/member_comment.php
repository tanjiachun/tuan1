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
                <li><a href="index.php?act=member_wallet">我的钱包</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=member_comment">我的评价</a></li>
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
        <div id="member_manage_set" class="left" style="width:970px;margin:0 0 0 10px;">
            <div class="member_comment_show">
                <p><span>我的评价</span></p>
                <div class="orderlist" style="overflow: hidden;">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=member_comment&state=all"<?php echo $state=='all' ? ' class="active"' : '';?>>全部评价</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_comment&state=good"<?php echo $state=='good' ? ' class="active"' : '';?>>好评</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_comment&state=middle"<?php echo $state=='middle' ? ' class="active"' : '';?>>中评</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_comment&state=bad"<?php echo $state=='bad' ? ' class="active"' : '';?>>差评</a>
                            </li>
                            <li>
                                <a href="index.php?act=member_comment&state=back"<?php echo $state=='back' ? ' class="active"' : '';?>>有回复</a>
                            </li>
                        </ul>
                    </div>
                    <ul class="agent_comment_show_title">
                        <li>我的评价</li>
                        <li>家政人员回复</li>
                        <li>操作</li>
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
                                        家政人员：<?php echo $nurse_list[$value['nurse_id']]['nurse_nickname'] ?>
                                    </li>
                                    <li>
                                        结束时间：<?php echo date('Y-m-d H:i', $book_list[$value['book_id']]['book_finish_time']);?>
                                    </li>
                                    <li>
                                        交易时间：<?php echo $book_list[$value['book_id']]['work_duration'] ?>月 <?php echo $book_list[$value['book_id']]['work_duration_days'] ?>天

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
                                            <div class="empty_reply">
                                                家政人员未回复
                                            </div>
                                        <?php } else {?>
                                            <div class="agent_reply_content">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value['agent_reply_content'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="nurse_comment left">
                                        <?php if(time() < $value['comment_time'] +2592000 && $value['comment_revise_state'] !=1 && $value['comment_level']!='good') { ?>
                                            <div style="text-align: center;margin-top: 50px;">
                                                <span class="btn btn-default"><a href="index.php?act=member_comment&op=comment_resume&comment_id=<?php echo $value['comment_id'] ?>">修改评价</a></span>
                                                <p style="margin-top: 10px;">评价后30天内可修改一次</p>
                                            </div>
                                        <?php } else { ?>
                                            <div style="text-align: center;line-height: 250px;">
                                                <p>
                                                    评价已超过30天，或者已修改过一次，或好评无法修改
                                                </p>
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
<!--                                     <span class="btn btn-default del_member_comment_btn" style="margin-top: 10px;">删除</span>-->
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