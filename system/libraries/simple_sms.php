<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
if(!is_object($db)){
    exit("DB Invalid!");
}

require_once(MALL_ROOT.'/static/sms.back/sms.php');

class Simple_SMS{
    // 手机号码
    private $phone_number;
    // 短信模板id
    private $template_id;
    // 发送的内容
    private $send_content;
    // 发送属性
    private $send_type;
    // 短信模板列表
    private $template_list;

    function __construct($phone_number, $send_type, $text_code = array()){
        global $config;
        $this->template_list = $config['sms']['sms_template_list'];
        // 检测短信模板
        if(!is_array($this->template_list) || !array_key_exists($send_type, $this->template_list)){
            throw new Exception('template_id error.');
            exit();
        }

        // 检测手机号码
        if(!preg_match('/^1[0-9]{10}$/', $phone_number)) {
            throw new Exception('phone number error.');
            exit();
        }

        // 检测发送的内容
        if(!is_array($text_code)){
            throw new Exception('send content error.');
            exit();
        }

        $this->phone_number = $phone_number;
        $this->send_type = $send_type;
        $this->template_id = $this->template_list[$this->send_type];
        $this->send_content = $text_code;
    }

    /**
     * 异步发送短信，入队列后直接返回成功
     */
    public function async_send(){
        $current_time = date('Y-m-d H:i:s');
        $queue_data = array(
            'postdate' => $current_time,
            'lastupdate' => $current_time,
            'code_type' => $this->send_type,
            'phone_number' => $this->phone_number,
            'text_param' =>  json_encode($this->send_content)
            );

        $queue_id = DB::insert('code_queue', $queue_data, true);
        if(!empty($queue_id)){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 同步发送短信
     */
    public function send_now(){
        $msg_send = new PublishBatchSMSMessage($this->phone_number,$this->template_id, $this->send_content);
        return $msg_send->run();
    }

    /**
     * 设置成功状态
     */
    public function set_sucess($queue_id){
        DB::update('code_queue', array('send_status' => 1), array('queue_id'=>$queue_id , 'phone_number' => $this->phone_number));
    }

    /**
     * 设置失败状态
     */
    public function set_error($queue_id){
        DB::update('code_queue', array('send_status' => 2), array('queue_id'=>$queue_id ,'phone_number' => $this->phone_number));
    }







}