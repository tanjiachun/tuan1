	<?php if($curmodule == 'home') { ?>
	<div class="footer">
		<div class="conwp">
			<div class="footer-link-list">
				<h1>城市列表</h1>
				<ul class="clearfix">
                	<?php foreach($this->setting['first_province_list'] as $key => $value) { ?>
					<li><a href="index.php?act=city&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a><span>|</span></li>
                    <?php } ?>
					<li><a href="index.php?act=city">更多</a></li>
				</ul>
				<ul class="clearfix">
                	<?php foreach($this->setting['second_province_list'] as $key => $value) { ?>
					<li><a href="index.php?act=city&op=select&district_id==<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a><span>|</span></li>
                    <?php } ?>
				</ul>
			</div>
			<div class="footer-link-list">
				<h1>友情链接</h1>
				<ul class="clearfix">
					<?php foreach($this->link_list['text'] as $key => $value) { ?>
					<li><a href="<?php echo $value['link_url'];?>"><?php echo $value['link_name'];?></a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="footer-link-list about-link">
				<ul class="clearfix">
					<?php $length = count($this->article_list);?>
                	<?php foreach($this->article_list as $key => $value) { ?>
                    <li>
                    	<a href="index.php?act=article&article_id=<?php echo $value['article_id'];?>"><?php echo $value['article_title'];?></a>
                        <?php if($length > $key+1) { ?>
                        <span>|</span>
                        <?php } ?>
                    </li>
                    <?php } ?>
				</ul>
				<p><?php echo $this->setting['copy_right'];?></p>
			</div>
			<div class="in clearfix">
				<?php foreach($this->link_list['image'] as $key => $value) { ?>
                <a href="<?php echo $value['link_url'];?>" target="_blank" rel="nofollow"><img src="<?php echo $value['link_image'];?>" /></a>
                <?php } ?>
			</div>
			<div class="footer-qrcode">
				<img src="<?php echo $this->setting['app_image'];?>">
				<p>扫一扫关注我们</p>
			</div>
		</div>
	</div>
	<?php } elseif($curmodule == 'member') { ?>
        <div class="login-about">
            <p>
                <?php foreach($this->article_list as $key => $value) { ?>
                <?php if(empty($key)) { ?>
                    <a href="index.php?act=article&article_id=<?php echo $value['article_id'];?>"><?php echo $value['article_title'];?></a>
                <?php } else { ?>
                    <em>|</em><a href="index.php?act=article&article_id=<?php echo $value['article_id'];?>"><?php echo $value['article_title'];?></a>
                <?php } ?>
                <?php } ?>
            </p>
            <p><?php echo $this->setting['copy_right'];?></p>
        </div>
	</div>        
	<?php } elseif($curmodule == 'profile') { ?>
	<div class="footer">
		<div class="conwp">
			<div class="footer-link-list about-link">
				<ul class="clearfix">
					<?php $length = count($this->article_list);?>
                	<?php foreach($this->article_list as $key => $value) { ?>
                    <li>
                    	<a href="index.php?act=article&article_id=<?php echo $value['article_id'];?>"><?php echo $value['article_title'];?></a>
                        <?php if($length > $key+1) { ?>
                        <span>|</span>
                        <?php } ?>
                    </li>
                    <?php } ?>
				</ul>
				<p><?php echo $this->setting['copy_right'];?></p>
			</div>
			<div class="in clearfix">
				<?php foreach($this->link_list['image'] as $key => $value) { ?>
                <a href="<?php echo $value['link_url'];?>" target="_blank" rel="nofollow"><img src="<?php echo $value['link_image'];?>" /></a>
                <?php } ?>
			</div>
			<div class="footer-qrcode">
				<img src="<?php echo $this->setting['app_image'];?>">
				<p>扫一扫关注我们</p>
			</div>
		</div>
	</div>		
	<?php } ?>
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
</body>
</html>