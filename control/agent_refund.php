<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_refundControl extends BaseAgentControl {
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

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $mpurl = "index.php?act=agent_refund";
        $wheresql = " WHERE agent_id='$this->agent_id' AND refund_state=1 AND book_state=0 AND show_state=0";
        $search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
        if(!empty($search_name)) {
            $mpurl .= "&search_name=".urlencode($search_name);
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_name like '%".$search_name."%' OR member_phone like '%".$search_name."%'");
            while($value = DB::fetch($query)) {
                $nurse_ids[] = $value['nurse_id'];
            }
            if(!empty($nurse_ids)) {
                $wheresql .= " AND (book_sn like '%".$search_name."%' OR nurse_id in ('".implode("','", $nurse_ids)."'))";
            } else {
                $wheresql .= " AND book_sn like '%".$search_name."%'";
            }
        }
        $state = in_array($_GET['state'], array('all', 'time','nurse_refund','member_refund')) ? $_GET['state'] : 'all';
        $mpurl .= "&state=$state";

        if($state == 'all') {
            $wheresql .= "";
        }elseif ($state=='time'){
            $wheresql .=" ORDER BY finish_time DESC";
        }elseif ($state=='nurse_refund'){
            $wheresql .=" AND refund_type='member'";
        }elseif ($state=='member_refund'){
            $wheresql .=" AND refund_type='nurse'";
        }
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $nurse_ids[] = $value['nurse_id'];
            $value['book_message'] = nl2br($value['book_message']);
            $value['book_service'] = empty($value['book_service']) ? array() : unserialize($value['book_service']);
            $value['invoice_content'] = empty($value['invoice_content']) ? array() : unserialize($value['invoice_content']);
            $book_service = array();
            foreach($value['book_service'] as $subkey => $subvalue) {
                $book_service[] = $subvalue['service_name'];
            }
            $value['book_service'] = empty($book_service) ? '' : implode(' ', $book_service);
            $book_list[] = $value;
        }
        if(!empty($nurse_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        $multi = multi($count, $perpage, $page, $mpurl);

        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_refund'));
    }

    public function delOp(){
        if(submitcheck()){
            $book_ids=empty($_POST['del_book_ids']) ? '' : $_POST['del_book_ids'];
            $book_ids = explode(',', $book_ids);
            if(empty($book_ids)){
                exit(json_encode(array('msg'=>'请至少选择一个家政人员')));
            }
            DB::query("UPDATE ".DB::table('nurse_book')." SET show_state=1 WHERE book_id in ('".implode("','", $book_ids)."')");
            exit(json_encode(array('done'=>'true', 'del_ids'=>$book_ids)));
        }else{
            exit(json_encode(array('msg'=>'网络不稳定，请稍后重试')));
        }
    }
}

?>