<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}
require_once MALL_ROOT.'/vendor/autoload.php';
use Yansongda\Pay\Pay;
class shopping_cartControl extends BaseMobileControl {
    /**
     * 获取用户所有地址数据
     */
    protected function get_member_address(){
        $address = array();
        $query = DB::query("SELECT * FROM " . DB::table('member_address') . " WHERE member_id='" .$this->member_id . "'");
        while ($value = DB::fetch($query)) {
            $address[] = $value;
        }
        return $address;
    }


    /**
     * 订单预生成信息获取接口
     */
    private function get_collect($collect_id){
        $member_favourite = DB::fetch_first("SELECT * FROM ".DB::table('member_favourite')." WHERE collect_id = '$collect_id' AND member_id = '$this->member_id'");
        if(empty($member_favourite)){
            exit(json_encode(array('code' => 1, 'msg' => '订单信息错误！')));
        }

        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id = '".$member_favourite['nurse_id']."'");
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id = '$this->member_id'");
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id = '".$member_favourite['agent_id']."'");
        $member_address = DB::fetch_first("SELECT * FROM ".DB::table('member_address')." WHERE member_id = '".$member['member_id']."' AND choose_state = '1'");
        $data = $member_favourite;
        $data['address'] = $member_address;
        $data['all_address'] = $this->get_member_address();
        if(empty($agent)){
            $data['agent']['agent_name'] = '个人家政人员';
            $data['agent']['agent_id'] = '0';
        }else{
            $data['agent']['agent_name'] = $agent['agent_name'];
            $data['agent']['agent_id'] = $agent['agent_id'];
        }
        if(empty($nurse)){
            exit(json_encode(array('code' => 1, 'msg' => '该家政人员不存在！')));
        }
        $data['nurse']['nurse_id'] = $nurse['nurse_id'];
        $data['nurse']['nurse_title'] = $nurse['nurse_nickname'].' '. $nurse['service_type'].' '. $nurse['nurse_special_service'];
        $data['nurse']['nurse_image'] = $nurse['nurse_image'];
        $data['nurse']['nurse_tips_msg'] = '';
        switch ($nurse['promise_state']){
            case '1':
                $data['nurse']['nurse_tips_msg'] = '不支持三小时';
                break;
            case '2':
                $data['nurse']['nurse_tips_msg'] = '支持三小时';
                break;
            case '3':
                $data['nurse']['nurse_tips_msg'] = '不支持三天';
                break;
            case '4':
                $data['nurse']['nurse_tips_msg'] = '支持三天';
                break;
        }
        if($member_favourite['nurse_discount'] == '0'){
            $data['nurse']['nurse_discount'] = '暂无优惠';
        }else{
            $data['nurse']['nurse_discount'] = $member_favourite['nurse_discount'] * 100 .'折优惠';
        }

        // 每笔订单最大可以使用团豆抵扣的金额
        $max_discount_price = $member_favourite['total_price'] * 0.02;



        // 最大团豆的抵扣金额和抵扣的团都数量
        if(($member['member_coin']/100) > $max_discount_price){
            $data['member_discount_price'] = (string)$max_discount_price;
            $data['member_coin_discount'] = (string)($max_discount_price * 100);
        }else{
            $data['member_discount_price'] = (string)($member['member_coin']/100);
            $data['member_coin_discount'] = (string)$member['member_coin'];
        }

        // 是否可以使用团豆折扣
        $data['use_coin_discount'] = '0';
        $data['member_coin_msg'] = '';
        if($member['member_coin'] >= 100){
            $data['use_coin_discount'] = '1';
            $data['member_coin_msg'] = "使用{$data['member_coin_discount']}个团豆豆可抵扣{$data['member_discount_price']}元(最高可抵扣订单总价的2%)";
        }else{
            $data['member_coin_msg'] = "您的团豆豆数量不足100，暂时无法使用团豆豆抵扣！(最高可抵扣订单总价的2%)";
        }

        return $data;
    }


    /**
     * 服务车列表
     * API:/mobile.php?act=shopping_cart
     * @internal param string $_POST ['token']
     */
	public function indexOp() {
        // 检测用户权限
        $this->check_authority();

        $query_field = 'sc.*, ns.nurse_id, ns.nurse_image, ns.member_phone, ns.agent_phone, ns.nurse_phone, ns.nurse_name, ns.nurse_type, ns.agent_id, ns.nurse_price, ns.service_price, ns.service_type, ns.nurse_special_service, ag.agent_name, ag.agent_other_phone_choose ';
        $query = DB::query("SELECT $query_field FROM ".DB::table('shopping_cart')." as sc LEFT JOIN ".DB::table('nurse')." as ns ON sc.nurse_id = ns.nurse_id LEFT JOIN ".DB::table('agent')." as ag ON ns.agent_id = ag.agent_id WHERE sc.member_id = '$this->member_id'");
        $shopping_list = array();
        while($value = DB::fetch($query)) {
            $contact_number = $value['member_phone'];
            //agent_id为0表示个人家政会员
            if($value['agent_id'] != 0){
                if(!empty($value['agent_phone'])){
                    $contact_number = $value['agent_phone'];
                }elseif(!empty($value['agent_phone'])){
                    $contact_number = $value['agent_other_phone_choose'];
                }
            }else{
                if(!empty($value['nurse_phone'])){
                    $contact_number = $value['nurse_phone'];
                }
            }
            $shopping_list[] = array(
                'shopping_cart_id' => $value['shopping_cart_id'],
                'member_id' => $value['member_id'],
                'nurse_id' => $value['nurse_id'],
                'agent_name' => $value['agent_name'],
                'agent_id' => $value['agent_id'],
                'nurse_name' => $value['nurse_name'],
                'nurse_image' => $value['nurse_image'],
                'nurse_type' => $value['nurse_type'],
                'service_type' => $value['service_type'],
                'nurse_price' => $value['nurse_price'],
                'service_price' => $value['service_price'],
                'nurse_special_service' => $value['nurse_special_service'],
                'nurse_phone' => $contact_number,
            );

        }
        exit(json_encode(array('code'=> 0,  'msg'=>'查询成功', 'data' => $shopping_list)));
	}

