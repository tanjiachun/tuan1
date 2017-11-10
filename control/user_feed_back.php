<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class user_feed_backControl extends BaseHomeControl {
    public function indexOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了','index.php?act=login','info');
        }
        $curmodule = 'home';
        $bodyclass = '';
        include(template('user_feed_back'));
    }
    public function suggestOp(){
        if(empty($this->member_id)){
            $this->showmessage('您还未登录了','index.php?act=login','info');
        }
        $suggest_type=empty($_POST['suggest_type']) ? 0 : intval($_POST['suggest_type']);
        $suggest_content=empty($_POST['suggest_content']) ? '' : $_POST['suggest_content'];
        $suggest_image=empty($_POST['suggest_image']) ? '' : $_POST['suggest_image'];
        if(empty($suggest_type)){
            exit(json_encode(array('msg'=>'请选择建议种类')));
        }
        if(empty($suggest_content)){
            exit(json_encode(array('msg'=>'请填写建议内容')));
        }
        $data=array(
            'member_id'=>$this->member_id,
            'suggest_type'=>$suggest_type,
            'suggest_content'=>$suggest_content,
            'suggest_image'=>empty($suggest_image) ? '' : json_encode($suggest_image),
            'add_time'=>time()
        );
        DB::insert('suggest',$data);
        exit(json_encode(array('done'=>'true')));
    }
}
?>