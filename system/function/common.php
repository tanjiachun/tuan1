<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

/**
 * 验证码作为参数传进去
 */
function send_work_code($phone_number,$template_code,$code){
    $current_time = date('Y-m-d');
//    $send_count = DB::fetch_first("SELECT COUNT(phone_number) as num FROM ".DB::table('code_queue')." WHERE phone_number='$phone_number' AND code_type = '$template_code' AND postdate > '$current_time'");
//    // 限制每天发送数量
//    if(!empty($send_count['num']) && $send_count['num'] >= SMS_SEND_LITMIT){
//        exit(json_encode(array('msg'=>'您的验证码获取过于频繁，请稍后再试！')));
//    }

    // 限制60秒只能发送一次同类型验证码
//    $last_send = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$phone_number' AND code_type = '$template_code' ORDER BY postdate DESC");
//    if(!empty($last_send) && strtotime($last_send['postdate'])+60 > time())
//    {
//        $wait_time = 60 -(time()- strtotime($last_send['postdate']));
//        exit(json_encode(array('msg'=>'每隔60秒可以发送1次', 'time'=>$wait_time)));
//    }
    $content=array(
        'code'=>$code
    );
    $text_send = new Simple_SMS($phone_number, $template_code, $content);
    $send_result = $text_send->async_send();
}

/**
 * @param $phone_number
 * @param $template_code
 */
function send_text_code($phone_number, $template_code){
    $current_time = date('Y-m-d');
//    $send_count = DB::fetch_first("SELECT COUNT(phone_number) as num FROM ".DB::table('code_queue')." WHERE phone_number='$phone_number' AND code_type = '$template_code' AND postdate > '$current_time'");
//    // 限制每天发送数量
//    if(!empty($send_count['num']) && $send_count['num'] >= SMS_SEND_LITMIT){
//        exit(json_encode(array('msg'=>'您的验证码获取过于频繁，请稍后再试！')));
//    }

    // 限制60秒只能发送一次同类型验证码
//    $last_send = DB::fetch_first("SELECT * FROM  ".DB::table('code_queue')."  WHERE phone_number = '$phone_number' AND code_type = '$template_code' ORDER BY postdate DESC");
//    if(!empty($last_send) && strtotime($last_send['postdate'])+60 > time())
//    {
//        $wait_time = 60 -(time()- strtotime($last_send['postdate']));
//        exit(json_encode(array('msg'=>'每隔60秒可以发送1次', 'time'=>$wait_time)));
//    }
    $content =  array(
        'code' => $this->createCode()
    );
    $text_send = new Simple_SMS($phone_number, $template_code, $content);
    $send_result = $text_send->async_send();

}


function dsetcookie($name, $value = '', $life = 0, $httponly = false) {
	$config = $GLOBALS['config']['cookie'];
	$name = $config['cookiepre'].$name;
	if($value == '' || $life < 0) {
		$value = '';
		$life = -1;
	}
	$life = $life > 0 ? TIMESTAMP + $life : ($life < 0 ? TIMESTAMP - 259200 : 0);
	$path = $httponly && PHP_VERSION < '5.2.0' ? $config['cookiepath'].'; HttpOnly' : $config['cookiepath'];
	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	if(PHP_VERSION < '5.2.0') {
		setcookie($name, $value, $life, $path, $config['cookiedomain'], $secure);
	} else {
		setcookie($name, $value, $life, $path, $config['cookiedomain'], $secure, $httponly);
	}
}

/**
 * 短信发送函数
 * @param string $mobile
 * @param string $templateid
 * @param string $content
 * @return bool
 */

