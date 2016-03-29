<?php
/*
 * 人人商城
 * 
 * @author ewei www.weixin023.com*/
if(!defined('IN_IA')) {
     exit('Access Denied');
}
require IA_ROOT. '/addons/ewei_shop/version.php';
require IA_ROOT. '/addons/ewei_shop/defines.php';
require EWEI_SHOP_INC.'functions.php'; 
require EWEI_SHOP_INC.'core.php';
require EWEI_SHOP_INC.'plugin/plugin.php';
require EWEI_SHOP_INC.'plugin/plugin_model.php';
class Ewei_shopModuleSite extends Core { 
     
    //商城管理 
    public function doWebShop(){ $this->_exec(__FUNCTION__ ,'goods'); }
    //订单管理 
    public function doWebOrder(){ $this->_exec(__FUNCTION__,'list'); }
    //会员管理
    public function doWebMember(){ $this->_exec(__FUNCTION__,'list'); }
    //财务管理
    public function doWebFinance(){ $this->_exec(__FUNCTION__,'log'); }
    //统计分析
    public function doWebStatistics(){ $this->_exec(__FUNCTION__,'sale'); }
    //插件管理
    public function doWebPlugins(){ $this->_exec(__FUNCTION__,'list'); }
	//
	public function doWebPlugCreditshop(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'creditshop'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开积分商城!',$url,'seccess');
	}
	public function doWebPlugDesigner(){
		//http://v888.v-888.com/web/index.php?c=site&a=entry&p=designer&do=plugin&m=ewei_shop
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'designer'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;
		message('正在打开店铺装修!',$url,'seccess');
	}
	public function doWebPlugSale(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'sale'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开营销宝!',$url,'seccess');
	}
	public function doWebPlugPerm(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'perm'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开分权系统!',$url,'seccess');
	}	
	public function doWebPlugTmessage(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'tmessage'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开会员群发!',$url,'seccess');
	}
	public function doWebPlugVerify(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'verify'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开O2O核销!',$url,'seccess');
	}
	public function doWebPlugPoster(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'poster'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开超级海报!',$url,'seccess');
	}
	public function doWebPlugCommission(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'commission'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开人人分销!',$url,'seccess');
	}
	public function doWebPlugTaobao(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'taobao'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开淘宝助手!',$url,'seccess');
	}
	public function doWebPlugqiniu(){ 
		$url=$this->createWebUrl('plugin', array('c' => 'site','a' => 'entry','p' => 'qiniu'));
		header("Location: ".$url); 
		//确保重定向后，后续代码不会被执行 
		exit;		
		message('正在打开七牛存储!',$url,'seccess');
	}
    //系统设置 
    public function doWebSysset(){ $this->_exec(__FUNCTION__,'sysset'); } 
    //插件web入口  
    public function doWebPlugin(){   
        global $_W,$_GPC;
        require_once EWEI_SHOP_INC."plugin/plugin.php";
        $plugins = m('plugin')->getAll(); 
        $p = $_GPC['p']; 
        $file = EWEI_SHOP_PLUGIN.$p."/web.php";
        if(!is_file($file)){ 
            message('未找到插件 '.$plugins[$p].' 入口方法');
        }
        require $file;
        $pluginClass = ucfirst($p)."Web";
        $plug = new $pluginClass($p);
        $method =  strtolower($_GPC['method']);
        if(empty($method)){
           $plug->index();    
           exit;
        }
        if(method_exists($plug,$method)){
            $plug->$method();
            exit;
        }
        trigger_error('Plugin Web Method '.$method.' not Found!');
    }
    //插件app入口
    public function doMobilePlugin(){ 
        global $_W,$_GPC;
        require_once EWEI_SHOP_INC."plugin/plugin.php";
        $plugins = m('plugin')->getAll();
        $p = $_GPC['p'];
        $file = EWEI_SHOP_PLUGIN.$p."/mobile.php";
 
        if(!is_file($file)){
            message('未找到插件 '.$plugins[$p].' 入口方法');
        }
        require $file;
        $pluginClass = ucfirst($p)."Mobile";
        $plug = new $pluginClass($p);
        $method =  strtolower($_GPC['method']);
        if(empty($method)){
           $plug->index();    
           exit;
        }
        if(method_exists($plug,$method)){
            $plug->$method();
            exit;
        }
        trigger_error('Plugin Mobile Method '.$method.' not Found!');
    }
    //购物车入口
    public function doMobileCart(){ $this->_exec('doMobileShop','cart',false); }
    //我的收藏入口
    public function doMobileFavorite(){ $this->_exec('doMobileShop','favorite',false); }
    //工具
    public function doMobileUtil(){ $this->_exec(__FUNCTION__,'',false); }
    //会员
    public function doMobileMember(){ $this->_exec(__FUNCTION__,'center',false); }
    //商城
    public function doMobileShop(){ $this->_exec(__FUNCTION__,'index',false); }
    //订单
    public function doMobileOrder(){ $this->_exec(__FUNCTION__,'list',false); }
    //支付成功
    public function payResult($params){  m('order')->payResult($params); }
    public function getAuthSet() {
        global $_W;
        $set = pdo_fetch('select sets from ' . tablename('ewei_shop_sysset') . ' order by id asc  limit 1');
        $sets = iunserializer($set['sets']);
        if (is_array($sets)) {
            return is_array($sets['auth']) ? $sets['auth'] : array();
        }
        return array();
    }
    public function doWebAuth() {$this->_exec('doWebSysset','auth',true);  }
    public function doWebUpgrade() {$this->_exec('doWebSysset','upgrade',true);   }

}