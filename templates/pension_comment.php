<?php foreach($comment_list as $key => $value) { ?>
<div class="commit-item clearfix">
	<div class="commit-score">
		<span><strong><?php echo $value['comment_score'];?></strong>分</span>
		<div class="score-item">
			<?php for($i=0; $i<5; $i++) { ?>
			<?php if($i < $value['comment_score']) { ?>
			<i class="iconfont icon-solidstar cur"></i>
			<?php } else { ?>
			<i class="iconfont icon-solidstar"></i>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
	<div class="commit-info">
		<div class="commit-desc"><?php echo $value['comment_content'];?></div>
		<?php if(!empty($value['comment_image'])) { ?>
		<div class="commit-img">
			<ul>
				<?php foreach($value['comment_image'] as $subkey => $subvalue) { ?>
				<li><img src="<?php echo $subvalue;?>"></li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div>
	<div class="commit-user">
		<?php if(empty($member_list[$value['member_id']]['member_avatar'])) { ?>
		<img src="templates/images/peopleicon_01.gif">
		<?php } else { ?>
		<img src="<?php echo $member_list[$value['member_id']]['member_avatar'];?>">
		<?php } ?>
		<p><?php echo $member_list[$value['member_id']]['member_phone'];?></p>
		<p><?php echo date('Y-m-d H:i', $value['comment_time']);?></p>
	</div>
</div>
<?php } ?>