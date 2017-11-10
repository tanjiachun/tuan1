<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
header('Content-type: application/json');
class signControl extends BaseMobileControl {
    public function indexOp(){
        // 检测用户登录权限
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(empty($member)){
            exit(json_encode(array('code'=>1,'msg'=>'用户不存在')));
        }
        $BeginDate = date('Y-m-01', strtotime(date("Y-m-d")));
        $now_date = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $sign_date = $now_date - 86400;
        if($member['sign_time'] == $sign_date){
            $sign_count = intval($member['sign_count']) + 1;
        }else{
            $sign_count = 1;
        }
        if($sign_count >= 7){
            $get_coin = 35;
        }else{
            $get_coin=intval($sign_count) * 5;
        }
        if($member['sign_count']>=7){
            $get_tomorrow_coin=35;
        }else{
            $get_tomorrow_coin=(intval($member['sign_count'])+1)*5;
        }
        if($member['sign_time'] == $now_date){
            exit(json_encode(array('code'=>0,'msg'=>'今日已签到','data'=>array('member_coin'=>$member['member_coin'],'get_tomorrow_coin'=>$get_tomorrow_coin,'sign_days'=>$member['sign_count']))));
        }else{
            exit(json_encode(array('code'=>0,'msg'=>'今日还未签到','data'=>array('member_coin'=>$member['member_coin'],'get_coin'=>$get_coin,'sign_days'=>$member['sign_count']))));
        }
    }
    public function signOp(){
        // 检测用户登录权限
        $this->check_authority();
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(empty($member)){
            exit(json_encode(array('code'=>1,'msg'=>'用户不存在')));
        }
        $BeginDate = date('Y-m-01', strtotime(date("Y-m-d")));
        $now_date = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

        if($member['sign_time'] == $now_date){
            exit(json_encode(array('code'=>1,'msg'=>'今日已签到')));
        }
        $sign_data = array(
            'member_id' => $member['member_id'],
            'sign_true_time' => time(),
            'sign_time' => $now_date,
            'sign_ip' => getip()
        );
        DB::insert('signin',$sign_data);
        $sign_date = $now_date - 86400;
        if($member['sign_time'] == $sign_date){
            $sign_count = intval($member['sign_count']) + 1;
        }else{
            $sign_count = 1;
        }
        if($sign_count >= 7){
            $get_coin = 35;
            $get_tomorrow_coin=35;
        }else{
            $get_coin=intval($sign_count) * 5;
            $get_tomorrow_coin=(intval($sign_count) + 1 ) * 5;
        }
        DB::query("UPDATE ".DB::table('member')." SET sign_count=$sign_count,member_coin=member_coin+$get_coin,sign_time=$now_date WHERE member_id='$this->member_id'");
        $coin_data=array(
            'member_id' => $member['member_id'],
            'coin_count' => $get_coin,
            'get_type' => 'sign',
            'get_state' => 0,
            'true_time' => time(),
            'get_time' => $now_date
        );
        DB::insert('member_coin',$coin_data);
        exit(json_encode(array('code'=>0,'msg'=>'签到成功','data'=>array('get_coin'=>$get_coin,'member_coin'=>$member['member_coin']+$get_coin,'get_tomorrow_coin'=>$get_tomorrow_coin,'sign_days'=>$member['sign_count']+1))));
    }
}
?>