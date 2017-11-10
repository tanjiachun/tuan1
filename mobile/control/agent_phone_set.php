<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_phone_setControl extends BaseMobileControl {
    public function indexOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : $_GET['agent_id'];
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在')));
        }
        $agent['agent_qa_image'] = empty($agent['agent_qa_image'] ) ? array() : unserialize($agent['agent_qa_image'] );
        $agent['agent_other_phone'] = empty($agent['agent_other_phone'] ) ? array() : unserialize($agent['agent_other_phone'] );
        $agent['agent_other_phone_choose'] = empty($agent['agent_other_phone_choose'] ) ? array() : unserialize($agent['agent_other_phone_choose'] );
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' AND nurse_state=1");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$agent_id' AND refund_amount=0");
        $question_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='$agent_id' AND answer_content=''");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
    public function phone_setOp(){
        $agent_id=empty($_POST['agent_id'])? 0 : intval($_POST['agent_id']);
        $agent_other_phone = empty($_POST['agent_other_phone']) ? array() : $_POST['agent_other_phone'];
        $agent_phone = empty($_POST['agent_phone']) ? '' : $_POST['agent_phone'];
        $agent_other_phone_choose = empty($_POST['agent_other_phone_choose']) ? array() : $_POST['agent_other_phone_choose'];
        $data=array(
            'agent_other_phone'=>empty($agent_other_phone) ? '' : serialize($agent_other_phone),
            'agent_phone'=>$agent_phone,
            'agent_other_phone_choose'=>empty($agent_other_phone_choose) ? '' : serialize($agent_other_phone_choose),
        );
        DB::update('agent', $data,array('agent_id'=>$agent_id));
        exit(json_encode(array('done'=>'true')));
    }
}

?>