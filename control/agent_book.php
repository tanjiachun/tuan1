<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_bookControl extends BaseAgentControl {
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
        $mpurl = "index.php?act=agent_book";
        $wheresql = " WHERE agent_id='$this->agent_id' AND show_state=0 AND book_type=''";
        $state = in_array($_GET['state'], array('all', 'in_three_month', 'payment','onwork','evaluated','close','before_three_month')) ? $_GET['state'] : 'all';
        $mpurl .= "&state=$state";
        $deltime=strtotime("-90days");

        if($state == 'all') {
            $wheresql .= "";
        }elseif ($state=='in_three_month'){
            $wheresql .=" AND add_time>'$deltime'";
        }elseif ($state=='payment'){
            $wheresql .=" AND book_state=20";
        }elseif ($state=='onwork'){
            $wheresql .=" AND book_state=20 AND nurse_state=2";
        }elseif ($state=='evaluated'){
            $wheresql .=" AND comment_state=1";
        }elseif ($state=='close'){
            $wheresql .=" AND book_state=0";
        }elseif ($state=='before_three_month'){
            $wheresql .=" AND add_time<'$deltime'";
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
            $nurse_ids[] = $value['nurse_id'];
            $member_ids[]=$value['member_id'];
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
        if(!empty($member_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('member')." WHERE member_id in ('".implode("','", $member_ids)."')");
            while($value = DB::fetch($query)) {
                $member_list[$value['member_id']] = $value;
            }
        }
        $multi = multi($count, $perpage, $page, $mpurl);

        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_book'));
    }

    public function del_bookOp(){
        if(submitcheck()) {
            $book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
            $book = DB::fetch_first("SELECT * FROM " . DB::table('nurse_book') . " WHERE book_id='$book_id'");
            if (empty($book)) {
                exit(json_encode(array('msg' => '订单不存在')));
            }
            DB::query("UPDATE ".DB::table('nurse_book')." SET show_state=1 WHERE book_id='$book_id'");
            exit(json_encode(array('done'=>'true','book_id'=>$book_id)));
        }else{
            exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
        }
    }

    public function del_booksOp(){
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

    public function  onworkOp(){
        if(submitcheck()){
            $book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
            $book = DB::fetch_first("SELECT * FROM " . DB::table('nurse_book') . " WHERE book_id='$book_id'");
            if (empty($book)) {
                exit(json_encode(array('msg' => '订单不存在')));
            }
            $book_code=empty($_POST['book_code']) ? 0 : $_POST['book_code'];
            if(empty($book_code)){
                exit(json_encode(array('msg'=>'请输入在岗码')));
            }
            $code=DB::fetch_first("SELECT * FROM " .DB::table('book_code') . " WHERE book_id='$book_id' AND code_value='$book_code'");
            if(empty($code)){
                exit(json_encode(array('msg'=>'在岗码不正确')));
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
            exit(json_encode(array('done'=>'true')));
        }else{
            exit(json_encode(array('msg'=>'网络不稳定，请稍后重试')));
        }
    }
    public function onworksOp(){
        if(submitcheck()) {
            $book_ids = empty($_POST['book_ids']) ? '' : $_POST['book_ids'];
            $book_ids = explode(',', $book_ids);
            $query = DB::query("SELECT * FROM " . DB::table('nurse_book') . " WHERE book_id in ('" . implode("','", $book_ids) . "')");
            while ($value = DB::fetch($query)) {
                if ($value['agent_id'] == $this->agent_id) {
                    $nurse_ids[] = $value['nurse_id'];
                }
            }
            if (empty($nurse_ids)) {
                exit(json_encode(array('msg' => '请至少选择一个家政人员')));
            }
            DB::query("UPDATE " . DB::table('nurse_book') . " SET nurse_state=4 WHERE book_id in ('" . implode("','", $book_ids) . "')");
            DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=4 WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            exit(json_encode(array('done'=>'true')));
        }else{
            exit(json_encode(array('msg'=>'网络不稳定，请稍后重试')));
        }
    }

    public function cancelOp(){
        $cancel_id=empty($_POST['cancel_id']) ? 0 : $_POST['cancel_id'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$cancel_id'");
        if($book['book_state']==0){
            exit(json_encode(array('msg'=>'订单已取消')));
        }
        if($book['book_state']!=10){
            exit(json_encode(array('msg'=>'订单无法取消')));
        }
        DB::query("UPDATE ".DB::table('nurse_book')." SET book_state=0 WHERE book_id='$cancel_id'");

        $red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=1 AND use_id='$cancel_id' AND red_state=1");
        if(!empty($red)) {
            DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
            DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
        }
        DB::query("DELETE FROM ".DB::table('message')." WHERE from_id=$cancel_id");
        $member_coin_discount=DB::fetch_first("SELECT * FROM ".DB::table('member_coin')." WHERE book_id='$cancel_id' AND get_type='discount'");
        if(!empty($member_coin_discount)){
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            $member_coin_discount_coin=intval($member_coin_discount['coin_count'])-intval($member_coin_discount['coin_count'])*2;
            $member_coin_discount_data=array(
                'member_id'=>$member_coin_discount['member_id'],
                'book_id'=>$book['book_id'],
                'coin_count'=>$member_coin_discount_coin,
                'get_type'=>'discount',
                'get_state'=>0,
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_coin', $member_coin_discount_data);
            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_coin_discount_coin WHERE member_id='".$book['member_id']."'");
        }

        exit(json_encode(array('done'=>'true')));

    }

    public function refundOp() {
        if(submitcheck()) {
            $refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
            $refund_amount = empty($_POST['refund_amount']) ? 0 : floatval($_POST['refund_amount']);
            $refund_reason=empty($_POST['refund_reason']) ? '' : $_POST['refund_reason'];
            $refund_state = !in_array($_POST['refund_state'], array('1', '2')) ? '' : $_POST['refund_state'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$refund_id'");
            if(empty($book) || $book['agent_id'] != $this->agent_id) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '20') {
                exit(json_encode(array('msg'=>'预约单不能退款')));
            }
            if($book['refund_state'] ==1 && $book['book_state']==0) {
                exit(json_encode(array('msg'=>'预约单已经退款处理了')));
            }
            if($refund_amount <= 0) {
                exit(json_encode(array('msg'=>'请输入退款金额')));
            }
            if($refund_amount > $book['book_amount']) {
                exit(json_encode(array('msg'=>'退款金额不能大于订单金额')));
            }
            if(empty($refund_state)) {
                exit(json_encode(array('msg'=>'请选择处理方式')));
            }
            if($refund_state == 2) {
                $book_data = array();
                $book_data['refund_state'] = 2;
                $book_data['refund_time'] = time();
                DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
                exit(json_encode(array('done'=>'true')));
            }
            $refund_sn = date('Ymd').random(8, 1);
            if($book['payment_code'] == 'alipay') {
                require_once MALL_ROOT.'/api/alipay/alipay.php';
                $param = array();
                $param['batch_no'] = $refund_sn;
                $param['refund_date'] = date('Y-m-d H:i:s');
                $param['transaction_id'] = $book['transaction_id'];
                $param['refund_amount'] = $refund_amount;
                $requesturl = get_refundurl($param);
                $result = geturlfile($requesturl);
                $result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
                $is_success = $result->is_success;
                if($is_success == 'F') {
                    exit(json_encode(array('msg' => '退款失败')));
                }
            } elseif($book['payment_code'] == 'weixin') {
                require_once MALL_ROOT.'/api/weixin/weixin.php';
                $refundOrder = new Refund_pub();
                $refundOrder->setParameter("transaction_id", $book['transaction_id']);
                $refundOrder->setParameter("out_refund_no", $refund_sn);
                $refundOrder->setParameter("total_fee", $book['book_amount']*100);
                $refundOrder->setParameter("refund_fee", $refund_amount*100);
                $refundOrder->setParameter("op_user_id", MCHID);
                $result = $refundOrder->getResult();
                if($result['return_code'] == 'FAIL' || $result['result_code'] == 'FAIL') {
                    exit(json_encode(array('msg' => '退款失败')));
                }
            } elseif($book['payment_code'] == 'predeposit') {
                $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
                $data = array(
                    'pdl_memberid' => $member['member_id'],
                    'pdl_memberphone' => $member['member_phone'],
                    'pdl_stage' => 'book',
                    'pdl_type' => 1,
                    'pdl_price' => $refund_amount,
                    'pdl_predeposit' => $member['available_predeposit']+$refund_amount,
                    'pdl_desc' => '预约退款，预约单号: '.$book['book_sn'],
                    'pdl_addtime' => time(),
                );
                DB::insert('pd_log', $data);
                DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+".$refund_amount." WHERE member_id='".$member['member_id']."'");
            } else {
                exit(json_encode(array('msg' => '退款失败')));
            }
            $book_data = array();
            $book_data['refund_sn'] = $refund_sn;
            $book_data['refund_amount'] = $refund_amount;
            $book_data['nurse_state']=1;
            $book_data['refund_state']=1;
            $book_data['refund_type']="nurse";
            $book_data['refund_reason']=$refund_reason;
            $book_data['book_state'] = 0;
            $book_data['refund_time'] = time();
            DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
            DB::query("UPDATE ".DB::table('nurse')." SET work_state=0, state_cideci=1 WHERE nurse_id='".$book['nurse_id']."'");
            if($book['payment_code']=='alipay'){
                //财务表插入数据（支出）
                $finance_data=array(
                    'finance_type'=>'refund',
                    'book_id'=>$book['book_id'],
                    'member_id'=>$book['member_id'],
                    'nurse_id'=>$book['nurse_id'],
                    'agent_id'=>$book['agent_id'],
                    'finance_state'=>1,
                    'finance_amount'=>$book['book_amount'],
                    'finance_time'=>time()
                );
                DB::insert('finance',$finance_data);
            }
            //红包
            $red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=1 AND use_id='$refund_id' AND red_state=1");
            if(!empty($red)) {
                DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
                DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
            }
            //团豆豆退还雇主
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            if(!empty($book['member_coin_amount'])){
                $coin_refund_data=array(
                    'member_id'=>$book['member_id'],
                    'book_id'=>$book['book_id'],
                    'coin_count'=>$book['member_coin_amount'],
                    'get_type'=>'payment',
                    'get_state'=>0,
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin',$coin_refund_data);
                //财务表插入数据
                $finance_coin_data=array(
                    'finance_type'=>'coin',
                    'book_id'=>$book['book_id'],
                    'member_id'=>$book['member_id'],
                    'agent_id'=>$book['agent_id'],
                    'nurse_id'=>$book['nurse_id'],
                    'finance_state'=>0,
                    'finance_amount'=>intval($book['member_coin_amount']/100),
                    'finance_time'=>time()
                );
                DB::insert('finance',$finance_coin_data);
            }
            //家政人员收益统计
            $profit_data = array(
                'nurse_id' => $book['nurse_id'],
                'agent_id' => $book['agent_id'],
                'profit_stage' => 'order',
                'profit_type' => 0,
                'profit_amount' => $refund_amount,
                'profit_desc' => '预约退款，预约单号：'.$book['book_sn'],
                'is_freeze' => 0,
                'book_id' => $book['book_id'],
                'book_sn' => $book['book_sn'],
                'add_time' => time(),
            );
            DB::insert('nurse_profit', $profit_data);
            DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount-".$refund_amount.", pool_amount=pool_amount-".$refund_amount." WHERE nurse_id='".$book['nurse_id']."'");
            //养老金收益
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
            $this->setting['first_oldage_rate'] = floatval($this->setting['first_oldage_rate']);
            if($this->setting['first_oldage_rate'] > 0)	{
                $oldage_price = priceformat($refund_amount*$this->setting['first_oldage_rate']);
                $oldage_data = array(
                    'member_id' => $member['member_id'],
                    'oldage_stage' => 'consume',
                    'oldage_type' => 0,
                    'oldage_price' => $oldage_price,
                    'oldage_balance' => $member['oldage_amount']-$oldage_price,
                    'oldage_desc' => '预约退款，扣除'.$oldage_price.'元养老金',
                    'oldage_addtime' => time(),
                );
                DB::insert('oldage', $oldage_data);
                DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount-$oldage_price WHERE member_id='".$member['member_id']."'");
            }
            //家政人员销售统计
            DB::query("UPDATE ".DB::table('nurse')." SET nurse_salenum=nurse_salenum-1 WHERE nurse_id='".$book['nurse_id']."'");
            $date = date('Ymd', $book['add_time']);
            $nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='".$book['nurse_id']."' AND date='$date'");
            if(!empty($nurse_stat)) {
                $nurse_stat_array = array(
                    'salenum' => $nurse_stat['salenum']-1,
                );
                DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$book['nurse_id'], 'date'=>$date));
            }
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
            $message_data=array(
                'member_id'=>$book['member_id'],
                'nurse_id'=>$book['nurse_id'],
                'agent_id'=>$book['agent_id'],
                'book_id'=>$book['book_id'],
                'book_sn'=>$book['book_sn'],
                'message_type'=>'deal',
                'message_content'=>'您预订的编号为'.$book['book_sn'].'的订单，家政人员'.$nurse['nurse_nickname']."已退款",
                'add_time'=>time()
            );
            DB::insert('system_message',$message_data);
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));
        }
    }
}

?>