<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class nurse_reviseControl extends BaseAdminControl {
    public function indexOp() {
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 20;
        $start = ($page-1)*$perpage;
        $mpurl = "admin.php?act=nurse_revise";
        $wheresql = " WHERE revise_state=0";
        $search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
        if(!empty($search_name)) {
            $mpurl .= '&nurse_name='.urlencode($search_name);
            $wheresql .= " AND nurse_name like '%".$search_name."%'";
        }
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse_revise').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('nurse_revise').$wheresql." ORDER BY revise_time ASC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $value['nurse_qa_image'] = empty($value['nurse_qa_image']) ? array() : unserialize($value['nurse_qa_image']);
            $nurse_list[] = $value;
        }
        $multi = simplepage($count, $perpage, $page, $mpurl);
        include(template('nurse_revise'));
    }

    public function reviseOp() {
        $nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
        $nurse_id=intval($nurse_ids);

        $revise=DB::fetch_first("SELECT * FROM ".DB::table('nurse_revise')." WHERE nurse_id='$nurse_id' AND revise_state=0");
        $revise['nurse_qa_image'] = empty($revise['nurse_qa_image'] ) ? array() : unserialize($revise['nurse_qa_image'] );
        $data=array(
            'nurse_name'=>$revise['nurse_name'],
            'nurse_phone'=>$revise['nurse_phone'],
            'nurse_type'=>$revise['nurse_type'],
            'service_type'=>$revise['service_type'],
            'nurse_special_service'=>$revise['nurse_special_service'],
            'nurse_age'=>$revise['nurse_age'],
            'birth_provinceid'=>$revise['birth_provinceid'],
            'birth_cityid'=>$revise['birth_cityid'],
            'birth_areaid'=>$revise['birth_areaid'],
            'birth_areainfo'=>$revise['birth_areainfo'],
            'birth_cityname'=>$revise['birth_cityname'],
            'nurse_provinceid'=>$revise['nurse_provinceid'],
            'nurse_cityid'=>$revise['nurse_cityid'],
            'nurse_areaid'=>$revise['nurse_areaid'],
            'nurse_areainfo'=>$revise['nurse_areainfo'],
            'nurse_cityname'=>$revise['nurse_cityname'],
            'nurse_areaname'=>$revise['nurse_areaname'],
            'nurse_address'=>$revise['nurse_address'],
            'nurse_cardid'=>$revise['nurse_cardid'],
            'nurse_image'=>$revise['nurse_image'],
            'nurse_cardid_image'=>$revise['nurse_cardid_image'],
            'nurse_qa_image'=>empty($revise['nurse_qa_image'] ) ? '' : serialize($revise['nurse_qa_image'] ),
            'nurse_content'=>$revise['nurse_content'],
            'revise_state'=>1
        );
        DB::update('nurse', $data,array('nurse_id'=>$revise['nurse_id']));
        DB::query("UPDATE ".DB::table('nurse_revise')." SET revise_state=1 WHERE revise_id='".$revise['revise_id']."'");
        showdialog('审核成功', 'admin.php?act=nurse_revise', 'succ');
    }
    public function unreviseOp(){
        $nurse_ids = empty($_GET['nurse_ids']) ? '' : $_GET['nurse_ids'];
        $nurse_id=intval($nurse_ids);
        DB::query("UPDATE ".DB::table('nurse')." SET revise_state=2 WHERE nurse_id='$nurse_id'");
        DB::query("UPDATE ".DB::table('nurse_revise')." SET revise_state=2 WHERE nurse_id='$nurse_id' AND revise_state=0");
        showdialog('关闭成功', 'admin.php?act=nurse_revise', 'succ');
    }
}
?>