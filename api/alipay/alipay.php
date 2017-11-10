<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
define('MALL_PARTNER', '2088621702163789');
define('MALL_SECURITYCODE', 'hkz71wisfcv9qipardq82vs8opw33gdc');
define('MALL_DIRECTPAY', 1);
define('MALL_ACCOUNT', 'shyldj@qq.com');
define('MALL_APPID', '2017041206670305');
define('MALL_PUBLICKEY', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB');
define('MALL_RSAPRIVATEKEY', 'MIICXQIBAAKBgQDeEqSqaWqLonKfmTdRQ1UcSNNLo9X7iOW0BPC+rxxu0NHtKG21RGEGrrL4bnfPAP3H7t5loNbbbcLnqdd45UqbZZEJ8lP6dbaApHVoSCU0+0Wh1DO7xrkyhEAirMzB4FvWOBJr+CeoD5r77k5jbXvyDMD3G5O6QHisPvFbUatiRQIDAQABAoGAe/7q5KABtr/YwpZ5Va2D60wadsSfKViR8YdEqSP7e0CrxoDpaOoqRuHq2l5MFVBXY3hTw2kgLttFlba2sjFNCxbauL8KAlgZXH1Bft95EE5mcJBk30Yt2eEamTVeExoOqYwwhsCanOQtACO3dESW3Pnr0a8svhv82VODTU3eE00CQQD89Ft8Kg7D3Df1GIjMmRHL8y1AJmDQEFv78N5u+MV3WJvSoHKENwkadbv5UI3eAehG7AijvFKhzLGnjj7nnVPfAkEA4L8anpSj2nVywsiPP0V7DL5GqmMOnf/8Nmmf9SjylIAn+/JpfasrX42yLjwQCBsLU861PCSErrwPaJZdMeOuWwJAau0EKwVAPoy9Xq6jTRE7U6IUs7RFYk44A9S6weAI4L0lCTDnl3oIMBYqgmYJwhm+TRSDRmtZ5qk3Q6O+dXusswJBAKPU63S6ag9wGm4/gZzf62lIs361owwudQ69RKuUY/E40uzos1m2k3Gee0sU4aLnBATd5GOpQ0eVM65MHjel/40CQQDuljDkM+3o5V4e+XWbOCJ07sWZ/WEgYXx+ACSJHjPuGYR1Nw+tjbzMfeDcm98+HD/ushi5vqDZ4lIcQOpuM1y2');

define('STATUS_SELLER_SEND', 4);
define('STATUS_WAIT_BUYER', 5);
define('STATUS_TRADE_SUCCESS', 7);
define('STATUS_REFUND_CLOSE', 17);

function get_payurl($param) {
	$args = array(
		'subject' => $param['subject'],
		'body' => $param['subject'],
		'service' => 'trade_create_by_buyer',
		'partner' => MALL_PARTNER,
		'notify_url' => $param['notify_url'],
		'return_url' => $param['return_url'],
		'show_url' => SiteUrl,
		'_input_charset' => CHARSET,
		'out_trade_no' => $param['out_trade_no'],
		'price' => $param['price'],
		'quantity' => 1,
		'seller_email' => MALL_ACCOUNT,
		'extend_param' => 'isv^dz11'
	);
	if(MALL_DIRECTPAY) {
		$args['service'] = 'create_direct_pay_by_user';
		$args['payment_type'] = '1';
	} else {
		$args['logistics_type'] = 'EXPRESS';
		$args['logistics_fee'] = 0;
		$args['logistics_payment'] = 'SELLER_PAY';
		$args['payment_type'] = 1;
	}
	return trade_returnurl($args);
}

function get_refundurl($param) {
	$args = array(
		'service' => 'refund_fastpay_by_platform_nopwd',
		'partner' => MALL_PARTNER,
		'_input_charset' => CHARSET,
		'batch_no' => $param['batch_no'],
		'refund_date' => $param['refund_date'],
		'batch_num' => 1,
		'detail_data' => $param['transaction_id'].'^'.$param['refund_amount'].'^协商退款',
	);
	return trade_returnurl($args);
}

function trade_returnurl($args) {
	ksort($args);
	$urlstr = $sign = '';
	foreach($args as $key => $val) {
		$sign .= '&'.$key.'='.$val;
		$urlstr .= $key.'='.rawurlencode($val).'&';
	}
	$sign = substr($sign, 1);
	$sign = md5($sign.MALL_SECURITYCODE);
	return 'https://www.alipay.com/cooperate/gateway.do?'.$urlstr.'sign='.$sign.'&sign_type=MD5';
}

function trade_notifycheck() {
	if(!empty($_POST)) {
		$notify = $_POST;
	} elseif(!empty($_GET)) {
		$notify = $_GET;
	} else {
		exit('Access Denied');
	}
	unset($notify['act']);
	unset($notify['op']);
	
	if(dfsockopen("http://notify.alipay.com/trade/notify_query.do?partner=".MALL_PARTNER."&notify_id=".$notify['notify_id'], 60) !== 'true') {
		exit('Access Denied');
	}

	if(!MALL_SECURITYCODE) {
		exit('Access Denied');
	}
	ksort($notify);
	$sign = '';
	foreach($notify as $key => $val) {
		if($key != 'sign' && $key != 'sign_type') $sign .= "&$key=$val";
	}
	if($notify['sign'] != md5(substr($sign,1).MALL_SECURITYCODE)) {
		exit('Access Denied');
	}
	
	if(!MALL_DIRECTPAY && $notify['notify_type'] == 'trade_status_sync' && ($notify['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $notify['trade_status'] == 'TRADE_FINISHED') || MALL_DIRECTPAY && ($notify['trade_status'] == 'TRADE_FINISHED' || $notify['trade_status'] == 'TRADE_SUCCESS')) {
		return array(
			'validator'	=> TRUE,
			'order_no' 	=> $notify['out_trade_no'],
			'price' 	=> !MALL_DIRECTPAY && $notify['price'] ? $notify['price'] : $notify['total_fee'],
			'trade_no'	=> $notify['trade_no'],
			'notify'	=> 'success',
		);
	} else {
		return array(
			'validator'	=> FALSE,
			'notify'	=> 'fail',
		);
	}
}

function dfsockopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE, $encodetype  = 'URLENCODE', $allowcurl = TRUE, $position = 0) {
	$return = '';
	$matches = parse_url($url);
	$scheme = $matches['scheme'];
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if(function_exists('curl_init') && function_exists('curl_exec') && $allowcurl) {
		$ch = curl_init();
		$ip && curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: ".$host));
		curl_setopt($ch, CURLOPT_URL, $scheme.'://'.($ip ? $ip : $host).':'.$port.$path);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			if($encodetype == 'URLENCODE') {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			} else {
				parse_str($post, $postarray);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarray);
			}
		}
		if($cookie) {
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$data = curl_exec($ch);
		$status = curl_getinfo($ch);
		$errno = curl_errno($ch);
		curl_close($ch);
		if($errno || $status['http_code'] != 200) {
			return;
		} else {
			return !$limit ? $data : substr($data, 0, $limit);
		}
	}

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$header = "Accept: */*\r\n";
		$header .= "Accept-Language: zh-cn\r\n";
		$boundary = $encodetype == 'URLENCODE' ? '' : '; boundary='.trim(substr(trim($post), 2, strpos(trim($post), "\n") - 2));
		$header .= $encodetype == 'URLENCODE' ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data$boundary\r\n";
		$header .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$header .= "Host: $host:$port\r\n";
		$header .= 'Content-Length: '.strlen($post)."\r\n";
		$header .= "Connection: Close\r\n";
		$header .= "Cache-Control: no-cache\r\n";
		$header .= "Cookie: $cookie\r\n\r\n";
		$out .= $header.$post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$header = "Accept: */*\r\n";
		$header .= "Accept-Language: zh-cn\r\n";
		$header .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$header .= "Host: $host:$port\r\n";
		$header .= "Connection: Close\r\n";
		$header .= "Cookie: $cookie\r\n\r\n";
		$out .= $header;
	}

	$fpflag = 0;
	if(!$fp = @fsocketopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout)) {
		$context = array(
			'http' => array(
				'method' => $post ? 'POST' : 'GET',
				'header' => $header,
				'content' => $post,
				'timeout' => $timeout,
			),
		);
		$context = stream_context_create($context);
		$fp = @fopen($scheme.'://'.($ip ? $ip : $host).':'.$port.$path, 'b', false, $context);
		$fpflag = 1;
	}

	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp) && !$fpflag) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			if($position) {
				for($i=0; $i<$position; $i++) {
					$char = fgetc($fp);
					if($char == "\n" && $oldchar != "\r") {
						$i++;
					}
					$oldchar = $char;
				}
			}

			if($limit) {
				$return = stream_get_contents($fp, $limit);
			} else {
				$return = stream_get_contents($fp);
			}
		}
		@fclose($fp);
		return $return;
	}
}

