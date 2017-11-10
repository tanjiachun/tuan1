<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_coin_detailsControl extends BaseProfileControl {
    public function indexOp() {
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $mpurl = "index.php?act=member_coin_details";
        $wheresql = " WHERE member_id='$this->member_id'";
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_coin').$wheresql." AND coin_count!=0");
        $query = DB::query("SELECT * FROM ".DB::table('member_coin').$wheresql." AND coin_count!=0 ORDER BY true_time DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            if($value['get_state'] ==0) {
                $value['markclass'] = 'red';
            } else {
                $value['markclass'] = 'green';
            }
            $coin_list[] = $value;
        }
        $multi = multi($count, $perpage, $page, $mpurl);
        $curmodule = 'home';
        $bodyclass = 'gray-bg';
        include(template('member_coin_details'));
    }
}

?>