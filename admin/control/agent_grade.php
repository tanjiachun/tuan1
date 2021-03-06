<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_gradeControl extends BaseAdminControl {
    public function indexOp() {
        if(submitcheck()) {
            $grade_id = empty($_POST['grade_id']) ? array() : $_POST['grade_id'];
            $grade_name = empty($_POST['grade_name']) ? array() : $_POST['grade_name'];
            $grade_icon = empty($_POST['grade_icon']) ? array() : $_POST['grade_icon'];
            $deposit_amount = empty($_POST['deposit_amount']) ? array() : $_POST['deposit_amount'];
            $agent_score = empty($_POST['agent_score']) ? array() : $_POST['agent_score'];
            $query = DB::query("SELECT * FROM ".DB::table("agent_grade"));
            while($value = DB::fetch($query)) {
                $grade_id_all[] = $value['grade_id'];
            }
            foreach($grade_name as $key => $value) {
                if(!empty($grade_name[$key])) {
                    $data = array(
                        'grade_name' => $grade_name[$key],
                        'grade_icon' => $grade_icon[$key],
                        'deposit_amount' => intval($deposit_amount[$key]),
                        'agent_score' => intval($agent_score[$key]),
                    );
                    if(empty($grade_id[$key])) {
                        DB::insert('agent_grade', $data);
                    } else {
                        DB::update('agent_grade', $data, array('grade_id'=>$grade_id[$key]));
                    }
                }
            }
            $grade_id_in = array_diff($grade_id_all, $grade_id);
            if(!empty($grade_id_in)) {
                DB::query("DELETE FROM ".DB::table('agent_grade')." WHERE grade_id in ('".implode("','", $grade_id_in)."')");
            }
            showdialog('保存成功', 'admin.php?act=agent_grade', 'succ');
        } else {
            $query = DB::query("SELECT * FROM ".DB::table("agent_grade")." ORDER BY agent_score ASC");
            while($value = DB::fetch($query)) {
                $grade_list[] = $value;
            }
            include(template('agent_grade'));
        }
    }
}
?>