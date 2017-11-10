<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
	<title>团家政</title>
	<meta name='apple-itunes-app' content='app-id=你的APP-ID'>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no"/>
	<meta name="keywords"  content="">
	<meta name="description" content="">
	<link href="mobile/templates/css/common.css" type="text/css" rel="stylesheet">
	<style>
		.app-3lolS {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			min-height: 100%;
		}
	</style>
</head>
<body>
	<img src="mobile/templates/images/bg.4256378.jpg" class="app-3lolS">
	<div class="container">
		<div class="info-box">
			<div class="info-head">
				<img src="<?php echo empty($nurse) ? $setting['app_share_image'] : $nurse['nurse_image'];?>">
			</div>
			<div class="info-title"><?php echo empty($nurse) ? $setting['app_share_title'] : $nurse['nurse_name'];?></div>
			<div class="info-desc"><?php echo empty($nurse) ? nl2br($setting['app_share_desc']) : nl2br($nurse['nurse_content']);?></div>
		</div>
	</div>
	<div class="fixed-bar">
		<ul>
			<li style="width:100%;"><a href="javascript:;" class="btn btn-primary" onClick="openapp();">下载客户端</a></li>
		</ul>
	</div>
	<script type="text/javascript">
		function openapp() {
			if(navigator.userAgent.match(/android/i)) {
				// 通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
				// 否则打开a标签的href链接
				var isInstalled;
				
				//下面是安卓端APP接口调用的地址，自己根据情况去修改
				var ifrSrc = 'nursemall://startApp';
				
				var ifr = document.createElement('iframe');
				ifr.src = ifrSrc;		
				ifr.style.display = 'none';
				ifr.onload = function() {
					isInstalled = true;
					document.getElementById('openApp0').click();
				};
				ifr.onerror = function() {
					isInstalled = false;
				}
				document.body.appendChild(ifr);
				setTimeout(function() {
					document.body.removeChild(ifr);
				}, 1000);
			}
			if(navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
				var ver = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);  
     			ver = parseInt(ver[1], 10);  
    			if(ver >= 9) {  
					window.location = 'wsl://com.md.mall';
				}  else {
					var isInstalled;
					
					//下面是IOS调用的地址，自己根据情况去修改
					var ifrSrc = 'wsl://com.md.mall';
					
					var ifr = document.createElement('iframe');
					ifr.src = ifrSrc;
					ifr.style.display = 'none';
					ifr.onload = function() {
						isInstalled = true;
						document.getElementById('openApp1').click();
					};
					ifr.onerror = function() {
						isInstalled = false;
					}
					document.body.appendChild(ifr);
					setTimeout(function() {
						document.body.removeChild(ifr);
					}, 1000);
				}	
			}
		}
	</script>
</body>
</html>