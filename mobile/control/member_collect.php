<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_collectControl extends BaseMobileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            exit(json_encode(array('code'=>1,'msg'=>'您还没有登录')));
        }
        $member_token=empty($_GET['token']) ? '' : $_GET['token'];
//        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_token='$member_token'");
        if(empty($member)){
            exit(json_encode(array('code'=>1,'msg'=>'用户不存在')));
        }
        $member_id=$member['member_id'];
        $query = DB::query("SELECT * FROM ".DB::table('member_favourite')." WHERE member_id='$member_id' AND favourite_type='favourite' ORDER BY agent_id DESC");
        while($value = DB::fetch($query)) {
            $agent_ids[]=$value['agent_id'];
            $nurse_ids[]=$value['nurse_id'];
            $favourite_list[]=$value;
        }
        if(!empty($agent_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
            while($value = DB::fetch($query)) {
                $agent_list[$value['agent_id']] = $value;
            }
        }
        if(!empty($nurse_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $value['car_price_list']=empty($value['car_price_list']) ? array(): unserialize($value['car_price_list']);
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        foreach($favourite_list as $key => $value) {
            $favourite_list[$key]['agent_name'] = $agent_list[$value['agent_id']]['agent_name'];
            $favourite_list[$key]['nurse_nickname'] = $nurse_list[$value['nurse_id']]['nurse_nickname'];
            $favourite_list[$key]['nurse_type'] = $nurse_list[$value['nurse_id']]['nurse_type'];
            $favourite_list[$key]['nurse_special_service'] = $nurse_list[$value['nurse_id']]['nurse_special_service'];
            $favourite_list[$key]['member_phone'] = $nurse_list[$value['nurse_id']]['member_phone'];
            $favourite_list[$key]['service_price'] = $nurse_list[$value['nurse_id']]['service_price'];
            $favourite_list[$key]['nurse_price'] = $nurse_list[$value['nurse_id']]['nurse_price'];
            $favourite_list[$key]['nurse_image'] = $nurse_list[$value['nurse_id']]['nurse_image'];
        }
        $data['favourite_list']=$favourite_list;
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$data)));
    }

}

?>