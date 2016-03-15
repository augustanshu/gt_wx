<?php defined('IN_IA') or exit('Access Denied');?><!--
登录首页显示-->
<div class="footer center-block">
	<div class="center-block footer" role="footer">
		<div class="text-center">
			<?php  if(empty($_W['setting']['copyright']['footerright'])) { ?>
			Powered by 宁波港通天下网络科技有限公司</a> © 2015-2016 
			<?php  } else { ?><?php  echo $_W['setting']['copyright']['footerright'];?><?php  } ?> &nbsp; &nbsp; <?php  if(!empty($_W['setting']['copyright']['statcode'])) { ?><?php  echo $_W['setting']['copyright']['statcode'];?><?php  } ?>
		</div>
	</div>
</body>

</html>