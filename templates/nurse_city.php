<?php include(template('common_header'));?>
    <div class="conwp clearfix">
        <h1 class="top-logo">
            <a href="index.php"><img src="templates/images/logo.png"></a>
            <strong>城市选择</strong>
        </h1>
    </div>
    </div>
    <div class="content">
        <div class="conwp">
            <div class="city-head">
                <div class="enterIPcity">
                    <a href="index.php?act=nurse_city&op=select&district_id=<?php echo $this->district['district_id'];?>" class="entrance"><b>进入 <?php echo $this->district['district_ipname'];?> 站</b></a>
                </div>
                <?php if(!empty($near_list)) { ?>
                    <div class="around">
                        <span class="gray">周边城市推荐：</span>
                        <?php foreach($near_list as $key => $value) { ?>
                            <a href="index.php?act=nurse_city&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="clist">
                <dl class="clist-line"></dl>
                <dl>
                    <b>搜索热门城市：</b>
                    <input type="text" class="cityinput " id="district_name" placeholder="请输入目的地">
                    <div class="popular">
                        <b>热门</b>
                        <a href="index.php?act=nurse_city&op=select&district_id=1">北京</a>
                        <a href="index.php?act=nurse_city&op=select&district_id=9">上海</a>
                        <a href="index.php?act=nurse_city&op=select&district_id=2">天津</a>
                        <a href="index.php?act=nurse_city&op=select&district_id=22">重庆</a>
                        <a href="index.php?act=nurse_city&op=select&district_id=32">台湾</a>
                        <a href="index.php?act=nurse_city&op=select&district_id=33">香港</a>
                        <a href="index.php?act=nurse_city&op=select&district_id=34">澳门</a>
                    </div>
                </dl>
                <dl class="clist-line"><span>华东</span></dl>
                <dl class="clearfix">
                    <?php foreach($area_list[0] as $province_id) { ?>
                        <dt>
                            <b><?php echo $province_list[$province_id]['district_ipname'];?></b>
                        </dt>
                        <dd>
                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                <a href="index.php?act=nurse_city&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                            <?php } ?>
                        </dd>
                    <?php } ?>
                </dl>
                <dl class="clist-line"><span>华南</span></dl>
                <dl class="clearfix">
                    <?php foreach($area_list[1] as $province_id) { ?>
                        <dt>
                            <b><?php echo $province_list[$province_id]['district_ipname'];?></b>
                        </dt>
                        <dd>
                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                <a href="index.php?act=nursecity&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                            <?php } ?>
                        </dd>
                    <?php } ?>
                </dl>
                <dl class="clist-line"><span>华中</span></dl>
                <dl class="clearfix">
                    <?php foreach($area_list[2] as $province_id) { ?>
                        <dt>
                            <b><?php echo $province_list[$province_id]['district_ipname'];?></b>
                        </dt>
                        <dd>
                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                <a href="index.php?act=nursecity&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                            <?php } ?>
                        </dd>
                    <?php } ?>
                </dl>
                <dl class="clist-line"><span>东北</span></dl>
                <dl class="clearfix">
                    <?php foreach($area_list[3] as $province_id) { ?>
                        <dt>
                            <b><?php echo $province_list[$province_id]['district_ipname'];?></b>
                        </dt>
                        <dd>
                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                <a href="index.php?act=nurse_city&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                            <?php } ?>
                        </dd>
                    <?php } ?>
                </dl>
                <dl class="clist-line"><span>西南</span></dl>
                <dl class="clearfix">
                    <?php foreach($area_list[4] as $province_id) { ?>
                        <dt>
                            <b><?php echo $province_list[$province_id]['district_ipname'];?></b>
                        </dt>
                        <dd>
                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                <a href="index.php?act=nurse_city&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                            <?php } ?>
                        </dd>
                    <?php } ?>
                </dl>
                <dl class="clist-line"><span>华北</span></dl>
                <dl class="clearfix">
                    <?php foreach($area_list[5] as $province_id) { ?>
                        <dt>
                            <b><?php echo $province_list[$province_id]['district_ipname'];?></b>
                        </dt>
                        <dd>
                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                <a href="index.php?act=nurse_city&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                            <?php } ?>
                        </dd>
                    <?php } ?>
                </dl>
                <dl class="clist-line"><span>西北</span></dl>
                <dl class="clearfix">
                    <?php foreach($area_list[6] as $province_id) { ?>
                        <dt>
                            <b><?php echo $province_list[$province_id]['district_ipname'];?></b>
                        </dt>
                        <dd>
                            <?php foreach($city_list[$province_id] as $key => $value) { ?>
                                <a href="index.php?act=nurse_city&op=select&district_id=<?php echo $value['district_id'];?>"><?php echo $value['district_ipname'];?></a>
                            <?php } ?>
                        </dd>
                    <?php } ?>
                </dl>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="templates/js/cityselect.js"></script>
    <script type="text/javascript">
        var city = new Vcity.CitySelector({
            input : 'district_name',
            func : function () {
                var district_name = $('#district_name').val();
                $.getJSON('index.php?act=nurse_city&op=checkname', {'district_name' : district_name}, function(data){
                    if(data.done == 'true') {
                        window.location.href = 'index.php?act=nurse_city&op=select&district_id='+data.district_id;
                    } else {
                        showalert(data.msg);
                    }
                });
            },
        });
    </script>
<?php include(template('common_footer'));?>