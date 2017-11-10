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
                                <span>预定单号：<?php echo $bespoke['bespoke_sn'];?></span>
                                <em>
                                    <strong>￥<?php echo $bespoke['bespoke_amount'];?></strong>
                                </em>
                            </dt>
                            <dd class="wf show">
                                <h6><?php echo $pension['pension_name'];?> <?php echo $room['room_name'];?></h6>
                                <p>入驻时间：<?php echo date('Y-m-d H:i', $bespoke['live_time']);?>  入驻时长：<?php echo $bespoke['live_duration'];?>个月 床位：<?php echo $bespoke['bed_number'];?></p>
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
		function checkstate(bespoke_sn) {
			$.getJSON('index.php?act=bespoke&op=checkstate', {'bespoke_sn':bespoke_sn}, function(data){
				if(data.done == 'true') {
					window.location.href = 'index.php?act=bespoke&op=weixin&bespoke_sn='+bespoke_sn;
				}
			});
		}

		$(function() {
			setInterval("checkstate('<?php echo $bespoke['bespoke_sn'];?>')", 1500);
		});
	</script>
<?php include(template('common_footer'));?>