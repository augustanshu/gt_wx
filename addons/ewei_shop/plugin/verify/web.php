<?php

//微讯 by QQ:6841611 http://www.weixin023.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
class VerifyWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('verify');
	}
	public function index()
	{
		global $_W;
		if (cv('verify.keyword')) {
			header('location: ' . $this->createPluginWebUrl('verify/keyword'));
			die;
		} else {
			if (cv('verify.saler')) {
				header('location: ' . $this->createPluginWebUrl('verify/saler'));
				die;
			} else {
				if (cv('verify.store')) {
					header('location: ' . $this->createPluginWebUrl('verify/store'));
					die;
				}
			}
		}
	}
	public function keyword()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function saler()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function store()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}