<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class favoriteControl extends BaseProfileControl {
	public function indexOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 12;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=favorite";
		$wheresql = " WHERE member_id='$this->member_id' AND fav_type='goods'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('favorite').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('favorite').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$goods_ids[] = $value['fav_id'];
		}
		if(!empty($goods_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('goods')." WHERE goods_id in ('".implode("','", $goods_ids)."') AND goods_show=1 AND goods_state=1");
			while($value = DB::fetch($query)) {
				$goods_list[$value['goods_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'profile';
		$bodyclass = 'gray-bg';
		include(template('favorite_goods'));
	}

	//页头信息获取
    public  function common_nurseOp(){
        $member_id=empty($_GET['member_id']) ? 0 : intval($_GET['member_id']);
        $wheresql = " WHERE member_id='$member_id' AND favourite_type='favourite'";
            $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('member_favourite').$wheresql);
            $query = DB::query("SELECT * FROM ".DB::table('member_favourite').$wheresql." AND show_state=0");
        while($value = DB::fetch($query)) {
            $nurse_ids[] = $value['nurse_id'];
        }
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
        if(!empty($nurse_ids)) {
            $query = DB::query("SELECT * FROM " . DB::table('nurse') . " WHERE nurse_id in ('" . implode("','", $nurse_ids) . "') AND nurse_state=1 LIMIT 0,2");
            while ($value = DB::fetch($query)) {
                $agent_ids[] = $value['agent_id'];
                $nurse_list[$value['nurse_id']] = $value;
            }
            if(!empty($agent_ids)) {
                $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
                while($value = DB::fetch($query)) {
                    $agent_list[$value['agent_id']] = $value['agent_name'];
                }
            }
        }
        $query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
        while($value = DB::fetch($query)) {
            $grade_list[$value['grade_id']] = $value;
        }
        $member=DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='$member_id'");
        if(!empty($member)){
            $card = DB::fetch_first("SELECT * FROM ".DB::table('card')." WHERE card_predeposit<=".$member['card_predeposit']." ORDER BY card_predeposit DESC");
        }
        $nurse=DB::fetch_first("SELECT * FROM ".DB::table('nurse')." WHERE member_id='$member_id'");
        if(!empty($nurse)){
            $nurse_grade=DB::fetch_first("SELECT * FROM ".DB::table('nurse_grade')." WHERE nurse_score<=".$nurse['nurse_score']." ORDER BY nurse_score DESC");
        }
        $agent=DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE member_id='$member_id'");
        if(!empty($agent)){
            $agent_grade=DB::fetch_first("SELECT * FROM ".DB::table('agent_grade')." WHERE agent_score<=".$agent['agent_score']." ORDER BY agent_score DESC");
        }
        $grade_html='';
        if(!empty($nurse)&&!empty($agent)){
            $grade_html.='<p>雇主等级<img src="'.$card['card_icon'].'" alt=""></p>';
            $grade_html.='<p>机构等级<img src="'.$agent_grade['grade_icon'].'" alt=""></p>';
            $grade_html.='<p>家政等级<img src="'.$nurse_grade['grade_icon'].'" alt=""></p>';
            $grade_html.='<p style="line-height: 30px;"><a href="index.php?act=member_wallet">我的钱包 <img style="margin:0 0 4px 5px;" src="templates/images/money.png" alt=""></a></p>';
        }else if(empty($nurse)&&empty($agent)){
            $grade_html.='<p>雇主等级<img src="'.$card['card_icon'].'" alt=""></p>';
            $grade_html.='<p style="line-height: 30px;"><a href="index.php?act=member_wallet">我的钱包 <img style="margin:0 0 4px 5px;" src="templates/images/money.png" alt=""></a></p>';
        }else if(empty($nurse)){
            $grade_html.='<p>雇主等级<img src="'.$card['card_icon'].'" alt=""></p>';
            $grade_html.='<p>机构等级<img src="'.$agent_grade['grade_icon'].'" alt=""></p>';
            $grade_html.='<p style="line-height: 30px;"><a href="index.php?act=member_wallet">我的钱包 <img style="margin:0 0 4px 5px;" src="templates/images/money.png" alt=""></a></p>';
        }else if(empty($agent)){
            $grade_html.='<p>雇主等级<img src="'.$card['card_icon'].'" alt=""></p>';
            $grade_html.='<p>家政等级<img src="'.$nurse_grade['grade_icon'].'" alt=""></p>';
            $grade_html.='<p style="line-height: 30px;"><a href="index.php?act=member_wallet">我的钱包 <img style="margin:0 0 4px 5px;" src="templates/images/money.png" alt=""></a></p>';
        }
        $collect_html='';
        foreach($nurse_list as $key => $value) {
            $collect_html.='<div class="collect_nurse">';
            $collect_html.='<div class="left">';
            if($value['nurse_image']==''){
                $collect_html .= '<span><img width="50px" height="50px" src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></span>';
            } else {
                $collect_html .= '<span><img width="50px" height="50px" src="'.$value['nurse_image'].'"></span>';
            }
            $collect_html.='</div>';
            $collect_html.='<div class="left collect_message">';
            $collect_html.='<div class="collect_message1">';
            $collect_html.='<span class="span1">'.$value['nurse_nickname'].'</span>';
            $collect_html.='<span>'.$type_array[$value['service_type']].'</span>';
            if($value['nurse_type'] == 3) {
                $collect_html.='<span class="span3">每小时¥<b>'.$value['nurse_price'].'</b></span>';
            }elseif ($value['nurse_type'] == 4){
                $collect_html.='<span class="span3">每平方¥<b>'.$value['nurse_price'].'</b></span>';
            }elseif ($value['nurse_type'] == 5||$value['nurse_type']==6){
                $collect_html.='<span class="span3">每月¥<b>'.$value['nurse_price'].'</b></span>';
            }elseif ($value['nurse_type'] == 7||$value['nurse_type']==8){
                $collect_html.='<span class="span3">每次¥<b>'.$value['nurse_price'].'</b></span>';
            }elseif ($value['nurse_type'] == 9||$value['nurse_type']==10){
                $collect_html.='<span class="span3">每人¥<b>'.$value['nurse_price'].'</b></span>';
            }elseif ($value['nurse_type'] == 11||$value['nurse_type']==12){
                $collect_html.='<span class="span3">每月¥<b>'.$value['nurse_price'].'</b></span>';
            } else{
                $collect_html.='<span class="span3">服务费¥<b>'.$value['service_price'].'</b></span>';
            }
            $collect_html.='</div>';
            $collect_html.='<div class="collect_message2">';
            if($value['nurse_type'] == 3) {
                $collect_html.='<span>每小时'.$value['nurse_price'].'元</span>';
            }elseif ($value['nurse_type'] == 4){
                $collect_html.='<span>每平方'.$value['nurse_price'].'元</span>';
            }elseif ($value['nurse_type'] == 5||$value['nurse_type']==6){
                $collect_html.='<span>每月'.$value['nurse_price'].'元</span>';
            }elseif ($value['nurse_type'] == 7||$value['nurse_type']==8){
                $collect_html.='<span>每次'.$value['nurse_price'].'元</span>';
            }elseif ($value['nurse_type'] == 9||$value['nurse_type']==10){
                $collect_html.='<span>每人'.$value['nurse_price'].'元</span>';
            }elseif ($value['nurse_type'] == 11||$value['nurse_type']==12){
                $collect_html.='<span>每月'.$value['nurse_price'].'元</span>';
            } else{
                $collect_html.='<span>月薪'.$value['nurse_price'].'元</span>';
            }
            if($value['agent_id'] == 0){
                $collect_html.='<span class="span2" style="text-decoration: underline;">个人</span>';
            }else{
                $collect_html.='<span class="span2" style="text-decoration: underline;">'.$agent_list[$value['agent_id']].'</span>';
            }
            $collect_html.='</div>';
            $collect_html.='</div>';
            $collect_html.='</div>';
        }
        $counts=$count-2;
        if($counts<0){
            $counts=0;
        }
        $collect_more_html='<p class="right">服务车还有'.$counts.'位家政人员</p>';
        exit(json_encode(array('done'=>'true','collect_html'=>$collect_html,'collect_more_html'=>$collect_more_html,'grade_html'=>$grade_html,)));
    }
	public function nurseOp() {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		if($page < 1) $page = 1;
		$perpage = 8;
		$start = ($page-1)*$perpage;
		$mpurl = "index.php?act=favorite";
		$wheresql = " WHERE member_id='$this->member_id' AND fav_type='nurse'";
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('favorite').$wheresql);
		$query = DB::query("SELECT * FROM ".DB::table('favorite').$wheresql." LIMIT $start, $perpage");
		while($value = DB::fetch($query)) {
			$nurse_ids[] = $value['fav_id'];
		}
		if(!empty($nurse_ids)) {
			$query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE nurse_id in ('".implode("','", $nurse_ids)."') AND nurse_state=1");
			while($value = DB::fetch($query)) {
				$nurse_list[$value['nurse_id']] = $value;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
		$curmodule = 'home';
		$bodyclass = 'gray-bg';
		include(template('favorite_nurse'));
	}
	
	public function delOp() {
		$fav_id = empty($_GET['fav_id']) ? 0 : intval($_GET['fav_id']);
		$fav_type = !in_array($_GET['fav_type'], array('goods', 'nurse')) ? '' : $_GET['fav_type'];
		if(empty($fav_id) || empty($fav_type)) {
			exit(json_encode(array('msg'=>'对象不存在')));
		}
		$fav = DB::fetch_first("SELECT * FROM ".DB::table('favorite')." WHERE member_id='$this->member_id' AND fav_id='$fav_id' AND fav_type='$fav_type'");
		if(empty($fav)) {
			exit(json_encode(array('msg'=>'您已经取消了')));
		}
		DB::delete('favorite', array('member_id'=>$this->member_id, 'fav_id'=>$fav_id, 'fav_type'=>$fav_type));
		if($fav_type == 'goods') {
			DB::query("UPDATE ".DB::table('goods')." SET goods_favoritenum=goods_favoritenum-1 WHERE goods_id='$fav_id'");
		} elseif($fav_type == 'nurse') {
			DB::query("UPDATE ".DB::table('nurse')." SET nurse_favoritenum=nurse_favoritenum-1 WHERE nurse_id='$fav_id'");
		}
		exit(json_encode(array('done'=>'true')));			
	}
}

?>