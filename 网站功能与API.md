
> 2017/10/20  初稿

> 编辑：张腾

> 只包含网站前台页面，不包含网站后台管理页面
#### 网站首页 请求地址：` /index.php` 
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
```
{
    $this->setting['banner_image'] : 图片轮播数组 {0：url,1:url,..}
    $this->setting['banner_url'] : 轮播URL数组 {0:url,1:url,...}
    $this->setting['nav_image'] : 热门家政机构图片数组 {0：url,1:url,..}
    $this->setting['nav_url'] : 热门家政机构URL数组 {0：url,1:url,..}
    $this->setting['hot_image'] : 机构热卖图片数组 {0：url,1:url,..}
    $this->setting['hot_url'] : 机构热卖URL数组 {0：url,1:url,..}
    $this->setting['banner_left_image'] ： 热门家政机构左侧图片 url
    $this->setting['banner_left_url'] ： 热门家政机构左侧URL url
    $this->setting['app_image'] ：app二维码URL
    $this->district['district_name'] ： 当前城市名
    $this->setting['service_qq'] ： 客服QQ
    $book_list : 24小时交易动态 ；{
        member_phone : 雇主手机号码 （已处理）,
        comment_time : 交易时间 （时间戳）
    }
    $comment_lisr ：雇主热评 ：{
        $member_list[$value['nurse_id']]['nurse_areaname'] ：地区
        comment_content : 评价内容
    }
    $nurse_list : 家政人员列表{
        nurse_id : 家政人员ID
        nurse_image : 家政人员头像
        nurse_type : 家政人员类别 （1-16）（
            1、2 ：/月 3 ：/时 4 ：/平方 5、6 ：/月
            7、8 ：/次 9/10 ：/次 11、12 ：/月 13、14、15、16 ：/月
        ）
        service_type : 家政人员服务类别
        nurse_price : 家政人员工资
        nurse_nickname : 家政人员称呼
        nurse_content : 家政人员自我简介
        nurse_special_service : 特色需求
        $grade_list[$value['grade_id']]['grade_icon'] ：等级图标URL
        nurse_commentnum ：评价人数
        nurse_salenum ：付款人数
        $agent_list[$value['agent_id']] ：机构名称 （如果为空，显示个人）
    }
    $multi ：页码模块
}
```

#### 网站首页 请求地址：` /index.php?act=index&op=nurse` 
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_type|int|家政人员类别|
|service_type|string|家政人员服务类别|
|keyword|string|搜索关键字|
|page|int|页码数|

######返回JSON数据格式
````
{
    $nurse_list : 家政人员列表{
        nurse_id : 家政人员ID
        nurse_image : 家政人员头像
        nurse_type : 家政人员类别 （1-16）（
            1、2 ：/月 3 ：/时 4 ：/平方 5、6 ：/月
            7、8 ：/次 9/10 ：/次 11、12 ：/月 13、14、15、16 ：/月
        ）
        service_type : 家政人员服务类别
        nurse_price : 家政人员工资
        nurse_nickname : 家政人员称呼
        nurse_content : 家政人员自我简介
        nurse_special_service : 特色需求
        $grade_list[$value['grade_id']]['grade_icon'] ：等级图标URL
        nurse_commentnum ：评价人数
        nurse_salenum ：付款人数
        $agent_list[$value['agent_id']] ：机构名称 （如果为空，显示个人）
    }
    $multi : 页码模块
}

````

#### 新用户注册验证码接口 请求地址：` /index.php?act=register`
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话号码|

######返回JSON数据格式
````
{
    done ：true 成功
    msg ：失败信息
}
````

#### 网站新用户注册页 请求地址：` /index.php?act=register`
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话号码|
|phone_code|string|验证码|
|pwd|string|密码|
|pwd2|string|确认密码|

######返回JSON数据格式
````
{
    done ：true 成功
    msg ：失败信息
}
````

#### 忘记密码短信接口 请求地址：` /index.php?act=misc&op=forget_pwd_code`
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话号码|

######返回JSON数据格式
````
{
    done ：true 成功
    msg ：失败信息
}
````

#### 忘记密码第一步 请求地址 ：` /index.php?act=forget`
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话号码|
|phone_code|string|验证码|

######返回JSON数据格式
````
{
    done ：true 成功
    msg ：失败信息
}
````
#### 忘记密码第二步 请求地址 ：` /index.php?act=forget&op=step2`
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_password|string|密码|
|member_password2|string|确认密码|

######返回JSON数据格式
````
{
    done ：true 成功
    msg ：失败信息
}
````
#### 忘记密码第三步 请求地址 ：` /index.php?act=forget&op=step3`
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无
######返回JSON数据格式
````
无
````

#### 验证码登录获取验证码 请求地址 ：` /index.php?act=misc&op=login_code`
######请求参数
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|手机号码|

######返回JSON数据格式
````
{
    done ：true 成功
    msg ：失败信息
}
````
#### 验证码登录 请求地址：` /index.php?act=login_code`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话号码|
|phone_code|string|验证码|

######返回JSON数据格式
````
{
    done ：true 成功
    msg ：失败信息
}
````

#### 网页密码登录 请求地址 ：` /index.php?act=login`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话号码|
|member_password|string|密码|
|cookietime|int|是否记住用户名 0：不记住 1：记住|

######返回JSON数据格式
````
无
````

#### 设置页面 请求地址 ：` /index.php?act=member_set`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $footprint_list ：我的足迹列表 {
        $nurse_list[$value['nurse_id']]['nurse_image'] ：家政人员头像
        $nurse_list[$value['nurse_id']]['nurse_nickname'] ：家政人员称呼
        $nurse_list[$value['nurse_id']]['service_type'] ：家政人员服务类别
    }
}
````

#### 设计页面 换一批 请求地址 ：` /index.php?act=member_set`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|state|string|rand|

######返回JSON数据格式
````
{
    $footprint_list ：我的足迹列表 {
        $nurse_list[$value['nurse_id']]['nurse_image'] ：家政人员头像
        $nurse_list[$value['nurse_id']]['nurse_nickname'] ：家政人员称呼
        $nurse_list[$value['nurse_id']]['service_type'] ：家政人员服务类别
    }
}
````


#### 帮助与反馈页 
> 还未设计

#### 关于团家政页 请求地址 ：` /index.php?act=article`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|article_id|int|文章ID|

######返回JSON数据格式
````
{
    $article_list ：文章列表 {
        article_id ：文章ID
        article_title ：文章标题
    }
    $article['article_content'] ：文章内容
}
````

#### 交易通知页 请求地址：` /index.php?act=message_center`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $deal_count ：交易通知数量
     $system_count ：系统通知数量
     $interact_count ：互动通知数量
     $message_show : (如果禁止接收交易通知，所抛出的msg)
     $message_list ：未读消息列表 {
        message_id ：消息ID
        message_content ：消息内容
     }
     $message_read_list : 已读消息列表 {
        message_id ：消息ID
        message_content ：消息内容
     }
}
````

#### 交易通知设为已读 请求地址 ：` /index.php?act=message_center&op=read`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|read_ids|0,1,2|消息ID （大于1个中间用 ，分隔）|

######返回JSON数据格式
````
{
   done ：成功
   msg ：失败抛出的msg
}
````

#### 交易通知删除 请求地址 ：` /index.php?act=message_center&op=del`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|del_ids|0,1,2|消息ID （大于1个中间用 ，分隔）|

######返回JSON数据格式
````
{
   done ：成功
   msg ：失败抛出的msg
}
````

#### 系统通知页 请求地址：` /index.php?act=message_system`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $deal_count ：交易通知数量
     $system_count ：系统通知数量
     $interact_count ：互动通知数量
     $message_show : (如果禁止接收系统通知，所抛出的msg)
     $message_list ：未读消息列表 {
        message_id ：消息ID
        message_content ：消息内容
     }
     $message_read_list : 已读消息列表 {
        message_id ：消息ID
        message_content ：消息内容
     }
}
````

#### 系统通知设为已读 请求地址 ：` /index.php?act=message_system&op=read`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|read_ids|0,1,2|消息ID （大于1个中间用 ，分隔）|

######返回JSON数据格式
````
{
   done ：成功
   msg ：失败抛出的msg
}
````

#### 系统交易通知删除 请求地址 ：` /index.php?act=message_system&op=del`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|del_ids|0,1,2|消息ID （大于1个中间用 ，分隔）|

######返回JSON数据格式
````
{
   done ：成功
   msg ：失败抛出的msg
}
````

