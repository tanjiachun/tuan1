<?php include(template('common_header'));?>
    <div class="wrap">
    	<div class="content clearfix">
		<?php if($msg_type == 'succ') { ?>
			<div class="tip_box">
                <h2><i class="icon32_common icon-success"></i><?php echo $msg;?></h2>
            </div>
        <?php } else { ?>
            <div class="tip_box error_box">
                <h2><i class="icon32_common icon-error"></i><?php echo $msg;?></h2>
            </div> 
        <?php } ?>
        </div>
    </div>
	<?php if(!empty($url)) { ?>
    	<script type="text/javascript">
			setTimeout(function() {
				window.location.href = '<?php echo $url;?>';
			}, <?php echo $time;?>);
		</script>
    <?php } ?>
<?php include(template('common_footer'));?>