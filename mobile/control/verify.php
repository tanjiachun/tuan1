<?php
/**
 * API身份证号码验证
 */
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class verifyControl extends BaseMobileControl {
	public function indexOp() {

	}
	
	public function checkidOp() {
	    $member_token = empty($_POST['token']) ? '' : $_POST['token'];
        $identity_id = empty($_POST['identity_id']) ? '' : $_POST['identity_id'];
	    if($member_token){
            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_token = '$member_token'");
        }
        if(empty($member)){
            exit(json_encode(array('code'=>'1', 'msg'=>'用户不存在', 'data'=>array())));
        }

        $result = array(
            'code' => '1',
            'msg' => '身份证号码识别失败',
            'data' => array(),
        );

        $check_result  = check_identity($identity_id);
        if(!empty($check_result['area']))
        {
            $result['code'] = '0';
            $result['msg'] = '身份证信息识别成功';
            $result['data'] = $check_result;
        }
        print json_encode($result) ;
	}
}

?>