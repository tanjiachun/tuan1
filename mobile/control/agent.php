<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agentControl extends BaseMobileControl {
    public function indexOp() {
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $agent['agent_qa_image'] = empty($agent['agent_qa_image'] ) ? array() : unserialize($agent['agent_qa_image'] );
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$agent_id'");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND refund_amount=0");
        $book_pay_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND refund_amount=0 AND book_state=10");
        $comment_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE agent_id='$this->agent_id' AND agent_reply_content!=''");
        $audit_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('staff_audit')." WHERE agent_id='$this->agent_id' AND nurse_state=1 AND agent_audit_state=0");
        $invoice_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND invoice_id!=0 AND book_state!=10 AND book_state!=0");
        $agent['nurse_count']=intval($count);
        $agent['book_count']=intval($book_count);
        $agent['book_pay_count']=intval($book_pay_count);
        $agent['comment_count']=intval($comment_count);
        $agent['audit_count']=intval($audit_count);
        $agent['invoice_count']=intval($invoice_count);
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$agent)));
    }

    public function invoice_listOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }

    }

    public function invoiceOp(){
        $member_id=empty($_POST['member_id']) ? 0 : intval($_POST['member_id']);
        $invoice_type=empty($_POST['invoice_type']) ? '' : $_POST['invoice_type'];
        $invoice_title=empty($_POST['invoice_title']) ? '' : $_POST['invoice_title'];
        $invoice_content=empty($_POST['invoice_content']) ? '' : $_POST['invoice_content'];
        $invoice_membername=empty($_POST['invoice_membername']) ? '' : $_POST['invoice_membername'];
        $invoice_provinceid=empty($_POST['invoice_provinceid']) ? 0 : intval($_POST['invoice_provinceid']);
        $invoice_cityid=empty($_POST['invoice_cityid']) ? 0 : intval($_POST['invoice_cityid']);
        $invoice_areaid=empty($_POST['invoice_areaid']) ? 0 : intval($_POST['invoice_areaid']);
        $invoice_address=empty($_POST['invoice_address']) ? '' : $_POST['invoice_address'];
        $unit_name=empty($_POST['unit_name']) ? '' : $_POST['unit_name'];
        $invoice_code=empty($_POST['invoice_code']) ? '' : $_POST['invoice_code'];
        $invoice_unit_membername=empty($_POST['invoice_unit_membername']) ? '' : $_POST['invoice_unit_membername'];
        $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_provinceid'");
        $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_cityid'");
        $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_areaid'");
        $invoice_areainfo = $member_provincename.$member_cityname.$member_areaname;
        $invoice_data=array(
            'member_id'=>$member_id,
            'invoice_type'=>$invoice_type,
            'invoice_title'=>$invoice_title,
            'invoice_content'=>$invoice_content,
            'invoice_membername'=>$invoice_membername,
            'invoice_provinceid'=>$invoice_provinceid,
            'invoice_cityid'=>$invoice_cityid,
            'invoice_areaid'=>$invoice_areaid,
            'invoice_areainfo'=>$invoice_areainfo,
            'invoice_address'=>$invoice_address,
            'unit_name'=>$unit_name,
            'invoice_code'=>$invoice_code,
            'invoice_unit_membername'=>$invoice_unit_membername,
            'add_time'=>time()
        );
        $invoice_id=DB::insert('invoice',$invoice_data,1);
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','invoice_id'=>$invoice_id)));
    }

    public function invoice_showOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $query = DB::query("SELECT invoice_id,member_id,book_state,book_amount FROM ".DB::table('nurse_book')." WHERE agent_id='$agent_id' AND invoice_id!='' AND book_state!=10 AND book_state!=0 ORDER BY add_time DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $member_ids[]=$value['member_id'];
            $invoice_ids[]=$value['invoice_id'];
            $book_list[] = $value;
        }
        if(!empty($invoice_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('invoice')." WHERE invoice_id in ('".implode("','", $invoice_ids)."')");
            while($value = DB::fetch($query)) {
                $invoice_list[$value['invoice_id']] = $value;
            }
        }
        if(!empty($member_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $member_list[$value['member_id']] = $value;
            }
        }
        foreach($book_list as $key => $value) {
            $book_list[$key]['member_name'] = $member_list[$value['member_id']]['member_name'];
            $book_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
            $book_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
            $book_list[$key]['invoice_type'] = $invoice_list[$value['invoice_id']]['invoice_type'];
            $book_list[$key]['invoice_membrname'] = $invoice_list[$value['invoice_id']]['invoice_membername'];
            $book_list[$key]['invoice_unit_membrname'] = $invoice_list[$value['invoice_id']]['invoice_unit_membername'];
            $book_list[$key]['invoice_title'] = $invoice_list[$value['invoice_id']]['invoice_utitle'];
            $book_list[$key]['invoice_content'] = $invoice_list[$value['invoice_id']]['invoice_ucontent'];
            $book_list[$key]['unit_name'] = $invoice_list[$value['invoice_id']]['unit_name'];
            $book_list[$key]['invoice_code'] = $invoice_list[$value['invoice_id']]['invoice_code'];
            $book_list[$key]['invoice_areainfo'] = $invoice_list[$value['invoice_id']]['invoice_areainfo'];
            $book_list[$key]['invoice_address'] = $invoice_list[$value['invoice_id']]['invoice_address'];
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$book_list)));
    }


    public function agent_bookOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE agent_id='$agent_id' AND show_state=0 AND book_type=''";
        $field_value=!in_array($_GET['filed_value'], array('all', 'payment', 'no_pay', 'close')) ? 'all' : $_GET['field_value'];
        if($field_value == 'all') {
            $wheresql .= "";
        }elseif ($field_value=='payment'){
            $wheresql .=" AND book_state=20";
        }elseif ($field_value=='no_pay'){
            $wheresql .=" AND book_state=10";
        }elseif ($field_value=='close'){
            $wheresql .=" AND book_state=0";
        }
        $query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
        while($value=DB::fetch($query)){
            $member_ids[]=$value['member_id'];
            $book_list[]=$value;
        }
        if(!empty($member_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $member_list[$value['member_id']] = $value;
            }
        }
        foreach($book_list as $key => $value) {
            $book_list[$key]['member_name'] = $member_list[$value['member_id']]['member_name'];
        }
        $agent['book_list']=$book_list;
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$agent)));
    }

    public function staff_recruitOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent=DB::fetch_first("SELECT agent_id,agent_name FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        if(empty($nurse_id)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在','data'=>array())));
        }
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('done'=>'false','msg'=>'家政人员不存在')));
        }
        $data=array(
            'agent_id'=>$agent_id,
            'nurse_id'=>$nurse_id,
            'agent_state'=>1,
            'nurse_state'=>0,
            'invitation_count'=>1,
            'staff_time'=>time()
        );
        $staff_id=DB::insert('staff_audit', $data, 1);
        if(!empty($staff_id)){
            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'网络连接错误，请稍后重试')));
        }
    }

    public function re_invitationOp(){
        $staff_id=empty($_GET['staff_id']) ? 0 : intval($_GET['staff_id']);
        $staff=DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE staff_id='$staff_id'");
        if(empty($staff)){
            exit(json_encode(array('code'=>1,'msg'=>'网络不稳定，请稍后重试')));
        }
        $nurse_id=$staff['nurse_id'];
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
        if($staff['invitation_count']>=6){
            exit(json_encode(array('code'=>1,'msg'=>'邀请数量过多')));
        }
        DB::query("UPDATE ".DB::table('staff_audit')." SET nurse_audit_state=0 WHERE staff_id='$staff_id'");
        DB::query("UPDATE ".DB::table('staff_audit')." SET invitation_count=invitation_count+1 WHERE staff_id='$staff_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }

    public function agent_contentOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent=DB::fetch_first("SELECT agent_id,agent_sign_image FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $query=DB::query("SELECT * FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' ORDER BY nurse_score DESC LIMIT 0,4");
        while($value=DB::fetch($query)){
            $value['agent_name']=$agent['agent_name'];
            $nurse_list[]=$value;
        }
        $agent['nurse_list']=$nurse_list;
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$agent)));
    }

    public function agent_nurseOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent = DB::fetch_first("SELECT agent_id,agent_name FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $page=empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE agent_id='$agent_id'";
        $sort_value=!in_array($_GET['sort_value'], array('all', 'volume', 'favourable', 'price_up','price_down')) ? 'all' : $_GET['sort_value'];
        if($sort_value=='volume'){
            $wheresql.=" ORDER BY nurse_booknum DESC";
        }elseif($sort_value=='favourable'){
            $wheresql.=" ORDER BY nurse_commentnum DESC";
        }elseif ($sort_value=='price_up'){
            $wheresql.=" ORDER BY nurse_price DESC";
        }elseif ($sort_value=='price_down'){
            $wheresql.=" ORDER BY nurse_price ASC";
        }
        $query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." LIMIT $start, $perpage");
        while($value=DB::fetch($query)){
            $value['agent_name']=$agent['agent_name'];
            $nurse_list[]=$value;
        }
        $agent['nurse_list']=$nurse_list;
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$agent)));
    }
    public function  agent_questionOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent=DB::fetch_first("SELECT agent_id FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $page = 1;$perpage = 10;$start = ($page-1)*$perpage;
        $query = DB::query("SELECT * FROM ".DB::table('agent_question')." WHERE agent_id=".$agent['agent_id']." AND answer_content!='' ORDER BY question_time DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $member_ids[]=$value['member_id'];
            $question_list[] = $value;
        }
        if(!empty($member_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $value['member_phone'] = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
                $member_list[$value['member_id']] = $value;
            }
        }
        foreach($question_list as $key => $value) {
            $question_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
            $question_list[$key]['member_avatar'] = $member_list[$value['member_id']]['member_avatar'];
        }
        $agent['question_list']=$question_list;
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$agent)));
    }
    public function put_questionOp(){
        $this->check_authority();
        if(empty($this->member_id)){
            exit(json_encode(array('code'=>1,'msg'=>'您还未登录')));
        }
        $agent_id=empty($_POST['agent_id']) ? 0 : intval($_POST['agent_id']);
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }

        $question_content=empty($_POST['question_content']) ? '' : $_POST['question_content'];
        if(empty($question_content)){
            exit(json_encode(array('code'=>1,'msg'=>'问题内容不能为空')));
        }
        $data=array(
            'member_id'=>$this->member_id,
            'agent_id'=>$agent_id,
            'question_content'=>$question_content,
            'question_time'=>time()
        );
        DB::insert('agent_question', $data, 1);
        exit(json_encode(array('code'=>0,'msg'=>'提问成功')));
    }
    public function summaryOp(){
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $agent = DB::fetch_first("SELECT agent_id,agent_summary FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$agent)));
    }
    public function agent_focusOp(){
        $this->check_authority();
        $agent_id=empty($_POST['agent_id']) ? 0 : intval($_POST['agent_id']);
        if(empty($agent_id)){
            exit(json_encode(array('code'=>1,'msg'=>'家政机构不存在','data'=>array())));
        }
        $focus= DB::fetch_first("SELECT * FROM ".DB::table('focus')." WHERE member_id='$this->member_id' AND agent_id='$agent_id' AND focus_type='agent'");
        if(empty($focus)) {
            $data = array(
                'member_id' => $this->member_id,
                'agent_id' => $agent_id,
                'focus_type' => 'agent',
                'focus_time' => time(),
            );
            DB::insert('focus', $data);
            DB::query("UPDATE ".DB::table('agent')." SET agent_focusnum=agent_focusnum+1 WHERE agent_id='$agent_id'");
        } else {
            exit(json_encode(array('code'=>1,'msg'=>'您已经关注了')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'关注成功')));
    }
    public function agent_nurse_auditOp(){
        $this->check_authority();
        $agent = DB::fetch_first("SELECT agent_id,agent_name FROM ".DB::table('agent')." WHERE member_id='$this->member_id'");
        if(exit($agent)){
            exit(json_encode(array('code'=>1,'msg'=>'您还不是机构')));
        }
        $query = DB::query("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='".$agent['agent_id']."' AND nurse_state=1 ORDER BY staff_time DESC");
        while($value = DB::fetch($query)) {
            $nurse_audit_list[] = $value;
        }
        $query = DB::query("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='".$agent['agent_id']."' ORDER BY staff_time DESC");
        while($value = DB::fetch($query)) {
            $nurse_recruit_list[] = $value;
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>array('nurse_audit_list'=>$nurse_audit_list,'nurse_recruit_list'=>$nurse_recruit_list))));
    }
    //同意审核
    public function agreeOp(){
        $nurse_id=empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
        $nurse = DB::fetch_first("SELECT agent_id,agent_name FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(!empty($nurse['agent_id'])){
            exit(json_encode(array('code'=>1,'msg'=>'该家政人员已加入机构')));
        }
        if(empty($nurse_id)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
        DB::query("UPDATE ".DB::table('staff_audit')." SET agent_audit_state=1 WHERE agent_id='$this->agent_id' AND nurse_id='$nurse_id'");
        DB::query("UPDATE ".DB::table('nurse')." SET agent_id='$this->agent_id' WHERE nurse_id='$nurse_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
    //拒绝审核
    public function rejectOp(){
        $nurse_id=empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
        if(empty($nurse_id)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
        DB::query("UPDATE ".DB::table('staff_audit')." SET agent_audit_state=2 WHERE agent_id='$this->agent_id' AND nurse_id='$nurse_id'");
        exit(json_encode(array('code'=>1,'msg'=>'操作成功')));
    }
}

?>