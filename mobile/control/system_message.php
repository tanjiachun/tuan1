<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class system_messageControl extends BaseMobileControl {
  public function indexOp(){

  }
  //交易消息通知
  public function deal_messageOp(){
      // 登陆验证
      $this->check_authority();
      $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='deal' AND message_state=0 AND show_state=0");
      $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
      if($message_set['deal_message_state']!=1){
          $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='deal' AND show_state=0 AND message_state=0 ORDER BY add_time DESC");
          while($value = DB::fetch($query)) {
              $value['add_time']=date('Y-m-d H:i:s',$value['add_time']);
              $message_list[]=$value;
          }
          $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='deal' AND show_state=0 AND message_state=1 ORDER BY add_time DESC");
          while($value = DB::fetch($query)) {
              $value['add_time']=date('Y-m-d H:i:s',$value['add_time']);
              $message_read_list[]=$value;
          }
          exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>array('message_count'=>$count,'message_list'=>$message_list,'message_read_list'=>$message_read_list))));
      }else{
          $message_show='您已禁止接收交易通知 ， 请在设置中修改后查看！';
          exit(json_encode(array('code'=>1,'msg'=>$message_show)));
      }
  }
  //系统消息通知
  public function system_messageOp(){
      // 登陆验证
      $this->check_authority();
      $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='system' AND message_state=0 AND show_state=0");
      $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
      if($message_set['system_message_state']!=1){
          $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='system' AND show_state=0 AND message_state=0 ORDER BY add_time DESC");
          while($value = DB::fetch($query)) {
              $value['add_time']=date('Y-m-d H:i:s',$value['add_time']);
              $message_list[]=$value;
          }
          $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='system' AND show_state=0 AND message_state=1 ORDER BY add_time DESC");
          while($value = DB::fetch($query)) {
              $value['add_time']=date('Y-m-d H:i:s',$value['add_time']);
              $message_read_list[]=$value;
          }
          exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>array('message_count'=>$count,'message_list'=>$message_list,'message_read_list'=>$message_read_list))));
      }else{
          $message_show='您已禁止接收系统通知 ， 请在设置中修改后查看！';
          exit(json_encode(array('code'=>1,'msg'=>$message_show)));
      }
  }
  //互动消息通知
    public function interact_messageOp(){
        // 登陆验证
        $this->check_authority();
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='interact' AND message_state=0 AND show_state=0");
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if($message_set['interact_message_state']!=1){
            $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='interact' AND show_state=0 AND message_state=0 ORDER BY add_time DESC");
            while($value = DB::fetch($query)) {
                $value['add_time']=date('Y-m-d H:i:s',$value['add_time']);
                $message_list[]=$value;
            }
            $query = DB::query("SELECT * FROM ".DB::table('system_message')." WHERE member_id='$this->member_id' AND message_type='interact' AND show_state=0 AND message_state=1 ORDER BY add_time DESC");
            while($value = DB::fetch($query)) {
                $value['add_time']=date('Y-m-d H:i:s',$value['add_time']);
                $message_read_list[]=$value;
            }
            exit(json_encode(array('code'=>0,'msg'=>'操作成功','data'=>array('message_count'=>$count,'message_list'=>$message_list,'message_read_list'=>$message_read_list))));
        }else{
            $message_show='您已禁止接收互动通知 ， 请在设置中修改后查看！';
            exit(json_encode(array('code'=>1,'msg'=>$message_show)));
        }
    }
    //设为已读
    public function readOp(){
        $read_ids = empty($_POST['read_ids']) ? '' : $_POST['read_ids'];
        $read_ids = explode(',', $read_ids);
        if(empty($read_ids)) {
            exit(json_encode(array('code'=>1,'msg'=>'请至少选择一条信息')));
        }
        DB::query("UPDATE ".DB::table('system_message')." SET message_state=1 WHERE message_id in ('".implode("','", $read_ids)."')");
        exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
    }
    //删除
    public function delOp(){
        $del_ids = empty($_POST['del_ids']) ? '' : $_POST['del_ids'];
        $del_ids = explode(',', $del_ids);
        if(empty($del_ids)) {
            exit(json_encode(array('code'=>1,'msg'=>'请至少选择一条信息')));
        }
        DB::query("UPDATE ".DB::table('system_message')." SET show_state=1 WHERE message_id in ('".implode("','", $del_ids)."')");
        exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
    }
    //交易通知设置
    public function set1Op(){
        $this->check_authority();
        $deal_message_state=empty($_POST['deal_message_state']) ? 0 : $_POST['deal_message_state'];
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if(empty($message_set)){
            $data=array(
                'member_id'=>$this->member_id,
                'deal_message_state'=>$deal_message_state,
                'add_time'=>time()
            );
            DB::insert('message_set',$data);
            exit(json_encode(array('code'=>0,'msg'=>'设置成功')));
        }else{
            DB::query("UPDATE ".DB::table('message_set')." SET deal_message_state=$deal_message_state WHERE member_id='$this->member_id'");
            exit(json_encode(array('code'=>0,'msg'=>'设置成功')));
        }
    }
    //系统通知设置
    public function set2Op(){
        $this->check_authority();
        $system_message_state=empty($_POST['system_message_state']) ? 0 : $_POST['system_message_state'];
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if(empty($message_set)){
            $data=array(
                'member_id'=>$this->member_id,
                'system_message_state'=>$system_message_state,
                'add_time'=>time()
            );
            DB::insert('message_set',$data);
            exit(json_encode(array('code'=>0,'msg'=>'设置成功')));
        }else{
            DB::query("UPDATE ".DB::table('message_set')." SET system_message_state=$system_message_state WHERE member_id='$this->member_id'");
            exit(json_encode(array('code'=>0,'msg'=>'设置成功')));
        }
    }
    //互动通知设置
    public function set3Op(){
        $this->check_authority();
        $interact_message_state=empty($_POST['interact_message_state']) ? 0 : $_POST['interact_message_state'];
        $message_set=DB::fetch_first("SELECT * FROM ".DB::table('message_set')." WHERE member_id='$this->member_id'");
        if(empty($message_set)){
            $data=array(
                'member_id'=>$this->member_id,
                'interact_message_state'=>$interact_message_state,
                'add_time'=>time()
            );
            DB::insert('message_set',$data);
            exit(json_encode(array('code'=>0,'msg'=>'设置成功')));
        }else{
            DB::query("UPDATE ".DB::table('message_set')." SET interact_message_state=$interact_message_state WHERE member_id='$this->member_id'");
            exit(json_encode(array('code'=>0,'msg'=>'设置成功')));
        }
    }

}

?>