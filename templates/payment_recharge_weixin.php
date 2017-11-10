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
                                <span>充值单号：<?php echo $recharge['pdr_sn'];?></span>
                                <em>
                                    <strong>￥<?php echo $recharge['pdr_amount'];?></strong>
                                </em>
                            </dt>
                            <dd class="wf show">
                                <h6>充值帐号：<?php echo $recharge['pdr_memberphone'];?></h6>
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
		function checkstate(pdr_sn) {
			$.getJSON('index.php?act=recharge&op=checkstate', {'pdr_sn':pdr_sn}, function(data){
				if(data.done == 'true') {
					window.location.href = 'index.php?act=recharge&op=weixin&pdr_sn='+pdr_sn;
				}
			});
		}
		$(function() {
			setInterval("checkstate('<?php echo $recharge['pdr_sn'];?>')", 1500);
		});
	</script>
<?php include(template('common_footer'));?>