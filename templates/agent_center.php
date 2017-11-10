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
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_center">首页编辑</a></li>
                <li><a href="index.php?act=agent_question">机构问答</a></li>
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
            <?php if($agent['revise_state'] == 0) { ?>
                <div class="edit-box">
                    <p class="b-tips"><span class="red" style="font-size: 24px;">我们将在一天内完成您的信息审核，请耐心等待我们的客服人员联系您</span></p>
                </div>
            <?php } elseif($agent['revise_state'] == 1) { ?>
                <div class="edit-box">
                    <p class="b-tips"><span class="red" style="font-size: 24px;">您提交的信息已经通过审核，<a href="index.php?act=agent_show&agent_id=<?php echo $agent['agent_id'] ?>">点我</a>进入首页查看效果</span></p>
                </div>
            <?php } elseif($agent['revise_state'] == 2) { ?>
                <div class="edit-box">
                    <p class="b-tips"><span class="red" style="font-size: 24px;">抱歉，您的审核不通过，请确认您的信息正确与完整</span></p>
                </div>
            <?php } ?>
            <input type="hidden" id="formhash" name="formhash" value="<?php echo formhash();?>" />
            <div class="agent_manage_point">
                <img style="margin:0 5px 2px 0" src="templates/images/warning_mark.png" alt="">每次修改资料都会在1-3个工作日内审核完成，审核完成之前请勿再次修改。审核完成后本页面内容也会更新，如有疑问，请联系客服
            </div>
            <div class="basic_message_set">
                <p><span>基本信息编辑</span></p>
                <div>
                    机构名称 <input id="agent_name" type="text" value="<?php echo $agent['agent_name'] ?>">
                    法人 <input id="owner_name" type="text" value="<?php echo $agent['owner_name'] ?>">
                    客服座机 <input id="agent_phone" type="text" value="<?php echo $agent['agent_phone'] ?>">
                    <br>
                    地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;址 <input id="agent_address" type="text" value="<?php echo $agent['agent_address'] ?>">
                    <span style="margin-left: 50px;text-decoration: underline;">点击文本框即可修改</span>
                </div>
            </div>
            <div class="agent_banner_set">
                <p><span>首页广告编辑</span> <b style="display:inline-block;margin-left:50px;font-size:14px;font-weight: normal;">上传图片必须为.jpg , .png格式&nbsp;&nbsp;横幅大小建议为1180*400像素 , logo为100*100</b></p>
                <div class="form-item clearfix left" style="margin-right: 200px;">
                    <label>机构横幅：</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <?php if(!empty($this->agent['agent_banner'])) { ?>
                                    <li id="show_image_0" class="cover-item">
                                        <img src="<?php echo $this->agent['agent_banner'];?>">
                                        <span class="close-modal single_close" field_id="0">×</span>
                                    </li>
                                <?php } else { ?>
                                    <li id="show_image_0" class="cover-item" style="display:none;"></li>
                                <?php } ?>
                                <li id="upload_image_0">
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                            <input type="file" id="file_0" name="file_0" field_id="0" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_logo" name="agent_logo" class="image_0" value="<?php echo $this->agent['agent_banner'];?>"  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-item clearfix left">
                    <label>机构LOGO：</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <?php if(!empty($this->agent['agent_logo'])) { ?>
                                    <li id="show_image_1" class="cover-item">
                                        <img src="<?php echo $this->agent['agent_logo'];?>">
                                        <span class="close-modal single_close" field_id="1">×</span>
                                    </li>
                                <?php } else { ?>
                                    <li id="show_image_1" class="cover-item" style="display:none;"></li>
                                <?php } ?>
                                <li id="upload_image_1">
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                            <input type="file" id="file_1" name="file_1" field_id="1" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_banner" name="agent_banner" class="image_1" value="<?php echo $this->agent['agent_logo'];?>"  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="agent_aptitude_set">
                <p><span>资质认证编辑</span> <b style="display:inline-block;margin-left:50px;font-size:14px;font-weight: normal;">上传图片必须为.jpg , .png格式</b></p>
                <div class="form-item clearfix left" style="margin-right: 100px;overflow: hidden;">
                    <label>营业执照正面</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <?php if(!empty($agent['agent_code_image'])) { ?>
                                    <li id="show_image_2" class="cover-item">
                                        <img src="<?php echo $agent['agent_code_image'];?>">
                                        <span class="close-modal single_close" field_id="2">×</span>
                                    </li>
                                <?php } else { ?>
                                    <li id="show_image_2" class="cover-item" style="display:none;"></li>
                                <?php } ?>
                                <li id="upload_image_2"<?php echo !empty($agent['agent_code_image']) ? ' style="display:none;"' : '';?>>
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                            <input type="file" id="file_2" name="file_2" field_id="2" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_code_image" name="agent_code_image" class="image_2" value="<?php echo $agent['agent_code_image'];?>"  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-item clearfix left" style="margin-right: 100px">
                    <label>法人身份证正面</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <?php if(!empty($agent['agent_person_image'])) { ?>
                                    <li id="show_image_3" class="cover-item">
                                        <img src="<?php echo $agent['agent_person_image'];?>">
                                        <span class="close-modal single_close" field_id="3">×</span>
                                    </li>
                                <?php } else { ?>
                                    <li id="show_image_3" class="cover-item" style="display:none;"></li>
                                <?php } ?>
                                <li id="upload_image_3"<?php echo !empty($agent['agent_person_image']) ? ' style="display:none;"' : '';?>>
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                             <input type="file" id="file_3" name="file_3" field_id="3" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_person_image" name="agent_person_image" class="image_3" value="<?php echo $agent['agent_person_image'];?>"  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-item clearfix left" style="margin-right: 100px">
                    <label>法人手持营业执照</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <?php if(!empty($agent['agent_person_code_image'])) { ?>
                                    <li id="show_image_4" class="cover-item">
                                        <img src="<?php echo $agent['agent_person_code_image'];?>">
                                        <span class="close-modal single_close" field_id="4">×</span>
                                    </li>
                                <?php } else { ?>
                                    <li id="show_image_4" class="cover-item" style="display:none;"></li>
                                <?php } ?>
                                <li id="upload_image_4"<?php echo !empty($agent['agent_person_code_image']) ? ' style="display:none;"' : '';?>>
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                             <input type="file" id="file_4" name="file_4" field_id="4" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_person_code_image" name="agent_person_code_image" class="image_4" value="<?php echo $agent['agent_person_code_image'];?>"  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-item clearfix left">
                    <label>机构门头照</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <?php if(!empty($agent['agent_sign_image'])) { ?>
                                    <li id="show_image_5" class="cover-item">
                                        <img src="<?php echo $agent['agent_sign_image'];?>">
                                        <span class="close-modal single_close" field_id="5">×</span>
                                    </li>
                                <?php } else { ?>
                                    <li id="show_image_5" class="cover-item" style="display:none;"></li>
                                <?php } ?>
                                <li id="upload_image_5"<?php echo !empty($agent['agent_sign_image']) ? ' style="display:none;"' : '';?>>
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                             <input type="file" id="file_5" name="file_5" field_id="5" hidefocus="true" maxlength="0" mall_type="image" mode="single">
                                        </span>
                                    </div>
                                </li>
                                <input type="hidden" id="agent_sign_image" name="agent_sign_image" class="image_5" value="<?php echo $agent['agent_sign_image'];?>"  />
                                <div class="Validform-checktip Validform-wrong" style="line-height:100px;height:100px;"></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="agent_work_aptitude_set">
                <div class="form-item clearfix left">
                    <label>工作资质（建议四张及以下）</label>
                    <div class="form-item-value">
                        <div class="picture-list">
                            <ul class="clearfix">
                                <?php foreach($agent['agent_qa_image'] as $key => $value) { ?>
                                    <li class="cover-item">
                                        <img src="<?php echo $value;?>">
                                        <span class="close-modal multi_close">×</span>
                                        <input type="hidden" name="image_6[]" class="image_6" value="<?php echo $value;?>">
                                    </li>
                                <?php } ?>
                                <li id="show_image_6">
                                    <div class="img-update">
                                        <span class="img-layer">+ 上传</span>
                                        <span class="img-file">
                                               <input type="file" id="file_6" name="file_6" field_id="6" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
                                         </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="agent_content_set">
                <div class="left agent_summary_set">
                    <p><span>机构概述编辑</span></p>
                    <textarea name="agent_summary" id="agent_summary" cols="30" rows="10"><?php echo $agent['agent_summary'] ?></textarea>
                </div>
                <div class="left agent_service_set">
                    <p><span>机构服务编辑</span></p>
                    <textarea name="agent_content" id="agent_content" cols="30" rows="10"><?php echo $agent['agent_content'] ?></textarea>
                </div>
            </div>
             <div class="agent_service_image_set">
                    <p><span>机构服务广告编辑</span><b style="display:inline-block;margin-left:50px;font-size:14px;font-weight: normal;">上传图片必须为.jpg , .png格式&nbsp;&nbsp;建议图片大小为210*150像素，建议上传图片数量为4张</b></p>
                    <div>
                        <div class="form-item clearfix left">
                            <div class="form-item-value">
                                <div class="picture-list">
                                    <ul class="clearfix">
                                        <?php foreach($agent['agent_service_image'] as $key => $value) { ?>
                                            <li class="cover-item">
                                                <img src="<?php echo $value;?>">
                                                <span class="close-modal multi_close">×</span>
                                                <input type="hidden" name="image_7[]" class="image_7" value="<?php echo $value;?>">
                                            </li>
                                        <?php } ?>
                                        <li id="show_image_7">
                                            <div class="img-update">
                                                <span class="img-layer">+ 上传</span>
                                                <span class="img-file">
                                               <input type="file" id="file_7" name="file_7" field_id="7" hidefocus="true" maxlength="0" mall_type="image" mode="multi">
                                         </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="agent_manage_submit">
                 <span class="agent_manage_submit_btn">确认 提交审核</span><span class="return-success" style="display: block;"></span>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="templates/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="templates/js/imageupload.js"></script>
