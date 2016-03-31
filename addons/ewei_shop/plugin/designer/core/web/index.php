<?php

//decode by 012wz.com QQ:800083075
global $_W, $_GPC;
load()->func('tpl');

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tempdo = empty($_GPC['tempdo']) ? "" : $_GPC['tempdo'];
$pageid = empty($_GPC['pageid']) ? "" : $_GPC['pageid'];
$apido = empty($_GPC['apido']) ? "" : $_GPC['apido'];
if ($op == 'display') {
	ca('designer.page.view');
	$page = empty($_GPC['page']) ? "" : $_GPC['page'];
	$pindex = max(1, intval($page));
	$psize = 10;
	$kw = empty($_GPC['keyword']) ? "" : $_GPC['keyword'];
	$pages = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_designer') . " WHERE uniacid= :uniacid and pagename LIKE :name " . "ORDER BY savetime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $_W['uniacid'], ':name' => "%{$kw}%"));
	$pagesnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('ewei_shop_designer') . " WHERE uniacid= :uniacid " . "ORDER BY savetime DESC ", array(':uniacid' => $_W['uniacid']));
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('ewei_shop_designer') . " WHERE uniacid= :uniacid and pagename LIKE :name " . "ORDER BY savetime DESC ", array(':uniacid' => $_W['uniacid'], ':name' => "%{$kw}%"));
	$pager = pagination($total, $pindex, $psize);
} elseif ($op == 'post') {
	$pages = pdo_fetchall("SELECT id,pagename,pagetype,setdefault FROM " . tablename('ewei_shop_designer') . " WHERE uniacid= :uniacid  ", array(':uniacid' => $_W['uniacid']));
	$categorys = pdo_fetchall("SELECT id,name,parentid FROM " . tablename('ewei_shop_category') . " WHERE enabled=:enabled and uniacid= :uniacid  ", array(':uniacid' => $_W['uniacid'], ':enabled' => '1'));
	if (!empty($pageid)) {
		ca('designer.page.edit');
		$datas = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_designer') . " WHERE uniacid= :uniacid and id=:id", array(':uniacid' => $_W['uniacid'], ':id' => $pageid));
		$data = htmlspecialchars_decode($datas['datas']);
		$data = json_decode($data, true);
		if (!empty($data)) {
			foreach ($data as $i1 => &$dd) {
				if ($dd['temp'] == 'goods') {
					foreach ($dd['data'] as $i2 => &$ddd) {
						$goodinfo = pdo_fetchall("SELECT id,title,productprice,marketprice,thumb FROM " . tablename('ewei_shop_goods') . " WHERE uniacid= :uniacid and id=:goodid", array(':uniacid' => $_W['uniacid'], ':goodid' => $ddd['goodid']));
						$goodinfo = set_medias($goodinfo, 'thumb');
						if (!empty($goodinfo)) {
							$data[$i1]['data'][$i2]['name'] = $goodinfo[0]['title'];
							$data[$i1]['data'][$i2]['priceold'] = $goodinfo[0]['productprice'];
							$data[$i1]['data'][$i2]['pricenow'] = $goodinfo[0]['marketprice'];
							$data[$i1]['data'][$i2]['img'] = $goodinfo[0]['thumb'];
						}
					}
					unset($ddd);
				} elseif ($dd['temp'] == 'richtext') {
					$dd['content'] = $this->model->unescape($dd['content']);
				}
			}
			unset($dd);
			$data = json_encode($data);
		}
		$data = rtrim($data, "]");
		$data = ltrim($data, "[");
		$pageinfo = htmlspecialchars_decode($datas['pageinfo']);
		$pageinfo = rtrim($pageinfo, "]");
		$pageinfo = ltrim($pageinfo, "[");
		$shopset = m('common')->getSysset('shop');
		$system = array('shop' => array('name' => $shopset['name'], 'logo' => tomedia($shopset['logo'])));
		$system = json_encode($system);
	} else {
		ca('designer.page.edit');
		$pageinfo = "{id:'M0000000000000',temp:'topbar',params:{title:'',desc:'',img:'',kw:'',footer:'1',floatico:'0',floatstyle:'right',floatwidth:'40px',floattop:'100px',floatimg:'',floatlink:''}}";
	}
} elseif ($op == 'api') {
	if ($_W['ispost']) {
		if ($apido == 'savepage') {
			$id = $_GPC['pageid'];
			$datas = $_GPC['datas'];
			$date = date("Y-m-d H:i:s");
			$pagename = $_GPC['pagename'];
			$pagetype = $_GPC['pagetype'];
			$pageinfo = $_GPC['pageinfo'];
			$p = htmlspecialchars_decode($pageinfo);
			$p = json_decode($p, true);
			$keyword = empty($p[0]['params']['kw']) ? "" : $p[0]['params']['kw'];
			$insert = array('pagename' => $pagename, 'pagetype' => $pagetype, 'pageinfo' => $pageinfo, 'savetime' => $date, 'datas' => $datas, 'uniacid' => $_W['uniacid'], 'keyword' => $keyword);
			if (empty($id)) {
				ca('designer.page.edit');
				$insert['createtime'] = $date;
				pdo_insert('ewei_shop_designer', $insert);
				$id = pdo_insertid();
				plog('designer.page.edit', "店铺装修-添加修改页面 ID: {$id}");
			} else {
				ca('designer.page.edit');
				if ($pagetype == '4') {
					$insert['setdefault'] = '0';
				}
				pdo_update('ewei_shop_designer', $insert, array('id' => $id));
				plog('designer.page.edit', "店铺装修-修改修改页面 ID: {$id}");
			}
			$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shop', ':name' => "ewei_shop:designer:" . $id));
			if (empty($rule)) {
				$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shop:designer:' . $id, 'module' => 'ewei_shop', 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule', $rule_data);
				$rid = pdo_insertid();
				$keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'ewei_shop', 'content' => trim($keyword), 'type' => 1, 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule_keyword', $keyword_data);
			} else {
				pdo_update('rule_keyword', array('content' => trim($keyword)), array('rid' => $rule['id']));
			}
			echo $id;
			die;
		} elseif ($apido == 'delpage') {
			ca('designer.page.delete');
			if (empty($pageid)) {
				message('删除失败！Url参数错误', $this->createPluginWebUrl('designer'), 'error');
			} else {
				$page = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_designer') . " WHERE uniacid= :uniacid and id=:id", array(':uniacid' => $_W['uniacid'], ':id' => $pageid));
				if (empty($page)) {
					echo '删除失败！目标页面不存在！';
					die;
				} else {
					$do = pdo_delete('ewei_shop_designer', array('id' => $pageid));
					if ($do) {
						$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shop', ':name' => "ewei_shop:designer:" . $pageid));
						if (!empty($rule)) {
							pdo_delete('rule_keyword', array('rid' => $rule['id']));
							pdo_delete('rule', array('id' => $rule['id']));
						}
						plog('designer.page.edit', "店铺装修-修改修改页面 ID: {$pageid} 页面名称: {$page['pagename']}");
						echo 'success';
					} else {
						echo '删除失败！';
					}
				}
			}
		} elseif ($apido == 'selectgood') {
			$kw = $_GPC['kw'];
			$goods = pdo_fetchall("SELECT id,title,productprice,marketprice,thumb FROM " . tablename('ewei_shop_goods') . " WHERE uniacid= :uniacid and status=:status AND title LIKE :title ", array(':title' => "%{$kw}%", ':uniacid' => $_W['uniacid'], ':status' => '1'));
			$goods = set_medias($goods, 'thumb');
			echo json_encode($goods);
		} elseif ($apido == 'setdefault') {
			ca('designer.page.setdefault');
			$do = $_GPC['d'];
			$id = $_GPC['id'];
			$type = $_GPC['type'];
			if ($do == 'on') {
				$pages = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_designer') . " WHERE pagetype=:pagetype and setdefault=:setdefault and uniacid=:uniacid ", array(':pagetype' => $type, ':setdefault' => '1', ':uniacid' => $_W['uniacid']));
				if (!empty($pages)) {
					$array = array('setdefault' => '0');
					pdo_update('ewei_shop_designer', $array, array('id' => $pages['id']));
				}
				$array = array('setdefault' => '1');
				$action = pdo_update('ewei_shop_designer', $array, array('id' => $id));
				if ($action) {
					$json = array('result' => 'on', 'id' => $id, 'closeid' => $pages['id']);
					plog('designer.page.edit', "店铺装修-设置默认页面 ID: {$id} 页面名称: {$pages['pagename']}");
					echo json_encode($json);
				}
			} else {
				$pages = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_designer') . " WHERE  id=:id and uniacid=:uniacid ", array(':id' => $id, ':uniacid' => $_W['uniacid']));
				if ($pages['setdefault'] == 1) {
					$array = array('setdefault' => '0');
					$action = pdo_update('ewei_shop_designer', $array, array('id' => $pages['id']));
					if ($action) {
						$json = array('result' => 'off', 'id' => $pages['id']);
						plog('designer.page.edit', "店铺装修-关闭默认页面 ID: {$id} 页面名称: {$pages['pagename']}");
						echo json_encode($json);
					}
				}
			}
		} elseif ($apido == 'selectkeyword') {
			$kw = $_GPC['kw'];
			$pid = $_GPC['pid'];
			$rule = pdo_fetch("select * from " . tablename('rule_keyword') . ' where content=:content and uniacid=:uniacid and module=:module limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shop', ':content' => $kw));
			if (empty($rule)) {
				echo 'ok';
			} else {
				$rule2 = pdo_fetch("select * from " . tablename('rule') . ' where id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $rule['rid']));
				if ($rule2['name'] == 'ewei_shop:designer:' . $pid) {
					echo 'ok';
				}
			}
		} elseif ($apido == 'selectlink') {
			$type = $_GPC['type'];
			$kw = $_GPC['kw'];
			if ($type == 'notice') {
				$notices = pdo_fetchall("select * from " . tablename('ewei_shop_notice') . ' where title LIKE :title and status=:status and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':status' => '1', ':title' => "%{$kw}%"));
				echo json_encode($notices);
			} elseif ($type == 'good') {
				$goods = pdo_fetchall("select title,id,marketprice,productprice from " . tablename('ewei_shop_goods') . ' where title LIKE :title and status=:status and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':status' => '1', ':title' => "%{$kw}%"));
				echo json_encode($goods);
			} else {
				die;
			}
		}
	}
	die;
}
include $this->template('index');