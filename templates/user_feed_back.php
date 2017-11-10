<?php include(template('common_header'));?>
    <link rel="stylesheet" href="templates/css/admin.css">
    <div class="member_center_header" style="border-bottom: 2px solid #ff6905;">
        <ul>
            <li class="left" style="padding-right: 20px;"><img src="templates/images/tjz.png" alt=""></li>
            <li class="left" style="font-size: 24px;">|</li>
            <li class="left" style="height:40px;line-height: 40px;display: inline-block;margin-top: 30px;padding-left: 20px;"><span style="font-size: 26px;">用户反馈调查</span></li>
        </ul>
    </div>
</div>
<style>
    *{
        color: #000;
    }
    .select-box{
        width:1180px;
        height:180px;
        border: 1px solid #cce8ff;
    }
    .select-box p{
        width:100%;
        height:30px;
        line-height: 30px;
        background: #eef6ff;
    }
    .input-box{
        width: 1180px;
        height:400px;
        border: 1px solid #cce8ff;
        margin-top: 20px;
        overflow: hidden;
    }
    .input-box p{
        width:100%;
        height:30px;
        line-height: 30px;
        background: #eef6ff;
    }
    textarea{
        margin:30px 0 0 50px;
        width:500px;
        border-color: #64b9ff;
    }
    .submit-btn{
        display: inline-block;
        width: 100px;
        text-align: center;
        height:40px;
        line-height: 40px;
        background: #ff6905;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
<div style="width:1180px;margin:0 auto;">
    <div style="color:#000;">
        <br>
        <br>
        <p style="margin-left: 50px;">尊敬的用户：</p>
        <p style="margin-left: 50px;">您好！为了给您提供更好的服务，我们希望收集您使用<span style="color: #ff6905;">团家政</span>的看法或建议。对您的配合和支持表示衷心感谢!</p>
        <br>
        <br>
    </div>
    <div class="select-box">
        <p> <span style="color: #ff6905;font-size: 16px;margin:0 5px;">*</span> 您对<span style="color: #ff6905;">团家政网站</span>有哪些方面意见或建议？</p>
        <input type="radio" class="suggest" name="suggest" style="margin: 30px 0 0 50px;" value="1"> 界面外观 <br>
        <input type="radio" class="suggest" name="suggest" style="margin: 0 0 5px 50px;" value="2"> 功能需求 <br>
        <input type="radio" class="suggest" name="suggest" style="margin: 0 0 5px 50px;" value="3"> 交易流程 <br>
        <input type="radio" class="suggest" name="suggest" style="margin-left: 50px;" value="4"> 服务态度 <br>
        <span class="suggest_error" style="color: #ff6905;display:inline-block;margin-left: 50px;"></span>
    </div>
    <div class="input-box">
        <p> <span style="color: #ff6905;font-size: 16px;margin:0 5px;">*</span> 如果您在使用团家政网站时，有什么好或不好的地方，请大声说出来！我们会关注您的反馈，不断优化产品，为您提供更好的服务！</p>
        <textarea name="" id="suggest_content" cols="30" rows="10" maxlength="500"></textarea>
        <span class="suggest_content_error" style="color: #ff6905;display:inline-block;margin-left: 50px;"></span>
        <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
        <div class="agent_work_aptitude_set">
            <div class="form-item clearfix left" style="margin:20px 0 0 40px;">
                <div class="form-item-value">
                    <div class="picture-list">
                        <ul class="clearfix">
                            <li id="show_image_6">
                                <div class="img-update">
                                    <span class="img-layer">+ 上传</span>
                                    <span class="img-file">
                                               <input type="file" id="file_6" name="file_6" field_id="6" hidefocus="true" maxlength="2" mall_type="image" mode="multi">
                                         </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="width:1180px;text-align: center;height:100px;line-height: 100px;">
        <span class="submit-btn">提交反馈</span>
    </div>
</div>
<div class="alert-box" style="display:none;">
    <div class="alert alert-danger tip-title"></div>
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
<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="templates/js/imageupload.js"></script>
<script>
    var file_name = 'file';
</script>

<script>
    $(".submit-btn").click(function () {
        var suggest_type=$(".suggest:checked").val();
        var suggest_content=$("#suggest_content").val();
        var suggest_image={};
        var i=0;
        $('.image_6').each(function() {
            suggest_image[i] = $(this).val();
            i++;
        });
        if(suggest_type=='' || suggest_type==undefined){
            $(".suggest_error").html('请选择一种建议');
            return;
        }else{
            $(".suggest_error").html('');
        }
        if(suggest_content=='' || suggest_content==undefined){
            $(".suggest_content_error").html('请填写建议内容');
            return;
        }else{
            $(".suggest_content_error").html('');
        }
//        var reg=/()|()/g;
        var submitData={
            'suggest_type':suggest_type,
            'suggest_content':suggest_content,
            'suggest_image':suggest_image
        };
        console.log(submitData);
        $.ajax({
            type:'POST',
            url:'index.php?act=user_feed_back&op=suggest',
            data:submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    $('.alert-box .tip-title').html('提交成功');
                    $('.alert-box').show();
                    setTimeout(function() {
                        $('.alert-box .tip-title').html('');
                        $('.alert-box').hide();
                        window.location.reload();
                    }, 2000);
                }else{
                    showalert(data.msg);
                }
            },
            timeout : 15000,
            error:function (xhr, type) {
                showalert('网络不稳定，请稍后重试');
            }
        })
    });
</script>

