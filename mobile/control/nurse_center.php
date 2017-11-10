<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_centerControl extends BaseMobileControl {
    /**
     * 获取家政人员信息
     */
    private function get_nurse(){
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id = '$this->member_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在','data'=>array())));
        }
        return $nurse;
    }

    public function indexOp(){
        // 检测登陆状态
        $this->check_authority();
        // 获取家政人员信息
        $nurse = $this->get_nurse();

        $collect_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_favourite')." WHERE nurse_id='$nurse[nurse_id]' AND show_state=0");
        $nurse['collect_count']=$collect_count;
        $work_days=ceil((time()-$nurse['nurse_time'])/86400);
        $nurse['work_days'] = $work_days;
        $time_seven = time()-604800;
        $new_book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$nurse[nurse_id]' AND book_state!=30 AND book_state!=0 AND add_time>$time_seven");
        $nurse['new_book_count'] = $new_book_count;
        $pending_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$nurse[nurse_id]' AND book_state=30 AND comment_state=0");
        $nurse['pending_count'] = $pending_count;
        $refund_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$nurse[nurse_id]' AND refund_state=1 AND refund_time>=$time_seven");
        $nurse['refund_count'] = $refund_count;
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$nurse)));
    }


    /**
     * 家政人员简历编辑
     *
     */
    public function nurse_setOp(){
        // @todo 未测试，编辑功能缺少
        // 检测登陆状态
        $this->check_authority();
        // 获取家政人员信息
        $nurse = $this->get_nurse();

        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse[nurse_id]'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在','data'=>array())));
        }
        $nurse['nurse_work_exe']=empty($nurse['nurse_work_exe']) ? array() : unserialize($nurse['nurse_work_exe']);
        $nurse['car_weight_list']=empty($nurse['car_weight_list']) ? array() : unserialize($nurse['car_weight_list']);
        $nurse['car_price_list']=empty($nurse['car_price_list']) ? array() : unserialize($nurse['car_price_list']);
        $nurse['nurse_qa_image']=empty($nurse['nurse_qa_image']) ? array() : unserialize($nurse['nurse_qa_image']);
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$nurse)));
    }

    public function nurse_bookOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在','data'=>array())));
        }
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE nurse_id='$nurse_id' AND show_state=0 AND book_type=''";
        $field_value=!in_array($_GET['filed_value'], array('all', 'payment', 'comment', 'before_three_mouth')) ? 'all' : $_GET['field_value'];
        $deltime=strtotime("-90days");
        if($field_value == 'all') {
            $wheresql .= "";
        }elseif ($field_value=='payment'){
            $wheresql .=" AND book_state=20";
        }elseif ($field_value=='comment'){
            $wheresql .=" AND book_state=30 AND comment_state=1";
        }elseif ($field_value=='before_three_mouth'){
            $wheresql .=" AND add_time<'$deltime'";
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
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$book_list)));
    }

    public function nurse_commentOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在','data'=>array())));
        }
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE nurse_id='$nurse_id' AND comment_content!=''";
        $field_value=!in_array($_GET['filed_value'], array('member','nurse')) ? 'member' : $_GET['field_value'];
        if($field_value == 'member') {
            $wheresql .= " AND comment_content!=''";
        }elseif ($field_value=='nurse'){
            $wheresql .=" AND nurse_comment_content!=''";
        }
        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql." ORDER BY comment_time DESC LIMIT $start, $perpage");
        while($value=DB::fetch($query)){
            $member_ids[]=$value['member_id'];
            $book_ids[]=$value['book_id'];
            $value['comment_image']=empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
            $value['nurse_comment_image']=empty($value['nurse_comment_image']) ? array() : unserialize($value['nurse_comment_image']);
            $comment_list[]=$value;
        }
        if(!empty($member_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $member_list[$value['member_id']] = $value;
            }
        }
        if(!empty($book_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $book_ids)."')");
            while($value = DB::fetch($query)) {
                $book_list[$value['book_id']] = $value;
            }
        }
        foreach($comment_list as $key => $value) {
            $comment_list[$key]['member_name'] = $member_list[$value['member_id']]['member_nickname'];
            $comment_list[$key]['member_phone'] = $member_list[$value['member_id']]['member_phone'];
            $comment_list[$key]['book_sn'] = $book_list[$value['book_id']]['book_sn'];
            $comment_list[$key]['book_amount'] = $book_list[$value['book_id']]['book_amount'];
            $comment_list[$key]['member_coin_amount'] = $book_list[$value['book_id']]['member_coin_amount'];
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$comment_list)));
    }

    public function state_showOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if($nurse['state_cideci']==2 || $nurse['state_cideci']==4){
            exit(json_encode(array('code'=>1,'msg'=>'已在岗，状态无法设置')));
        }
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在','data'=>array())));
        };
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$nurse)));
    }

    public function state_setOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $state=!in_array($_GET['state'], array('1', '2', '3')) ? '1' : $_GET['state'];
        if($state==1){
            $book_code=empty($_GET['book_code']) ? '' : $_GET['book_code'];
            $book_id=empty($_GET['book_id']) ? 0 : $_GET['book_id'];
            $code=DB::fetch_first("SELECT * FROM " .DB::table('book_code') . " WHERE book_id='$book_id' AND code_value='$book_code'");
            if(empty($code)){
                exit(json_encode(array('code'=>1,'msg'=>'在岗验证码不正确')));
            }
            DB::query("UPDATE ".DB::table('nurse_book')." SET nurse_state=$state WHERE book_id='$book_id'");
        }
        DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=$state WHERE nurse_id='$nurse_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }

    /**
     * 家政人员申请加入机构
     * 请求类型：POST
     * 接口地址：/mobile?act=nurse_center&op=nurse_aduit
     * 请求参数：$_POST['agent_id']
     */
    public function nurse_aduitOp(){
        if(!empty($_POST)){
            // 检测登陆状态
            $this->check_authority();
            // 获取家政人员信息
            $nurse = $this->get_nurse();
            $agent_id = empty($_POST['agent_id']) ? 0 : intval($_POST['agent_id']);
            $agent = DB::fetch_first("SELECT agent_id,agent_name FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
            if(empty($agent)){
                exit(json_encode(array('code' => 1,'msg' => '家政机构不存在','data' => (object)array())));
            }

            if(!empty($nurse['agent_id'])){
                exit(json_encode(array('code' => 1,'msg' => '您已经加入机构，无法加入其他机构！','data' => (object)array())));
            }

            $staff_info = DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE agent_id='$agent_id' AND nurse_state = '1' AND nurse_audit_state = '0' AND nurse_id = '$nurse[nurse_id]'");
            if(!empty($staff_info)){
                exit(json_encode(array('code' => 1,'msg' => '您已经申请过该机构，请等待处理！','data' => (object)array())));
            }

            $data = array(
                'agent_id' => $agent_id,
                'nurse_id' => $nurse['nurse_id'],
                'agent_state' => 0,
                'nurse_state' => 1,
                'invitation_count' => 1,
                'staff_time' => time()
            );

            $staff_id = DB::insert('staff_audit', $data, 1);

            if(!empty($staff_id)){
                exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
            }else{
                exit(json_encode(array('code'=>1,'msg'=>'网络连接错误，请稍后重试')));
            }
        }else{
            exit(json_encode(array('code' => 1,'msg' => '权限错误！')));
        }
    }

    /**
     * 个人家政人员接受和拒绝家政机构邀请API
     * 请求类型：POST
     * 接口地址：/mobile?act=nurse_center&op=nurse_aduit_manger
     * 请求参数：$_POST['staff_id'] 邀请ID
     * 请求参数：$_POST['member_act'] 操作（同意：approve,拒绝： reject）
     */
    public function nurse_aduit_mangerOp(){
        if(!empty($_POST)){
            // 检测登陆状态
            $this->check_authority();
            // 获取家政人员信息
            $nurse = $this->get_nurse();
            if(empty($_POST['staff_id']) || empty($_POST['member_act']) || !in_array($_POST['member_act'], array('approve', 'reject'))){
                exit(json_encode(array('code' => 1,'msg' => '请求参数错误！')));
            }
            $staff_id = intval($_POST['staff_id']);
            $member_act = $_POST['member_act'];
            $staff_invit = DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE staff_id = '$staff_id' AND nurse_id='".$nurse['nurse_id']."' AND agent_state = '1' AND nurse_audit_state = '0'");
            if(empty($staff_invit)){
                exit(json_encode(array('code' => 1, 'msg' => '操作错误，您无权限处理此请求！')));
            }

            if($member_act == 'approve'){
                $nurse_work_state = DB::fetch_first("SELECT COUNT(book_id) AS num FROM ".DB::table('nurse_book')." WHERE nurse_id = '".$nurse['nurse_id']."' AND `book_state` in ('10', '20')");
                if($nurse_work_state['num'] != 0){
                    exit(json_encode(array('code' => 1, 'msg' => '您还有未完成的服务，暂时无法加入机构！')));
                }
                DB::query("UPDATE ".DB::table('staff_audit')." SET nurse_audit_state = '1' WHERE staff_id='$staff_id'");
                DB::query("UPDATE ".DB::table('nurse')." SET agent_id = '".$staff_invit['agent_id']."' WHERE nurse_id = '".$nurse['nurse_id']."'");
                exit(json_encode(array('code' => 0, 'msg' => '操作成功，您已成功加入机构！')));
            }else{
                DB::query("UPDATE ".DB::table('staff_audit')." SET nurse_audit_state = '2' WHERE staff_id='$staff_id'");
                exit(json_encode(array('code' => 2, 'msg' => '操作成功，您拒绝了该邀请！')));
            }
        }else{
            exit(json_encode(array('code' => 1,'msg' => '权限错误！')));
        }
    }

    /**
     * 机构家政人员退出机构API接口
     * 请求类型：GET
     * 接口地址：/mobile?act=nurse_center&op=nurse_org_quit
     */
    public function nurse_org_quitOp(){
        // 检测登陆状态
        $this->check_authority();
        // 获取家政人员信息
        $nurse = $this->get_nurse();

        if($nurse['agent_id'] == '0'){
            exit(json_encode(array('code' => 1, 'msg' => '您还没有加入机构！')));
        }
        $nurse_work_state = DB::fetch_first("SELECT COUNT(book_id) AS num FROM " . DB::table('nurse_book') . " WHERE nurse_id = '" . $nurse['nurse_id'] . "' AND `book_state` in ('10', '20')");
        if ($nurse_work_state['num'] != 0) {
            exit(json_encode(array('code' => 1, 'msg' => '您还有未完成的服务，暂时无法退出机构！')));
        }
        DB::query("UPDATE ".DB::table('nurse')." SET agent_id = '0' WHERE nurse_id = '".$nurse['nurse_id']."'");
        exit(json_encode(array('code' => 0, 'msg' => '您已经成功退出机构！')));
    }

    public function re_nurse_auditOp(){
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
        DB::query("UPDATE ".DB::table('staff_audit')." SET agent_audit_state=0 WHERE staff_id='$staff_id'");
        DB::query("UPDATE ".DB::table('staff_audit')." SET invitation_count=invitation_count+1 WHERE staff_id='$staff_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }

    /**
     * 加盟状态
     */
    public function audit_showOp(){

        $this->check_authority();
        $nurse = $this->get_nurse();
        $data = array(
            'agent_id' => $nurse['agent_id'],
            'agent' => (object)array(),
            'audit' => (object)array(),
        );

        if(!empty($nurse['agent_id'])){
            $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id = '".$nurse['agent_id']."'");
            $nurse['agent_name'] = $agent['agent_name'];
            $data['agent'] = array(
                'nurse_id' => $nurse['nurse_id'],
                'agent_id' => $agent['agent_id'],
                'agent_name' => $agent['agent_name'],
            );
        }else{
            $staff_audit = DB::fetch_first("SELECT * FROM ".DB::table('staff_audit')." WHERE nurse_id = '$nurse[nurse_id]' AND agent_state = '1' ORDER BY staff_time DESC");
            if(!empty($staff_audit)){
                $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$staff_audit['agent_id']."'");
                $data['audit'] = $staff_audit;
                $data['audit']['agent_name'] = $agent['agent_name'];
            }
        }

        exit(json_encode(array(
            'code' => 0,
            'data' => $data,
        )));
    }

    public function phone_showOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : $_GET['nurse_id'];
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
        if(empty($nurse['agent_id'])){
            $nurse['nurse_other_phone']=empty($nurse['nurse_other_phone']) ? '' : unserialize($nurse['nurse_other_phone']);
            $nurse['nurse_other_phone_choose']=empty($nurse['nurse_other_phone_choose']) ? '' : unserialize($nurse['nurse_other_phone_choose']);
        }else{
            $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$nurse['agent_id']."'");
            $nurse['nurse_other_phone']=empty($agent['agent_other_phone']) ? '' : unserialize($agent['agent_other_phone']);
            $nurse['nurse_other_phone_choose']=empty($agent['agent_other_phone_choose']) ? '' : unserialize($agent['agent_other_phone_choose']);
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$nurse)));
    }
    public function phone_setOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : $_GET['nurse_id'];
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
        if(!empty($nurse['agent_id'])){
            exit(json_encode(array('code'=>1,'msg'=>'您属于机构，无法修改')));
        }
        $nurse_other_phone_choose=empty($GET['nurse_other_phone_choose']) ? '' : $_GET['nurse_other_phone_choose'];
        $data=array(
            'nurse_other_phone_choose'=>empty($nurse_other_phone_choose) ? array() : serialize($nurse_other_phone_choose)
        );
        DB::update('nurse', $data, array('nurse_id'=>$nurse_id));
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
    public function promise_showOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : $_GET['nurse_id'];
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$nurse['promise_state'])));
    }
    public function promise_setOp(){
        $nurse_id=empty($_POST['nurse_id']) ? 0 : $_POST['nurse_id'];
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)){
            exit(json_encode(array('code'=>1,'msg'=>'家政人员不存在')));
        }
        if(!empty($nurse['agent_id']) && $nurse['authority_state'] ==1){
            exit(json_encode(array('code'=>1,'msg'=>'您沒有权限操作')));
        }
        $promise_state=empty($_POST['promise_state']) ? 0 : intval($_POST['promise_state']);
        DB::query("UPDATE ".DB::table('nurse')." SET promise_state=$promise_state WHERE nurse_id='$nurse_id'");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
}

?>