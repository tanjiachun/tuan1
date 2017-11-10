<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class member_centerControl extends BaseProfileControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了', 'index.php?act=login', 'info');
        }
        $member_provinceid=$this->member['member_provinceid'];
        $member_cityid=$this->member['member_cityid'];
        $member_areaid=$this->member['member_areaid'];
        $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_provinceid'");
        $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_cityid'");
        $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_areaid'");
        $current_year = date('Y');
        $days = 31;
        if(!empty($this->member['member_birthyear']) && !empty($this->member['member_birthmonth'])) {
            if($this->member['member_birthmonth'] == 2) {
                if($this->member['member_birthyear']%400 == 0 || ($this->member['member_birthyear']%4 == 0 && $this->member['member_birthyear']%100 != 0)) {
                    $days = 29;
                } else {
                    $days = 28;
                }
            } else {
                $months = array('1', '3', '5', '7', '8', '10', '12');
                if(in_array($this->member['member_birthmonth'], $months)) {
                    $days = 31;
                } else {
                    $days = 30;
                }
            }
        }
        $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
        while($value = DB::fetch($query)) {
            if($value['district_id'] == $this->member['member_provinceid']) {
                $member_provinceid = $value['district_id'];
                $member_provincename = $value['district_name'];
            }
            $province_list[] = $value;
        }
        if(!empty($member_provinceid)) {
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_provinceid' ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                if($value['district_id'] == $this->member['nurse_cityid']) {
                    $member_cityid = $value['district_id'];
                    $member_cityname = $value['district_name'];
                }
                $member_city_list[] = $value;
            }
        }
        if(!empty($member_cityid)) {
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$member_cityid' ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                if($value['district_id'] == $this->nurse['nurse_areaid']) {
                    $member_areaid = $value['district_id'];
                    $member_areaname = $value['district_name'];
                }
                $member_area_list[] = $value;
            }
        }
        $curmodule = 'home';
        $bodyclass = '';
        include(template('member_center'));
    }
    public function member_resumeOp(){
        $member= DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$this->member_id'");
        $member_nickname = empty($_POST['member_nickname']) ? '' : $_POST['member_nickname'];
        $member_avatar = empty($_POST['member_avatar']) ? '' : $_POST['member_avatar'];
        $member_sex = empty($_POST['member_sex']) ? 0 : intval($_POST['member_sex']);
        $member_birthyear = empty($_POST['member_birthyear']) ? 0 : intval($_POST['member_birthyear']);
        $member_birthmonth = empty($_POST['member_birthmonth']) ? 0 : intval($_POST['member_birthmonth']);
        $member_birthday = empty($_POST['member_birthday']) ? 0 : intval($_POST['member_birthday']);
        $member_provinceid = empty($_POST['member_provinceid']) ? 0 : intval($_POST['member_provinceid']);
        $member_cityid = empty($_POST['member_cityid']) ? 0 : intval($_POST['member_cityid']);
        $member_areaid = empty($_POST['member_areaid']) ? 0 : intval($_POST['member_areaid']);
        $member_provincename = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_provinceid'");
        $member_cityname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_cityid'");
        $member_areaname = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$member_areaid'");
        $member_areainfo = $member_provincename.$member_cityname.$member_areaname;
        $data = array(
            'member_nickname' => $member_nickname,
            'member_avatar' => $member_avatar,
            'member_sex' => $member_sex,
            'member_birthyear' => $member_birthyear,
            'member_birthmonth' => $member_birthmonth,
            'member_birthday' => $member_birthday,
            'member_provinceid'=>$member_provinceid,
            'member_cityid'=>$member_cityid,
            'member_areaid'=>$member_areaid,
            'member_areainfo'=>$member_areainfo
        );
        DB::update('member', $data, array('member_id'=>$this->member_id));

        //修改云信头像，昵称
        $im_update_data=array(
            'accid'=>$member['yx_accid'],
            'name'=>$member_nickname,
            'icon'=>SiteUrl.'/'.$member_avatar
        );
        $nim=new NimUser();
        $a=$nim->updateInfo($im_update_data);
        exit(json_encode(array('done'=>'true')));
    }
}

?>