    /**
     * 加入服务车
     * API:/mobile.php?act=shopping_cart&op=favourite_add
     * @internal param string $_POST ['token']
     * @internal param int $_POST ['nurse_id']
     */

    public function favourite_addOp(){
        $nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id = '$this->member_id'");
        $nurse_info = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id = '$nurse_id'");
        if(empty($nurse_info) || empty($member)){
            exit(json_encode(array('code'=> 1, 'msg'=>'家政人员不存在！', data=>(object)array())));
        }
        $shopping_cart_info = DB::fetch_first("SELECT * FROM ".DB::table('shopping_cart')." WHERE member_id = '$this->member_id' AND nurse_id = '$nurse_id' ");
        if(!empty($shopping_cart_info)){
            exit(json_encode(array('code'=> 1, 'msg'=>'已经添加到服务车，请勿重复添加！', data=>(object)array())));
        }

        $data=array(
            'member_id'=>$this->member_id,
            'nurse_id'=>$nurse_id,
            'add_time'=>time()
        );
        $shopping_cart_id = DB::insert('shopping_cart', $data, true);
        if(!empty($shopping_cart_id)){
            exit(json_encode(array('code'=> 0, 'msg'=>'加入服务车成功', 'data'=>(object)array())));
        }
        exit(json_encode(array('code'=> 0,  'msg'=>'添加失败！', 'data'=>(object)array())));
    }


    /**
     * 订单预生成接口
     * 请求方法：POST
     * 请求地址：/mobile.php?act=shopping_cart&op=collect_add
     * 身份验证：是
     */
    public function collect_addOp(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);

            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id = '$nurse_id'");
            if(empty($nurse)){
                exit(json_encode(array('code' => 1, 'msg'=>'提交参数错误！')));
            }
            $work_duration = empty($_POST['work_duration']) ? 0 : intval($_POST['work_duration']);
            $work_duration_days = empty($_POST['work_duration_days']) ? 0 : intval($_POST['work_duration_days']);
            $work_duration_hours = empty($_POST['work_duration_hours']) ? 0 : intval($_POST['work_duration_hours']);
            $work_duration_mins = empty($_POST['work_duration_mins']) ? 0 : intval($_POST['work_duration_mins']);
            $work_area = empty($_POST['work_area']) ? 0 : intval($_POST['work_area']);
            $work_person = empty($_POST['work_person']) ? 0 : intval($_POST['work_person']);
            $work_machine = empty($_POST['work_machine']) ? 0 : intval($_POST['work_machine']);
            $work_cars = empty($_POST['work_cars']) ? 0 : intval($_POST['work_cars']);
            $car_price = empty($_POST['car_price']) ? 0 : intval($_POST['car_price']);
            $work_students = empty($_POST['work_students']) ? 0 : intval($_POST['work_students']);
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id = '$nurse_id'");
            $collect_details = '';

