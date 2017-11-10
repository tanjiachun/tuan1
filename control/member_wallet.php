<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_walletControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }


        $curmodule = 'home';
        $bodyclass = '';
        include(template('member_wallet'));
    }
}

?>