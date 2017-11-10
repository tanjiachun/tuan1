<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_evaluateControl extends BaseAgentControl {
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
        $level_array = array('good'=>'得到好评 ', 'middle'=>'得到中评', 'bad'=>'得到差评');
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $mpurl = "index.php?act=agent_evaluate";
        $wheresql = " WHERE agent_id='$this->agent_id'";
        $state = in_array($_GET['state'], array('all', 'nurse_reply', 'nurse_comment','comment_middle','comment_bad','nurse_good','nurse_middle','nurse_bad')) ? $_GET['state'] : 'all';
        if($state == 'all') {
            $mpurl .= '&state=all';
            $wheresql .= "";
        } elseif($state == 'nurse_reply') {
            $mpurl .= '&state=nurse_reply';
            $wheresql .= " AND agent_reply_content=''";
        } elseif($state == 'nurse_comment') {
            $mpurl .= '&state=nurse_comment';
            $wheresql .= " AND nurse_comment_content=''";
        } elseif ($state=='comment_middle'){
            $mpurl .= '&state=comment_middle';
            $wheresql .= " AND comment_level='middle'";
        }elseif ($state=='comment_bad'){
            $mpurl .= '&state=comment_bad';
            $wheresql .= " AND comment_level='bad'";
        }elseif ($state=='nurse_good'){
            $mpurl .= '&state=nurse_good';
            $wheresql .= " AND nurse_comment_level='good'";
        }elseif ($state=='nurse_middle'){
            $mpurl .= '&state=nurse_middle';
            $wheresql .= " AND nurse_comment_level='middle'";
        }elseif ($state=='nurse_bad'){
            $mpurl .= '&state=nurse_bad';
            $wheresql .= " AND nurse_comment_level='bad'";
        }

        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql." LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $nurse_ids[] = $value['nurse_id'];
            $book_ids[]=$value['book_id'];
            $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
            $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
            $value['nurse_comment_image'] = empty($value['nurse_comment_image']) ? array() : unserialize($value['nurse_comment_image']);
            $comment_list[] = $value;
        }
        if(!empty($nurse_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        if(!empty($book_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $book_ids)."')");
            while($value=DB::fetch($query)){
                $book_list[$value['book_id']]=$value;
            }
        }
        $multi = multi($count, $perpage, $page, $mpurl);

        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_evaluate'));
    }
    public function agent_replyOp(){
        $comment_id=empty($_POST['comment_id']) ? 0 : intval($_POST['comment_id']);
        $agent_reply_content=empty($_POST['agent_reply_content']) ? '' : $_POST['agent_reply_content'];
        $comment=DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
        if(empty($comment)){
            exit(json_encode(array('msg'=>'订单评价不存在')));
        }
        if(empty($agent_reply_content)){
            exit(json_encode(array('msg'=>'回复内容不能为空')));
        }
        DB::query("UPDATE ".DB::table('nurse_comment')." SET agent_reply_content='$agent_reply_content' WHERE comment_id='$comment_id'");
        exit(json_encode(array('done'=>'true')));
    }

    public function agent_replysOp(){
        $comment_ids = empty($_POST['comment_ids']) ? '' : $_POST['comment_ids'];
        $comment_ids = explode(',', $comment_ids);
        $agent_reply_content=empty($_POST['agent_reply_content']) ? '' : $_POST['agent_reply_content'];
        if(empty($comment_ids)){
            exit(json_encode(array('msg'=>'请至少选择一个订单')));
        }
        if(empty($agent_reply_content)){
            exit(json_encode(array('msg'=>'回复内容不能为空')));
        }
        DB::query("UPDATE ".DB::table('nurse_comment')." SET agent_reply_content='$agent_reply_content' WHERE comment_id in ('".implode("','", $comment_ids)."')");
        exit(json_encode(array('done'=>'true')));
    }

    public function nurse_commentOp(){
        $comment_id=empty($_POST['comment_id']) ? 0 : intval($_POST['comment_id']);
        $comment=DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
        $member_id=$comment['member_id'];
        if(empty($comment)){
            exit(json_encode(array('msg'=>'订单评价不存在')));
        }
        $nurse_comment_level=!in_array($_POST['nurse_comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['nurse_comment_level'];
        $nurse_comment_content = empty($_POST['nurse_comment_content']) ? '' : $_POST['nurse_comment_content'];
        if(empty($nurse_comment_content)){
            exit(json_encode(array('msg'=>'评价内容不能为空')));
        }
        $nurse_comment_image = empty($_POST['nurse_comment_image']) ? array() : $_POST['nurse_comment_image'];
        $data=array(
            'nurse_comment_level'=>$nurse_comment_level,
            'nurse_comment_content'=>$nurse_comment_content,
            'nurse_comment_image' => empty($nurse_comment_image) ? '' : serialize($nurse_comment_image),
            'nurse_comment_state'=>1,
            'nurse_comment_time'=>time()
        );
        DB::update('nurse_comment', $data, array('comment_id'=>$comment_id));
        $book_data=array(
            'nurse_comment_state'=>1,
            'nurse_comment_time'=>time()
        );
        DB::update('nurse_book', $book_data, array('book_id'=>$comment['book_id']));
        $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
        $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
        //雇主被评价积分收益
        $nurse_comment_score=0;
        if($nurse_comment_level=='good'){
            $nurse_comment_score+=10;
        }elseif($nurse_comment_level=='middle'){
            $nurse_comment_score-=2;
        }elseif ($nurse_comment_level=='bad'){
            $nurse_comment_score-=10;
        }
        $member_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('member_score')." WHERE member_id='".$comment['member_id']."' AND get_type='nurse_comment' AND get_time=$now_date");
        if($member_get_score+$nurse_comment_score>=200){
            $nurse_comment_score=200-$member_get_score;
        }
        $member_score_data=array(
            'member_id'=>$comment['member_id'],
            'book_id'=>$comment['book_id'],
            'score_count'=>$nurse_comment_score,
            'get_type'=>'nurse_comment',
            'true_time'=>time(),
            'get_time'=>$now_date
        );
        DB::insert('member_score', $member_score_data);
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
        $member_score=$member['member_score']+$nurse_comment_score;
        $grade = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member_score." ORDER BY card_predeposit DESC");
        $grade_id=$grade['card_id'];
        DB::query("UPDATE ".DB::table('member')." SET member_score=member_score+$nurse_comment_score,grade_id=$grade_id WHERE member_id='".$comment['member_id']."'");
        //雇主团豆豆收益
        $member_add_coin=20;
        $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$comment['member_id']."' AND get_type='nurse_comment' AND get_time=$now_date");
        if($member_add_coin+$member_get_coin>=80){
            $member_add_coin=80-$member_get_coin;
        }
        $member_coin_data=array(
            'member_id'=>$comment['member_id'],
            'book_id'=>$comment['book_id'],
            'coin_count'=>$member_add_coin,
            'get_type'=>'nurse_comment',
            'true_time'=>time(),
            'get_time'=>$now_date
        );
        DB::insert('member_coin', $member_coin_data);
        DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$comment['member_id']."'");
        //雇主升级团豆豆收益
        if(intval($grade_id)>intval($member['grade_id'])){
            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+188 WHERE member_id='".$comment['member_id']."'");
            $member_coin_level_data=array(
                'member_id'=>$comment['member_id'],
                'book_id'=>$comment['book_id'],
                'coin_count'=>188,
                'get_type'=>'level_up',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_coin', $member_coin_level_data);
        }
        exit(json_encode(array('done'=>'true')));
    }
    public function nurse_comment_resumeOp(){
        if(submitcheck()){
            $comment_id=empty($_POST['comment_id']) ? 0 : intval($_POST['comment_id']);
            $nurse_comment_level = !in_array($_POST['nurse_comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['nurse_comment_level'];
            $nurse_comment_content = empty($_POST['nurse_comment_content']) ? '' : $_POST['nurse_comment_content'];
            $nurse_comment_image = empty($_POST['nurse_comment_image']) ? array() : $_POST['nurse_comment_image'];
            $comment=DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
            if(empty($nurse_comment_level)) {
                exit(json_encode(array('msg'=>'请选择满意度评分')));
            }
            if(empty($nurse_comment_content)) {
                exit(json_encode(array('msg'=>'请至少写点你的服务感受')));
            }
            if($comment['nurse_comment_level']=='good'){
                exit(json_encode(array('msg'=>'好评无法修改')));
            }
            if($comment['nurse_comment_level']==$nurse_comment_level){
                exit(json_encode(array('msg'=>'改变评价等级才可以修改')));
            }
            $data = array(
                'nurse_comment_level' => $nurse_comment_level,
                'nurse_comment_image' => empty($nurse_comment_image) ? '' : serialize($nurse_comment_image),
                'nurse_comment_content' => $nurse_comment_content,
                'nurse_revise_state'=>1,
                'nurse_revise_time' => time()
            );
            DB::update('nurse_comment', $data, array('comment_id'=>$comment_id));
            //积分和团豆豆获得
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            //雇主被评价积分收益
            $member_add_score=0;
            if($comment['nurse_comment_level']=='bad' && $nurse_comment_level=='middle'){
                $member_add_score+=8;
            }elseif($comment['nurse_comment_level']=='bad' && $nurse_comment_level=='good'){
                $member_add_score+=20;
            }elseif ($comment['nurse_comment_level']=='middle' && $nurse_comment_level=='good'){
                $member_add_score+=12;
            }
            $member_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('member_score')." WHERE member_id='".$comment['member_id']."' AND get_type='nurse_comment' AND get_time=$now_date");
            if($member_get_score+$member_add_score>=200){
                $member_add_score=200-$member_get_score;
            }
            $member_score_data=array(
                'member_id'=>$comment['member_id'],
                'book_id'=>$comment['book_id'],
                'score_count'=>$member_add_score,
                'get_type'=>'nurse_comment',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_score', $member_score_data);
            $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$comment['member_id']."'");
            $member_score=$member['member_score']+$member_add_score;
            $grade = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member_score." ORDER BY card_predeposit DESC");
            $grade_id=$grade['card_id'];
            DB::query("UPDATE ".DB::table('member')." SET member_score=member_score+$member_add_score,grade_id=$grade_id WHERE member_id='".$comment['member_id']."'");
            //等级提升团豆豆收益
            if(intval($grade_id)>intval($member['grade_id'])){
                if(intval($grade_id)>intval($member['large_grade_id'])){
                    DB::query("UPDATE ".DB::table('member')." SET large_grade_id=$grade_id WHERE member_id='".$comment['member_id']."'");
                    DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+188 WHERE member_id='".$comment['member_id']."'");
                    $member_coin_level_data=array(
                        'member_id'=>$comment['member_id'],
                        'book_id'=>$comment['book_id'],
                        'coin_count'=>188,
                        'get_type'=>'level_up',
                        'true_time'=>time(),
                        'get_time'=>$now_date
                    );
                    DB::insert('member_coin', $member_coin_level_data);
                }
            }
            exit(json_encode(array('done'=>'true')));
        }else{
            $comment_id=empty($_GET['comment_id']) ? 0 : intval($_GET['comment_id']);
            $comment = DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
            $comment['nurse_comment_image']=empty($comment['nurse_comment_image']) ? '' : unserialize($comment['nurse_comment_image']);
            $member_id=$comment['member_id'];
            $book_id=$comment['book_id'];
            $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
            $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
            $curmodule = 'home';
            $bodyclass = '';
            include(template('nurse_comment_resume'));
        }
    }
}

?>