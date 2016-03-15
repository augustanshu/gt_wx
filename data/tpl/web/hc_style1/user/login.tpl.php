<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-base', TEMPLATE_INCLUDEPATH)) : (include template('common/header-base', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .app,.app body {
	width:100%;
	height:100%;
	overflow:auto;
}
</style>
<link rel="stylesheet" href="<?php echo RES_URL;?>css/common1.css" type="text/css" />
<link rel="stylesheet" href="<?php echo RES_URL;?>css/login.css" type="text/css" />
<?php $logo = !empty($_W['setting']['copyright']['flogo'])?tomedia($_W['setting']['copyright']['flogo']):'./themes/hc_style1/style/images/gw-logo.png'?>
<div id="wrapper" class='bg-black'>
	<div class="whz-login-box">
		<div class="mod-body clearfix" style="background-color:#2b2b2b;">
                    <div class="content">
				<h1 class="logo"><div class="logo" style="width:420px;height:95px;">
		<a href="./?refresh" style="background:url('<?php  echo $logo;?>') no-repeat;width:420px;height:95px;"></a>
	</div></h1>
				<h2></h2>
                           
				<form id="login_form" method="post" onsubmit="return formcheck();" >
					<ul>
						<li>
							<input type="text" id="whz-login-user-name" class="form-control" placeholder="用户名" name="username" />
						</li>
						<li>
							<input type="password" id="whz-login-user-password" class="form-control" placeholder="密码" name="password" />
						</li>
						<li class="alert alert-danger hide error_message">
							<i class="icon icon-delete"></i> <em></em>
						</li>
						<li class="last">
							 
                                                           <input type="submit" name="submit" value="登录" class="pull-right btn btn-large btn-primary" />
							<input name="token" value="<?php  echo $_W['token'];?>" type="hidden" />
							<label><input type="checkbox" value="true" name="rember" id="remember"> 记住用户名</label>
							
						</li>
					</ul>
				</form>
			</div>
		</div>	 
		 
		 <?php  if(!empty($settings['register']['open'])) { ?><div class="mod-footer">
			<span>还没有账号?</span>&nbsp;&nbsp;
			<a href="<?php  echo url('user/register');?>">立即注册</a>&nbsp;&nbsp;&nbsp;&nbsp;
			 
		</div><?php  } ?>
            
            
	</div>
    
    <div class="whz-footer-wrap" style='margin-top:20px;'>
        <div class="whz-footer">
			<?php  if(empty($_W['setting']['copyright']['footerright'])) { ?><?php  } else { ?><?php  echo $_W['setting']['copyright']['footerright'];?><?php  } ?> &nbsp; &nbsp; <?php  if(!empty($_W['setting']['copyright']['statcode'])) { ?><?php  echo $_W['setting']['copyright']['statcode'];?><?php  } ?>
		</div>
</div>
</div>
</div>
 
<script>
function formcheck() {
	if($('#remember:checked').length == 1) {
		cookie.set('remember-username', $(':text[name="username"]').val());
	} else {
		cookie.del('remember-username');
	}
	return true;
}
$(function(){
     $("#whz-login-user-name").focus();
})
require(['jquery'],function($){
    
       
	var h = document.documentElement.clientHeight;
	$("#wrapper").css('min-height',h);
});


</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-copyright', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-copyright', TEMPLATE_INCLUDEPATH));?>