            switch ($nurse['nurse_type']){
                // 包月
                case '13':
                case '14':
                case '15':
                case '16':
                case '1':
                case '2':
                    // 折扣价服务费
                    $discount_service_price = $nurse['service_price'] * $nurse['nurse_discount'];
                    // 超过1个月每个月服务费优惠5元 个人不收取服务费
                    if($nurse['agent_id'] != '0'){
                        $total_price = ($work_duration * $discount_service_price) - (5 * ($work_duration - 1));
                    }else{
                        $total_price = 0 ;
                    }

                    // 服务费
                    $service_price = $total_price;
                    $all_total_price = $nurse['nurse_price'] + $total_price;

                    $nurse_price = $nurse['nurse_price'];
                    if($nurse['agent_id'] != '0'){
                        $collect_details = $work_duration .'月服务费' . '+本月工资';
                    }else{
                        $collect_details =  '按月付费';
                    }

                break;

                // 小时
                case '3':
                    // 实际工资价格
                    $discount_price = $nurse['nurse_price'] * $nurse['nurse_discount'];
                    // 总价格
                    $all_total_price = $work_duration_days * (($work_duration_hours * $discount_price) + ($work_duration_mins/60) * $discount_price);
                    // 服务费
                    $service_price = 0;
                    $nurse_price = $discount_price;

                    $collect_details = "每天{$work_duration_hours}小时{$work_duration_mins}分,共{$work_duration_days}天";
                    break;

                // 平方
                case '4':
                    // 实际工资价格
                    $discount_price = $nurse['nurse_price'] * $nurse['nurse_discount'];
                    // 总价格
                    $all_total_price = $work_area * $discount_price;
                    $service_price = 0;
                    $nurse_price = $discount_price;
                    $collect_details = "共{$work_area}平方";
                    break;

                // 月嫂
                case '5':
                case '6':
                    // 实际工资价格
                    $discount_price = $nurse['nurse_price'] * $nurse['nurse_discount'];
                    // 总价格
                    $all_total_price = $discount_price/26 * $work_duration_days;
                    $service_price = 0;
                    $nurse_price = $discount_price;
                    $collect_details = "共{$work_duration_days}天";
                    break;

                // 按次/电器维修
                case '7':
                case '8':
                    // 实际工资价格
                    $discount_price = $nurse['nurse_price'] * $nurse['nurse_discount'];
                    // 总价格
                    $all_total_price = $discount_price * $work_machine;
                    $service_price = 0;
                    $nurse_price = $discount_price;
                    $collect_details = "共{$work_machine}台设备";
                    break;

                // 按次/搬家
                case '9':
                case '10':
                    // 实际工资价格
                    $discount_price = $nurse['nurse_price'] * $nurse['nurse_discount'];
                    // 总价格
                    $all_total_price = $discount_price * $work_person + $car_price;
                    $service_price = 0;
                    $nurse_price = $discount_price;
                    $collect_details  = "共{$work_person}人";
                    if(!empty($work_cars) && $work_cars!=0){
                        $collect_details .= "+" . $work_cars . '吨车一辆';
                    }

                    break;

                // 学生 家教
                case '11':
                case '12':
                    // 实际工资价格
                    $discount_price = $nurse['nurse_price'] * $nurse['nurse_discount'];
                    // 不支持多余一个学生
                    if($nurse['students_state'] == '0' && $work_students > 1){
                        exit(json_encode(array('code' => 1, 'msg'=>'学生数量错误，最多只能带一个学生！')));
                    }
                    // 单个学生
                    if($nurse['students_state'] == '0'){
                        $all_total_price = $discount_price * $work_duration;
                    }
                    // 支持多个学生
                    if($nurse['students_state'] == '1'){
                        // 超过1个学生打5折
                        if($nurse['students_sale'] == '1'){
                            $all_total_price = $work_duration * ($discount_price + ($work_students - 1) * $discount_price * 0.5);
                        }else{
                            $all_total_price = $work_duration * $discount_price * $work_students;
                        }
                    }
                    $service_price = 0;
                    $nurse_price = $discount_price;
                    $collect_details  = '共' . $work_students . '个学生'.'+'.$work_duration.'个月';
                    break;
            }
            if(!isset($all_total_price) || $all_total_price < 1){
                exit(json_encode(array('code' => 1, 'msg'=>'数据错误，暂时无法提交！')));
            }



            $data=array(
                'favourite_type' => 'order',
                'member_id' => $this->member_id,
                'nurse_id' => $nurse_id,
                'agent_id' => $nurse['agent_id'],
                'nurse_type' => $nurse['nurse_type'],
                'member_phone' => $this->member['member_phone'],
                'work_duration' => $work_duration,
                'work_duration_days' => $work_duration_days,
                'work_duration_hours' => $work_duration_hours,
                'work_duration_mins' => $work_duration_mins,
                'work_area' => $work_area,
                'work_person' => $work_person,
                'work_machine' => $work_machine,
                'work_cars' => $work_cars,
                'car_price' => $car_price,
                'work_students' => $work_students,
                'service_price' => $service_price,
                'nurse_price' => $nurse_price,
                'total_price' => $all_total_price,
                'nurse_discount' => $nurse['nurse_discount'],
                'collect_details' => $collect_details,
                'add_time' => time()
            );
            $collect_id = DB::insert('member_favourite', $data, 1);
            if(!empty($collect_id)){
                exit(json_encode(array('code' => 0, 'data' => $this->get_collect($collect_id))));
            }else{
                // 后期需要日志记录
                exit(json_encode(array('code' => 1, 'msg'=> '系统错误!')));
            }
        } else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误！')));
        }
    }




    /**
     * 支付订单提交接口
     * 请求方法：POST
     * 请求地址：/mobile.php?act=shopping_cart&op=order
     * 身份验证：是
     */
    function orderOp(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $collect_id = empty($_POST['collect_id']) ? 0 : intval($_POST['collect_id']);
            $collect = $this->get_collect($collect_id);
            $nurse_book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE member_id = '$this->member_id' AND nurse_id = '".$collect['nurse_id']."' AND book_state = '10'");
            if(!empty($nurse_book)){
                exit(json_encode(array('code' => 1, 'msg'=>'您已经预约过该家政人员，请取消预约后重试')));
            }


            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$collect['nurse_id']."'");
            if(empty($nurse)){
                exit(json_encode(array('code' => 1, 'msg'=>'该家政人员已经下岗！')));
            }

            $book_message = empty($_POST['book_message']) ? '' : $_POST['book_message'];
            $member_address_id = empty($_POST['member_address_id']) ? '' : $_POST['member_address_id'];
            $use_member_coin = empty($_POST['use_member_coin']) ? '' : $_POST['use_member_coin'];

            $member_address = DB::fetch_first("SELECT * FROM ".DB::table('member_address')." WHERE member_address_id = '$member_address_id'");
            if(empty($member_address)){
                exit(json_encode(array('code' => 1, 'msg'=>'地址信息错误，无法提交订单！')));
            }

            $agent_id = $collect['agent_id'];
            $nurse_type = $collect['nurse_type'];
            $book_phone = $nurse['member_phone'];
            $book_details = $collect['collect_details'];
            $work_duration = $collect['work_duration'];
            $work_duration_days = $collect['work_duration_days'];
            $work_duration_hours = $collect['work_duration_hours'];
            $work_duration_mins = $collect['work_duration_mins'];
            $work_area = $collect['work_area'];
            $work_person = $collect['work_person'];
            $work_machine = $collect['work_machine'];
            $work_cars = $collect['work_cars'];
            $car_price = $collect['car_price'];
            $work_students = $collect['work_students'];
            $service_price = $collect['service_price'];
            $nurse_price = $collect['nurse_price'];
            $book_address = $member_address['member_areainfo'].$member_address['address_content'];
            $service_member_phone = $member_address['address_phone'];
            $service_member_name = $member_address['address_member_name'];

            $deposit_amount = $collect['total_price'];
            $member_coin_amount = $collect['member_coin_discount'];
            if($use_member_coin == '1'){
                $book_amount = $collect['total_price'] - $collect['member_discount_price'];
            }else{
                $book_amount = $collect['total_price'];
            }


            // @todo 发票流程需要编写和优化

