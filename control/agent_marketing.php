<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_marketingControl extends BaseAgentControl {
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
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_marketing'));
    }
    function orderOp(){
        $book_deposit=empty($_POST['deposit_amount']) ? 0 : $_POST['deposit_amount'];
        if(empty($book_deposit)){
            exit(json_encode(array('id'=>'system','msg'=>'保证金不能为0')));
        }
        if(empty($this->member_id)) {
            exit(json_encode(array('done'=>'login')));
        }
        if(empty($this->agent_id)){
            exit(json_encode(array('id'=>'system','msg'=>'机构为空')));
        }
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$this->agent_id'");
//        exit(json_encode(array($agent['agent_deposit'])));
        $agent_deposit=intval($agent['agent_deposit']);
        if(!empty($agent_deposit)){
            exit(json_encode(array('id'=>'system','msg'=>'您已经缴纳过保证金')));
        }
//        $refund=DB::fetch_first("SELECT * FROM ".DB::table('refund')." WHERE agent_id='$this->agent_id' AND refund_state=3");
//        if(!empty($refund)){
//            exit(json_encode(array('id'=>'system','msg'=>'正在退款，无法继续缴纳')));
//        }
        $book_sn = makesn(8);
        $out_sn = date('YmdHis').random(18);
        $data=array(
            'book_sn'=>$book_sn,
            'out_sn'=>$out_sn,
            'member_id' => $this->member_id,
            'member_phone'=>$this->member['member_phone'],
            'agent_id'=>$this->agent_id,
            'deposit_amount'=>$book_deposit,
            'book_amount'=>$book_deposit,
            'book_state'=>10,
            'show_state'=>1,
            'member_show_state'=>1,
            'add_time'=>time()
        );
        $book_id = DB::insert('nurse_book', $data, 1);
        if(!empty($book_id)){
            exit(json_encode(array('done'=>'true', 'book_sn'=>$book_sn)));
        }else{
            exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
        }
    }
    function cancelOp(){
        $book_sn=empty($GET['book_sn']) ? '' : $GET['book_sn'];
        DB::query("UPDATE ".DB::table('nurse_book')." book_state=0 WHERE book_sn='$book_sn'");
        exit(json_encode(array('done'=>'true')));
    }
    public function paymentOp() {
        if(submitcheck()) {
            if(empty($this->member_id)) {
                exit(json_encode(array('done'=>'login')));
            }
            $book_sn = empty($_POST['book_sn']) ? '' : $_POST['book_sn'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
            if(empty($book) || $book['member_id'] != $this->member_id) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '10') {
                exit(json_encode(array('msg'=>'预约单还不能支付')));
            }
            $payment_code = !in_array($_POST['payment_code'], array('alipay', 'weixin', 'predeposit')) ? 'alipay' : $_POST['payment_code'];
            if($payment_code == 'predeposit') {
                $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$book['agent_id']."'");

                $income = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='".$agent['agent_id']."' AND profit_type=1 AND is_freeze=0");
                $expend = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='".$agent['agent_id']."' AND profit_type=0 AND is_freeze=0");
                $this->agent['available_amount'] = $income-$expend;
                if($this->agent['available_amount'] < $book['book_amount']) {
                    exit(json_encode(array('msg'=>'余额不足，请选择其他支付方式')));
                }
//                if($agent['agent_bail'] < $book['book_amount']) {
//                    exit(json_encode(array('msg'=>'余额不足，请选择其他支付方式')));
//                }
//                $data = array(
//                    'pdl_memberid' => $this->member_id,
//                    'pdl_agentid'=>$agent['agent_id'],
//                    'pdl_stage' => 'book',
//                    'pdl_type' => 0,
//                    'pdl_price' => $book['book_amount'],
//                    'pdl_predeposit' => $agent['agent_bail']-$book['book_amount'],
//                    'pdl_desc' => '缴纳保证金，单号: '.$book['book_sn'],
//                    'pdl_addtime' => time(),
//                );
//                DB::insert('pd_log', $data);
//                DB::query("UPDATE ".DB::table('agent')." SET agent_bail=agent_bail-".$book['book_amount']." WHERE agent_id='".$agent['agent_id']."'");
                DB::query("UPDATE ".DB::table('agent')." SET agent_deposit=agent_deposit+".$book['book_amount']." WHERE agent_id='".$agent['agent_id']."'");
                $book_data = array();
                $book_data['payment_name'] = '余额支付';
                $book_data['payment_code'] = 'predeposit';
                $book_data['book_state'] = 20;
                $book_data['payment_time'] = time();
                DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
                $agent_profit_data=array(
                    'nurse_id' => $book['nurse_id'],
                    'agent_id' => $book['agent_id'],
                    'profit_stage' => 'deposit',
                    'profit_type' => 0,
                    'profit_amount' => $book['book_amount'],
                    'profit_desc' => '缴纳保证金，单号：'.$book['book_sn'],
                    'is_freeze' => 0,
                    'book_id' => $book['book_id'],
                    'book_sn' => $book['book_sn'],
                    'add_time' => time(),
                );
                DB::insert('agent_profit', $agent_profit_data);

                $nurse_profit_data=array(
                    'agent_id'=>$book['agent_id'],
                    'profit_stage'=>'deposit',
                    'profit_type'=>0,
                    'profit_amount'=>$book['book_amount'],
                    'profit_desc' => '缴纳保证金，单号：'.$book['book_sn'],
                    'is_freeze' => 0,
                    'book_id' => $book['book_id'],
                    'book_sn' => $book['book_sn'],
                    'add_time' => time(),
                );
                DB::insert('nurse_profit', $nurse_profit_data);
            } else {
                DB::update('nurse_book', array('payment_code'=>$payment_code), array('book_id'=>$book['book_id']));
            }
            exit(json_encode(array('done'=>'true', 'book_sn'=>$book_sn)));
        } else {
            if(empty($this->member_id)) {
                $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
            }
            $book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
            if(empty($book) || $book['member_id'] != $this->member_id) {
                $this->showmessage('预约单不存在', 'index.php?act=agent_marketing', 'error');
            }
            if($book['book_state'] != '10') {
                $this->showmessage('预约单还不能支付', 'index.php?act=agent_marketing', 'info');
            }
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
            $curmodule = 'home';
            $bodyclass = '';
            include(template('agent_payment'));
        }
    }

    public function alipayOp() {
        require_once MALL_ROOT.'/api/alipay/alipay.php';
        $notifydata = trade_notifycheck();
        if($notifydata['validator']) {
            $out_sn = $notifydata['order_no'];
            $transaction_id = $notifydata['trade_no'];
            $book_amount = $notifydata['price'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE out_sn='$out_sn'");
            if($book['book_state'] == 10) {
                if(floatval($book_amount) == floatval($book['book_amount'])) {
                    $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
                    $book_data = array();
                    $book_data['transaction_id'] = $transaction_id;
                    $book_data['payment_name'] = '支付宝';
                    $book_data['payment_code'] = 'alipay';
                    $book_data['book_state'] = 20;
                    $book_data['payment_time'] = time();
                    DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
                    DB::query("UPDATE ".DB::table('agent')." SET agent_deposit=agent_deposit+$book_amount WHERE agent_id='".$book['agent_id']."'");
                    //财务表插入数据（收入）
                    $finance_data=array(
                        'finance_type'=>'bail',
                        'agent_id'=>$book['agent_id'],
                        'finance_state'=>0,
                        'finance_amount'=>$book['deposit_amount'],
                        'finance_time'=>time()
                    );
                    DB::insert('finance',$finance_data);
                    $this->showmessage('预约付款成功', 'index.php?act=marketing');
                } else {
                    $this->showmessage('预约付款失败', 'index.php?act=marketing', 'error');
                }
            } else {
                $this->showmessage('预约付款成功', 'index.php?act=marketing');
            }
        } else {
            $this->showmessage('预约付款失败', 'index.php?act=marketing', 'error');
        }
    }
    public function weixinOp(){
        $book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
        if(empty($book) || $book['member_id'] != $this->member_id) {
            $this->showmessage('预约单不存在', 'index.php?act=agent_marketing', 'error');
        }
        if($book['book_state'] == '20') {
            $this->showmessage('预约付款成功', 'index.php?act=agent_marketing');
        }
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$book['nurse_id']."'");
        require_once MALL_ROOT.'/api/weixin/weixin.php';
        $unifiedOrder = new OrderQuery_pub();
        $unifiedOrder->setParameter("out_trade_no", $book['out_sn']);
        $result = $unifiedOrder->postXml();
        $result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        exit(json_encode(array('data'=>$result,'money'=>intval($book['book_amount']))));


    }
    public function weixin_bookOp(){
        $book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
        $transaction_id = empty($_GET['transaction_id']) ? '' : $_GET['transaction_id'];
        $total_fee = empty($_GET['total_fee']) ? '' : $_GET['total_fee'];
        $diff_amount = $total_fee-$book['book_amount']*100;
        if($book['book_state'] == 10) {
            if($diff_amount >= -1 && $diff_amount<=1) {
                $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$book['member_id']."'");
                $book_data = array();
                $book_data['transaction_id'] = $transaction_id;
                $book_data['payment_name'] = '微信';
                $book_data['payment_code'] = 'weixin';
                $book_data['book_state'] = 20;
                $book_data['payment_time'] = time();
                DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
                DB::query("UPDATE ".DB::table('agent')." SET agent_deposit=agent_deposit+".$book['book_amount']." WHERE agent_id='".$book['agent_id']."'");

                exit(json_encode(array('done'=>'true')));
            } else {
                $this->showmessage('预约付款失败', 'index.php?act=agent_marketing', 'error');
            }
        } else {
            exit(json_encode(array('done'=>'true')));
        }
    }

