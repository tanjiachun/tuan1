<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_questionControl extends BaseAgentControl {
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
//        $page = 1;$perpage = 4;$start = ($page-1)*$perpage;
        $query = DB::query("SELECT * FROM ".DB::table('agent_question')." WHERE agent_id=".$agent['agent_id']." AND answer_content='' ORDER BY question_time DESC");
        while($value = DB::fetch($query)) {
            $member_ids[]=$value['member_id'];
            $question_list[] = $value;
        }
        if(!empty($member_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $value['member_image']=$value['member_avatar'];
                $value['member_name']=preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
                $member_list[$value['member_id']]=$value;

            }
        }
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='$this->agent_id' AND answer_content=''");
//        $multi = multi($count, $perpage, $page, '', 'selectquestion');
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_question'));
    }

    public function answerOp(){
        $question_id=empty($_POST['question_id']) ? 0 : intval($_POST['question_id']);
        $question=DB::fetch_first("SELECT * FROM ".DB::table('agent_question')." WHERE question_id='$question_id'");
        if(empty($question)){
            exit(json_encode(array('done'=>'false','msg'=>'问题不存在')));
        }
        $answer_content=empty($_POST['answer_content']) ? '' : $_POST['answer_content'];
        if(empty($answer_content)){
            exit(json_encode(array('done'=>'false','msg'=>'请认证填写回答内容')));
        }
        DB::query("UPDATE ".DB::table('agent_question')." SET answer_content='$answer_content' WHERE question_id='".$question_id."'");
        exit(json_encode(array('done'=>'true')));
    }
}

?>