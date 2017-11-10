<?php
/**
 * php后台定时执行脚本程序，通过统计循环次数，达到次数后自动退出进程释放内存
 */
// 检测系统模式
error_reporting(E_ERROR);
if(PHP_SAPI !== 'cli' || empty($argv)){
    exit('system mode error.');
}

//if(!in_array('sms', $argv)){
//    exit('argv error.');
//}

define('InMall', true);
define('MALL_ROOT', str_replace('\\','/',dirname(__FILE__)));

require(MALL_ROOT.'/config.ini.php');
require(MALL_ROOT.'/system/function/common.php');
//require(MALL_ROOT.'/system/core/runtime.php');
date_default_timezone_set("Asia/Shanghai");

define("TIMESTAMP", time());
define('MAX_REQUEST', 1000);

if(function_exists('ini_get')) {
    $memorylimit = @ini_get('memory_limit');
    if($memorylimit && return_bytes($memorylimit) < 33554432 && function_exists('ini_set')) {
        ini_set('memory_limit', '256m');
    }
}

require_once(MALL_ROOT.'/system/database/mysql.php');
$class = 'db_mysql';
$db = & DB::object($class);
$db->set_config($config['db']);
$db->connect();

// 异步短信封装类
include_once MALL_ROOT.'/system/libraries/simple_sms.php';

if(in_array('sms', $argv)){
    while (true){
        $queue_data = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')." WHERE send_status = '0' ORDER BY postdate DESC");
        if(!empty($queue_data)){
            $my_simple_sms = new Simple_SMS($queue_data['phone_number'], $queue_data['code_type'], json_decode($queue_data['text_param'], true));
            if($my_simple_sms->send_now()){
                $my_simple_sms->set_sucess($queue_data['queue_id']);
                print date('Y-m-d H:i:s').":{$queue_data[phone_number]} {$queue_data[code_type]} {$queue_data['text_param']} send sucess.\r\n";
            }else{
                $my_simple_sms->set_error($queue_data['queue_id']);
                print date('Y-m-d H:i:s').":{$queue_data[phone_number]} {$queue_data[code_type]} {$queue_data['text_param']} send error.\r\n";
            }
        }else{
            print date('Y-m-d H:i:s').": wait for new task.\r\n";
            sleep(1);
        }
        print date('Y-m-d H:i:s').": sleep 1s;.\r\n";
        sleep(1);
        unset($queue_data,$my_simple_sms );
    }

}