#### 互动通知页 请求地址：` /index.php?act=message_interact`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $deal_count ：交易通知数量
     $system_count ：系统通知数量
     $interact_count ：互动通知数量
     $message_show : (如果禁止接收互动通知，所抛出的msg)
     $message_list ：未读消息列表 {
        message_id ：消息ID
        message_content ：消息内容
     }
     $message_read_list : 已读消息列表 {
        message_id ：消息ID
        message_content ：消息内容
     }
}
````

#### 系统通知设为已读 请求地址 ：` /index.php?act=message_interact&op=read`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|read_ids|0,1,2|消息ID （大于1个中间用 ，分隔）|

######返回JSON数据格式
````
{
   done ：成功
   msg ：失败抛出的msg
}
````

#### 系统交易通知删除 请求地址 ：` /index.php?act=message_interact&op=del`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|del_ids|0,1,2|消息ID （大于1个中间用 ，分隔）|

######返回JSON数据格式
````
{
   done ：成功
   msg ：失败抛出的msg
}
````

#### 消息设置页面 请求地址 ：` /index.php?act=message_set`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
   $message_set : 消息设置情况 {
        system_message_state ：系统消息设置状态 0：开启 1：关闭
        deal_message_state ：交易消息设置状态 0：开启 1：关闭
        interact_message_state ; 互动消息设置状态 0：开启 1：关闭
   }
}
````

#### 交易通知设置 请求地址 ：` /index.php?act=message_set&op=step1`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|deal_message_state|int|交易通知状态 0：开启 1：关闭|

######返回JSON数据格式
````
{
   done : 成功
   msg ：抛出错误消息
}
````

#### 系统通知设置 请求地址 ：` /index.php?act=message_set&op=step2`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|system_message_state|int|交易通知状态 0：开启 1：关闭|

######返回JSON数据格式
````
{
   done : 成功
   msg ：抛出错误消息
}
````

#### 互动通知设置 请求地址 ：` /index.php?act=message_set&op=step3`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|interact_message_state|int|交易通知状态 0：开启 1：关闭|

######返回JSON数据格式
````
{
   done : 成功
   msg ：抛出错误消息
}
````

#### 服务车/全部家政人员页 ：` /index.php?act=member_collect`  GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|state|string|all|

######返回JSON数据格式
````
{
   all_count : 收藏家政人员的数量
   lose_count : 失效收藏的数量
   favourite_list : 收藏列表 {
        collect_id ：收藏ID
        $agent_list[$value['agent_id']]['agent_name'] ：机构名称
        $nurse_list[$value['nurse_id']]['member_phone'] ：联系方式
        $nurse_list[$value['nurse_id']]['yx_accid'] ：云信ID
        $nurse_list[$value['nurse_id']]['nurse_image'] ：家政人员头像
        $nurse_list[$value['nurse_id']]['nurse_nickname'] ：家政人员称呼
        $nurse_list[$value['nurse_id']]['nurse_special_service'] ：特色需求
        $nurse_list[$value['nurse_id']]['promise_state'] ：家政人员承诺 （1：不支持三小时无理由 2：支持三小时无理由 3：不支持三天无理由 4：支持三天无理由）
        $nurse_list[$value['nurse_id']]['nurse_type'] ：家政人员类别
        $nurse_list[$value['nurse_id']]['nurse_price'] ：家政人员工资
        $nurse_list[$value['nurse_id']]['nurse_discount'] ：家政人员折扣
        $nurse_list[$value['nurse_id']]['service_price'] ：家政人员服务费
        $nurse_list[$value['nurse_id']]['students_state'] （0：不支持多余一个学生 1：支持多余一个学生）
        $nurse_list[$value['nurse_id']]['students_sale'] ：（0：多余一个学生无优惠 1：多余一个学生优惠）
        $nurse_list[$value['nurse_id']]['car_weight_list'] ：搬运工 车吨位列表
        $nurse_list[$value['nurse_id']]['car_price_list'] ; 搬运工 车价格列表
        
   }
}
````

#### 服务车/降价服务 ：` /index.php?act=member_collect`     GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|state|string|discount|

######返回JSON数据格式
````
{
   all_count : 收藏家政人员的数量
   lose_count : 失效收藏的数量
   favourite_list : 收藏列表 {
        collect_id ：收藏ID
        $agent_list[$value['agent_id']]['agent_name'] ：机构名称
        $nurse_list[$value['nurse_id']]['member_phone'] ：联系方式
        $nurse_list[$value['nurse_id']]['yx_accid'] ：云信ID
        $nurse_list[$value['nurse_id']]['nurse_image'] ：家政人员头像
        $nurse_list[$value['nurse_id']]['nurse_nickname'] ：家政人员称呼
        $nurse_list[$value['nurse_id']]['nurse_special_service'] ：特色需求
        $nurse_list[$value['nurse_id']]['promise_state'] ：家政人员承诺 （1：不支持三小时无理由 2：支持三小时无理由 3：不支持三天无理由 4：支持三天无理由）
        $nurse_list[$value['nurse_id']]['nurse_type'] ：家政人员类别
        $nurse_list[$value['nurse_id']]['nurse_price'] ：家政人员工资
        $nurse_list[$value['nurse_id']]['nurse_discount'] ：家政人员折扣
        $nurse_list[$value['nurse_id']]['service_price'] ：家政人员服务费
        $nurse_list[$value['nurse_id']]['students_state'] （0：不支持多余一个学生 1：支持多余一个学生）
        $nurse_list[$value['nurse_id']]['students_sale'] ：（0：多余一个学生无优惠 1：多余一个学生优惠）
        $nurse_list[$value['nurse_id']]['car_weight_list'] ：搬运工 车吨位列表
        $nurse_list[$value['nurse_id']]['car_price_list'] ; 搬运工 车价格列表
        
   }
}
````

#### 服务车/暂时失效 ：` /index.php?act=member_collect`     GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|state|string|lose|

######返回JSON数据格式
````
{
   all_count : 收藏家政人员的数量
   lose_count : 失效收藏的数量
   favourite_list : 收藏列表 {
        collect_id ：收藏ID
        $agent_list[$value['agent_id']]['agent_name'] ：机构名称
        $nurse_list[$value['nurse_id']]['member_phone'] ：联系方式
        $nurse_list[$value['nurse_id']]['yx_accid'] ：云信ID
        $nurse_list[$value['nurse_id']]['nurse_image'] ：家政人员头像
        $nurse_list[$value['nurse_id']]['nurse_nickname'] ：家政人员称呼
        $nurse_list[$value['nurse_id']]['nurse_special_service'] ：特色需求
        $nurse_list[$value['nurse_id']]['promise_state'] ：家政人员承诺 （1：不支持三小时无理由 2：支持三小时无理由 3：不支持三天无理由 4：支持三天无理由）
        $nurse_list[$value['nurse_id']]['nurse_type'] ：家政人员类别
        $nurse_list[$value['nurse_id']]['nurse_price'] ：家政人员工资
        $nurse_list[$value['nurse_id']]['nurse_discount'] ：家政人员折扣
        $nurse_list[$value['nurse_id']]['service_price'] ：家政人员服务费
        $nurse_list[$value['nurse_id']]['students_state'] （0：不支持多余一个学生 1：支持多余一个学生）
        $nurse_list[$value['nurse_id']]['students_sale'] ：（0：多余一个学生无优惠 1：多余一个学生优惠）
        $nurse_list[$value['nurse_id']]['car_weight_list'] ：搬运工 车吨位列表
        $nurse_list[$value['nurse_id']]['car_price_list'] ; 搬运工 车价格列表
        
   }
}
````

#### 服务车 删除家政人员 ：` /index.php?act=member_collect&op=del` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_id|int|个人ID|
|del_id|0,1,2|要删除的ID|

######返回JSON数据格式
````
{
   done : 成功
   msg ：抛出错误消息
}
````

#### 服务车 删除失效收藏 ：` /index.php?act=member_collect&op=del_lose` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_id|int|个人ID|

######返回JSON数据格式
````
{
   done : 成功
   msg ：抛出错误消息
}
````

#### 城市切换页 ：`index.php?act=city`    
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
   $this->district['district_id'] ： 默认城市ID
   $this->district['district_ipname'] ：默认城市名称
   $near_list : 周边城市列表 {
        district_id ：城市ID
        district_ipname ：城市名称
   }
   $area_list : 地区列表 {
        '0' => array('15', '10', '11', '12'),
        '1' => array('19', '13', '20', '21'),
        '2' => array('16', '17', '18', '14'),
        '3' => array('6', '8', '7'),
        '4' => array('23', '25', '24', '26'),
        '5' => array('3', '4', '5'),
        '6' => array('27', '31', '28', '30', '29'),
   }
    $province_list[$province_id]['district_ipname'] ：省份名称
    $city_list[$province_id] ：城市列表 {
        district_id ：城市ID
        district_ipname ：城市名称
    }
}
````

