<?php
/**
 * Copyright ? 2015-2016 Port-of-World.
 *  2016-03-07
 *  index.php首次登陆判断跳转 
 */
defined('IN_IA') or exit('Access Denied');
if (!empty($_W['uid'])) {
	header('Location: '.url('account/display'));
	exit;
}
header("Location: ".url('user/login'));
exit;
