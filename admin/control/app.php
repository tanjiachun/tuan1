<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class appControl extends BaseAdminControl {
	public function indexOp() {
		if(submitcheck()) {
			$app_desc = empty($_POST['app_desc']) ? '' : $_POST['app_desc'];

			// 首页轮播 x 5
			$app_banner_image = empty($_POST['app_banner_image']) ? '' : serialize($_POST['app_banner_image']);
			$app_banner_url = empty($_POST['app_banner_url']) ? '' : serialize($_POST['app_banner_url']);
			$app_banner_weight = empty($_POST['app_banner_weight']) ? '' : serialize($_POST['app_banner_weight']);

			// 分类图标 x 24
			$app_icon_image = empty($_POST['app_icon_image']) ? '' : serialize($_POST['app_icon_image']);
			$app_icon_url = empty($_POST['app_icon_url']) ? '' : serialize($_POST['app_icon_url']);
			$app_icon_weight = empty($_POST['app_icon_weight']) ? '' : serialize($_POST['app_icon_weight']);
			$app_icon_name = empty($_POST['app_icon_name']) ? '' : serialize($_POST['app_icon_name']);

			//  活动专区  x 4
            $app_active_image = empty($_POST['app_active_image']) ? '' : serialize($_POST['app_active_image']);
            $app_active_url = empty($_POST['app_active_url']) ? '' : serialize($_POST['app_active_url']);
            $app_active_weight = empty($_POST['app_active_weight']) ? '' : serialize($_POST['app_active_weight']);

            // 入驻  x 1
            $adv_image = empty($_POST['adv_image']) ? '' : $_POST['adv_image'];

            //热门服务 x 3
            $app_hot_service_image = empty($_POST['app_hot_service_image']) ? '' : serialize($_POST['app_hot_service_image']);
            $app_hot_service_url = empty($_POST['app_hot_service_url']) ? '' : serialize($_POST['app_hot_service_url']);
            $app_hot_service_weight = empty($_POST['app_hot_service_weight']) ? '' : serialize($_POST['app_hot_service_weight']);


			DB::query("REPLACE INTO ".DB::table('setting')." VALUES ('app_desc', '".$app_desc."'), ('app_banner_image', '".$app_banner_image."'),('app_banner_url', '".$app_banner_url."'),('app_banner_weight', '".$app_banner_weight."'),('app_icon_image', '".$app_icon_image."'),('app_icon_url', '".$app_icon_url."'),('app_icon_weight', '".$app_icon_weight."'),('app_icon_name', '".$app_icon_name."'),('app_active_image', '".$app_active_image."'),('app_active_url', '".$app_active_url."'),('app_active_weight', '".$app_active_weight."'), ('adv_image', '".$adv_image."'),('app_hot_service_image', '".$app_hot_service_image."'),('app_hot_service_url', '".$app_hot_service_url."'),('app_hot_service_weight', '".$app_hot_service_weight."')");
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			writetocache('setting', getcachevars(array('setting'=>$setting)));
			showdialog('保存成功', 'admin.php?act=app', 'succ');
		} else {
			$query = DB::query("SELECT * FROM ".DB::table("setting"));
			while($value = DB::fetch($query)) {
				$setting[$value['setting_key']] = $value['setting_value'];	
			}
			// 轮播
			$setting['app_banner_image'] = empty($setting['app_banner_image']) ? array() : unserialize($setting['app_banner_image']);
			$setting['app_banner_url'] = empty($setting['app_banner_url']) ? array() : unserialize($setting['app_banner_url']);
			$setting['app_banner_weight'] = empty($setting['app_banner_weight']) ? array() : unserialize($setting['app_banner_weight']);

			// 分类图标
            $setting['app_icon_image'] = empty($setting['app_icon_image']) ? array() : unserialize($setting['app_icon_image']);
            $setting['app_icon_url'] = empty($setting['app_icon_url']) ? array() : unserialize($setting['app_icon_url']);
            $setting['app_icon_weight'] = empty($setting['app_icon_weight']) ? array() : unserialize($setting['app_icon_weight']);
            $setting['app_icon_name'] = empty($setting['app_icon_name']) ? array() : unserialize($setting['app_icon_name']);

            //活动专区
            $setting['app_active_image'] = empty($setting['app_active_image']) ? array() : unserialize($setting['app_active_image']);
            $setting['app_active_url'] = empty($setting['app_active_url']) ? array() : unserialize($setting['app_active_url']);
            $setting['app_active_weight'] = empty($setting['app_active_weight']) ? array() : unserialize($setting['app_active_weight']);

            // 热门服务
            $setting['app_hot_service_image'] = empty($setting['app_hot_service_image']) ? array() : unserialize($setting['app_hot_service_image']);
            $setting['app_hot_service_url'] = empty($setting['app_hot_service_url']) ? array() : unserialize($setting['app_hot_service_url']);
            $setting['app_hot_service_weight'] = empty($setting['app_hot_service_weight']) ? array() : unserialize($setting['app_hot_service_weight']);


			include(template('app'));
		}
	}
}
?>