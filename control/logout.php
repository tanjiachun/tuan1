<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class logoutControl extends BaseHomeControl {
	public function indexOp() {
		dsetcookie('mallauth', '', -3600);
		@header("Location: index.php?act=login");
		exit;
	}
}

?>