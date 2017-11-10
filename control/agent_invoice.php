<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_invoiceControl extends BaseAgentControl {
     public function indexOP(){
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
         $mpurl = "index.php?act=agent_invoice";
         $wheresql = " WHERE agent_id='$this->agent_id' AND invoice_id!='' AND book_state!=10 AND book_state!=0";
         $state = in_array($_GET['state'], array('person', 'unit')) ? $_GET['state'] : 'person';
         $mpurl .= "&state=$state";
         if($state == 'person') {
             $wheresql .= " AND invoice_type='个人'";
         }elseif ($state=='unit'){
             $wheresql .=" AND invoice_type='单位'";
         }
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
         $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book').$wheresql);
         $query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
         while($value = DB::fetch($query)) {
             $invoice_ids[]=$value['invoice_id'];
             $book_list[] = $value;
         }
         if(!empty($invoice_ids)) {
             $query = DB::query("SELECT * FROM ".DB::table('invoice')." WHERE invoice_id in ('".implode("','", $invoice_ids)."')");
             while($value = DB::fetch($query)) {
                 $invoice_list[$value['invoice_id']] = $value;
             }
         }
         $multi = multi($count, $perpage, $page, $mpurl);

         $curmodule = 'home';
         $bodyclass = '';
         include(template('agent_invoice'));
    }

    public function invoice_showOp(){
         $book_id=empty($_GET['book_id']) ? 0 : $_GET['book_id'];
         $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
         if(!empty($book['book_type'])){
             $book_ids=explode(',',$book['book_sub_order']);
             $book_sn_contens='';
             for($i=0;$i<count($book_ids);$i++){
                 $books = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_ids[$i]'");
                if($i>=1){
                    $book_sn_contens=$book_sn_contens.','.$books['book_sn'];
                }else{
                    $book_sn_contens=$book_sn_contens.$books['book_sn'];
                }
             }
             $invoice=DB::fetch_first("SELECT * FROM ".DB::table('invoice')." WHERE invoice_id='".$book['invoice_id']."'");
             $book_sn_contens=explode(',',$book_sn_contens);
             $invoice['book_sn_content']=$book_sn_contens;
         }else{
             $invoice=DB::fetch_first("SELECT * FROM ".DB::table('invoice')." WHERE invoice_id='".$book['invoice_id']."'");
         }
        $curmodule = 'home';
        $bodyclass = '';
        include(template('invoice_details'));
    }
    public function invoice_okOp(){
        $book_id=empty($_GET['book_id']) ? 0 : $_GET['book_id'];
        DB::query("UPDATE ".DB::table('nurse_book')." SET invoice_state=1 WHERE book_id='$book_id'");
        exit(json_encode(array('done'=>'true')));
    }
    public function invoice_noOp(){
        $book_id=empty($_GET['book_id']) ? 0 : $_GET['book_id'];
        DB::query("UPDATE ".DB::table('nurse_book')." SET invoice_state=0 WHERE book_id='$book_id'");
        exit(json_encode(array('done'=>'true')));
    }
}

?>