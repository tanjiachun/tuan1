<?php include(template('common_header'));?>
    <div id="message_header">
        <div class="message_header_content">
            <h1 class="left">团家政设置</h1>
            <h4 class="left">首页</h4>
        </div>
    </div>
</div>

<div id="member_content">
    <div id="member_content_show">
        <div class="member_content_left left">
            <ul>
                <li><span>●</span><a href="index.php?act=member_center">账号与安全</a></li>
                <li><span>●</span><a href="index.php?act=message_set">消息设置</a></li>
                <li><span>●</span><a href="javascript:;">帮助与反馈</a></li>
                <li><span>●</span><a href="index.php?act=article&article_id=1">关于团家政</a></li>
            </ul>
        </div>
        <div class="member_content_right left">
            <div class="member_set_header">
                <div class="left">
                    <?php if(empty($member['member_avatar'])) { ?>
                        <img src="templates/images/peopleicon_01.gif" alt="">
                    <?php } else { ?>
                        <img src="<?php echo $member['member_avatar'] ?>" alt="">
                    <?php } ?>
                </div>
                <div class="left" style="margin: 20px;">
                    <?php if(empty($member['member_nickname'])) { ?>
                        <?php echo $member['member_phone'] ?>
                    <?php } else { ?>
                        <?php echo $member['member_nickname'] ?>(<?php echo $member['member_phone'] ?>)
                    <?php } ?>
                </div>
                <div class="left" style="margin:50px 0 0 100px">
                    <a href="index.php?act=member_address_set">地址管理</a>
                </div>
                <div class="left" style="margin:50px 0 0 100px">
                    <a href="index.php?act=member_wallet">我的钱包</a>
                </div>
            </div>
            <div class="member_order_search">
                <span><a href="index.php?act=member_book&state=pending">待付款</a></span>
                <span><a href="index.php?act=member_book&state=duty">待上岗</a></span>
                <span><a href="index.php?act=member_book&state=evaluation">待评价</a></span>
            </div>
            <div class="member_fontprint">
                <div class="member_fontprint_header">
                    <span class="left">我的足迹</span><span class="right"><a href="index.php?act=member_set&state=rand"><img src="templates/images/shuaxin.png" alt="">换一批</a></span>
                </div>
                <div class="member_fontprint_content">
                    <ul>
                        <?php foreach ($footprint_list as $key => $value) { ?>
                            <li>
                                <div class="footprint_nurse_image">
                                    <a target="_blank" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'] ?>"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'] ?>" alt=""></a>
                                </div>
                                <div class="footprint_nurse_name">
                                    <span><?php echo $nurse_list[$value['nurse_id']]['nurse_nickname'] ?></span><span><?php echo $nurse_list[$value['nurse_id']]['service_type'] ?></span>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>