<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_setControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member['card_predeposit']." ORDER BY card_predeposit DESC");
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
        $wheresql = " WHERE member_id='$this->member_id'";
        $state = in_array($_GET['state'], array('show', 'rand')) ? $_GET['state'] : 'show';
        if($state=='show'){
            $wheresql .= " ORDER BY footprint_count DESC";
        }elseif($state=='rand'){
            $wheresql .= " ORDER BY rand()";
        }
        $query = DB::query("SELECT * FROM ".DB::table('member_footprint').$wheresql." LIMIT 0,8");
        while($value = DB::fetch($query)) {
            $nurse_ids[] = $value['nurse_id'];
            $footprint_list[] = $value;
        }
        if(!empty($nurse_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        $curmodule = 'home';
        $bodyclass = 'gray-bg';
        include(template('member_set'));
    }
}

?>