function app_payurl($param) {
	$content = array();
	$content['body'] = $param['body'];
	$content['subject'] = $param['subject'];
	$content['out_trade_no'] = $param['out_trade_no'];
	$content['timeout_express'] = '30m';
	$content['total_amount'] = $param['total_fee'];
	$content['seller_id'] = MALL_ACCOUNT;
	$content['product_code'] = 'QUICK_MSECURITY_PAY';
	$biz_content = json_encode($content);
	$args = array(
		'app_id' => MALL_APPID,
		'method' => 'alipay.trade.app.pay',
		'charset' => CHARSET,
		'timestamp' => date('Y-m-d H:i:s'),
		'sign_type' => 'RSA',
		'version' => '1.0',
		'notify_url' => $param['notify_url'],
		'biz_content' => $biz_content,
	);
	ksort($args);
	$sign = $url = '';
	foreach($args as $key => $value) {
		$sign .= '&'.$key.'='.$value;
		$url .= $key.'='.rawurlencode($value).'&';
	}
	$sign = substr($sign, 1);
	$res = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap(MALL_RSAPRIVATEKEY, 64, "\n", true)."\n-----END RSA PRIVATE KEY-----";
	openssl_sign($sign, $sign, $res);
	$sign = base64_encode($sign);
	$url .= 'sign='.urlencode($sign);
	return $url;
}

