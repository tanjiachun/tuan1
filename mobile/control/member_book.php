<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_bookControl extends BaseMobileControl {

    /**
     * 提交订单功能
     * 请求类型：POST
     * 接口地址：/mobile.php?act=member_book
     */
    public function indexOp(){
        $this->check_authority();
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $member_id=$member['member_id'];
        $page = empty($_POST['page']) ? 0 : intval($_POST['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE member_id='$member_id'";
        $state = in_array($_POST['state'], array('all','pay','work','comment','refund')) ? $_POST['state'] : 'all';
        if($state == 'all') {
          $wheresql .= "";
        } elseif($state == 'pay') {
          $wheresql .= " AND book_state=10";
        }elseif($state == 'work') {
          $wheresql .= " AND book_state=20 AND nurse_state!=2 AND nurse_state!=4";
        }elseif($state == 'comment') {
          $wheresql .= " AND book_state=30 AND comment_state=0";
        }elseif($state == 'back') {
          $wheresql .= " AND refund_state=1";
        }
        $query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
          $nurse_ids[] = $value['nurse_id'];
          $agent_ids[]=$value['agent_id'];
          $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
          $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
          $value['nurse_comment_image'] = empty($value['nurse_comment_image']) ? array() : unserialize($value['nurse_comment_image']);
          $book_list[] = $value;
        }
        if(!empty($nurse_ids)) {
          $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
          while($value = DB::fetch($query)) {
              $nurse_list[$value['nurse_id']] = $value;
          }
        }
        if(!empty($agent_ids)) {
          $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
          while($value = DB::fetch($query)) {
              $agent_list[$value['agent_id']] = $value;
          }
        }
        foreach($book_list as $key => $value) {
          $book_list[$key]['nurse_nickname'] = $nurse_list[$value['nurse_id']]['nurse_nickname'];
          $book_list[$key]['nurse_image'] = $nurse_list[$value['nurse_id']]['nurse_image'];
          $book_list[$key]['service_type'] = $nurse_list[$value['nurse_id']]['service_type'];
          $book_list[$key]['nurse_promise_state'] = intval($nurse_list[$value['nurse_id']]['promise_state']);
          $book_list[$key]['nurse_special_service'] = $nurse_list[$value['nurse_id']]['nurse_special_service'];
          $book_list[$key]['agent_name'] = $agent_list[$value['agent_id']]['agent_name'];
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$book_list)));
    }

    public function book_finishOp(){
        $this->check_authority();
        $book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
        if(empty($book) || $book['member_id'] != $this->member_id) {
            exit(json_encode(array('code'=>1,'msg'=>'预约单不存在')));
        }
        if($book['book_state'] != '20') {
            exit(json_encode(array('code'=>1,'msg'=>'预约单不能完成')));
        }
        DB::update('nurse_book', array('book_state'=>30, 'finish_time'=>time()), array('book_id'=>$book_id));
        DB::query("UPDATE ".DB::table('nurse')." SET work_state=0 WHERE nurse_id='".$book['nurse_id']."'");
        DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=1 WHERE nurse_id='".$book['nurse_id']."'");
        $profit = DB::fetch_first("SELECT * FROM ".DB::table('nurse_profit')." WHERE book_id='".$book['book_id']."'");
        if(!empty($profit)) {
            $profit_data = array(
                'is_freeze' => 0,
                'add_time' => time(),
            );
            DB::update('nurse_profit', $profit_data, array('profit_id'=>$profit['profit_id']));
            DB::query("UPDATE ".DB::table('nurse')." SET pool_amount=pool_amount-".$profit['profit_amount'].", available_amount=available_amount+".$profit['profit_amount']." WHERE nurse_id='".$book['nurse_id']."'");
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
}

?>