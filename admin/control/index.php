<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class indexControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$banner_image = empty($_POST['banner_image']) ? '' : serialize($_POST['banner_image']);
			$banner_url = empty($_POST['banner_url']) ? '' : serialize($_POST['banner_url']);
			$banner_left_image=empty($_POST['banner_left_image']) ? '' : $_POST['banner_left_image'];
			$banner_left_url=empty($_POST['banner_left_url']) ? '' : $_POST['banner_left_url'];
            $nav_image = empty($_POST['nav_image']) ? '' : serialize($_POST['nav_image']);
            $nav_url = empty($_POST['nav_url']) ? '' : serialize($_POST['nav_url']);
            $nav_name = empty($_POST['nav_name']) ? '' : serialize($_POST['nav_name']);
            $hot_image = empty($_POST['hot_image']) ? '' : serialize($_POST['hot_image']);
            $hot_url = empty($_POST['hot_url']) ? '' : serialize($_POST['hot_url']);
			$site_name = empty($_POST['site_name']) ? '' : $_POST['site_name'];
			$seo_keyword = empty($_POST['seo_keyword']) ? '' : $_POST['seo_keyword'];
			$seo_desc =  empty($_POST['seo_desc']) ? '' : $_POST['seo_desc'];
			$service_qq_arr = empty($_POST['service_qq']) ? '' : $_POST['service_qq'];
			$site_time =  empty($_POST['site_time']) ? '' : $_POST['site_time'];
			$site_phone =  empty($_POST['site_phone']) ? '' : $_POST['site_phone'];
			$first_province = empty($_POST['first_province']) ? '' : $_POST['first_province'];
			$second_province = empty($_POST['second_province']) ? '' : $_POST['second_province'];
			$app_image = empty($_POST['app_image']) ? '' : $_POST['app_image'];
			$copy_right = empty($_POST['copy_right']) ? '' : $_POST['copy_right'];
			$sale_support = empty($_POST['sale_support']) ? '' : $_POST['sale_support'];
			$nurse_fee = empty($_POST['nurse_fee']) ? 0 : intval($_POST['nurse_fee']);
			foreach($service_qq_arr as $key => $value) {
				if(!empty($value)) {
					$service_qq[] = $value;
				}
			}
			$service_qq = empty($service_qq) ? '' : serialize($service_qq);
			$first_province = empty($first_province) ? array() : explode('|', $first_province);
			if(!empty($first_province)) {
				$query = DB::query("SELECT * FROM ".DB::table("district")." WHERE district_ipname in ('".implode("','", $first_province)."')");
				while($value = DB::fetch($query)) {
					$first_district_list[$value['district_ipname']] = $value;
				}
			}
			foreach($first_province as $key => $value) {
				if(!empty($first_district_list[$value])) {
					$first_province_list[] = array(
						'district_id' => $first_district_list[$value]['district_id'],
						'district_ipname' => $first_district_list[$value]['district_ipname'],
					);
					$first_province_temp[] = $first_district_list[$value]['district_ipname'];
				}
			}
			$first_province = empty($first_province_temp) ? '' : implode('|', $first_province_temp);
			$first_province_list = empty($first_province_list) ? '' : serialize($first_province_list);
			$second_province = empty($second_province) ? array() : explode('|', $second_province);
			if(!empty($second_province)) {
				$query = DB::query("SELECT * FROM ".DB::table("district")." WHERE district_ipname in ('".implode("','", $second_province)."')");
				while($value = DB::fetch($query)) {
					$second_district_list[$value['district_ipname']] = $value;
				}
			}
			foreach($second_province as $key => $value) {
				if(!empty($second_district_list[$value])) {
					$second_province_list[] = array(
						'district_id' => $second_district_list[$value]['district_id'],
						'district_ipname' => $second_district_list[$value]['district_ipname'],
					);
					$second_province_temp[] = $second_district_list[$value]['district_ipname'];
				}
			}
			$second_province = empty($second_province_temp) ? '' : implode('|', $second_province_temp);
			$second_province_list = empty($second_province_list) ? '' : serialize($second_province_list);
			DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('banner_image', '".$banner_image."'), ('banner_url', '".$banner_url."'),('banner_left_image', '".$banner_left_image."'),('banner_left_url', '".$banner_left_url."'), ('nav_image', '".$nav_image."'), ('nav_url', '".$nav_url."'),('nav_name', '".$nav_name."'),('hot_image', '".$hot_image."'), ('hot_url', '".$hot_url."'),('site_name', '".$site_name."'), ('seo_keyword', '".$seo_keyword."'), ('seo_desc', '".$seo_desc."'), ('service_qq', '".$service_qq."'), ('site_time', '".$site_time."'), ('site_phone', '".$site_phone."'), ('first_province', '".$first_province."'), ('first_province_list', '".$first_province_list."'), ('second_province', '".$second_province."'), ('second_province_list', '".$second_province_list."'), ('app_image', '".$app_image."'), ('copy_right', '".$copy_right."'), ('sale_support', '".$sale_support."'), ('nurse_fee', '".$nurse_fee."')");
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
			showdialog('保存成功', 'admin.php?act=index', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];
			}
			$setting['banner_image'] = empty($setting['banner_image']) ? array() : unserialize($setting['banner_image']);
			$setting['banner_url'] = empty($setting['banner_url']) ? array() : unserialize($setting['banner_url']);
            $setting['nav_image'] = empty($setting['nav_image']) ? array() : unserialize($setting['nav_image']);
            $setting['nav_url'] = empty($setting['nav_url']) ? array() : unserialize($setting['nav_url']);
            $setting['nav_name'] = empty($setting['nav_name']) ? array() : unserialize($setting['nav_name']);
            $setting['hot_image'] = empty($setting['hot_image']) ? array() : unserialize($setting['hot_image']);
            $setting['hot_url'] = empty($setting['hot_url']) ? array() : unserialize($setting['hot_url']);
			$setting['service_qq'] = empty($setting['service_qq']) ? array() : unserialize($setting['service_qq']);
			$setting['province_ids'] = empty($setting['province_ids']) ? array() : unserialize($setting['province_ids']);
			include(template('index'));
		}
	}
}
?>