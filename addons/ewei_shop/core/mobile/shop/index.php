<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
$openid = m('user')->getOpenid();
$uniacid = $_W['uniacid'];
$designer = p('designer');
if ($designer) {
	$pagedata = $designer->getPage();
	if ($pagedata) {
		extract($pagedata);
		$guide = $designer->getGuide($system, $pageinfo);
		$_W['shopshare'] = array('title' => $share['title'], 'imgUrl' => $share['imgUrl'], 'desc' => $share['desc'], 'link' => $this->createMobileUrl('shop'));
		if (p('commission')) {
			$set = p('commission')->getSet();
			if (!empty($set['level'])) {
				$member = m('member')->getMember($openid);
				if (!empty($member) && $member['status'] == 1 && $member['isagent'] == 1) {
					$_W['shopshare']['link'] = $this->createMobileUrl('shop', array('mid' => $member['id']));
					if (empty($set['become_reg']) && (empty($member['realname']) || empty($member['mobile']))) {
						$trigger = true;
					}
				} else {
					if (!empty($_GPC['mid'])) {
						$_W['shopshare']['link'] = $this->createMobileUrl('shop', array('mid' => $_GPC['mid']));
					}
				}
			}
		}
		include $this->template('shop/index_diy');
		die;
	}
}
$set = set_medias(m('common')->getSysset('shop'), array('logo', 'img'));
if ($_W['isajax']) {
	if ($operation == 'index') {
		$advs = pdo_fetchall("select id,advname,link,thumb from " . tablename('ewei_shop_adv') . ' where uniacid=:uniacid and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));
		$advs = set_medias($advs, 'thumb');
		show_json(1, array('set' => $set, 'advs' => $advs));
	} else {
		if ($operation == 'goods') {
			$type = $_GPC['type'];
			$args = array('page' => $_GPC['page'], 'pagesize' => 6, 'isrecommand' => 1, 'order' => 'displayorder desc,createtime desc', 'by' => '');
			$goods = m('goods')->getList($args);
			show_json(1, array('goods' => $goods, 'pagesize' => $args['pagesize']));
		}
	}
}
$this->setHeader();
include $this->template('shop/index');