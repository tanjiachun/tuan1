<?php include(template('common_header'));?>
		<div class="conwp clearfix">
			<h1 class="top-logo">
				<a href="index.php"><img src="templates/images/logo.png"></a>
			</h1>
			<div class="nav-box">
				<div class="nav-list">
					<ul class="clearfix">
						<li><a href="index.php">首页</a></li>
						<li><a href="javascript:;">下载APP</a></li>
						<li<?php echo $article['article_id'] == 2 ? ' class="active"' : '';?>><a href="index.php?act=article&article_id=2">加盟合作</a></li>
						<li<?php echo $article['article_id'] == 1 ? ' class="active"' : '';?>><a href="index.php?act=article&article_id=1">关于我们</a></li>
					 </ul>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="conwp">
			<div class="layout-main clearfix">
				<div class="main-left nomal-list">
					<ul>
						<?php foreach($article_list as $key => $value) { ?>
						<li<?php echo $article['article_id'] == $value['article_id'] ? ' class="active"' : '';?>><a href="index.php?act=article&article_id=<?php echo $value['article_id'];?>"><?php echo $value['article_title'];?></a></li>
						<?php } ?>
					</ul>
				</div>
				<div class="main-right">
					<div class="article-box">
						<div class="acticle-box-title">
							<strong><?php echo $article['article_title'];?></strong>
							<span class="pull-right">
								<b>当前位置：</b><a href="index.php">首页</a><em>></em><?php echo $article['article_title'];?>
							</span>
						</div>
						<div class="acticle-con">
							<?php echo $article['article_content'];?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include(template('common_footer'));?>