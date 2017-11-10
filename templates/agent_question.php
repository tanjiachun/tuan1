<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
    <div class="agent_set_center_header">
        <div class="left" style="margin-top: 10px;">
            <a href="index.php">
                <img src="templates/images/logo.png">
            </a>
            <span style="font-size: 18px;font-weight: 500;margin-left: 5px;">机构管理平台</span>
        </div>
        <div class="left agent_message_show">
            <span>被关注 <?php echo $agent['agent_focusnum'] ?></span>
            <span>员工总数 <?php echo $nurse_count ?></span>
            <span>浏览数 <?php echo $agent['agent_viewnum'] ?></span>
            <span>累计交易 <?php echo $book_count ?></span>
        </div>
        <div class="left agent_code_show">
            <span>机构编号 <?php echo $agent['agent_id'] ?></span>
            <span>有<?php echo $question_count ?>个问题待回答 <a href="index.php?act=agent_question" style="background: #ff6905;color:#fff;padding:0 5px;">回答</a></span>
        </div>
    </div>
</div>
<div id="agent_manage">
    <div id="agent_manage_content">
        <div id="agent_manage_sidebar" class="left">
            <div class="agent_manage_logo">
                <img width="100px" height="100px" src="<?php echo $agent['agent_logo'] ?>" alt="">
            </div>
            <ul class="sidebar_list">
                <li><a href="index.php?act=agent_center">首页编辑</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_question">机构问答</a></li>
                <li><a href="index.php?act=agent_phone_set">手机设置</a></li>
                <li class="staff_set_list">
                    <a class="list_show">员工管理</a>
                    <ul style="display: none;">
                        <li><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
                        <li><a href="index.php?act=agent_nurse_audit" style="font-size: 12px;">· 审核员工</a></li>
                        <li><a href="index.php?act=agent_nurse_add" style="font-size: 12px;">· 新建员工</a></li>
                    </ul>
                </li>
                <li><a href="index.php?act=agent_book">全部订单</a></li>
                <li><a href="index.php?act=agent_evaluate">评价管理</a></li>
                <li><a href="index.php?act=agent_refund">退款查询</a></li>
                <li><a href="index.php?act=agent_marketing">营销管理</a></li>
                <li><a href="index.php?act=agent_profit">财务中心</a></li>
                <li><a href="index.php?act=agent_invoice">发票管理</a></li>
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
        <div id="agent_manage_set" class="left form-list">
            <div id="answers_list">
                <?php foreach ($question_list as $key => $value)  { ?>
                    <div class="question_details">
                        <div class="question_message">
                            <?php if($member_list[$value['member_id']]['member_image']== '') { ?>
                                <img src="templates/images/head.png">
                            <?php } else { ?>
                                <img src="<?php echo $member_list[$value['member_id']]['member_image'];?>">
                            <?php } ?>
                            <b><?php echo $member_list[$value['member_id']]['member_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;提了一个问题</b> <span><?php echo date('Y-m-d H:i', $value['question_time']);?></span>
                        </div>
                        <h3><img style="margin:0 5px 3px 0" src="templates/images/question.png" alt=""><a href="javascript:;"><?php echo $value['question_content'] ?></a></h3>
                            <textarea placeholder="回答内容不超过300个字符" name="<?php echo $value['question_id'] ?>" id="<?php echo $value['question_id'] ?>" cols="30" rows="10" maxlength="300"></textarea> <span class="answer_submit_btn" data="<?php echo $value['question_id'] ?>">确定</span> <span style="color: #ff6905;position: absolute;right:30px;bottom:4px;" class="return_success"></span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(".answer_submit_btn").click(function () {
        var question_id=$(this).attr('data');
        var answer_content=$(this).siblings('textarea').val();
        if(answer_content==''){
            $(this).siblings('.return_success').html('回答内容不能为空');
            return;
        }
        var that=$(this);
        var submitData={
            'question_id':question_id,
            'answer_content':answer_content
        }
        $.ajax({
            type:'POST',
            url:'index.php?act=agent_question&op=answer',
            data:submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    that.siblings('.return_success').html('回答成功');
                    setTimeout(function () {
                        that.parent().remove();
                    },2000)
                }else {
                    that.siblings('.return_success').html(data.msg);
                    setTimeout(function () {
                        that.siblings('.return_success').html('');
                    },2000)
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                that.siblings('.return_success').html('网络不稳定，请稍后重试');
                setTimeout(function () {
                    that.siblings('.return_success').html('');
                },2000)
            }
        });
    })
    $(".staff_set_list span").click(function () {
        if(!$(".staff_set_list ul").is(":hidden")){
            $(".staff_set_list ul").fadeOut();
            $(".staff_set_list img").attr('src','templates/images/toBW.png');
        }else{
            $(".staff_set_list ul").fadeIn();
            $(".staff_set_list img").attr('src','templates/images/toTopW.png');
        }
    })
</script>