if(in_array('order', $argv)){
    // TODO 每个短信通知（如果需要）
    //提前7天通知续费
    $now_time=time()+604800;
    $time_test_seven=DB::fetch_first("SELECT * FROM " .DB::table('nurse_book')." WHERE book_state=20 AND finish_time<=$now_time AND notifiy_seven=0 ORDER BY add_time DESC");
    if($time_test_seven){
        $data=array(
            'member_id'=>$time_test['member_id'],
            'nurse_id'=>$time_test['nurse_id'],
            'agent_id'=>$time_test['agent_id'],
            'book_id'=>$time_test['book_id'],
            'book_sn'=>$time_test['book_sn'],
            'message_type'=>'deal',
            'message_content'=>'尊敬的团家政用户，编号为'.$time_test['book_sn'].'的订单将于7天后到期，如果您需要家政人员继续为您服务，请保证账户余额充足并及时续费',
            'add_time'=>time()
        );
        $message_id=DB::insert('system_message',$data,1);
        if(!empty($message_id)){
            DB::query("UPDATE ".DB::table('nurse_book')." SET notifiy_seven=1 WHERE book_id='".$time_test['book_id']."'");
        }
        send_text_code($book_test_seven['member_phone'],'overdue_seven');
    }
    //提前3天通知续费
    $now_time2=time()+259200;
    $time_test_three=DB::fetch_first("SELECT * FROM " .DB::table('nurse_book')." WHERE book_state=20 AND finish_time<=$now_time2 AND notifiy_three=0 ORDER BY add_time DESC");
    if($time_test_three){
        $data2=array(
            'member_id'=>$time_test['member_id'],
            'nurse_id'=>$time_test['nurse_id'],
            'agent_id'=>$time_test['agent_id'],
            'book_id'=>$time_test['book_id'],
            'book_sn'=>$time_test['book_sn'],
            'message_type'=>'deal',
            'message_content'=>'尊敬的团家政用户，编号为'.$time_test['book_sn'].'的订单将于3天后到期，如果您需要家政人员继续为您服务，请保证账户余额充足并及时续费',
            'add_time'=>time()
        );
        $message_id2=DB::insert('system_message',$data2,1);
        if(!empty($message_id2)){
            DB::query("UPDATE ".DB::table('nurse_book')." SET notifiy_three=1 WHERE book_id='".$time_test['book_id']."'");
        }
        send_text_code($book_test_three['member_phone'],'overdue_three');
    }
    //提前1天通知续费
    $now_time3=time()+86400;
    $time_test_one=DB::fetch_first("SELECT * FROM " .DB::table('nurse_book')." WHERE book_state=20 AND finish_time<=$now_time3 AND notifiy_three=0 ORDER BY add_time DESC");
    if($time_test_one){
        $data3=array(
            'member_id'=>$time_test['member_id'],
            'nurse_id'=>$time_test['nurse_id'],
            'agent_id'=>$time_test['agent_id'],
            'book_id'=>$time_test['book_id'],
            'book_sn'=>$time_test['book_sn'],
            'message_type'=>'deal',
            'message_content'=>'尊敬的团家政用户，编号为'.$time_test['book_sn'].'的订单将于1天后到期，如果您需要家政人员继续为您服务，请保证账户余额充足或及时续费',
            'add_time'=>time()
        );
        $message_id3=DB::insert('system_message',$data3,1);
        if(!empty($message_id3)){
            DB::query("UPDATE ".DB::table('nurse_book')." SET notifiy_one=1 WHERE book_id='".$time_test['book_id']."'");
        }
        send_text_code($book_test_one['member_phone'],'overdue_one');
    }
    //预约后1天未付款，自动失败
    $now_time4=time()-86400;
    $book_test=DB::fetch_first("SELECT * FROM " .DB::table('nurse_book')." WHERE book_state=10 AND add_time<=$now_time4 AND notifiy_overdue=0 ORDER BY add_time DESC");
    if($book_test){
        $data4=array(
            'member_id'=>$time_test['member_id'],
            'nurse_id'=>$time_test['nurse_id'],
            'agent_id'=>$time_test['agent_id'],
            'book_id'=>$time_test['book_id'],
            'book_sn'=>$time_test['book_sn'],
            'message_type'=>'deal',
            'message_content'=>'尊敬的团家政用户，编号为'.$time_test['book_sn'].'的订单已失效',
            'add_time'=>time()
        );
        $message_id4=DB::insert('system_message',$data4,1);
        if(!empty($message_id4)){
            DB::query("UPDATE ".DB::table('nurse_book')." SET notifiy_overdue=1 WHERE book_id='".$time_test['book_id']."'");
        }
        send_text_code($book_test['member_phone'],'order_lose');
    }
    //订单自动完成 并 解冻金额
    $now_time5=time();
    $book_finish=DB::fetch_first("SELECT * FROM " .DB::table('nurse_book')." WHERE book_state=20 AND book_finish_time<=$now_time5 ORDER BY add_time DESC");
    if($book_finish){
        $data5=array(
          'member_id'=>$book_finish['member_id'],
            'nurse_id'=>$book_finish['nurse_id'],
            'agent_id'=>$book_finish['agent_id'],
            'book_id'=>$book_finish['book_id'],
            'book_sn'=>$book_finish['book_sn'],
            'message_type'=>'deal',
            'message_content'=>'尊敬的团家政用户，编号为'.$book_finish['book_sn'].'的订单已完成服务',
            'add_time'=>time()
        );
        $message_id5=DB::insert('system_message',$data5,1);
        if(!empty($message_id5)){
            DB::query("UPDATE ".DB::table('nurse_book')." SET book_state=30,nurse_state=1 WHERE book_id='".$book_finish['book_id']."'");
            DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=1 WHERE nurse_id='".$book_finish['nurse_id']."'");
        }
        //短信通知雇主评价
        send_text_code($book_finish['member_phone'],'order_reviews_notice');
        DB::query("UPDATE ".DB::table('nurse_profit')." SET is_freeze=0 WHERE book_id='".$book_finish['book_id']."'");
        DB::query("UPDATE ".DB::table('agent_profit')." SET is_freeze=0 WHERE book_id='".$book_finish['book_id']."'");
    }
    //订单超过7天未续费自动失效
    //toDo 站内消息和短信通知（如果需要）
    $now_time6=time()-604800;
    $book_lose=DB::fetch_first("SELECT * FROM " .DB::table('nurse_book')." WHERE book_state=20 AND book_finish_time>$now_time6 AND finish_time<$now_time6 ORDER BY add_time DESC");
    if($book_lose){
        DB::query("UPDATE ".DB::table('nurse_book')." SET book_state=0,nurse_state=1 WHERE book_id='".$book_lose['book_id']."'");
        DB::query("UPDATE ".DB::table('nurse')." SET state_cideci=1 WHERE nurse_id='".$book_lose['nurse_id']."'");
    }
    send_text_code($book_lose['member_phone'],'order_overdue');
}