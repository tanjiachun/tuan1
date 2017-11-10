<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
header('Content-type: application/json');
class book_refundControl extends BaseMobileControl {
    public function indexOp(){

    }
    public function nurseOp(){
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'您还不是家政人员')));
        }
        $page = empty($_POST['page']) ? 0 : intval($_POST['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE nurse_id='".$nurse['nurse_id']."'";
        $query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." AND refund_state=1 AND refund_type='nurse' LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $member_ids[]=$value['member_id'];
            $refund_list[] = $value;
        }
        if(!empty($member_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $member_list[$value['member_id']] = $value;
            }
        }
        foreach($refund_list as $key => $value) {
            $refund_list[$key]['member_nickname'] = $member_list[$value['member_id']]['member_nickname'];
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$refund_list)));
    }

    public function memberOp(){
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$this->member_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'您还不是家政人员')));
        }
        $page = empty($_POST['page']) ? 0 : intval($_POST['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE nurse_id='".$nurse['nurse_id']."'";
        $query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." AND refund_state=1 AND refund_type='member' LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $member_ids[]=$value['member_id'];
            $refund_list[] = $value;
        }
        if(!empty($member_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $member_list[$value['member_id']] = $value;
            }
        }
        foreach($refund_list as $key => $value) {
            $refund_list[$key]['member_nickname'] = $member_list[$value['member_id']]['member_nickname'];
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$refund_list)));
    }
}

?>