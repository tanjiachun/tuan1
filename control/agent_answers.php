<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_answersControl extends BaseHomeControl {
    public function indexOp() {
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)) {
            $this->showmessage('家政机构不存在', 'index.php?act=index', 'error');
        }
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$agent['member_id']."'");
        $agent_grade = DB::fetch_first("SELECT * FROM ".DB::table('agent_grade')." WHERE agent_score<=".$agent['agent_score']." ORDER BY agent_score DESC");
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' AND nurse_state=1");
        if(empty($agent)) {
            $this->showmessage('家政机构不存在', 'index.php?act=index', 'error');
        }
        $page = 1;$perpage = 10;$start = ($page-1)*$perpage;
        $query = DB::query("SELECT * FROM ".DB::table('agent_question')." WHERE agent_id=".$agent['agent_id']." AND answer_content!='' ORDER BY question_time DESC LIMIT $start, $perpage");
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
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='$agent_id' AND answer_content!=''");
        $multi = multi($count, $perpage, $page, '', 'selectquestion');
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_answers'));
    }
    public function get_questionOp(){
        if(empty($this->member_id)){
            exit(json_encode(array('done'=>'empty')));
        }
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            $this->showmessage('家政机构不存在', 'index.php?act=index', 'error');
        }

        $question_content=empty($_GET['question_content']) ? '' : $_GET['question_content'];
        if(empty($question_content)){
            exit(json_encode(array('done'=>'false','msg'=>'问题内容不能为空')));
        }
        $data=array(
            'member_id'=>$this->member_id,
            'agent_id'=>$agent_id,
            'question_content'=>$question_content,
            'question_time'=>time()
        );
        DB::insert('agent_question', $data, 1);
        exit(json_encode(array('done'=>'true','msg'=>'提问成功')));
    }

    public function search_questionOp(){
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        $perpage = 10;$start = ($page-1)*$perpage;
        $sort = !in_array($_GET['sort'], array('time', 'focus')) ? 'time' : $_GET['sort'];
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $wheresql=" WHERE agent_id='$agent_id' AND answer_content!=''";
        if($sort=='focus'){
            $wheresql .= " ORDER BY focus_count DESC";
        }elseif($sort=='time'){
            $wheresql .= " ORDER BY question_time DESC";
        }
        $count= DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('agent_question').$wheresql." LIMIT $start,$perpage");
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

        $multi = multi($count, $perpage, $page, '', 'selectquestion');
        $question_html='';
        $question_multi_html='';
        foreach($question_list as $key => $value) {
            $question_html.='<div class="answer_details">';
            $question_html.='<div class="question_message">';
            if($member_list[$value['member_id']]['member_image']== '') {
              $question_html.='<img src="templates/images/head.png">';
            }else{
                $question_html.='<img src="'. $member_list[$value['member_id']]['member_image'].'">';
            }
            $question_html.='<b>'.$member_list[$value['member_id']]['member_name'].'&nbsp;&nbsp;&nbsp;&nbsp;提了一个问题</b> <span>'.date('Y-m-d H:i', $value['question_time']).'<img src="templates/images/browse.png" alt=""> '.$value['focus_count'].'</span>';
            $question_html.='</div>';
            $question_html.='<h3><img src="templates/images/question.png" alt=""><a href="javascript:;">'.$value['question_content'].'</a></h3>';
            $question_html.='<div class="answer_content"><img src="templates/images/answer.png" alt="">'.$value['answer_content'].'</div>';
            $question_html.='</div>';
        }
        $question_multi_html.=$multi;
        exit(json_encode(array('done'=>'true','question_html'=>$question_html,'question_multi_html'=>$question_multi_html)));
    }

}

?>