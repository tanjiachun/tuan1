<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class cityControl extends BaseHomeControl {
	public function indexOp() {
		if(!empty($this->district['parent_id'])) {
			$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='".$this->district['parent_id']."' ORDER BY district_sort ASC LIMIT 0, 10");
			while($value = DB::fetch($query)) {
				if($value['district_id'] != $this->district['district_id']) {
					$near_list[] = $value;	
				}
			}
		}	
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id=0 ORDER BY district_sort ASC");
		while($value = DB::fetch($query)) {
			$province_list[$value['district_id']] = $value;	
		}
		$area_list = array(
			'0' => array('15', '10', '11', '12'),
			'1' => array('19', '13', '20', '21'),
			'2' => array('16', '17', '18', '14'),
			'3' => array('6', '8', '7'),
			'4' => array('23', '25', '24', '26'),
			'5' => array('3', '4', '5'),
			'6' => array('27', '31', '28', '30', '29'),
		);
		$query = DB::query("SELECT * FROM ".DB::table('district')." WHERE district_level=2 ORDER BY district_sort");
		while($value = DB::fetch($query)) {
			$city_list[$value['parent_id']][] = $value;	
		}
		$district_id=empty($_GET['district_id']) ? 0 : $_GET['district_id'];
		if(!empty($district_id)){
            $query = DB::query("SELECT * FROM ".DB::table('district')." WHERE parent_id='$district_id' ORDER BY district_sort ASC");
            while($value = DB::fetch($query)) {
                $district_list[] = $value;
            }
        }
		$curmodule = 'home';
		$bodyclass = 'changecity';
		include(template('city'));
	}
	
	public function selectOp() {
		$district_id = empty($_GET['district_id']) ? 0 : intval($_GET['district_id']);
		if(in_array($district_id , array('1', '2', '9', '22', '32', '33', '34'))) {
			$district_ipname = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$district_id'");
		} else {
			$district_ipname = DB::result_first("SELECT district_ipname FROM ".DB::table('district')." WHERE district_id='$district_id' AND district_level=2");
		}
		if(!empty($district_ipname)) {
			dsetcookie('district_ipname', $district_ipname, 86400);
			@header("Location: index.php");
			exit;
		} else {
			@header("Location: index.php?act=city");
			exit;
		}	
	}
//	public function select_cityOp(){
//        $district_id = empty($_GET['district_id']) ? 0 : intval($_GET['district_id']);
//        $district_name = DB::result_first("SELECT district_name FROM ".DB::table('district')." WHERE district_id='$district_id' AND district_level=3");
//        if(!empty($district_name)) {
//            dsetcookie('district_name', $district_name, 86400);
//            @header("Location: index.php");
//            exit;
//        } else {
//            @header("Location: index.php?act=city");
//            exit;
//        }
//    }
	public function checknameOp() {
		$district_name = empty($_GET['district_name']) ? '' : $_GET['district_name'];
		$province_array = array('北京', '上海', '天津', '重庆', '台湾', '香港', '澳门');
		if(in_array($district_name, $province_array)) {
			$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_ipname='$district_name'");
		} else {
			$district = DB::fetch_first("SELECT * FROM ".DB::table('district')." WHERE district_name='$district_name'");
		}
		if(!empty($district)) {
			exit(json_encode(array('done'=>'true', 'district_id'=>$district['district_id'])));
		} else {
			exit(json_encode(array('msg'=>'对不起，找不到该城市')));
		}
	}
}

?>