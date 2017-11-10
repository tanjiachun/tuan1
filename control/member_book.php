<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_bookControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $mpurl = "index.php?act=member_book";
        $wheresql = " WHERE member_id='$this->member_id' AND member_show_state=0 AND book_type=''";
        $state = !in_array($_GET['state'],array('pending', 'payment', 'duty','evaluation','finish', 'cancel')) ? '' : $_GET['state'];
        $mpurl .= "&state=$state";
        if($state == 'pending') {
            $wheresql .= " AND book_state=10";
        } elseif($state == 'payment') {
            $wheresql .= " AND book_state=20";
        } elseif($state == 'duty') {
            $wheresql .= " AND book_state=20 AND nurse_state=4";
        } elseif($state == 'evaluation') {
            $wheresql .= " AND book_state=30 AND comment_state=0";
        } elseif($state == 'finish') {
            $wheresql .= " AND book_state=30";
        } elseif($state == 'cancel') {
            $wheresql .= " AND book_state=0";
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
        $nurse_ids = array();
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('nurse_book').$wheresql." AND book_type!='father' ORDER BY add_time DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $agent_ids[] = $value['agent_id'];
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
        if(!empty($agent_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
            while($value = DB::fetch($query)) {
                $agent_list[$value['agent_id']] = $value;
            }
        }
        if(!empty($nurse_ids)) {
            $nurse_field = 'nurse.*,member.member_token,member.member_id,member.yx_accid';
            $query = DB::query("SELECT $nurse_field FROM ".DB::table('nurse')." as nurse LEFT JOIN ".DB::table('member')." as member ON nurse.member_id=member.member_id WHERE nurse.nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
//        if(!empty($nurse_ids)) {
//            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
//            while($value = DB::fetch($query)) {
//                $value['member_token']=DB::fetch_first("SELECT member_token FROM ".DB::table('member')." WHERE member_id='".$value['member_id']."'");
//                $nurse_list[$value['nurse_id']] = $value;
//            }
//        }
        $multi = multi($count, $perpage, $page, $mpurl);

        $curmodule = 'home';
        $bodyclass = '';
        include(template('member_book'));
    }
    function book_cancelOp() {
        if(submitcheck()) {
            $cancel_id = empty($_POST['cancel_id']) ? 0 : intval($_POST['cancel_id']);
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$cancel_id'");
            $member_coin_amount=intval($book['member_coin_amount']);
            if(empty($book) || $book['member_id'] != $this->member_id) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '10') {
                exit(json_encode(array('msg'=>'预约单不能取消')));
            }
            DB::update('nurse_book', array('book_state'=>0), array('book_id'=>$cancel_id));
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
//            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_coin_amount WHERE member_id='$this->member_id'");
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));
        }
    }
    function book_delOp() {
        if(submitcheck()) {
            $del_id = empty($_POST['del_id']) ? 0 : intval($_POST['del_id']);
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$del_id'");
            if(empty($book) || $book['member_id'] != $this->member_id) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '0') {
                exit(json_encode(array('msg'=>'预约单不能删除')));
            }
            DB::update('nurse_book', array('member_show_state'=>1), array('book_id'=>$del_id));
            DB::query("DELETE FROM ".DB::table('message')." WHERE from_id=$del_id");
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));
        }
    }
    function book_confirmOp() {
        if(submitcheck()) {
            $confirm_id = empty($_POST['confirm_id']) ? 0 : intval($_POST['confirm_id']);
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$confirm_id'");
            if(empty($book) || $book['member_id'] != $this->member_id) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '20') {
                exit(json_encode(array('msg'=>'该状态不能确认已到岗')));
            }
            $confirm_time=time();
            $book_finish_time=$confirm_time+2592000*intval($book['work_duration'])+864008*intval($book['work_duration_days'])+6400*intval($book['work_duration_hours'])+60*intval($book['work_duration_mins']);
            if($book['work_duration']>1){
                $finish_time=$confirm_time+2592000;
            }else{
                $finish_time=$book_finish_time;
            }
            DB::update('nurse_book', array('nurse_state'=>2,'work_time'=>$confirm_time,'finish_time'=>$finish_time,'book_finish_time'=>$book_finish_time), array('book_id'=>$confirm_id));
            DB::update('nurse', array('state_cideci'=>2), array('nurse_id'=>$book['nurse_id']));
            //雇主团豆豆收益
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            $member_add_coin=10;
            $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='sure_work' AND get_time=$now_date");
            if($member_add_coin+$member_get_coin>=40){
                $member_add_coin=40-$member_get_coin;
            }
            $member_coin_data=array(
                'member_id'=>$book['member_id'],
                'book_id'=>$book['book_id'],
                'coin_count'=>$member_add_coin,
                'get_type'=>'sure_work',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_coin', $member_coin_data);
            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));
        }
    }

    function book_refundOp() {
        if(submitcheck()) {
            $refund_id = empty($_POST['refund_id']) ? 0 : intval($_POST['refund_id']);
            $refund_amount = empty($_POST['refund_amount']) ? 0 : floatval($_POST['refund_amount']);
            $refund_reason=empty($_POST['refund_reason']) ? '' : $_POST['refund_reason'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$refund_id'");
            if(empty($book) || $book['member_id'] != $this->member_id) {
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
                $refundOrder->setParameter("total_fee", intval($book['book_amount'])*100);
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
            $book_data['refund_type']="member";
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
            //雇主团豆豆退款
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            //付款时获取团豆豆退还
            $member_coin_payment=DB::fetch_first("SELECT * FROM ".DB::table('member_coin')." WHERE book_id='$refund_id' AND get_type='payment'");
            if(!empty($member_coin_payment)){
                $member_coin_payment_coin=intval($member_coin_payment['coin_count'])-intval($member_coin_payment['coin_count'])*2;
                $member_coin_payment_data=array(
                    'member_id'=>$member_coin_payment['member_id'],
                    'book_id'=>$book['book_id'],
                    'coin_count'=>$member_coin_payment_coin,
                    'get_type'=>'payment',
                    'get_state'=>1,
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin', $member_coin_payment_data);
                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_coin_payment_coin WHERE member_id='".$book['member_id']."'");
            }
            //确认在岗时获取团豆豆退还
            $member_coin_work=DB::fetch_first("SELECT * FROM ".DB::table('member_coin')." WHERE book_id='$refund_id' AND get_type='sure_work'");
            if(!empty($member_coin_work)){
                $member_coin_work_coin=intval($member_coin_work['coin_count'])-intval($member_coin_work['coin_count'])*2;
                $member_coin_work_data=array(
                    'member_id'=>$member_coin_work['member_id'],
                    'book_id'=>$book['book_id'],
                    'coin_count'=>$member_coin_work_coin,
                    'get_type'=>'sure_work',
                    'get_state'=>1,
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin', $member_coin_work_data);
                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_coin_work_coin WHERE member_id='".$book['member_id']."'");
            }
//            $member_coin_discount=DB::fetch_first("SELECT * FROM ".DB::table('member_coin')." WHERE book_id='$refund_id' AND get_type='discount'");
//            if(!empty($member_coin_discount)){
//                $member_coin_discount_coin=intval($member_coin_discount['coin_count'])-intval($member_coin_discount['coin_count'])*2;
//                $member_coin_discount_data=array(
//                    'member_id'=>$member_coin_discount['member_id'],
//                    'book_id'=>$book['book_id'],
//                    'coin_count'=>$member_coin_discount_coin,
//                    'get_type'=>'discount',
//                    'get_state'=>0,
//                    'true_time'=>time(),
//                    'get_time'=>$now_date
//                );
//                DB::insert('member_coin', $member_coin_discount_data);
//                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_coin_discount_coin WHERE member_id='".$book['member_id']."'");
//            }
            //抵扣消费团豆豆退还雇主
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
            //红包
            $red = DB::fetch_first("SELECT * FROM ".DB::table('red')." WHERE use_type=1 AND use_id='$refund_id' AND red_state=1");
            if(!empty($red)) {
                DB::query("UPDATE ".DB::table('red')." SET use_type=0, use_id=0, red_state=0 WHERE red_id='".$red['red_id']."'");
                DB::query("UPDATE ".DB::table('red_template')." SET red_t_used=red_t_used-1 WHERE red_t_id='".$red['red_t_id']."'");
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
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));
        }
    }

    function book_finishOp() {
        if(submitcheck()) {
            $finish_id = empty($_POST['finish_id']) ? 0 : intval($_POST['finish_id']);
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$finish_id'");
            if(empty($book) || $book['member_id'] != $this->member_id) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '20') {
                exit(json_encode(array('msg'=>'预约单不能完成')));
            }
            DB::update('nurse_book', array('book_state'=>30, 'finish_time'=>time()), array('book_id'=>$finish_id));
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
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('msg' => '网路不稳定，请稍候重试')));
        }
    }

    public function book_commentOp() {
        if(submitcheck()) {
            $book_id = empty($_POST['book_id']) ? 0 : intval($_POST['book_id']);
            $star_array = array('1', '2', '3', '4', '5');
            $comment_level = !in_array($_POST['comment_level'], array('good', 'middle', 'bad')) ? '' : $_POST['comment_level'];
            $comment_honest_star = !in_array($_POST['comment_honest_star'], $star_array) ? 1 : intval($_POST['comment_honest_star']);
            $comment_love_star = !in_array($_POST['comment_love_star'], $star_array) ? 1 : intval($_POST['comment_love_star']);
            $tag_ids = empty($_POST['tag_ids']) ? array() : $_POST['tag_ids'];
            $comment_content = empty($_POST['comment_content']) ? '' : $_POST['comment_content'];
            $comment_image = empty($_POST['comment_image']) ? array() : $_POST['comment_image'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
            if(empty($book) || $book['member_id'] != $this->member_id) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '30') {
                exit(json_encode(array('msg'=>'预约单不能评价')));
            }
            if(!empty($book['comment_state'])) {
                exit(json_encode(array('msg'=>'您已经评价过了')));
            }
            if(empty($comment_level)) {
                exit(json_encode(array('msg'=>'请选择满意度评分')));
            }
            if(empty($comment_content)) {
                exit(json_encode(array('msg'=>'请至少写点你的服务感受')));
            }
            $query = DB::query("SELECT * FROM ".DB::table('nurse_tag'));
            while($value = DB::fetch($query)) {
                $tag_list[$value['tag_id']] = $value;
            }
            $comment_tags = array();
            $comment_score = $comment_honest_star+$comment_love_star;
            foreach($tag_ids as $key => $value) {
                if(!empty($tag_list[$value])) {
                    $comment_tags[] = $tag_list[$value]['tag_name'];
                    $comment_score += $tag_list[$value]['tag_score'];
                }
            }
            $data = array(
                'nurse_id' => $book['nurse_id'],
                'member_id' => $book['member_id'],
                'book_id' => $book['book_id'],
                'comment_level' => $comment_level,
                'comment_honest_star' => $comment_honest_star,
                'comment_love_star' => $comment_love_star,
                'comment_tags' => empty($comment_tags) ? '' : serialize($comment_tags),
                'comment_score' => $comment_score,
                'comment_image' => empty($comment_image) ? '' : serialize($comment_image),
                'comment_content' => $comment_content,
                'comment_time' => time(),
            );
            $comment_id = DB::insert('nurse_comment', $data , 1);
            if(!empty($comment_id)) {
                DB::update('nurse_book', array('comment_state'=>1, 'comment_time'=>time()), array('book_id'=>$book['book_id']));
                $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
                $nurse['nurse_tags'] = empty($nurse['nurse_tags']) ? array() : unserialize($nurse['nurse_tags']);
                $nurse_tags = array_merge($nurse['nurse_tags'], $comment_tags);
                $nurse_score = $nurse['nurse_score']+$comment_score;
                $query = DB::query("SELECT * FROM ".DB::table('nurse_grade')." ORDER BY nurse_score DESC");
                while($value = DB::fetch($query)) {
                    if($value['nurse_score'] < $nurse_score) {
                        $grade_id = $value['grade_id'];
                        break;
                    }
                }
                $complaint_state = 0;
                if($comment_level == 'bad') {
                    $complaint_state = 1;
                }
                DB::query("UPDATE ".DB::table('nurse')." SET nurse_tags='".(empty($nurse_tags) ? '' : serialize($nurse_tags))."', grade_id=$grade_id, nurse_score=nurse_score+$comment_score, nurse_commentnum=nurse_commentnum+1, complaint_state=$complaint_state WHERE nurse_id='".$nurse['nurse_id']."'");
                //养老金收益
                $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
                $this->setting['second_oldage_rate'] = floatval($this->setting['second_oldage_rate']);
                if($this->setting['second_oldage_rate'] > 0) {
                    $oldage_price = priceformat($this->setting['second_oldage_rate']);
                    $oldage_data = array(
                        'member_id' => $member['member_id'],
                        'oldage_stage' => 'comment',
                        'oldage_type' => 1,
                        'oldage_price' => $oldage_price,
                        'oldage_balance' => $member['oldage_amount']+$oldage_price,
                        'oldage_desc' => '您评价了家政人员看护'.$nurse['nurse_name'].'，获得'.$oldage_price.'元养老金',
                        'oldage_addtime' => time(),
                    );
                    DB::insert('oldage', $oldage_data);
                    DB::query("UPDATE ".DB::table('member')." SET oldage_amount=oldage_amount+$oldage_price WHERE member_id='".$member['member_id']."'");
                }
                exit(json_encode(array('done'=>'true')));
            } else {
                exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
            }
        } else {
            $book_id = empty($_GET['book_id']) ? 0 : intval($_GET['book_id']);
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
            $query = DB::query("SELECT * FROM ".DB::table('nurse_tag'));
            while($value = DB::fetch($query)) {
                $tag_list[] = $value;
            }
            $curmodule = 'profile';
            $bodyclass = 'gray-bg';
            include(template('order_book_comment'));
        }
    }

    public function dismissOp(){
        if(empty($this->member_id)) {
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $member_address=DB::fetch_first("SELECT * FROM ".DB::table('member_address')." WHERE member_id='$this->member_id' AND choose_state=1");
        $dismiss_ids=empty($_GET['dismiss_ids']) ? '' : $_GET['dismiss_ids'];
        $pay_ids=$dismiss_ids;
        $agent_id=empty($_GET['agent_id']) ? '' : $_GET['agent_id'];
        $dismiss_ids = explode(',', $dismiss_ids);
        $total_price=DB::result_first("SELECT SUM(deposit_amount) FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $dismiss_ids)."')");
        $query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $dismiss_ids)."')");
        while($value = DB::fetch($query)) {
            $agent_ids[]=$value['agent_id'];
            $nurse_ids[]=$value['nurse_id'];
            $book_list[]=$value;
        }
        if(!empty($agent_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
            while($value = DB::fetch($query)) {
                $agent_list[$value['agent_id']] = $value;
            }
        }
        if(!empty($nurse_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        $query=DB::query("SELECT * FROM ".DB::table("member_address")." WHERE member_id='$this->member_id' AND show_state=0 ORDER BY address_time DESC");
        while($value=DB::fetch($query)){
            $address_list[]=$value;
        }
        $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
        while($value = DB::fetch($query)) {
            $province_list[] = $value;
        }
        if(!empty($member_provinceid)) {
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_provinceid' ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                $member_city_list[] = $value;
            }
        }
        if(!empty($member_cityid)) {
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_cityid' ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                $member_area_list[] = $value;
            }
        }

        $curmodule = 'home';
        $bodyclass = '';
        include(template('place_order_dismiss'));
    }

    public function orderOp(){
        $book_sub_order=empty($_POST['book_sub_order']) ? '' : $_POST['book_sub_order'];
        $agent_id=empty($_POST['agent_id']) ?  0 : intval($_POST['agent_id']);
        $book_address=empty($_POST['book_address']) ? '' : $_POST['book_address'];
        $service_member_phone=empty($_POST['service_member_phone']) ? '' : $_POST['service_member_phone'];
        $service_member_name=empty($_POST['service_member_name']) ? '' : $_POST['service_member_name'];
        $book_message=empty($_POST['book_message']) ? '' : $_POST['book_message'];
        $deposit_amount=empty($_POST['deposit_amount']) ?  0 : intval($_POST['deposit_amount']);

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
        $invoice_content=array(
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
        $invoice_id=DB::insert('invoice',$invoice_content,1);
        $book_sn = makesn(8);
        $out_sn = date('YmdHis').random(18);
        $data=array(
            'book_sn'=>$book_sn,
            'out_sn'=>$out_sn,
            'book_type'=>'father',
            'book_sub_order'=>$book_sub_order,
            'member_id' => $this->member_id,
            'agent_id'=>$agent_id,
            'member_phone' => $this->member['member_phone'],
            'service_address'=>$book_address,
            'service_member_phone'=>$service_member_phone,
            'service_member_name'=>$service_member_name,
            'deposit_amount'=>$deposit_amount,
            'book_amount'=>$deposit_amount,
            'book_message'=>$book_message,
            'invoice_type'=>$invoice_type,
            'invoice_id'=>$invoice_id,
            'book_state'=>10,
            'add_time'=>time()
        );
        $book_id = DB::insert('nurse_book', $data, 1);
        if(!empty($book_id)){
            exit(json_encode(array('done'=>'true', 'book_sn'=>$book_sn)));
        }else{
            exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
        }
    }

    //订单续费
    public function book_renewOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了','index.php?act=login','info');
        }
        $book_id=empty($_POST['book_id']) ? 0 : $_POST['book_id'];
        $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id='$book_id'");
        if(empty($book)){
            exit(json_encode(array('msg'=>'订单不存在')));
        }
        if($book['work_duration']<=1 || $book['pay_count']+1>=$book['work_duration'] || $book['finish_time']>=$book['book_finish_time'] || $book['book_state']!=20){
            exit(json_encode(array('msg'=>'订单无法续费')));
        }
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(intval($member['available_predeposit'])<intval($book['nurse_price'])){
            exit(json_encode(array('msg'=>'可用余额不足，请进入我的钱包充值')));
        }
        //个人账单
        $data = array(
            'pdl_memberid' => $member['member_id'],
            'pdl_memberphone' => $member['member_phone'],
            'pdl_stage' => 'book',
            'pdl_type' => 0,
            'pdl_price' => $book['nurse_price'],
            'pdl_predeposit' => $member['available_predeposit']-$book['nurse_price'],
            'pdl_desc' => '订单续费，单号: '.$book['book_sn'],
            'pdl_addtime' => time(),
        );
        DB::insert('pd_log', $data);
        DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit-".$book['nurse_price']." WHERE member_id='$this->member_id'");
        //订单状态改变
        DB::query("UPDATE ".DB::table('nurse_book')." SET pay_count=pay_count+1,finish_time=finish_time+2592000 WHERE book_id='$book_id'");
        //家政人员收益统计
        $profit_data = array(
            'nurse_id' => $book['nurse_id'],
            'agent_id' => $book['agent_id'],
            'profit_stage' => 'order',
            'profit_type' => 1,
            'profit_amount' => $book['nurse_price'],
            'profit_desc' => '续费订单，单号：'.$book['book_sn'],
            'is_freeze' => 1,
            'book_id' => $book['book_id'],
            'book_sn' => $book['book_sn'],
            'add_time' => time(),
        );
        DB::insert('nurse_profit', $profit_data);
        DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$book['nurse_price'].", pool_amount=pool_amount+".$book['nurse_price']." WHERE nurse_id='".$book['nurse_id']."'");
        //机构收益
        if(!empty($book['agent_id'])){
            $agent_profit_data=array(
                'nurse_id' => $book['nurse_id'],
                'agent_id' => $book['agent_id'],
                'profit_stage' => 'order',
                'profit_type' => 1,
                'profit_amount' => $book['nurse_price'],
                'profit_desc' => '续费订单，单号：'.$book['book_sn'],
                'is_freeze' => 1,
                'book_id' => $book['book_id'],
                'book_sn' => $book['book_sn'],
                'add_time' => time(),
            );
            DB::insert('agent_profit', $agent_profit_data);
        }
        //雇主团豆豆收益
        $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
        $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
        $member_add_coin=50;
        $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$member['member_id']."' AND get_type='payment' AND get_time=$now_date");
        if($member_add_coin+$member_get_coin>=200){
            $member_add_coin=200-$member_get_coin;
        }
        $member_coin_data=array(
            'member_id'=>$member['member_id'],
            'book_id'=>$book['book_id'],
            'coin_count'=>$member_add_coin,
            'get_type'=>'payment',
            'true_time'=>time(),
            'get_time'=>$now_date
        );
        DB::insert('member_coin', $member_coin_data);
        DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$member['member_id']."'");
        exit(json_encode(array('done'=>'true')));
    }

}


?>