//            $invoice_state = empty($_POST['invoice_state']) ? 0 : $_POST['invoice_state'];
//            $invoice_type = empty($_POST['invoice_type']) ? '' : $_POST['invoice_type'];
//            $invoice_title = empty($_POST['invoice_title']) ? '' : $_POST['invoice_title'];
//            $invoice_content = empty($_POST['invoice_content']) ? '' : $_POST['invoice_content'];
//            $invoice_membername = empty($_POST['invoice_membername']) ? '' : $_POST['invoice_membername'];
//            $invoice_provinceid = empty($_POST['invoice_provinceid']) ? 0 : intval($_POST['invoice_provinceid']);
//            $invoice_cityid = empty($_POST['invoice_cityid']) ? 0 : intval($_POST['invoice_cityid']);
//            $invoice_areaid = empty($_POST['invoice_areaid']) ? 0 : intval($_POST['invoice_areaid']);
//            $invoice_address = empty($_POST['invoice_address']) ? '' : $_POST['invoice_address'];
//            $unit_name = empty($_POST['unit_name']) ? '' : $_POST['unit_name'];
//            $invoice_code = empty($_POST['invoice_code']) ? '' : $_POST['invoice_code'];
//            $invoice_unit_membername=empty($_POST['invoice_unit_membername']) ? '' : $_POST['invoice_unit_membername'];
//            $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_provinceid'");
//            $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_cityid'");
//            $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_areaid'");
//            $invoice_areainfo = $member_provincename.$member_cityname.$member_areaname;
//            $invoice_data=array(
//                'member_id'=>$this->member_id,
//                'invoice_type'=>$invoice_type,
//                'invoice_title'=>$invoice_title,
//                'invoice_content'=>$invoice_content,
//                'invoice_membername'=>$invoice_membername,
//                'invoice_provinceid'=>$invoice_provinceid,
//                'invoice_cityid'=>$invoice_cityid,
//                'invoice_areaid'=>$invoice_areaid,
//                'invoice_areainfo'=>$invoice_areainfo,
//                'invoice_address'=>$invoice_address,
//                'unit_name'=>$unit_name,
//                'invoice_code'=>$invoice_code,
//                'invoice_unit_membername'=>$invoice_unit_membername,
//                'add_time'=>time()
//            );
//            $invoice_id=DB::insert('invoice',$invoice_data,1);


            $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
            if($member['member_coin'] < $member_coin_amount){
                exit(json_encode(array('code' => 1, 'msg'=>'团豆豆余额不足')));
            }
            $book_sn = makesn(8);
            // @todo 交易号长度需要优化
            $out_sn = date('YmdHis').random(18);
            $data=array(
                'book_sn'=>$book_sn,
                'out_sn'=>$out_sn,
                'member_id' => $this->member_id,
                'member_phone' => $this->member['member_phone'],
                'nurse_id'=>$collect['nurse_id'],
                'agent_id'=>$agent_id,
                'nurse_type'=>$nurse_type,
                'book_phone'=>$book_phone,
                'book_details'=>$book_details,
                'work_duration'=>$work_duration,
                'work_duration_days'=>$work_duration_days,
                'work_duration_hours'=>$work_duration_hours,
                'work_duration_mins'=>$work_duration_mins,
                'work_area'=>$work_area,
                'work_person'=>$work_person,
                'work_machine'=>$work_machine,
                'work_cars'=>$work_cars,
                'car_price'=>$car_price,
                'work_students'=>$work_students,
                'service_price'=>$service_price,
                'nurse_price'=>$nurse_price,
                'service_address'=>$book_address,
                'service_member_phone'=>$service_member_phone,
                'service_member_name'=>$service_member_name,
                'deposit_amount'=>$deposit_amount,
                'member_coin_amount'=>$member_coin_amount,
                'book_amount'=>$book_amount,
                'book_message'=>$book_message,
                'invoice_type'=> 0,
                'invoice_id'=> 0,
                'book_state'=>10,
                'add_time'=>time()
            );
            $book_id = DB::insert('nurse_book', $data, 1);
            if(!empty($book_id)){
                $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
                $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
                $coin_data=array(
                    'member_id'=>$this->member_id,
                    'book_id'=>$book_id,
                    'coin_count'=>-$member_coin_amount,
                    'get_type'=>'discount',
                    'get_state'=>1,
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin', $coin_data);
                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin-$member_coin_amount WHERE member_id='$this->member_id'");
                //短信通知家政人员
                $content=array(
                    'code'=>'123456'
                );
                if(empty($agent_id)){
                    $text_send = new Simple_SMS($book_phone, 'personal_order_reservation', $content);
                }else{
                    $text_send = new Simple_SMS($book_phone, 'company_order_reservation', $content);
                }
                $send_result = $text_send->async_send();
                exit(json_encode(array('code' => 0, 'data'=>array(
                    'book_sn' => $book_sn,
                    'book_amount' => (string)$book_amount,
                ))));
            }
        } else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误！')));
        }

    }

    /**
     * 支付订单提交接口
     * 请求方法：POST
     * 请求地址：/mobile.php?act=shopping_cart&op=payment
     * 身份验证：是
     */
    function paymentOp(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $book_sn = empty($_POST['book_sn']) ? '' : $_POST['book_sn'];
            $payment_code = empty($_POST['payment_code']) ? '' : $_POST['payment_code'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn' AND member_id = '$this->member_id'");
            if(empty($book)) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '10') {
                exit(json_encode(array('code' => 1, 'msg'=>'订单状态已经改变，请返回查看！')));
            }
            if (!in_array($payment_code, array('alipay', 'predeposit'))){
                exit(json_encode(array('code' => 1, 'msg'=>'支付方式错误，请重新选择支付方式！')));
            }

            // @todo 代码未测试
            if ($payment_code == 'predeposit'){
                if($this->member['available_predeposit'] < $book['book_amount']) {
                    exit(json_encode(array('code' => 1, 'msg'=>'账户余额不足，请选择其他支付方式！')));
                }
                $data = array(
                    'pdl_memberid' => $this->member['member_id'],
                    'pdl_memberphone' => $this->member['member_phone'],
                    'pdl_stage' => 'book',
                    'pdl_type' => 0,
                    'pdl_price' => $book['book_amount'],
                    'pdl_predeposit' => $this->member['available_predeposit'] - $book['book_amount'],
                    'pdl_desc' => '预约家政人员，预约单号: '.$book['book_sn'],
                    'pdl_addtime' => time(),
                );
                DB::insert('pd_log', $data);
                DB::query("UPDATE ".DB::table('member')." SET available_predeposit=available_predeposit-".$book['book_amount']." WHERE member_id='".$this->member_id."'");
                $book_data = array();
                $book_data['payment_name'] = '余额支付';
                $book_data['payment_code'] = 'predeposit';
                $book_data['book_state'] = 20;
                $book_data['payment_time'] = time();
                DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
                DB::query("UPDATE ".DB::table('nurse')." SET work_state=1 WHERE nurse_id='".$book['nurse_id']."'");

                if($book['book_type']!='father'){
                    //团豆豆消费（如果有） 财务表插入数据
                    if(!empty($book['member_coin_amount'])){
                        $finance_coin_data=array(
                            'finance_type'=>'coin',
                            'book_id'=>$book['book_id'],
                            'member_id'=>$book['member_id'],
                            'agent_id'=>$book['agent_id'],
                            'nurse_id'=>$book['nurse_id'],
                            'finance_state'=>1,
                            'finance_amount'=>intval($book['member_coin_amount']/100),
                            'finance_time'=>time()
                        );
                        DB::insert('finance',$finance_coin_data);
                    }
                    //在岗码生成
                    $book_code=num6();
                    $book_code_array=array(
                        'member_id'=>$this->member_id,
                        'book_id'=>$book['book_id'],
                        'book_sn'=>$book['book_sn'],
                        'code_value'=>$book_code,
                        'code_state'=>0,
                        'add_time'=>time()
                    );
                    $book_code_id=DB::insert('book_code',$book_code_array,1);
                    //到岗验证码 短信通知
                    send_work_code($book['member_phone'], 'start_work_code',$book_code);

                    //通知家政人员
                    $content =  array(
                        'code' =>''
                    );
                    if(empty($book['agent_id'])){
//                        send_text_code($book['book_phone'],'personal_order_payment');
                        $text_send = new Simple_SMS($book['book_phone'], 'personal_order_payment', $content);
                    }else{
//                        send_text_code($book['book_phone'],'company_order_payment');
                        $text_send = new Simple_SMS($book['book_phone'], 'company_order_payment', $content);
                    }
                    $send_result = $text_send->async_send();
                    //站内交易消息插入
                    if(!empty($book_code_id)){
                        $message_data=array(
                            'member_id'=>$this->member_id,
                            'book_id'=>$book['book_id'],
                            'book_sn'=>$book['book_sn'],
                            'message_type'=>'deal',
                            'message_content'=>'编号为'.$book['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_code.'请您妥善保管',
                            'add_time'=>time()
                        );
                        DB::insert('system_message',$message_data);
                    }
                    //家政人员收益统计
                    if(empty($book['service_price'])){
                        $nurse_get_amount=floor($book['deposit_amount']*0.93);
                    }else{
                        $nurse_get_amount=intval($book['deposit_amount'])-ceil(intval($book['service_price'])*0.2);
                    }
                    $profit_data = array(
                        'nurse_id' => $book['nurse_id'],
                        'agent_id' => $book['agent_id'],
                        'profit_stage' => 'order',
                        'profit_type' => 1,
                        'profit_amount' => $nurse_get_amount,
                        'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                        'is_freeze' => 1,
                        'book_id' => $book['book_id'],
                        'book_sn' => $book['book_sn'],
                        'add_time' => time(),
                    );
                    DB::insert('nurse_profit', $profit_data);
                    DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$nurse_get_amount.", pool_amount=pool_amount+".$nurse_get_amount." WHERE nurse_id='".$book['nurse_id']."'");
                    //机构收益
                    if(!empty($book['agent_id'])){
                        $agent_profit_data=array(
                            'nurse_id' => $book['nurse_id'],
                            'agent_id' => $book['agent_id'],
                            'profit_stage' => 'order',
                            'profit_type' => 1,
                            'profit_amount' => $nurse_get_amount,
                            'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                            'is_freeze' => 1,
                            'book_id' => $book['book_id'],
                            'book_sn' => $book['book_sn'],
                            'add_time' => time(),
                        );
                        DB::insert('agent_profit', $agent_profit_data);
                    }
                }
                //雇主团豆豆收益
                $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
                $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
                $member_add_coin=50;
                $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='payment' AND get_time=$now_date");
                if($member_add_coin+$member_get_coin>=200){
                    $member_add_coin=200-$member_get_coin;
                }
                $member_coin_data=array(
                    'member_id'=>$book['member_id'],
                    'book_id'=>$book['book_id'],
                    'coin_count'=>$member_add_coin,
                    'get_type'=>'payment',
                    'true_time'=>time(),
                    'get_time'=>$now_date
                );
                DB::insert('member_coin', $member_coin_data);
                DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");

                //家政人员销售统计
                DB::query("UPDATE ".DB::table('nurse')." SET nurse_salenum=nurse_salenum+1 WHERE nurse_id='".$book['nurse_id']."'");
                $date = date('Ymd');
                $nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='".$book['nurse_id']."' AND date='$date'");
                if(empty($nurse_stat)) {
                    $nurse_stat_array = array(
                        'nurse_id' => $book['nurse_id'],
                        'date' => $date,
                        'salenum' => 1,
                    );
                    DB::insert('nurse_stat', $nurse_stat_array);
                } else {
                    $nurse_stat_array = array(
                        'salenum' => $nurse_stat['salenum']+1,
                    );
                    DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$book['nurse_id'], 'date'=>$date));
                }
                $data = array(
                  'book_code' => $book_code,
                  'book_address' => array(
                      'service_address' => $book['service_address'],
                      'service_member_name' => $book['service_member_name'],
                      'service_member_phone' => $book['service_member_phone'],
                  )

                );
                exit(json_encode(array('code' => 0, 'msg' => '恭喜您，订单支付成功！', 'data'=>$data)));
            }else{
                DB::update('nurse_book', array('payment_code'=>$payment_code), array('book_id' => $book['book_id']));
                $pay_config = $this->alipay_payment($book['book_sn'], $book['book_amount'], '预约家政人员，预约单号：'.$book['book_sn']);
                exit(json_encode(array('code' => 0, 'data' => array('alipay_config' => $pay_config))));
            }


        }else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误！')));
        }
    }

    /**
     * 支付宝内网测试使用
     * @todo 正式上线前需要移除该代码改用支付宝回掉方式来获得支付结果
     */
    public function alipay_call_backOp(){
        if(!empty($_POST)){
            // 检测用户权限
            $this->check_authority();
            $book_sn = empty($_POST['book_sn']) ? '' : $_POST['book_sn'];
            $book = DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE book_sn='$book_sn' AND member_id = '$this->member_id'");
            if(empty($book)) {
                exit(json_encode(array('msg'=>'预约单不存在')));
            }
            if($book['book_state'] != '10') {
                exit(json_encode(array('code' => 1, 'msg'=>'订单状态已经改变，请返回查看！')));
            }

            $data = array(
                'pdl_memberid' => $this->member['member_id'],
                'pdl_memberphone' => $this->member['member_phone'],
                'pdl_stage' => 'book',
                'pdl_type' => 0,
                'pdl_price' => $book['book_amount'],
                'pdl_predeposit' => $this->member['available_predeposit'],
                'pdl_desc' => '支付宝支付 预约家政人员，预约单号: '.$book['book_sn'],
                'pdl_addtime' => time(),
            );
            DB::insert('pd_log', $data);

            $book_data = array();
            $book_data['payment_name'] = '支付宝';
            $book_data['payment_code'] = 'alipay';
            $book_data['book_state'] = 20;
            $book_data['payment_time'] = time();
            DB::update('nurse_book', $book_data, array('book_id'=>$book['book_id']));
            DB::query("UPDATE ".DB::table('nurse')." SET work_state=1 WHERE nurse_id='".$book['nurse_id']."'");

            if($book['book_type']!='father'){
                //团豆豆消费（如果有） 财务表插入数据
                if(!empty($book['member_coin_amount'])){
                    $finance_coin_data=array(
                        'finance_type'=>'coin',
                        'book_id'=>$book['book_id'],
                        'member_id'=>$book['member_id'],
                        'agent_id'=>$book['agent_id'],
                        'nurse_id'=>$book['nurse_id'],
                        'finance_state'=>1,
                        'finance_amount'=>intval($book['member_coin_amount']/100),
                        'finance_time'=>time()
                    );
                    DB::insert('finance',$finance_coin_data);
                }
                //在岗码生成
                $book_code=num6();
                $book_code_array=array(
                    'member_id'=>$this->member_id,
                    'book_id'=>$book['book_id'],
                    'book_sn'=>$book['book_sn'],
                    'code_value'=>$book_code,
                    'code_state'=>0,
                    'add_time'=>time()
                );
                $book_code_id=DB::insert('book_code',$book_code_array,1);
                //到岗验证码 短信通知
                send_work_code($book['member_phone'], 'start_work_code',$book_code);

                //通知家政人员
                $content =  array(
                    'code' =>''
                );
                if(empty($book['agent_id'])){
                    $text_send = new Simple_SMS($book['book_phone'], 'personal_order_payment', $content);
                }else{
                    $text_send = new Simple_SMS($book['book_phone'], 'company_order_payment', $content);
                }
                $send_result = $text_send->async_send();
                //站内交易消息插入
                if(!empty($book_code_id)){
                    $message_data=array(
                        'member_id'=>$this->member_id,
                        'book_id'=>$book['book_id'],
                        'book_sn'=>$book['book_sn'],
                        'message_type'=>'deal',
                        'message_content'=>'编号为'.$book['book_sn'].'的订单您已成功付款 ，本次交易的到岗验证码为'.$book_code.'请您妥善保管',
                        'add_time'=>time()
                    );
                    DB::insert('system_message',$message_data);
                }
                //家政人员收益统计
                if(empty($book['service_price'])){
                    $nurse_get_amount=floor($book['deposit_amount']*0.93);
                }else{
                    $nurse_get_amount=intval($book['deposit_amount'])-ceil(intval($book['service_price'])*0.2);
                }
                $profit_data = array(
                    'nurse_id' => $book['nurse_id'],
                    'agent_id' => $book['agent_id'],
                    'profit_stage' => 'order',
                    'profit_type' => 1,
                    'profit_amount' => $nurse_get_amount,
                    'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                    'is_freeze' => 1,
                    'book_id' => $book['book_id'],
                    'book_sn' => $book['book_sn'],
                    'add_time' => time(),
                );
                DB::insert('nurse_profit', $profit_data);
                DB::query("UPDATE ".DB::table('nurse')." SET plat_amount=plat_amount+".$nurse_get_amount.", pool_amount=pool_amount+".$nurse_get_amount." WHERE nurse_id='".$book['nurse_id']."'");
                //机构收益
                if(!empty($book['agent_id'])){
                    $agent_profit_data=array(
                        'nurse_id' => $book['nurse_id'],
                        'agent_id' => $book['agent_id'],
                        'profit_stage' => 'order',
                        'profit_type' => 1,
                        'profit_amount' => $nurse_get_amount,
                        'profit_desc' => '预约家政人员，预约单号：'.$book['book_sn'],
                        'is_freeze' => 1,
                        'book_id' => $book['book_id'],
                        'book_sn' => $book['book_sn'],
                        'add_time' => time(),
                    );
                    DB::insert('agent_profit', $agent_profit_data);
                }
            }
            //雇主团豆豆收益
            $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
            $now_date=strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
            $member_add_coin=50;
            $member_get_coin=DB::result_first("SELECT SUM(coin_count) FROM ".DB::table('member_coin')." WHERE member_id='".$book['member_id']."' AND get_type='payment' AND get_time=$now_date");
            if($member_add_coin+$member_get_coin>=200){
                $member_add_coin=200-$member_get_coin;
            }
            $member_coin_data=array(
                'member_id'=>$book['member_id'],
                'book_id'=>$book['book_id'],
                'coin_count'=>$member_add_coin,
                'get_type'=>'payment',
                'true_time'=>time(),
                'get_time'=>$now_date
            );
            DB::insert('member_coin', $member_coin_data);
            DB::query("UPDATE ".DB::table('member')." SET member_coin=member_coin+$member_add_coin WHERE member_id='".$book['member_id']."'");

            //家政人员销售统计
            DB::query("UPDATE ".DB::table('nurse')." SET nurse_salenum=nurse_salenum+1 WHERE nurse_id='".$book['nurse_id']."'");
            $date = date('Ymd');
            $nurse_stat = DB::fetch_first("SELECT * FROM ".DB::table('nurse_stat')." WHERE nurse_id='".$book['nurse_id']."' AND date='$date'");
            if(empty($nurse_stat)) {
                $nurse_stat_array = array(
                    'nurse_id' => $book['nurse_id'],
                    'date' => $date,
                    'salenum' => 1,
                );
                DB::insert('nurse_stat', $nurse_stat_array);
            } else {
                $nurse_stat_array = array(
                    'salenum' => $nurse_stat['salenum']+1,
                );
                DB::update('nurse_stat', $nurse_stat_array, array('nurse_id'=>$book['nurse_id'], 'date'=>$date));
            }
            $data = array(
                'book_code' => $book_code,
                'book_address' => array(
                    'service_address' => $book['service_address'],
                    'service_member_name' => $book['service_member_name'],
                    'service_member_phone' => $book['service_member_phone'],
                )

            );
            exit(json_encode(array('code' => 0, 'msg' => '恭喜您，订单支付成功！', 'data'=>$data)));

        }else{
            exit(json_encode(array('code' => 1, 'msg'=>'操作错误！')));
        }
    }

    /**
     * 支付宝支付
     */
    private function alipay_payment($out_trade_no, $total_amount, $subject){
        if(empty($out_trade_no) || empty($total_amount) || empty($subject)){
            return false;
        }

        /**
         * @todo 内部测试使用 后期一定要修改
         */
        $config = [
            'alipay' => [
                'app_id' => '2017040606574715',             // 支付宝提供的 APP_ID
                'notify_url' => 'http://www.tuanjiazheng.com/',             // 支付宝提供的 APP_ID
                'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtH7ZDx0QEinlu1IXOCVXYPp9sdG9SGvmvar/UVmACBxuUM7I8/eYr52HdYOgYcnwPa5tZ64PNdnwtGyNyORyNH6tyJaF8f+GoRMrZTveAq/iZCD2ai/zUfQYoVzL7/75OHQxmVKneNM4Ex5M0/2O7GiNC9WUEgUU1EICMJ1Hcd2MCvdtjPCiQAg+9lADeNeo12KB+yZY4ucjBoDJIwI44AqMW/36grB+h1rqzaMi07YmlGnQm2wYDPPBCjrXiEol+yKStej0Hdj8btd460GGqODJUvCNhz8r8b3c6q3mM5occmKwzV2DUuYmgroswR9CaHqeRZsniXR2MMdVOnGZdwIDAQAB', // 支付宝公钥
                'private_key' => 'MIIEpAIBAAKCAQEA4OSMx10Y77R/qQOpYU8krdZb0i2Cld8HhFuxz1r4Y2lue3FfKbKHy65P+Rz+l+Pc+IWvlnZPxiremak1vbHoz4oDoR+6lt1uKOjS98IrP12fDgcb/3K6PCbuCjP8g2XsmzFHoT10lsq25dFAtDF3zC4C6d+p23zwSb1KF7UB2RJZ3tG8v93vqXHuqI9mMtYisXRbjOick6tgfqo9serJDGJ7MNSv+tAd91g7ziFQolTflhPrXq6MO5+n2G582RFU1VQHVIBMLCqSkFgOF4CMRDOm2z1uNMoasgk2gkZ5ds2VHRoWukEVjaXOmBlSJoU7ZhyRVt9Md/1YA9FgT3IL1QIDAQABAoIBAQDGTwMMsb1Rcjq4EPCFTEKtv74MIfFqstZwEmNC05cAInI4DAq8PB+ercD7jGO8EEREKa5h4UYLGrZbjWhEM+N1TuBQbigc7Hk9PcD6lI+KOsYMmpxHryUl8bYp7RmXzILNfrEJL6Xlai/Ji7Ggs5ZNx6zuCjo/v3Yrr+zr5sMQY4TtThwcQ3Jo+4GRYEpMxaW5TknoHAmf1ZijPBuRj/4xqMi2tSnBRvK+VVbVniZu/s32yRPR53NZ/9y3xPZtMVnq1tKX4toN8EJ5rUTTHBNFLIfXnUIevykHpv2916cLQX3+d2ZAx2VRHARjKnQnEdPS/7ulff/ACwJl+as9GcgpAoGBAPXfcVodYK+ni+HUaOw+wJvkhC6VgGBEVlnGerSMb2O7dZimvQdTLvCFR/UekbilWfdryt1wlY62Y05Vw4BJXoqSCsDutBVrBgRcxzviEH3IkjU6hW5Fkt4O8SMpSxb4XpPVE+Kf/lX5rLkUxP96hGqDRa4xEpNJ7Jk/hjevbwO7AoGBAOon4xy69iwvuuRqdsgk5qypmxUCFVP5gM8Bl/PBl1uiUJJ8WgQBPfL+ICpJTF3Is92o0IHT7e1BrOiT8hfmzHprL9dmj+XGDLZYSAKbfz/MlQ/515OmzxVpaJ8YowzKUrgr7I96+XFsuADPxE/movPlVLffIvd42LJuBJPFOg2vAoGAe0HWe0x66dPeZgmrx7L1r7DxKsE7qAbaGwtK/DPiq2aQX0RHQuLKhzzpg9IdTyllXomlGzmTYAXC5sRLy4X8ZQ8tYObJVGtgitNP9Y8woT3pPdHmjg9GvxdSf0P2Jr+/+fA5lcSLJeKSWf41e7WZqCPCzL5BB6FpgLG9wTCrQH0CgYEAp2Hb962uS+fSsXVpc6jtPIABXAMhO+YfLdr1xEme6OIetpgtwK8imZEPbSr4bBIMgWcrpaLZccCA2cDoTi2t/dShXljyhE4OyEpTL4n4bKLR65mtgN69KZEwOIpkA8CdYQoFEIvXxDzzS6E5s+QqmC5XYRkg5cZ9IQ+ENh3GYgECgYA95AgTTCGPLKpHlnmhs6bLImBKTtGz6AFhB+PM1ffeC0bDwTtyKMe6NDHIFg+Mee7wJWVU1a4Bb+YG2ELlyxy8Gc84RTOtAS/m+IcdvqeEzEHrIhcMXHpjFmF4dlTkXMQ57VAjK9Jl/clK2S33/o7ngHYZ8eNNRMmQZnCl548wKw==' //应用私钥
            ],
        ];
        $config_biz = [
            'out_trade_no' => $out_trade_no,                 // 订单号
            'total_amount' => $total_amount,                 // 订单金额，单位：元
            'subject' => $subject,   // 订单商品标题
        ];
        $pay = new Pay($config);
        try {
            $app_pay = $pay->driver('alipay')->gateway('app')->pay($config_biz);
            return $app_pay;
        }catch (Exception  $e ){
            // @todo 异常处理
            return false;
        }
    }

}

?>