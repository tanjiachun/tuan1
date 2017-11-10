<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class message_setControl extends BaseHomeControl {
    public function indexOp() {
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(empty($member)) {
            $this->showmessage('您还未登陆', 'index.php?act=login', 'error');
        }
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if(empty($message_set)){
            $member_set['system_message_state']=0;
            $member_set['deal_message_state']=0;
            $member_set['interact_message_state']=0;
        }

        $curmodule = 'home';
        $bodyclass = '';
        include(template('message_set'));

    }
    public function set1Op(){
        if(empty($this->member_id)){
                $this->showmessage('您还未登陆', 'index.php?act=login', 'error');
        }
        $deal_message_state=empty($_GET['deal_message_state']) ? 0 : $_GET['deal_message_state'];
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if(empty($message_set)){
            $data=array(
                'member_id'=>$this->member_id,
                'deal_message_state'=>$deal_message_state,
                'add_time'=>time()
            );
            DB::insert('message_set',$data);
            exit(json_encode(array('done'=>'true')));
        }else{
            DB::query("UPDATE ".DB::table('message_set')." SET deal_message_state=$deal_message_state WHERE member_id='$this->member_id'");
            exit(json_encode(array('done'=>'true')));
        }
    }

    public function set2Op(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登陆', 'index.php?act=login', 'error');
        }
        $system_message_state=empty($_GET['system_message_state']) ? 0 : $_GET['system_message_state'];
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if(empty($message_set)){
            $data=array(
                'member_id'=>$this->member_id,
                'system_message_state'=>$system_message_state,
                'add_time'=>time()
            );
            DB::insert('message_set',$data);
            exit(json_encode(array('done'=>'true')));
        }else{
            DB::query("UPDATE ".DB::table('message_set')." SET system_message_state=$system_message_state WHERE member_id='$this->member_id'");
            exit(json_encode(array('done'=>'true')));
        }
    }

    public function set3Op(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登陆', 'index.php?act=login', 'error');
        }
        $interact_message_state=empty($_GET['interact_message_state']) ? 0 : $_GET['interact_message_state'];
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if(empty($message_set)){
            $data=array(
                'member_id'=>$this->member_id,
                'interact_message_state'=>$interact_message_state,
                'add_time'=>time()
            );
            DB::insert('message_set',$data);
            exit(json_encode(array('done'=>'true')));
        }else{
            DB::query("UPDATE ".DB::table('message_set')." SET interact_message_state=$interact_message_state WHERE member_id='$this->member_id'");
            exit(json_encode(array('done'=>'true')));
        }
    }

}

?>