//	public function weixinOp() {
//		$book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
//		$book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
//		if($book['book_state'] == 20) {
//            DB::query("UPDATE ".DB::table('agent')." SET agent_deposit=agent_deposit+".$book['book_amount']." WHERE agent_id='".$book['agent_id']."'");
//            $this->showmessage('预约付款成功', 'index.php?act=agent_marketing');
//		} else {
//			$this->showmessage('预约付款失败', 'index.php?act=agent_marketing', 'error');
//		}
//	}

    public function checkstateOp() {
        $book_sn = empty($_GET['book_sn']) ? '' : $_GET['book_sn'];
        $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn'");
        if($book['book_state'] == 20) {
            exit(json_encode(array('done'=>'true')));
        } else {
            exit(json_encode(array('done'=>'false')));
        }
    }
    public function deposit_refundOp(){
        $refund_amount=empty($_POST['refund_amount']) ? 0 : $_POST['refund_amount'];
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        if(empty($this->agent_id)){
            exit(json_encode(array('msg'=>'家政机构不存在')));
        }
        if(empty($refund_amount)){
            exit(json_encode(array('msg'=>'无可退保证金')));
        }
        $refund=DB::fetch_first("SELECT * FROM ".DB::table('refund')." WHERE agent_id='$this->agent_id' AND refund_state=3");
        if(!empty($refund)){
            exit(json_encode(array('msg'=>'正在退款')));
        }
        $refund_data=array(
          'agent_id'=>$this->agent_id,
          'refund_amount'=>$refund_amount,
          'refund_type'=>'deposit',
          'refund_state'=>3,
          'add_time'=>time()
        );
        $refund_id = DB::insert('refund', $refund_data, 1);
        if(!empty($refund_id)){
            DB::query("UPDATE ".DB::table('agent')." SET agent_deposit=agent_deposit-$refund_amount WHERE agent_id='$this->agent_id'");
            exit(json_encode(array('done'=>'true')));
        }else{
            exit(json_encode(array('msg'=>'网络不稳定，请稍后重试')));
        }
    }

}

?>