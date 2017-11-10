<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class predepositControl extends BaseMobileControl {

    /**
     * 个人账户资金API接口
     * 请求类型：GET
     * 接口地址：/mobile.php?act=predeposit
     */
    public function indexOp(){
        // 检测登陆状态
        $this->check_authority();

        $data = array(
            'available_predeposit' => $this->member['available_predeposit'],
            'member_coin' => $this->member['member_coin'],
        );
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$data)));
    }

    /**
     * 个人账户资金明细API接口
     * 请求类型：GET
     * 接口地址：/mobile.php?act=predeposit&op=fund_detail
     */
    public function fund_detailOp(){
        // 检测登陆状态
        $this->check_authority();

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE pdl_memberid = '$this->member_id'";
        $query = DB::query("SELECT * FROM ".DB::table('pd_log').$wheresql." ORDER BY pdl_addtime DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            if($value['pdl_type'] == '1') {
                $value['mark'] = '+';
            } else {
                $value['mark'] = '-';
            }
            if(!empty($value['pdl_addtime'])){
                $value['pdl_addtime'] = date('Y-m-d', $value['pdl_addtime']);
            }
            $pd_list[] = $value;
        }
        if(count($pd_list) >0 ){
            exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$pd_list)));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'没有更多数据了！','data'=>(object)array())));
        }

    }

    /**
     * 个人账户团豆豆明细
     * 请求类型：GET
     * 接口地址：/mobile.php?act=predeposit&op=member_coin_detail
     */
    public function member_coin_detailOp(){
        // 检测登陆状态
        $this->check_authority();

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE member_id='$this->member_id'";
//        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_coin').$wheresql." AND coin_count!=0");
        $query = DB::query("SELECT * FROM ".DB::table('member_coin').$wheresql." AND coin_count!=0 ORDER BY true_time DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            if($value['get_state'] ==0) {
                $value['mark'] = '+';
            } else {
                $value['mark'] = '-';
            }
            $value['true_time'] = date('Y-m-d', $value['true_time']);
            unset($value['get_time']); // 内部计算使用不需要暴露给api客户端
            $coin_list[] = $value;
        }
        if(count($coin_list) >0 ){
            exit(json_encode(array('code'=>0,'msg'=>'操作成功','data' => $coin_list)));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'没有更多数据了！','data'=>(object)array())));
        }

    }

    /**
     * 家政人员资金信息
     * 请求类型：GET
     * 接口地址：/mobile.php?act=predeposit&op=nurse
     */
    public function nurseOp(){
        // 检测登陆状态
        $this->check_authority();

        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id = '$this->member_id'");

        if(!empty($nurse['agent_id'])){
            exit(json_encode(array('code'=>1,'您属于机构，没有个人财务中心')));
        }

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1) * $perpage;
        $wheresql = " WHERE nurse_id='".$nurse['nurse_id']."' AND agent_id = 0";
        $plat_amount = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit').$wheresql." AND profit_type=1");
        $plat_refund = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit').$wheresql." AND profit_type=0");
        $pool_amount = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit').$wheresql." AND is_freeze=1");
        $income = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit').$wheresql." AND profit_type=1 AND is_freeze=0");
        $expend = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit').$wheresql." AND profit_type=0 AND is_freeze=0");
        $available_amount = $income-$expend;

        $plat_amount = empty($plat_amount) ? 0 : $plat_amount;
        $plat_refund = empty($plat_refund) ? 0 : $plat_refund;
        $pool_amount = empty($pool_amount) ? 0 : $pool_amount;
        $data = array(
            'plat_amount'=>$plat_amount,
            'plat_refund'=>$plat_refund,
            'pool_amount'=>$pool_amount,
            'available_amount'=>$available_amount,
        );

        exit(json_encode(array('code' => 0, 'msg' => '操作成功', 'data' => $data)));
    }

    /**
     * 家政人员资金明细接口
     * 请求类型：GET
     * 接口地址：/mobile.php?act=predeposit&op=nurse_fund_detail
     */
    public function nurse_fund_detailOp(){
        // 检测登陆状态
        $this->check_authority();

        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id = '$this->member_id'");

        if(!empty($nurse['agent_id'])){
            exit(json_encode(array('code'=>1,'您属于机构，没有个人财务中心')));
        }
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1) * $perpage;
        $wheresql = " WHERE nurse_id = '".$nurse['nurse_id']."' AND agent_id = '0'";
        $query = DB::query("SELECT * FROM ".DB::table('nurse_profit').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");

        while($value = DB::fetch($query)) {
            if($value['profit_type'] == '1') {
                $value['mark'] = '+';
            } else {
                $value['mark'] = '-';
            }
            $nurse_profit_list[] = $value;
        }
        exit(json_encode(array('code' => 0, 'msg' => '操作成功', 'data' => $nurse_profit_list)));
    }

    /**
     * 机构资金信息
     * 请求类型：GET
     * 接口地址：/mobile.php?act=predeposit&op=agent
     */
    public function agentOp(){
        // 检测登陆状态
        $this->check_authority();

        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id = '$this->member_id'");
        if(empty($agent)){
            exit(json_encode(array('code' => 1, 'msg'=> '您还没有开通机构权限，暂时无法使用机构相关服务！', 'data'=> (object)array())));
        }
        $agent_id = $agent['agent_id'];

        // 总收益
        $agent_plat_amount = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$agent_id' AND profit_type=1");
        // 退款
        $agent_plat_refund = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$agent_id' AND profit_type=0");
        // 冻结
        $agent_pool_amount = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$agent_id' AND is_freeze=1");
        $income = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$agent_id' AND profit_type=1 AND is_freeze=0");
        $expend = DB::result_first("SELECT SUM(profit_amount) FROM ".DB::table('nurse_profit')." WHERE agent_id='$agent_id' AND profit_type=0 AND is_freeze=0");
        // 可用金额
        $agent_available_amount = (string) ($income - $expend);


        $data = array(
          'agent_plat_amount' =>  $this->get_num_var($agent_plat_amount),
          'agent_plat_refund' =>  $this->get_num_var($agent_plat_refund),
          'agent_pool_amount' =>  $this->get_num_var($agent_pool_amount),
          'agent_available_amount' =>  $this->get_num_var($agent_available_amount),
        );

        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data' => $data)));
    }

    /**
     * 机构资金详情
     * 请求类型：GET
     * 接口地址：/mobile.php?act=predeposit&op=agent_fund_detail
     */
    public function agent_fund_detailOp(){
        // 检测登陆状态
        $this->check_authority();

        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id = '$this->member_id'");
        if(empty($agent)){
            exit(json_encode(array('code' => 1, 'msg'=> '您还没有开通机构权限，暂时无法使用机构相关服务！', 'data'=> (object)array())));
        }
        $agent_id = $agent['agent_id'];

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE agent_id='$agent_id'";
        $query = DB::query("SELECT * FROM ".DB::table('nurse_profit').$wheresql." ORDER BY add_time DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $nurse_ids[] = $value['nurse_id'];
            if($value['profit_type'] == '1') {
                $value['mark'] = '+';
            } else {
                $value['mark'] = '-';
            }
            $agent_profit_list[] = $value;
        }
        if(!empty($nurse_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        foreach($agent_profit_list as $key => $value) {
            $agent_profit_list[$key]['nurse_name'] = $nurse_list[$value['nurse_id']]['nurse_name'];
        }
        if(count($agent_profit_list) > 0){
            exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$agent_profit_list)));
        }else{
            exit(json_encode(array('code'=>1, 'msg' => '没有更多数据了!', 'data' => (object)array())));
        }

    }

}
?>