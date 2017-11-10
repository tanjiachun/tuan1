<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_trust_gradeControl extends BaseProfileControl {
    public function indexOp(){
        $nurse_id=empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        $nurse_token=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$nurse['member_id']."'");
        if(empty($nurse)){
            $this->showmessage('家政人员不存在', 'index.php', 'error');
        }
        $nurse_cityid=$nurse['nurse_cityid'];
        $nurse_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
        $nurse_grade = DB::fetch_first("SELECT * FROM ".DB::table('nurse_grade')." WHERE nurse_score<=".$nurse['nurse_score']." ORDER BY nurse_score DESC");
        $wheresql = " WHERE nurse_id='$nurse_id'";
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
        $good_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql." AND comment_level='good'");
        $good_count_chance=number_format($good_count/$count,2)*100;
        $middle_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment').$wheresql." AND comment_level='middle'");
        $bad_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_comment') .$wheresql." AND comment_level='bad'");
        $refund_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE nurse_id='$nurse_id' AND refund_state=1");
        $curmodule = 'home';
        $bodyclass = 'gray-bg';
        include(template('nurse_trust_grade'));
    }
}

?>