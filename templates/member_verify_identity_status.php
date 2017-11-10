<?php include(template('common_header'));?>
    <style>
        .header{
            border:1px solid #ddd;
        }
    </style>
<?php //var_dump($member);?>
<link href="templates/css/bootstrap.min.css" rel="stylesheet">
<link href="templates/css/verify.css" rel="stylesheet">
<script src="templates/js/member/jquery-1.11.1.min.js"></script>
<script src="templates/js/member/verify_check.js"></script>
<link rel="stylesheet" href="templates/css/admin.css">
    <div class="member_center_header">
        <img src="templates/images/my_tjz.png" alt="">
    </div>
</div>
<div id="member_manage">
    <div id="member_manage_content">
        <div class="login-box f-mt10 f-pb50">
            <div class="main bgf">
                <div class="reg-box-pan display-inline">
                    <div class="step">
                        <ul>
                            <li class="col-xs-4 on">
                                <span class="num"><em class="f-r5"></em><i>1</i></span>
                                <span class="line_bg lbg-r"></span>
                                <p class="lbg-txt">填写个人信息</p>
                            </li>
                            <li class="col-xs-4 on">
                                <span class="num"><em class="f-r5"></em><i>2</i></span>
                                <span class="line_bg lbg-l"></span>
                                <span class="line_bg lbg-r"></span>
                                <p class="lbg-txt">上传个人资料</p>
                            </li>
                            <li class="col-xs-4 on">
                                <span class="num"><em class="f-r5"></em><i>3</i></span>
                                <span class="line_bg lbg-l"></span>
                                <p class="lbg-txt">实名成功</p>
                            </li>
                        </ul>
                    </div>
                    <div class="reg-box" id="verifyCheck" style="margin-top:20px;">
                        <div class="part4 text-center">
                            <h3 style="padding-top: 40px;font-size:30px;color:#ff4400;"><?php print $template_msg;?></h3>
                            <p class="c-666 f-mt30 f-mb50"><a href="./index.php?act=member_center" style="color:#0080cb;">页面将在 <strong id="times" class="f-size18">3</strong> 秒钟后，跳转到 用户中心</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="javascript"><!--

    $(function () {
        setTimeout("lazyGo();", 1000);
    });
    function lazyGo() {
        var sec = $("#times").text();
        $("#times").text(--sec);
        if (sec > 0){
            setTimeout("lazyGo();", 1000);
        }
        else{
            window.location.href = './index.php?act=member_center';
        }
    }


    // --></script>