#### 城市切换页 点击城市名 ：`index.php?act=city&op=select`    GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|district_id|int|城市ID|

######返回JSON数据格式
````
无
````

#### 城市切换页 搜索热门城市 ：`index.php?act=city&op=checkname`    GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|district_name|string|城市名|

######返回JSON数据格式
````
done ：成功,district_id ：城市ID
msg ：错误抛出msg
````

#### 个人/个人资料页 ：`index.php?act=member_center` 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $this->member['member_avatar'] ：头像URL
    $this->member['member_phone'] ： 账号
    $this->member['member_nickname'] ：昵称
    $this->member['member_sex'] ; 性别 （0：保密 1：男 2：女）
    $this->member['member_birthyear'] ：生日/年
    $this->member['member_birthmonth'] ：生日/月
    $this->member['member_birthday'] ：生日/日
    $current_year ：当前年
    $days ：该月多少天
    $province_list ：省份列表 {
        district_id ：城市ID
        district_name ：城市名称
    }
    $member_city_list ：市列表 {
        district_id ：城市ID
        district_name ：城市名称
    }
    $member_area_list ：县/区列表 {
        district_id ：城市ID
        district_name ：城市名称
    }
    $member_provinceid ：地址省份ID
    $member_cityid ：地址市ID
    $member_areaid ：地址县/区ID
}
````

#### 个人/个人资料提交修改 ：`index.php?act=member_center&op=member_resume`    POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_nickname|string|昵称|
|member_avatar|string|头像URL|
|member_sex|int|性别 （0：保密 1：男 2：女）|
|member_birthyear|int|生日/年|
|member_birthmonth|int|生日/月|
|member_birthday|int|生日/日|
|member_provinceid|int|地址/省份ID|
|member_cityid|int|地址/市ID|
|member_areaid|int|县/区ID|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 个人/实名认证 ：`index.php?act=member_real_name`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $member_name ：真实姓名或账号
    $this->member['member_real_state'] ：认证状态 （0：未认证 1：已认证 2：重新认证 3：认证中）
}
````
#### 个人/填写个人信息 ：`index.php?act=member_verify_identity`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $template_msg ：状态抛出msg
}
````

####实名认证/填写个人信息界面获取验证码 ：`index.php?act=misc&op=member_verify_code`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|号码|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````


#### 实名认证/填写个人信息 ：`index.php?act=member_verify_identity&op=real_name_step1` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_truename|string|真实姓名|
|member_cardid|string|身份证号|
|member_phone|string|号码|
|phone_code|string|验证码|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 实名认证/上传个人资料 ：`index.php?act=member_verify_identity&op=real_name_step2` POST
 | 字段名称 | 字段类型  | 备注 |
 | -------- | -------- | -------- |
 |member_cardid_image|string|身份证正面照URL|
 |member_cardid_back_image|string|身份证反面照URL|
 
 ######返回JSON数据格式
 ````
 {
     done ：成功
     msg ：错误抛出msg
 }
 ````
 
 #### 实名认证/认证成功
 >  - -
 
 
 #### 个人/地址展示页 ：`index.php?act=member_address_set`  GET
 | 字段名称 | 字段类型  | 备注 |
 | -------- | -------- | -------- |
 |page|int|页码数|
 
######返回JSON数据格式
````
{
  $multi ：页码模块
  $province_list ：省份列表 {
        district_id ：城市ID
        district_name ：城市名称
  }
  $member_city_list ：市列表 {
        district_id ：城市ID
        district_name ：城市名称
  }
  $member_area_list ：县/区列表 {
        district_id ：城市ID
        district_name ：城市名称
  }
  $address_list ：地址列表 {
        member_address_id ：地址ID
        address_member_name ：姓名
        member_areainfo ：所在地区
        address_content ：详细地址
        address_phone ：电话/手机
        choose_state ：默认选中
  }
}
````

#### 地址/地址管理 ：`index.php?act=member_address_set&op=address_set` POST
 | 字段名称 | 字段类型  | 备注 |
 | -------- | -------- | -------- |
 |member_provinceid|int|省份ID|
 |member_cityid|int|市ID|
 |member_areaid|int|县/区ID|
|address_content|string|详细地址|
|address_member_name|string|姓名|
|address_phone|string|号码|
|member_selected|int|0:未选中 1：默认选中|

 ######返回JSON数据格式
 ````
 {
     done ：成功
     msg ：错误抛出msg
 }
 ````
 
 #### 地址/删除地址 ：`index.php?act=member_address_set&op=del_address` POST
  | 字段名称 | 字段类型  | 备注 |
  | -------- | -------- | -------- |
  |member_address_id|int|地址ID|
  
  ######返回JSON数据格式
   ````
   {
       done ：成功
       msg ：错误抛出msg
   }
   ````
   
 #### 地址/修改地址显示 ：`index.php?act=member_address_set&op=address_resume` GET
 
    | 字段名称 | 字段类型  | 备注 |
    | -------- | -------- | -------- |
    |member_address_id|int|地址ID|
    
 ######返回JSON数据格式
    ````
    {
        $member_address : 地址详情{
            $member_provincename ：地址省份名称
            $member_cityname ： 地址市名称
            $member_areaname ：地址县/区名称
            $member_address['address_content'] ：详细地址
            $member_address['address_member_name'] ：姓名
            $member_address['address_phone'] ：电话
            $member_address['choose_state'] ：选中状态 （0：未选中 1：选中）
        }
        $province_list ：省份列表 {
                district_id ：城市ID
                district_name ：城市名称
          }
          $member_city_list ：市列表 {
                district_id ：城市ID
                district_name ：城市名称
          }
          $member_area_list ：县/区列表 {
                district_id ：城市ID
                district_name ：城市名称
          }
    }
    ````
   
 #### 地址/修改地址提交 ：`index.php?act=member_address_set&op=address_resume`  POST
  | 字段名称 | 字段类型  | 备注 |
 | -------- | -------- | -------- |
 |member_address_id|int|地址ID|
 |member_provinceid|int|省份ID|
 |member_cityid|int|市ID|
 |member_areaid|int|县/区ID|
 |address_content|string|详细地址|
 |address_member_name|string|姓名|
 |address_phone|string|号码|
 |member_selected|int|是否设为默认地址 0：否 1：是|
 
######返回JSON数据格式
````
{
   done ：成功
   msg ：错误抛出msg
}
````

#### 密码管理/登录密码修改 验证码获取 `index.php?act=misc&op=login_pwd_code` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````
 
#### 密码管理/登录密码修改 提交 `index.php?act=member_password_set&op=login_set` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话|
|login_phone_code|string|验证码|
|login_password|string|密码|
|login_password2|string|确认密码|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````
 
#### 密码管理/支付密码修改 验证码获取 `index.php?act=misc&op=pay_pwd_code` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````
 
#### 密码管理/支付密码修改 提交 `index.php?act=member_password_set&op=pay_set` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话|
|pay_phone_code|string|验证码|
|pay_password|string|密码|
|pay_password2|string|确认密码|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 个人/我的订单页 `index.php?act=member_book`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|state|string|pending：待付款 payment：已付款 duty：待上岗 evaluation：待评价 finish：已完成 cancel：已取消|
|search_name|string|搜索框获取内容|

######返回JSON数据格式
````
{
    $book_list ：订单列表 {
        book_id ：订单ID
        agent_id ：机构ID
        nurse_state ：家政人员状态
        $agent_list[$value['agent_id']]['agent_name'] ：机构名称
        add_time ：订单添加时间
        book_sn ：订单编号
        $nurse_list[$value['nurse_id']]['yx_accid'] ：家政人员云信ID
        $nurse_list[$value['nurse_id']]['nurse_image'] ：家政人员头像
        $nurse_list[$value['nurse_id']]['nurse_nickname'] ：家政人员称呼
        $nurse_list[$value['nurse_id']]['member_phone'] ：家政人员电话
        $nurse_list[$value['nurse_id']]['promise_state'] ：家政人员承诺  （1：不支持三小时无理由 2：支持三小时无理由 3：不支持三天无理由 4：支持三天无理由）
        $nurse_list[$value['nurse_id']]['nurse_type'] ：家政人员类别
        $nurse_list[$value['nurse_id']]['nurse_price'] ：家政人员工资
        service_price ：服务费
        book_details ：订单详情
        book_amount ：订单实付款
        member_coin_amount ：团豆豆数量
        book_state ：订单状态 （0：取消 10：未付款 20：已付款 30：已完成）
        refund_state ：退款状态 （1：退款）
        work_duration ：工资月数
        finish_time ：订单月结束时间
        book_finish_time ：订单总结束时间
        pay_count ：订单支付次数
        comment_state ：订单评价状态
        work_duration_days ：工作天数
        work_time ：工作开始天数
        book_message ：雇主预留情况
        book_service ：额外服务
        $multi ：页码模块
    }
}
````

