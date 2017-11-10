<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class place_orderControl extends BaseHomeControl {
    public function indexOp() {
        if(empty($this->member_id)) {
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $collect_id=empty($_GET['collect_id']) ? 0 : $_GET['collect_id'];
        $member_favourite = DB::fetch_first("SELECT * FROM ".DB::table('member_favourite')." WHERE collect_id='$collect_id'");
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='".$member_favourite['nurse_id']."'");
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='".$member_favourite['agent_id']."'");
        $member_address=DB::fetch_first("SELECT * FROM ".DB::table('member_address')." WHERE member_id='".$member['member_id']."' AND choose_state=1");
        $discount_price=ceil($member_favourite['total_price']*0.02);
        $member_discount_price=0;
        if(intval($member['member_coin'])/100<=$discount_price){
            $member_discount_price+=intval($member['member_coin'])/100;
        }else{
            $member_discount_price+=$discount_price;
        }
        if(empty($nurse)) {
            $this->showmessage('请选择要预约的家政人员', 'index.php?act=index&op=nurse', 'info');
        }
//        if($nurse['nurse_type']==2 || $nurse['nurse_type']==4){
//            $this->showmessage('该家政人员暂时不能接受预约', 'index.php?act=index&op=nurse', 'info');
//        }
        $query=DB::query("SELECT * FROM ".DB::table("member_address")." WHERE member_id='$this->member_id' AND show_state=0 ORDER BY address_time DESC");
        while($value=DB::fetch($query)){
            $address_list[]=$value;
        }
        $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
        while($value = DB::fetch($query)) {
            $province_list[] = $value;
        }
        if(!empty($member_provinceid)) {
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_provinceid' ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                $member_city_list[] = $value;
            }
        }
        if(!empty($member_cityid)) {
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_cityid' ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                $member_area_list[] = $value;
            }
        }

        $curmodule = 'home';
        $bodyclass = '';
        include(template('place_order'));
    }

    public function order_address_setOp(){
        $member_address_id=empty($_GET['member_address_id']) ? 0 : $_GET['member_address_id'];
        $member_address = DB::fetch_first("SELECT * FROM ".DB::table('member_address')." WHERE member_address_id='$member_address_id'");
        $member_address['member_provincename']=DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='".$member_address['member_provinceid']."'");
        $member_address['member_cityname']=DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='".$member_address['member_cityid']."'");
        $member_address['member_areaname']=DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='".$member_address['member_areaid']."'");
        exit(json_encode(array('done'=>'true','member_address'=>$member_address)));
    }

    function order_address_resumeOp(){
        if(submitcheck()) {
            $member_address_id=empty($_POST['member_address_id']) ? 0 : intval($_POST['member_address_id']);
            if(empty($member_address_id)){
                exit(json_encode(array('msg'=>'该地址不存在')));
            }
            $member_provinceid = empty($_POST['member_provinceid']) ? 0 : intval($_POST['member_provinceid']);
            $member_cityid = empty($_POST['member_cityid']) ? 0 : intval($_POST['member_cityid']);
            $member_areaid = empty($_POST['member_areaid']) ? 0 : intval($_POST['member_areaid']);
            $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_provinceid'");
            $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_cityid'");
            $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_areaid'");
            $member_areainfo = $member_provincename.$member_cityname.$member_areaname;
            $address_content=empty($_POST['address_content']) ? '' : $_POST['address_content'];
            $address_member_name=empty($_POST['address_member_name']) ? '' : $_POST['address_member_name'];
            $address_phone=empty($_POST['address_phone']) ? '' : $_POST['address_phone'];
            $member_selected = empty($_POST['member_selected']) ? 0 : intval($_POST['member_selected']);
            if(empty($member_provinceid)){
                exit(json_encode(array('msg'=>'请选择省份')));
            }
            if(empty($member_cityid)){
                exit(json_encode(array('msg'=>'请选择市')));
            }
            if(empty($address_content)){
                exit(json_encode(array('msg'=>'请填写详细地址')));
            }
            if(empty($address_member_name)){
                exit(json_encode(array('msg'=>'请填写联系人姓名')));
            }
            if(empty($address_phone)){
                exit(json_encode(array('msg'=>'请填写联系号码')));
            }
            $data=array(
                'member_provinceid'=>$member_provinceid,
                'member_cityid'=>$member_cityid,
                'member_areaid'=>$member_areaid,
                'member_areainfo'=>$member_areainfo,
                'address_content'=>$address_content,
                'address_member_name'=>$address_member_name,
                'address_phone'=>$address_phone,
                'address_time'=>time()
            );
            DB::update('member_address', $data, array('member_address_id'=>$member_address_id));
            exit(json_encode(array('done'=>'true')));
        }else{
            $member_address_id = empty($_GET['member_address_id']) ? 0 : intval($_GET['member_address_id']);
            if (empty($member_address_id)) {

            }
            $address = DB::fetch_first("SELECT * FROM " . DB::table('member_address') . " WHERE member_address_id='$member_address_id'");
            $query = DB::query("SELECT * FROM " . DB::table('district') . " WHERE parent_id=0 ORDER BY district_sort ASC");
            while ($value = DB::fetch($query)) {
                $province_list[] = $value;
            }
            $query = DB::query("SELECT * FROM " . DB::table('district') . " WHERE parent_id='" . $address['member_provinceid'] . "' ORDER BY district_sort ASC");
            while ($value = DB::fetch($query)) {
                $city_list[] = $value;
            }
            $query = DB::query("SELECT * FROM " . DB::table('district') . " WHERE parent_id='" . $address['member_cityid'] . "' ORDER BY district_sort ASC");
            while ($value = DB::fetch($query)) {
                $area_list[] = $value;
            }
        }
        $curmodule = 'home';
        $bodyclass = '';
        include(template('order_address_resume'));
    }

    public function address_addOp(){
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_address')." WHERE member_id='$this->member_id' AND show_state=0");
        if($count>=20){
            exit(json_encode(array('msg'=>'最多可以保存20条地址')));
        }
        $member_provinceid = empty($_POST['member_provinceid']) ? 0 : intval($_POST['member_provinceid']);
        $member_cityid = empty($_POST['member_cityid']) ? 0 : intval($_POST['member_cityid']);
        $member_areaid = empty($_POST['member_areaid']) ? 0 : intval($_POST['member_areaid']);
        $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_provinceid'");
        $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_cityid'");
        $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_areaid'");
        $member_areainfo = $member_provincename.$member_cityname.$member_areaname;
        $address_content=empty($_POST['address_content']) ? '' : $_POST['address_content'];
        $address_member_name=empty($_POST['address_member_name']) ? '' : $_POST['address_member_name'];
        $address_phone=empty($_POST['address_phone']) ? '' : $_POST['address_phone'];
        $member_selected = empty($_POST['member_selected']) ? 0 : intval($_POST['member_selected']);
        if(empty($member_provinceid)){
            exit(json_encode(array('msg'=>'请选择省份')));
        }
        if(empty($member_cityid)){
            exit(json_encode(array('msg'=>'请选择市')));
        }
        if(empty($address_content)){
            exit(json_encode(array('msg'=>'请填写详细地址')));
        }
        if(empty($address_member_name)){
            exit(json_encode(array('msg'=>'请填写联系人姓名')));
        }
        if(empty($address_phone)){
            exit(json_encode(array('msg'=>'请填写联系号码')));
        }
        $data=array(
            'member_id'=>$this->member_id,
            'member_provinceid'=>$member_provinceid,
            'member_cityid'=>$member_cityid,
            'member_areaid'=>$member_areaid,
            'member_areainfo'=>$member_areainfo,
            'address_content'=>$address_content,
            'address_member_name'=>$address_member_name,
            'address_phone'=>$address_phone,
            'address_time'=>time()
        );
        $member_address_id=DB::insert('member_address', $data, 1);
        if($member_selected==1){
            DB::query("UPDATE ".DB::table('member')." SET show_address_id=$member_address_id WHERE member_id='$this->member_id'");
            DB::update('member_address', array('choose_state'=>0), array('member_id'=>$this->member_id));
            DB::update('member_address', array('choose_state'=>1), array('member_address_id'=>$member_address_id,'member_id'=>$this->member_id));
        }
        exit(json_encode(array('done'=>'true')));
    }
    function orderOp(){
        $collect_id=empty($_POST['collect_id']) ? 0 : intval($_POST['collect_id']);
        $collect=DB::fetch_first("SELECT * FROM ".DB::table('member_favourite')." WHERE collect_id='$collect_id'");
        $nurse_id=empty($_POST['nurse_id']) ?  0 : intval($_POST['nurse_id']);
        $nurse_book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE member_id='$this->member_id' AND nurse_id='$nurse_id' AND book_state=10");
        if(!empty($nurse_book)){
            exit(json_encode(array('id'=>'system', 'msg'=>'您已经预约过该家政人员，请取消预约后重试')));
        }
        $agent_id=empty($_POST['agent_id']) ?  0 : intval($_POST['agent_id']);
        $nurse_type=empty($_POST['nurse_type']) ?  0 : intval($_POST['nurse_type']);
        $book_phone=empty($_POST['book_phone']) ? '' : $_POST['book_phone'];
        $book_details=empty($_POST['book_details']) ? '' : $_POST['book_details'];
        $work_duration=empty($_POST['work_duration']) ?  0 : intval($_POST['work_duration']);
        $work_duration_days=empty($_POST['work_duration_days']) ?  0 : intval($_POST['work_duration_days']);
        $work_duration_hours=empty($_POST['work_duration_hours']) ?  0 : intval($_POST['work_duration_hours']);
        $work_duration_mins=empty($_POST['work_duration_mins']) ?  0 : intval($_POST['work_duration_mins']);
        $work_area=empty($_POST['work_area']) ?  0 : intval($_POST['work_area']);
        $work_person=empty($_POST['work_person']) ?  0 : intval($_POST['work_person']);
        $work_machine=empty($_POST['work_machine']) ?  0 : intval($_POST['work_machine']);
        $work_cars=empty($_POST['work_cars']) ?  0 : intval($_POST['work_cars']);
        $car_price=empty($_POST['car_price']) ?  0 : intval($_POST['car_price']);
        $work_students=empty($_POST['work_students']) ?  0 : intval($_POST['work_students']);
        $service_price=empty($_POST['service_price']) ?  0 : intval($_POST['service_price']);
        $nurse_price=empty($_POST['nurse_price']) ?  0 : intval($_POST['nurse_price']);
        $book_address=empty($_POST['book_address']) ? '' : $_POST['book_address'];
        $service_member_phone=empty($_POST['service_member_phone']) ? '' : $_POST['service_member_phone'];
        $service_member_name=empty($_POST['service_member_name']) ? '' : $_POST['service_member_name'];
        $book_message=empty($_POST['book_message']) ? '' : $_POST['book_message'];
        $deposit_amount=empty($_POST['deposit_amount']) ?  0 : intval($_POST['deposit_amount']);
        $member_coin_amount=empty($_POST['member_coin_amount']) ?  0 : intval($_POST['member_coin_amount']);
        $book_amount=empty($_POST['book_amount']) ?  0 : intval($_POST['book_amount']);
        if(intval($collect['total_price'])!=$deposit_amount){
            exit(json_encode(array('id'=>'system','msg'=>'金额有误')));
        }
        $invoice_state=empty($_POST['invoice_state']) ? 0 : $_POST['invoice_state'];
        $invoice_type=empty($_POST['invoice_type']) ? '' : $_POST['invoice_type'];
        $invoice_title=empty($_POST['invoice_title']) ? '' : $_POST['invoice_title'];
        $invoice_content=empty($_POST['invoice_content']) ? '' : $_POST['invoice_content'];
        $invoice_membername=empty($_POST['invoice_membername']) ? '' : $_POST['invoice_membername'];
        $invoice_provinceid=empty($_POST['invoice_provinceid']) ? 0 : intval($_POST['invoice_provinceid']);
        $invoice_cityid=empty($_POST['invoice_cityid']) ? 0 : intval($_POST['invoice_cityid']);
        $invoice_areaid=empty($_POST['invoice_areaid']) ? 0 : intval($_POST['invoice_areaid']);
        $invoice_address=empty($_POST['invoice_address']) ? '' : $_POST['invoice_address'];
        $unit_name=empty($_POST['unit_name']) ? '' : $_POST['unit_name'];
        $invoice_code=empty($_POST['invoice_code']) ? '' : $_POST['invoice_code'];
        $invoice_unit_membername=empty($_POST['invoice_unit_membername']) ? '' : $_POST['invoice_unit_membername'];
        $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_provinceid'");
        $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_cityid'");
        $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$invoice_areaid'");
        $invoice_areainfo = $member_provincename.$member_cityname.$member_areaname;
        $invoice_data=array(
            'member_id'=>$this->member_id,
            'invoice_type'=>$invoice_type,
            'invoice_title'=>$invoice_title,
            'invoice_content'=>$invoice_content,
            'invoice_membername'=>$invoice_membername,
            'invoice_provinceid'=>$invoice_provinceid,
            'invoice_cityid'=>$invoice_cityid,
            'invoice_areaid'=>$invoice_areaid,
            'invoice_areainfo'=>$invoice_areainfo,
            'invoice_address'=>$invoice_address,
            'unit_name'=>$unit_name,
            'invoice_code'=>$invoice_code,
            'invoice_unit_membername'=>$invoice_unit_membername,
            'add_time'=>time()
        );
        $invoice_id=DB::insert('invoice',$invoice_data,1);
        if(empty($nurse_id)) {
            exit(json_encode(array('id'=>'system', 'msg'=>'请选择要预约的家政人员')));
        }
        $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
        if(empty($nurse)) {
            exit(json_encode(array('id'=>'system', 'msg'=>'请选择要预约的家政人员')));
        }
        if($nurse['state_cideci'] == 2 || $nurse['state_cideci']==4) {
            exit(json_encode(array('id'=>'system', 'msg'=>'该家政人员暂时不能接受预约')));
        }
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        if(intval($member['member_coin'])<intval($member_coin_amount)){
            exit(json_encode(array('id'=>'system', 'msg'=>'团豆豆余额不足')));
        }
        $book_sn = makesn(8);
        $out_sn = date('YmdHis').random(18);
        $data=array(
            'book_sn'=>$book_sn,
            'out_sn'=>$out_sn,
            'member_id' => $this->member_id,
            'member_phone' => $this->member['member_phone'],
            'nurse_id'=>$nurse_id,
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
            'show_state'=>0,
            'member_show_state'=>0,
            'service_member_name'=>$service_member_name,
            'deposit_amount'=>$deposit_amount,
            'member_coin_amount'=>$member_coin_amount,
            'book_amount'=>$book_amount,
            'book_message'=>$book_message,
            'invoice_type'=>$invoice_type,
            'invoice_id'=>$invoice_id,
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
                'code'=>''
            );
            if(empty($agent_id)){
                $text_send = new Simple_SMS($book_phone, 'personal_order_reservation', $content);
            }else{
                $text_send = new Simple_SMS($book_phone, 'company_order_reservation', $content);
            }
            $send_result = $text_send->async_send();
            exit(json_encode(array('done'=>'true', 'book_sn'=>$book_sn)));
        }else{
            exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
        }
    }
}

?>