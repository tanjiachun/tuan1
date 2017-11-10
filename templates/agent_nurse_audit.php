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
                <li><a href="index.php?act=agent_phone_set">手机设置</a></li>
                <li class="staff_set_list">
                    <a class="list_show">员工管理</a>
                    <ul>
                        <li><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
                        <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_nurse_audit" style="font-size: 12px;">· 审核员工</a></li>
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
            <div class="new_staff_audit">
                <p><span>审核新员工</span><b style="font-weight: normal;font-size: 12px;margin-left: 10px;">（点击编号查看家政人员简历）</b></p>
                <ul>
                    <?php foreach ($nurse_audit_list as $key => $value) { ?>
                        <li>编号为 <a style="text-decoration: underline;" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'] ?>"><?php echo $value['nurse_id'] ?></a>的家政人员申请加入机构 <span data="<?php echo $value['nurse_id'] ?>" class="agree_audit_btn">同意</span> <span data="<?php echo $value['nurse_id'] ?>" class="reject_audit_btn">拒绝</span><span style="color: #ff6905;" class="return_success"></span></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="new_staff_recruit">
                <p><span>招募新员工</span></p>
                <ul>
                    <li>邀请编号为 <input type="text" id="invitation_nurse" value="">的家政人员加盟本机构 <span class="invitation_nurse_btn">确定</span> <span style="color: #ff6905;" class="return_success"></span></li>
                </ul>
            </div>
            <div class="staff_recruit_state">
                <p><span>招募状态</span></p>
                <?php foreach ($nurse_recruit_list as $key => $value) { ?>
                    <?php if($value['nurse_audit_state']==2) { ?>
                        <li>邀请编号为<a style="text-decoration: underline;" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'] ?>"><?php echo $value['nurse_id'] ?></a>的家政人员加入机构 <span style="margin-left: 10px">已拒绝</span> <span data="<?php echo $value['staff_id'] ?>" class="re_invitation_btn">重新邀请</span> <span style="color:#ff6905;" class="return_success"></span></li>
                     <?php } else if($value['nurse_audit_state']==1) { ?>

                        <li>邀请编号为<a style="text-decoration: underline;" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'] ?>"><?php echo $value['nurse_id'] ?></a>的家政人员加入机构 <span style="margin-left: 10px">已同意</span></li>
                    <?php } else if($value['nurse_audit_state']==0) { ?>
                        <li>邀请编号为<a style="text-decoration: underline;" href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'] ?>"><?php echo $value['nurse_id'] ?></a>的家政人员加入机构 <span style="margin-left: 10px">无回复</span></li>
                     <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(".agree_audit_btn").click(function () {
        var nurse_id=$(this).attr('data');
        var that=$(this);
        $.getJSON('index.php?act=agent_nurse_audit&op=agree&nurse_id='+nurse_id,function (data) {
            if(data.done=='true'){
                that.siblings(".return_success").html('已同意');
                setTimeout(function () {
                    that.parent().remove();
                },3000)
            }else if(data.done=='false'){
                that.siblings(".return_success").html(data.msg);
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000)
            }else{
                that.siblings(".return_success").html('网络连接错误');
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000)
            }
        })
    });
    $(".reject_audit_btn").click(function () {
        var nurse_id=$(this).attr('data');
        var that=$(this);
        $.getJSON('index.php?act=agent_nurse_audit&op=reject&nurse_id='+nurse_id,function (data) {
            if(data.done=='true'){
                that.siblings(".return_success").html('已拒绝');
                setTimeout(function () {
                    that.parent().remove();
                },3000)
            }else if(data.done=='false'){
                that.siblings(".return_success").html(data.msg);
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000)
            }else{
                that.siblings(".return_success").html('网络连接错误');
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000)
            }
        })
    });
    $(".invitation_nurse_btn").click(function () {
        var nurse_id=$("#invitation_nurse").val();
        var that=$(this);
        if(nurse_id==''){
            that.siblings(".return_success").html('请输入正确的员工编号');
            return;
        }
        $.getJSON('index.php?act=agent_nurse_audit&op=staff_recruit&nurse_id='+nurse_id,function (data) {
            if(data.done=='true'){
                that.siblings(".return_success").html('邀请成功，请耐心的等待回复');
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                    $("#invitation_nurse").val('');
                },3000)
            }else if(data.done=='false'){
                that.siblings(".return_success").html(data.msg);
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000)
            }else{
                that.siblings(".return_success").html('网络连接错误');
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000)
            }
        })
    });
    $(".re_invitation_btn").click(function () {
        var staff_id=$(this).attr('data');
        var that=$(this);
        $.getJSON('index.php?act=agent_nurse_audit&op=re_invitation&staff_id='+staff_id,function (data) {
            if(data.done=='true'){
                that.siblings(".return_success").html('邀请成功');
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                    $("#invitation_nurse").val('');
                },3000)
            }else if(data.done=='false'){
                that.siblings(".return_success").html(data.msg);
                setTimeout(function () {
                    that.siblings(".return_success").html('');
                },3000)
            }else{
                that.siblings(".return_success").html('网络连接错误');
                setTimeout(function () {
                    that.siblings(".return_success").html('');
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