#### 我的钱包/展示页 `index.php?act=member_book`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
    $this->member['available_predeposit'] ：可用金额
    $this->member['member_coin'] ：团豆豆余额
}
````

#### 查看账户明细页 `index.php?act=predeposit` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|

######返回JSON数据格式
````
{
    $this->member['available_predeposit'] ：可用金额
    $pd_list ：收支明细列表 {
        pdl_addtime ：时间
        markclass ：颜色值class
        mark ：收入/支出符号
        pdl_price ：金额
        pdl_predeposit ：账户余额
        pdl_desc ：备注
    }
}
````

#### 我的钱包/提现输入金额页 `index.php?act=cash`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|pdc_amount|int|提现金额|

######返回JSON数据格式
````
{
    done : 成功
    msg ：错误抛出msg
}
````

#### 我的钱包/提现选择方式页 `index.php?act=cash&op=step2` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|pdc_code|string|提现方式|
|alipay_card|string|支付宝账号|
|weixin_card|string|微信账号|
|bank_membername|string|银行卡收款人|
|bank_deposit|string|开户行|
|bank_card|string|银行卡号|

######返回JSON数据格式
````
{
    done : 成功
    msg ：错误抛出msg
}
````

#### 我的钱包 充值 `index.php?act=recharge`   POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|pdr_amount|int|充值金额|
|pdr_payment_code|string|充值方式|

######返回JSON数据格式
````
{
    done : 成功，pdr_sn ：充值单号
    msg ：错误抛出msg
}
````

#### 我的钱包/扫码充值页 `index.php?act=payment&op=recharge` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|pdr_sn|string|充值单号|

######返回JSON数据格式
````
{
    支付宝 ：。。。
    微信 ：$code_url
}
````

#### 我的钱包/查看团豆豆明细页 `index.php?act=member_coin_details` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|

######返回JSON数据格式
````
{
    $this->member['member_coin'] ：团豆豆余额
    $coin_list ：团豆豆明细列表 {
        true_time ：时间
        markclass ：颜色值class
        coin_count ：团豆豆数量
        get_type ：（register ：注册 level_up ：升级 member_comment ：评价 nurse_comment ：被评价 sure_work ：确认在岗 payment ：退款/付款 discount ：取消订单/退款/抵用折扣 sign ：签到 share ：分享）
        $multi 页面模块
    }
}
````

#### 个人/我的评价页 `index.php?act=member_comment`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|state|string|all ：全部 good ：好评 middle ：中评 bad：差评 back ：有回复|

######返回JSON数据格式
````
{
    $comment_list : 评价列表 {
        comment_id ：评价ID
        $book_list[$value['book_id']]['book_sn'] ：订单编号
        $nurse_list[$value['nurse_id']]['nurse_nickname'] ：家政人员称呼
        $book_list[$value['book_id']]['book_finish_time'] ：订单结束时间
        $book_list[$value['book_id']]['work_duration_days'] ：交易天数
        $book_list[$value['book_id']]['book_amount'] ：交易金额
        comment_level ：评价等级 （good ：好评 middle ：中评 bad ：差评）
        comment_level_star ：城市守信星级
        comment_love_star ：爱岗敬业星级
        comment_content ：评价内容
        comment_image ：评价配图
        agent_reply_content ：家政人员回复
        comment_time ：评价时间
        comment_revise_state ：修改评价次数
    }
    $multi ：页码模块
}
````

#### 个人/我的关注 `index.php?act=favorite&op=nurse`  GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|

######返回JSON数据格式
````
{
    $nurse_ids ：收藏的家政人员id {
        $nurse_list[$value]['nurse_id'] ：家政人员ID
        $nurse_list[$value]['nurse_image'] ：家政人员头像
        $nurse_list[$value]['nurse_name'] ：家政人员姓名
        $nurse_list[$value]['nurse_type'] ：家政人员类别
        $nurse_list[$value]['nurse_price'] ：家政人员工资
        $nurse_list[$value]['nurse_commentnum'] ：家政人员被评价数量
        $nurse_list[$value]['nurse_viewnum'] ：家政人员被查看数量
        
    }
    $multi ：页码模块
}
````

#### 机构入驻/验证手机 `index.php?act=misc&op=test_code`    POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话|

######返回JSON数据格式
````
{
    done : 成功
    msg ：错误抛出msg
}
````


#### 机构入驻/填写资料、上传资质、入驻成功页 `index.php?act=agent&op=step2`    POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_name|string|机构名称|
|owner_name|string|法人姓名|
|agent_provinceid|int|省份ID|
|agent_cityid|int|市ID|
|agent_areaid|int|县/区ID|
|agent_address|string|详细地址|
|agent_location|经度,纬度|机构经纬度|
|agent_code_image|string|营业执照正本|
|agent_person_image|string|法人身份证正面|
|agent_person_code_image|string|法人手持营业执照自拍|
|agent_sign_image|string|机构门头照|
|card_phone|string|机构号码|
|phone_code|string|验证码|


######返回JSON数据格式
````
{
    done : 成功
    msg ：错误抛出msg
}
````

#### 机构入驻/入驻成功页
> - -


#### 机构管理平台/首页编辑展示 `index.php?act=agent_center`   
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{   
    agent_id ：机构编号
    $nurse_count ：员工数量
    $book_count ：累计交易
    $question_count ：待回答问题数量
    agent_focusnum ：被关注数量
    agent_viewnum ：浏览数
    revise_state ：0 ：正在审核 1：通过审核 2：未通过审核
    agent_name ：机构名称
    owner_name ：法人姓名
    agent_phone ：机构电话
    agent_address ：机构地址
    agent_logo ：机构LOGO
    $this->agent['agent_banner'] ：机构横幅
    $this->agent['agent_logo'] ：机构LOGO
    agent_code_image ：营业执照正面
    agent_person_image ：法人身份证正面
    agent_person_code_image ：法人手持营业执照正面
    agent_sign_image ：机构门头照
    agent_qa_image ：工作资质 （列表）
    agent_summary ：机构概述
    agent_content ：机构服务
    agent_service_image ：机构服务广告 （列表）
}
````

#### 机构管理平台/首页编辑提交 `index.php?act=agent_center&op=agent_revise`     POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
|agent_name|string|机构名称|
|owner_name|string|法人姓名|
|agent_phone|string|客服座机|
|agent_address|string|机构地址|
|agent_location|经度,纬度|机构经纬度|
|agent_banner|string|机构横幅|
|agent_logo|string|机构LOGO|
|agent_code_image|string|营业执照正面|
|agent_person_image|string|法人身份证正面|
|agent_person_code_image|string|法人手持营业执照|
|agent_sign_image|string|机构门头照|
|agent_qa_image|string|工作资质（列表）|
|agent_service_image|string|机构服务广告|
|agent_summary|string|机构概述|
|agent_content|string|记过服务|

######返回JSON数据格式
````
{
    done ：成功
    id ：system  msg ：错误抛出msg
}
````

#### 机构管理平台/机构问答页展示 `index.php?act=agent_question`  
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
        agent_id ：机构编号
        $nurse_count ：员工数量
        $book_count ：累计交易
        $question_count ：待回答问题数量
        agent_focusnum ：被关注数量
        agent_viewnum ：浏览数
        agent_logo ：机构LOGO
        $question_list ：机构问题列表 {
            $member_list[$value['member_id']]['member_image'] ：提问人头像
            $member_list[$value['member_id']]['member_name'] ：提问人账号 （已处理）
            question_time ：提问时间
            question_content ：提问内容
            question_id ：问题编号
        }
}
````

#### 机构管理平台/机构问答页回答提交 `index.php?act=agent_question&op=answer`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|question_id|int|问题ID|
|answer_content|string|回答内容|

