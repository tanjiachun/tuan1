<?php include(template('common_header'));?>
<style>
    .header{
        border:1px solid #ddd;
    }
</style>
<link rel="stylesheet" href="templates/css/admin.css">
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
                        <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_nurse_set" style="font-size: 12px;">· 员工设置</a></li>
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
        <div class="agent_nurse_set left">
            <p><span>旗下员工设置</span><b style="font-weight: normal;font-size: 12px;margin-left: 10px;">（小提示 ： 点击员工编号可修改该员工简历）</b></p>
            <div class="orderlist">
                <div class="orderlist-head clearfix">
                    <ul>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=show&state_cideci=<?php echo $state_cideci ?>"<?php echo $state=='show' ? ' class="active"' : '';?>>默认排序</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=sex&state_cideci=<?php echo $state_cideci ?>"<?php echo $state=='sex' ? ' class="active"' : '';?>>性别</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=age&state_cideci=<?php echo $state_cideci ?>"<?php echo $state=='age' ? ' class="active"' : '';?>>年龄</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=score&state_cideci=<?php echo $state_cideci ?>"<?php echo $state=='score' ? ' class="active"' : '';?>>等级</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=time&state_cideci=<?php echo $state_cideci ?>"<?php echo $state=='time' ? ' class="active"' : '';?>>加盟时间</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=<?php echo $state ?>&state_cideci=all"<?php echo $state_decide=='all' ? ' class="active2"' : '';?>>全部员工</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=<?php echo $state ?>&state_cideci=forjob"<?php echo $state_decide=='forjob' ? ' class="active2"' : '';?>>待业中</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=<?php echo $state ?>&state_cideci=onwork"<?php echo $state_decide=='onwork' ? ' class="active2"' : '';?>>已在岗</a>
                        </li>
                        <li>
                            <a href="index.php?act=agent_nurse_set&state=<?php echo $state ?>&state_cideci=holiday"<?php echo $state_decide=='holiday' ? ' class="active2"' : '';?>>假期中</a>
                        </li>
                    </ul>
                    <div class="pull-right">
                        <div class="search">
                            <form action="index.php" method="get" id="search_form">
                                <input type="hidden" name="act" value="agent_nurse_set">
                                <input type="hidden" name="state" value="<?php echo $state;?>">
                                <input type="text" id="search_name" name="search_name" class="itxt" placeholder="家政人员姓名/电话" value="<?php echo $search_name;?>">
                                <a href="javascript:;" class="search-btn" onclick="$('#search_form').submit();"><i class="iconfont icon-search"></i></a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="orderlist-body">
                    <table class="order-tb">
                        <thead>
                        <tr>
                            <th width="30"></th>
                            <th width="80">编号</th>
                            <th width="80">姓名</th>
                            <th width="80">服务费设置</th>
                            <th width="80">工资</th>
                            <th width="100">状态</th>
                            <th width="100">中介费折扣</th>
                            <th width="150">业务电话</th>
                            <th width="90">员工权限</th>
                            <th width="50">操作</th>
                        </tr>
                        </thead>
                        <?php foreach($nurse_list as $key => $value) { ?>
                            <tbody id="nurse_<?php echo $value['nurse_id'];?>" data="<?php echo $value['state_cideci'];?>">
                            <tr class="tr-bd">
                                <td>
                                    <span class="check checkitem" nurse_id="<?php echo $value['nurse_id'];?>"><i class="iconfont icon-type"></i></span>
                                </td>
                                <td><a class="bluelink" href="index.php?act=agent_nurse_set&op=edit&nurse_id=<?php echo $value['nurse_id'];?>"><?php echo $value['nurse_id'];?></a></td>
                                <td><?php echo  $value['nurse_name']?></td>
                                <td>
                                    <?php if($value['nurse_type']==1 || $value['nurse_type']==2 ||$value['nurse_type']==11 || $value['nurse_type']==12 || $value['nurse_type']==13 ||$value['nurse_type']==14 || $value['nurse_type']==15 || $value['nurse_type']==16) { ?>
                                    <select name="service_price" id="service_price<?php echo $value['nurse_id'];?>" style="border:none;">
                                        <?php foreach ($service_price_array as $k => $v) { ?>
                                            <option value="<?php echo $v ?>"<?php echo $v == $value['service_price'] ? ' selected="selected"' : '';?>><?php echo $v ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } else { ?>
                                        无
                                    <?php } ?>
                                </td>
                                <td><input class="price_input" id="price_input<?php echo $value['nurse_id'];?>" type="text" value="<?php echo $value['nurse_price'] ?>"></td>
                                <td>
                                    <select class="state_select" name="" id="state_select<?php echo $value['nurse_id'];?>">
                                        <?php foreach ($nurse_state_array as $k => $v) { ?>
                                            <option value="<?php echo $v ?>"<?php echo $v == $value['state_cideci'] ? ' selected="selected"' : '';?>><?php echo $nurse_state_name_array[$v] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="discount_select" id="discount_select<?php echo $value['nurse_id'];?>" name="">
                                        <?php if($value['nurse_type']==1 || $value['nurse_type']==2 ||$value['nurse_type']==11 || $value['nurse_type']==12 || $value['nurse_type']==13 ||$value['nurse_type']==14 || $value['nurse_type']==15 || $value['nurse_type']==16) { ?>
                                            <?php foreach ($nurse_discount_array as $ke => $v) { ?>
                                                <option value="<?php echo $v*0.1 ?>"<?php echo $v == $value['nurse_discount'] ? ' selected="selected"' : '';?>><?php echo $v ?>折</option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php foreach ($nurse_discount_array2 as $ke => $v) { ?>
                                                <option value="<?php echo $v*0.1 ?>"<?php echo $v == $value['nurse_discount'] ? ' selected="selected"' : '';?>><?php echo $v ?>折</option>
                                            <?php } ?>
                                        <?php } ?>

                                    </select>
                                </td>
                                <td>
                                    <select name="" class="phone_select" id="phone_select<?php echo $value['nurse_id'];?>">
                                        <?php foreach ($agent['agent_other_phone'] as $k => $v) { ?>
                                            <option value="<?php echo $v ?>"<?php echo $v == $value['agent_phone'] ? ' selected="selected"' : '';?>><?php echo $v ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="" class="authority_select" id="authority_select<?php echo $value['nurse_id'];?>">
                                        <?php foreach ($authority_state_array as $k => $v) { ?>
                                            <option value="<?php echo $v ?>"<?php echo $v == $value['authority_state'] ? ' selected="selected"' : '';?>><?php echo $authority_state_name_array[$v] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><a href="javascript:;" data="<?php echo $value['nurse_id'];?>" class="nurse_set_submit" style="text-decoration: underline;">确认</a></td>
                            </tr>
                            </tbody>
                        <?php } ?>
                        <?php if(!empty($nurse_list)) { ?>
                            <tbody>
                            <tr class="tool-row">
                                <td colspan="11">
                                    <span class="check checkall"><i class="iconfont icon-type"></i>全选</span>
                                    <select name="nurse_authority_set" id="nurse_authority_set">
                                        <option value="0" class="nurse_authority_set">是否允许员工修改简历</option>
                                        <option value="1">允许员工修改简历</option>
                                        <option value="2">不允许员工修改简历</option>
                                    </select>
<!--                                    <select name="nurse_discount_set" id="nurse_discount_set">-->
<!--                                        <option value="-1" class="nurse_discount_set">请选择员工折扣率</option>-->
<!--                                        <option value="0">折扣率 — 0%</option>-->
<!--                                        <option value="10">折扣率 — 10%</option>-->
<!--                                        <option value="20">折扣率 — 20%</option>-->
<!--                                        <option value="30">折扣率 — 30%</option>-->
<!--                                        <option value="40">折扣率 — 40%</option>-->
<!--                                        <option value="50">折扣率 — 50%</option>-->
<!--                                        <option value="60">折扣率 — 60%</option>-->
<!--                                    </select>-->
                                    <select name="nurse_refund_set" id="nurse_refund_set">
                                        <option value="0" class="nurse_refund_set">请选择服务承诺</option>
                                        <option value="1">不支持三小时无理由</option>
                                        <option value="2">支持三小时无理由</option>
                                        <option value="3">不支持三天无理由</option>
                                        <option value="4">支持三天无理由</option>
                                    </select>
                                    <select name="nurse_phone_set" id="nurse_phone_set">
                                        <option value="0">请选择业务电话</option>
                                        <?php foreach ($agent['agent_other_phone'] as $k => $v) { ?>
                                            <option value="<?php echo $v ?>"><?php echo $v ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="btn btn-default dismiss_nurse">辞退员工</span>
                                </td>
                            </tr>
                            </tbody>
                        <?php } ?>
                    </table>
                </div>
                <?php echo $multi;?>
            </div>
        </div>
    </div>
</div>

<!--<div id="type_choose_box">-->
<!--    <input type="hidden" id="type_id" value="">-->
<!--</div>-->


<input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
<div class="modal-wrap w-400" id="authority-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="authority_ids" name="authority_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定修改吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="authoritysubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="discount-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="discount_ids" name="discount_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定修改吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="discountsubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="promise-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="promise_ids" name="promise_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定修改吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="promisesubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="phone-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="phone_ids" name="phone_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定修改吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="phonesubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="dismiss-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="dismiss_ids" name="dismiss_ids" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定辞退吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="dismisssubmit();">确定</a>
    </div>
</div>

<div class="modal-wrap w-400" id="nurse-box" style="display: none;">
    <div class="Validform-checktip Validform-wrong m-tip"></div>
    <input type="hidden" id="nurse_set_id" name="nurse_set_id" value="" />
    <div class="modal-bd">
        <div class="m-success-tip">
            <div class="tip-inner">
					<span class="tip-icon">
						<i class="iconfont icon-info"></i>
					</span>
                <h3 class="tip-message">确定提交吗？</h3>
            </div>
        </div>
    </div>
    <div class="modal-ft tc">
        <a class="btn btn-default" onclick="Custombox.close();">取消</a>
        <a class="btn btn-primary" onclick="nursesubmit();">确定</a>
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
<link href="templates/css/zoomify.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="templates/js/zoomify.js"></script>
<script>
//    简历权限设置
    $('#nurse_authority_set').on('change', function() {
        if(parseInt($(this).val())!==0){
            if($('.checkitem.active').length == 0) {
                showalert('请至少选择一个家政人员');
                $(".nurse_authority_set").prop('selected',true);
                return;
            }
            var items = '';
            $('.checkitem.active').each(function(){
                items += $(this).attr('nurse_id') + ',';
            });
            items = items.substr(0, (items.length - 1));
            console.log(items);
            $('#authority_ids').val(items);
            Custombox.open({
                target : '#authority-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        }
    });
    var authority_submit_btn=false;
    function authoritysubmit() {
        var formhash = $('#formhash').val();
        var authority_ids=$("#authority_ids").val();
        var authority_value=parseInt($("#nurse_authority_set").val());
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'authority_ids' : authority_ids,
            'authority_value':authority_value
        };
        if(authority_submit_btn) return;
        authority_submit_btn=true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_nurse_set&op=authority',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                authority_submit_btn=false;
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('权限设置成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                    $(".nurse_authority_set").prop('selected',true);
                }else{
                    showwarning('authority-box', data.msg);
                    $(".nurse_authority_set").prop('selected',true);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                authority_submit_btn=false;
                showwarning('authority-box', '网路不稳定，请稍候重试');
                $(".nurse_authority_set").prop('selected',true);
            }
        });
    }
</script>
<script>
//    员工折扣率函数
    $('#nurse_discount_set').on('change', function() {
        if(parseInt($(this).val())!==-1){
            if($('.checkitem.active').length == 0) {
                showalert('请至少选择一个家政人员');
                $(".nurse_discount_set").prop('selected',true);
                return;
            }
            var items = '';
            $('.checkitem.active').each(function(){
                items += $(this).attr('nurse_id') + ',';
            });
            items = items.substr(0, (items.length - 1));
            console.log(items);
            $('#discount_ids').val(items);
            Custombox.open({
                target : '#discount-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        }
    });
    var discount_submit_btn=false;
    function discountsubmit() {
        var formhash = $('#formhash').val();
        var discount_ids=$("#discount_ids").val();
        var discount_value=parseInt($("#nurse_discount_set").val());
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'discount_ids' : discount_ids,
            'discount_value':discount_value
        };
        if(discount_submit_btn) return;
        discount_submit_btn=true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_nurse_set&op=discount',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                discount_submit_btn=false;
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('折扣率设置成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                    $(".nurse_discount_set").prop('selected',true);
                }else{
                    showwarning('discount-box', data.msg);
                    $(".nurse_discount_set").prop('selected',true);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                discount_submit_btn=false;
                showwarning('discount-box', '网路不稳定，请稍候重试');
                $(".nurse_discount_set").prop('selected',true);
            }
        });
    }
</script>
<script>
    //    服务承诺函数
    $('#nurse_refund_set').on('change', function() {
        if(parseInt($(this).val())!==0){
            if($('.checkitem.active').length == 0) {
                showalert('请至少选择一个家政人员');
                $(".nurse_refund_set").prop('selected',true);
                return;
            }
            var items = '';
            $('.checkitem.active').each(function(){
                items += $(this).attr('nurse_id') + ',';
            });
            items = items.substr(0, (items.length - 1));
            console.log(items);
            $('#promise_ids').val(items);
            Custombox.open({
                target : '#promise-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        }
    });
    var promise_submit_btn=false;
    function promisesubmit() {
        var formhash = $('#formhash').val();
        var promise_ids=$("#promise_ids").val();
        var promise_value=parseInt($("#nurse_refund_set").val());
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'promise_ids' : promise_ids,
            'promise_value':promise_value
        };
        if(promise_submit_btn) return;
        promise_submit_btn=true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_nurse_set&op=promise',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                promise_submit_btn=false;
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('服务承诺设置成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                    $(".nurse_refund_set").prop('selected',true);
                }else{
                    showwarning('promise-box', data.msg);
                    $(".nurse_refund_set").prop('selected',true);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                promise_submit_btn=false;
                showwarning('promise-box', '网路不稳定，请稍候重试');
                $(".nurse_refund_set").prop('selected',true);
            }
        });
    }
</script>
<script>
    //    业务电话设置函数
    $('#nurse_phone_set').on('change', function() {
        if(parseInt($(this).val())!==0){
            if($('.checkitem.active').length == 0) {
                showalert('请至少选择一个家政人员');
                $("#nurse_phone_set option").eq(0).prop('selected',true);
                return;
            }
            var items = '';
            $('.checkitem.active').each(function(){
                items += $(this).attr('nurse_id') + ',';
            });
            items = items.substr(0, (items.length - 1));
            console.log(items);
            $('#phone_ids').val(items);
            Custombox.open({
                target : '#phone-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
        }
    });
    var phone_submit_btn=false;
    function phonesubmit() {
        var formhash = $('#formhash').val();
        var phone_ids=$("#phone_ids").val();
        var phone_value=$("#nurse_phone_set").val();
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'phone_ids' : phone_ids,
            'phone_value':phone_value
        };
        if(phone_submit_btn) return;
        phone_submit_btn=true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_nurse_set&op=phone',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                phone_submit_btn=false;
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('业务号码设置成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                        $("#nurse_phone_set option").eq(0).prop('selected',true);
                    });
                }else{
                    showwarning('phone-box', data.msg);
                    $("#nurse_phone_set option").eq(0).prop('selected',true);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                phone_submit_btn=false;
                showwarning('phone-box', '网路不稳定，请稍候重试');
                $("#nurse_phone_set option").eq(0).prop('selected',true);
            }
        });
    }
</script>
<script>
    //    员工辞退函数
    $('.dismiss_nurse').on('click', function() {
            if($('.checkitem.active').length == 0) {
                showalert('请至少选择一个家政人员');
                return;
            }
            var items = '';
            $('.checkitem.active').each(function(){
                items += $(this).attr('nurse_id') + ',';
            });
            items = items.substr(0, (items.length - 1));
            console.log(items);
            $('#dismiss_ids').val(items);
            Custombox.open({
                target : '#dismiss-box',
                effect : 'blur',
                overlayClose : true,
                speed : 500,
                overlaySpeed : 300,
            });
    });
    var dismiss_submit_btn=false;
    function dismisssubmit() {
        var formhash = $('#formhash').val();
        var dismiss_ids=$("#dismiss_ids").val();
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'dismiss_ids' : dismiss_ids
        };
        if(dismiss_submit_btn) return;
        dismiss_submit_btn=true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_nurse_set&op=dismiss',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                dismiss_submit_btn=false;
                if(data.done=='true'){
                    Custombox.close(function() {
                        for(var i=0; i<data.dismiss_ids.length; i++) {
                            $('#nurse_'+data.dismiss_ids[i]).remove();
                        }
                        $('.alert-box .tip-title').html('员工辞退成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                }else{
                    showwarning('dismiss-box', data.msg);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                dismiss_submit_btn=false;
                showwarning('dismiss-box', '网路不稳定，请稍候重试');
            }
        });
    }
</script>
<script>
    //    员工信息修改提交函数
    $('.nurse_set_submit').on('click', function() {


        var nurse_id=$(this).attr('data');
        $('#nurse_set_id').val(nurse_id);
        Custombox.open({
            target : '#nurse-box',
            effect : 'blur',
            overlayClose : true,
            speed : 500,
            overlaySpeed : 300,
        });
    });
    var nurse_submit_btn=false;
    function nursesubmit() {
        var formhash = $('#formhash').val();
        var nurse_id=$("#nurse_set_id").val();
        var nurse_price=$("#price_input"+nurse_id).val();
        var service_price=$("#service_price"+nurse_id).val();
        var state_cideci=$("#state_select"+nurse_id).val();
        var nurse_discount=$("#discount_select"+nurse_id).val();
        var agent_phone=$("#phone_select"+nurse_id).val();
        var authority_state=$("#authority_select"+nurse_id).val();
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'nurse_id':nurse_id,
            'service_price':service_price,
            'nurse_price':nurse_price,
            'nurse_discount':nurse_discount,
            'state_cideci':state_cideci,
            'agent_phone':agent_phone,
            'authority_state':authority_state
        };
        console.log(submitData);
        if(nurse_submit_btn) return;
        nurse_submit_btn=true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent_nurse_set&op=nurse_set',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                nurse_submit_btn=false;
                if(data.done=='true'){
                    Custombox.close(function() {
                        $('.alert-box .tip-title').html('资料修改成功');
                        $('.alert-box').show();
                        setTimeout(function() {
                            $('.alert-box .tip-title').html('');
                            $('.alert-box').hide();
                        }, 2000);
                    });
                }else{
                    showwarning('nurse-box', data.msg);
                }
            },
            timeout:15000,
            error:function (xhr, type) {
                nurse_submit_btn=false;
                showwarning('nurse-box', '网路不稳定，请稍候重试');
            }
        });
    }
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














