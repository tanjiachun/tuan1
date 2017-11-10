<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class comment_and_bookControl extends BaseHomeControl {
    public function indexOp() {
        $query=DB::query("SELECT * FROM ".DB::table("nurse_book")." ORDER BY add_time DESC");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." ORDER BY add_time ASC");
        while($value=DB::fetch($query)){
            $value['member_phone']=preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '\1****\3', $value['member_phone']);
            $value['count']=$book_count;
            $book_list[]=$value;
        }

        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment')." ORDER BY comment_time DESC");
        while($value = DB::fetch($query)) {
            $member_ids[] = $value['member_id'];
            $nurse_ids[] = $value['nurse_id'];
            $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
            $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
            $comment_list[] = $value;
        }
        if(!empty($member_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $value['nurse_areaname']=$value['nurse_areaname'];
                $member_list[$value['nurse_id']] = $value;
            }
        }


        $curmodule = 'member';
        $bodyclass = '';
        include(template('comment_and_book'));

    }

}

?>