######返回JSON数据格式
````
{
    done ：成功
    done：false  msg ：错误抛出msg
}
````

#### 机构管理平台/手机设置页 `index.php?act=agent_phone_set`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{
     agent_id ：机构编号
    $nurse_count ：员工数量
    $book_count ：累计交易
    $question_count ：待回答问题数量
    agent_focusnum ：被关注数量
    agent_viewnum ：浏览数
    agent_logo ：机构LOGO
    $agent['member_phone'] ：绑定号码，不可修改
    $agent['agent_other_phone'] ：机构号码 （列表）
    $agent['agent_other_phone_choose'] ：选中默认显示号码
    $agent['agent_phone'] ：客服号码
}
````

#### 机构管理平台/手机设置页 `index.php?act=agent_phone_set&op=phone_set`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
|agent_other_phone|string|机构号码（列表）|
|agent_other_phone_choose|string|默认选中 （列表）|
|agent_phone|string|客服电话|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/员工设置 `index.php?act=agent_nurse_set` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|state_cideci|string|all：全部 forjob：待业中 onwork：已在岗 holiday：假期中|
|state|string|show ：全部 sex：性别 age：年龄 score：等级 time：加盟时间|
|search_name|string|搜索框获取内容|

######返回JSON数据格式
````
{
    agent_id ：机构编号
    $nurse_count ：员工数量
    $book_count ：累计交易
    $question_count ：待回答问题数量
    agent_focusnum ：被关注数量
    agent_viewnum ：浏览数
    agent_logo ：机构LOGO
    $agent['agent_other_phone'] ：机构电话
    $nurse_state_array ：家政人员状态id列表
    $nurse_state_name_array ：家政人员状态名称列表
    $nurse_discount_array ：折扣列表1
    $nurse_discount_array2 ：折扣列表2
    $authority_state_array ：权限id列表
    $authority_state_name_array ：权限名称列表
    $service_price_array ：服务费列表
    $nurse_list ：员工列表 {
        nurse_id ：家政人员ID
        state_cideci ：家政人员工作状态 （0：无 1：待业 2：在岗 3：假期 4：到岗）
        nurse_name ：家政人员姓名
        nurse_type ：家政人员类别
        service_price ：服务费
        nurse_discount ：家政人员折扣率
        agent_phone ：号码
        authority_state ：权限 （0：默认 1：可修改 2：不可修改）
        
    }
    
}
````

####  机构管理平台/员工设置/权限设置 `index.php?act=agent_nurse_set&op=authority` POST 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|authority_ids|0,1,2..|家政人员id（多个中间以，隔开）|
|authority_value|int|权限值|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/员工设置/服务承诺设置 `index.php?act=agent_nurse_set&op=promise` POST 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|promise_ids|0,1,2..|家政人员id（多个中间以,隔开）|
|promise_value|int|承诺值|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/员工设置/服务承诺设置 `index.php?act=agent_nurse_set&op=phone` POST 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|phone_ids|0,1,2..|家政人员id（多个中间以,隔开）|
|phone_value|string|电话|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/辞退家政人员 `index.php?act=agent_nurse_set&op=dismiss` POST 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|dismiss_ids|0,1,2..|家政人员id（多个中间以,隔开）|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/员工资料修改提交（无需审核项） `index.php?act=agent_nurse_set&op=nurse_set` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_price|int|工资|
|service_price|int|服务费|
|agent_phone|string|机构电话|
|state_cideci|int|员工状态|
|nurse_discount| |折扣|
|authority_state|int|权限值|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/员工资料修改提交（需审核项）读取数据`index.php?act=agent_nurse_set&op=edit` 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据格式
````
{   
    $province_list ：省份列表 {
        district_id ：ID
        district_name ：名称
    }
    $birth_city_list ：市 {
        district_id ：ID
        district_name ：名称
    }
    $birth_area_list ：县/区 {
        district_id ：ID
        district_name ：名称
    }
    $nurse_city_list ：市 {
        district_id ：ID
        district_name ：名称
    }
    $nurse_area_list ：县/区 {
        district_id ：ID
        district_name ：名称
    }
    agent_id ：机构编号
    $nurse_count ：员工数量
    $book_count ：累计交易
    $question_count ：待回答问题数量
    agent_focusnum ：被关注数量
    agent_viewnum ：浏览数
    agent_logo ：机构LOGO
    nurse_name ：家政人员姓名
    member_phone ：家政人员手机号
    nurse_type ：家政人员类别
    service_type ：服务类别
    nurse_special_service ：特色需求
    nurse_age ：家政人员年龄
    $birth_provinceid ：出生省份ID
    $birth_provincename ：出生省份 名称
    $birth_cityid ：出生市ID
    $birth_cityname ：出生市 名称
    $birth_areaid ：出生 县/区 ID
    $birth_areaname ：出生 县/区 名称
    $nurse_provinceid ：出生 省份 ID
    $nurse_provincename ：出生 省份 名称
    $nurse_cityid ：出生 市 ID
    $nurse_cityname ：出生 市 名称
    $nurse_areaid ：出生 县/区 ID
    $nurse_areaname ：出生 县/区 名称
    nurse_address ：详细地址
    nurse_education ：工作年限
    
    nurse_price ：家政人员工资
    nurse_image ：家政人员头像
    nurse_cardid ：身份证号
    nurse_cardid_image ：手持身份证照
    nurse_qa_image ：工作资质
    nurse_content ：服务项目
}
````

#### 机构管理平台/员工资料修改提交（需审核项）提交数据 `index.php?act=agent_nurse_set&op=edit`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_id|int|家政人员ID|
|nurse_name|string|家政人员姓名|
|nurse_phone|string|家政人员电话|
|nurse_image|string|家政人员头像|
|nurse_type|int|家政人员类别|
|nurse_special_service|string|特色需求|
|nurse_price|int|家政人员工资|
|nurse_age|int|家政人员年龄|
|nurse_education|int|工作年限|
|birth_provinceid|int|生日 省 ID|
|birth_cityid|int| 生日 市 ID|
|birth_areaid|int|生日 县/区 ID|
|nurse_provinceid|int|现居地址 省 ID|
|nurse_cityid|int|现居地址 市 ID|
|nurse_areaid|int|现居地址 县/区 ID|
|nurse_address|string|详细地址|
|nurse_cardid|string|身份证号|
|nurse_cardid_image|string|手持身份证照|
|nurse_qa_image|string|工作地址|
|nurse_content|string|服务项目|


######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/审核员工 `index.php?act=agent_nurse_audit`
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无


######返回JSON数据格式
````
{
        agent_id ：机构编号
        $nurse_count ：员工数量
        $book_count ：累计交易
        $question_count ：待回答问题数量
        agent_focusnum ：被关注数量
        agent_viewnum ：浏览数
        agent_logo ：机构LOGO
        $nurse_audit_list : 员工审核 {
            nurse_id ：家政人员ID
        }
        $nurse_recruit_list ：招募状态 {
            nurse_id ：家政人员ID
            nurse_audit_state ：招募状态 （0：无回复 1：已同意 2：已拒绝）
        }
}
````

#### 机构管理平台/审核员工 通过审核 `index.php?act=agent_nurse_audit&op=agree` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_id|int|家政人员ID|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````


#### 机构管理平台/审核员工 不通过审核 `index.php?act=agent_nurse_audit&op=reject` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_id|int|家政人员ID|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/招募新员工 `index.php?act=agent_nurse_audit&op=staff_recruit`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_id|int|家政人员ID|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/重复招募 `index.php?act=agent_nurse_audit&op=re_invitation` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|staff_id|int|招募信息ID|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/新建员工 验证码获取 `index.php?act=misc&op=agent_nurse_add_code` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_phone|string|电话|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/新建员工 `index.php?act=agent_nurse_add&op=add` POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_name|string|家政人员姓名|
|nurse_sex|int|家政人员性别|
|agent_phone|string|机构号码|
|nurse_age|int|家政人员年龄|
|birth_cityname|string|籍贯|
|nurse_type|int|家政人员类别|
|service_type|string|家政人员服务类别|
|nurse_special_service|string|特色需求|
|nurse_price|int|工资|
|nurse_content|string|个人简介|
|nurse_image|string|家政人员头像|
|nurse_cardid_image|string|身份证正面照|
|nurse_cardid_person_image|string|手持身份证照|
|nurse_qa_image|string|工作资质|
|nurse_work_exe|string|工作经验 （列表）|
|car_weight_list|string|车吨位 （列表）|
|car_price_list|string|车价格 （列表）|
|students_state|int|是否支持多余一个学生|
|students_sale|int|多余一个学生是否打折|
|promise_state|int|家政人员承诺值|
|member_phone|string|家政人员电话号码|
|phone_code|string|验证码|

