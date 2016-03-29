<?php

//decode by 012wz.com QQ:800083075
if (!defined('IN_IA')) {
	die('Access Denied');
}
if (!class_exists('DesignerModel')) {
	class DesignerModel extends PluginModel
	{
		public function getPage($type = 1)
		{
			global $_W, $_GPC;
			$page = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_designer') . " WHERE uniacid= :uniacid and pagetype=:type and setdefault=:default", array(':uniacid' => $_W['uniacid'], ':type' => $type, ':default' => '1'));
			if (empty($page)) {
				return false;
			}
			return $this->getData($page);
		}
		public function change(&$d, $cdata)
		{
			$d[$b['k1']][$b['k2']]['name'] = $cdata['title'];
			$d[$b['k1']][$b['k2']]['priceold'] = $cdata['productprice'];
			$d[$b['k1']][$b['k2']]['pricenow'] = $cdata['marketprice'];
			$d[$b['k1']][$b['k2']]['img'] = $cdata['thumb'];
		}
		public function getData($page)
		{
			global $_W;
			$data = htmlspecialchars_decode($page['datas']);
			$d = json_decode($data, true);
			$goodsids = array();
			foreach ($d as $k1 => &$dd) {
				if ($dd['temp'] == 'goods') {
					foreach ($dd['data'] as $k2 => $ddd) {
						$goodsids[] = array('id' => $ddd['goodid'], 'k1' => $k1, 'k2' => $k2);
					}
				} elseif ($dd['temp'] == 'richtext') {
					$dd['content'] = $this->unescape($dd['content']);
				}
			}
			unset($dd);
			$arr = array();
			foreach ($goodsids as $a) {
				$arr[] = $a['id'];
			}
			if (count($arr) > 0) {
				$goodinfos = pdo_fetchall("SELECT id,title,productprice,marketprice,thumb FROM " . tablename('ewei_shop_goods') . " WHERE id in ( " . implode(',', $arr) . ") and uniacid= :uniacid ", array(':uniacid' => $_W['uniacid']), 'id');
				$goodinfos = set_medias($goodinfos, 'thumb');
				foreach ($d as $k1 => &$dd) {
					if ($dd['temp'] == 'goods') {
						foreach ($dd['data'] as $k2 => &$ddd) {
							$cdata = $goodinfos[$ddd['goodid']];
							$ddd['name'] = $cdata['title'];
							$ddd['priceold'] = $cdata['productprice'];
							$ddd['pricenow'] = $cdata['marketprice'];
							$ddd['img'] = $cdata['thumb'];
						}
						unset($ddd);
					}
				}
				unset($dd);
			}
			$data = json_encode($d);
			$data = rtrim($data, "]");
			$data = ltrim($data, "[");
			$pageinfo = htmlspecialchars_decode($page['pageinfo']);
			$p = json_decode($pageinfo, true);
			$page_title = empty($p[0]['params']['title']) ? "未设置页面标题" : $p[0]['params']['title'];
			$page_desc = empty($p[0]['params']['desc']) ? "未设置页面简介" : $p[0]['params']['desc'];
			$page_img = empty($p[0]['params']['img']) ? "" : tomedia($p[0]['params']['img']);
			$page_keyword = empty($p[0]['params']['kw']) ? "" : $p[0]['params']['kw'];
			$shopset = m('common')->getSysset(array('shop', 'share'));
			$system = $shopset;
			$system['shop'] = set_medias($system['shop'], 'logo');
			$system = json_encode($system);
			$pageinfo = rtrim($pageinfo, "]");
			$pageinfo = ltrim($pageinfo, "[");
			return array('page' => $page, 'pageinfo' => $pageinfo, 'data' => $data, 'share' => array('title' => $page_title, 'desc' => $page_desc, 'imgUrl' => $page_img), 'footermenu' => intval($p[0]['params']['footer']), 'system' => $system);
		}
		public function unescape($str)
		{
			$ret = '';
			$len = strlen($str);
			for ($i = 0; $i < $len; $i++) {
				if ($str[$i] == '%' && $str[$i + 1] == 'u') {
					$val = hexdec(substr($str, $i + 2, 4));
					if ($val < 127) {
						$ret .= chr($val);
					} else {
						if ($val < 2048) {
							$ret .= chr(192 | $val >> 6) . chr(128 | $val & 63);
						} else {
							$ret .= chr(224 | $val >> 12) . chr(128 | $val >> 6 & 63) . chr(128 | $val & 63);
						}
					}
					$i += 5;
				} else {
					if ($str[$i] == '%') {
						$ret .= urldecode(substr($str, $i, 3));
						$i += 2;
					} else {
						$ret .= $str[$i];
					}
				}
			}
			return $ret;
		}
		public function getGuide($system, $pageinfo)
		{
			global $_W, $_GPC;
			if (!empty($_GPC['preview'])) {
				$guide['followed'] = '0';
			} else {
				$guide['openid2'] = m('user')->getOpenid();
				$guide['followed'] = m('user')->followed($guide['openid2']);
			}
			if ($guide['followed'] != '1') {
				$system = json_decode($system, true);
				$system['shop'] = set_medias($system['shop'], 'logo');
				$pageinfo = json_decode($pageinfo, true);
				if (!empty($_GPC['mid'])) {
					$guide['member1'] = pdo_fetch("SELECT id,nickname,openid,avatar FROM " . tablename('ewei_shop_member') . " WHERE id=:mid and uniacid= :uniacid limit 1 ", array(':uniacid' => $_W['uniacid'], ':mid' => $_GPC['mid']));
					$guide['member2'] = pdo_fetch("SELECT id,nickname,openid FROM " . tablename('ewei_shop_member') . " WHERE openid=:openid and uniacid= :uniacid limit 1 ", array(':uniacid' => $_W['uniacid'], ':openid' => $guide['openid2']));
				}
				$guide['followurl'] = $system['share']['followurl'];
				if (empty($guide['member1'])) {
					$guide['title1'] = $pageinfo['params']['guidetitle1'];
					$guide['title2'] = $pageinfo['params']['guidetitle2'];
					$guide['logo'] = $system['shop']['logo'];
				} else {
					$pageinfo['params']['guidetitle1s'] = str_replace("[邀请人]", $guide['member1']['nickname'], $pageinfo['params']['guidetitle1s']);
					$pageinfo['params']['guidetitle2s'] = str_replace("[邀请人]", $guide['member1']['nickname'], $pageinfo['params']['guidetitle2s']);
					$pageinfo['params']['guidetitle1s'] = str_replace("[访问者]", $guide['member2']['nickname'], $pageinfo['params']['guidetitle1s']);
					$pageinfo['params']['guidetitle2s'] = str_replace("[访问者]", $guide['member2']['nickname'], $pageinfo['params']['guidetitle2s']);
					$guide['title1'] = $pageinfo['params']['guidetitle1s'];
					$guide['title2'] = $pageinfo['params']['guidetitle2s'];
					$guide['logo'] = $guide['member1']['avatar'];
				}
			}
			return $guide;
		}
		public function perms()
		{
			return array('designer' => array('text' => $this->getName(), 'isplugin' => true, 'child' => array('page' => array('text' => '页面设置', 'view' => '浏览', 'edit' => '添加修改-log', 'delete' => '删除-log', 'setdefault' => '设置默认-log'))));
		}
	}
}