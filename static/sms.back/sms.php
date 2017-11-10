<?php
/**
 * 阿里云MNS SDK接入和配置
 */
if(!defined("InMall")) {
    exit("Access Invalid!");
}
require_once('mns-autoloader.php');
use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;
class PublishBatchSMSMessage {
	private $endPoint;
	private $accessId;
	private $accessKey;
	private $topicName;
	private $signName;
	private $sendPhone;
	private $templateId;
	private $smsParams;
	
	public function __construct($sendPhone, $templateId, $smsParams) {
		$this->endPoint = "http://1267695763911701.mns.cn-hangzhou.aliyuncs.com/";
        $this->accessId = "LTAI1DXDJWq4MnLE";
        $this->accessKey = "RV7bcIfeD3qeQ47E4JVvFWPNjWntGi";
		$this->topicName = "sms.topic-cn-hangzhou";
		$this->signName = $this->get_sign();
		$this->sendPhone = $sendPhone;
		$this->templateId = $templateId;
		$this->smsParams = $smsParams;
	}

    /**
     * 获取随机签名
     */
	private function get_sign(){
        $sign_data = array(
            '团家政网',
            '团家政APP',
            '团家政',
        );

        $rand_key = array_rand($sign_data, 1);
        return $sign_data[$rand_key];
    }

    public function run() {
	    if(defined('IS_DEBUG') && IS_DEBUG){
            return true;
        }
        /**
         * Step 1. 初始化Client
         */
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
		
        /**
         * Step 2. 获取主题引用
         */
        $topic = $this->client->getTopicRef($this->topicName);
		
        /**
         * Step 3. 生成SMS消息属性
         */
        $batchSmsAttributes = new BatchSmsAttributes($this->signName, $this->templateId);
        $batchSmsAttributes->addReceiver($this->sendPhone, $this->smsParams);
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        
		/**
         * Step 4. 设置SMS消息体（必须）
         */
         $messageBody = "smsmessage";
        
		/**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        $res = $topic->publishMessage($request);
		if($res->isSucceed()) {
			return true;
		} else {
			return false;
		}
	}
}
?>