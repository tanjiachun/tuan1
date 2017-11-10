<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class agent_staffControl extends BaseHomeControl {
    public function indexOp() {
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $keyword = empty($_GET['keyword']) ? '' : $_GET['keyword'];
        $agent = DB::fetch_first("SELECT * FROM ".DB::table('agent')." WHERE agent_id='$agent_id'");
        if(empty($agent)) {
            $this->showmessage('家政机构不存在', 'index.php?act=index', 'error');
        }
        $member = DB::fetch_first("SELECT * FROM ".DB::table('member')." WHERE member_id='".$agent['member_id']."'");
        $agent_grade = DB::fetch_first("SELECT * FROM ".DB::table('agent_grade')." WHERE agent_score<=".$agent['agent_score']." ORDER BY agent_score DESC");
        $nurse_count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' AND nurse_state=1");
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
        $count=DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse')." WHERE agent_id='$agent_id'");
        $page = 1;
        $perpage = 40;$start = ($page-1)*$perpage;
        $query = DB::query("SELECT * FROM ".DB::table('nurse')." WHERE agent_id='$agent_id' AND nurse_content like '%".$keyword."%' AND nurse_state=1 ORDER BY nurse_time DESC LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $agent_ids[] = $value['agent_id'];
            $value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
            $value['comment_score'] = priceformat($value['nurse_score']/$value['nurse_commentnum']);
            $nurse_list[] = $value;
        }
        if(!empty($agent_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
            while($value = DB::fetch($query)) {
                $agent_list[$value['agent_id']] = $value['agent_name'];
            }
        }
        $query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
        while($value = DB::fetch($query)) {
            $grade_list[$value['grade_id']] = $value;
        }
        $multi = multi($count, $perpage, $page, '', 'selectnurse');
        $multiTop=multiTop($count, $perpage, $page, '', 'selectnurse');
        $maxpage = ceil($count/$perpage);
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_staff'));
    }

    public function nurse_searchOp() {
        $type_array = array('1'=>'职业保姆', '2'=>'涉外保姆', '3'=>'钟点服务', '4'=>'清洁清扫','5'=>'月嫂保育','6'=>'育婴早教','7'=>'水电维修','8'=>'管道疏通','9'=>'搬家服务','10'=>'设备搬运','11'=>'家庭外教','12'=>'家庭辅导','13'=>'陪护医护','14'=>'老年照顾','15'=>'管家服务','16'=>'高级家教');
        $query = DB::query("SELECT * FROM ".DB::table("nurse_grade")." ORDER BY nurse_score ASC");
        while($value = DB::fetch($query)) {
            $grade_list[$value['grade_id']] = $value;
        }
        $agent_id=empty($_GET['agent_id']) ? 0 : intval($_GET['agent_id']);
        $keyword = empty($_GET['keyword']) ? '' : $_GET['keyword'];
        $service_type = empty($_GET['service_type']) ? '' : $_GET['service_type'];
        $nurse_type = empty($_GET['nurse_type']) ? 0 : intval($_GET['nurse_type']);
        $nurse_education = empty($_GET['nurse_education']) ? '' : $_GET['nurse_education'];
        $nurse_price = empty($_GET['nurse_price']) ? '' : $_GET['nurse_price'];
        $nurse_age = empty($_GET['nurse_age']) ? '' : $_GET['nurse_age'];
        $grade_id = empty($_GET['grade_id']) ? 0 : intval($_GET['grade_id']);
        $sort_field = !in_array($_GET['sort_field'], array('time', 'education', 'price', 'age', 'new')) ? 'time' : $_GET['sort_field'];
        $time_sort = !in_array($_GET['time_sort'], array('asc', 'desc')) ? 'desc' : $_GET['time_sort'];
        $price_sort = !in_array($_GET['price_sort'], array('asc', 'desc')) ? 'desc' : $_GET['price_sort'];
        $salenum_sort = !in_array($_GET['salenum_sort'], array('asc', 'desc')) ? 'desc' : $_GET['salenum_sort'];
        $commentnum_sort = !in_array($_GET['commentnum_sort'], array('asc', 'desc')) ? 'desc' : $_GET['commentnum_sort'];
        $favoritenum_sort = !in_array($_GET['favoritenum_sort'], array('asc', 'desc')) ? 'desc' : $_GET['favoritenum_sort'];
        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
        if($page < 1) $page = 1;
        $perpage = 40;
        $start = ($page-1)*$perpage;
        $wheresql = " WHERE agent_id='$agent_id' AND nurse_state=1";
        if(!empty($keyword)) {
            $wheresql .= " AND nurse_content like '%".$keyword."%'";
        }
        if(!empty($nurse_type)) {
            $wheresql .= " AND nurse_type='$nurse_type'";
        }
        if(!empty($service_type)) {
            $wheresql .= " AND service_type like '%".$service_type."%'";
        }
        if(!empty($nurse_education)) {
            $education_array = explode('-', $nurse_education);
            if(!empty($education_array[0])) {
                $wheresql .= " AND nurse_education>='$education_array[0]'";
            }
            if(!empty($education_array[1])) {
                $wheresql .= " AND nurse_education<='$education_array[1]'";
            }
        }
        if(!empty($nurse_price)) {
            $price_array = explode('-', $nurse_price);
            if(!empty($price_array[0])) {
                $wheresql .= " AND nurse_price>='$price_array[0]'";
            }
            if(!empty($price_array[1])) {
                $wheresql .= " AND nurse_price<='$price_array[1]'";
            }
        }
        if(!empty($nurse_age)) {
            $age_array = explode('-', $nurse_age);
            if(!empty($age_array[0])) {
                $wheresql .= " AND nurse_age>='$age_array[0]'";
            }
            if(!empty($age_array[1])) {
                $wheresql .= " AND nurse_age<='$age_array[1]'";
            }
        }
        if(!empty($grade_id)) {
            $wheresql .= " AND grade_id='$grade_id'";
        }
        if($sort_field == 'time') {
            $ordersql = " ORDER BY nurse_time $time_sort";
        } elseif($sort_field == 'price') {
            $ordersql = " ORDER BY nurse_price $price_sort";
        } elseif($sort_field == 'salenum') {
            $ordersql = " ORDER BY nurse_age $salenum_sort";
        }  elseif($sort_field == 'commentnum') {
            $ordersql = " ORDER BY nurse_age $commentnum_sort";
        }  elseif($sort_field == 'favoritenum') {
            $ordersql = " ORDER BY nurse_age $favoritenum_sort";
        } elseif($sort_field == 'new') {
            $ordersql = " ORDER BY nurse_time DESC";
        }
        $count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nurse').$wheresql);
        $query = DB::query("SELECT * FROM ".DB::table('nurse').$wheresql.$ordersql." LIMIT $start, $perpage");
        while($value = DB::fetch($query)) {
            $agent_ids[] = $value['agent_id'];
            $value['nurse_tags'] = empty($value['nurse_tags']) ? array() : unserialize($value['nurse_tags']);
            $value['comment_score'] = priceformat($value['nurse_score']/$value['nurse_commentnum']);
            $nurse_list[] = $value;
        }
        if(!empty($agent_ids)) {
            $query = DB::query("SELECT * FROM ".DB::table('agent')." WHERE agent_id in ('".implode("','", $agent_ids)."')");
            while($value = DB::fetch($query)) {
                $agent_list[$value['agent_id']] = $value['agent_name'];
            }
        }
        $multi = multi($count, $perpage, $page, '', 'selectnurse');
        $maxpage = ceil($count/$perpage);
        $nurse_html = '';
        if(empty($nurse_list)) {
            $nurse_html .= '<div class="no-shop"><dl><dt></dt><dd><p>抱歉，没有找到符合条件的看护人员</p><p>您可以适当减少筛选条件</p></dd></dl></div>';
        } else {
            $nurse_html .= '<ul>';
            foreach($nurse_list as $key => $value) {
                $nurse_html .= '<li>';
                $nurse_html .= '<div class="nurse-img">';
                if($value['nurse_image']==''){
                    $nurse_html .= '<a target="_Blank" href="index.php?act=nurse&nurse_id='.$value['nurse_id'].'"><img src="data/nurse/201706/26/143921ze4zqzemwqqree0p.png"></a>';
                } else {
                    $nurse_html .= '<a target="_Blank" href="index.php?act=nurse&nurse_id='.$value['nurse_id'].'"><img src="'.$value['nurse_image'].'"></a>';
                }
                $nurse_html.='<div class="nurse-salary">';
                if($value['nurse_type'] == 3) {
                    $nurse_html.='<span class="nurse-price">¥<strong>'.$value['nurse_price'].'</strong>/时</span>';
                }else if ($value['nurse_type']==4){
                    $nurse_html.='<span class="nurse-price">¥<strong>'.$value['nurse_price'].'</strong>/平方</span>';
                }else if($value['nurse_type']==7 || $value['nurse_type']==8 || $value['nurse_type']==9 || $value['nurse_type']==10){
                    $nurse_html.='<span class="nurse-price">¥<strong>'.$value['nurse_price'].'</strong>/次</span>';
                } else{
                    $nurse_html.='<span class="nurse-price">¥<strong>'.$value['nurse_price'].'</strong>/月</span>';
                }
                $nurse_html.='</div>';
                $nurse_html.='</div>';
                $nurse_html .='<div class="clear"></div>';
                $nurse_html.='<div class="nurse-type">';
                $nurse_html.='<a target="_Blank" href="index.php?act=nurse&nurse_id='.$value['nurse_id'].'" class="nurse-name">'.$value['nurse_nickname'].'</a><span>'.$value['service_type'].'</span>';
                $nurse_html.='<span class="nurse_content">'.$value['nurse_content'].'</span>';
                $nurse_html.='</div>';
                $nurse_html.='<div class="nurse-evaluate">';
                $nurse_html.='<span class="level-box"><img src="'.$grade_list[$value['grade_id']]['grade_icon'].'" alt=""></span>';
                $nurse_html.='<span class="nurse-score">';
                $nurse_html.='评价 ('.$value['nurse_commentnum'].')';
                $nurse_html.='</span>';
                $nurse_html.=' <span class="book_count right">';
                $nurse_html.=''.$value['nurse_salenum'].'人已付款';
                $nurse_html.='</span>';
                $nurse_html.='</div>';
                $nurse_html.='<div class="nurse-certified">';
                if($value['agent_id'] == 0){
                    $nurse_html.='<span style="text-decoration: underline;">个人</span>';
                }else{
                    $nurse_html.=' <span><a style="text-decoration: underline;" href="index.php?act=agent_show&agent_id='.$value['agent_id'].'">'.$agent_list[$value['agent_id']].'</a></span>';
                }
                $nurse_html.='</div>';
                $nurse_html .= '</li>';
            }
            $nurse_html .= '</ul>';
        }
        $nurse_multi_html = '';
        $nurse_multi_html .= $multi;
        $multiTop=multiTop($count, $perpage, $page, '', 'selectnurse');
        $multiTop_html='';
        $multiTop_html.=$multiTop;
        exit(json_encode(array('done'=>'true', 'multiTop_html'=>$multiTop_html,'nurse_page_html'=>$nurse_page_html, 'nurse_count_html'=>$nurse_count_html, 'nurse_html'=>$nurse_html, 'nurse_multi_html'=>$nurse_multi_html)));
        $curmodule = 'home';
        $bodyclass = '';
        include(template('agent_staff'));
    }

}

?>