function sendsms($mobile, $templateid, $content) {
    // 调试模式不发送短信
    if(defined('IS_DEBUG') && IS_DEBUG)
    {
        return true;
    }
//    var_dump($mobile, $templateid, $content);
    include_once MALL_ROOT.'/static/sms/TopSdk.php';
    date_default_timezone_set('Asia/Shanghai');
    $c = new TopClient;
    $c->appkey = '24328633';
    $c->secretKey = 'b7ff4397145671aa59fdf36ea3ba1021';
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setSmsType('normal');
    $req->setSmsFreeSignName('养老到家');
    $req->setSmsParam($content);
    $req->setRecNum($mobile);
    $req->setSmsTemplateCode($templateid);
    $resp = $c->execute($req);
//    var_dump($resp);
    if($resp->result->success) {
        return true;
    } else {
        return false;
    }
}
//随机获取六位数字
function num6(){
	$ychar="0,1,2,3,4,5,6,7,8,9";
    $authnum='';
    $list=explode(",",$ychar);
    for($i=0;$i<6;$i++){
        $randnum=rand(0,9); // 10+26;
        $authnum.=$list[$randnum];
    }
    return $authnum;
}
function dgetcookie($name) {
	$config = $GLOBALS['config']['cookie'];
	$name = $config['cookiepre'].$name;
	return isset($_COOKIE[$name]) ? $_COOKIE[$name] : '';
}

function daddslashes($string) {
	if(is_array($string)) {
		$keys = array_keys($string);
		foreach($keys as $key) {
			$val = $string[$key];
			unset($string[$key]);
			$string[addslashes($key)] = daddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

function dstripslashes($string) {
	if(empty($string)) return $string;
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

function dreferer() {
	$referer = !empty($_GET['referer']) ? $_GET['referer'] : $_SERVER['HTTP_REFERER'];
	$referer = substr($referer, -1) == '?' ? substr($referer, 0, -1) : $referer;
	if(strpos($referer, 'index.php?act=register') || strpos($referer, 'index.php?act=forget') || strpos($referer, 'index.php?act=logout')) {
		$referer = '';
	}
	$reurl = parse_url($referer);
	if(!$reurl || (isset($reurl['scheme']) && !in_array(strtolower($reurl['scheme']), array('http', 'https')))) {
		$referer = '';
	}
	if(empty($reurl['host'])) {
		$referer = '';
	}
	$referer = empty($referer) ? 'index.php' : $referer;
	return $referer;
}

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

function formhash() {
	return substr(md5(substr(TIMESTAMP, 0, -7).md5($GLOBALS['config']['security']['authkey'])), 8, 8);
}

function submitcheck() {
	$formhash = $_POST['formhash'];
	if($_POST['form_submit'] == 'ok' && $formhash == formhash()) {
		return true;
	}
	return false;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key != '' ? $key : $GLOBALS['config']['security']['authkey']);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function xss_check() {
	$check = array('"', '>', '<', '\'', '(', ')', 'CONTENT-TRANSFER-ENCODING');
	$temp = strtoupper(urldecode(urldecode($_SERVER['REQUEST_URI'])));
	foreach ($check as $str) {
		if(strpos($temp, $str) !== false) {
			exit('request_tainting');
		}
	}
	return true;
}

function checkcard($card_id) {
	$apiurl = 'http://api.jisuapi.com/idcard/query?appkey=4014c74f5661295f&idcard='.$card_id;
	$content = geturlfile($apiurl);
	$result = json_decode($content, true);
	if(empty($result['status'])) {
		return 1;	
	} else {
		return 0;
	}
}

function getip() {
	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] as $xip) {
			if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
				$ip = $xip;
				break;
			}
		}
	}
	return $ip;
}
function url(){
	$ip='192.168.1.110';
	$url="http://".$ip."/";
    return $url;
}

function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	if($numeric) {
		$hash = '';
	} else {
		$hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
		$length--;
	}
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}

function makesn($pre = '') {
	$sn = $pre.date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), -8, 8);
	return $sn;
}
	
function makeoutsn($length = 8) {  
	$chars = "0123456789";  
	$outsn = date('YmdHis');
	for($i = 0; $i < $length; $i++) {
		$outsn.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
	}
	return $outsn;  
}

function timeformat($time) {
	return $time.'年';
}

function priceformat($price) {
    $price_format = 0;
    $price_format = number_format($price, 2, ".", "");
    return $price_format;
}

function br2nl($text){
	return preg_replace('/<br\\s*?\/??>/i','',$text);
}

