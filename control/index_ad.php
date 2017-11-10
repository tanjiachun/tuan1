<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class index_adControl extends BaseHomeControl {
    public function indexOp() {



        $curmodule = 'member';
        $bodyclass = '';
        include(template('index_ad'));

    }

}

?>