<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class book_commentControl extends BaseMobileControl {
    public function indexOp(){
        $this->check_authority();
        $book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
        $star_array = array('1', '2', '3', '4', '5');
        $comment_level = !in_array($_POST['comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['comment_level'];
        $comment_honest_star = !in_array($_POST['comment_honest_star'], $star_array) ? 1 : intval($_POST['comment_honest_star']);
        $comment_love_star = !in_array($_POST['comment_love_star'], $star_array) ? 1 : intval($_POST['comment_love_star']);
        $comment_content = empty($_POST['comment_content']) ? '' : $_POST['comment_content'];
        $comment_image = empty($_POST['comment_image']) ? array() : $_POST['comment_image'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
        if(empty($book) || $book['member_id'] != $this->member_id) {
            exit(json_encode(array('code'=>1,'msg'=>'预约单不存在')));
        }
        if($book['book_state'] != '30') {
            exit(json_encode(array('code'=>1,'msg'=>'预约单不能评价')));
        }
        if(!empty($book['comment_state'])) {
            exit(json_encode(array('code'=>1,'msg'=>'您已经评价过了')));
        }
        if(empty($comment_level)) {
            exit(json_encode(array('code'=>1,'msg'=>'请选择满意度评分')));
        }
        if(empty($comment_content)) {
            exit(json_encode(array('code'=>1,'msg'=>'请至少写点你的服务感受')));
        }
        $comment_score = $comment_honest_star+$comment_love_star;
        if($comment_level=='good'){
            $comment_score+=10;
        }elseif ($comment_level=='middle'){
            $comment_score-=2;
        }elseif ($comment_level=='bad'){
            $comment_score-=10;
        }
        $comment_add_score=$comment_score*intval($book['work_duration']*30+$book['work_duration_days']);
        $comment_image = explode(',',$comment_image);
        $data = array(
            'nurse_id' => $book['nurse_id'],
            'member_id' => $book['member_id'],
            'book_id' => $book['book_id'],
            'comment_level' => $comment_level,
            'comment_honest_star' => $comment_honest_star,
            'comment_love_star' => $comment_love_star,
            'comment_image' => empty($comment_image) ? '' : serialize($comment_image),
            'comment_content' => $comment_content,
            'comment_time' => time(),
        );
        $comment_id = DB::insert('nurse_comment', $data , 1);
        if(!empty($comment_id)) {
            DB::update('nurse_book', array('comment_state'=>1, 'comment_time'=>time()), array('book_id'=>$book['book_id']));
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
            //家政人员积分收益
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
                'book_id'=>$book_id,
                'score_count'=>$comment_add_score,
                'get_type'=>'member_comment',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('nurse_score', $nurse_score_data);
            $complaint_state = 0;
            if($comment_level == 'bad') {
                $complaint_state = 1;
            }
            DB::query("UPDATE ".DB::table('nurse')." SET grade_id=$grade_id, nurse_score=nurse_score+$comment_add_score, nurse_commentnum=nurse_commentnum+1, complaint_state=$complaint_state WHERE nurse_id='".$book['nurse_id']."'");
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
                    'book_id'=>$book_id,
                    'score_count'=>$agent_add_score,
                    'get_type'=>'member_comment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('agent_score', $agent_score_data);
                DB::query("UPDATE ".DB::table('agent')." SET grade_id=$agent_grade_id, agent_score=agent_score+$agent_add_score WHERE agent_id='".$book['agent_id']."'");
            }
            //雇主积分收益
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
            $member_add_score=0;
            if(empty($comment_image)){
                $member_add_score+=30;
            }else{
                $member_add_score+=80;
            }
            $member_get_score=DB::result_first("SELECT SUM(score_count) FROM ".DB::table('member_score')." WHERE member_id='".$book['member_id']."' AND get_type='member_comment' AND get_time=$now_date");
            if($member_get_score+$member_add_score>=400){
                $member_add_score=400-$member_get_score;
            }
            $member_score_data=array(
                'member_id'=>$book['member_id'],
                'book_id'=>$book_id,
                'agent_id'=>$book['agent_id'],
                'score_count'=>$member_add_score,
                'get_type'=>'member_comment',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_score', $member_score_data);
            $member_score=$member['member_score']+$member_add_score;
            $grade = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member_score." ORDER BY card_predeposit DESC");
            $member_data=array(
                'member_score'=>intval($member_score),
                'grade_id'=>intval($grade['card_id'])
            );
            DB::update('member', $member_data, array('member_id'=>$book['member_id']));
            //雇主团豆豆收益
            $member_add_coin=0;
            if(empty($comment_image)){
                $member_add_coin+=20;
            }else{
                $member_add_coin+=50;
            }
            $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='member_comment' AND get_time=$now_date");
            if($member_get_coin+$member_add_coin>=200){
                $member_add_coin=200-$member_get_coin;
            }
            $member_coin_data=array(
                'member_id'=>$book['member_id'],
                'book_id'=>$book_id,
                'coin_count'=>$member_add_coin,
                'get_type'=>'member_comment',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_coin', $member_coin_data);
            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
            //升级送团豆豆
            if(intval($grade['card_id'])>intval($member['grade_id'])){
                if(intval($grade['card_id'])>intval($member['large_grade_id'])){
                    DB::query("UPDATE ".DB::table('member')." SET large_grade_id=$grade_id WHERE member_id='".$book['member_id']."'");
                    DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+188 WHERE member_id='".$book['member_id']."'");
                    $member_coin_level_data=array(
                        'member_id'=>$book['member_id'],
                        'book_id'=>$book_id,
                        'coin_count'=>188,
                        'get_type'=>'level_up',
                        'true_time'=>time(),
                        'get_time'=>$now_date
                    );
                    DB::insert('member_coin', $member_coin_level_data);
                }
            }
            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        } else {
            exit(json_encode(array('code'=>1,'msg'=>'网路不稳定，请稍候重试')));
        }
    }
    public function member_comment_messageOp(){
        $book_id = empty($_GET['book_id']) ? 0 : intval($_GET['book_id']);
        if(empty($book_id)){
            exit(json_encode(array('code'=>1,'msg'=>'订单号不能为空')));
        }
        $book = DB::fetch_first("SELECT book_id,book_sn,nurse_id,book_amount,agent_id FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
        $book['nurse_image'] = $nurse['nurse_image'];
        $book['nurse_nickname'] = $nurse['nurse_nickname'];
        $book['service_type'] = $nurse['service_type'];
        $book['promise_state'] = $nurse['promise_state'];
        if(!empty($book['agent_id'])){
            $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$book['agent_id']."'");
            $book['agent_name'] = $agent['agent_name'];
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$book)));
    }
    public function member_comment_showOp(){
        $comment_id=empty($_GET['comment_id']) ? 0 : intval($_GET['comment_id']);
        $comment = DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
        $comment['comment_image']=empty($comment['comment_image']) ? '' : unserialize($comment['comment_image']);
        $nurse_id=$comment['nurse_id'];
        $book_id=$comment['book_id'];
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>array('nurse_nickname'=>$nurse['nurse_nickname'],'book_sn'=>$book['book_sn'],'comment'=>$comment))));
    }
    public function member_comment_resumeOp(){
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
            exit(json_encode(array('code'=>1,'msg'=>'请选择满意度评分')));
        }
        if(empty($comment_content)) {
            exit(json_encode(array('code'=>1,'msg'=>'请至少写点你的服务感受')));
        }
        if($comment['comment_level']=='good'){
            exit(json_encode(array('code'=>1,'msg'=>'好评无法修改')));
        }
        if($comment['comment_level']==$comment_level){
            exit(json_encode(array('code'=>1,'msg'=>'改变评价等级才可以修改')));
        }
        if($comment_honest_star<intval($comment['comment_honest_star'])){
            exit(json_encode(array('code'=>1,'msg'=>'诚实守信等级不能减少')));
        }
        if($comment_love_star<intval($comment['comment_love_star'])){
            exit(json_encode(array('code'=>1,'msg'=>'爱岗敬业等级不能减少')));
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
        exit(json_encode(array('code'=>0,'msg'=>'修改成功')));
    }
    public function nurse_replyOp(){
        $comment_id=empty($_POST['comment_id']) ? 0 : intval($_POST['comment_id']);
        $agent_reply_content=empty($_POST['agent_reply_content']) ? '' : $_POST['agent_reply_content'];
        $comment=DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
        if(empty($comment)){
            exit(json_encode(array('code'=>1,'msg'=>'订单评价不存在')));
        }
        if(empty($agent_reply_content)){
            exit(json_encode(array('code'=>1,'msg'=>'回复内容不能为空')));
        }
        DB::query("UPDATE ".DB::table('nurse_comment')." SET agent_reply_content='$agent_reply_content' WHERE comment_id='$comment_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
    public function nurse_commentOp(){
        $comment_id=empty($_POST['comment_id']) ? 0 : intval($_POST['comment_id']);
        $comment=DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
        $member_id=$comment['member_id'];
        if(empty($comment)){
            exit(json_encode(array('code'=>1,'msg'=>'订单评价不存在')));
        }
        $nurse_comment_level=!in_array($_POST['nurse_comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['nurse_comment_level'];
        $nurse_comment_content = empty($_POST['nurse_comment_content']) ? '' : $_POST['nurse_comment_content'];
        if(empty($nurse_comment_content)){
            exit(json_encode(array('code'=>1,'msg'=>'评价内容不能为空')));
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
        exit(json_encode(array('code'=>0,'msg'=>'评价成功')));
    }
    public function nurse_comment_showOp(){
        $comment_id=empty($_GET['comment_id']) ? 0 : intval($_GET['comment_id']);
        $comment = DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
        $comment['comment_image']=empty($comment['comment_image']) ? '' : unserialize($comment['comment_image']);
        $member_id=$comment['member_id'];
        $book_id=$comment['book_id'];
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
        $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>array('member_nickname'=>$member['member_nickname'],'book_sn'=>$book['book_sn'],'comment'=>$comment))));
    }
    public function nurse_comment_resumeOp(){
        $comment_id=empty($_POST['comment_id']) ? 0 : intval($_POST['comment_id']);
        $nurse_comment_level = !in_array($_POST['nurse_comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['nurse_comment_level'];
        $nurse_comment_content = empty($_POST['nurse_comment_content']) ? '' : $_POST['nurse_comment_content'];
        $nurse_comment_image = empty($_POST['nurse_comment_image']) ? array() : $_POST['nurse_comment_image'];
        $comment=DB::fetch_first("SELECT * FROM ".DB::table('nurse_comment')." WHERE comment_id='$comment_id'");
        if(empty($nurse_comment_level)) {
            exit(json_encode(array('code'=>1,'msg'=>'请选择满意度评分')));
        }
        if(empty($nurse_comment_content)) {
            exit(json_encode(array('code'=>1,'msg'=>'请至少写点你的服务感受')));
        }
        if($comment['nurse_comment_level']=='good'){
            exit(json_encode(array('code'=>1,'msg'=>'好评无法修改')));
        }
        if($comment['nurse_comment_level']==$nurse_comment_level){
            exit(json_encode(array('code'=>1,'msg'=>'改变评价等级才可以修改')));
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
        exit(json_encode(array('code'=>0,'msg'=>'修改成功')));
    }
}
?>