function dgmdate($timestamp) {
	$todaytimestamp = strtotime(date('Y-m-d', time()));
	$diff_time = time() - $timestamp;
	if($timestamp > $todaytimestamp) {
		if($diff_time > 3600) {
			$return = intval($diff_time/3600).'小时前';
		} elseif($diff_time > 1800) {
			$return = '半小时前';
		} elseif($diff_time > 60) {
			$return = intval($diff_time/60).'分钟前';
		} elseif($diff_time > 0) {
			$return = $diff_time.'秒前';
		} elseif($diff_time == 0) {
			$return = '刚刚';
		} else {
			$return = '';
		}
	} else {
		$days = intval(($todaytimestamp - $timestamp)/86400);
		if($days == 0) {
			$return = '昨天';
		} elseif($days == 1) {
			$return = '前天';
		} else {
			$return = date('Y-m-d H:i', $timestamp);
		}
	}
	return $return;
}

function cutstr($string, $length, $dot = ' ...') {
	if(strlen($string) <= $length) {
		return $string;
	}
	$pre = chr(1);
	$end = chr(1);
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);
	$strcut = '';
	if(strtolower(CHARSET) == 'utf-8') {
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
	} else {
		$_length = $length - 1;
		for($i = 0; $i < $length; $i++) {
			if(ord($string[$i]) <= 127) {
				$strcut .= $string[$i];
			} else if($i < $_length) {
				$strcut .= $string[$i].$string[++$i];
			}
		}
	}
	$strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	$pos = strrpos($strcut, chr(1));
	if($pos !== false) {
		$strcut = substr($strcut,0,$pos);
	}
	return $strcut.$dot;
}

function getimgthumbname($fileStr, $extend = '.thumb.jpg', $holdOldExt = TRUE) {
	if(empty($fileStr)) {
		return '';
	}
	if(!$holdOldExt) {
		$fileStr = substr($fileStr, 0, strrpos($fileStr, '.'));
	}
	$extend = strstr($extend, '.') ? $extend : '.'.$extend;
	return $fileStr.$extend;
}

function dmkdir($dir, $mode = 0777, $makeindex = TRUE){
	if(!is_dir($dir)) {
		dmkdir(dirname($dir), $mode, $makeindex);
		@mkdir($dir, $mode);
		if(!empty($makeindex)) {
			@touch($dir.'/index.html'); @chmod($dir.'/index.html', 0777);
		}
	}
	return true;
}

function getcachevars($data, $type = 'VAR') {
	$evaluate = '';
	foreach($data as $key => $val) {
		if(!preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $key)) {
			continue;
		}
		if(is_array($val)) {
			$evaluate .= "\$$key = ".arrayeval($val).";\n";
		} else {
			$val = addcslashes($val, '\'\\');
			$evaluate .= $type == 'VAR' ? "\$$key = '$val';\n" : "define('".strtoupper($key)."', '$val');\n";
		}
	}
	return $evaluate;
}

function arrayeval($array, $level = 0) {
	if(!is_array($array)) {
		return "'".$array."'";
	}
	if(is_array($array) && function_exists('var_export')) {
		return var_export($array, true);
	}
	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = $space;
	if(is_array($array)) {
		foreach($array as $key => $val) {
			$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
			$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
			if(is_array($val)) {
				$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
			} else {
				$evaluate .= "$comma$key => $val";
			}
			$comma = ",\n$space";
		}
	}
	$evaluate .= "\n$space)";
	return $evaluate;
}

function writetocache($script, $cachedata, $prefix = 'cache_') {
	$dir = MALL_ROOT.'/cache/';
	if(!is_dir($dir)) {
		dmkdir($dir, 0777);
	}
	if($fp = @fopen("$dir$prefix$script.php", 'wb')) {
		fwrite($fp, "<?php\n//cache file, DO NOT modify me!\n\n$cachedata?>");
		fclose($fp);
	} else {
		exit('Can not write to cache files, please check directory ./cache/.');
	}
}

