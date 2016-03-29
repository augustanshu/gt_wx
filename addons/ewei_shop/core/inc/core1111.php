<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
?>
<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Core extends WeModuleSite {
    public $footer = array();
    public $header = null;
    public function __construct() {
        check_shop_auth();
        if (is_weixin()) {
            m('member')->checkMember();
        }
    }
    public function setHeader() {
        global $_W, $_GPC;
        $zym_16 = m('user')->getOpenid();
        $zym_15 = m('user')->followed($zym_16);
        @session_start();
        if (!$zym_15) {
            $zym_14 = intval($_GPC['mid']);
            $zym_18 = m('common')->getSysset();
            $this->header = array(
                'url' => $zym_18['share']['followurl']
            );
            $zym_19 = false;
            if (!empty($zym_14)) {
                if (!empty($_SESSION[EWEI_SHOP_PREFIX . '_shareid']) && $_SESSION[EWEI_SHOP_PREFIX . '_shareid'] == $zym_14) {
                    $zym_14 = $_SESSION[EWEI_SHOP_PREFIX . '_shareid'];
                }
                $zym_24 = m('member')->getMember($zym_14);
                if (!empty($zym_24)) {
                    $_SESSION[EWEI_SHOP_PREFIX . '_shareid'] = $zym_14;
                    $zym_19 = true;
                    $this->header['icon'] = $zym_24['avatar'];
                    $this->header['text'] = '来自好友 <span>' . $zym_24['nickname'] . '</span> 的推荐';
                }
            }
            if (!$zym_19) {
                $this->header['icon'] = tomedia($zym_18['shop']['logo']);
                $this->header['text'] = '欢迎进入 <span>' . $zym_18['shop']['name'] . '</span>';
            }
        }
    }
    public function setFooter() {
        global $_GPC;
        $zym_14 = intval($_GPC['mid']);
        $this->footer['first'] = array(
            'text' => '首页',
            'ico' => 'home',
            'url' => $this->createMobileUrl('shop')
        );
        $this->footer['second'] = array(
            'text' => '分类',
            'ico' => 'list',
            'url' => $this->createMobileUrl('shop/category')
        );
        $zym_16 = m('user')->getOpenid();
        if (p('commission')) {
            $zym_18 = p('commission')->getSet();
            if (empty($zym_18['level'])) {
                return;
            }
            $zym_24 = m('member')->getMember($zym_16);
            $zym_22 = $zym_24['isagent'] == 1 && $zym_24['status'] == 1;
            if ($_GPC['do'] == 'plugin') {
                $this->footer['first'] = array(
                    'text' => '小店',
                    'ico' => 'home',
                    'url' => $this->createPluginMobileUrl('commission/myshop', array(
                        'mid' => $zym_24['id']
                    ))
                );
                if ($_GPC['method'] == '') {
                    $this->footer['first']['text'] = '我的小店';
                }
                $this->footer['second'] = array(
                    'text' => '分销中心',
                    'ico' => 'sitemap',
                    'url' => $this->createPluginMobileUrl('commission')
                );
            } else {
                if (!$zym_22) {
                    $this->footer['second'] = array(
                        'text' => '成为分销商',
                        'ico' => 'sitemap',
                        'url' => $this->createPluginMobileUrl('commission/register')
                    );
                } else {
                    $this->footer['second'] = array(
                        'text' => '小店',
                        'ico' => 'heart',
                        'url' => $this->createPluginMobileUrl('commission/myshop', array(
                            'mid' => $zym_24['mid']
                        ))
                    );
                }
            }
        }
    }
    public function createMobileUrl($zym_21, $zym_20 = array() , $zym_13 = true) {
        global $_W, $_GPC;
        $zym_21 = explode('/', $zym_21);
        if (isset($zym_21[1])) {
            $zym_20 = array_merge(array(
                'p' => $zym_21[1]
            ) , $zym_20);
        }
        if (empty($zym_20['mid'])) {
            $zym_14 = intval($_GPC['mid']);
            if (!empty($zym_14)) {
                $zym_20['mid'] = $zym_14;
            }
        }
        return $_W['siteroot'] . 'app/' . substr(parent::createMobileUrl($zym_21[0], $zym_20, true) , 2);
    }
    public function createWebUrl($zym_21, $zym_20 = array()) {
        global $_W;
        $zym_21 = explode('/', $zym_21);
        if (count($zym_21) > 1 && isset($zym_21[1])) {
            $zym_20 = array_merge(array(
                'p' => $zym_21[1]
            ) , $zym_20);
        }
        return $_W['siteroot'] . 'web/' . substr(parent::createWebUrl($zym_21[0], $zym_20, true) , 2);
    }
    public function createPluginMobileUrl($zym_21, $zym_20 = array()) {
        global $_W, $_GPC;
        $zym_21 = explode('/', $zym_21);
        $zym_20 = array_merge(array(
            'p' => $zym_21[0]
        ) , $zym_20);
        $zym_20['m'] = 'ewei_shop';
        if (isset($zym_21[1])) {
            $zym_20 = array_merge(array(
                'method' => $zym_21[1]
            ) , $zym_20);
        }
        if (isset($zym_21[2])) {
            $zym_20 = array_merge(array(
                'op' => $zym_21[2]
            ) , $zym_20);
        }
        if (empty($zym_20['mid'])) {
            $zym_14 = intval($_GPC['mid']);
            if (!empty($zym_14)) {
                $zym_20['mid'] = $zym_14;
            }
        }
        return $_W['siteroot'] . 'app/' . substr(parent::createMobileUrl('plugin', $zym_20, true) , 2);
    }
    public function createPluginWebUrl($zym_21, $zym_20 = array()) {
        global $_W;
        $zym_21 = explode('/', $zym_21);
        $zym_20 = array_merge(array(
            'p' => $zym_21[0]
        ) , $zym_20);
        if (isset($zym_21[1])) {
            $zym_20 = array_merge(array(
                'method' => $zym_21[1]
            ) , $zym_20);
        }
        if (isset($zym_21[2])) {
            $zym_20 = array_merge(array(
                'op' => $zym_21[2]
            ) , $zym_20);
        }
        return $_W['siteroot'] . 'web/' . substr(parent::createWebUrl('plugin', $zym_20, true) , 2);
    }
    public function _exec($zym_21, $zym_12 = '', $zym_5 = true) {
        global $_GPC;
        $zym_21 = strtolower(substr($zym_21, $zym_5 ? 5 : 8));
        $zym_4 = trim($_GPC['p']);
        empty($zym_4) && $zym_4 = $zym_12;
        if ($zym_5) {
            $zym_3 = IA_ROOT . '/addons/ewei_shop/core/web/' . $zym_21 . '/' . $zym_4 . '.php';
        } else {
            $this->setFooter();
            $zym_3 = IA_ROOT . '/addons/ewei_shop/core/mobile/' . $zym_21 . '/' . $zym_4 . '.php';
        }
        if (!is_file($zym_3)) {
            message("未找到 控制器文件 {$zym_21}::{$zym_4} : {$zym_3}");
        }
        include $zym_3;
        exit;
    }
    public function template($zym_1, $zym_2 = TEMPLATE_INCLUDEPATH) {
        global $_W;
        $zym_6 = strtolower($this->modulename);
        if (defined('IN_SYS')) {
            $zym_7 = IA_ROOT . "/web/themes/{$_W['template']}/{$zym_6}/{$zym_1}.html";
            $zym_11 = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$zym_6}/{$zym_1}.tpl.php";
            if (!is_file($zym_7)) {
                $zym_7 = IA_ROOT . "/web/themes/default/{$zym_6}/{$zym_1}.html";
            }
            if (!is_file($zym_7)) {
                $zym_7 = IA_ROOT . "/addons/{$zym_6}/template/{$zym_1}.html";
            }
            if (!is_file($zym_7)) {
                $zym_7 = IA_ROOT . "/web/themes/{$_W['template']}/{$zym_1}.html";
            }
            if (!is_file($zym_7)) {
                $zym_7 = IA_ROOT . "/web/themes/default/{$zym_1}.html";
            }
            if (!is_file($zym_7)) {
                $zym_10 = explode('/', $zym_1);
                $zym_9 = array_slice($zym_10, 1);
                $zym_7 = IA_ROOT . "/addons/{$zym_6}/plugin/" . $zym_10[0] . '/template/' . implode('/', $zym_9) . '.html';
            }
        } else {
            $zym_8 = 'default';
            $zym_3 = IA_ROOT . '/addons/ewei_shop/data/template/shop_' . $_W['uniacid'];
            if (is_file($zym_3)) {
                $zym_8 = file_get_contents($zym_3);
                if (!is_dir(IA_ROOT . '/addons/ewei_shop/template/mobile/' . $zym_8)) {
                    $zym_8 = 'default';
                }
            }
            $zym_11 = IA_ROOT . "/data/tpl/app/ewei_shop/{$zym_8}/mobile/{$zym_1}.tpl.php";
            $zym_7 = IA_ROOT . "/addons/{$zym_6}/template/mobile/{$zym_8}/{$zym_1}.html";
            if (!is_file($zym_7)) {
                $zym_7 = IA_ROOT . "/addons/{$zym_6}/template/mobile/default/{$zym_1}.html";
            }
            if (!is_file($zym_7)) {
                $zym_7 = IA_ROOT . "/app/themes/{$_W['template']}/{$zym_1}.html";
            }
            if (!is_file($zym_7)) {
                $zym_7 = IA_ROOT . "/app/themes/default/{$zym_1}.html";
            }
        }
        if (!is_file($zym_7)) {
            exit("Error: template source '{$zym_1}' is not exist!");
        }
        if (DEVELOPMENT || !is_file($zym_11) || filemtime($zym_7) > filemtime($zym_11)) {
            template_compile($zym_7, $zym_11, true);
        }
        return $zym_11;
    }
    public function getUrl() {
        if (p('commission')) {
            $zym_18 = p('commission')->getSet();
            if (!empty($zym_18['level'])) {
                return $this->createPluginMobileUrl('commission/myshop');
            }
        }
        return $this->createMobileUrl('shop');
    }
} ?>
