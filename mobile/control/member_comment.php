<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_commentControl extends BaseMobileControl {
    public function indexOp(){
//        if(empty($this->member_id)){
//            exit(json_encode(array('code'=>1,'msg'=>'您还未登录','data'=>array())));
//        }
//        $member_token=empty($_POST['token']) ? '' : $_POST['token'];
        $this->check_authority();
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $member_id=$member['member_id'];
        $page = empty($_POST['page']) ? 0 : intval($_POST['page']);
        if($page < 1) $page = 1;
        $perpage = 20;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE member_id='$member_id'";
        $state = in_array($_POST['state'], array('all','good','middle','bad','back')) ? $_POST['state'] : 'all';
        if($state == 'all') {
            $wheresql .= "";
        } elseif($state == 'good') {
            $wheresql .= " AND comment_level='good'";
        }elseif($state == 'middle') {
            $wheresql .= " AND comment_level='middle'";
        }elseif($state == 'bad') {
            $wheresql .= " AND comment_level='bad'";
        }elseif($state == 'back') {
            $wheresql .= " AND agent_reply_content!=''";
        }
        $query = DB::query("SELECT * FROM ".DB::table('nurse_comment').$wheresql." LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $nurse_ids[] = $value['nurse_id'];
            $book_ids[]=$value['book_id'];
            $agent_ids[]=$value['agent_id'];
            $value['comment_tags'] = empty($value['comment_tags']) ? array() : unserialize($value['comment_tags']);
            $value['comment_image'] = empty($value['comment_image']) ? array() : unserialize($value['comment_image']);
            $value['nurse_comment_image'] = empty($value['nurse_comment_image']) ? array() : unserialize($value['nurse_comment_image']);
            $comment_list[] = $value;
        }
        if(!empty($nurse_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            while($value = DB::fetch($query)) {
                $nurse_list[$value['nurse_id']] = $value;
            }
        }
        if(!empty($agent_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
            while($value = DB::fetch($query)) {
                $agent_list[$value['agent_id']] = $value;
            }
        }
        if(!empty($book_ids)){
            $query = DB::query("SELECT * FROM ".DB::table('nurse_book')." WHERE book_id in ('".implode("','", $book_ids)."')");
            while($value=DB::fetch($query)){
                $book_list[$value['book_id']]=$value;
            }
        }
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
        foreach($comment_list as $key => $value) {
            $comment_list[$key]['nurse_name'] = $nurse_list[$value['nurse_id']]['nurse_name'];
            $comment_list[$key]['nurse_image'] = $nurse_list[$value['nurse_id']]['nurse_image'];
            $comment_list[$key]['nurse_type'] = $type_array[$nurse_list[$value['nurse_id']]['nurse_type']];
            $comment_list[$key]['nurse_content'] = $nurse_list[$value['nurse_id']]['nurse_content'];
            $comment_list[$key]['agent_name'] = $agent_list[$value['agent_id']]['agent_name'];
            $comment_list[$key]['book_sn'] = $book_list[$value['book_id']]['book_sn'];
            $comment_list[$key]['book_amount'] = $book_list[$value['book_id']]['book_amount'];
        }
        exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>$comment_list)));
    }
}

?>