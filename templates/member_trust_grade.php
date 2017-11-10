<?php include(template('common_header'));?>
<link rel="stylesheet" href="templates/css/admin.css">
<div id="member_trust_header">
    <div class="member_trust_header_content">
        <a href="index.php">
            <img src="templates/images/logo.png" alt="">
        </a>
        <span>雇主信用等级</span>
    </div>
</div>
</div>
<div id="member_trust_section">
    <div id="member_trust_section_content">
        <div class="member_trust_content_left left">
            <ul>
                <li><b>雇主个人信息</b></li>
                <li><b>昵称：</b>  <?php echo $member['member_nickname'] ?></li>
                <li><b>所在地区：</b>  <?php echo $member_cityname ?></li>
                <li><b>信用等级：</b> <img src="<?php echo $card['card_icon'] ?>" alt=""></li>
                <li><b>信用积分： </b> <?php echo $member['member_score'] ?></li>
            </ul>
        </div>
        <div class="member_trust_content_right left">
            <div class="member_good_chance">
                <span><b><?php echo $member['member_nickname'] ?></b>&nbsp;&nbsp;信用评价展示</span> <span>好评率<?php echo $good_count_chance ?>%</span>
            </div>
            <div class="orderlist-head clearfix">
                <ul>
                    <li>
                        <a href="index.php?act=member_trust_grade&member_id=<?php echo $member['member_id'] ?>&state=show"<?php echo $state=='show' ? ' class="active"' : '';?>>全部</a>
                    </li>
                    <li>
                        <a href="index.php?act=member_trust_grade&member_id=<?php echo $member['member_id'] ?>&state=one_mouth"<?php echo $state=='one_mouth' ? ' class="active"' : '';?>>最近1月</a>
                    </li>
                    <li>
                        <a href="index.php?act=member_trust_grade&member_id=<?php echo $member['member_id'] ?>&state=six_mouth"<?php echo $state=='six_mouth' ? ' class="active"' : '';?>>最近半年</a>
                    </li>
                    <li>
                        <a href="index.php?act=member_trust_grade&member_id=<?php echo $member['member_id'] ?>&state=one_year"<?php echo $state=='one_year' ? ' class="active"' : '';?>>最近1年</a>
                    </li>
                    <li>
                        <a href="index.php?act=member_trust_grade&member_id=<?php echo $member['member_id'] ?>&state=one_year_ago"<?php echo $state=='one_year_ago' ? ' class="active"' : '';?>>1年以前</a>
                    </li>
                </ul>
            </div>
            <div class="member_comment_count">
                <div>
                    <span><img src="templates/images/comment_good.png" alt="">好评</span>
                    <span><?php echo $good_count ?></span>
                </div>
                <div>
                    <span><img src="templates/images/comment_middle.png" alt="">中评</span>
                    <span><?php echo $middle_count ?></span>
                </div>
                <div>
                    <span><img src="templates/images/comment_bad.png" alt="">差评</span>
                    <span><?php echo $bad_count ?></span>
                </div>
                <div>
                    <span>退款</span>
                    <span><?php echo $refund_count ?></span>
                </div>
                <div>
                    <span>因违规被处罚</span>
                    <span>0</span>
                </div>
            </div>
        </div>

    </div>
</div>