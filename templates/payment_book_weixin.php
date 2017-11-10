<?php include(template('common_header'));?>
		<div class="conwp clearfix">
            <h1 class="top-logo">
                <a href="index.php"><img src="templates/images/logo.png"></a>
                <strong>支付</strong>
            </h1>
        </div>
    </div>
    <div class="content">
        <div class="conwp">
            <div class="pay-box">
                <div class="pay-head">
                    <div class="money">
                        <dl>
							<dt>
                                <span>预约单号：<?php echo $book['book_sn'];?></span>
                                <em>
                                    <strong>￥<?php echo $book['book_amount'];?></strong>
                                </em>
                            </dt>
                            <dd class="wf show">
                                <h6><?php echo $nurse['nurse_name'];?> <?php echo $nurse['member_phone'];?></h6>
                                <p>开始服务时间：<?php echo date('Y-m-d H:i', $book['work_time']);?>  服务时长：<?php echo $book['work_duration'];?>个月</p>
                            </dd>
                        </dl>
                    </div>
                </div>
                <dl class="saoyisao">
                    <dt>
                        <div class="qrCode">
                            <img src="index.php?act=misc&op=qrcode&l=<?php echo urlencode($code_url);?>" width="298px" height="298px">
                        </div>
                        <div class="pw-box-ft">
                            <p>请使用微信扫一扫</p>
                            <p>扫描二维码支付</p>
                        </div>
                    </dt>
                    <dd>
                        <div class="qrCode"></div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
	<script type="text/javascript">

        function checkstate(book_sn) {
            $.getJSON('index.php?act=book&op=weixin',{'book_sn':book_sn},function (data) {
                if(data.data.trade_state=='SUCCESS' && data.money*100==parseInt(data.data.total_fee) && data.data.return_code=='SUCCESS' && data.data.result_code=='SUCCESS'){
                    var transaction_id =data.data.transaction_id;
                    var total_fee=data.data.total_fee;
                    $.getJSON('index.php?act=book&op=weixin_book',{'book_sn':book_sn,'transaction_id':transaction_id,'total_fee':total_fee},function (data) {
                        if(data.done=='true'){
                            window.location.href='index.php?act=member_book';
                        }
                    })
                }
            })

        }

//        function checkstate(book_sn) {
//            $.getJSON('index.php?act=book&op=checkstate', {'book_sn':book_sn}, function(data){
//                if(data.done == 'true') {
//                    window.location.href = 'index.php?act=book&op=weixin&book_sn='+book_sn;
//                }
//            });
//        }

		$(function() {
//			setInterval("checkstate('<?php //echo $book['book_sn'];?>//')", 1500);
			setInterval(function () {
                checkstate('<?php echo $book['book_sn'];?>');
            },1500);
//			console.log("checkstate('<?php //echo $book['book_sn'];?>//')");
		});
	</script>
<?php include(template('common_footer'));?>