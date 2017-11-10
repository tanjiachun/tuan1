<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_address_setControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_address')." WHERE member_id='$this->member_id' AND show_state=0");
        $also_count=20-$count;

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 10;
        $start = ($page-1)*$perpage;
        $mpurl = "index.php?act=member_address_set";
        $wheresql = " WHERE member_id='$this->member_id' AND show_state=0";
        $query = DB::query("SELECT * FROM ".DB::table('member_address').$wheresql." LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $address_list[] = $value;
        }
        $multi = multi($count, $perpage, $page, $mpurl);

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
        include(template('member_address_set'));
    }

    public function address_setOp(){
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
    public function del_addressOp(){
        $member_address_id=empty($_POST['member_address_id']) ? 0 : intval($_POST['member_address_id']);
        DB::query("UPDATE ".DB::table('member_address')." SET show_state=1 WHERE member_address_id='$member_address_id'");
        exit(json_encode(array('done'=>'true','member_address_id'=>$member_address_id)));
    }
    public function address_resumeOp(){
        if(submitcheck()){
            $member_address_id=empty($_POST['member_address_id']) ? 0 : intval($_POST['member_address_id']);
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
            if($member_selected==1){
                DB::query("UPDATE ".DB::table('member')." SET show_address_id=$member_address_id WHERE member_id='$this->member_id'");
                DB::update('member_address', array('choose_state'=>0), array('member_id'=>$this->member_id));
                DB::update('member_address', array('choose_state'=>1), array('member_address_id'=>$member_address_id,'member_id'=>$this->member_id));
            }
            exit(json_encode(array('done'=>'true')));
        }else{
            $member_address_id=empty($_GET['member_address_id']) ? 0 : intval($_GET['member_address_id']);
            if(empty($member_address_id)){

            }
            $member_address=DB::fetch_first("SELECT * FROM ".DB::table('member_address')." WHERE member_address_id='$member_address_id'");
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                if($value['district_id'] == $member_address['member_provinceid']) {
                    $member_provinceid = $value['district_id'];
                    $member_provincename = $value['district_name'];
                }
                $province_list[] = $value;
            }
            if(!empty($member_provinceid)) {
                $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_provinceid' ORDER BY district_sort ASC");
                while($value = DB::fetch($query)) {
                    if($value['district_id'] == $member_address['member_cityid']) {
                        $member_cityid = $value['district_id'];
                        $member_cityname = $value['district_name'];
                    }
                    $member_city_list[] = $value;
                }
            }
            if(!empty($member_cityid)) {
                $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_cityid' ORDER BY district_sort ASC");
                while($value = DB::fetch($query)) {
                    if($value['district_id'] == $member_address['member_areaid']) {
                        $member_areaid = $value['district_id'];
                        $member_areaname = $value['district_name'];
                    }
                    $member_area_list[] = $value;
                }
            }
            $curmodule = 'home';
            $bodyclass = '';
            include(template('member_address_resume'));
        }
    }
}

?>