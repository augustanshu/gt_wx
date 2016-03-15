<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-base', TEMPLATE_INCLUDEPATH)) : (include template('common/header-base', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .app,.app body {
	width:100%;
	height:100%;
	overflow:auto;
}
</style>
<div class="gw-container">
	<?php  if(!defined('NO_HEADER')) { ?>
	<div class="navbar navbar-inverse navbar-static-top  bg-dark " role="navigation" style="z-index:1001; margin-bottom:0;position: absolute;top:0;width:100%;">
		<div class="container-fluid">
		
			<ul class="nav navbar-nav">
				 <!--
                            <li >
                                <?php $logo = !empty($_W['setting']['copyright']['blogo'])?tomedia($_W['setting']['copyright']['blogo']):'./themes/hc_style1/style/images/gw-logo.png'?>
                                <a  href="./?refresh" style='padding:3px 0 5px 0'><img src='<?php  echo $logo;?>' style='height:40px;' /></a>
                            </li>
							-->
                                 
				<?php  if(!empty($_W['uniacid'])) { ?>
                                <li><a href="<?php  echo url('home/welcome/platform');?>" target="_blank"><i class="fa fa-share"></i>继续管理公众号<!--（<?php  echo $_W['account']['name'];?>）--></a></li>
                                <?php  } ?>
                                
                                    <li class="<?php  if($controller == 'account') { ?> active<?php  } ?>"><a href="<?php  echo url('account/display');?>">
					<i class="fa fa-cogs"></i>
					<span>公众号管理</span>
				</a></li>
                                <!--
								<li class="<?php  if($controller == 'system') { ?> active<?php  } ?>"><a href="<?php  echo url('system/welcome');?>" >
					<i class="fa fa-sitemap"></i>
					<span>系统管理</span>
				</a></li>
								<li><a href="http://www.jianghugei.com" target="_blank" >
					<i class="fa fa-sitemap"></i>
					<span>源码下载</span>
				</a></li>
                        -->        
				<?php  if($_W['isfounder']) { ?><?php  } ?>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
                            <?php  if(!empty($_W['uniacid'])) { ?>
				<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-group"></i> <b class="caret"></b></a>
				<!--
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"  style="display:block; width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><i class="fa fa-group"></i> <?php  echo $_W['account']['name'];?> <b class="caret"></b></a>
					-->
					<ul class="dropdown-menu">
						<?php  if($_W['role'] != 'operator') { ?>
						<li><a href="<?php  echo url('account/post', array('uniacid' => $_W['uniacid']));?>" target="_blank"><i class="fa fa-weixin fa-fw"></i> 编辑当前账号资料</a></li>
						<?php  } ?>
						<li><a href="<?php  echo url('account/display');?>" target="_blank"target="_blank"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
						<li><a href="<?php  echo url('utility/emulator');?>" target="_blank"><i class="fa fa-mobile fa-fw"></i> 模拟测试</a></li>
					</ul>
				</li>
                                <?php  } ?>
				<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <b class="caret"></b></a>
					<!--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php  echo $_W['user']['username'];?> (<?php  if($_W['role'] == 'founder') { ?>系统管理员<?php  } else if($_W['role'] == 'manager') { ?>公众号管理员<?php  } else { ?>公众号操作员<?php  } ?>) <b class="caret"></b></a>
					-->
					<ul class="dropdown-menu">
						<li><a href="<?php  echo url('user/profile/profile');?>" target="_blank"><i class="fa fa-weixin fa-fw"></i> 我的账号</a></li>
						<?php  if($_W['role'] != 'operator') { ?>
						<li class="divider"></li>
						<li><a href="<?php  echo url('system/welcome');?>" target="_blank"><i class="fa fa-sitemap fa-fw"></i> 系统管理</a></li>
						<li><a href="<?php  echo url('system/updatecache');?>" target="_blank"><i class="fa fa-refresh fa-fw"></i> 更新缓存</a></li>
						<li class="divider"></li>
						<?php  } ?>
						<li><a href="<?php  echo url('user/logout');?>"><i class="fa fa-sign-out fa-fw"></i> 退出系统</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
 
	 <?php  } ?>
	 
	<div class="container-fluid">
		<?php  if(defined('IN_MESSAGE')) { ?>
		<div  style='margin-top:60px;'>
			<div class="jumbotron clearfix alert alert-<?php  echo $label;?>">
				<div class="row">
					<div class="col-sm-4 col-md-3 col-lg-2" style='text-align:right'>
						<i style="font-size:10em" class="fa fa-5x fa-<?php  if($label=='success') { ?>check-circle<?php  } ?><?php  if($label=='danger') { ?>times-circle<?php  } ?><?php  if($label=='info') { ?>info-circle<?php  } ?><?php  if($label=='warning') { ?>exclamation-triangle<?php  } ?>"></i>
					</div>
					<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
						<?php  if(is_array($msg)) { ?>
							<h2>MYSQL 错误：</h2>
							<p><?php  echo cutstr($msg['sql'], 300, 1);?></p>
							<p><b><?php  echo $msg['error']['0'];?> <?php  echo $msg['error']['1'];?>：</b><?php  echo $msg['error']['2'];?></p>
						<?php  } else { ?>
						<h2><?php  echo $caption;?></h2>
						<p><?php  echo $msg;?></p>
						<?php  } ?>
						<?php  if($redirect) { ?>
						<p><a href="<?php  echo $redirect;?>" class="alert-link">如果你的浏览器没有自动跳转，请点击此链接</a></p>
						<script type="text/javascript">
							setTimeout(function () {
								location.href = "<?php  echo $redirect;?>";
							}, 2000);
						</script>
						<?php  } else { ?>
						<p>[<a href="javascript:history.go(-1);" class="alert-link">点击这里返回上一页</a>] &nbsp; [<a href="./?refresh" class="alert-link">首页</a>]</p>
						<?php  } ?>
					</div>
				</div>
			</div>
		<?php  } else { ?>
		<div class="well" style="margin-top:60px;overflow:auto;">
		<?php  } ?>
<script>
    function res(){
       require(['jquery'],function($){
		var h = document.documentElement.clientHeight;
		$(".gw-container").css('min-height',h);
	});
    }
	window.onresize = res;
        res();
</script>