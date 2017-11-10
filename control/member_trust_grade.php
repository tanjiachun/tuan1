<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_trust_gradeControl extends BaseProfileControl {
    public function indexOp(){
        $member_id=empty($_GET['member_id']) ? 0 : intval($_GET['member_id']);
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
        if(empty($member)){
            $this->showmessage('雇主不存在', 'index.php?act=agent_book', 'error');
        }
        $member_cityid=$member['member_cityid'];
        $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_cityid'");
        $card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member['card_predeposit']." ORDER BY card_predeposit DESC");
        $wheresql = " WHERE member_id='$member_id'";
        $state = in_array($_GET['state'], array('show', 'one_mouth','six_mouth','one_year','one_year_ago')) ? $_GET['state'] : 'show';
        $deltime1=strtotime("-30days");
        $deltime2=strtotime("-180days");
        $deltime3=strtotime("-365days");
        if($state=='show'){
            $wheresql.="";
        }elseif ($state=='one_mouth'){
            $wheresql.=" AND comment_time>'$deltime1'";
        }elseif ($state=='six_mouth'){
            $wheresql.=" AND comment_time>'$deltime2'";
        }elseif ($state=='one_year'){
            $wheresql.=" AND comment_time>'$deltime3'";
        }elseif ($state=='one_year_ago'){
            $wheresql.=" AND comment_time<'$deltime3'";
        }
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql);
        $good_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql." AND nurse_comment_level='good'");
        $good_count_chance=number_format($good_count/$count,2)*100;
        $middle_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql." AND nurse_comment_level='middle'");
        $bad_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment') .$wheresql." AND nurse_comment_level='bad'");
        $refund_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE member_id='$member_id' AND refund_state=1");
        $curmodule = 'home';
        $bodyclass = 'gray-bg';
        include(template('member_trust_grade'));
    }
}

?>