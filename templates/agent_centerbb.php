<?php include(template('common_header'));?>
	<div class="conwp">
		<div class="user-main">
			<div class="user-left">
				<div class="user-nav">
					<h1><a href="javascript:;"><i class="iconfont icon-user"></i>家政机构</a></h1>
					<h3 class="no1">订单中心</h3>
					<ul>
						<li><a href="index.php?act=agent_book">订单管理</a></li>
					</ul>
                    <h3 class="no2">资产中心</h3>
					<ul>
						<li><a href="index.php?act=agent_profit">资金收益</a></li>
					</ul>
					<h3 class="no3">家政人员中心</h3>
					<ul>
						<li><a href="index.php?act=agent_nurse">家政人员管理</a></li>
					</ul>
					<h3 class="no4">机构设置</h3>
					<ul>
						<li><a href="index.php?act=agent_profile">机构信息</a></li>
					</ul>
				</div>
			</div>
			<div class="user-right">
				<div class="user-head clearfix">
					<div class="user-info-l">
						<div class="imgHeaderBox">
                        	<a href="javascript:;" class="headerImg">
                            	<?php if(empty($this->agent['agent_logo'])) { ?>
                                <img src="templates/images/peopleicon_01.gif">
                                <?php } else { ?>
                                <img src="<?php echo $this->agent['agent_logo'];?>">
                                <?php } ?>
                            </a>
							<div class="updateInfo">
							   <div class="opacityBox"></div>
							   <a href="index.php?act=agent_profile" class="realBox">修改资料</a>
							</div>
						</div>
						<p class="name"><a href="javascript:;"><?php echo $this->agent['agent_name'];?></a></p>
						<p class="VIP">
							<a class="txtExplain"><?php echo $agent['agent_phone'];?></a>
						</p>
						<p class="cert-status">
							<span><i class="iconfont icon-certphone"></i>手机认证</span>
							<span><i class="iconfont icon-cert"></i><a href="index.php?act=agent_profile">实名认证</a></span>
						</p>
					</div>
					<div class="user-info-r">
						<div class="order-num-info">
							<h1>订单</h1>
							<ul>
								<li>
									<a href="index.php?act=agent_book&state=pending">待付款订单<strong><?php echo $pending_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=agent_book&state=payment">已付款订单<strong><?php echo $payment_count;?></strong></a>
								</li>
                                <li>
									<a href="index.php?act=agent_book&state=finish">已完成订单<strong><?php echo $finish_count;?></strong></a>
								</li>
								<li>
									<a href="index.php?act=agent_book&state=cancel">已取消订单<strong><?php echo $cancel_count;?></strong></a>
								</li>
							</ul>
						</div>
						<div class="amount-sum">
                        	<h1>总收益</h1>
							<p>
								<strong class="amount-num"><?php echo priceformat($this->agent['plat_amount']);?></strong>
								<a href="index.php?act=agent_profit" class="btn btn-primary">我的收益 </a>
							</p>
							<p class="other-amount">
								<span>可现提金额<a href="index.php?act=agent_profit"><?php echo priceformat($this->agent['available_amount']);?></a></span>
								<span>冻结金额<a href="index.php?act=agent_profit"><?php echo priceformat($this->agent['pool_amount']);?></a></span>
							</p>
						</div>
					</div>
				</div>
				<div class="user-module">
					<h1><strong>家政人员预约订单</strong><a href="index.php?act=agent_book">更多>></a></h1>
					<div class="user-module-body">
                    	<?php if(empty($book_list)) { ?>
                        	<div class="empty-box">
                                <i class="iconfont icon-order"></i>
                                你还没有预约订单
                        	</div>
                        <?php } else { ?>
                        <table class="simple-order">
                            <tbody>
                            	<?php foreach($book_list as $key => $value) { ?>
                                <tr>
                                    <td width="120">
                                        <div class="simple-img only-img">
                                            <a href="index.php?act=nurse&nurse_id=<?php echo $value['nurse_id'];?>" target="_blank"><img src="<?php echo $nurse_list[$value['nurse_id']]['nurse_image'];?>"></a>
                                        </div>
                                    </td>
                                    <td><?php echo $nurse_list[$value['nurse_id']]['nurse_name'];?></td>
                                    <td><?php echo date('Y-m-d H:i', $value['work_time']);?></td>
									<td><?php echo $value['work_duration'];?> 个月</td>
                                    <td>￥<?php echo $value['book_amount'];?></td>
									<td>
                                    	<?php if(empty($value['book_state'])) { ?>
                                            <?php if(empty($value['refund_state'])) { ?>
												已取消
											<?php } else { ?>
												已退款
											<?php } ?>
                                        <?php } elseif($value['book_state'] == 10) { ?>
                                            待付款
                                        <?php } elseif($value['book_state'] == 20) { ?>
                                            <?php if(empty($value['refund_state'])) { ?>
												已付款
											<?php } elseif($value['refund_state'] == 1) { ?>
												待退款
                                            <?php } else { ?>
                                                已拒绝
											<?php } ?>
                                        <?php } elseif($value['book_state'] == 30) { ?>
                                            <?php if(empty($value['comment_state'])) { ?>
												待评价
											<?php } else { ?>
												已评价
											<?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td><a href="index.php?act=agent_book">查看</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
				</div>
			</div>
		</div>
	</div>
    <style>
        #orderBox{
            height:200px;
            width:300px;
            position: fixed;
            top:10px;
            left:40%;
            background:#ffe600 ;
            border:1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            display: none;
        }
        #orderBox p{text-align: center;height:40px;line-height: 40px;background: #fff;font-size: 20px;margin-top: 0;}
        .boxBtn{margin-top:100px;text-align: center;}
        .boxBtn span{margin:0 10px;cursor: pointer;}
        .boxBtn span:nth-child(1){background: #fff;padding: 5px 10px;border-radius: 5px;}
        .boxBtn span:nth-child(1):hover{color:#f66;}
        .boxBtn span:nth-child(2){background: #f66;padding: 5px 10px;border-radius: 5px;}
        .boxBtn span:nth-child(2) a:hover{color:#fff;}
    </style>
    <div id="orderBox">
        <p>您有新的订单</p>
        <audio id="audio1">

            <source src="templates/images/comeOn.wav" type="audio/wav">

        </audio>
        <div class="boxBtn">
            <span>我知道了</span><span><a href="index.php?act=agent_book">进入订单中心</a></span>
        </div>
    </div>
    <script>
        $(function () {
            var row=[];
            setInterval(function () {
                $.getJSON('index.php?act=agent_centerbb&op=get_count',function (data) {
                    row.push(parseInt(data.count));
                    if(row.length>1) {
                        if (parseInt(row[row.length-1]) > parseInt(row[row.length - 2])) {
                            $("#orderBox").show();
                            myAudio.play();
                        }

                    }
                })
            },2000000000000)

        })
        var myAudio=document.getElementById("audio1");
        function playVid()
        {
            myAudio.play();
        }
        function pauseVid()
        {
            myAudio.pause();
        }
        $(".boxBtn span").click(function () {
            $("#orderBox").hide();
            pauseVid();
        });
    </script>
<?php include(template('common_footer'));?>