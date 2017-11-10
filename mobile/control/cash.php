<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
use Yansongda\Pay\Pay;
class cashControl extends BaseMobileControl {
  public function indexOp(){
      $this->check_authority();
      $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
      if($member['member_real_state'] != '1'){
          exit(json_encode(array('code'=>1,'msg'=>'为了您的资金安全，提现前请先实名认证！')));
      }
      $pdc_amount = empty($_POST['pdc_amount']) ? 0 : intval($_POST['pdc_amount']);
      if(empty($pdc_amount)) {
          exit(json_encode(array('code'=>1,'msg'=>'请输入提现金额')));
      }
      if($pdc_amount < 10) {
          exit(json_encode(array('code'=>1,'msg'=>'提现金额不能小于10')));
      }
      if($pdc_amount > 50000) {
          exit(json_encode(array('code'=>1,'msg'=>'提现金额不能大于50000')));
      }
      if($pdc_amount > $member['available_predeposit']) {
          exit(json_encode(array('code'=>1,'msg'=>'您的可提现金额只有￥'.$member['available_predeposit'])));
      }
      exit(json_encode(array('code'=>0,'msg'=>'操作成功','pdc_amount'=>$pdc_amount)));
  }
  public function step2Op(){
      $this->check_authority();
      $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
      if($member['member_real_state'] != '1'){
          exit(json_encode(array('code'=>1,'msg'=>'为了您的资金安全，提现前请先实名认证！')));
      }
      $pdc_amount = empty($_POST['pdc_amount']) ? 0 : intval($_POST['pdc_amount']);
      $pdc_code = empty($_POST['pdc_code']) ? '' : $_POST['pdc_code'];
      $alipay_card = empty($_POST['alipay_card']) ? '' : $_POST['alipay_card'];
      $weixin_card = empty($_POST['weixin_card']) ? '' : $_POST['weixin_card'];
      $bank_membername = empty($_POST['bank_membername']) ? '' : $_POST['bank_membername'];
      $bank_deposit = empty($_POST['bank_deposit']) ? '' : $_POST['bank_deposit'];
      $bank_card = empty($_POST['bank_card']) ? '' : $_POST['bank_card'];
      if(empty($pdc_amount)) {
          exit(json_encode(array('code'=>1,'msg'=>'请输入提现金额')));
      }
      if($pdc_amount < 10) {
          exit(json_encode(array('code'=>1,'msg'=>'提现金额不能小于10')));
      }
      if($pdc_amount > 50000) {
          exit(json_encode(array('code'=>1,'msg'=>'提现金额不能大于50000')));
      }
      if($pdc_amount > $member['available_predeposit']) {
          exit(json_encode(array('code'=>1,'msg'=>'您的可提现金额只有￥'.$member['available_predeposit'])));
      }
      if($pdc_code == 'alipay') {
          if(empty($alipay_card)) {
              exit(json_encode(array('code'=>1,'msg'=>'请输入支付宝帐号')));
          }
      } elseif($pdc_code == 'weixin') {
          if(empty($weixin_card)) {
              exit(json_encode(array('code'=>1,'msg'=>'请输入微信号')));
          }
      } elseif($pdc_code == 'bank') {
          if(empty($bank_membername)) {
              exit(json_encode(array('code'=>1,'msg'=>'请输入收款人')));
          }
          if(empty($bank_deposit)) {
              exit(json_encode(array('code'=>1,'msg'=>'请输入开户行')));
          }
          if(empty($bank_card)) {
              exit(json_encode(array('code'=>1,'msg'=>'请输入银行卡号')));
          }
      }
      $pdc_sn = makeoutsn(5);
      $data = array(
          'pdc_sn' => $pdc_sn,
          'member_id' => $this->member_id,
          'pdc_code' => $pdc_code,
          'pdc_amount' => $pdc_amount,
          'pdc_state' => 3,
          'add_time' => time(),
      );
      if($pdc_code == 'alipay') {
          $data['alipay_card'] = $alipay_card;
      } elseif($pdc_code == 'weixin') {
          $data['weixin_card'] = $weixin_card;
      } elseif($pdc_code == 'bank') {
          $data['bank_membername'] = $bank_membername;
          $data['bank_deposit'] = $bank_deposit;
          $data['bank_card'] = $bank_card;
      }
      $pdc_id = DB::insert('pd_cash', $data, 1);
      if(!empty($pdc_id)) {
          $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
          $data = array(
              'pdl_memberid' => $member['member_id'],
              'pdl_memberphone' => $member['member_phone'],
              'pdl_stage' => 'cash',
              'pdl_type' => 0,
              'pdl_price' => $pdc_amount,
              'pdl_predeposit' => $member['available_predeposit']-$pdc_amount,
              'pdl_desc' => '申请提现，提现单号: '.$pdc_sn,
              'pdl_addtime' => time(),
          );
          DB::insert('pd_log', $data);
          DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit-$pdc_amount WHERE member_id='".$member['member_id']."'");
          dsetcookie('mallpdcamount', '', -3600);
          /**
           * !!!仅限测试期间使用!!!
           * @todo 临时提现功能实现，后期必须分离内网处理！！！
           * 2017/9/29
           */
          $config = [
              'alipay' => [
                  'app_id' => '2017040606574715',             // 支付宝提供的 APP_ID
                  'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtH7ZDx0QEinlu1IXOCVXYPp9sdG9SGvmvar/UVmACBxuUM7I8/eYr52HdYOgYcnwPa5tZ64PNdnwtGyNyORyNH6tyJaF8f+GoRMrZTveAq/iZCD2ai/zUfQYoVzL7/75OHQxmVKneNM4Ex5M0/2O7GiNC9WUEgUU1EICMJ1Hcd2MCvdtjPCiQAg+9lADeNeo12KB+yZY4ucjBoDJIwI44AqMW/36grB+h1rqzaMi07YmlGnQm2wYDPPBCjrXiEol+yKStej0Hdj8btd460GGqODJUvCNhz8r8b3c6q3mM5occmKwzV2DUuYmgroswR9CaHqeRZsniXR2MMdVOnGZdwIDAQAB', // 支付宝公钥
                  'private_key' => 'MIIEpAIBAAKCAQEA4OSMx10Y77R/qQOpYU8krdZb0i2Cld8HhFuxz1r4Y2lue3FfKbKHy65P+Rz+l+Pc+IWvlnZPxiremak1vbHoz4oDoR+6lt1uKOjS98IrP12fDgcb/3K6PCbuCjP8g2XsmzFHoT10lsq25dFAtDF3zC4C6d+p23zwSb1KF7UB2RJZ3tG8v93vqXHuqI9mMtYisXRbjOick6tgfqo9serJDGJ7MNSv+tAd91g7ziFQolTflhPrXq6MO5+n2G582RFU1VQHVIBMLCqSkFgOF4CMRDOm2z1uNMoasgk2gkZ5ds2VHRoWukEVjaXOmBlSJoU7ZhyRVt9Md/1YA9FgT3IL1QIDAQABAoIBAQDGTwMMsb1Rcjq4EPCFTEKtv74MIfFqstZwEmNC05cAInI4DAq8PB+ercD7jGO8EEREKa5h4UYLGrZbjWhEM+N1TuBQbigc7Hk9PcD6lI+KOsYMmpxHryUl8bYp7RmXzILNfrEJL6Xlai/Ji7Ggs5ZNx6zuCjo/v3Yrr+zr5sMQY4TtThwcQ3Jo+4GRYEpMxaW5TknoHAmf1ZijPBuRj/4xqMi2tSnBRvK+VVbVniZu/s32yRPR53NZ/9y3xPZtMVnq1tKX4toN8EJ5rUTTHBNFLIfXnUIevykHpv2916cLQX3+d2ZAx2VRHARjKnQnEdPS/7ulff/ACwJl+as9GcgpAoGBAPXfcVodYK+ni+HUaOw+wJvkhC6VgGBEVlnGerSMb2O7dZimvQdTLvCFR/UekbilWfdryt1wlY62Y05Vw4BJXoqSCsDutBVrBgRcxzviEH3IkjU6hW5Fkt4O8SMpSxb4XpPVE+Kf/lX5rLkUxP96hGqDRa4xEpNJ7Jk/hjevbwO7AoGBAOon4xy69iwvuuRqdsgk5qypmxUCFVP5gM8Bl/PBl1uiUJJ8WgQBPfL+ICpJTF3Is92o0IHT7e1BrOiT8hfmzHprL9dmj+XGDLZYSAKbfz/MlQ/515OmzxVpaJ8YowzKUrgr7I96+XFsuADPxE/movPlVLffIvd42LJuBJPFOg2vAoGAe0HWe0x66dPeZgmrx7L1r7DxKsE7qAbaGwtK/DPiq2aQX0RHQuLKhzzpg9IdTyllXomlGzmTYAXC5sRLy4X8ZQ8tYObJVGtgitNP9Y8woT3pPdHmjg9GvxdSf0P2Jr+/+fA5lcSLJeKSWf41e7WZqCPCzL5BB6FpgLG9wTCrQH0CgYEAp2Hb962uS+fSsXVpc6jtPIABXAMhO+YfLdr1xEme6OIetpgtwK8imZEPbSr4bBIMgWcrpaLZccCA2cDoTi2t/dShXljyhE4OyEpTL4n4bKLR65mtgN69KZEwOIpkA8CdYQoFEIvXxDzzS6E5s+QqmC5XYRkg5cZ9IQ+ENh3GYgECgYA95AgTTCGPLKpHlnmhs6bLImBKTtGz6AFhB+PM1ffeC0bDwTtyKMe6NDHIFg+Mee7wJWVU1a4Bb+YG2ELlyxy8Gc84RTOtAS/m+IcdvqeEzEHrIhcMXHpjFmF4dlTkXMQ57VAjK9Jl/clK2S33/o7ngHYZ8eNNRMmQZnCl548wKw==' //应用私钥
              ],
          ];
          $config_biz = [
              'out_biz_no' => $pdc_sn,                      // 订单号
              'payee_type' => 'ALIPAY_LOGONID',        // 收款方账户类型(ALIPAY_LOGONID | ALIPAY_USERID)
              'payee_account' => $alipay_card,   // 收款方账户
              'amount' => $pdc_amount,                        // 转账金额
              'payer_show_name' => '团家政',             // 付款方姓名
              'payee_real_name' => $member['member_truename'],             // 收款方真实姓名
              'remark' => '团家政用户提现',                      // 转账备注
          ];
          $pay = new Pay($config);
          try {
              $transfer = $pay->driver('alipay')->gateway('transfer')->pay($config_biz);
//                    exit(json_encode($transfer));
              if(is_array($transfer)){
                  DB::query("UPDATE ".DB::table('pd_cash')." SET pay_sucess_id='".$transfer[order_id]."', pdc_state='1', pay_msg='付款成功' WHERE pdc_sn='".$pdc_sn."'");
                  $finance_data=array(
                      'finance_type'=>'deposit',
                      'member_id'=>$member['member_id'],
                      'finance_state'=>1,
                      'finance_amount'=>$pdc_amount,
                      'finance_time'=>time()
                  );
                  DB::insert('finance',$finance_data);
              }else{
//                        exit(json_encode(array('msg'=>'提现申请已经提交，请等待处理')));
              }
//                    exit($transfer);
          }catch (Exception  $e ){
              if(!empty($e->raw['alipay_fund_trans_toaccount_transfer_response'])){
                  DB::query("UPDATE ".DB::table('pd_cash')." SET pay_msg='".json_encode($e->raw['alipay_fund_trans_toaccount_transfer_response'])."', pdc_state='2'  WHERE pdc_sn='".$pdc_sn."'");
              }else{
                  DB::query("UPDATE ".DB::table('pd_cash')." SET pay_msg='".$e->getMessage()."', pdc_state='2'  WHERE pdc_sn='".$pdc_sn."'");
              }
              $data = array(
                  'pdl_memberid' => $member['member_id'],
                  'pdl_memberphone' => $member['member_phone'],
                  'pdl_stage' => 'cash',
                  'pdl_type' => 1,
                  'pdl_price' => $pdc_amount,
                  'pdl_predeposit' => $member['available_predeposit'],
                  'pdl_desc' => '提现失败退款: '.$pdc_sn.' 失败原因：'.$e->raw['alipay_fund_trans_toaccount_transfer_response']['sub_msg'],
                  'pdl_addtime' => time()+1,
              );
              DB::insert('pd_log', $data);
              DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit+$pdc_amount WHERE member_id='".$member['member_id']."'");
          }
          exit(json_encode(array('code'=>0)));
      } else {
          exit(json_encode(array('code'=>1,'msg'=>'网路繁忙，请稍候重试')));
      }


  }
}
?>