function geturlfile($url, $data = '') {
	$contents = '';
	$url = htmlspecialchars_decode($url);
	if(function_exists('curl_init') && @$ch = curl_init()) {
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		if(!empty($data)) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);				
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$contents = curl_exec($ch);
		curl_close($ch);
	}
	if(empty($contents)){	
		if(!empty($data)) {
			$context_param['http'] = array('user_agent'=>$_SERVER['HTTP_USER_AGENT'], 'method'=>'POST', 'content'=>$data);
		} else {
			$context_param['http'] = array('user_agent'=>$_SERVER['HTTP_USER_AGENT']);
		}
		$context = stream_context_create($context_param);
		@$contents = file_get_contents($url, 0, $context);
	}
	return $contents;
}

function simplemulti($count, $perpage, $curpage, $mpurl) {
	$dot = '...';
	$page = 6;	
	$realpages = 1;
	$page_next = 0;
	$page -= strlen($curpage) - 1;
	if($page <= 0) {
		$page = 1;
	}
	if($count > $perpage) {
		$offset = floor($page * 0.5);
		$realpages = @ceil($count / $perpage);
		$pages = $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$page_next = $to;
		$multipage = '';
		$mpurl .= strpos($mpurl, '?') !== FALSE ? '&amp;' : '?';
		$multipage = '<div class="small-paging"><div class="clearfix pager"><span class="title">页:</span>';
		$multipage .= $curpage - $offset > 1 && $pages > $page ? '<a class="page active first" href="'.$mpurl.'page=1">1</a>'.$dot : '';
		for($i = $from; $i <= $to; $i++) {
			if($i == 1) {
				$multipage .= '<a class="page active first" href="'.$mpurl.'page=1">1</a>';
			} else {
				$multipage .= $i == $curpage ? '<a class="page active" href="'.$mpurl.'page='.$i.'">'.$i.'</a>' :'<a class="page" href="'.$mpurl.'page='.$i.'">'.$i.'</a>';
			}
		}
		$multipage .= $to < $pages ? $dot.'<a class="page" href="'.$mpurl.'page='.$pages.'">'.$realpages.'</a>' : '';
		$multipage .= '</div></div>';
	}
	return $multipage;	
}

function simplepage($count, $perpage, $curpage, $mpurl) {
	$return = '';
	$mpurl .= strpos($mpurl, '?') !== FALSE ? '&amp;' : '?';
	if($count > $perpage) {
		$realpages = @ceil($count/$perpage);
		$return = '<span class="page_nav_area"><a href="'.(($curpage - 1) > 0 ? $mpurl.'page='.($curpage - 1) : 'javascript:void(0)').'" class="btn page_prev"><i class="arrow"></i></a><span class="page_num"><label>'.$curpage.'</label><span class="num_gap">/</span><label>'.$realpages.'</label></span><a href="'.(($curpage + 1) > $realpages ? 'javascript:void(0)' : $mpurl.'page='.($curpage + 1)).'" class="btn page_next"><i class="arrow"></i></a></span><span class="goto_area"><input type="text" id="page" value=""><a href="javascript:void(0);" class="btn page_go" onclick="window.location=\''.$mpurl.'page=\'+document.getElementById(\'page\').value">跳转</a></span>';
	}
	return $return;
}

