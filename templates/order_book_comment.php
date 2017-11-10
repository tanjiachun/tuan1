<?php include(template('common_header'));?>
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
                            <a href="index.php?act=nurse&nurse_id=<?php echo $nurse['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse['nurse_image'];?>"></a>
                        </div>
                        <div class="p-name">
                            <a href="index.php?act=nurse&nurse_id=<?php echo $nurse['nurse_id'];?>" target="_blank"><?php echo $nurse['nurse_name'];?></a>
                        </div>
                        <div class="p-attr"><?php echo $book['work_duration'];?>个月</div>
                        <div class="p-price"><strong>￥<?php echo $book['book_amount'];?></strong></div>
                    </div>
                </div>
                <div class="voucher-info">
                    <div class="voucher-form">
                    	<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
                        <input type="hidden" id="book_id" name="book_id" value="<?php echo $book_id;?>" />
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">满意度评分</div>
                            <div class="voucher-form-value radio-box">
                            	<label class="radio" field_value="good" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    好评
                                </label>
                                <label class="radio" field_value="middle" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    中评
                                </label>
                                <label class="radio" field_value="bad" field_key="comment_level">
                                    <i class="iconfont icon-type"></i>
                                    差评
                                </label>
                            </div>
                            <input type="hidden" id="comment_level" name="comment_level" value="" />
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">诚实守信</div>
                            <div class="voucher-form-value">
                            	<fieldset class="rating">
                                    <input type="radio" id="hstar5" name="comment_honest_star" value="5" />
                                    <label class="full" for="hstar5"></label>
                                    <input type="radio" id="hstar4" name="comment_honest_star" value="4" />
                                    <label class="full" for="hstar4"></label>
                                    <input type="radio" id="hstar3" name="comment_honest_star" value="3" />
                                    <label class="full" for="hstar3"></label>
                                    <input type="radio" id="hstar2" name="comment_honest_star" value="2" />
                                    <label class="full" for="hstar2"></label>
                                    <input type="radio" id="hstar1" name="comment_honest_star" value="1" checked="checked" />
                                    <label class="full" for="hstar1"></label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">爱岗敬业</div>
                            <div class="voucher-form-value">
                            	<fieldset class="rating">
                                    <input type="radio" id="lstar5" name="comment_love_star" value="5" />
                                    <label class="full" for="lstar5"></label>
                                    <input type="radio" id="lstar4" name="comment_love_star" value="4" />
                                    <label class="full" for="lstar4"></label>
                                    <input type="radio" id="lstar3" name="comment_love_star" value="3" />
                                    <label class="full" for="lstar3"></label>
                                    <input type="radio" id="lstar2" name="comment_love_star" value="2" />
                                    <label class="full" for="lstar2"></label>
                                    <input type="radio" id="lstar1" name="comment_love_star" value="1" checked="checked" />
                                    <label class="full" for="lstar1"></label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="voucher-form-item clearfix">
                            <div class="voucher-form-label">评价描述</div>
                            <div class="voucher-form-value">
                                <div class="f-textarea">
                                    <textarea id="comment_content" name="comment_content" placeholder="家政人员是否给力？快分享你的服务感受吧~"></textarea>
                                    <div class="textarea-ext">还可输入500字</div>
                                </div>
                                <div class="picture-list voucher-list">
                                    <ul class="clearfix">
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
                <a href="javascript:commentsubmit();" class="btn-submit">提交</a><span class="return-success">评价成功</span>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		var file_name = 'nurse';
	</script>
	<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="templates/js/imageupload.js"></script>
    <script>
        var comment_submit_btn = false;
        function commentsubmit() {
            var formhash = $('#formhash').val();
            var book_id = $('#book_id').val();
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
                'book_id' : book_id,
                'comment_level' : comment_level,
                'comment_honest_star' : comment_honest_star,
                'comment_love_star' : comment_love_star,
                'comment_content' : comment_content,
                'comment_image' : comment_image,
            };
            console.log(submitData);
//            return;
            if(comment_submit_btn) return;
            comment_submit_btn = true;
            $.ajax({
                type : 'POST',
                url : 'index.php?act=order&op=book_comment',
                data : submitData,
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                dataType : 'json',
                success : function(data){
                    console.log(data);
                    return;
                    comment_submit_btn = false;
                    if(data.done == 'true') {
                        $('.return-success').html('评价成功');
                        $('.return-success').show();
                        setTimeout(function(){
                            window.location.href = 'index.php?act=member_book';
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