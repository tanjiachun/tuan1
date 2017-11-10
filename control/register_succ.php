<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class register_succControl extends BaseHomeControl {
    public function indexOp() {
            if(empty($this->member_id)){
                $this->showmessage('您还没注册', 'index.php?act=login', 'info');

            }
            $curmodule = 'home';
            $bodyclass = '';
            include(template('register_succ'));

    }


}

?>