######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````

#### 机构管理平台/全部订单 `index.php?act=agent_book` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页，页码数|
|state|string|all：全部 in_three_month：三个月之内 payment：已付款 onwork：已在岗 evaluated：已评价 close：关闭的订单 before_three_month：三个月之前|
|search_name|string|搜索框获取内容|
######返回JSON数据格式
````
{
    agent_id ：机构编号
    $nurse_count ：员工数量
    $book_count ：累计交易
    $question_count ：待回答问题数量
    agent_focusnum ：被关注数量
    agent_viewnum ：浏览数
    agent_logo ：机构LOGO
    $book_list ：订单列表 {
        book_id ：订单ID
        book_sn ：订单编号
        add_time ：预约时间
        $nurse_list[$value['nurse_id']]['nurse_name'] ：员工名称
        member_phone ：雇主号码
        book_amount ：订单金额
        book_details ：订单详情
        comment_state ：评价状态
        book_state ：订单状态 （0：已关闭 10：未付款 20：已付款 30：已完成）
        $nurse_list[$value['nurse_id']]['state_cideci'] ：家政人员状态
        
    }
    $multi ：页码模块
}
````

#### 机构管理平台/全部订单 删除订单（单个） `index.php?act=agent_book&op=del_book`     POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_id|int|订单ID|

######返回JSON数据格式
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 机构管理平台/全部订单 删除订单 （多个） `index.php?act=agent_book&op=del_books`     POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|del_book_ids|0,1,2|订单ID|

######返回JSON数据格式
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 机构管理平台/全部订单 确认在岗 `index.php?act=agent_book&op=onwork`   POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_id|int|订单ID|
|book_code|string|在岗验证码|

######返回JSON数据格式
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 机构管理平台/全部订单 取消订单 `index.php?act=agent_book&op=cancel`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|cancel_id|int|订单ID|

######返回JSON数据格式
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 机构管理平台/全部订单 订单退款 `index.php?act=agent_book&op=refund`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|refund_id|int|退款订单ID|
|refund_amount|int|退款金额|
|refund_reason|string|退款原因|
|refund_state|int|退款处理方式|

######返回JSON数据格式
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 机构管理平台/评价管理 `index.php?act=agent_evaluate`     GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|state|string|all：全部 nurse_reply：未回复雇主 nurse_comment：未评价雇主 comment_middle：雇主中评 comment_bad：雇主差评 nurse_good：我的好评 nurse_middle：我的中评 nurse_bad：我的差评|


######返回JSON数据格式
````
{
      agent_id ：机构编号
     $nurse_count ：员工数量
     $book_count ：累计交易
     $question_count ：待回答问题数量
     agent_focusnum ：被关注数量
     agent_viewnum ：浏览数
     agent_logo ：机构LOGO
     $comment_list ：评价列表 {
        comment_id ：评价ID
        $book_list[$value['book_id']]['book_sn'] ：订单编号
        $book_list[$value['book_id']]['member_phone'] ：雇主号码
        $nurse_list[$value['nurse_id']]['nurse_name'] ：家政人员姓名
        $book_list[$value['book_id']]['finish_time'] ：订单结束时间
        $book_list[$value['book_id']]['work_duration_days'] ：交易天数
        $book_list[$value['book_id']]['book_amount'] ：交易金额
        comment_level ：评价等级
        comment_honest_star ：诚实守信星级
        comment_love_star ：爱岗敬业星级
        comment_content ：评价内容
        comment_image ：评价配图
        agent_reply_content ：回复内容
        nurse_comment_state ：我的评价状态 （0：未评价 1：已评价）
        nurse_comment_level ：家政人员评价等级
        nurse_comment_time ：家政人员评价时间
        nurse_revise_state ：家政人员修改评价状态 （1：修改过一次）
        nurse_comment_content ：家政人员评价内容
        nurse_comment_image ：家政人员评价配图
     }
     $multi ：页码模块
}
````

#### 机构管理平台/评价管理 家政人员回复 `index.php?act=agent_evaluate&op=agent_reply`   POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|comment_id|int|评价ID|
|agent_reply_content|string|家政人员回复内容|


######返回JSON数据格式
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 机构管理平台/评价管理 统一回复 `index.php?act=agent_evaluate&op=agent_replys`     POST
 | 字段名称 | 字段类型  | 备注 |
 | -------- | -------- | -------- |
 |comment_ids|0,1,2|评价编号|
 |agent_reply_content|string|家政人员回复内容|
 
######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
 #### 机构管理平台/评价管理 家政人员评价雇主 `index.php?act=agent_evaluate&op=nurse_comment`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|comment_id|int|评价ID|
|nurse_comment_level|string|家政人员评价等级|
|nurse_comment_content|string|家政人员评价内容|
|nurse_comment_image|string|家政人员评价图片|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
#### 机构管理平台/评价管理 家政人员修改评价 展示页 `index.php?act=agent_evaluate&op=nurse_comment_resume`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|comment_id|int|评价ID|

######返回JSON数据格式
 ````
 {
       nurse_comment_level ：家政人员评价等级
       nurse_comment_content ：家政人员评价内容
       nurse_comment_image ：家政人员评价配图
       $member :
       .
       .
       .(雇主相关信息)
 }
 ````
 
#### 机构管理平台/评价管理 家政人员修改评价 提交 `index.php?act=agent_evaluate&op=nurse_comment_resume`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|comment_id|int|评价ID|
|nurse_comment_level|string|家政人员评价等级|
|nurse_comment_content|string|家政人员评价内容|
|nurse_comment_image|string|家政人员评价配图|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
#### 机构管理平台/评价管理 `index.php?act=agent_refund`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|search_name|string|搜索框内容|
|state|string|all：全部 time：按时间排序 nurse_refund：员工收到的退款 member_refund：员工申请的退款|

######返回JSON数据格式
 ````
 {
       agent_id ：机构编号
       $nurse_count ：员工数量
       $book_count ：累计交易
       $question_count ：待回答问题数量
       agent_focusnum ：被关注数量
       agent_viewnum ：浏览数
       agent_logo ：机构LOGO
       $book_list ：订单列表 {
            book_id ：订单ID
            book_sn ：订单编号
            add_time ：订单时间
            $nurse_list[$value['nurse_id']]['nurse_name'] ：家政人员姓名
            $nurse_list[$value['nurse_id']]['member_phone'] ：雇主电话
            book_amount ：订单金额
            refund_amount ：退款金额
            refund_reason ：退款原因                      
       }
       $multi ：页码模块
 }
 ````
 
#### 营销管理   `index.php?act=agent_marketing`  
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |

######返回JSON数据格式
 ````
 {
       agent_id ：机构编号
      $nurse_count ：员工数量
      $book_count ：累计交易
      $question_count ：待回答问题数量
      agent_focusnum ：被关注数量
      agent_viewnum ：浏览数
      agent_logo ：机构LOGO
 }
 ````
 
####  保证金 提交缴纳金额 `index.php?act=agent_marketing&op=order`   POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|deposit_amount|int|保证金金额|

#####返回JSON数据格式
 ````
 {
      done ：成功 book_sn ：订单编号
      id ：system  msg ：错误抛出msg
 }
 ````
 
#### 保证金 保证金选择方式 （页面） `index.php?act=agent_marketing&op=payment` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_sn|string|订单编号|


#####返回JSON数据格式
 ````
 {
      book_sn ：订单编号
      book_amount ：订单金额
      add_time ：时间
 }
 ````
 
#### 保证金 保证金选择支付方式 （提交） `index.php?act=agent_marketing&op=payment`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_sn|string|订单编号|
|payment_code|string|支付方式|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
#### 保证金 保证金扫码支付界面
> - -

#### 机构管理中心/财务中心界面 `index.php?act=agent_profit`     GET 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|state|string|income：收入 expend：支出 freeze：冻结|

######返回JSON数据格式
 ````
 {
       agent_id ：机构编号
      $nurse_count ：员工数量
      $book_count ：累计交易
      $question_count ：待回答问题数量
      agent_focusnum ：被关注数量
      agent_viewnum ：浏览数
      agent_logo ：机构LOGO
       $this->agent['plat_amount'] ：总收益
       $this->agent['plat_refund'] ：退款
       $this->agent['available_amount'] ：可用金额
       $this->agent['pool_amount'] ：冻结金额
       $profit_list ：财务明细列表 {
            add_time ：时间
            $nurse_list[$value['nurse_id']]['nurse_name'] ：家政人员姓名
            markclass ：颜色值class
            mark ：+ - 符号
            profit_amount ：金额
            is_freeze ：是否冻结 0：解冻 1：冻结
            profit_desc ：备注
       }
       $multi ：页码模块
 }
 ````
 
