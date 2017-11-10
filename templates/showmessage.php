<?php include(template('common_header'));?>
    	<div class="conwp clearfix">
            <h1 class="top-logo">
                <a href="index.php"><img src="templates/images/logo.png"></a>
                <strong>消息提醒</strong>
            </h1>
        </div>
    </div>
    <div class="conwp">
    	<div class="page-jumptip">
            <div class="m-success-tip">
                <div class="tip-inner">
                    <?php if($msg_type == 'succ') { ?>
                    <span class="tip-icon">
                        <i class="iconfont icon-check"></i>
                    </span>
					<?php } elseif($msg_type == 'info') { ?>
					<span class="tip-icon">
                        <i class="iconfont icon-info"></i>
                    </span>
                    <?php } else { ?>
                    <span class="tip-icon">
                        <i class="iconfont icon-error"></i>
                    </span>
                    <?php } ?>
                    <h3 class="tip-title"><?php echo $msg;?></h3>
					<?php if(!empty($url)) { ?>
                    <div class="tip-hint"><a href="<?php echo $url;?>">如果您的浏览器没有自动跳转，请点击此链接</a></div>
                    <?php } else { ?>
                    <div class="tip-hint"><a href="javascript:history.back()">点击这里返回上一页</a></div>
                    <?php } ?>
                </div>
            </div>
        </div>
	</div>
    <?php if(!empty($url)) { ?>
		<script type="text/javascript">
            setTimeout(function() {
                window.location.href = '<?php echo $url;?>';
            }, 3000);
        </script>
    <?php } ?>
<?php include(template('common_footer'));?>