function multi($count, $perpage, $curpage, $mpurl, $func = '') {
	$dot = '...';
	$page = 6;	
	$realpages = 1;
	$page_next = 0;
	$page -= strlen($curpage) - 1;
	if($page <= 0) {
		$page = 1;
	}
	if($count > $perpage) {
		$offset = floor($page * 0.5);
		$realpages = @ceil($count / $perpage);
		$pages = $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$page_next = $to;
		$multipage = '';
		if($func == '') {
			$mpurl .= strpos($mpurl, '?') !== FALSE ? '&amp;' : '?';
			$multipage = ($curpage > 1 ? '<li class="ui-pager"><a href="'.$mpurl.'page='.($curpage - 1).'">上一页</a></li>' : '').($curpage - $offset > 1 && $pages > $page ? '<li class="ui-pager"><a href="'.$mpurl.'page=1">1</a></li><li class="ui-paging-ellipse">'.$dot.'</li>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<li class="ui-pager cur">'.$i.'</li>' :
				'<li class="ui-pager"><a href="'.$mpurl.'page='.$i.'">'.$i.'</a></li>';
			}
			$multipage .= ($to < $pages ? '<li class="ui-paging-ellipse">'.$dot.'</li><li class="ui-pager"><a href="'.$mpurl.'page='.$pages.'">'.$realpages.'</a></li>' : '').($curpage < $pages ? '<li class="ui-pager"><a href="'.$mpurl.'page='.($curpage + 1).'">下一页</a></li>' : '');
			$multipage .= '<li class="ui-paging-toolbar"><input type="text" id="page" class="ui-paging-count"><a href="javascript:void(0)" onclick="window.location=\''.$mpurl.'page=\'+document.getElementById(\'page\').value">跳转</a></li>';
			$multipage = $multipage ? '<div class="ui-paging-container"><ul>'.$multipage.'</ul></div>' : '';
		} else {
			$multipage = ($curpage > 1 ? '<li class="ui-pager"><a href="javascript:;" onclick="'.$func.'(this, \'page\', \''.($curpage - 1).'\')">上一页</a></li>' : '').($curpage - $offset > 1 && $pages > $page ? '<li class="ui-pager"><a href="javascript:;" onclick="'.$func.'(this, \'page\', \'1\')">1</a></li><li class="ui-paging-ellipse">'.$dot.'</li>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<li class="ui-pager cur">'.$i.'</li>' :
				'<li class="ui-pager"><a href="javascript:;" onclick="'.$func.'(this, \'page\', \''.$i.'\')">'.$i.'</a></li>';
			}
			$multipage .= ($to < $pages ? '<li class="ui-paging-ellipse">'.$dot.'</li><li class="ui-pager"><a href="javascript:;" onclick="'.$func.'(this, \'page\', \''.$pages.'\')">'.$realpages.'</a></li>' : '').($curpage < $pages ? '<li class="ui-pager"><a href="javascript:;" onclick="'.$func.'(this, \'page\', \''.($curpage + 1).'\')">下一页</a></li>' : '');
			$multipage .= '<li class="ui-paging-toolbar"><input type="text" id="page" class="ui-paging-count"><a href="javascript:void(0)" onclick="'.$func.'(this, \'page\', document.getElementById(\'page\').value)">跳转</a></li>';
			$multipage = $multipage ? '<div class="ui-paging-container"><ul>'.$multipage.'</ul></div>' : '';
		}
	}
	return $multipage;
}
function multiTop($count, $perpage, $curpage, $mpurl, $func = '') {
    $dot = '...';
    $page = 6;
    $realpages = 1;
    $page_next = 0;
    $page -= strlen($curpage) - 1;
    if($page <= 0) {
        $page = 1;
    }
    if($count > $perpage) {
        $offset = floor($page * 0.5);
        $realpages = @ceil($count / $perpage);
        $pages = $realpages;
        if($page > $pages) {
            $from = 1;
            $to = $pages;
        } else {
            $from = $curpage - $offset;
            $to = $from + $page - 1;
            if($from < 1) {
                $to = $curpage + 1 - $from;
                $from = 1;
                if($to - $from < $page) {
                    $to = $page;
                }
            } elseif($to > $pages) {
                $from = $pages - $page + 1;
                $to = $pages;
            }
        }
        $page_next = $to;
        $multipage = '';
			$multipage.='<span class="fp-text"><b>'.$curpage.'</b><em>/</em><i>'.$pages.'</i></span>';
            $multipage .= ($curpage > 1 ? '<a class="fp-prev" href="javascript:;" onclick="'.$func.'(this, \'page\', \''.($curpage - 1).'\')">&lt;</a>' : '<a class="fp-prev disabled" href="javascript:;">&lt;</a>');
            $multipage .= ($curpage < $pages ? '<a class="fp-next" href="javascript:;" onclick="'.$func.'(this, \'page\', \''.($curpage + 1).'\')">&gt;</a>' : '<a class="fp-next disabled" href="javascript:;">&gt;</a>');
    }
    return $multipage;
}
function template($tplpath) {
	if(!defined("ProjectName")) {
		return MALL_ROOT.'/templates/'.$tplpath.'.php';
	} else {
		return MALL_ROOT.'/'.ProjectName.'/templates/'.$tplpath.'.php';
	}
}

