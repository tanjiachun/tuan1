<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_nurse_auditControl extends BaseAgentControl {
    public function indexOp(){
        if(empty($this->agent_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$this->agent_id'");
        $agent['agent_qa_image'] = empty($agent['agent_qa_image'] ) ? array() : unserialize($agent['agent_qa_image'] );
        $agent['agent_service_image'] = empty($agent['agent_service_image'] ) ? array() : unserialize($agent['agent_service_image'] );
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$this->agent_id' AND nurse_state=1");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND refund_amount=0");
        $question_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='$this->agent_id' AND answer_content=''");
        $query = DB::query("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='$this->agent_id' AND nurse_state=1 ORDER BY staff_time DESC");
        while($value = DB::fetch($query)) {
            $nurse_audit_list[] = $value;
        }
        $query = DB::query("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='$this->agent_id' ORDER BY staff_time DESC");
        while($value = DB::fetch($query)) {
            $nurse_recruit_list[] = $value;
        }
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_nurse_audit'));
    }

    public function agreeOp(){
        $nurse_id=empty($_GET[' ']) ? 0 : intval($_GET['nurse_id']);
        if(empty($nurse_id)){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员不存在')));
        }
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(!empty($nurse['agent_id'])){
            exit(json_encode(array('done'=>'false','msg'=>'该家政人员已加入机构')));
        }
        DB::query("UPDATE ".DB::table('staff_audit')." SET agent_audit_state=1 WHERE agent_id='$this->agent_id' AND nurse_id='$nurse_id'");
        DB::query("UPDATE ".DB::table('nurse')." SET agent_id='$this->agent_id' WHERE nurse_id='$nurse_id'");
        exit(json_encode(array('done'=>'true')));
    }

    public function rejectOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        if(empty($nurse_id)){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员不存在')));
        }
        DB::query("UPDATE ".DB::table('staff_audit')." SET agent_audit_state=2 WHERE agent_id='$this->agent_id' AND nurse_id='$nurse_id'");
        exit(json_encode(array('done'=>'true')));
    }

    public function staff_recruitOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        if(empty($nurse_id)){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员不存在')));
        }
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员不存在')));
        }
        if(!empty($nurse['agent_id'])){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员已有机构')));
        }
        $audit = DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='$this->agent_id' AND nurse_id='$nurse_id' AND nurse_audit_state=0");
        if(!empty($audit)){
            exit(json_encode(array('done'=>'false','msg'=>'请勿重复邀请')));
        }
        $data=array(
            'agent_id'=>$this->agent_id,
            'nurse_id'=>$nurse_id,
            'agent_state'=>1,
            'nurse_state'=>0,
            'invitation_count'=>1,
            'staff_time'=>time()

        );
        $staff_id=DB::insert('staff_audit', $data, 1);
        if(!empty($staff_id)){
            exit(json_encode(array('done'=>'true')));
        }else{
            exit(json_encode(array('done'=>'false','msg'=>'网络连接错误，请稍后重试')));
        }
    }

    public function re_invitationOp(){
        $staff_id=empty($_GET['staff_id']) ? 0 : intval($_GET['staff_id']);
        $staff=DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE staff_id='$staff_id'");
        if(empty($staff)){
            exit(json_encode(array('done'=>'false','msg'=>'网络不稳定，请稍后重试')));
        }
        $nurse_id=$staff['nurse_id'];
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员不存在')));
        }
        if(!empty($nurse['agent_id'])){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员已有机构')));
        }
        $audit = DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='$this->agent_id' AND nurse_id='$nurse_id' AND nurse_audit_state=0");
        if(!empty($audit)){
            exit(json_encode(array('done'=>'false','msg'=>'请勿重复邀请')));
        }
        if($staff['invitation_count']>=6){
            exit(json_encode(array('done'=>'false','msg'=>'邀请数量过多')));
        }
        DB::query("UPDATE ".DB::table('staff_audit')." SET nurse_audit_state=0 WHERE staff_id='$staff_id'");
        DB::query("UPDATE ".DB::table('staff_audit')." SET invitation_count=invitation_count+1 WHERE staff_id='$staff_id'");
        exit(json_encode(array('done'=>'true')));
    }
}

?>