<script type="text/javascript">
    var file_name = 'agent';
    var agent_id='<?php echo $agent['agent_id'] ?>';
    var agent_location='<?php echo $agent['agent_location'] ?>';
    console.log(agent_location);
</script>
<script>
    var locations;
    $("#agent_address").blur(function () {
        if($(this).val()!==''){
            var address=$(this).val();
            var data={
                'address':address
            }
            $.getJSON('http://restapi.amap.com/v3/geocode/geo?key=4b569988aabf7d13657119c564931f8f',data,function (data) {
                if(data.geocodes.length!==0) {
                    locations = data.geocodes[0].location;
                }
            })
        }
    })
    var submit_btn = false;
    $(".agent_manage_submit_btn").click(function () {
        var formhash = $('#formhash').val();
        var agent_name=$("#agent_name").val();
        var owner_name=$("#owner_name").val();
        var agent_phone=$("#agent_phone").val();
        var agent_address=$("#agent_address").val();
        var agent_locations;
        if(locations==undefined||locations==''){
            agent_locations=agent_location;
        }else{
            agent_locations=locations;
        }
        var agent_banner=$("#agent_banner").val();
        var agent_logo=$("#agent_logo").val();
        var agent_code_image=$("#agent_code_image").val();
        var agent_person_image=$("#agent_person_image").val();
        var agent_person_code_image=$("#agent_person_code_image").val();
        var agent_sign_image=$("#agent_sign_image").val();
        var i=0;
        var agent_qa_image={};
        $('.image_6').each(function() {
            agent_qa_image[i] = $(this).val();
            i++;
        });
        var agent_summary=$("#agent_summary").val();
        var agent_content=$("#agent_content").val();
        var j=0;
        var agent_service_image={};
        $('.image_7').each(function() {
            agent_service_image[j] = $(this).val();
            j++;
        });
        var submitData={
            'form_submit' : 'ok',
            'formhash' : formhash,
            'agent_id':agent_id,
            'agent_name':agent_name,
            'owner_name':owner_name,
            'agent_phone':agent_phone,
            'agent_address':agent_address,
            'agent_location':agent_locations,
            'agent_banner':agent_banner,
            'agent_logo':agent_logo,
            'agent_code_image':agent_code_image,
            'agent_person_image':agent_person_image,
            'agent_person_code_image':agent_person_code_image,
            'agent_sign_image':agent_sign_image,
            'agent_qa_image':agent_qa_image,
            'agent_service_image':agent_service_image,
            'agent_summary':agent_summary,
            'agent_content':agent_content
        };
        $.ajax({
            type:'POST',
            url:'index.php?act=agent_center&op=agent_revise',
            data:submitData,
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            dataType : 'json',
            success:function (data) {
                console.log(data);
                submit_btn=false;
                if(data.done=='true'){
                    $('.return-success').html('提交成功，请耐心等待审核');
                    $('.return-success').show();
                    setTimeout(function () {
                        $('.return-success').hide();
                    },3000)
                }else{
                    if(data.id=='system'){
                        $('.return-success').html(data.msg);
                        $('.return-success').show();
                        setTimeout(function () {
                            $('.return-success').hide();
                        },3000)
                    }
                }
            },
            timeout : 15000,
            error:function (xhr, type) {
                submit_btn = false;
                $('.return-success').html(data.msg);
                $('.return-success').show();
                setTimeout(function () {
                    $('.return-success').hide();
                },3000)
            }
        })
    })
</script>