#### 机构管理中心/提现 提交 `index.php?act=agent_cash`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
|pdc_amount|int|提现金额|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
#### 机构管理中心/提现 选择提现方式 `index.php?act=agent_cash&op=step2`   POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
|pdc_code|string|提现方式|
|alipay_card|string|支付宝账号|
|weixin_card|string|微信账号|
|bank_membername|string|持卡人姓名|
|bank_deposit|string|开户行|
|bank_card|string|银行卡号|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
#### 机构管理中心/发票管理 `index.php?act=agent_invoice`      GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|state|string|person：个人 unit ：单位|
|search_name|string|搜索框内容|

######返回JSON数据格式
 ````
 {
       agent_id ：机构编号
     $nurse_count ：员工数量
     $book_count ：累计交易
     $question_count ：待回答问题数量
     agent_focusnum ：被关注数量
     agent_viewnum ：浏览数
     agent_logo ：机构LOGO
     $book_list ：订单列表 {
            book_id ：订单ID
           $invoice_list[$value['invoice_id']]['invoice_type'] ：发票种类
           $invoice_list[$value['invoice_id']]['invoice_title'] ：发票抬头
           $invoice_list[$value['invoice_id']]['invoice_content'] ：发票明细
           $invoice_list[$value['invoice_id']]['invoice_membername'] ：收件人
           $invoice_list[$value['invoice_id']]['invoice_areainfo'] ：收件地址
           $invoice_list[$value['invoice_id']]['invoice_address'] ：详细地址
           member_phone ：收件人号码
           $invoice_list[$value['invoice_id']]['unit_name'] ：单位名称
           $invoice_list[$value['invoice_id']]['invoice_code'] ：纳税人识别码
           $invoice_list[$value['invoice_id']]['invoice_unit_membername'] ：收件人
           
     }
     $multi ：页码模块
 }
 ````

#### 机构管理中心/发票管理  查看发票详情 `index.php?act=agent_invoice&op=invoice_show`  GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_id|int|订单ID|

######返回JSON数据格式
 ````
 {
       book_type ：订单种类
       book_sn ：订单编号
       payment_time ：付款时间 
       invoice_title ：发票抬头
       invoice_content ：发票明细
       invoice_membername ：收件人
       member_phone ：联系号码
       invoice_areainfo ：发票地址
       invoice_address ：详细地址
       unit_name ：单位名称
       invoice_code ; 纳税人识别码
       invoice_unit_membername ：单位收件人
 }
 ````
 
#### 机构管理中心/发票管理 设为已开 `index.php?act=agent_invoice&op=invoice_ok`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_id|int|订单编号|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
#### 机构管理中心/发票管理 设为未开 `index.php?act=agent_invoice&op=invoice_no`   GET 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_id|int|订单编号|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
#### 家政人员简历页面 `index.php?act=nurse`     GET 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|nurse_id|int|家政人员ID|

######返回JSON数据格式
 ````
 {
       agent_id ：机构ID
       agent_name ：机构名称
       $grade['grade_icon'] ：家政人员等级图标
       $agent_grade['grade_icon'] ：机构等级图标
       $nurse['nurse_favoritenum'] ：家政人员被关注人数
       $agent['agent_focusnum'] ：机构被关注人数
       $agent['agent_id'] ：机构号
       nurse_image ：家政人员头像
       nurse_type ：家政人员类别
       nurse_price ：家政人员薪资
       nurse_nickname ：家政人员称呼
       nurse_age ：家政人员年龄
       birth_cityname ：家政人员籍贯
       service_price ：家政人员服务费
       nurse_discount ：家政人员折扣
       $success_count ：交易成功次数
       $count ：累计评论次数
       promise_state ：家政人员承诺
       $agent['agent_score'] ：机构积分
       nurse_sex ：家政人员性别  （0：保密 1：男 2：女）
       nurse_work_exe ：工作经历
       nurse_content ：自我评价
       nurse_qa_image ：其他资质
       
       $count ：全部评价数量
       $good_count ：好评数量
       $middle_count ：中评数量
       $bad_count ：差评数量
       $hasImg_count ：有图评价数量
       $comment_list ：评价列表 {
            comment_conten ：评价内容
            comment_image ：评价配图
            $member_list[$value['member_id']]['member_phone'] ：评价人账号 （已处理）
            'comment_time ：评价时间
       }
       $multi ：页码模块
 }
 ````
 
#### 家政人员简历页面 累计评价/筛选、翻页 `index.php?act=nurse&op=comment`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码数|
|nurse_id|int|家政人员ID|
|field_value|string|all：全部 good：好评 middle：中评 bad：差评 hasimg：有图|
|content|string|是否有内容|
|value|string|time：按时间排序 score：按好评排序|

######返回JSON数据格式
 ````
 {
       comment_content ：评价内容
       comment_image ：评价图片
       $member_list[$value['member_id']]['member_phone'] ：评价人账号（已处理）
       $book_list[$value['book_id']]['work_duration'] ：服务时长 月
       $book_list[$value['book_id']]['work_duration_days'] ：服务时长 天
       $book_list[$value['book_id']]['work_duration_hours'] ：服务时长 小时
       $book_list[$value['book_id']]['work_duration_mins'] ：服务时长 分钟
       comment_time ：评价时间
       $multi ：代码模块
 }
 ````
 
#### 家政人员简历页面 收藏家政人员 `index.php?act=nurse&op=collect`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_id|int|家政人员ID|
|agent_id|int|机构ID|

######返回JSON数据格式
 ````
 {
       done ：成功
       msg ：错误抛出msg
 }
 ````
 
 ####  家政人员简历页面  关注家政人员 `index.php?act=nurse&op=favourite`  GET
 | 字段名称 | 字段类型  | 备注 |
 | -------- | -------- | -------- |
 |fav_id|int|家政人员ID|
 
######返回JSON数据格式
````
{
    done ：成功
    msg ：错误抛出msg
}
````
  
#### 家政人员简历页面 关注机构 `index.php?act=nurse&op=focus`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
 
######返回JSON数据格式
````
{
     done ：成功
     msg ：错误抛出msg
}
````

#### 机构首页 `index.php?act=agent_show`    GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|

######返回JSON数据格式
````
{
     agent_name ：机构名称
     $agent_grade['grade_icon'] ：机构等级图标
     agent_focusnum ：机构关注人数
     agent_id ：机构ID
     owner_name ：法人姓名
     agent_phone ：客服电话
     agent_address ：机构地址
     agent_summary ：机构概述
     agent_content ：机构服务
     agent_service_image ：机构服务图片
     agent_code_image ：机构资质
     agent_person_image ：机构资质
     agent_person_code_image ：机构资质
     $nurse_list ：机构推荐 {
           nurse_image ：家政人员头像
           nurse_id ：家政人员ID
           nurse_nickname ：家政人员称呼
           service_type ：家政人员服务类别
           nurse_special_service ：特色标签
           nurse_price ：家政人员薪资
           $grade_list[$value['grade_id']]['grade_icon'] ：家政人员等级图标          
     }
     $nurse_good_list ：好评榜 {
        nurse_image ：家政人员头像
       nurse_id ：家政人员ID
       nurse_nickname ：家政人员称呼
       service_type ：家政人员服务类别
       nurse_special_service ：特色标签
       nurse_price ：家政人员薪资
       $grade_list[$value['grade_id']]['grade_icon'] ：家政人员等级图标
     }
}
````

#### 机构展示/全部员工页 `index.php?act=agent_staff`    GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
|keyword|string|关键字|

