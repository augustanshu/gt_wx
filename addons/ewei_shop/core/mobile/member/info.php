<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$member = m('member')->getInfo($openid);
if ($_W['isajax']) {
	if ($_W['ispost']) {
		$memberdata = $_GPC['memberdata'];
		pdo_update('ewei_shop_member', $memberdata, array('openid' => $openid, 'uniacid' => $_W['uniacid']));
		if (!empty($member['uid'])) {
			$mcdata = $_GPC['mcdata'];
			load()->model('mc');
			mc_update($member['uid'], $mcdata);
		}
		show_json(1);
	}
	show_json(1, array('member' => $member));
}
include $this->template('member/info');