function showeditor($id, $value = "", $width = "700px", $height = "300px", $style = "visibility:hidden;", $upload_state = "true", $media_open = FALSE) {
    $media = "";
	$media_state = "false";
    if($media_open) {
        $media = ", 'media'";
		$media_state = "true";
    }
    $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',\r\n\t\t\t'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',\r\n\t\t\t'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',\r\n\t\t\t'superscript', '|', 'selectall', 'clearhtml','quickformat','|',\r\n\t\t\t'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',\r\n\t\t\t'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image'".$media.", 'table', 'hr', 'emoticons', 'link', 'unlink', '|', 'about']";
    echo "<textarea id=\"".$id."\" name=\"".$id."\" style=\"width:".$width.";height:".$height.";".$style."\">".$value."</textarea>";
    echo "\r\n<script src=\"static/kindeditor/kindeditor-min.js\" charset=\"utf-8\"></script>\r\n<script src=\"static/kindeditor/lang/zh_CN.js\" charset=\"utf-8\"></script>\r\n<script>\r\n\tvar KE;\r\n  KindEditor.ready(function(K) {\r\n        KE = K.create(\"textarea[name='".$id."']\", {\r\n\t\t\t\t\t\titems : ".$items.",\r\n\t\t\t\t\t\tcssPath : \"static/kindeditor/themes/default/default.css\",\r\n\t\t\t\t\t\tallowImageUpload : ".$upload_state.",\r\n\t\t\t\t\t\tuploadJson : 'index.php?act=misc&op=kindeditor',\r\n\t\t\t\t\t\tallowMediaUpload : ".$media_state.",\r\n\t\t\t\t\t\tsyncType:\"form\",\r\n\t\t\t\t\t\tafterCreate : function() {\r\n\t\t\t\t\t\t\tvar self = this;\r\n\t\t\t\t\t\t\tself.sync();\r\n\t\t\t\t\t\t},\r\n\t\t\t\t\t\tafterChange : function() {\r\n\t\t\t\t\t\t\tvar self = this;\r\n\t\t\t\t\t\t\tself.sync();\r\n\t\t\t\t\t\t},\r\n\t\t\t\t\t\tafterBlur : function() {\r\n\t\t\t\t\t\t\tvar self = this;\r\n\t\t\t\t\t\t\tself.sync();\r\n\t\t\t\t\t\t}\r\n        });\r\n\t\t\tKE.appendHtml = function(id,val) {\r\n\t\t\t\tthis.html(this.html() + val);\r\n\t\t\t\tif (this.isCreated) {\r\n\t\t\t\t\tvar cmd = this.cmd;\r\n\t\t\t\t\tcmd.range.selectNodeContents(cmd.doc.body).collapse(false);\r\n\t\t\t\t\tcmd.select();\r\n\t\t\t\t}\r\n\t\t\t\treturn this;\r\n\t\t\t}\r\n\t});\r\n</script>\r\n\t";
    return TRUE;
}

function showdialog($message = "", $url = "", $alert_type = "alert", $extrajs = "", $time = 3) {
    $message = str_replace("'", "\\'", strip_tags($message));
    $paramjs = "";
    if($url != "") {
        $paramjs = "window.location.href ='".$url."'";
    }
    if($paramjs) {
        $paramjs = "function() {".$paramjs."}";
    }
    $modes = array(
        "alert" => "alert",
        "succ" => "succ",
    );
    $cover = $alert_type == "error" ? 1 : 0;
    $extra.= "showDialog('".$message."', '".$modes[$alert_type]."', '', ".($paramjs ? $paramjs : "").", ".$cover.", '', '', '', '', ".(is_numeric($time) ? $time : "").", '');";
    $extra = $extra ? "<script type=\"text/javascript\" reload=\"1\">".$extra."</script>" : "";
    if($extrajs != "" && substr(trim($extrajs) , 0, 8) != "<script>") {
        $extrajs = "<script type=\"text/javascript\" reload=\"1\">".$extrajs."</script>";
    }
    $extra .= $extrajs;
    ob_end_clean();
    @header("Expires: -1");
    @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
    @header("Pragma: no-cache");
    @header("Content-type: text/xml; charset=".CHARSET);
    $string = "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\r\n";
    $string.= "<root><![CDATA[".$message.$extra.output_ajax()."]]></root>";
    echo $string;
    exit();
}

