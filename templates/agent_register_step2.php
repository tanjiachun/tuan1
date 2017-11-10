<?php include(template('common_header'));?>
    <style>
        .clear{clear: both;}
        .register-hd{width:1180px;border-bottom: 1px solid #ddd;padding-bottom: 20px;margin:0 auto;margin-top: 20px;}
        .zh-title h2{font-size:22px;font-weight: 700;margin-top: 30px;}
        .register-c{width:1180px;margin:0 auto;}
        .register-form{width:100%;}
        .basic_message tr{float:left;width:50%;}
        .basic_message .lr-input{width:300px;}
        .basic_message .lr-textarea{width:300px;height:100px;}
        .choose_step_btn{width:1180px;margin:0 auto;}
        .toStep2,.toStep3,.toSuccess{text-align: center;}
        .toStep2_btn,.toStep3_btn,.toStep1_btn,.returnStep2_btn,.success_btn{cursor:pointer;display: inline-block;width:200px;text-align: center;height:40px;line-height: 40px;background: #ff6905;color:#fff;margin-top:20px;font-size: 16px;}
        .picture-list ul li {
            float: left;
            margin: 0 10px 5px 0;
            display: block;
            width: 200px;
            height: 200px;
            border: 1px solid #ddd;
            background-color: #fff;
            position: relative;
        }
        .agent_picture tr{
            float:left;
            width:25%;
        }
        .help-desc{text-align: center;}
        .trade_tool { display:inline-block;width:600px;margin-left:290px;}
        .trade_tool .lr-input{width:400px;}
        .register-form .code-input .lr-input {
            width: 400px;
            vertical-align: middle;
        }
        .take-code{position: absolute;left:310px;}
        .code-input .take-code {
            cursor: default;
            display: inline-block;
            border-radius: 3px;
            vertical-align: middle;
            height: 35px;
            color: #ff6905;
            line-height: 35px;
            text-align: center;
            width: 100px;
            font-size: 12px;
            border: none;
        }
        .img-layer{
            color:#000;
        }
    </style>
    <div class="register-hd">
        <a href="index.php">
            <img src="templates/images/logo.png">
        </a>
        <span style="font-size: 16px;font-weight: 700;margin-left: 5px;">创建机构门店</span>
    </div>
</div>
<div class="top-progress zhmm-box">
    <div class="zh-title">
        <ul>
            <li class="l1 active"><span>1</span><br>机构信息</li>
            <li class="l2"><u></u></li>
            <li class="l3"><span>2</span><br>资质认证</li>
            <li class="l4"><u></u></li>
            <li class="l5"><span>3</span><br>交易工具</li>
        </ul>
        <h2 class="h1">机构信息，加*号位必填项目</h2>
        <h2 class="h2" style="display: none;">资质认证点击上传照片,图片支持 .jpg , .png </h2>
        <h2 class="h3" style="display: none;">交易工具，添加收付款银行卡</h2>
    </div>

</div>
    <div class="register-c" style="overflow: inherit;">
        <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
        <table class="register-form">
            <tbody class="basic_message">
                <tr>
                    <th>*机构名称</th>
                    <td>
                        <input id="agent_name" name="agent_name" class="lr-input" type="text" placeholder="请输入机构名称">
                        <div class="Validform-checktip Validform-wrong"></div>
                    </td>
                </tr>
                <tr>
                    <th>*法人姓名</th>
                    <td>
                        <input id="owner_name" name="owner_name" class="lr-input" type="text" placeholder="请输入法人名称">
                        <div class="Validform-checktip Validform-wrong"></div>
                    </td>
                </tr>
                <tr>
                    <th>*所在地区</th>
                    <td>
                        <div class="first-province-box" prefix="agent" style="display:inline-block">
                            <div class="select-class">
                                <a href="javascript:;" class="select-choice">-省份-<i class="select-arrow"></i></a>
                                <div class="select-list" style="display: none">
                                    <ul>
                                        <li field_value="">-省份-</li>
                                        <?php foreach($province_list as $key => $value) { ?>
                                            <li field_value="<?php echo $value['district_id'];?>"><?php echo $value['district_name'];?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="first-city-box" style="display:inline-block"></div>
                        <div class="first-area-box" style="display:inline-block"></div>
                        <input type="hidden" id="agent_provinceid" name="agent_provinceid" value=""  />
                        <input type="hidden" id="agent_cityid" name="agent_cityid" value=""  />
                        <input type="hidden" id="agent_areaid" name="agent_areaid" value=""  />
                        <div class="Validform-checktip Validform-wrong"></div>
                    </td>
                </tr>
                <tr>
                    <th>*详细地址</th>
                    <td>
                        <input id="agent_address" name="agent_address" class="lr-input" type="text" placeholder="点击输入输入详细地址">
                        <div class="Validform-checktip Validform-wrong"></div>
                        <div id="allmap"></div>
                    </td>
                </tr>
            </tbody>
            <tbody style="display: none;" class="agent_picture">
                <tr>
                    <td>
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_1" class="cover-item" style="display:none;"></li>
                                <li id="upload_image_1">
                                    <div class="img-update">
                                        <span class="img-layer">点击上传营业执照正本</span>
                                        <span class="img-file">
                                            <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_code_image" name="agent_code_image" class="image_1" value=""  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:40px;height:40px;"></div>
                            </ul>
                            <p class="help-desc">图片建议大小为400px*300px</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_2" class="cover-item" style="display:none;"></li>
                                <li id="upload_image_2">
                                    <div class="img-update">
                                        <span class="img-layer">点击上传法人身份证正面</span>
                                        <span class="img-file">
                                            <input type="file" id="file_2" name="file_2" field_id="2" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_person_image" name="agent_person_image" class="image_2" value=""  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:40px;height:40px;"></div>
                            </ul>
                            <p class="help-desc">图片建议大小为400px*300px</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_3" class="cover-item" style="display:none;"></li>
                                <li id="upload_image_3">
                                    <div class="img-update">
                                        <span class="img-layer">点击上传法人手持营业执照副本自拍</span>
                                        <span class="img-file">
                                            <input type="file" id="file_3" name="file_3" field_id="3" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_person_code_image" name="agent_person_code_image" class="image_3" value=""  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:40px;height:40px;"></div>
                            </ul>
                            <p class="help-desc">图片建议大小为400px*300px</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="picture-list">
                            <ul class="clearfix">
                                <li id="show_image_4" class="cover-item" style="display:none;"></li>
                                <li id="upload_image_4">
                                    <div class="img-update">
                                        <span class="img-layer">点击上传机构门头照片</span>
                                        <span class="img-file">
                                            <input type="file" id="file_4" name="file_4" field_id="4" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_sign_image" name="agent_sign_image" class="image_4" value=""  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:40px;height:40px;"></div>
                            </ul>
                            <p class="help-desc">图片建议大小为1180px*400px</p>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody style="display: none;"  class="trade_tool">

                <tr>
                    <th>注册手机号</th>
                    <td>
                        <input id="card_phone" name="card_phone" class="lr-input" type="text" placeholder="请输入手机号" value="<?php echo $this->member['member_phone'] ?>" disabled>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </td>
                </tr>
                <tr>
                    <th>验证码</th>
                    <td class="code-input" style="position:relative;">
                        <input id="phone_code" name="phone_code" class="lr-input code-input" type="text" maxlength="6">
                        <span class="take-code">获取手机验证码</span>
                        <div class="Validform-checktip Validform-wrong"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="choose_step_btn">
        <div class="toStep2">
            <input type="checkbox" id="agent_know" checked>同意<a href="index.php?act=article&article_id=4">&lt;&lt;团家政入驻声明&gt;&gt;</a>
            <div class="Validform-checktip Validform-wrong"></div>
            <div>
                <span class="toStep2_btn">下一步</span>
            </div>
            <p style="margin-top: 10px;"><img style="margin:0 5px 2px 0" src="templates/images/starO.png" alt="">注册账户时所登记号码，默认为机构号码，如需添加座机，请在注册完成并通过审核后进入机构中心编辑</p>
        </div>
        <div class="toStep3" style="display: none;">
            <input type="checkbox" id="agent_conceal" checked>同意<a href="index.php?act=article&article_id=4">&lt;&lt;团家政入驻声明&gt;&gt;</a>
            <div class="Validform-checktip Validform-wrong"></div>
            <div>
                <span class="toStep1_btn" style="margin-right: 20px;">上一步</span>
                <span class="toStep3_btn">下一步</span>
            </div>
        </div>
        <div class="toSuccess" style="display: none;">
            <input type="checkbox" id="agent_moneyBag" checked>同意<a href="index.php?act=article&article_id=4">&lt;&lt;团家政入驻声明&gt;&gt;</a>
            <div class="Validform-checktip Validform-wrong"></div>
            <div>
                <span class="returnStep2_btn" style="margin-right: 20px;">上一步</span>
                <span class="success_btn">提交注册</span>
            </div>
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

<script>
    var locations;
    $(".toStep2_btn").click(function () {
        var agent_name=$("#agent_name").val();
        var owner_name=$("#owner_name").val();
        var agent_provinceid = $('#agent_provinceid').val();
        var agent_cityid = $('#agent_cityid').val();
        var agent_areaid = $('#agent_areaid').val();
        var agent_address = $('#agent_address').val();
//        var service_address = $('#service_address').val();
//        var agent_phone=$("#agent_phone").val();
        if(agent_name == '') {
            $('#agent_name').focus();
            showerror('agent_name', '机构名称必须填写');
            return;
        }
        if(owner_name == '') {
            $('#owner_name').focus();
            showerror('owner_name', '法人名称必须填写');
            return;
        }else{
            showsuccess('owner_name');
        }
        if(agent_cityid == '') {
            $('#agent_name').focus();
            showerror('agent_provinceid', '所在地区必须填写');
            return;
        }
        if(agent_address == '') {
            $('#agent_address').focus();
            showerror('agent_address', '详细地址必须填写');
            return;
        }
//        if(service_address == '') {
//            $('#service_address').focus();
//            showerror('service_address', '服务地址必须填写');
//            return;
//        }else{
//            showsuccess('service_address');
//        }
//        if(agent_phone=='') {
//            $('#agent_phone').focus();
//            showerror('agent_phone', '机构号码必须填写');
//            return;
//        }else {
//            showsuccess('agent_phone');
//        }
        if(!$("#agent_know").is(':checked')){
            $('#agent_know').focus();
            showerror('agent_know', '需同意团家政商家须知');
            return;
        }else{
            showsuccess('agent_know');
        }
        var data={
            'address':agent_address,
            'city':agent_cityid
        }
        $.getJSON('http://restapi.amap.com/v3/geocode/geo?key=4b569988aabf7d13657119c564931f8f',data,function (data) {
            console.log(data);
            if(data.geocodes.length!==0) {
                console.log(data.geocodes[0].location);
                locations = data.geocodes[0].location;
            }
        })
        $(".toStep2").hide();
        $(".toStep3").show();
        $(".h1").hide();
        $(".h2").show();
        $(".l2").addClass("active");
        $(".l3").addClass("active");
        $(".basic_message").hide();
        $(".agent_picture").show();
    })
    $(".toStep1_btn").click(function () {
        $(".toStep2").show();
        $(".toStep3").hide();
        $(".h1").show();
        $(".h2").hide();
        $(".l2").removeClass("active");
        $(".l3").removeClass("active");
        $(".basic_message").show();
        $(".agent_picture").hide();
    });

    $(".toStep3_btn").click(function () {
        var agent_code_image=$("#agent_code_image").val();
         var agent_person_image=$("#agent_person_image").val();
         var agent_person_code_image=$("#agent_person_code_image").val();
         var agent_sign_image=$("#agent_sign_image").val();
         if(agent_code_image==''){
             $("#agent_code_image").focus();
             showerror('agent_code_image','营业执照正本必须上传');
             return;
         }else{
             showsuccess('agent_code_image');
         }
        if(agent_person_image==''){
            $("#agent_person_image").focus();
            showerror('agent_person_image','法人身份证正面必须上传');
            return;
        }else{
            showsuccess('agent_person_image');
        }
        if(agent_person_code_image==''){
            $("#agent_person_code_image").focus();
            showerror('agent_person_code_image','法人手持营业执照自拍');
            return;
        }else{
            showsuccess('agent_person_code_image');
        }
        if(agent_sign_image==''){
            $("#agent_sign_image").focus();
            showerror('agent_sign_image','机构门头照片必须上传');
            return;
        }else{
            showsuccess('agent_sign_image');
        }
        if(!$("#agent_conceal").is(':checked')){
            $('#agent_conceal').focus();
            showerror('agent_conceal', '需同意团家政隐私协议');
            return;
        }else{
            showsuccess('agent_conceal');
        }
        $(".h2").hide();
        $(".h3").show();
        $(".l4").addClass("active");
        $(".l5").addClass("active");
        $(".trade_tool").show();
        $(".agent_picture").hide();
        $(".toSuccess").show();
        $(".toStep3").hide();
    });
    var submit_btn=false;
  $(".returnStep2_btn").click(function () {
      $(".h2").show();
      $(".h3").hide();
      $(".l4").removeClass("active");
      $(".l5").removeClass("active");
      $(".trade_tool").hide();
      $(".agent_picture").show();
      $(".toSuccess").hide();
      $(".toStep3").show();
  });
    $(".success_btn").click(function () {

        var formhash = $('#formhash').val();
        var agent_name=$("#agent_name").val();
        var owner_name=$("#owner_name").val();
        var agent_provinceid = $('#agent_provinceid').val();
        var agent_cityid = $('#agent_cityid').val();
        var agent_areaid = $('#agent_areaid').val();
        var agent_address = $('#agent_address').val();
//        var service_address = $('#service_address').val();
//        var agent_phone=$("#agent_phone").val();
        var agent_summary=$("#agent_summary").val();
        var agent_content=$("#agent_content").val();
        var agent_code_image=$("#agent_code_image").val();
        var agent_person_image=$("#agent_person_image").val();
        var agent_person_code_image=$("#agent_person_code_image").val();
        var agent_sign_image=$("#agent_sign_image").val();

        var card_phone=$("#card_phone").val();
        var phone_code=$("#phone_code").val();


        if(card_phone==''){
            $("#card_phone").focus();
            showerror('card_phone', '注册手机号');
            return;
        }else {
            showsuccess('card_phone');
        }
        var reg2 = /^[1][0-9]{10}$/;
        if(!reg2.test(card_phone)){
            $("#card_phone").focus();
            showerror('card_phone', '手机号格式不正确');
            return;
        }else{
            showsuccess('card_phone');
        }
        if(phone_code==''){
            $("#phone_code").focus();
            showerror('phone_code','请填写验证码');
            return;
        }else{
            showsuccess('phone_code');
        }
        if(!$("#agent_moneyBag").is(':checked')){
            $('#agent_moneyBag').focus();
            showerror('agent_moneyBag', '需同意团家政隐私协议');
            return;
        }else{
            showsuccess('agent_moneyBag');
        }
        var agent_location=locations;
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'agent_name':agent_name,
            'owner_name':owner_name,
            'agent_provinceid':agent_provinceid,
            'agent_cityid':agent_cityid,
            'agent_areaid':agent_areaid,
            'agent_address':agent_address,
            'agent_location':agent_location,
//            'agent_phone':agent_phone,

            'agent_code_image':agent_code_image,
            'agent_person_image':agent_person_image,
            'agent_person_code_image':agent_person_code_image,
            'agent_sign_image':agent_sign_image,
            'card_phone':card_phone,
            'phone_code':phone_code
        }
        console.log(submitData);
        if(submit_btn) return;
        submit_btn = true;
        $.ajax({
            type : 'POST',
            url : 'index.php?act=agent&op=step2',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data) {
                submit_btn = false;
                if(data.done == 'true') {
                    window.location.href = 'index.php?act=agent&op=step3';
                } else if(data.done == 'login') {
                    window.location.href = 'index.php?act=agent&op=login';
                } else if(data.done == 'agent') {
                    window.location.href = 'index.php?act=agent_center';
                } else {
                    if(data.id == 'system') {
                        showalert(data.msg);
                    } else {
                        showerror(data.id, data.msg);
                    }
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                submit_btn = false;
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
    
    
    
//    获取验证码
    var code_btn = false;
    $('.take-code').on('click', function() {
        if($('.take-code').hasClass('acquired')) return;
        var card_phone = $('#card_phone').val();
        if(card_phone == '') {
            showerror('card_phone', '手机号必须填写');
            return;
        }else{
            showsuccess('card_phone');
        }
        var regu = /^[1][0-9]{10}$/;
        if(!regu.test(card_phone)) {
            showerror('card_phone', '手机号格式不正确');
            return;
        }else{
            showsuccess('card_phone');
        }
        if(code_btn) return;
        code_btn = true;
        var submitData = {
            'member_phone' : card_phone
        };
        $.ajax({
            type : 'POST',
            url : 'index.php?act=misc&op=test_code',
            data : submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success : function(data){
                code_btn = false;
                if(data.done == 'true') {
                    var second = 60;
                    $('.take-code').addClass('acquired');
                    $('.take-code').html('重新获取('+ second +'秒)');
                    var progress = setInterval(function(){
                        if(second <= 0) {
                            $('.take-code').removeClass('acquired');
                            $('.take-code').html('重新获取');
                            clearInterval(progress);
                        } else {
                            second--;
                            $('.take-code').html('重新获取('+ second +'秒)');
                        }
                    },1000);
                } else {
                    showerror('card_phone', data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                code_btn = false;
                showalert('网路不稳定，请稍候重试');
            }
        });
    });
</script>












<script type="text/javascript">
    var file_name = 'agent';
</script>

<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="templates/js/imageupload.js"></script>
<script type="text/javascript" src="templates/js/member/agent.js"></script>