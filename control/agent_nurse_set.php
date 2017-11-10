<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_nurse_setControl extends BaseAgentControl {
    public function indexOp(){
        if(empty($this->agent_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$this->agent_id'");
        $agent['agent_qa_image'] = empty($agent['agent_qa_image'] ) ? array() : unserialize($agent['agent_qa_image'] );
        $agent['agent_service_image'] = empty($agent['agent_service_image'] ) ? array() : unserialize($agent['agent_service_image'] );
        $agent['agent_other_phone'] = empty($agent['agent_other_phone'] ) ? array() : unserialize($agent['agent_other_phone'] );
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$this->agent_id' AND nurse_state=1");
        $book_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_book')." WHERE agent_id='$this->agent_id' AND refund_amount=0");
        $question_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_question')." WHERE agent_id='$this->agent_id' AND answer_content=''");
        $nurse_state_array=array('0','1','2','3','4');
        $nurse_state_name_array=array('0'=>'默认','1'=>'待业中', '2'=>'已在岗', '3'=>'假期中', '4'=>'已到岗');
        $nurse_discount_array=array('8','7','6','5','4');
        $nurse_discount_array2=array('9.5','9','8.5','8');
        $authority_state_array=array('0','1','2');
        $authority_state_name_array=array('0'=>'默认','1'=>'可修改', '2'=>'不可修改');
        $service_price_array=array('0'=>150,'1'=>200,'2'=>250,'3'=>300,'4'=>350,'5'=>400,'6'=>450,'7'=>500);
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $mpurl = "index.php?act=agent_nurse_set";
        $wheresql = " WHERE agent_id='$this->agent_id'";
        $search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
        if(!empty($search_name)) {
            $mpurl .= '&search_name='.urlencode($search_name);
            $wheresql .= " AND (nurse_name like '%".$search_name."%' OR member_phone like '%".$search_name."%')";
        }
        $state_cideci = in_array($_GET['state_cideci'], array('all','forjob', 'onwork', 'holiday')) ? $_GET['state_cideci'] : 'all';
        if(!empty($state_cideci)){
            if($state_cideci=='all'){
                $mpurl .= '&state_cideci=all';
                $wheresql .= "";
            } elseif($state_cideci=='forjob'){
                $mpurl .= '&state_cideci=forjob';
                $wheresql .= " AND (state_cideci=1 OR state_cideci=0)";
            }elseif ($state_cideci=='onwork'){
                $mpurl .= '&state_cideci=onwork';
                $wheresql .= " AND state_cideci=2";
            }elseif ($state_cideci=='holiday'){
                $mpurl .= '&state_cideci=holiday';
                $wheresql .= " AND state_cideci=3";
            }
        }
        $state = in_array($_GET['state'], array('show', 'sex', 'age','score','time')) ? $_GET['state'] : 'show';
        if($state == 'show') {
            $mpurl .= '&state=show';
            $wheresql .= " ORDER BY nurse_id ASC";
        } elseif($state == 'sex') {
            $mpurl .= '&state=sex';
            $wheresql .= " ORDER BY nurse_sex DESC";
        } elseif($state == 'age') {
            $mpurl .= '&state=age';
            $wheresql .= " ORDER BY nurse_age ASC";
        } elseif ($state=='score'){
            $mpurl .= '&state=score';
            $wheresql .= " ORDER BY nurse_score DESC";
        }elseif ($state=='time'){
            $mpurl .= '&state=time';
            $wheresql .= " ORDER BY nurse_time ASC";
        }
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql." LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $nurse_list[] = $value;
        }
        $multi = multi($count, $perpage, $page, $mpurl);

        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_nurse_set'));
    }

    public function authorityOp(){
        if(submitcheck()) {
            $authority_ids = empty($_POST['authority_ids']) ? '' : $_POST['authority_ids'];
            $authority_ids = explode(',', $authority_ids);
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $authority_ids)."')");
            while($value = DB::fetch($query)) {
                if($value['agent_id'] == $this->agent_id) {
                    $nurse_ids[] = $value['nurse_id'];
                }
            }
            if(empty($nurse_ids)) {
                exit(json_encode(array('msg'=>'请至少选择一个家政人员')));
            }
            $authority_value=empty($_POST['authority_value']) ? 0 : intval($_POST['authority_value']);
            if(empty($authority_value)){
                exit(json_encode(array('msg'=>'请选择正确的选项')));
            }
            DB::query("UPDATE ".DB::table('nurse')." SET authority_state='$authority_value' WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            exit(json_encode(array('done'=>'true', 'authority_ids'=>$nurse_ids)));
        } else {
            exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
        }
    }

    public function discountOp(){
        if(submitcheck()) {
            $discount_ids = empty($_POST['discount_ids']) ? '' : $_POST['discount_ids'];
            $discount_ids = explode(',', $discount_ids);
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $discount_ids)."')");
            while($value = DB::fetch($query)) {
                if($value['agent_id'] == $this->agent_id) {
                    $nurse_ids[] = $value['nurse_id'];
                }
            }
            if(empty($nurse_ids)) {
                exit(json_encode(array('msg'=>'请至少选择一个家政人员')));
            }
            $discount_value=empty($_POST['discount_value']) ? 0 : intval($_POST['discount_value']);
            DB::query("UPDATE ".DB::table('nurse')." SET nurse_discount='$discount_value' WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            exit(json_encode(array('done'=>'true', 'discount_ids'=>$nurse_ids)));
        } else {
            exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
        }
    }

    public function promiseOp(){
        if(submitcheck()) {
            $promise_ids = empty($_POST['promise_ids']) ? '' : $_POST['promise_ids'];
            $promise_ids = explode(',', $promise_ids);
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $promise_ids)."')");
            while($value = DB::fetch($query)) {
                if($value['agent_id'] == $this->agent_id) {
                    $nurse_ids[] = $value['nurse_id'];
                }
            }
            if(empty($nurse_ids)) {
                exit(json_encode(array('msg'=>'请至少选择一个家政人员')));
            }
            $promise_value=empty($_POST['promise_value']) ? 0 : intval($_POST['promise_value']);
            if(empty($promise_value)){
                exit(json_encode(array('msg'=>'请选择正确的选项')));
            }
            DB::query("UPDATE ".DB::table('nurse')." SET promise_state='$promise_value' WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            exit(json_encode(array('done'=>'true', 'promise_ids'=>$nurse_ids)));
        } else {
            exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
        }
    }

    public function phoneOp(){
        if(submitcheck()) {
            $phone_ids = empty($_POST['phone_ids']) ? '' : $_POST['phone_ids'];
            $phone_ids = explode(',', $phone_ids);
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $phone_ids)."')");
            while($value = DB::fetch($query)) {
                if($value['agent_id'] == $this->agent_id) {
                    $nurse_ids[] = $value['nurse_id'];
                }
            }
            if(empty($nurse_ids)) {
                exit(json_encode(array('msg'=>'请至少选择一个家政人员')));
            }
            $phone_value=empty($_POST['phone_value']) ? '' : $_POST['phone_value'];
            if(empty($phone_value)){
                exit(json_encode(array('msg'=>'请选择正确的选项')));
            }
            DB::query("UPDATE ".DB::table('nurse')." SET agent_phone='$phone_value' WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            exit(json_encode(array('done'=>'true', 'phone_ids'=>$nurse_ids)));
        } else {
            exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
        }
    }

    public function dismissOp(){
        if(submitcheck()) {
            $dismiss_ids = empty($_POST['dismiss_ids']) ? '' : $_POST['dismiss_ids'];
            $dismiss_ids = explode(',', $dismiss_ids);
            $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $dismiss_ids)."')");
            while($value = DB::fetch($query)) {
                if($value['agent_id'] == $this->agent_id) {
                    $nurse_ids[] = $value['nurse_id'];
                }
            }
            if(empty($nurse_ids)) {
                exit(json_encode(array('msg'=>'请至少选择一个家政人员')));
            }
            DB::query("UPDATE ".DB::table('nurse')." SET agent_id=0 WHERE nurse_id in ('".implode("','", $nurse_ids)."')");
            exit(json_encode(array('done'=>'true', 'dismiss_ids'=>$nurse_ids)));
        } else {
            exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
        }
    }

    public function nurse_setOp(){
        if(submitcheck()) {
            $nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
            if(empty($nurse_id)) {
                exit(json_encode(array('msg'=>'提交信息出错')));
            }
            $book=DB::fetch_first("SELECT * FROM ".DB::table('nurse_book')." WHERE nurse_id='$nurse_id' AND book_state=10");
            if(!empty($book)){
                exit(json_encode(array('msg'=>'该家政人员已被下单，无法修改')));
            }
            $nurse_price=empty($_POST['nurse_price']) ? 0 : intval($_POST['nurse_price']);
            $service_price=empty($_POST['service_price']) ? 0 : intval($_POST['service_price']);
            $agent_phone=empty($_POST['agent_phone']) ? '' : $_POST['agent_phone'];
            $state_cideci=empty($_POST['state_cideci']) ? '' : $_POST['state_cideci'];
            $nurse_discount=empty($_POST['nurse_discount']) ? 1 : floatval($_POST['nurse_discount']);
            $authority_state=empty($_POST['authority_state']) ? 0 : intval($_POST['authority_state']);
            $data=array(
                'nurse_price'=>$nurse_price,
                'service_price'=>$service_price,
                'agent_phone'=>$agent_phone,
                'state_cideci'=>$state_cideci,
                'nurse_discount'=>$nurse_discount,
                'authority_state'=>$authority_state
            );
            DB::update('nurse', $data,array('nurse_id'=>$nurse_id));
            exit(json_encode(array('done'=>'true',)));
        } else {
            exit(json_encode(array('msg'=>'网路不稳定，请稍候重试')));
        }
    }

    public function editOp() {
        if(submitcheck()) {
            $nurse_id = empty($_POST['nurse_id']) ? 0 : intval($_POST['nurse_id']);
            $nurse_name = empty($_POST['nurse_name']) ? '' : $_POST['nurse_name'];
            $nurse_phone = empty($_POST['nurse_phone']) ? '' : $_POST['nurse_phone'];
            $nurse_type = empty($_POST['nurse_type']) ? 0 : intval($_POST['nurse_type']);
            $service_type = empty($_POST['service_type']) ? '' : $_POST['service_type'];
            $nurse_special_service = empty($_POST['nurse_special_service']) ? '' : $_POST['nurse_special_service'];
            $nurse_age = empty($_POST['nurse_age']) ? 0 : intval($_POST['nurse_age']);
            $birth_provinceid = empty($_POST['birth_provinceid']) ? 0 : intval($_POST['birth_provinceid']);
            $birth_cityid = empty($_POST['birth_cityid']) ? 0 : intval($_POST['birth_cityid']);
            $birth_areaid = empty($_POST['birth_areaid']) ? 0 : intval($_POST['birth_areaid']);
            $nurse_provinceid = empty($_POST['nurse_provinceid']) ? 0 : intval($_POST['nurse_provinceid']);
            $nurse_cityid = empty($_POST['nurse_cityid']) ? 0 : intval($_POST['nurse_cityid']);
            $nurse_areaid = empty($_POST['nurse_areaid']) ? 0 : intval($_POST['nurse_areaid']);
            $nurse_address = empty($_POST['nurse_address']) ? '' : $_POST['nurse_address'];
            $nurse_education = empty($_POST['nurse_education']) ? 0 : intval($_POST['nurse_education']);
            $nurse_price = empty($_POST['nurse_price']) ? 0 : intval($_POST['nurse_price']);
            $nurse_image = empty($_POST['nurse_image']) ? '' : $_POST['nurse_image'];
            $nurse_cardid = empty($_POST['nurse_cardid']) ? '' : $_POST['nurse_cardid'];
            $nurse_cardid_image = empty($_POST['nurse_cardid_image']) ? '' : $_POST['nurse_cardid_image'];
            $nurse_qa_image = empty($_POST['nurse_qa_image']) ? array() : $_POST['nurse_qa_image'];
            $nurse_content = empty($_POST['nurse_content']) ? '' : $_POST['nurse_content'];
            if(empty($nurse_name)) {
                exit(json_encode(array('id'=>'nurse_name', 'msg'=>'请输入家政人员姓名')));
            }
            if(empty($nurse_phone)) {
                exit(json_encode(array('id'=>'nurse_phone', 'msg'=>'请输入家政人员手机号')));
            }
            if(!preg_match('/^1[0-9]{10}$/', $nurse_phone)) {
                exit(json_encode(array('id'=>'nurse_phone', 'msg'=>'家政人员手机号格式不正确')));
            }
            if(empty($nurse_type)) {
                exit(json_encode(array('id'=>'nurse_type', 'msg'=>'请选择看护类别')));
            }
            if(empty($service_type)) {
                exit(json_encode(array('id'=>'service_type', 'msg'=>'服务类别必须填写')));
            }
            if(empty($nurse_age)) {
                exit(json_encode(array('id'=>'nurse_age', 'msg'=>'请输入您的年龄')));
            }
            if(empty($birth_provinceid)) {
                exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'请选择出生地址')));
            }
            $district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$birth_cityid'");
            if(empty($district_count)) {
                if(empty($birth_cityid)) {
                    exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'请选择出生地址')));
                }
            } else {
                if(empty($birth_areaid)) {
                    exit(json_encode(array('id'=>'birth_provinceid', 'msg'=>'请选择出生地址')));
                }
            }
            if(empty($nurse_provinceid)) {
                exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'请选择现居地址')));
            }
            $district_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid'");
            if(empty($district_count)) {
                if(empty($nurse_cityid)) {
                    exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'请选择现居地址')));
                }
            } else {
                if(empty($nurse_areaid)) {
                    exit(json_encode(array('id'=>'nurse_provinceid', 'msg'=>'请选择现居地址')));
                }
            }
            if(empty($nurse_address)) {
                exit(json_encode(array('id'=>'nurse_address', 'msg'=>'请输入详细地址')));
            }
            if($nurse_education <= 0) {
                exit(json_encode(array('id'=>'nurse_education', 'msg'=>'请输入工作年限')));
            }
            if($nurse_price <= 0) {
                exit(json_encode(array('id'=>'nurse_price', 'msg'=>'请输入月薪')));
            }
            if(empty($nurse_image)) {
                exit(json_encode(array('id'=>'nurse_image', 'msg'=>'请上传个人照片')));
            }
            if(empty($nurse_cardid)) {
                exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'请输入身份证号码')));
            }
            /*
            if(!checkcard($nurse_cardid)) {
                exit(json_encode(array('id'=>'nurse_cardid', 'msg'=>'身份证号码格式不正确')));
            }
            */
            if(empty($nurse_cardid_image)) {
                exit(json_encode(array('id'=>'nurse_cardid_image', 'msg'=>'请上传手持身份证照')));
            }
            if(empty($nurse_content)) {
                exit(json_encode(array('id'=>'nurse_content', 'msg'=>'请输入服务项目')));
            }
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id'");
            if(empty($nurse) || $nurse['agent_id'] != $this->agent_id) {
                exit(json_encode(array('id'=>'system', 'msg'=>'家政人员不存在')));
            }
            $district_ids = array('1', '2', '9', '22', '32', '33', '34');
            $birth_province = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$birth_provinceid'");
            $birth_city = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$birth_cityid'");
            $birth_area = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_id='$birth_areaid'");
            $birth_areainfo = $birth_province['district_name'].$birth_city['district_name'].$birth_area['district_name'];
            if(in_array($birth_provinceid , $district_ids)) {
                $birth_cityname = $birth_province['district_ipname'];
            } else {
                $birth_cityname = $birth_city['district_ipname'];
            }
            $nurse_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_provinceid'");
            $nurse_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_cityid'");
            $nurse_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$nurse_areaid'");
            $nurse_areainfo = $nurse_provincename.$nurse_cityname.$nurse_areaname;
            $data = array(
                'nurse_id'=>$nurse_id,
                'nurse_name' => $nurse_name,
                'nurse_phone' => $nurse_phone,
                'nurse_image' => $nurse_image,
                'nurse_type' => $nurse_type,
                'service_type'=>$service_type,
                'nurse_special_service'=>$nurse_special_service,
                'nurse_price' => $nurse_price,
                'nurse_age' => $nurse_age,
                'nurse_education' => $nurse_education,
                'birth_provinceid' => $birth_provinceid,
                'birth_cityid' => $birth_cityid,
                'birth_areaid' => $birth_areaid,
                'birth_areainfo' => $birth_areainfo,
                'birth_cityname' => $birth_cityname,
                'nurse_provinceid' => $nurse_provinceid,
                'nurse_cityid' => $nurse_cityid,
                'nurse_areaid' => $nurse_areaid,
                'nurse_areainfo' => $nurse_areainfo,
                'nurse_cityname' => in_array($nurse_provinceid , $district_ids) ? $nurse_provincename : $nurse_cityname,
                'nurse_areaname' => in_array($nurse_provinceid , $district_ids) ? $nurse_cityname : $nurse_areaname,
                'nurse_address' => $nurse_address,
                'nurse_cardid' => $nurse_cardid,
                'nurse_cardid_image' => $nurse_cardid_image,
                'nurse_qa_image' => empty($nurse_qa_image) ? '' : serialize($nurse_qa_image),
                'nurse_content' => $nurse_content,
                'revise_state'=>0,
                'revise_time'=>time()
            );
            $revise=DB::fetch_first("SELECT * FROM ".DB::table('nurse_revise')." WHERE nurse_id='$nurse_id' AND revise_state=0");
            if(!empty($revise)){
                DB::update('nurse_revise', $data,array('revise_id'=>$revise['revise_id']));
                DB::query("UPDATE ".DB::table('nurse')." SET revise_state=0 WHERE nurse_id='$nurse_id'");
                exit(json_encode(array('done'=>'true')));
            }else{
                $revise_id=DB::insert('nurse_revise', $data,1);
                DB::query("UPDATE ".DB::table('nurse')." SET revise_state=0 WHERE nurse_id='$nurse_id'");
                if(!empty($revise_id)){
                    exit(json_encode(array('done'=>'true')));
                }else{
                    exit(json_encode(array('id'=>'system', 'msg'=>'网路不稳定，请稍候重试')));
                }
            }
            DB::update('nurse', $data, array('nurse_id'=>$nurse['nurse_id']));
            exit(json_encode(array('done'=>'true')));
        } else {
            $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
            $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$this->agent_id'");
            $nurse_id = empty($_GET['nurse_id']) ? 0 : intval($_GET['nurse_id']);
            $nurse = DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id='$nurse_id' AND agent_id='$this->agent_id'");
            $nurse['nurse_qa'] = empty($nurse['nurse_qa']) ? array() : unserialize($nurse['nurse_qa']);
            $nurse['nurse_qa_image'] = empty($nurse['nurse_qa_image']) ? array() : unserialize($nurse['nurse_qa_image']);
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                if($value['district_id'] == $nurse['nurse_provinceid']) {
                    $nurse_provinceid = $value['district_id'];
                    $nurse_provincename = $value['district_name'];
                }
                if($value['district_id'] == $nurse['birth_provinceid']) {
                    $birth_provinceid = $value['district_id'];
                    $birth_provincename = $value['district_name'];
                }
                $province_list[] = $value;
            }
            if(!empty($nurse_provinceid)) {
                $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$nurse_provinceid' ORDER BY district_sort ASC");
                while($value = DB::fetch($query)) {
                    if($value['district_id'] == $nurse['nurse_cityid']) {
                        $nurse_cityid = $value['district_id'];
                        $nurse_cityname = $value['district_name'];
                    }
                    $nurse_city_list[] = $value;
                }
            }
            if(!empty($birth_provinceid)) {
                $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$birth_provinceid' ORDER BY district_sort ASC");
                while($value = DB::fetch($query)) {
                    if($value['district_id'] == $nurse['birth_cityid']) {
                        $birth_cityid = $value['district_id'];
                        $birth_cityname = $value['district_name'];
                    }
                    $birth_city_list[] = $value;
                }
            }
            if(!empty($nurse_cityid)) {
                $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$nurse_cityid' ORDER BY district_sort ASC");
                while($value = DB::fetch($query)) {
                    if($value['district_id'] == $nurse['nurse_areaid']) {
                        $nurse_areaid = $value['district_id'];
                        $nurse_areaname = $value['district_name'];
                    }
                    $nurse_area_list[] = $value;
                }
            }
            if(!empty($birth_cityid)) {
                $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$birth_cityid' ORDER BY district_sort ASC");
                while($value = DB::fetch($query)) {
                    if($value['district_id'] == $nurse['birth_areaid']) {
                        $birth_areaid = $value['district_id'];
                        $birth_areaname = $value['district_name'];
                    }
                    $birth_area_list[] = $value;
                }
            }
            $curmodule = 'home';
            $bodyclass = 'gray-bg';
            include(template('agent_nurse_edit'));
        }
    }
}

?>