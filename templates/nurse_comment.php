<?php foreach($comment_list as $key => $value) { ?>
    <style>
        .comment_img img {
            border: 1px solid #ddd;
            padding: 5px 5px;
            margin-left: 10px;
        }
    </style>
    <div class="comment_list">
        <div class="comment_list_content">
            <p><?php echo $value['comment_content'];?></p>
            <div class="order_message">
                <?php if(!empty($value['comment_image'])) { ?>
                    <div class="commit-img left">
                        <ul>
                            <?php foreach($value['comment_image'] as $subkey => $subvalue) { ?>
                                <li class="left"><img style="border:1px solid #ddd;padding: 5px 5px;margin-left: 10px;" class="zoomify" width="60px" height="60px" src="<?php echo $subvalue;?>"></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <div class="comment_user right">
                    <p><?php echo $member_list[$value['member_id']]['member_phone'];?>(匿名)</p>
                    <span><img src="templates/images/star.png" alt=""></span>
                </div>
                <div class="right" style="margin-right:30px;">
                    服务时长：
                    <?php if(!empty($book_list[$value['book_id']]['work_duration'])) { ?>
                        <?php echo $book_list[$value['book_id']]['work_duration'] ?>个月
                    <?php } else if(!empty($book_list[$value['book_id']]['work_duration_days'])) { ?>
                        <?php echo $book_list[$value['book_id']]['work_duration_days'] ?>天
                    <?php } else if(!empty($book_list[$value['book_id']]['work_duration_hours'])) { ?>
                        <?php echo $book_list[$value['book_id']]['work_duration_hours'] ?>小时
                    <?php } else if(!empty($book_list[$value['book_id']]['work_duration_mins'])) { ?>
                        <?php echo $book_list[$value['book_id']]['work_duration_mins'] ?>分钟
                    <?php } ?>
                </div>
            </div>
            <h5><?php echo date('Y年m月d日 H:i', $value['comment_time']);?></h5>
        </div>
    </div>
    <script>
        $('.zoomify').zoomify();
    </script>
<?php } ?>
<div class="multi-box"><?php echo $multi;?></div>

