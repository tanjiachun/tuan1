<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_showControl extends BaseHomeControl {
    public function indexOp() {
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $all_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$agent_id'");
        $finish_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$agent_id' AND book_state=30");
        $good_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$agent_id' AND comment_state=1");
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' AND nurse_state=1");
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
        $success=$finish_count/$all_count*100;
        $good=$good_count/$finish_count*100;
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$agent['member_id']."'");
        if(empty($agent)) {
            $this->showmessage('家政机构不存在', 'index.php?act=index', 'error');
        }
        $agent['agent_qa_image'] = empty($agent['agent_qa_image'] ) ? array() : unserialize($agent['agent_qa_image'] );
        $agent['agent_service_image'] = empty($agent['agent_service_image'] ) ? array() : unserialize($agent['agent_service_image'] );

        $agent_grade = DB::fetch_first("SELECT * FROM ".DB::table('agent_grade')." WHERE agent_score<=".$agent['agent_score']." ORDER BY agent_score DESC");
        $query=DB::query("SELECT * FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' ORDER BY nurse_score LIMIT 0,5");
        while($value = DB::fetch($query)) {
            $nurse_ids = $value['nurse_id'];
            $counts=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='$nurse_ids'");
//            $good_counts=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='$nurse_ids' AND comment_score=10");
//            $goods=$good_counts/$counts*100;
            $value['counts']=$counts;
            $nurse_list[]=$value;
        }
        $query=DB::query("SELECT * FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' ORDER BY nurse_score LIMIT 0,5");
        while($value = DB::fetch($query)) {
            $nurse_ids = $value['nurse_id'];
            $counts=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment')." WHERE nurse_id='$nurse_ids'");
            $value['counts']=$counts;
            $nurse_good_list[]=$value;
        }
        $query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
        while($value = DB::fetch($query)) {
            $grade_list[$value['grade_id']] = $value;
        }
        DB::query("UPDATE ".DB::table('agent')." SET agent_viewnum=agent_viewnum+1 WHERE agent_id='".$agent['agent_id']."'");
            $curmodule = 'home';
            $bodyclass = '';
            include(template('agent_show'));
        }

}

?>