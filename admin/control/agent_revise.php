<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_reviseControl extends BaseAdminControl {
    public function indexOp() {
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 20;
        $start = ($page-1)*$perpage;
        $mpurl = "admin.php?act=agent_revise";
        $wheresql = " WHERE revise_state=0";
        $search_name = empty($_GET['search_name']) ? '' : $_GET['search_name'];
        if(!empty($search_name)) {
            $mpurl .= '&agent_name='.urlencode($search_name);
            $wheresql .= " AND agent_name like '%".$search_name."%'";
        }
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('agent_revise').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('agent_revise').$wheresql." ORDER BY revise_time ASC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $value['agent_qa_image'] = empty($value['agent_qa_image']) ? array() : unserialize($value['agent_qa_image']);
            $value['agent_service_image'] = empty($value['agent_service_image']) ? array() : unserialize($value['agent_service_image']);
            $agent_list[] = $value;
        }
        $multi = simplepage($count, $perpage, $page, $mpurl);
        include(template('agent_revise'));
    }

    public function reviseOp() {
        $agent_ids = empty($_GET['agent_ids']) ? '' : $_GET['agent_ids'];
        $agent_id=intval($agent_ids);
        $revise=DB::fetch_first("SELECT * FROM ".DB::table('agent_revise')." WHERE agent_id='$agent_id' AND revise_state=0");
        $revise['agent_qa_image'] = empty($revise['agent_qa_image'] ) ? array() : unserialize($revise['agent_qa_image'] );
        $revise['agent_service_image'] = empty($revise['agent_service_image'] ) ? array() : unserialize($revise['agent_service_image'] );
        $data=array(
            'agent_name'=>$revise['agent_name'],
            'owner_name'=>$revise['owner_name'],
            'agent_phone'=>$revise['agent_phone'],
            'agent_address'=>$revise['agent_address'],
            'agent_location'=>$revise['agent_location'],
            'agent_banner'=>$revise['agent_banner'],
            'agent_logo'=>$revise['agent_logo'],
            'agent_code_image'=>$revise['agent_code_image'],
            'agent_person_image'=>$revise['agent_person_image'],
            'agent_person_code_image'=>$revise['agent_person_code_image'],
            'agent_sign_image'=>$revise['agent_sign_image'],
            'agent_qa_image'=>empty($revise['agent_qa_image'] ) ? '' : serialize($revise['agent_qa_image'] ),
            'agent_service_image'=>empty($revise['agent_service_image'] ) ? '' : serialize($revise['agent_service_image'] ),
            'agent_summary'=>$revise['agent_summary'],
            'agent_content'=>$revise['agent_content'],
            'revise_state'=>1
        );
        DB::update('agent', $data,array('agent_id'=>$revise['agent_id']));
        DB::query("UPDATE ".DB::table('agent_revise')." SET revise_state=1 WHERE revise_id='".$revise['revise_id']."'");
        showdialog('审核成功', 'admin.php?act=agent_revise', 'succ');
    }
    public function unreviseOp(){
        $agent_ids = empty($_GET['agent_ids']) ? '' : $_GET['agent_ids'];
        $agent_id=intval($agent_ids);
        DB::query("UPDATE ".DB::table('agent')." SET revise_state=2 WHERE agent_id='$agent_id'");
        DB::query("UPDATE ".DB::table('agent_revise')." SET revise_state=2 WHERE agent_id='$agent_id' AND revise_state=0");
        showdialog('关闭成功', 'admin.php?act=agent_revise', 'succ');
    }
}
?>