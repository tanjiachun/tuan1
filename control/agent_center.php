<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_centerControl extends BaseAgentControl {
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
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_center'));
    }
    public function agent_reviseOp(){
        $agent_id=empty($_POST['agent_id'])? 0 : intval($_POST['agent_id']);
        $agent_name = empty($_POST['agent_name']) ? '' : $_POST['agent_name'];
        $owner_name = empty($_POST['owner_name']) ? '' : $_POST['owner_name'];
        $agent_phone = empty($_POST['agent_phone']) ? '' : $_POST['agent_phone'];
        $agent_address = empty($_POST['agent_address']) ? '' : $_POST['agent_address'];
        $agent_location = empty($_POST['agent_location']) ? '' : $_POST['agent_location'];
        $agent_banner = empty($_POST['agent_banner']) ? '' : $_POST['agent_banner'];
        $agent_logo = empty($_POST['agent_logo']) ? '' : $_POST['agent_logo'];
        $agent_code_image = empty($_POST['agent_code_image']) ? '' : $_POST['agent_code_image'];
        $agent_person_image = empty($_POST['agent_person_image']) ? '' : $_POST['agent_person_image'];
        $agent_person_code_image = empty($_POST['agent_person_code_image']) ? '' : $_POST['agent_person_code_image'];
        $agent_sign_image = empty($_POST['agent_sign_image']) ? '' : $_POST['agent_sign_image'];
        $agent_qa_image = empty($_POST['agent_qa_image']) ? array() : $_POST['agent_qa_image'];
        $agent_service_image = empty($_POST['agent_service_image']) ? array() : $_POST['agent_service_image'];
        $agent_summary = empty($_POST['agent_summary']) ? '' : $_POST['agent_summary'];
        $agent_content = empty($_POST['agent_content']) ? '' : $_POST['agent_content'];
        $data=array(
            'agent_id'=>$agent_id,
            'agent_name'=>$agent_name,
            'owner_name'=>$owner_name,
            'agent_phone'=>$agent_phone,
            'agent_address'=>$agent_address,
            'agent_location'=>$agent_location,
            'agent_banner'=>$agent_banner,
            'agent_logo'=>$agent_logo,
            'agent_code_image'=>$agent_code_image,
            'agent_person_image'=>$agent_person_image,
            'agent_person_code_image'=>$agent_person_code_image,
            'agent_sign_image'=>$agent_sign_image,
            'agent_qa_image'=>empty($agent_qa_image) ? '' : serialize($agent_qa_image),
            'agent_service_image'=>empty($agent_service_image) ? '' : serialize($agent_service_image),
            'agent_summary'=>$agent_summary,
            'agent_content'=>$agent_content,
            'revise_state'=>0,
            'revise_time'=>time()
        );
        $revise=DB::fetch_first("SELECT * FROM ".DB::table('agent_revise')." WHERE agent_id='$this->agent_id' AND revise_state=0");
        if(!empty($revise)){
            DB::update('agent_revise', $data,array('revise_id'=>$revise['revise_id']));
            DB::query("UPDATE ".DB::table('agent')." SET revise_state=0 WHERE agent_id='$agent_id'");
            exit(json_encode(array('done'=>'true')));
        }else{
            $revise_id=DB::insert('agent_revise', $data,1);
            DB::query("UPDATE ".DB::table('agent')." SET revise_state=0 WHERE agent_id='$agent_id'");
            if(!empty($revise_id)){
                exit(json_encode(array('done'=>'true')));
            }else{
                exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
            }
        }

    }
}

?>