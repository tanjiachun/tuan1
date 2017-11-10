<?php include(template('common_header'));?>
	<div class="wrap">
        <div class="left_menu" id="menu_wrap">
            <ul>
                <li>
                    <a class="active" href="javascript:;">全局<span></span></a>
                    <dl style="display:block">
                        <?php if(in_array('index', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=index">站点信息</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('recommend', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a class="active" href="admin.php?act=recommend">网站推荐</a>
                        </dd>
						<?php } ?>
                        <?php if(in_array('type', $this->admin['admin_permission'])) { ?>
                            <dd>
                                <a href="admin.php?act=type">服务类别</a>
                            </dd>
                        <?php } ?>
						<?php if(in_array('article', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=article">文章公告</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('link', $this->admin['admin_permission'])) { ?>
                        <dd>
                            <a href="admin.php?act=link">友情链接</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('related', $this->admin['admin_permission'])) { ?>
						<dd>
                            <a href="admin.php?act=related">相关认证</a>
                        </dd>
						<?php } ?>
						<?php if(in_array('app', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=app">APP设置</a>
						</dd>
						<?php } ?>
						<?php if(in_array('admin', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=admin">管理员</a>
						</dd>
						<?php } ?>
						<?php if(in_array('log', $this->admin['admin_permission'])) { ?>
						<dd>
							<a href="admin.php?act=log">操作记录</a>
						</dd>
						<?php } ?>
                    </dl>
                </li>
            </ul>
        </div>
        <div id="main" class="main no-tab">
			<div class="page_filter">
            	<label class="frm_checkbox_label"><strong>网站推荐</strong></label>
            </div>
        	<div class="goods_content">
                <table class="goods_table">
                    <thead>
                    	<th style="width:16px;"></th>
                        <th>栏目</th>
                        <th class="th_opr">操作</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>住家保姆</td>
                            <td class="td_opr"><a href="admin.php?act=recommend&op=nurse&type=inside">添加推荐</a></td>
                        </tr>
						<tr>
                            <td></td>
                            <td>不住家保姆</td>
                            <td class="td_opr"><a href="admin.php?act=recommend&op=nurse&type=outside">添加推荐</a></td>
                        </tr>
						<tr>
                            <td></td>
                            <td>病后照护</td>
                            <td class="td_opr"><a href="admin.php?act=recommend&op=nurse&type=illness">添加推荐</a></td>
                        </tr>
						<tr>
                            <td></td>
                            <td>钟点工</td>
                            <td class="td_opr"><a href="admin.php?act=recommend&op=nurse&type=hour">添加推荐</a></td>
                        </tr>
						<tr>
                            <td></td>
                            <td>老年用品</td>
                            <td class="td_opr"><a href="admin.php?act=recommend&op=goods">添加推荐</a></td>
                        </tr>
						<tr>
                            <td></td>
                            <td>养老机构</td>
                            <td class="td_opr"><a href="admin.php?act=recommend&op=pension">添加推荐</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include(template('common_footer'));?>