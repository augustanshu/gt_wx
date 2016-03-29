<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_GPC;

$tpl = trim($_GPC['tpl']);
load()->func('tpl');
if ($tpl == 'option') {
	$tag = random(32);
	include $this->template('web/shop/tpl/option');
} else {
	if ($tpl == 'spec') {
		$spec = array("id" => random(32), "title" => $_GPC['title']);
		include $this->template('web/shop/tpl/spec');
	} else {
		if ($tpl == 'specitem') {
			$spec = array("id" => $_GPC['specid']);
			$specitem = array("id" => random(32), "title" => $_GPC['title'], "show" => 1);
			include $this->template('web/shop/tpl/spec_item');
		} else {
			if ($tpl == 'param') {
				$tag = random(32);
				include $this->template('web/shop/tpl/param');
			}
		}
	}
}