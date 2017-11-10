<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_commentControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 2;
        $start = ($page-1)*$perpage;
        $mpurl = "index.php?act=member_comment";
        $wheresql = " WHERE member_id='$this->member_id'";
        $state = in_array($_GET['state'], array('all','good','middle','bad','back')) ? $_GET['state'] : 'all';
        if($state == 'all') {
            $mpurl .= '&state=all';
            $wheresql .= "";
        } elseif($state == 'good') {
            $mpurl .= '&state=good';
            $wheresql .= " AND comment_level='good'";
        }elseif($state == 'middle') {
            $mpurl .= '&state=middle';
            $wheresql .= " AND comment_level='middle'";
        }elseif($state == 'bad') {
            $mpurl .= '&state=bad';
            $wheresql .= " AND comment_level='bad'";
        }elseif($state == 'back') {
            $mpurl .= '&state=back';
            $wheresql .= " AND agent_reply_content!=''";
        }
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql." ORDER BY comment_time DESC LIMIT $start, $perpage");
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
        include(template('member_comment'));
    }

    public function comment_resumeOp(){
        if(submitcheck()){
            $comment_id=empty($_POST['comment_id']) ? 0 : intval($_POST['comment_id']);
            $star_array = array('1', '2', '3', '4', '5');
            $comment_level = !in_array($_POST['comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['comment_level'];
            $comment_honest_star = !in_array($_POST['comment_honest_star'], $star_array) ? 1 : intval($_POST['comment_honest_star']);
            $comment_love_star = !in_array($_POST['comment_love_star'], $star_array) ? 1 : intval($_POST['comment_love_star']);
            $comment_content = empty($_POST['comment_content']) ? '' : $_POST['comment_content'];
            $comment_image = empty($_POST['comment_image']) ? array() : $_POST['comment_image'];
            $comment=DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
            $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='".$comment['book_id']."'");
            if(empty($comment_level)) {
                exit(json_encode(array('msg'=>'请选择满意度评分')));
            }
            if(empty($comment_content)) {
                exit(json_encode(array('msg'=>'请至少写点你的服务感受')));
            }
            if($comment['comment_level']=='good'){
                exit(json_encode(array('msg'=>'好评无法修改')));
            }
            if($comment['comment_level']==$comment_level){
                exit(json_encode(array('msg'=>'改变评价等级才可以修改')));
            }
            if($comment_honest_star<intval($comment['comment_honest_star'])){
                exit(json_encode(array('msg'=>'诚实守信等级不能减少')));
            }
            if($comment_love_star<intval($comment['comment_love_star'])){
                exit(json_encode(array('msg'=>'爱岗敬业等级不能减少')));
            }
            $data = array(
                'comment_level' => $comment_level,
                'comment_honest_star' => $comment_honest_star,
                'comment_love_star' => $comment_love_star,
                'comment_image' => empty($comment_image) ? '' : serialize($comment_image),
                'comment_content' => $comment_content,
                'comment_revise_state'=>1,
                'comment_revise_time' => time()
            );
            DB::update('nurse_comment', $data, array('comment_id'=>$comment_id));
            //家政人员积分收益
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
            $nurse_add_score=$comment_honest_star+$comment_love_star-intval($comment['comment_honest_star'])-intval($comment['comment_love_star']);
            if($comment['comment_level']=='bad' && $comment_level=='middle'){
                $nurse_add_score+=8;
            }elseif($comment['comment_level']=='bad' && $comment_level=='good'){
                $nurse_add_score+=20;
            }elseif ($comment['comment_level']=='middle' && $comment_level=='good'){
                $nurse_add_score+=12;
            }
            $comment_add_score=$nurse_add_score*intval($book['work_duration_days']);
            $nurse_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('nurse_score')." WHERE nurse_id='".$book['nurse_id']."' AND get_type='member_comment' AND get_time=$now_date");
            if($nurse_get_score+$comment_add_score>=1600){
                $comment_add_score=1600-$nurse_get_score;
            }
            $nurse_score = $nurse['nurse_score']+$comment_add_score;
            $query = DB::query("SELECT * FROM ".DB::table('nurse_grade')." ORDER BY nurse_score DESC");
            while($value = DB::fetch($query)) {
                if($value['nurse_score'] < $nurse_score) {
                    $grade_id = $value['grade_id'];
                    break;
                }
            }
            $nurse_score_data=array(
                'nurse_id'=>$book['nurse_id'],
                'book_id'=>$book['book_id'],
                'score_count'=>$comment_add_score,
                'get_type'=>'member_comment',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('nurse_score', $nurse_score_data);
            DB::query("UPDATE ".DB::table('nurse')." SET grade_id=$grade_id, nurse_score=nurse_score+$comment_add_score WHERE nurse_id='".$book['nurse_id']."'");
            //机构积分收益
            if(!empty($book['agent_id'])){
                $agent_nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='".$book['agent_id']."'");
                $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$book['agent_id']."'");
                $agent_add_score=ceil($comment_add_score/intval($agent_nurse_count));
                if($agent_add_score<1){
                    $agent_add_score=1;
                }
                $agent_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('agent_score')." WHERE agent_id='".$book['agent_id']."' AND get_type='member_comment' AND get_time=$now_date");
                if($agent_get_score+$agent_add_score>=1000){
                    $agent_add_score=1000-$agent_get_score;
                }
                $agent_score=$agent['agent_score']+$agent_add_score;
                $query = DB::query("SELECT * FROM ".DB::table('agent_grade')." ORDER BY agent_score DESC");
                while($value = DB::fetch($query)) {
                    if($value['agent_score'] < $agent_score) {
                        $agent_grade_id = $value['grade_id'];
                        break;
                    }
                }
                $agent_score_data=array(
                    'agent_id'=>$book['agent_id'],
                    'book_id'=>$book['book_id'],
                    'score_count'=>$agent_add_score,
                    'get_type'=>'member_comment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('agent_score', $agent_score_data);
                DB::query("UPDATE ".DB::table('agent')." SET grade_id=$agent_grade_id, agent_score=agent_score+$agent_add_score WHERE agent_id='".$book['agent_id']."'");
            }
            exit(json_encode(array('done'=>'true')));
        }else{
            $comment_id=empty($_GET['comment_id']) ? 0 : intval($_GET['comment_id']);
            $comment = DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
            $comment['comment_image']=empty($comment['comment_image']) ? '' : unserialize($comment['comment_image']);
            $nurse_id=$comment['nurse_id'];
            $book_id=$comment['book_id'];
            $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
            $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
            $curmodule = 'home';
            $bodyclass = '';
            include(template('member_comment_resume'));
        }
    }

}

?>