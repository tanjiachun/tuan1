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
                <li><a href="index.php?act=agent_question">机构问答</a></li>
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_phone_set">手机设置</a></li>
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
            <div class="agent_phone_set">
                <p><span>机构业务相关号码设置</span> <b style="font-weight: normal;font-size: 12px;margin-left:50px;">号码最多可添加10个 ， 默认显示不超过2个 (点击多选框选择默认显示的号码)</b></p>
                <div><span>绑定手机号</span><input type="text" value="<?php echo $agent['member_phone'] ?>" disabled> <b>不可修改</b></div>
                <?php foreach($agent['agent_other_phone'] as $subkey => $subvalue) { ?>
                        <?php if($subvalue==$agent['agent_other_phone_choose'][0] || $subvalue==$agent['agent_other_phone_choose'][1]) { ?>
                            <div><span><input class="agent_other_phone_choose" name="agent_other_phone_choose" type="checkbox" checked></span><input type="text" class="agent_other_phone" value="<?php echo $subvalue ?>"> <b></b></div>
                        <?php } else { ?>
                            <div><span><input class="agent_other_phone_choose" name="agent_other_phone_choose" type="checkbox"></span><input type="text" class="agent_other_phone" value="<?php echo $subvalue ?>"> <b></b></div>
                        <?php } ?>
                <?php } ?>
                <button class="add_agent_phone_btn">新&nbsp;&nbsp;增</button>
            </div>
            <div class="agent_service_phone_set">
                <p><span>机构客服相关号码设置</span> <b style="font-weight: normal;font-size: 12px;margin-left: 50px;">客服号码最多1个</b></p>
                <div><span>客服号码</span><input id="agent_phone" type="text" value="<?php echo $agent['agent_phone'] ?>"> <b></b></div>
            </div>
            <div class="agent_manage_submit">
                <span class="agent_phone_submit_btn">确认 提交</span><span class="return-success" style="display: block;"></span>
            </div>
        </div>
    </div>
</div>

<script>
    var agent_id='<?php echo $this->agent_id ?>';
</script>

<script>

</script>

<script>
    $(".add_agent_phone_btn").click(function () {
        var html='';
        html+='<div><span><input class="agent_other_phone_choose" name="agent_other_phone_choose" type="checkbox"></span><input type="text" class="agent_other_phone" value=""><b></b></div>';
        $(this).before(html);
        if($(".agent_other_phone").length>=10){
            $(this).hide();
        }
        checkbox_choose();
    });
    function checkbox_choose() {
        $("input[type='checkbox']").each(function () {
            if($("input[type='checkbox']:checked").length>=2){
                $("input[type='checkbox']").not($("input[type='checkbox']:checked")).prop('disabled','disabled');
            }else{
                $("input[type='checkbox']").not($("input[type='checkbox']:checked")).prop('disabled',false);
            }
        })
        $("input[type='checkbox']").each(function () {
            $(this).click(function () {
                if($("input[type='checkbox']:checked").length>=2){
                    $("input[type='checkbox']").not($("input[type='checkbox']:checked")).prop('disabled','disabled');
                }else{
                    $("input[type='checkbox']").not($("input[type='checkbox']:checked")).prop('disabled',false);
                }
            })
        })
    }
    checkbox_choose();
    $(".agent_phone_submit_btn").click(function () {
        var i=0;
        var agent_other_phone={};
        $(".agent_other_phone").each(function () {
            agent_other_phone[i]=$(this).val();
            i++;
        });
        var agent_phone=$("#agent_phone").val();
        var j=0;
        var agent_other_phone_choose={};
            $(".agent_other_phone_choose").each(function () {
                if($(this).is(":checked")){
                    agent_other_phone_choose[j]=$(this).parent().siblings("input").val();
                    j++;
                }
        })
        var submitData={
            'agent_id':agent_id,
            'agent_other_phone':agent_other_phone,
            'agent_phone':agent_phone,
            'agent_other_phone_choose':agent_other_phone_choose
        };
        console.log(submitData);
        $.ajax({
            type:'POST',
            url:'index.php?act=agent_phone_set&op=phone_set',
            data:submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                console.log(data);
                if(data.done=='true'){
                    $('.return-success').html('提交成功');
                    $('.return-success').show();
                    setTimeout(function () {
                        $('.return-success').hide();
                    },3000)
                }else{
                    $('.return-success').html('提交失败');
                    $('.return-success').show();
                    setTimeout(function () {
                        $('.return-success').hide();
                    },3000)
                }
            },
            timeout : 15000,
            error:function () {
                $('.return-success').html('网络连接超时');
                $('.return-success').show();
                setTimeout(function () {
                    $('.return-success').hide();
                },3000)
            }

        })
    });
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


