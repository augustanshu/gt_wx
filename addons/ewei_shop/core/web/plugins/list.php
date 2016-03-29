<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$plugins = m('plugin')->getAll();
	include $this->template('web/plugins/list');
	die;
}