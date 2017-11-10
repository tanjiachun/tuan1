<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class user_feed_backControl extends BaseMobileControl {
    public function indexOp(){
        $this->check_authority();
        $suggest_type=empty($_POST['suggest_type']) ? 0 : intval($_POST['suggest_type']);
        $suggest_content=empty($_POST['suggest_content']) ? '' : $_POST['suggest_content'];
        $suggest_image=empty($_POST['suggest_image']) ? '' : $_POST['suggest_image'];
        if(empty($suggest_type)){
            exit(json_encode(array('code'=>1,'msg'=>'请选择建议种类')));
        }
        if(empty($suggest_content)){
            exit(json_encode(array('code'=>1,'msg'=>'请填写建议内容')));
        }
        $data=array(
            'member_id'=>$this->member_id,
            'suggest_type'=>$suggest_type,
            'suggest_content'=>$suggest_content,
            'suggest_image'=>empty($suggest_image) ? '' : json_encode($suggest_image),
            'add_time'=>time()
        );
        $suggest_id=DB::insert('suggest',$data,1);
        if(empty($suggest_id)){
            exit(json_encode(array('code'=>1,'msg'=>'操作失败')));
        }else{
            exit(json_encode(array('code'=>0,'msg'=>'操作成功')));
        }
    }

}

?>