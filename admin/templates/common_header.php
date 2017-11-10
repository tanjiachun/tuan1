<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>管理中心</title>
	<link href="admin/templates/css/common.css" type="text/css" rel="stylesheet">
    <link href="admin/templates/css/dialog.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="admin/templates/css/new.css" type="text/css">
    <script type="text/javascript">
		var SITEURL = '<?php echo SiteUrl;?>';
	</script>
    <script type="text/javascript" src="admin/templates/js/jquery-1-9-0.js"></script>
    <script type="text/javascript" src="admin/templates/js/common.js"></script>
    <script type="text/javascript" src="admin/templates/js/admin.js"></script>
</head>
<body>
	<div id="append_parent"></div>
    <div id="ajaxwaitid"></div>
	<div class="header">
		<div class="header_container">
			<a class="logo" href="javascript:void(0);"><img src="admin/templates/images/logo.jpg"></a>
			<nav class="header_nav">
                <ul>
                    <li<?php if(in_array($_GET['act'], array('index', 'recommend', 'article', 'link', 'related', 'app', 'admin', 'log','type'))) {?> class="active"<?php } ?>><a href="admin.php">全局</a></li>
                    <li<?php if(in_array($_GET['act'], array('nurse', 'grade', 'tag', 'service','nurse_revise'))) {?> class="active"<?php } ?>><a href="admin.php?act=nurse">阿姨</a></li>
                    <li<?php if(in_array($_GET['act'], array('goods', 'gclass'))) {?> class="active"<?php } ?>><a href="admin.php?act=goods">商品</a></li>
					<li<?php if(in_array($_GET['act'], array('store', 'sclass'))) {?> class="active"<?php } ?>><a href="admin.php?act=store">店铺</a></li>
                    <li<?php if(in_array($_GET['act'], array('agent', 'pension','agent_revise'))) {?> class="active"<?php } ?>><a href="admin.php?act=agent">机构</a></li>
					<li<?php if(in_array($_GET['act'], array('card', 'oldage', 'red', 'package'))) {?> class="active"<?php } ?>><a href="admin.php?act=card">运营</a></li>
                    <li<?php if(in_array($_GET['act'], array('pnurse', 'pstore', 'ppension'))) {?> class="active"<?php } ?>><a href="admin.php?act=pnurse">财务</a></li>
                </ul>
            </nav>
            <section class="iconBox">
                <a href="admin.php?act=logout" class="header_icon">
                    <i class="no3"></i>
                </a>
            </section>
		</div>
	</div>