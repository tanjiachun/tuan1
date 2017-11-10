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
        <p>我的团家政 —— 我的评价</p>
    </div>
</div>
    <div class="conwp">
        <div class="voucher-wrap">
            <div class="voucher-head">
                <h1>家政人员评价</h1>
                <p>预约单号：<a href="javascript:;"><?php echo $book['book_sn'];?></a><?php echo date('Y-m-d H:i', $book['add_time']);?></p>
            </div>
            <div class="voucher-body clearfix">
                <div class="voucher-goods">
                    <div class="comment-goods">
                        <div class="p-img">
                            <a href="index.php?act=nurse&nurse_id=<?php echo $comment['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse['nurse_image'];?>"></a>
                        </div>
                        <div class="p-name">
                            <a href="index.php?act=nurse&nurse_id=<?php echo $comment['nurse_id'];?>" target="_blank"><?php echo $nurse['nurse_name'];?></a>
                        </div>
                        <div class="p-attr"><?php echo $book['work_duration'];?>个月</div>
                        <div class="p-price"><strong>￥<?php echo $book['book_amount'];?></strong></div>
                    </div>
                </div>
                <div class="voucher-info">
                    <div class="voucher-form">
                        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">满意度评分</div>
                            <div class="voucher-form-value radio-box">
                                <label <?php echo $comment['comment_level']=='good' ? ' class="active radio"' : ' class="radio"';?> field_value="good" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    好评
                                </label>
                                <label <?php echo $comment['comment_level']=='middle' ? ' class="active radio"' : ' class="radio"';?> field_value="middle" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    中评
                                </label>
                                <label <?php echo $comment['comment_level']=='bad' ? ' class="active radio"' : ' class="radio"';?> field_value="bad" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    差评
                                </label>
                            </div>
                            <input type="hidden" id="comment_level" name="comment_level" value="<?php echo $comment['comment_level'] ?>" />
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">诚实守信</div>
                            <div class="voucher-form-value">
                                <fieldset class="rating">
                                    <input type="radio" id="hstar5" name="comment_honest_star" value="5" <?php echo $comment['comment_honest_star']==5 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="hstar5"></label>
                                    <input type="radio" id="hstar4" name="comment_honest_star" value="4" <?php echo $comment['comment_honest_star']==4 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="hstar4"></label>
                                    <input type="radio" id="hstar3" name="comment_honest_star" value="3" <?php echo $comment['comment_honest_star']==3 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="hstar3"></label>
                                    <input type="radio" id="hstar2" name="comment_honest_star" value="2" <?php echo $comment['comment_honest_star']==2 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="hstar2"></label>
                                    <input type="radio" id="hstar1" name="comment_honest_star" value="1" <?php echo $comment['comment_honest_star']==1 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="hstar1"></label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">爱岗敬业</div>
                            <div class="voucher-form-value">
                                <fieldset class="rating">
                                    <input type="radio" id="lstar5" name="comment_love_star" value="5" <?php echo $comment['comment_love_star']==5 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="lstar5"></label>
                                    <input type="radio" id="lstar4" name="comment_love_star" value="4" <?php echo $comment['comment_love_star']==4 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="lstar4"></label>
                                    <input type="radio" id="lstar3" name="comment_love_star" value="3" <?php echo $comment['comment_love_star']==3 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="lstar3"></label>
                                    <input type="radio" id="lstar2" name="comment_love_star" value="2" <?php echo $comment['comment_love_star']==2 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="lstar2"></label>
                                    <input type="radio" id="lstar1" name="comment_love_star" value="1" <?php echo $comment['comment_love_star']==1 ? 'checked="checked"' :'' ?>/>
                                    <label class="full" for="lstar1"></label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">评价描述</div>
                            <div class="voucher-form-value">
                                <div class="f-textarea">
                                    <textarea id="comment_content" name="comment_content" placeholder="家政人员是否给力？快分享你的服务感受吧~"><?php echo $comment['comment_content'] ?></textarea>
                                    <div class="textarea-ext">还可输入500字</div>
                                </div>
                                <div class="picture-list voucher-list">
                                    <ul class="clearfix">
                                        <?php foreach($comment['comment_image'] as $key => $value) { ?>
                                            <li class="cover-item">
                                                <img src="<?php echo $value;?>">
                                                <span class="close-modal multi_close">×</span>
                                                <input type="hidden" name="image_0[]" class="image_0" value="<?php echo $value;?>">
                                            </li>
                                        <?php } ?>
                                        <li id="show_image_0">
                                            <a class="add-goods" href="javascript:;">
                                                <i class="iconfont icon-camera"></i>
                                                <span class="img-upload"><input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="multi"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="f-btnbox">
                <a href="javascript:commentsubmit();" class="btn-submit">修改</a><span class="return-success">修改成功</span>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var file_name = 'nurse';
        var comment_id='<?php echo $comment['comment_id'] ?>';
    </script>
    <script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
    <script type="text/javascript" src="templates/js/imageupload.js"></script>
    <script>
        var comment_submit_btn = false;
        function commentsubmit() {
            var formhash = $('#formhash').val();
            var comment_level = $('#comment_level').val();
            var comment_honest_star = $('[name="comment_honest_star"]:checked').val();
            var comment_love_star = $('[name="comment_love_star"]:checked').val();
            var comment_content = $('#comment_content').val();
            var i = 0;
            var comment_image = {};
            $('.image_0').each(function() {
                comment_image[i] = $(this).val();
                i++;
            });
            if(comment_level == '') {
                showalert('请选择满意度评分');
                return;
            }
            if(comment_content == '') {
                showalert('请至少写点你的服务感受');
                return;
            }
            var submitData = {
                'form_submit' : 'ok',
                'formhash' : formhash,
                'comment_id':comment_id,
                'comment_level' : comment_level,
                'comment_honest_star' : comment_honest_star,
                'comment_love_star' : comment_love_star,
                'comment_content' : comment_content,
                'comment_image' : comment_image
            };
            if(comment_submit_btn) return;
            comment_submit_btn = true;
            $.ajax({
                type : 'POST',
                url : 'index.php?act=member_comment&op=comment_resume',
                data : submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType : 'json',
                success : function(data){
                    comment_submit_btn = false;
                    if(data.done == 'true') {
                        $('.return-success').html('修改成功');
                        $('.return-success').show();
                        setTimeout(function(){
                            window.location.href = 'index.php?act=member_comment';
                        }, 1000);
                    } else {
                        showalert(data.msg);
                    }
                },
                timeout : 15000,
                error : function(xhr, type){
                    comment_submit_btn = false;
                    showalert('网路不稳定，请稍候重试');
                }
            });
        }

    </script>
<?php include(template('common_footer'));?>