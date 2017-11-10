<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_collectControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }

        $mpurl = "index.php?act=member_collect";
        $wheresql = " WHERE member_id='$this->member_id'";
        $state = in_array($_GET['state'], array('all','discount','lose')) ? $_GET['state'] : 'all';
        if($state == 'all') {
            $mpurl .= '&state=all';
            $wheresql .= "";
        } elseif($state == 'discount') {
            $mpurl .= '&state=discount';
            $wheresql .= "";
        }elseif($state == 'lose') {
            $mpurl .= '&state=lose';
            $wheresql .= " AND (nurse_state=2 OR nurse_state=4)";
        }
        $all_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_favourite')." WHERE member_id='$this->member_id' AND favourite_type='favourite' AND show_state=0");
        $lose_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_favourite')." WHERE member_id='$this->member_id' AND favourite_type='favourite' AND show_state=0 AND (nurse_state=2 OR nurse_state=4)");
        $query = DB::query("SELECT * FROM ".DB::table('member_favourite').$wheresql." AND favourite_type='favourite' ORDER BY agent_id DESC");
        while($value = DB::fetch($query)) {
            $agent_ids[]=$value['agent_id'];
            $nurse_ids[]=$value['nurse_id'];
            $favourite_list[]=$value;
        }
        if(!empty($agent_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
            while($value = DB::fetch($query)) {
                $agent_list[$value['agent_id']] = $value;
            }
        }
        if(!empty($nurse_ids)) {
            $nurse_field='nurse.*,member.member_token,member.yx_accid';
            $query = DB::query("SELECT $nurse_field FROM ".DB::table('nurse')." as nurse LEFT JOIN ".DB::table('member')." as member ON nurse.member_id=member.member_id WHERE nurse.nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $value['car_price_list']=empty($value['car_price_list']) ? array(): unserialize($value['car_price_list']);
                $nurse_list[$value['nurse_id']] = $value;
            }
        }

        $curmodule = 'home';
        $bodyclass = '';
        include(template('member_collect'));
    }

    public function delOp(){
        $member_id=empty($_POST['member_id']) ? 0 : $_POST['member_id'];
        $del_id=empty($_POST['del_id']) ? 0 : $_POST['del_id'];
        $del_id = explode(',', $del_id);
        DB::query("DELETE FROM ".DB::table('member_favourite')." WHERE member_id='$member_id' AND collect_id in ('".implode("','", $del_id)."')");
        exit(json_encode(array('done'=>'true')));
    }

    public function del_loseOp(){
        $member_id=empty($_POST['member_id']) ? 0 : $_POST['member_id'];
        DB::query("DELETE FROM ".DB::table('member_favourite')." WHERE member_id='$member_id' AND (nurse_state=2 OR nurse_state=4)");
        exit(json_encode(array('done'=>'true')));
    }
}

?>