function app_notifycheck() {
	if(!empty($_POST)) {
		$notify = $_POST;
	} elseif(!empty($_GET)) {
		$notify = $_GET;
	} else {
		exit('Access Denied');
	}
	
	if(!MALL_PUBLICKEY) {
		exit('Access Denied');
	}
	ksort($notify);
	$sign = '';
	foreach($notify as $key => $value) {
		if($key != 'sign' && $key != 'sign_type') {
			$sign .= '&'.$key.'='.$value;
		}
	}
	$sign = substr($sign, 1);
	$res = "-----BEGIN PUBLIC KEY-----\n".wordwrap(MALL_PUBLICKEY, 64, "\n", true)."\n-----END PUBLIC KEY-----";
	$result = (bool)openssl_verify($sign, base64_decode($notify['sign']), $res);
	if(!result) {
		exit('Access Denied');
	}
	
	if($notify['notify_type'] == 'trade_status_sync' && ($notify['trade_status'] == 'TRADE_FINISHED' || $notify['trade_status'] == 'TRADE_SUCCESS')) {
		return array(
			'validator'	=> TRUE,
			'order_no' 	=> $notify['out_trade_no'],
			'price' 	=> $notify['total_amount'],
			'trade_no'	=> $notify['trade_no'],
			'notify'	=> 'success',
		);
	} else {
		return array(
			'validator'	=> FALSE,
			'notify'	=> 'fail',
		);
	}
}

?>