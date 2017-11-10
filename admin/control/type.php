<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class typeControl extends BaseAdminControl {
    public function indexOp(){

        $query = DB::query("SELECT * FROM ".DB::table('nurse_type')." WHERE type_level=1 ORDER BY type_id ASC");
        while($value = DB::fetch($query)) {
            $type_list[] = $value;
        }

        include(template('type'));
    }

    public function child_typeOp(){
            $parent_id = empty($_GET['parent_id']) ? 0 : intval($_GET['parent_id']);
            $query = DB::query("SELECT * FROM ".DB::table('nurse_type')." WHERE type_level=2 AND parent_id=$parent_id ORDER BY type_id ASC");
            while($value = DB::fetch($query)) {
                $type_list[] = $value;
            }

            include(template('child_type'));
    }
    public function child_type_addOp(){
        $type_name = empty($_POST['type_name']) ? '' : $_POST['type_name'];
        $parent_id = empty($_POST['parent_id']) ? 0 : $_POST['parent_id'];
        $type_unit = empty($_POST['type_unit']) ? '' : $_POST['type_unit'];
        $is_service_price = empty($_POST['is_service_price']) ? 0 : $_POST['is_service_price'];
        if(empty($type_name)){
            exit;
        }
        if(empty($parent_id)){
            exit;
        }
        if(empty($type_unit)){
            exit;
        }
        $data=array(
            'type_level'=>2,
            'type_name'=>$type_name,
            'parent_id'=>$parent_id,
            'type_unit'=>$type_unit,
            'is_service_price'=>$is_service_price
        );
        $type_id = DB::insert('nurse_type',$data,1);
        if(empty($type_id)){
            exit;
        }else{
            exit(json_encode(array('done'=>'true')));
        }
    }
    public function child_type_delOp(){
        $type_id = empty($_POST['type_id']) ? 0 : $_POST['type_id'];
        if(empty($type_id)){
            exit;
        }
        DB::query("DELETE FROM ".DB::table('nurse_type')." WHERE type_id='$type_id'");
        exit(json_encode(array('done'=>'true')));
    }
//    public function type_addOp(){
//        $data=array(
//          'type_level'=>1,
//            'type_name'=>'管家服务/高级家教',
//        );
//        DB::insert('nurse_type',$data);
//    }

}

?>