function output_ajax() {
	$s = ob_get_contents();
	ob_end_clean();
	$s = preg_replace("/([\\x01-\\x08\\x0b-\\x0c\\x0e-\\x1f])+/", ' ', $s);
	$s = str_replace(array(chr(0), ']]>'), array(' ', ']]&gt;'), $s);
	return $s;
}

function check_identity($identity_id) {

	$result = array();
    require_once MALL_ROOT.'/system/libraries/simple_identity.php';
    $identity = new identity_db($identity_id);
    if($identity_id)
	{
		$result['identity_id'] = $identity_id;
		$result['province'] = $identity->getProvince()['name'];
		$result['city'] = $identity->getCity()['name'];
		$result['area'] = $identity->getArea()['name'];
		$result['age'] = $identity->age;
		$result['sex'] = $identity->sex;
	}
	return $result;

}

/**
 * @param string $face_img_code 身份证正面图片base64编码
 * @param string $back_img_code 身份证反面图片base64编码
 * @return array
 */
function get_identity_ocr($face_img_code, $back_img_code)
{
    $cloud_api_url = "https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";
    $appcode = "74d45ad8cd304f7a9d95b9e88cab454f";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
//根据API的要求，定义相对应的Content-Type
    array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
    $querys = "";
    $bodys = "{
    \"inputs\": [
        {
            \"image\": {
                \"dataType\": 50,
                \"dataValue\": \"$face_img_code\"    
            },
            \"configure\": {
                \"dataType\": 50,
                \"dataValue\": \"{\\\"side\\\":\\\"face\\\"}\"
            }
        },
        {
            \"image\": {
                \"dataType\": 50,
                \"dataValue\": \"$back_img_code\"    
            },
            \"configure\": {
                \"dataType\": 50,
                \"dataValue\": \"{\\\"side\\\":\\\"back\\\"}\"
            }
        }
    ]
}";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_URL, $cloud_api_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
    $curl_result = curl_exec($curl);
    $curl_json = json_decode($curl_result, true);
    $result = array();

    if(is_array($curl_json) and count($curl_json['outputs']) == 2)
    {
        $result['face'] = json_decode($curl_json['outputs'][0]['outputValue']['dataValue'], true);
        $result['back'] = json_decode($curl_json['outputs'][1]['outputValue']['dataValue'], true);
    }
    return $result;
}

/**
 * @param string $image_file 图片路径
 * @return string 图片的base64编码
 */
function base64EncodeImage ($image_file) {
    $base64_image = '';
    $image_info = getimagesize($image_file);
    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
    //$base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    $base64_image = chunk_split(base64_encode($image_data));
    return trim($base64_image);
}

/**
 * 营业执照验证
 * @param string $verify_img_data
 * @return array|mixed
 */
function company_card_verify($verify_img_data){
    $url = "https://dm-58.data.aliyun.com/rest/160601/ocr/ocr_business_license.json";

    $appcode = "74d45ad8cd304f7a9d95b9e88cab454f";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
//根据API的要求，定义相对应的Content-Type
    array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
    $querys = "";
    $bodys = "{\"inputs\":[{\"image\":{\"dataType\":50,\"dataValue\":\"$verify_img_data\"}}]}";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
    $api_result_str = curl_exec($curl);
//file_put_contents('qy_result.txt', $api_result_str);
//$api_result_str = file_get_contents('qy_result.txt');
    $api_result_str = json_decode($api_result_str, true);

    if(!empty($api_result_str['outputs'][0]['outputValue']['dataValue'])){
        $temp_info = json_decode($api_result_str['outputs'][0]['outputValue']['dataValue'], true);
    }
    if(is_array($temp_info)){
    	return $temp_info;
	}else{
    	return array();
	}

}

function getDistance($lat1,$lng1,$lat2,$lng2){
		
}

?>
