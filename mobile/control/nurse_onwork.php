<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_onworkControl extends BaseMobileControl {
    public function indexOp(){
        $book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
        $book = DB::fetch_first("SELECT * FROM " . DB::table('nurse_book') . " WHERE book_id='$book_id'");
        if (empty($book)) {
            exit(json_encode(array('code'=>1,'msg' => '订单不存在')));
        }
        $book_code=empty($_POST['book_code']) ? 0 : $_POST['book_code'];
        if(empty($book_code)){
            exit(json_encode(array('code'=>1,'msg'=>'请输入在岗码')));
        }
        $code=DB::fetch_first("SELECT * FROM " .DB::table('book_code') . " WHERE book_id='$book_id' AND code_value='$book_code'");
        if(empty($code)){
            exit(json_encode(array('code'=>1,'msg'=>'在岗码不正确')));
        }
        DB::query("UPDATE ".DB::table('nurse_book')." SET nurse_state=2 WHERE book_id='$book_id'");
        DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=2 WHERE nurse_id='".$book['nurse_id']."'");
        DB::query("UPDATE ".DB::table('member_favourite')." SET nurse_state=2 WHERE nurse_id='".$book['nurse_id']."'");
        $confirm_time=time();
        if($book['nurse_type']==4 || $book['nurse_type']==7 || $book['nurse_type']==8 || $book['nurse_type']==9 || $book['nurse_type']==10){
            $book_finish_time=$confirm_time+28800;
            $finish_time=$confirm_time+28800;
        }else{
            $book_finish_time=$confirm_time+2592000*intval($book['work_duration'])+864008*intval($book['work_duration_days'])+6400*intval($book['work_duration_hours'])+60*intval($book['work_duration_mins']);
            if($book['work_duration']>1){
                $finish_time=$confirm_time+2592000;
            }else{
                $finish_time=$book_finish_time;
            }
        }
        DB::update('nurse_book', array('nurse_state'=>2,'work_time'=>$confirm_time,'finish_time'=>$finish_time,'book_finish_time'=>$book_finish_time), array('book_id'=>$book_id));
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
}

?>