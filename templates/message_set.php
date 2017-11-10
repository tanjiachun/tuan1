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
            <li><span>●</span><a href="index.php?act=message_center">交易通知</a></li>
            <li><span>●</span><a href="index.php?act=message_system">系统通知</a></li>
            <li><span>●</span><a href="index.php?act=message_interact">互动通知</a></li>
            <li><span style="visibility: inherit;">●</span><a style="color:#ff6905;" href="index.php?act=message_set">设置</a></li>
        </ul>
    </div>
    <div class="message_content_right left">
        <div class="message_info">
            <h2 class="left">设置</h2>
        </div>
        <div class="message_set">
            <div class="message_set_detail">
                <span class="left">■&nbsp;&nbsp;交易通知</span>
                <div class="left">
                    <input type="checkbox" id="set1" class="setCheckBox" <?php echo $message_set['deal_message_state']==0 ? 'checked="checked"' : ''; ?>>
                    <label for="set1">
                        <i></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="message_set">
            <div class="message_set_detail">
                <span class="left">■&nbsp;&nbsp;系统通知</span>
                <div class="left">
                    <input type="checkbox" id="set2" class="setCheckBox" <?php echo $message_set['system_message_state']==0 ? 'checked="checked"' : ''; ?>>
                    <label for="set2">
                        <i></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="message_set">
            <div class="message_set_detail">
                <span class="left">■&nbsp;&nbsp;互动通知</span>
                <div class="left">
                    <input type="checkbox" id="set3" class="setCheckBox" <?php echo $message_set['interact_message_state']==0 ? 'checked="checked"' : ''; ?>>
                    <label for="set3">
                        <i></i>
                    </label>
                </div>
            </div>
        </div>
    <script src="templates/js/home/message.js"></script>
</div>
    <script>
        $("#set1").click(function () {
            var deal_message_state;
           if($(this).is(":checked")){
               deal_message_state=0;
           }else{
               deal_message_state=1;
           }
           $.getJSON('index.php?act=message_set&op=set1&deal_message_state='+deal_message_state,function (data) {

           })
        });
        $("#set2").click(function () {
            var system_message_state;
            if($(this).is(":checked")){
                system_message_state=0;
            }else{
                system_message_state=1;
            }
            $.getJSON('index.php?act=message_set&op=set2&system_message_state='+system_message_state,function (data) {

            })
        });
        $("#set3").click(function () {
            var interact_message_state;
            if($(this).is(":checked")){
                interact_message_state=0;
            }else{
                interact_message_state=1;
            }
            $.getJSON('index.php?act=message_set&op=set3&interact_message_state='+interact_message_state,function (data) {

            })
        });
    </script>
