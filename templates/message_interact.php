<?php include(template('common_header'));?>
<div id="message_header">
    <div class="message_header_content">
        <h1 class="left">我的消息</h1>
        <h4 class="left">首页</h4>
        <div class="right">
            <input type="text"><a>搜索</a>
        </div>
    </div>
</div>
</div>
<div id="message_content">
    <div class="message_content_left left">
        <ul>
            <li><span>●</span><a href="index.php?act=message_center">交易通知(<?php echo $deal_count ?>)</a></li>
            <li><span>●</span><a href="index.php?act=message_system">系统通知(<?php echo $system_count ?>)</a></li>
            <li><span style="visibility: inherit;">●</span><a href="index.php?act=message_interact" style="color:#ff6905;">互动通知(<?php echo $interact_count ?>)</a></li>
            <li><span>●</span><a href="index.php?act=message_set">设置</a></li>
        </ul>
    </div>
    <div class="message_content_right left">
        <div class="message_info">
            <h2 class="left">互动通知</h2> <span>未读<?php echo $count ?>条</span>
        </div>
        <div class="message_change">
            <span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
            <span class="read_message_btn">设为已读</span><span class="del_message_btn">删除</span>
        </div>
        <div class="message_details">
            <?php echo $message_show ?>
            <ul>
                <?php foreach ($message_list as $key => $value) { ?>
                    <li>
                        <span class="check checkitem" message_id="<?php echo $value['message_id'];?>"><i class="iconfont icon-type"></i></span>
                        <img src="templates/images/voice.gif" alt="" style="margin-right: 5px;"><?php echo $value['message_content'] ?>
                        <span style="margin-left: 50px;color: #ff6905;">未读</span>
                    </li>
                <?php } ?>
                <?php foreach ($message_read_list as $key => $value) { ?>
                    <li>
                        <span class="check checkitem" message_id="<?php echo $value['message_id'];?>"><i class="iconfont icon-type"></i></span>
                        <img src="templates/images/voice.gif" alt="" style="margin-right: 5px;"><?php echo $value['message_content'] ?>
                        <span style="margin-left: 50px;color:#58c526;">已读</span>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <script src="templates/js/home/message.js"></script>
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
<div class="modal-wrap w-400" id="read-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="read_ids" name="read_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定设为已读吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="readsubmit();">确定</a>
    </div>
</div>
<div class="modal-wrap w-400" id="del-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="del_ids" name="del_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定删除吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="delsubmit();">确定</a>
    </div>
</div>
<script>
    $(".read_message_btn").click(function () {
        if($('.checkitem.active').length == 0) {
            showalert('请至少选择一条消息');
            return;
        }
        var items = '';
        $('.checkitem.active').each(function(){
            items += $(this).attr('message_id') + ',';
        });
        items = items.substr(0, (items.length - 1));
        $('#read_ids').val(items);
        Custombox.open({
            target : '#read-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function readsubmit() {
        var read_ids=$("#read_ids").val();
        var submitData={
            'read_ids':read_ids
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=message_center&op=read',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('设置成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    });

                }else{
                    showwarning('read-box', data.msg);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                showwarning('read-box', '网路不稳定，请稍候重试');

            }
        });
    }
</script>
<script>
    $(".del_message_btn").click(function () {
        if($('.checkitem.active').length == 0) {
            showalert('请至少选择一条消息');
            return;
        }
        var items = '';
        $('.checkitem.active').each(function(){
            items += $(this).attr('message_id') + ',';
        });
        items = items.substr(0, (items.length - 1));
        $('#del_ids').val(items);
        Custombox.open({
            target : '#del-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    function delsubmit() {
        var del_ids=$("#del_ids").val();
        var submitData={
            'del_ids':del_ids
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=message_center&op=del',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('删除成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    });

                }else{
                    showwarning('del-box', data.msg);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                showwarning('del-box', '网路不稳定，请稍候重试');

            }
        });
    }
</script>