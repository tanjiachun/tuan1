<?php include(template('common_header'));?>
    <link rel="stylesheet" href="templates/css/admin.css">
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
                <li style="background: #000;border-left:5px solid #ff6905;box-sizing: border-box;"><a href="index.php?act=agent_invoice">发票管理</a></li>
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
        <div id="agent_manage_set" class="left form-list" style="width:970px;margin:0 0 0 10px;">
            <div class="agent_orders_list">
                <p><span>全部发票</span></p>
                <div class="orderlist">
                    <div class="orderlist-head clearfix">
                        <ul>
                            <li>
                                <a href="index.php?act=agent_invoice&state=person"<?php echo $state=='person' ? ' class="active"' : '';?>>个人发票</a>
                            </li>
                            <li>
                                <a href="index.php?act=agent_invoice&state=unit"<?php echo $state=='unit' ? ' class="active"' : '';?>>单位发票</a>
                            </li>
                        </ul>
                    </div>
                    <div class="orderlist-body">
                        <table class="order-tb">
                        <?php if($state=='person') { ?>
                                <thead>
                                <tr>
<!--                                    <th width="30"></th>-->
                                    <th width="50">发票种类</th>
                                    <th width="100">发票抬头</th>
                                    <th width="200">发票明细</th>
                                    <th width="80">收件人</th>
                                    <th width="200">收件地址</th>
                                    <th width="100">收件人号码</th>
                                    <th width="80">操作</th>
                                    <th width="80">状态</th>
                                </tr>
                                </thead>
                                <?php foreach($book_list as $key => $value) { ?>
                                    <tbody id="book_<?php echo $value['book_id'];?>" ">
                                    <tr class="tr-bd">
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_type'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_title'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_content'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_membername'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_areainfo'] ?> <?php echo $invoice_list[$value['invoice_id']]['invoice_address'] ?></td>
                                        <td><?php echo $value['member_phone'] ?></td>
                                        <td><a href="javascript:;" class="invoice_show" book_id="<?php echo $value['book_id'] ?>">查看</a></td>
                                        <td>
                                            <?php if($value['invoice_state']==1){ ?>
                                                <a style="color: #ff6905;" href="javascript:;" class="invoice_ok" book_id="<?php echo $value['book_id'] ?>">已开</a>
                                            <?php } else { ?>
                                                <a href="javascript:;" class="invoice_ok" book_id="<?php echo $value['book_id'] ?>">已开</a>
                                            <?php } ?>
                                            /
                                            <?php if($value['invoice_state']==1){ ?>
                                            <a href="javascript:;" class="invoice_no" book_id="<?php echo $value['book_id'] ?>">未开</a></td>
                                            <?php } else { ?>
                                                <a style="color: #ff6905" href="javascript:;" class="invoice_no" book_id="<?php echo $value['book_id'] ?>">未开</a></td>
                                            <?php } ?>
                                    </tr>
                                    </tbody>
                                <?php } ?>
                        <?php } else { ?>
                            <table class="order-tb">
                                <thead>
                                <tr>
                                    <th width="50">发票种类</th>
                                    <th width="100">单位名称</th>
                                    <th width="200">纳税人识别码</th>
                                    <th width="80">收件人</th>
                                    <th width="200">收件地址</th>
                                    <th width="100">收件人号码</th>
                                    <th width="80">操作</th>
                                    <th width="80">状态</th>
                                </tr>
                                </thead>
                                <?php foreach($book_list as $key => $value) { ?>
                                    <tbody id="book_<?php echo $value['book_id'];?>" ">
                                    <tr class="tr-bd">
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_type'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['unit_name'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_code'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_unit_membername'] ?></td>
                                        <td><?php echo $invoice_list[$value['invoice_id']]['invoice_areainfo'] ?> <?php echo $invoice_list[$value['invoice_id']]['invoice_address'] ?></td>
                                        <td><?php echo $value['member_phone'] ?></td>
                                        <td><a href="javascript:;" class="invoice_show" book_id="<?php echo $value['book_id'] ?>">查看</a></td>
                                        <td>
                                            <?php if($value['invoice_state']==1){ ?>
                                                <a style="color: #ff6905;" href="javascript:;" class="invoice_ok" book_id="<?php echo $value['book_id'] ?>">已开</a>
                                            <?php } else { ?>
                                                <a href="javascript:;" class="invoice_ok" book_id="<?php echo $value['book_id'] ?>">已开</a>
                                            <?php } ?>
                                            /
                                            <?php if($value['invoice_state']==1){ ?>
                                            <a href="javascript:;" class="invoice_no" book_id="<?php echo $value['book_id'] ?>">未开</a></td>
                                        <?php } else { ?>
                                            <a style="color: #ff6905" href="javascript:;" class="invoice_no" book_id="<?php echo $value['book_id'] ?>">未开</a></td>
                                        <?php } ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                <?php } ?>
                            </table>
                        <?php } ?>
                         </table>
                    </div>
                    <?php echo $multi;?>
                </div>
            </div>
        </div>
    </div>
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
    $(".invoice_show").click(function () {
        var book_id=$(this).attr('book_id');
        Custombox.open({
            target: 'index.php?act=agent_invoice&op=invoice_show&book_id='+book_id,
            effect: 'blur',
            overlayClose: true,
            speed:500,
            overlaySpeed: 300,
        });
    })
    $(".invoice_ok").click(function () {
        var book_id=$(this).attr('book_id');
        $.getJSON('index.php?act=agent_invoice&op=invoice_ok&book_id='+book_id,function (data) {
            if(data.done=='true'){
                window.location.reload();
            }else{
                showalert('网络不稳定，请稍后重试');
            }
        });
    })
    $(".invoice_no").click(function () {
        var book_id=$(this).attr('book_id');
        $.getJSON('index.php?act=agent_invoice&op=invoice_no&book_id='+book_id,function (data) {
            if(data.done=='true'){
                window.location.reload();
            }else{
                showalert('网络不稳定，请稍后重试')
            }
        });
    })
</script>