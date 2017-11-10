<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class logoutControl extends BaseAdminControl {
	public function indexOp() {
		dsetcookie('malladminauth', '', -3600);
		@header("Location: admin.php?act=login");
		exit;
	}
}
?>