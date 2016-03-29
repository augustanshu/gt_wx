<?php

//decode by 012wz.com QQ:800083075
if (!defined('IN_IA')) {
	die('Access Denied');
}
class CreditshopWeb extends Plugin
{
	protected $set = null;
	public function __construct()
	{
		parent::__construct('creditshop');
		$this->set = $this->getSet();
	}
	public function index()
	{
		global $_W;
		if (cv('creditshop.cover')) {
			header('location: ' . $this->createPluginWebUrl('creditshop/cover'));
			die;
		} else {
			if (cv('creditshop.goods')) {
				header('location: ' . $this->createPluginWebUrl('creditshop/goods'));
				die;
			} else {
				if (cv('creditshop.category')) {
					header('location: ' . $this->createPluginWebUrl('creditshop/category'));
					die;
				} else {
					if (cv('creditshop.adv')) {
						header('location: ' . $this->createPluginWebUrl('creditshop/adv'));
						die;
					} else {
						if (cv('creditshop.log.view0')) {
							header('location: ' . $this->createPluginWebUrl('creditshop/log', array('type' => 0)));
							die;
						} else {
							if (cv('creditshop.log.view1')) {
								header('location: ' . $this->createPluginWebUrl('creditshop/log', array('type' => 1)));
								die;
							} else {
								if (cv('creditshop.notice')) {
									header('location: ' . $this->createPluginWebUrl('creditshop/notice'));
									die;
								} else {
									if (cv('creditshop.set')) {
										header('location: ' . $this->createPluginWebUrl('creditshop/set'));
										die;
									}
								}
							}
						}
					}
				}
			}
		}
	}
	public function cover()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function category()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function goods()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function adv()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function log()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function notice()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function set()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}