######返回JSON数据格式
````
{
      agent_name ：机构名称
      $agent_grade['grade_icon'] ：机构等级图标
      agent_focusnum ：机构关注人数
      $nurse_list ：员工列表 {
        nurse_id ：家政人员ID
        nurse_image ：家政人员头像
        nurse_type ：家政人员类别
        nurse_price ：薪资
        nurse_nickname ：家政人员称呼
        service_type ：家政人员服务类别
        nurse_special_service ：特色需求
        $grade_list[$value['grade_id']]['grade_icon'] ：家政人员等级图标
        nurse_commentnum ：被评价数量
        nurse_salenum ：付款人数数量
        agent_id ：机构ID
        $agent_list[$value['agent_id'] ：机构名称
        
      }
      $multi ：页码模块
}
````

#### 机构展示/全部员工页 家政人员筛选 `index.php?act=agent_staff&op=nurse_search`  GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
|keyword|string|搜索关键字|
|service_type|string|服务类别|
|nurse_type|int|家政人员类别|
|nurse_price|int|家政人员薪资|
|grade_id|int|家政人员薪资|
|salenum_sort|string|交易量排序|
|commentnum_sort|string|好评度排序|
|price_sort|string|价格排序|
|favoritenum_sort|string|收藏排序|


######返回JSON数据格式
````
{
    nurse_html ：家政人员html
    nurse_multi_html ：页码模块html
}
````

#### 机构展示/机构问答 `index.php?act=agent_answers`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|

######返回JSON数据格式
````
{
    $agent_grade['grade_icon'] ：机构等级图标
    agent_focusnum ：关注人数
    agent_name ：机构名称
    agent_id ：机构ID
    $nurse_count ：全部员工数量
    $question_list ：问答列表 {
        $member_list[$value['member_id']]['member_image'] ：用户头像
        $member_list[$value['member_id']]['member_name'] ：用户账号 （已处理）
        question_time ：提问时间
        focus_count ：查看数量
        question_content ：问题内容
        answer_content ：回答内容
        
    }
    $multi ：页码模块
}
````

#### 机构展示/机构问答 提问 `index.php?act=agent_answers&op=get_question` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|agent_id|int|机构ID|
|question_content|string|问题内容|

######返回JSON数据格式
````
{
     done ：成功
     msg ：错误抛出msg
}
````

#### 机构展示/机构问答 问题排序 `index.php?act=agent_answers&op=search_question`    GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|page|int|页码|
|agent_id|int|机构ID|
|sort|string|time：默认 focus：热门|

######返回JSON数据格式
````
{
     question_html ：问答html
     question_multi_html ：页码模块html
}
````

#### 家政人员页面/立即预约    `index.php?act=nurse&op=collect_add`    POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_id|int|家政人员ID|
|agent_id|int|机构ID|
|nurse_type|int|家政人员类别|
|work_duration|int|工作时长 月|
|work_duration_days|int|工作时长 天|
|work_duration_hours|int|工作时长 小时|
|work_duration_mins|int|工作时长 分钟|
|work_area|int|工作面积|
|work_person|int|工作人数|
|work_machine|int|几次数量|
|work_cars| |车吨位|
|car_price|int|车价格|
|work_students|int|学生数量|
|service_price|int|服务费|
|nurse_price| |薪资|
|total_price| |总价|
|nurse_discount|0.00|折扣|
|collect_details|string|详情|

######返回JSON数据
````
{
      done ：成功，collect_id ：收藏ID
      msg ：错误抛出msg
}
````

#### 提交订单页面/修改地址 `index.php?act=place_order&op=order_address_resume`   POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_address_id|int|地址ID|
|member_provinceid|int|省份 ID|
|member_cityid|int|市 ID|
|member_areaid|int|县/区 ID|
|address_content|string|地址详情|
|address_member_name|string|姓名|
|address_phone|string|号码|

######返回JSON数据
````
{
      done ：成功，collect_id ：收藏ID
      msg ：错误抛出msg
}
````

#### 提交订单页面/新增地址 `index.php?act=place_order&op=address_add`     POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_provinceid|int|省 ID|
|member_cityid|int|市 ID|
|member_areaid|int|县/区 ID|
|address_content|string|详细地址|
|address_member_name|string|姓名|
|address_phone|string|号码|
|member_selected|int|是否设为默认地址|

######返回JSON数据
````
{
      done ：成功，collect_id ：收藏ID
      msg ：错误抛出msg
}
````

#### 提交订单页面/提交订单 开据发票 `index.php?act=place_order&op=order`  POST
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|collect_id|int|收藏ID|
|nurse_id|int|家政人员ID|
|agent_id|int|机构ID|
|nurse_type|int|家政人员类别|
|book_phone|string|家政人员号码|
|book_details|string|订单详情|
|work_duration|int|工作时长 月|
|work_duration_days|int|工作时长 天|
|work_duration_hours|int|工作时长 小时|
|work_duration_mins|int|工作时长 分钟|
|work_area|int|工作面积|
|work_person|int|工作人数|
|work_machine|int|机器数量|
|work_cars|int|车 吨位|
|car_price|int|车价格|
|work_students|int|学生数量|
|service_price| |服务费|
|nurse_price| |薪资|
|book_address|string|订单地址|
|service_member_phone|string|联系人号码|
|service_member_name|string|联系姓名|
|book_message|string|预留信息|
|deposit_amount|int|订单金额|
|member_coin_amount|int|使用团豆豆数量|
|book_amount|int|订单实付款|
|invoice_state|int|发票状态|
|invoice_type|string|发票种类|
|invoice_title|string|发票抬头|
|invoice_content|string|发票明细|
|invoice_membername|string|姓名|
|invoice_provinceid|int|省 ID|
|invoice_cityid|int|市 ID|
|invoice_areaid|int|县/区 ID|
|invoice_address|string|发票地址|
|unit_name|string|单位名称|
|invoice_code|string|纳税人识别码|
|invoice_unit_membername|string|单位收件人姓名|

######返回JSON数据
````
{
      done ：成功，book_sn ：订单编号
      msg ：错误抛出msg
}
````

#### 提交订单页面/选择支付方式 `index.php?act=book&op=payment` GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|book_sn|string|订单编号|

######返回JSON数据
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 提交订单页面/扫码支付
> - -

#### 外接聊天系统/跟别人聊天 
> - -

#### 外接聊天系统/进入我的聊天
> - -

#### 文章/关于我们 `index.php?act=article`  GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|article_id|int|文章ID  1|

######返回JSON数据
````
{
      $article_list ：文章列表 {
            article_id ：文章ID
      }
      article_title ：文章标题
      article_content ：文章内容
}
````

#### 文章/使用声明 `index.php?act=article`  GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|article_id|int|文章ID  3|

######返回JSON数据
````
{
      $article_list ：文章列表 {
            article_id ：文章ID
      }
      article_title ：文章标题
      article_content ：文章内容
}
````

#### 文章/入驻声明 `index.php?act=article`  GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|article_id|int|文章ID  4|

######返回JSON数据
````
{
      $article_list ：文章列表 {
            article_id ：文章ID
      }
      article_title ：文章标题
      article_content ：文章内容
}
````

#### 家政人员信用等级界面 `index.php?act=nurse_trust_grade`   GET
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|nurse_id|int|家政人员ID|
|state|string|show：全部 one_mouth：最近1月 six_mouth：最近半年 one_year：最近1年 one_year_ago：1年以前|

######返回JSON数据
````
{
    $nurse_cityname ：所在地区
    $nurse_grade['grade_icon']  ：信用等级
    $nurse['nurse_score'] ：信用积分
    $nurse['promise_state'] ：家政人员承诺状态
    $nurse['nurse_bail'] ：保证金金额
    $nurse['nurse_nickname'] ：家政人员称呼
    $good_count_chance ：好评率
    $good_count ：好评数量
    $middle_count ：中评数量
    $bad_count ：差评数量
    $refund_count ：退款数量
}
````

#### 雇主信用等级页面 `index.php?act=member_trust_grade`    GET 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|member_id|int|雇主|
|state|string|show：全部 one_mouth：最近1月 six_mouth：最近半年 one_year：最近1年 one_year_ago：1年以前|

######返回JSON数据
````
{
    $member['member_nickname'] ：雇主昵称
    $member_cityname ：所在地区
    $card['card_icon'] ：信用等级
    $member['member_score'] ：信用积分
    $good_count_chance ：好评率
    $good_count ：好评数量
    $middle_count ：中评数量
    $bad_count ：差评数量
    $refund_count ：退款数量
}
````

#### 意见与反馈 页面 `index.php?act=user_feed_back` 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
无

######返回JSON数据
````
无
````

#### 意见与反馈 提交 `index.php?act=user_feed_back&op=suggest`  POST 
| 字段名称 | 字段类型  | 备注 |
| -------- | -------- | -------- |
|suggest_type|int|建议种类|
|suggest_content|string|建议内容|
|suggest_image|string|建议配图|

######返回JSON数据
````
{
      done ：成功
      msg ：错误抛出msg
}
````

#### 我的足迹页面 `` 
> - -