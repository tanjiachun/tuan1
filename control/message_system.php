<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class message_systemControl extends BaseHomeControl {
    public function indexOp() {
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(empty($member)) {
            $this->showmessage('您还未登陆', 'index.php?act=login', 'error');
        }
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='system' AND message_state=0 AND show_state=0");
        $deal_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='deal' AND message_state=0 AND show_state=0");
        $system_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='system' AND message_state=0 AND show_state=0");
        $interact_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='interact' AND message_state=0 AND show_state=0");
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if($message_set['system_message_state']!=1){
            $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='system' AND show_state=0 AND message_state=0 ORDER BY add_time DESC");
            while($value = DB::fetch($query)) {
                $message_list[]=$value;
            }
            $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='system' AND show_state=0 AND message_state=1 ORDER BY add_time DESC");
            while($value = DB::fetch($query)) {
                $message_read_list[]=$value;
            }
        }else{
            $message_show='您已禁止接收系统通知 ， 请在设置中修改后查看！';
        }

        $curmodule = 'home';
        $bodyclass = '';
        include(template('message_system'));

    }

    public function readOp(){
        $read_ids = empty($_POST['read_ids']) ? '' : $_POST['read_ids'];
        $read_ids = explode(',', $read_ids);
        if(empty($read_ids)) {
            exit(json_encode(array('msg'=>'请至少选择一条信息')));
        }
        DB::query("UPDATE ".DB::table('system_message')." SET message_state=1 WHERE message_id in ('".implode("','", $read_ids)."')");
        exit(json_encode(array('done'=>'true')));
    }

    public function delOp(){
        $del_ids = empty($_POST['del_ids']) ? '' : $_POST['del_ids'];
        $del_ids = explode(',', $del_ids);
        if(empty($del_ids)) {
            exit(json_encode(array('msg'=>'请至少选择一条信息')));
        }
        DB::query("UPDATE ".DB::table('system_message')." SET show_state=1 WHERE message_id in ('".implode("','", $del_ids)."')");
        exit(json_encode(array('done'=>'true')));
    }
}

?>